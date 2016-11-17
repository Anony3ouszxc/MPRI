<?php
namespace Apps\Controllers;

use Cygnite\Foundation\Application;
use Cygnite\Mvc\Controller\AbstractBaseController;
use Cygnite\Common\Input\Input;
use Application\Components\Authentication\Auth;
use Cygnite\Common\UrlManager\Url;
use Cygnite\Helpers\Config;
use Cygnite\Translation\Translator;
use Cygnite\Common\Encrypt;
use Apps\Models\Logs;
use Apps\Models\User;
use Apps\Models\Role;
use Apps\Models\Slider;
use Apps\Models\Banner;
use Apps\Models\Files;
use Apps\Models\Menu;
use Apps\Models\Post;
use Apps\Models\Page;
use Apps\Models\Embed;
use Apps\Models\Category;

use Cygnite\Common\File\Thumbnail;
use Cygnite\Common\File\Upload\Upload;
use Cygnite\Common\Security;

class AdminController extends AbstractBaseController
{

	protected $layout = 'layout.cms';

	protected $templateEngine = false;

	// protected $templateExtension = '.html.twig';

	protected $autoReload = true;
	/*
     * Your constructor.
     * @access public
     *
     */
	public function __construct( Security $security ) {
		parent::__construct();
		$this->security = $security;
		$this->auth     = Auth::model( '\Apps\Models\User' );
		$this->baseUrl  = Url::getBase();
		// set Global parameter
		$this->params = Config::get( 'global.config', 'params' );
		$this->mode   = Config::get( 'global.config', 'environment' );
		// set default lang
		$this->_initCookie();
		// set language translator
		$this->_initTranslator();
		$this->isAdmin  = 0;
		$this->action   = '';
		$this->title    = '';
		$this->table    = '';
		$this->editLink = '';
		$this->keyword  = !empty($_GET['keyword'])? $_GET['keyword']:'';

		if ( $this->auth->isLoggedIn() || !empty( $_SESSION['auth:user']['isLoggedIn'] ) ) {
			$this->isLogged = true;
			$this->userInfo = $_SESSION['auth:user'];
			$this->_initTable();
			$this->_initRole();
		} else {
			$this->isLogged = false;
			// force to login page
			if ( (Url::segment( $this->params['start_segment'] + 1 ) != 'backend' && Url::segment( $this->params['start_segment'] + 1 ) != 'api')
				|| ( Url::segment( $this->params['start_segment'] + 1 ) =='admin' && Url::segment( $this->params['start_segment'] + 2 ) != 'login' )
			) {
				$this->redirectTo( 'backend' );
			}
		}
	}

	private function _initRole()
	{
		if( $this->userInfo['user_id'] == 0 ){
			$this->isAdmin    = 1;
			$this->roleAccess = '';
			$this->avatar     = array('thumbpath' => 'assets/avatar/blank.jpg');
		}else{
			$role             = Role::find($this->userInfo['role_id']);
			$this->roleAccess = unserialize($role->attributes['role_config']);
			$this->avatar     = !empty($this->userInfo['file_id'])
							    ? Files::find($this->userInfo['file_id'])
							    : array('thumbpath' => 'assets/avatar/blank.jpg');
		}
		if( !$this->isAdmin && !$this->checkAccess() ){
			$this->redirectTo( 'admin/forbidden' );
			exit;
		}
	}

	protected function checkAccess()
	{
		$allow = 1;
		switch( Url::segment( $this->params['start_segment'] + 1 ) ){
			case 'user':
				if( in_array(Url::segment( $this->params['start_segment'] + 2 ),[ 'all-user', 'new-user','edit-user' ]) && empty($this->roleAccess['user_see']) ){
					$allow = 0;
				} elseif( in_array(Url::segment( $this->params['start_segment'] + 2 ),[ 'all-role', 'new-role','edit-role' ]) && empty($this->roleAccess['role_see']) ){
					$allow = 0;
				}
			break;
			case 'page':
				if( in_array(Url::segment( $this->params['start_segment'] + 2 ),[ 'all-page', 'new-page','edit-page' ]) && empty($this->roleAccess['page_see']) ){
					$allow = 0;
				}
			break;
			case 'post':
				if( in_array(Url::segment( $this->params['start_segment'] + 2 ),[ 'all-post', 'new-post','edit-post','category-post' ]) && empty($this->roleAccess['post_see']) ){
					$allow = 0;
				}
			break;
			case 'module':
				if( in_array(Url::segment( $this->params['start_segment'] + 2 ),[ 'all-banner', 'new-banner', 'edit-banner' ]) && empty($this->roleAccess['banner_see']) ){
					$allow = 0;
				} elseif( in_array(Url::segment( $this->params['start_segment'] + 2 ),[ 'all-slider', 'new-slider','edit-slider' ]) && empty($this->roleAccess['slider_see']) ){
					$allow = 0;
				}
			break;
		}

		return $allow;
	}

	private function _initTable()
	{
		switch( Url::segment( $this->params['start_segment'] + 1 ) ){
			case 'user':
				$this->table = Url::segment( $this->params['start_segment'] + 2 ) == 'all-user' ? 'user' : 'role';
			break;
			case 'page':
				$this->table = 'page';
			break;
			case 'post':
				switch(Url::segment( $this->params['start_segment'] + 2 )){
					case 'category-post':
						$this->table = 'category';
					break;
					default:
						$this->table = 'post';
					break;
				}
			break;
			case 'module':
				switch(Url::segment( $this->params['start_segment'] + 2 )){
					case 'all-banner':
						$this->table = 'banner';
					break;
					case 'category-module':
						$this->table = 'category';
					break;
					case 'all-slider':
						$this->table = 'slider';
					break;
				}
			break;
			case 'main':
				switch(Url::segment( $this->params['start_segment'] + 2 )){
					case 'map-menu':
						$this->table = 'menu';
					break;
				}
			break;
		}
	}

	private function _initCookie()
    {
        if(!isset($_COOKIE['cms_lang'])){
       		setcookie('cms_lang', 'th', time() + (60 * 60 * 24), "/");
        }
    }

	private function _initTranslator() {
		$trans = new Translator();
		$trans->setRootDirectory( LANGPATH );
		// $locale = $trans->locale();
		$trans->setLangDir( 'admin' );
		$lang = $_COOKIE['cms_lang'];
		$this->lang = $lang;
		$trans->locale( $lang );// en,th
		$this->trans = $trans;
	}

	public function indexAction() {
		$this->render( 'index' );
	}

	public function mainAction( $action ) {
		$data = array(
			'isMain' => 1,
			'action' => $action,
			'title'  => 'Menu Management'
		);
		if ( !in_array( $action, ['menu', 'map-menu','sort_menu', 'update-menu'] ) ) {
			$this->redirectTo( 'admin/error' );
		} else {
			switch( $action ){
				case "menu":
					$data['title'] = $this->trans->get('manage_menu');
				break;
				case "map-menu":
					$data['title']    = $this->trans->get('map_menu');
					$data['showSave'] = 1;
				break;
				case 'update-menu':
					$this->updateMenu();
				break;
			}
			$this->render( 'main/' . $action, $data );
		}
	}

	protected function updateMenu()
	{
		$input = Input::make();
		if( $input->isAjax() && $input->hasPost('data') ){
			$data   = $input->post('data');
			if(!empty($data['menu'])){
				$menu   = $data['menu'];
				foreach( $menu as $menuId => $pageId ){

					$update = Menu::find( $menuId );

					if( substr($pageId,0,5) == 'post-'){
						$update->post_id = substr($pageId,5);
						$update->page_id = 0;
						$update->category_id = 0;
					} elseif( substr($pageId,0,5) == 'page-' ){
						$update->page_id = substr($pageId,5);
						$update->post_id = 0;
						$update->category_id = 0;
					} elseif( substr($pageId,0,9) == 'category-' ){
						$update->category_id = substr($pageId,9);
						$update->post_id = 0;
						$update->page_id = 0;
					} elseif( substr($pageId,0,7) == 'http://' ){
						$update->url     = $pageId;
						$update->post_id = 0;
						$update->page_id = 0;
						$update->category_id = 0;
					} elseif( substr($pageId,0,8) == 'https://' ){
						$update->url     = $pageId;
						$update->post_id = 0;
						$update->page_id = 0;
						$update->category_id = 0;
					}

					$update->save();
					$this->log('activity', 'update mapping menu id : ' . $menuId . ', page id : ' . $pageId );
				}
			}
			if(!empty($data['name_en'])){
				$nameEn = $data['name_en'];
				foreach( $nameEn as $menuId => $_name ){
					$update = Menu::find( $menuId );
					$update->name_en = $_name;
					$update->save();
					$this->log('activity', 'update mapping menu id : ' . $menuId . ', set name en : : ' . $_name );
				}
			}

			$rs = array(
				'success' => 1,
				'message' => $this->trans->get('update_success')
			);

			echo json_encode($rs);
		} else {
			$this->redirectTo('');
		}

		exit;
	}

	public function apiAction( $action ) {
		$input = Input::make();
		$data  = [];
		if ( !in_array( $action, ['nodeMenu','nodeCreate','nodeUpdate','nodeMove','nodeDelete','delete'] ) ) {
			$data = [];
		} else {
			switch ( $action ) {
				case 'nodeMenu':
					if ( $input->hasPost( 'level' ) ) {
						$level  = $input->post( 'level' );
						$nodeId = $input->post( 'id' );

						$childMenu = Menu::findByParentId( $nodeId );
						if( is_object($childMenu->offsetGet(0)) ){
							foreach( $childMenu as $_menu ){
								$data['nodes'][] = array( 'id' => $_menu->menu_id, 'name' => $_menu->name, 'level' => $_menu->level, 'type' => 'child' );
							}
						} else {
							$data['nodes'] = [];
						}

					} else {
						$data = array(
							'nodes' => [
								array( 'id' => 0, 'name' => 'Root', 'level' => 0, 'type' => 'default' )
							]
						);
					}
				break;
				case 'nodeCreate':
					if( $input->hasPost( 'parent' )
						&& $input->hasPost( 'name' )
						&& $input->hasPost('position')
						&& $input->hasPost('related')
					){
						$parent   = $input->post( 'parent' );
						$name     = $input->post( 'name' );
						$position = $input->post( 'position' );
						$related  = $input->post( 'related' );

						$menu   = new Menu();
						$lastId = $menu->getLastId();
						$level  = ($parent == 0) ? 1 : $menu->getNextLevel( $parent );

						$menu->menu_id      = $lastId;
						$menu->name         = $name;
						$menu->parent_id    = $parent;
						$menu->user_id      = $this->userInfo['user_id'];
						$menu->created_date = date('Y-m-d H:i:s');
						$menu->level        = $level;
						$menu->save();

						$this->log( 'activity', 'create new menu : ' . $name);

						$data = array( 'id' => $lastId, 'name' => $name, 'level' => $level, 'type' => 'child' );

					}
				break;
				case 'nodeUpdate':
					if( $_GET['id'] && $input->hasPost('name') ){
						$update  = Menu::find( $_GET['id'] );
						$oldname = $update->attributes['name'];
						$update->name = $input->post('name');
						$update->save();
						$data = array(
							'id'    => $update->attributes['menu_id'],
							'name'  => $update->attributes['name'],
							'level' => $update->attributes['level'],
							'type'  => 'child'
						);
						$this->log( 'activity', 'update menu name `' . $oldname . '` to `' . $update->attributes['name'] . '`');
					}
				break;
				case 'nodeMove':
					if( $input->hasPost('id') && $input->hasPost('related') ){
						$from     = $input->post('id');
						$to       = $input->post('related');
						$position = $input->post('position');
						$menu     = new Menu();
						$menu->updatePriority($from, $to, $position);
					}
					$data = true;
				break;
				case 'nodeDelete':
					if( $input->hasPost('id') ){
						$cmenu = Menu::find($input->post('id'));
						$menu = new Menu();
						$menu->trash( $input->post('id') );
						$this->log('activity', 'delete menu name `'.$cmenu->attributes['name'].'`');
					}
					$data = true;
				break;
				case 'delete':
					if( $input->hasPost('table') && $input->hasPost('id') ){
						$table  = ( strtolower( (string) $input->post('table') ) );
						$id     = (int) $input->post('id');
						// $class  = new $table;
						// $record = call_user_func_array( array($class,'find'), array( $id ) );
						if( $table == 'page' ){
							$record = Page::find($id);
							$class  = new Page();
						} elseif( $table == 'user' ){
							$record = User::find($id);
							$class  = new User();
						} elseif( $table == 'post' ){
							$record = Post::find($id);
							$class  = new Post();
						} elseif( $table == 'role' ){
							$record = Role::find($id);
							$class  = new Role();
						} elseif( $table == 'banner' ){
							$record = Banner::find($id);
							$class  = new Banner();
						} elseif( $table == 'slider' ){
							$record = Slider::find($id);
							$class  = new Slider();
						} elseif( $table == 'category' ){
							$record = Category::find($id);
							$class  = new Category();
						}
						if( is_object( $record ) && $id ){
							$class->trash( $id );
							$this->log('activity', 'delete record ' . $id . ' from table ' . $table );
							$data = array(
								'success' => 1,
								'message' => $this->trans->get('delete_success')
							);
						} else {
							$data = array(
								'success' => 0,
								'message' => $this->trans->get('delete_fail')
							);
						}

					} else {
						$data = array(
							'success' => 0,
							'message' => $this->trans->get('delete_fail')
						);
					}
				break;
			}
		}
		echo json_encode( $data );
	}

	public function loginAction() {
		$input = Input::make();
		$directTo = 'backend';

		if ( $input->hasPost( 'data' ) ) {

			$data        = $input->post( 'data' );
			$crypt       = new Encrypt();
			$credentials = array(
				'email'    => $data['email'],
				'password' => $crypt->encode( $data['password'] )
			);

			if( ($credentials['email'] == $this->params['admin_email'])
				&& ($credentials['password'] == $this->params['admin_password']) )
			{
				$_SESSION['auth:user'] = array(
					'isLoggedIn' => true,
					'user_id'    => 0,
					'firstname'  => 'Auiz',
					'lastname'   => 'Developer',
					'email'      => $this->params['admin_email'],
					'username'   => 'developer'
				);
				$directTo = 'admin/dashboard';
			} elseif ( $this->auth->verify( $credentials ) ) {
				$this->auth->login();
				$status = true;
				$directTo = 'admin/dashboard';

				$this->auth->isLoggedIn();
				$this->isLogged = true;
				$this->userInfo = $_SESSION['auth:user'];
				// logs activity
				$this->log('login');
			}
		}
		$this->redirectTo( $directTo );
	}

	public function logoutAction() {
		$this->auth->logout( false );
	}

	public function dashboardAction() {
		$logs     = new Logs();
		$logged   = $logs->getLogs( 'login', array( 'limit' => 5, 'orderBy' => 'ts desc' ) );
		$activity = $logs->getLogs( 'activity', array( 'limit' => 5, 'orderBy' => 'ts desc' ) );
		$publish  = $logs->getLogs( 'publish', array( 'limit' => 5, 'orderBy' => 'ts desc' ) );
		$data     = array(
			'isDashboard' => 1,
			'title'       => 'Dashboard',
			'logged'      => $logged,
			'activity'    => $activity,
			'publish'     => $publish,
			'count'       => array(
				'login'    => Logs::findByLogsType('login')->count(),
				'activity' => Logs::findByLogsType('activity')->count(),
				'publish'  => Logs::findByLogsType('publish')->count()
			)
		);
		$this->render( 'dashboard', $data );
	}

	public function userAction( $action ) {
		$data = array(
			'isUser'   => 1,
			'action'   => $action,
			'title'    => 'User Management'
		);
		if ( !in_array( $action, ['all-user', 'new-user', 'all-role', 'new-role', 'profile', 'add-user', 'add-role', 'update-profile','edit-user','edit-role','update-user', 'update-role'] ) ) {
			$this->redirectTo( 'admin/error' );
		} else {
			$template = $action;
			switch ( $action ) {
				case 'all-user':
					$userModel          = new User();
					$record 			= $userModel->getList( $this->keyword );
					$data['title']      = $this->trans->get('title_all_user');
					$data['userList']   = $record['data'];
					$data['pagination'] = $record['pagination'];
				break;
				case 'new-user':
					$data['title'] = $this->trans->get('title_new_user');
					$data['roleList'] = Role::all();
				break;
				case 'add-user':
					$this->addUser();
				break;
				case 'edit-user':
					$template         = 'new-user';
					$data['roleList'] = Role::all();
					$id               = Url::segment( $this->params['start_segment'] + 3);
					$data['record']   = User::find($id);

				break;
				case 'all-role':
					$data['title']      = $this->trans->get('all_role');
					$roleModel          = new Role();
					$record             = $roleModel->getList($this->keyword);
					$data['roleList']   = $record['data'];
					$data['pagination'] = $record['pagination'];
					$data['authList']   = array(
						'department'  => $this->trans->get( 'role_work_unit' ),
						'ceo'         => $this->trans->get( 'role_ceo' ),
						'advertise'   => $this->trans->get( 'role_advertise' ),
						'knowledge'   => $this->trans->get( 'role_knowledge' ),
						'service'     => $this->trans->get( 'role_service' )
					);
				break;
				case 'update-profile':
					$id = Url::segment( $this->params['start_segment'] + 3);
					$this->updateProfile( $id );
				break;
				case 'update-user':
					$id = Url::segment( $this->params['start_segment'] + 3);
					$this->updateUser( $id );
				break;
				case 'new-role':
					$data['title']    = $this->trans->get('title_new_role');
					foreach( $this->params['menu'] as $code ){
						$menuList[ $code ] = $this->trans->get( $code );
					}
					$data['menuList'] = $menuList;
				break;
				case 'edit-role':
					$template       = 'new-role';
					$id             = Url::segment( $this->params['start_segment'] + 3);
					$data['record'] = Role::find($id);
					foreach( $this->params['menu'] as $code ){
						$menuList[ $code ] = $this->trans->get( $code );
					}
					$data['menuList'] = $menuList;
				break;
				case 'add-role':
					$this->saveRole();
				break;
				case 'update-role':
					$id = Url::segment( $this->params['start_segment'] + 3);
					$this->saveRole( $id );
				break;
				case 'profile':
					$data['title']    = $this->trans->get('title_profile');
					$data['showSave'] = 1;
				break;
			}
			$this->render( 'user/' . $template, $data );
		}
	}

	protected function updateUser($id){
		$input = Input::make();
		if( $id && $input->hasPost('data') ){
			$data = $input->post('data');
			$user = User::find($id);

			$user->firstname = $data["firstname"];
			$user->lastname  = $data["lastname"];
			$user->email     = $data["email"];
			$user->role_id   = $data["role_id"];
			if( !empty( $data['password'] ) ){
				$crypt = new Encrypt();
				$user->password  = $crypt->encode( $data["password"] );
			}
			$user->save();
			$this->log( 'activity', 'update user information id : ' . $id );
		}
		$this->redirectTo('user/all-user');
		exit;
	}

	protected function updateProfile(){
		$input = Input::make();
		$file  = $_FILES['fileinput'];
		if( /*$input->isAjax() &&*/ $input->hasPost('data') ){
			if( !$file['error'] ){
				$fileId = Files::getLastId();
				$fileId++;
				$files  = new Files();

				$this->checkDir( $this->params['avatar_path'] );
				$filename   = str_replace( " ", '_', $this->security->sanitize( $file['name'] ) );
				$filename   = Files::convertFilename( $filename );
				$targetPath = $this->params['avatar_path'] . DS . $filename;
				move_uploaded_file( $file['tmp_name'], $targetPath );
				// create thumb section
				$thumbPath = realpath($this->params['avatar_path']) . DS . 'thumb' . DS;
				$this->checkDir( $thumbPath );
				$savePath  = $this->params['avatar_path'] . DS . 'thumb' . DS . 'thumbnail-' . $filename;
				// create thumnail 260x260
				$this->createThumbnail($targetPath, $thumbPath, 32, 32);
				$files->files_id = $fileId;
				$files->filename = $filename;
				$files->filepath = $targetPath;
				$files->thumbpath = $savePath;
				$files->user_id  = $this->userInfo['user_id'];
				$files->save();
			}
			$data   = $input->post('data');
			$crypt  = new Encrypt();
			// $oldPwd = $crypt->encode( $data['old_password'] );

			// if( $this->userInfo['password'] != $oldPwd ){
			// 	echo json_encode([
			// 		'success' => 0,
			// 		'message' => $this->trans->get('old_password_incorrect'),
			// 		'oldPassword' => $this->userInfo['password'],
			// 		'chkPassword' => $oldPwd
			// 	]);
			// } else {
				$user = User::find( $this->userInfo['user_id'] );
				$user->firstname = $data["firstname"];
				$user->lastname  = $data["lastname"];
				$user->email     = $data["email"];
				if( !empty($fileId) ){
					$user->file_id = $fileId;
				}
				if( $data['password'] ){
					$user->password  = $crypt->encode( $data["password"] );
				}
				$user->save();
				# update profile in session
				$credentials = array(
					'email'    => $this->userInfo['email'],
					'password' => $this->userInfo['password']
				);
				$this->auth->logout( false );
				if ( $this->auth->verify( $credentials ) ) {
					$this->auth->login();
				}

				$this->log( 'activity', 'update profile : ' . $this->userInfo['username'] );
				// echo json_encode([ 'success' => 1, 'message' => $this->trans->get('update_success') ]);
			// }
		}
		$this->redirectTo('user/profile');
		exit;
	}

	protected function addUser() {
		$input = Input::make();
		$data  = $input->post( 'data' );
		if ( !empty( $data ) && !empty( $data['username'] ) ) {
			$user = User::findByUsername( $data['username'] );
			if ( !is_object( $user->offsetGet( 0 ) ) ) {
				$user  = new User();
				$crypt = new Encrypt();
				$user->username = $data["username"];
				$user->password = $crypt->encode( $data["password"] );
				$user->firstname = $data["firstname"];
				$user->lastname  = $data["lastname"];
				$user->email     = $data["email"];
				$user->role_id   = $data['role_id'];
				$user->save();
				$this->log( 'activity', 'create new user : ' . $data['username'] );
			}
		}
		$this->redirectTo( 'user/all-user' );
		exit;
	}

	protected function saveRole( $id="" ) {
		$input = Input::make();
		$data  = $input->post( 'data' );
		if ( !empty( $data ) && !empty( $data['role_name'] ) ) {
			if( !$id ){
				$role = Role::findBySql( "select * from role where role_name='".$data['role_name']."'" );
			}
			if ( $id || !is_object( $role->offsetGet( 0 ) ) ) {
				if( $id ){
					$role = Role::find($id);
				} else {
					$role  = new Role();
				}

				$role->role_name = $data["role_name"];
				$role->role_config = serialize($data['role_config']);
				$role->save();
				if( $id ){
					$this->log( 'activity', 'update role id: ' . $id );
				} else {
					$this->log( 'activity', 'create new role : ' . $data['role_name'] );
				}
			}
		}
		$this->redirectTo( 'user/all-role' );
		exit;
	}

	public function postAction( $action ) {
		$data = array(
			'isPost'   => 1,
			'action'   => $action,
			'title'    => 'Post Management'
		);
		if ( !in_array( $action, ['all-post', 'new-post', 'add-post', 'edit-post', 'update-post','category-post', 'form-category', 'add-category','edit-category', 'update-category'] ) ) {
			$this->redirectTo( 'admin/error' );
		} else {
			$template = $action;
			switch( $action ){
				case "all-post":
					$keyword            = $this->keyword;
					$data['title']      = $this->trans->get('title_all_post');
					$postModel          = new Post();
					$record             = $postModel->getList($keyword);
					$data['postList']   = $record['data'];
					$data['pagination'] = $record['pagination'];
					// $data['postList']   = Post::all();
					// $data['pagination'] = Post::createLinks();
					// $data['pagination']->setPerPage(5);
				break;
				case "new-post":
					$data['title']          = $this->trans->get('title_new_post');
					$data['categoryListTH'] = array('ข่าวประชาสัมพันธ์','ข่าวประกาศของหน่วยงาน','เครือข่าย DSMC');
					$data['categoryListEN'] = array('Press Release','News Agency','DMSC network');
				break;
				case "add-post":
					$this->savePost();
				break;
				case 'edit-post':
					$template       = 'new-post';
					$id             = Url::segment( $this->params['start_segment'] + 3);
					$data['record'] = Post::find($id);
					$data['categoryListTH'] = array('ข่าวประชาสัมพันธ์','ข่าวประกาศของหน่วยงาน','เครือข่าย DSMC');
					$data['categoryListEN'] = array('Press Release','News Agency','DMSC network');
				break;
				case 'update-post':
					$id = Url::segment( $this->params['start_segment'] + 3);
					$this->savePost( $id );
				break;
				case 'category-post':
					$data['title']        = $this->trans->get('title_all_category');
					$categoryModel 		  = new Category();
					$record 			  = $categoryModel->getList( 'post', 5, $this->keyword);
					$data['categoryList'] = $record['data'];
					$data['pagination']   = $record['pagination'];
				break;
				case 'form-category':
					$data['title'] = $this->trans->get('title_new_category');
				break;
				case 'edit-category':
					$id = Url::segment( $this->params['start_segment'] + 3);
					$data['record'] = Category::find( $id );
					$template = 'form-category';
				break;
				case 'add-category':
					$this->saveCategory();
				break;
				case 'update-category':
					$id = Url::segment( $this->params['start_segment'] + 3);
					$this->saveCategory( $id );
				break;
			}
			$this->render( 'post/' . $template, $data );
		}
	}

	protected function saveCategory( $id, $type="post")
	{
		$input = Input::make();
		$data  = $input->post( 'data' );
		$file  = $_FILES['fileinput'];
		if ( !empty( $data ) ) {
			if( !empty($file) && !$file['error'] ){
				$fileId = Files::getLastId();
				$fileId++;
				$files  = new Files();

				$this->checkDir( $this->params['category_path'], 'category');
				$filename   = str_replace( " ", '_', $this->security->sanitize( $file['name'] ) );
				$filename   = Files::convertFilename( $filename );
				$targetPath = $this->params['category_path'] . DS . $filename;
				move_uploaded_file( $file['tmp_name'], $targetPath );
				// create thumb section
				// $thumbPath = realpath($this->params['category_path']) . DS . 'thumb' . DS;
				// $this->checkDir( $thumbPath );
				// $savePath  = $this->params['category_path'] . DS . 'thumb' . DS . 'thumbnail-' . $filename;
				// // create thumnail 260x260
				// $this->createThumbnail($targetPath, $thumbPath, 260, 260);
				$files->files_id = $fileId;
				$files->filename = $filename;
				$files->filepath = $targetPath;
				// $files->thumbpath = $savePath;
				$files->user_id  = $this->userInfo['user_id'];
				$files->save();
			}
			if( $id ){
				$category = Category::find( $id );
			} else {
				$category = new Category();
			}
			$category->name_en         = $data['name_en'];
			$category->name_th         = $data['name_th'];
			$category->priority        = $data['priority'];
			$category->top_desc        = $data['top_desc'];
			$category->bottom_desc     = $data['bottom_desc'];
			$category->rss             = isset($data['rss'])? 'on':'off';
			$category->user_id         = $this->userInfo['user_id'];
			$category->type            = $type;
			$category->container_class = $data['container_class'];
			if( $fileId ){ $category->file_id = $fileId; }
			$category->save();

			$this->log( 'activity', 'create new category : ' . $data['name_th'] );
			if( $type == 'post'){
				$this->redirectTo( 'post/category-post' );
			} elseif( $type == 'module' ) {
				$this->redirectTo( 'module/category-module' );
			}
		}
		exit;
	}

	protected function savePost( $id )
	{
		$input = Input::make();
		$data  = $input->post( 'data' );
		$file  = $_FILES['fileinput'];
		$pdf   = $_FILES['pdfinput'];
		if ( !empty( $data ) ) {
			if( !$file['error'] ){
				$fileId = Files::getLastId();
				$fileId++;
				$files  = new Files();

				$this->checkDir( $this->params['post_path'] );
				$filename   = str_replace( " ", '_', $this->security->sanitize( $file['name'] ) );
				$filename   = Files::convertFilename( $filename );
				$targetPath = $this->params['post_path'] . DS . $filename;
				move_uploaded_file( $file['tmp_name'], $targetPath );
				// create thumb section
				$thumbPath = realpath($this->params['post_path']) . DS . 'thumb' . DS;
				$this->checkDir( $thumbPath );
				$savePath  = $this->params['post_path'] . DS . 'thumb' . DS . 'thumbnail-' . $filename;
				// create thumnail 260x260
				$this->createThumbnail($targetPath, $thumbPath, 260, 260);
				$files->files_id = $fileId;
				$files->filename = $filename;
				$files->filepath = $targetPath;
				$files->thumbpath = $savePath;
				$files->user_id  = $this->userInfo['user_id'];
				$files->save();
			}

			if( !$pdf['error'] ){
				$pdfId = Files::getLastId();
				$pdfId++;
				$files  = new Files();

				$this->checkDir( $this->params['post_path'] );
				$filename   = str_replace( " ", '_', $this->security->sanitize( $pdf['name'] ) );
				$filename   = Files::convertFilename( $filename );
				$targetPath = $this->params['post_path'] . DS . $filename;
				move_uploaded_file( $pdf['tmp_name'], $targetPath );

				$files->files_id = $pdfId;
				$files->filename = $filename;
				$files->filepath = $targetPath;
				$files->user_id  = $this->userInfo['user_id'];
				$files->save();
			}

			if( $id ){
				$post = Post::find( $id );
			} else {
				$post = new Post();
			}

			$post->title           = $data['title'];
			$post->detail          = $data['detail'];
			$post->category_id     = $data['category_id'];
			$post->priority        = $data['priority'];
			$post->user_id         = $this->userInfo['user_id'];
			$post->publish         = !empty($data['publish'])?'on':'off';
			$post->publish_start   = $data['publish_start'];
			$post->publish_end     = $data['publish_end'];
			$post->last_publish    = date('Y-m-d H:i:s');
			$post->container_class = $data['container_class'];
			if( $fileId ){ $post->file_id = $fileId; }
			if( $pdfId ){ $post->pdf_id = $pdfId; }
			$post->save();

			$this->log( 'activity', 'create new post : ' . $data['title'] );
			$this->redirectTo( 'post/all-post' );
		}
		exit;
	}

	public function pageAction( $action ) {

		$data = array(
			'isPage'   => 1,
			'action'   => $action,
			'title'    => 'Page Management'
		);
		if ( !in_array( $action, ['all-page', 'new-page','add-page', 'edit-page', 'delete-page', 'update-page'] ) ) {
			$this->redirectTo( 'admin/error' );
		} else {
			$template = $action;
			switch( $action ){
				case "all-page":
					$keyword            = $this->keyword;
					$data['title']      = $this->trans->get('title_all_page');
					$pageModel          = new Page();
					$record             = $pageModel->getList($keyword);
					$data['pageList']   = $record['data'];
					$data['pagination'] = $record['pagination'];
					// $data['pageList']   = Page::all();
					// $data['pagination'] = Page::createLinks();
				break;
				case "add-page":
					$data['title'] = $this->trans->get('title_new_page');
					$this->savePage();
				break;
				case 'edit-page':
					$template       = 'new-page';
					$id             = Url::segment( $this->params['start_segment'] + 3);
					$data['record'] = Page::find( $id );
				break;
				case 'update-page':
					$id = Url::segment( $this->params['start_segment'] + 3);
					$this->savePage( $id );
				break;
				case 'delete-page':
					$this->deletePage();
				break;
			}
			$this->render( 'page/' . $template, $data );
		}
	}

	protected function deletePage()
	{
		$input = Input::make();
		if( $input->isAjax() && $input->hasPost('id') ){

		}
		echo json_encode([ 'success' => 1, 'message' => $this->trans->get('delete_success') ]);
		exit;
	}

	protected function savePage( $id = '' )
	{
		$input = Input::make();
		$file  = $_FILES['fileinput'];
		$pdf   = $_FILES['pdfinput'];
		if( $input->hasPost('data') ){
			if( !$file['error'] ){
				$fileId = Files::getLastId();
				$fileId++;
				$files  = new Files();

				$this->checkDir( $this->params['page_path'] );
				$filename   = str_replace( " ", '_', $this->security->sanitize( $file['name'] ) );
				$filename   = Files::convertFilename( $filename );
				$targetPath = $this->params['page_path'] . DS . $filename;
				move_uploaded_file( $file['tmp_name'], $targetPath );
				// create thumb section
				$thumbPath = realpath($this->params['page_path']) . DS . 'thumb' . DS;
				$this->checkDir( $thumbPath );
				$savePath  = $this->params['page_path'] . DS . 'thumb' . DS . 'thumbnail-' . $filename;
				// create thumnail 260x260
				$this->createThumbnail($targetPath, $thumbPath, 260, 260);
				$files->files_id = $fileId;
				$files->filename = $filename;
				$files->filepath = $targetPath;
				$files->thumbpath = $savePath;
				$files->user_id  = $this->userInfo['user_id'];
				$files->save();
			}

			if( !$pdf['error'] ){
				$pdfId = Files::getLastId();
				$pdfId++;
				$files  = new Files();

				$this->checkDir( $this->params['post_path'] );
				$filename   = str_replace( " ", '_', $this->security->sanitize( $pdf['name'] ) );
				$filename   = Files::convertFilename( $filename );
				$targetPath = $this->params['post_path'] . DS . $filename;
				move_uploaded_file( $pdf['tmp_name'], $targetPath );

				$files->files_id = $pdfId;
				$files->filename = $filename;
				$files->filepath = $targetPath;
				$files->user_id  = $this->userInfo['user_id'];
				$files->save();
			}

			$data = $input->post('data');
			if( $id ){
				$page = Page::find($id);
			} else {
				$page = new Page();
				$page->user_id = $this->userInfo['user_id'];
				$page->created_date = date('Y-m-d H:i:s');
			}
			$page->title           = $data['title'];
			$page->publish         = !empty($data['publish'])?'on':'off';
			$page->publish_start   = $data['publish_start'];
			$page->publish_end     = $data['publish_end'];
			$page->last_publish    = date('Y-m-d H:i:s');
			$page->container_class = $data['container_class'];
			if( !empty($data['content']) ){
				$page->content = serialize($data['content']);
			}
			if( $fileId ){ $page->file_id = $fileId; }
			if( $pdfId ){ $page->pdf_id = $pdfId; }
			$page->save();
			if( $id ){
				$this->log( 'activity', 'update page id : ' . $id );
			} else {
				$this->log( 'activity', 'create new page : ' . $data['title'] );
			}
		}
		$this->redirectTo( 'page/all-page' );
		exit;
	}

	public function settingAction( $action ) {
		$data = array(
			'isSetting' => 1,
			'title'     => 'Setting'
		);
		$this->render( 'setting/' . $action, $data );
	}

	public function reportAction( $action ) {
		$data = array(
			'isReport' => 1,
			'title'    => 'Report',
			'action'   => $action,
			'report'   => array(
				[
					[
						'title'    => $this->trans->get('title_post'),
						'subtitle' => $this->trans->get('top10_post_edit'),
						'count'    => Post::all()->count(),
						'list'     => Post::findBySql('select title,ts from post order by ts desc limit 10')
					],
					[
						'title'    => $this->trans->get('title_page'),
						'subtitle' => $this->trans->get('top10_page_edit'),
						'count'    => Page::all()->count(),
						'list'     => Page::findBySql('select title,ts from page order by ts desc limit 10')
					],
					[
						'title'    => $this->trans->get('title_user'),
						'subtitle' => $this->trans->get('top10_user_edit'),
						'count'    => User::all()->count(),
						'list'     => User::findBySql('select firstname as title,ts from user order by ts desc limit 10')
					]
				],
				[
					[
						'title'    => $this->trans->get('title_user'),
						'subtitle' => $this->trans->get('top10_user_login'),
						'count'    => Logs::findByLogsType('login')->count(),
						'list'     => Logs::findBySql('select distinct message as title,user_id,ts from logs where logs_type=\'login\' limit 10')
					],
					[
						'title'    => $this->trans->get('title_post'),
						'subtitle' => $this->trans->get('top10_post_viewer'),
						'count'    => Post::findBySql('select 1 from post where view>0')->count(),
						'list'     => Post::findBySql('select title,ts, view as count from post where view>0 order by view desc limit 10')
					],
					[
						'title'    => $this->trans->get('title_page'),
						'subtitle' => $this->trans->get('top10_page_viewer'),
						'count'    => Page::findBySql('select 1 from page where view>0')->count(),
						'list'     => Page::findBySql('select title,ts,view as count from page where view>0 order by view desc limit 10')
					]
				],
				[
					[
						'title'    => $this->trans->get('title_post'),
						'subtitle' => $this->trans->get('top10_post_share'),
						'count'    => Post::findBySql('select 1 from post where share>0')->count(),
						'list'     => Post::findBySql('select title,ts,share as count from post where share>0 order by share desc limit 10')
					],
					[
						'title'    => $this->trans->get('title_search'),
						'subtitle' => $this->trans->get('top10_keyword_search'),
						'count'    => Logs::findByLogsType('keyword')->count(),
						'list'     => Logs::findBySql('select message as title,max(ts) as ts,count(1) as count from logs where logs_type=\'keyword\' group by message limit 10')
					],
				]
			)
		);
		$this->render( 'report/' . $action, $data );
	}

	public function moduleAction( $action ) {
		$data = array(
			'isModule' => 1,
			'title'    => 'Module',
			'action'   => $action
		);
		if ( !in_array( $action, ['all-banner', 'new-banner', 'all-slider', 'new-slider', 'calendar', 'add-banner', 'add-slider', 'edit-banner','edit-slider','update-banner','update-slider','poll','stats','save-calendar','save-stats','save-poll','category-module', 'form-category', 'add-category','edit-category', 'update-category'] ) ) {
			$this->redirectTo( 'admin/error' );
		} else {
			$template = $action;
			switch ( $action ) {
				case 'all-banner':
					$data['title']      = $this->trans->get('title_all_banner');
					$bannerModel        = new Banner();
					$record             = $bannerModel->getList($this->keyword);
					$data['bannerList'] = $record['data'];
					$data['pagination'] = $record['pagination'];
				break;
				case 'new-banner':
					$data['title']          = $this->trans->get('title_new_banner');
					$data['categoryListEN'] = Banner::findBySql( "select distinct category_en from banner" );
					$data['categoryListTH'] = Banner::findBySql( "select distinct category_th from banner" );
				break;
				case 'add-banner':
					$this->saveBanner();
				break;
				case 'edit-banner':
					$template       = 'new-banner';
					$id             = Url::segment( $this->params['start_segment'] + 3);
					$data['record'] = Banner::find( $id );
					$data['categoryListEN'] = Banner::findBySql( "select distinct category_en from banner" );
					$data['categoryListTH'] = Banner::findBySql( "select distinct category_th from banner" );
				break;
				case 'update-banner':
					$id = Url::segment( $this->params['start_segment'] + 3);
					$this->saveBanner( $id );
				break;
				case 'all-slider':
					$data['title']      = $this->trans->get('title_all_slider');
					$sliderModel        = new Slider();
					$record             = $sliderModel->getList($this->keyword);
					$data['sliderList'] = $record['data'];
					$data['pagination'] = $record['pagination'];
				break;
				case 'new-slider':
					$data['title']          = $this->trans->get('title_new_slider');
					$data['categoryListEN'] = Slider::findBySql( "select distinct category_en from slider" );
					$data['categoryListTH'] = Slider::findBySql( "select distinct category_th from slider" );
				break;
				case 'add-slider':
					$this->saveSlider();
				break;
				case 'edit-slider':
					$template       = 'new-slider';
					$id             = Url::segment( $this->params['start_segment'] + 3);
					$data['record'] = Slider::find( $id );
					$data['categoryListEN'] = Slider::findBySql( "select distinct category_en from slider" );
					$data['categoryListTH'] = Slider::findBySql( "select distinct category_th from slider" );
				break;
				case 'update-slider':
					$id = Url::segment( $this->params['start_segment'] + 3);
					$this->saveSlider( $id );
				break;
				case 'calendar':
					$data['title'] = $this->trans->get('calendar');
					$data['record'] = Embed::findByCode( 'calendar' );
				break;
				case 'poll':
					$data['title'] = $this->trans->get('poll');
					$data['record'] = Embed::findByCode( 'poll' );
				break;
				case 'stats':
					$data['title'] = $this->trans->get('stats');
					$data['record'] = Embed::findByCode( 'stats' );
				break;
				case 'save-stats':
					$this->saveEmbed( 'stats' );
				break;
				case 'save-poll':
					$this->saveEmbed( 'poll' );
				break;
				case 'save-calendar':
					$this->saveEmbed( 'calendar' );
				break;
				case 'category-module':
					$data['title']        = $this->trans->get('title_all_category');
					$categoryModel 		  = new Category();
					$record 			  = $categoryModel->getList( 'module', 5, $this->keyword);
					$data['categoryList'] = $record['data'];
					$data['pagination']   = $record['pagination'];
				break;
				case 'form-category':
					$data['title'] = $this->trans->get('title_new_category');
				break;
				case 'edit-category':
					$id = Url::segment( $this->params['start_segment'] + 3);
					$data['record'] = Category::find( $id );
					$template = 'form-category';
				break;
				case 'add-category':
					$this->saveCategory('','module');
				break;
				case 'update-category':
					$id = Url::segment( $this->params['start_segment'] + 3);
					$this->saveCategory( $id, 'module' );
				break;
			}
			$this->render( 'module/' . $template, $data );
		}
	}

	protected function saveEmbed( $code )
	{
		$input = Input::make();
		$data  = $input->post( 'data' );

		if ( !empty( $data ) && !empty( $code ) ) {
			$check = Embed::findByCode( $code );
			// show(base64_decode($data['embed']));
			if( !$check->count() ){
				$record = new Embed();
			} else {
				$record = Embed::find( $check->offsetGet(0)->embed_id );
				show($check->offsetGet(0)->embed_id);
			}
			$record->code    = $code;
			$record->embed   = $data['embed'];
			$record->user_id = $this->userInfo['user_id'];
			$record->save();
			$this->log( 'activity', 'update embed : ' . $code );
		}
		$this->redirectTo( 'module/' . $code  );
		exit;
	}

	protected function saveBanner($id="") {
		$input = Input::make();
		$data  = $input->post( 'data' );
		$file  = $_FILES['fileinput'];
		if ( !empty( $data ) ) {
			if( !$file['error'] ){
				$fileId = Files::getLastId();
				$fileId++;
				$files  = new Files();

				$this->checkDir( $this->params['banner_path'] );

				$filename   = str_replace( " ", '_', $this->security->sanitize( $file['name']) );
				$filename   = Files::convertFilename( $filename );
				$targetPath = $this->params['banner_path'] . DS . $filename;
				move_uploaded_file( $file['tmp_name'], $targetPath );
				// create thumb section
				// $thumbPath = realpath($this->params['banner_path']) . DS . 'thumb' . DS;
				// $this->checkDir( $thumbPath );
				// $savePath  = $this->params['banner_path'] . DS . 'thumb' . DS . 'thumbnail-' . $filename;
				// create thumnail 100x100
				// $this->createThumbnail($targetPath, $thumbPath, 300, 100);
				$files->files_id  = $fileId;
				$files->filename  = $filename;
				$files->filepath  = $targetPath;
				$files->thumbpath = $targetPath;
				$files->user_id   = $this->userInfo['user_id'];
				$files->save();
			}

			if( $id ){
				$banner = Banner::find($id);
			} else {
				$banner = new Banner();
			}
			$banner->title         = $data['title'];
			$banner->detail        = $data['detail'];
			$banner->category_id   = $data['category_id'];
			$banner->priority      = $data['priority'];
			$banner->user_id       = $this->userInfo['user_id'];
			$banner->link          = $data['link'];
			$banner->publish       = !empty($data['publish'])?'on':'off';
			$banner->publish_start = $data['publish_start'];
			$banner->publish_end   = $data['publish_end'];
			$banner->last_publish  = date('Y-m-d H:i:s');
			if( $fileId ){
				$banner->file_id     = $fileId;
			}
			$banner->save();
			if( $id ){
				$this->log( 'activity', 'update banner id : ' . $id );
			} else {
				$this->log( 'activity', 'create new banner : ' . $file['name'] );
			}
			$this->redirectTo( 'module/all-banner' );
		}
		exit;
	}

	protected function saveSlider( $id="") {
		$input = Input::make();
		$data  = $input->post( 'data' );
		$file  = $_FILES['fileinput'];
		if ( !empty( $data ) ) {
			if( !$file['error'] ){
				$fileId = Files::getLastId();
				$fileId++;
				$files  = new Files();

				$this->checkDir( $this->params['slider_path'] );

				$filename   = str_replace( " ", '_', $this->security->sanitize( $file['name'] ) );
				$filename   = Files::convertFilename( $filename );
				$targetPath = $this->params['slider_path'] . DS . $filename;
				move_uploaded_file( $file['tmp_name'], $targetPath );
				$thumbPath = realpath($this->params['slider_path']) . DS . 'thumb' . DS;
				$this->checkDir( $thumbPath );
				$savePath  = $this->params['slider_path'] . DS . 'thumb' . DS . 'thumbnail-' . $filename;
				$this->createThumbnail($targetPath, $thumbPath, 1280, 400);
				$files->files_id = $fileId;
				$files->filename = $filename;
				$files->filepath = $targetPath;
				$files->thumbpath = $savePath;
				$files->user_id  = $this->userInfo['user_id'];
				$files->save();
			}

			if( $id ){
				$slider = Slider::find($id);
			} else {
				$slider = new Slider();
			}
			$slider->title          = $data['title'];
			$slider->detail         = $data['detail'];
			// $slider->category_en = $data['category_en'];
			// $slider->category_th = $data['category_th'];
			$slider->category_id    = $data['category_id'];
			$slider->priority       = $data['priority'];
			$slider->user_id        = $this->userInfo['user_id'];
			$slider->title          = $data['title'];
			$slider->publish        = !empty($data['publish'])?'on':'off';
			$slider->publish_start  = $data['publish_start'];
			$slider->publish_end    = $data['publish_end'];
			$slider->last_publish   = date('Y-m-d H:i:s');
			$slider->link           = $data['link'];
			if( $fileId ){
				$slider->file_id     = $fileId;
			}
			$slider->save();
			if( $id ){
				$this->log( 'activity', 'update silder id : ' . $id );
			} else {
				$this->log( 'activity', 'create new silder : ' . $file['name'] );
			}
			$this->redirectTo( 'module/all-slider' );
		}
		exit;
	}

	protected function createThumbnail( $imagePath, $targetPath, $width = 100, $height = 100 ) {
		if ( !file_exists( $imagePath ) ) {
			return;
		} else {
			$fileinfo = pathinfo( $imagePath );
			$filename = $fileinfo['basename'];
			$image    = new \Cygnite\Common\File\Thumbnail\Image();
			//Your image location anywhere inside the framework  root directory
			$image->directory   = realpath($imagePath); // Original image location directory
			$image->fixedWidth  = $width; // width of the thumbnail image
			$image->fixedHeight = $height; // height of the thumbnail image
			// $image->thumbPath   = realpath($this->params['thumb_path']) . DS; // destination path for thumbnail image
			$image->thumbPath   = $targetPath; // destination path for thumbnail image

			$image->thumbName   = 'thumbnail-'.$fileinfo['filename']; // new name for thumbnail image
			$image->resize(); // Crop the image and create a thumbnail image into specified location
			// $thumbPath = realpath($this->params['thumb_path']) . DS . "thumbnail-" . $filename;
			// $thumbPath = realpath(ASSETS_PATH) . DS . $type . DS . "thumbnail-" . $filename;
			// $targetPath = realpath($this->params['thumb_path']) . DS . "thumbnail-" . $filename;
			// $result = copy( $thumbPath, $targetPath);
			// show("copy result" . $result);
			// exit;
			// if( file_exists($thumbPath) && copy( $thumbPath, $targetPath) ){
			// 	show('moved');
			// 	unlink( $thumbPath );
			// 	exit;
			// }
		}
	}

	public function errorAction() {
		$this->render( '404' );
	}

	public function forbiddenAction(){
		$this->render( '403' );
	}

	protected function log( $type, $msg='' ){
		if( $this->userInfo['user_id'] == 0 ){
			return;
		}
		$logs = new Logs();
		$logs->add( $type, $this->userInfo, $msg );
	}

	protected function checkDir( $path, $type="" ){
		if( !is_dir($path) ){
			if( $type ){
				$path = realpath(ASSETS_PATH) . DS . $type;
			}
			if ( !mkdir( $path, 0755, true ) ) {
				die( 'failed create folder..' . $path);
			}
		}
		chmod( $path, 0755 );
	}

	public function getProfileAction(){
		$data = array(
			'role'    => $this->roleAccess,
			'avatar'  => $this->avatar,
			'profile' => $this->userInfo,
			'isAdmin' => $this->isAdmin
		);
		echo json_encode($data);
	}

	public function publishAction(){
		$input = Input::make();
		$rs = ['success' => 0];
		if( $input->hasPost('table') && $input->hasPost('id')
			&& (!empty($this->roleAccess[$input->post('table').'_publish']) || $this->isAdmin ) ){
			$table = $input->post('table');
			$id    = $input->post('id');
			if( $table == 'page' ){
				$record = Page::find($id);
				$class  = new Page();
			} elseif( $table == 'user' ){
				$record = User::find($id);
				$class  = new User();
			} elseif( $table == 'post' ){
				$record = Post::find($id);
				$class  = new Post();
			} elseif( $table == 'role' ){
				$record = Role::find($id);
				$class  = new Role();
			} elseif( $table == 'banner' ){
				$record = Banner::find($id);
				$class  = new Banner();
			} elseif( $table == 'slider' ){
				$record = Slider::find($id);
				$class  = new Slider();
			} elseif( $table == 'category' ){
				$record = Category::find($id);
				$class  = new Category();
			}
			$record->publish      = $record->attributes['publish'] == 'on' ? 'off' : 'on';
			$record->last_publish = date('Y-m-d H:i:s');
			$record->publish_start = $record->attributes["publish_start"];
			$record->publish_end   = $record->attributes["publish_end"];
			$record->save();
			$this->log('activity', "set $table to ".($record->publish == 'on'?'active':'inactive') );
		}
		echo json_encode($rs);
	}

}//End of your home controller
