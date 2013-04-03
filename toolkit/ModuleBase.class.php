<?php

/**
 * Abstract class that can be used to extend normal modules with extra automatic functionality.
 * @package TheFarm WP Toolkit
 * @author Hans Westman <hans@thefarm.se>
 */
abstract class ModuleBase {

	var $name = '';
	var $version = '';
	var $author = '';
	var $description = '';
	var $helpfile;

	function __construct(){
		
		global $BASECLASS;
		if($BASECLASS instanceof Base){
			$this->helpfile = get_template_directory() . '/include/help/' . get_class($this) . '.html';
			$BASECLASS->RegisterModule($this->name, $this->version, $this->author, $this->description);
			add_action('admin_menu', array(&$this, 'CreateMenuPage'));
		}
	}

	/**
	 * Creates a submenu page for the modules documentation.
	 * @global Base $BASECLASS
	 */
	function CreateMenuPage(){
		global $BASECLASS;
		if(file_exists($this->helpfile)){
			add_submenu_page(strtolower($BASECLASS->title), $this->name . ' About', $this->name, 'manage_options', strtolower($BASECLASS->title) . '-' . preg_replace('/\s/', '', strtolower($this->name)), array(&$this, 'ShowMenuPage'));
		}
	}

	/**
	 * Displays the content of the submenu page.
	 */
	function ShowMenuPage(){
		echo('<div class="wrapper">' . file_get_contents($this->helpfile) . '</div>');
	}

}

?>