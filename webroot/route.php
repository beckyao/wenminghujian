<?php
if (isset($_SERVER['REQUEST_URI'])&&preg_match('/(.*\.(php|png|jpg|jpeg|gif|js|css|woff))(?:\?[^?]*)?$/', $_SERVER["REQUEST_URI"],$matches)) {
    if($matches[2]=='php'){
        ini_set('include_path',ini_get("include_path").":".dirname(__FILE__)."/ckfinder/core/connector/php");
        require (dirname(__FILE__).$matches[1]);
        return true;
    }
    if($matches[2]=='css'){
        header("Content-Type:text/css");
    }
    if($matches[2]=='js'){
        header("Content-Type:application/x-javascript");
    }
    if($matches[2]=='jpg'||$matches[2]=='jpeg'){
        header("Content-Type:image/jpeg");
    }
    if($matches[2]=='png'){
        header("Content-Type:image/png");
    }
    if($matches[2]=='gif'){
        header("Content-Type:image/gif");
    }
    if(substr($_SERVER['REQUEST_URI'],0,8)=='/winphp/'){
        $file=dirname(__DIR__).'/winphp/webroot/'.substr($_SERVER['REQUEST_URI'],8);
        readfile($file);
        return true;
    }
    
    return false;    // serve the requested resource as-is.
}


define('ROOT_PATH', dirname(dirname(__FILE__)));
require (ROOT_PATH."/config/classpath.php");
require (ROOT_PATH."/config/conf.php");

//Logger::open(LOG_PATH);
if(php_sapi_name()=='cli'){
    Logger::setLevel(1);
    //require(ROOT_PATH.'/script/'.$argv[1]);
    require($argv[1]);
    exit();
}

try
{
    $mapper = new UrlMapper($_SERVER['SCRIPT_NAME']);
    WinRequest::setAttribute("mapper", $mapper);
    $controller = $mapper->getController();
    $output = $controller->process();
}
catch(SystemException $e)
{
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    if ($IS_DEBUG)
    {
        echo $e;
    }
}
print $output;

//Logger::close();

