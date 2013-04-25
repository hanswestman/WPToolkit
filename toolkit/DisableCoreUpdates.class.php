<?php

/**
* Disable core updates
* @package TheFarm WP Toolkit
* @author Hans Westman <hans@thefarm.se>
*/
class DisableCoreUpdates extends ModuleBase {

	var $name = 'Core Update Blocker';
	var $version = '1.0';
	var $author = 'Hans Westman';
	var $description = 'Disables WordPress core updates.';

	function __construct(){
		add_action('init', array(&$this, 'Init'), 2); //WP -2.7
		add_filter('pre_option_update_core', array(&$this, 'DisableFilter'));//WP -2.7
		add_filter('pre_transient_update_core', array(&$this, 'DisableFilter')); //WP2.8-3.0
		remove_action('wp_version_check', 'wp_version_check'); //WP2.8-3.0
		remove_action('admin_init', '_maybe_update_core'); //WP2.8-3.0
		add_filter('pre_site_transient_update_core', array(&$this, 'DisableFilter'));//WP 3.0+ Should be the only one needed

		parent::__construct();
	}

	function Init(){
		remove_action('init', 'wp_version_check');
	}

	function DisableFilter(){
		return null;
	}
}

?>