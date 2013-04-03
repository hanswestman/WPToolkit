<?php

require_once(get_theme_root() . '/test/include/MetaBoxOutput.class.php');

/**
 * Creates metaboxes with various input types
 * @package TheFarm WP Toolkit
 * @author Hans Westman <hans@thefarm.se>
 * @uses MetaBoxOutput.class.php
 */
class MetaBox extends ModuleBase{

	var $name = 'Metabox';
	var $version = '1.2';
	var $author = 'Hans Westman';
	var $description = '';

	var $config;

	function __construct($config){
		$this->config = array();
		foreach($config as $posttype => $boxes){
			$this->config[strtolower($posttype)] = $boxes;
		}

		add_action('add_meta_boxes', array(&$this, 'RegisterMetaBoxes'));
		add_action('save_post', array(&$this, 'SaveMetaValues'));

		parent::__construct();
	}

	/**
	 * Register a meta box
	 */
	function RegisterMetaBoxes(){
		foreach($this->config as $typeName => $type){
			$typeName = strtolower($typeName);
			foreach($type as $sectionName => $section){
				add_meta_box('MetaBox_' . $typeName . '_' . $sectionName, $sectionName, array(&$this, 'ShowMetaBox'), $typeName, 'advanced', 'default', array('type'=>$typeName, 'section'=>$sectionName));
			}
		}

		//TODO: Kolla om det räcker med att enqueuea eventuella scripts här
	}
	
	/**
	 * Displays a metabox with all its input types.
	 * @param post-object $post
	 * @param array $args
	 */
	function ShowMetaBox($post, $args){
		$type = $args['args']['type'];
		$section = $args['args']['section'];
		wp_nonce_field(plugin_basename(__FILE__), 'MetaBox_nonce');
		$metaBoxes =$this->config;
		$metas = $metaBoxes[$type][$section];
		foreach($metas as $metaName => $meta){
			if(method_exists('MEtaBoxOutput', $meta['type'])){
				$name = $type . '_' . preg_replace('/\s/', '_', $section) . '_' . $metaName;
				call_user_func_array(array('MetaBoxOutput', $meta['type']), array($post, $name, $metaName, $meta));
			}
		}
	}
	
	/**
	 * Saving values inputted in the metaboxes
	 * @param integer $postId
	 * @return null
	 */
	function SaveMetaValues($postId){
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
			return;
		}
		if(empty($_POST['MetaBox_nonce']) || !wp_verify_nonce($_POST['MetaBox_nonce'], plugin_basename(__FILE__))){
			return;
		}
		if(!current_user_can('edit_page', $postId)){
			return;
		}

		if(function_exists('save_feed')){
			save_feed();
		}
		
		$metaBoxes = $this->config;
		$metaBox = $metaBoxes[$_POST['post_type']];
		foreach($metaBox as $sectionName => $section){
			foreach($section as $metaName => $meta){
				$inputName = $_POST['post_type'] . '_' . preg_replace('/\s/', '_', $sectionName) . '_' . $metaName;
				if(isset($_POST[$inputName])){
					$oldValue = get_post_meta($postId, $metaName . '_value', true);
					if(empty($oldValue)){
						if(!empty($_POST[$inputName])){
							add_post_meta($postId, $metaName . '_value', $_POST[$inputName]);
						}
					}
					else{
						if(empty($_POST[$inputName])){
							delete_post_meta($postId, $metaName . '_value');
						}
						else{
							update_post_meta($postId, $metaName . '_value', $_POST[$inputName]);
						}
					}
				}
			}
		}
	}
}


?>