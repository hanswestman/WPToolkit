<?php

define('THEME_PATH', get_template_directory());
define('THEME_URL', get_template_directory_uri());

add_theme_support('post-thumbnails');

function __autoload($class){
	$file = THEME_PATH. '/include/' . $class . '.class.php';
	if(file_exists($file)){
		include_once($file);
	}
}

global $BASECLASS;
$BASECLASS = new Base('Test');

require_once(THEME_PATH . 'config.php');



?>