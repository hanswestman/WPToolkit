<?php

define('THEME_PATH', get_template_directory());
define('THEME_URL', get_template_directory_uri());
define('THEME_TEXTDOMAIN', 'wptoolkit');

add_theme_support('post-thumbnails');
load_theme_textdomain(THEME_TEXTDOMAIN, THEME_PATH . 'toolkit/languages');

function custom_autoload($class){
	$file = THEME_PATH. '/toolkit/' . $class . '.class.php';
	if(file_exists($file)){
		include_once($file);
	}
}

spl_autoload_register('custom_autoload');

require_once(THEME_PATH . 'config.php');

?>