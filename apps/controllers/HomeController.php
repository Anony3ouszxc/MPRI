<?php
namespace Apps\Controllers;

use Cygnite\Foundation\Application;
use Cygnite\Mvc\Controller\AbstractBaseController;
use Cygnite\Translation\Translator;
use Apps\Models\Menu;
use Apps\Models\Slider;
use Apps\Models\Banner;
use Apps\Models\Post;
use Apps\Models\Page;
use Apps\Models\Files;
use Apps\Models\User;
use Apps\Models\Category;
use Apps\Models\Logs;

use Cygnite\Common\Input\CookieManager;
use Cygnite\Common\UrlManager\Url;
use Cygnite\Helpers\Config;
use Cygnite\Common\Input\Input;

use Array2XML;

class HomeController extends AbstractBaseController
{
    /**
     * --------------------------------------------------------------------------
     * The Default Controller
     * --------------------------------------------------------------------------
     *  This controller respond to uri beginning with home and also
     *  respond to root url like "home/index"
     *
     * Your GET request of "home/index" will respond like below -
     *
     *      public function indexAction()
     *     {
     *            echo "Cygnite : Hellow ! World ";
     *     }
     * Note: By default cygnite doesn't allow you to pass query string in url, which
     * consider as bad url format.
     *
     * You can also pass parameters into the function as below-
     * Your request to  "home/form/2134" will pass to
     *
     *      public function formAction($id = ")
     *      {
     *             echo "Cygnite : Your user Id is $id";
     *      }
     * In case if you are not able to access parameters passed into method
     * directly as above, you can also get the uri segment
     *  echo Url::segment(3);
     *
     * That's it you are ready to start your awesome application with Cygnite Framework.
     *
     */

    protected $layout = 'layout.home';

    protected $templateEngine = false;

    // protected $templateExtension = '.html.twig';

    //protected $autoReload = true;
    /*
     * Your constructor.
     * @access public
     *
     */
    public function __construct() {
        parent::__construct();
        $this->_initCookie();
        $this->_initTranslator();

        // set Global parameter
        $this->params = Config::get( 'global.config', 'params' );
        $this->action = '';
        $this->title  = '';
        # prepare data header
        $this->subtitle = $this->trans->get( 'subtitle_home_page', 'en' );
        $this->slider   = Slider::all();
        $this->menu     = Menu::findByLevel( '1' );
        $this->baseUrl  = Url::getBase();
        $this->appLogo  = $this->params['logo'];
    }

    private function _initCookie() {
        if ( !isset( $_COOKIE['cms_lang'] ) ) {
            setcookie('cms_lang', 'th', time() + (60 * 60 * 24), "/");
        }
    }

    private function _initTranslator() {
        $trans = new Translator();
        $trans->setRootDirectory( LANGPATH );
        // $locale = $trans->locale();
        $trans->setLangDir( 'admin' );
        if( !isset($_COOKIE['cms_lang']) ){
            $this->_initCookie();
        }
        $lang = $_COOKIE['cms_lang'];
        $trans->locale( $lang );// en,th
        $this->trans = $trans;
        $this->lang  = $lang;
    }

    private function _countVisit()
    {
        $logs = Logs::findByLogsType('visit');
        if( !$logs->count() ){
            $logs = new Logs();
            $logs->logs_type = 'visit';
            $logs->message = '1';
            $logs->user_id   = 0;
        } else {
            $logs = Logs::find( $logs->offsetGet(0)->logs_id );
            $logs->message = ($logs->attributes['message']*1)+1;
        }
        $logs->save();
    }

    protected function thai_date( $date )
    {
        $thai_month_arr_short=array(
            "0"=>"",
            "1"=>"ม.ค.",
            "2"=>"ก.พ.",
            "3"=>"มี.ค.",
            "4"=>"เม.ย.",
            "5"=>"พ.ค.",
            "6"=>"มิ.ย.",
            "7"=>"ก.ค.",
            "8"=>"ส.ค.",
            "9"=>"ก.ย.",
            "10"=>"ต.ค.",
            "11"=>"พ.ย.",
            "12"=>"ธ.ค."
        );
        $time = strtotime($date);
        $thai_date_return  = date("j",$time);
        $thai_date_return .= "&nbsp;&nbsp;".$thai_month_arr_short[date("n",$time)];
        $thai_date_return .=  " ".(date("Y",$time)+543);
        return $thai_date_return;
    }

    /**
     * Default method for your controller. Render welcome page to user.
     *
     * @access public
     *
     */
    public function indexAction() {
        $this->_countVisit();
        $banner   = new Banner();
        $post     = new Post();
        $calendar = Logs::findBySql('select embed from embed where code=\'calendar\'');
        $poll     = Logs::findBySql('select embed from embed where code=\'poll\'');
        $stats    = Logs::findBySql('select embed from embed where code=\'stats\'');
        $data     = array(
            // 'title'    => $this->trans->get( 'title_home_page', 'th' ),
            // 'subtitle' => $this->trans->get( 'subtitle_home_page', 'en' ),
            'postList' => $post->prepareData(),
            'slider'   => Slider::all( array( 'orderBy' => 'priority asc') ),
            'banner'   => $banner->prepareBanner()
        );

        $this->render( 'index', $data );
    }

    public function postGroupAction( $groupId ) {
        $postModel = new Post();
        $record    = $postModel->getDataByCategory( $groupId, 9);
        if( !$record['data']->count() ){
            $this->render('error', [ 'title' => $this->trans->get('page_not_exists') ] );
        } else {
            $category  = Category::find($groupId);
            $catname   = 'name_'.$this->lang;
            $groupName = urldecode($category->attributes[$catname]);
            if( $category->attributes['file_id'] ){
                $file  = Files::find($category->attributes['file_id']);
                $image = $this->baseUrl . $file->attributes['filepath'];
            } else {
                $image = '';
            }
            $data      = array(
                'title'      => $groupName,
                'category'   => $category->attributes,
                'record'     => $record['data'],
                'pagination' => $record['pagination'],
                'noSlider'   => 1,
                'topData'    => ['title' => $groupName, 'image' => $image ]
            );
            $this->render( 'post-group', $data );
        }

    }

    public function postViewAction( $postId ) {
        $post = Post::find($postId);
        $file = (object) array( 'attributes' => '' );
        $pdf = (object) array( 'attributes' => '' );
        if( $post->attributes['file_id'] ){
            $file = Files::find( $post->attributes['file_id'] );
        }
        if( $post->attributes['pdf_id'] ){
            $pdf  = Files::find( $post->attributes['pdf_id'] );
        }
        $previousId = Post::findBySql('select post_id from post where post_id=(select max(post_id) from post where post_id<'.$postId.')');
        $nextId     = Post::findBySql('select post_id from post where post_id=(select min(post_id) from post where post_id>'.$postId.')');
        $category   = Category::find($post->attributes['category_id']);
        if( !empty($category->attributes['file_id']) ){
            $file  = Files::find($category->attributes['file_id']);
            $image = $this->baseUrl . $file->attributes['filepath'];
        } else {
            $image = '';
        }
        $data = array(
            'title'      => $post->attributes['title'],
            'category'   => isset($category->attributes['name_'.$this->lang])
                            ?$category->attributes['name_'.$this->lang]
                            :'',
            'post'       => $post->attributes,
            'file'       => $file->attributes,
            'pdf'        => $pdf->attributes,
            'previousId' => is_object($previousId->offsetGet(0)) ? $previousId->offsetGet(0)->post_id : null,
            'nextId'     => is_object($nextId->offsetGet(0)) ? $nextId->offsetGet(0)->post_id : null,
            'noSlider'   => 1,
            'topData'    => ['title' => $post->attributes['title'], 'image' => $image ],
            'fb_data'    => ['title' => $post->attributes['title'], 'description' => '', 'image'=> $file->attributes['filepath'], 'url' => Url::getBase() . "post-view/" . $postId ]
        );
        $post->view = $post->attributes['view']+1;
        $post->save();
        $this->render( 'post-view', $data );
    }

    public function pageViewAction( $pageId ){
        $page          = Page::find($pageId);
        $page->view    = $page->attributes['view']+1;
        $page->save();
        if( $page->attributes['file_id'] ){
            $file  = Files::find($page->attributes['file_id']);
            $image = $this->baseUrl . $file->attributes['filepath'];
        } else {
            $image = '';
        }
        $pdffile = '';
        if( $page->attributes['pdf_id'] ){
            $pdf  = Files::find($page->attributes['pdf_id']);
            if( file_exists($pdf->attributes['filepath']) ){
                $pdffile = $pdf->attributes['filepath'];
            }
        }
        $data = array(
            'title'    => $page->attributes['title'],
            'content'  => unserialize($page->attributes['content']),
            'custom_css' => $page->attributes['container_class'],
            'noSlider' => 1,
            'topData'  => ['title' => $page->attributes['title'], 'image' => $image ],
            'pdffile'  => $pdffile
        );
        $this->render( 'page-view', $data );
    }

    public function countShareAction(){
        $input = Input::make();
        $data = array(
            'success' => 0
        );
        if( $input->hasPost('id') && $input->hasPost('type') ){
            $post = Post::find( $input->post('id') );
            if( $post->attributes['post_id'] ){
                $type  = $input->post('type');
                $count = $post->attributes[$type];
                $count++;
                $post->$type = $count;
                $post->save();
                $data['success'] = 1;
                $data[$type]   = $count;
            }
        }

        echo json_encode($data);
    }

    public function searchAction(){
        $input = Input::make();
        $data  = [ 'title' => $this->trans->get('search_result'), 'record' => [] ];
        if( $input->hasPost('keyword') ){
            $keyword = $input->post('keyword');

            $logs = new Logs();
            $logs->message = (string) $keyword;
            $logs->logs_type = 'keyword';
            $logs->save();

            $User    = new User();
            $record  = $User->search( $keyword, 12 );
            $data['record']     = $record['data'];
            $data['pagination'] = $record['pagination'];
        }
        $this->render( 'search', $data );
    }

    public function rssXmlAction()
    {

        $category = new Category;
        $postList = Category::findBySql('select title,detail,filepath,name_th as category_th,name_en as category_en
            from category
            join post using(category_id)
            join files on post.file_id=files.files_id
            where rss=\'on\'');
        foreach( $postList as $row ){
            $record = $row->attributes;
            $record['filepath'] = $this->baseUrl . $record['filepath'];
            $data['post'][] = $record;
        }
        $xml      = Array2XML::createXML('root', $data);
        header('Content-type: text/xml');
        echo $xml->saveXML();
    }

}//End of your home controller
