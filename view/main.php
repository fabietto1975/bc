<!DOCTYPE html>
<?php
require_once('../backend/libraries/autoload.php');
?>
<html class="no-js" lang="en" ng-app="baccaratAPP" xmlns:ng="http://angularjs.org">
    <head>
        <title>Baccarat</title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Cache-control" content="no-cache">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/stylesheets/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
        <link rel="stylesheet" type="text/css" href="css/webfonts.css"/>
        <link rel="stylesheet" type="text/css" href="css/css.css"/>
        <link rel="shortcut icon" href="favicon.ico"/>
    </head>
    <body ng-cloak>

    <!-- global header -->
    <ui-view id="appHeader" name="header"></ui-view>

    <div class="container">

        <ui-view id="appPageBody" name="pageBody"></ui-view> 

    </div>
    <!-- global footer -->
    <ui-view id="appFooter" name="footer"></ui-view>

    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/libs/jquery/jquery-1.11.3.min.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/libs/bootstrap/bootstrap.min.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/libs/angular/angular.min.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/libs/angular/angular-translate.min.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/libs/angular/angular-translate-loader-static-files.min.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/libs/angular/angular-ui-router.min.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/libs/angular/angular-resource.min.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/libs/angular/angular-cookies.min.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/libs/angular/angular-route.min.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/libs/angular/ui-router-styles.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/libs/angular/ui-bootstrap-tpls-0.11.0.min.js"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/app/app.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/app/controllers.js?<?php echo time() ?>"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/app/subscribeController.js"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/app/storeSelectionController.js"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/app/headerController.js"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/app/profileController.js"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/app/searchController.js"></script>
    <script src="<?php echo BASE_URL ?>/view/assets/javascripts/app/services.js?<?php echo time() ?>"></script>

    <script>
        app.constant('base_url', '<?php echo BASE_URL ?>');
    </script>


    
    
</body>

</html>
