<?php

/**
 * Class used to register post types with a oneline-command in config.
 * @package TheFarm WP Toolkit
 * @author Hans Westman <hans@thefarm.se>
 */
class PostType extends ModuleBase {

	var $name = 'Post Type';
	var $version = '1.0';
	var $author = 'Hans Westman';
	var $description = 'Makes it easy to add a new custom post type, just one command easy is needed.';

	var $name_singular;
	var $name_plural;
	var $options;

	function __construct($name_singular, $name_plural, $options = array()){
		$this->name_singular = $name_singular;
		$this->name_plural = $name_plural;
		$this->options = array_merge(array(
			'labels' => array(
				'name' => '',
				'singular_name' => ucfirst($name_singular),
				'add_new' => __('Add new', THEME_TEXTDOMAIN),
				'add_new_item' => sprintf(__('Add new %s', THEME_TEXTDOMAIN), $name_singular);
				'edit_item' => sprintf(__('Edit %s', THEME_TEXTDOMAIN), $name_singular);
				'new_item' => sprintf(__('New %s' , THEME_TEXTDOMAIN), $name_singular);
				'all_items' => sprintf(__('All %s', THEME_TEXTDOMAIN), $name_plural);
				'view_item' => sprintf(__('View %s', THEME_TEXTDOMAIN), $name_singular);
				'search_items' => sprintf(__('Search %s', THEME_TEXTDOMAIN), $name_plural);
				'not_found' => sprintf(__('No %s found', THEME_TEXTDOMAIN), $name_plural);
				'not_found_in_trash' => sprintf(__('No %s found in trash', THEME_TEXTDOMAIN), $name_plural);
				'parent_item_colon' => '',
				'menu_name' => ucfirst($name_plural),
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array(
				'slug'=>strtolower($name_singular)
			),
			'capability_type' => 'post',
			'has_archive' => $name_plural,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title','editor','thumbnail','excerpt'), //title, editor, author, thumbnail, excerpt, comments
			'taxonomies' => array(),
		), $options);

		add_action('init', array(&$this, 'RegisterPostType'));
		
		parent::__construct();
	}

	/**
	 * Callback function that registers the custom post type.
	 */
	function RegisterPostType(){
		register_post_type(strtolower($this->name_singular), $this->options);
	}

}

?>