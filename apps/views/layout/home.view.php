<?php
use Cygnite\Mvc\View\Widget;
use Cygnite\AssetManager\AssetCollection;
use Cygnite\Common\UrlManager\Url;
$baseUrl = $this->data['baseUrl'];
$asset = AssetCollection::make(function ($asset)
    {
        // group of stylesheets
        $asset->add('style', array('path' => 'assets/css/font-awesome.min.css'))
              ->add('style', array('path' => 'assets/cms/styles/style_standard.css'))
              ->add('style', array('path' => "assets/cms/animate.css/animate.min.css"))
              ->add('style', array('path' => "assets/cms/glyphicons/glyphicons.css"))
              ->add('style', array('path' => "assets/cms/font-awesome/css/font-awesome.min.css"))
              ->add('style', array('path' => "assets/cms/material-design-icons/material-design-icons.css"))
              ->add('style', array('path' => "assets/cms/bootstrap/dist/css/bootstrap.min.css"))
              ->add('style', array('path' => "assets/cms/styles/app.css"))
              ->add('style', array('path' => "assets/cms/styles/font.css"))
              ->add('style', array('path' => "assets/css/fonts.css?family=Kanit&amp;subset=thai,latin"))
              ->add('style', array('path' => "assets/cms/styles/custom.css"))
              ->add('style', array('path' => "assets/cms/styles/hover.css"))
              ->add('style', array('path' => "assets/cms/styles/owl.carousel.css"))
              ->add('style', array('path' => "assets/css/frontend.min.css"));
        // Group of scripts
        // $asset->add('script', array('path' => 'assets/js/lib/jquery/2.0.3/jquery.min.js'))
        //       ->add('script', array('path' => 'assets/js/lib/bootstrap/3.3.4/bootstrap.min.js'))
        //       ->add('script', array('path' => 'assets/cms/js/owl.carousel.min.js'))
        //       ->add('script', array('path' => 'assets/js/cookie.min.js'))

        //       ->add('script', array('path' => 'assets/js/frontend.min.js'));
              $asset->add('script', array('path' => 'assets/themeforest/libs/jquery/jquery/dist/jquery.js'))
      ->add('script', array('path' => 'assets/themeforest/libs/jquery/tether/dist/js/tether.min.js'))
      ->add('script', array('path' => 'assets/themeforest/libs/jquery/bootstrap/dist/js/bootstrap.js'))
      ->add('script', array('path' => 'assets/themeforest/libs/jquery/underscore/underscore-min.js'))
      ->add('script', array('path' => 'assets/themeforest/libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js'))
      ->add('script', array('path' => 'assets/themeforest/libs/jquery/PACE/pace.min.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/config.lazyload.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/palette.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/ui-load.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/ui-jp.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/ui-include.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/ui-device.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/ui-form.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/ui-nav.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/ui-screenfull.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/ui-scroll-to.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/ui-toggle-class.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/app.js'))
      ->add('script', array('path' => 'assets/themeforest/libs/jquery/jquery-pjax/jquery.pjax.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/ajax.js'))
      ->add('script', array('path' => 'assets/js/cookie.min.js'))
      ->add('script', array('path' => 'assets/cms/js/owl.carousel.js'))
      ->add('script', array('path' => 'assets/cms/js/owl.carousel2.thumbs.js'))
      ->add('script', array('path' => 'assets/js/frontend.min.js'));
        return $asset;
    });
?>
<!DOCTYPE html>
<html>
    <head>
        <?php if( !empty($this->data['fb_data']) ){ $fbData = $this->data['fb_data'];?>
          <meta property="fb:app_id"      content="182168615207805" />
          <meta property="og:url"         content="<?php echo $fbData['url']?>" />
          <meta property="og:type"        content="article" />
          <meta property="og:title"       content="<?php echo $fbData['title']?>" />
          <meta property="og:description" content="<?php echo $fbData['description']?>" />
          <meta property="og:image"       content="<?php echo $fbData['image']?>" />
        <?php } ?>
        <meta name="keywords" content="กรมวิทยาศาสตร์การแพทย์">
        <meta name="description" content="กรมวิทยาศาสตร์การแพทย์ กระทรวงสาธารณสุข">
        <link rel="icon" href="images\favicon.ico">
        <title><?php echo $this->title?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- main style -->
        <!-- for ios 7 style, multi-resolution icon of 152x152 -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
        <link rel="apple-touch-icon" href="<?php echo $this->data['appLogo']?>">
        <meta name="apple-mobile-web-app-title" content="Flatkit">
        <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="shortcut icon" sizes="196x196" href="<?php echo $this->data['appLogo']?>">
        <?php $asset->dump('style');?>
    </head>
    <body class="frontpage" data-base-url="<?php echo $baseUrl?>">
      <div id="fb-root"></div>
      <script>
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '182168615207805',
            xfbml      : true,
            version    : 'v2.6'
          });
        };

        (function(d, s, id){
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) {return;}
           js = d.createElement(s); js.id = id;
           js.src = "//connect.facebook.net/en_US/sdk.js";
           fjs.parentNode.insertBefore(js, fjs);
         }(document, 'script', 'facebook-jssdk'));
      </script>
      <!-- <div class="app" id="app"> -->
        <!-- <div id="content" class="app-content box-shadow-z0" role="main"> -->
          <?php
            include('front-header.html');
            echo '<div ui-view class="app-body" id="view">';
            echo $yield;
            echo '</div>';
            $asset->dump('script');
            include('front-footer.html');
          ?>
        <!-- </div> -->
      <!-- </div> -->
      <!-- Go to www.addthis.com/dashboard to customize your tools -->
      <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-578140d9dbf0aa9d"></script>
    </body>
</html>
