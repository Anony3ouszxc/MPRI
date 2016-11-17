<?php
use Cygnite\Mvc\View\Widget;
use Cygnite\AssetManager\AssetCollection;
$baseUrl = $this->data['baseUrl'];
$asset = AssetCollection::make(function ($asset)
{
// group of stylesheets
$asset->add('style', array( 'path' => 'assets/themeforest/assets/animate.css/animate.min.css'))
      ->add('style', array( 'path' => 'assets/themeforest/assets/glyphicons/glyphicons.css'))
      ->add('style', array( 'path' => 'assets/themeforest/assets/font-awesome/css/font-awesome.min.css'))
      ->add('style', array( 'path' => 'assets/themeforest/assets/material-design-icons/material-design-icons.css'))
      ->add('style', array( 'path' => 'assets/themeforest/assets/bootstrap/dist/css/bootstrap.min.css'))
      ->add('style', array( 'path' => 'assets/themeforest/assets/styles/app.css'))
      ->add('style', array( 'path' => 'assets/themeforest/assets/styles/font.css'))
      ->add('style', array( 'path' => 'assets/js/lib/bootstrap/bootstrap-gtreetable/dist/bootstrap-gtreetable.min.css'))
      ->add('style', array( 'path' => 'assets/js/lib/jquery/treegrid/jquery.treegrid.css'))
      ->add('style', array( 'path' => ( $this->data['mode'] == 'production')
                                      ? 'assets/css/custom.min.css'
                                      : 'assets/css/custom.css'));

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
      // ->add('script', array('path' => 'assets/themeforest/libs/jquery/summernote/dist/summernote.js'))
      // ->add('script', array('path' => 'assets/themeforest/libs/jquery/summernote/dist/uploadcare.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/app.js'))
      ->add('script', array('path' => 'assets/themeforest/libs/jquery/jquery-pjax/jquery.pjax.js'))
      ->add('script', array('path' => 'assets/themeforest/scripts/ajax.js'))
      ->add('script', array('path' => 'assets/js/lib/bootstrap/bootstrap-gtreetable/dist/jquery-ui.js'))
      ->add('script', array('path' => 'assets/js/lib/bootstrap/bootstrap-gtreetable/dist/jquery.browser.min.js'))
      ->add('script', array('path' => 'assets/js/lib/bootstrap/bootstrap-gtreetable/dist/bootstrap-gtreetable.js'))
      ->add('script', array('path' => 'assets/js/lib/jquery/treegrid/jquery.treegrid.min.js'))
      ->add('script', array('path' => 'assets/js/lib/jquery/treegrid/jquery.treegrid.bootstrap3.js'))
      ->add('script', array('path' => 'assets/js/lib/bootstrap/notify/bootstrap-notify.min.js'))
      ->add('script', array('path' => 'assets/js/cookie.min.js'))
      ->add('script', array('path' => ( $this->data['mode'] == 'production')
                                      ? 'assets/js/custom.min.js'
                                      : 'assets/js/custom.js'));
return $asset;
});
?>
<!DOCTYPE html>
<html lang="<?php echo $_COOKIE['cms_lang']?>">
  <head>
    <meta charset="UTF-8">
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="<?php echo $baseUrl?>assets/cms/images/logo-dmsc.png" > </link>
    <title><?php echo $this->title; ?></title>
    <!--  Mobile Viewport Fix -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <?php $asset->dump('style');// Header Style block ?>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body data-base-url="<?php echo $baseUrl?>">
    <div class="app" id="app">
      <!-- ############ LAYOUT START-->
      <!-- aside -->
      <?php if(!empty($this->data['isLogged'])){ ?>
      <div id="aside" class="app-aside modal fade nav-dropdown" ng-class="{'folded': app.setting.folded}">
        <?php include('menu.html');?>
      </div>
      <?php } ?>
      <!-- / -->
      <!-- content -->
      <div id="content" class="app-content box-shadow-z0" role="main">
        <?php if(!empty($this->data['isLogged'])){ ?>
        <div class="app-header dark box-shadow">
          <?php include('header.html');?>
        </div>
        <div class="app-footer">
          <?php include('footer.html');?>
        </div>
        <?php } ?>
        <div ui-view class="app-body" id="view">
          <!-- Fluid Container -->
          <!-- Content -->
          <?php echo $yield; ?>
          <!-- ./ Content -->
          <!-- Footer -->
          <!-- <footer class="clearfix"></footer> -->
          <!-- ./ Footer -->
          <!-- ./ Container End -->
          <?php
          //Script block. Scripts will render here
          $asset->dump('script');
          ?>
        </div>
      </div>
    </div>
  </body>
</html>