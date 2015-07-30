<?php

require_once('../backend/libraries/autoload.php');

use baccarat\common\rest\library\Request;


Logger::configure(APP_ROOT . '/config/log4php.xml');
$logger = Logger::getLogger("RESTGATEWAY");

$request = new Request ();     

$package = 'baccarat\app\rest\controllers\\';
$packageViews = 'baccarat\common\rest\views\\';

$controller_name = $package . ucfirst($request->url_elements [1]) . 'Controller';

$logger->info(array(
    'timestamp' => $request->datetime,
    'request' => $request));

if (class_exists($controller_name)) {
    $controller = new $controller_name($request);
    $action_name = strtolower($request->verb) . 'Action';
    $result = $controller->$action_name();
    
} else {
    $result ['request_time'] = date("D M j G:i:s T Y", $request->datetime);
    $result ['status'] = "fail";
    $result ['message'] = "Bad Request";
}

$view_name = $packageViews . ucfirst($request->format) . 'View';

if (class_exists($view_name)) {
    $view = new $view_name ();
    $view->render($result);
}
$logger->info(array(
    'timestamp' => $request->datetime,
    'result' => $result));
