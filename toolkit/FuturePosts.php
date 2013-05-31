<?php

/**
 * Class that automatically calls a callback function from AJAX calls.
 * @package TheFarm WP Toolkit
 * @author Hans Westman <hans@thefarm.se>
 */
class FuturePosts extends ModuleBase {

	var $name = 'Future Posts';
	var $version = '1.0';
	var $author = 'Hans Westman';
	var $description = 'Makes future posts published, but still in the future, allowing them to be queryable by everyone.';

	var $postTypes = array();

	function __construct($postTypes){
		if(!empty($postTypes)){
			if(is_array($postTypes)){
				$this->postTypes = $postTypes;
			}
			else{
				$this->postTypes[] = $postTypes;
			}
		}

		add_filter('init', array(&$this, 'ActionInit'));
		add_filter('posts_where', array(&$this, 'ModifyQuery'), 2, 10);

		parent::__construct();
	}

	/**
	 * Makes post future post published, but with a future date, when saved.
	 */
	public function ActionInit(){
		if(!empty($this->postTypes)){
			foreach($this->postTypes as $postType){
				remove_action('future_' . $postType, '_future_post_hook');
				add_action('future_' . $postType, array(&$this, 'PublishNow'), 2, 10);
			}
		}
	}

	/**
	 * Callback that sets a post as published.
	 */
	public function PublishNow($depreciated, $post){
		wp_publish_post($post);
	}

	/**
	 * Callback that modifies an archive query to include both published and future posts.
	 */
	public function ModifyQuery($where, $that){
		global $wpdb;

		if(is_archive() && in_array($that->query_vars['post_type'], $this->postTypes)){
			$where = str_replace("{$wpdb->posts}.post_status = 'publish'", "{$wpdb->posts}.post_status = 'publish' OR $wpdb->posts.post_status = 'future'", $where);
		}

		return $where;
	}
}

?>