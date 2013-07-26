<?php

require_once(get_template_directory() . '/toolkit/MetaBoxOutput.class.php');

/**
 * Creates metaboxes with various input types
 * @package TheFarm WP Toolkit
 * @author Hans Westman <hans@thefarm.se>
 * @uses MetaBoxOutput.class.php
 */
class MetaBox extends ModuleBase{

	var $name = 'Metabox';
	var $version = '1.7';
	var $author = 'Hans Westman';
	var $description = 'Adds metaboxes with various types of input fields.';

	var $config;

	function __construct($config){
		$this->config = array();
		foreach($config as $posttype => $boxes){
			$this->config[strtolower($posttype)] = $boxes;
		}

		add_action('add_meta_boxes', array(&$this, 'RegisterMetaBoxes'));
		add_action('save_post', array(&$this, 'SaveMetaValues'));
		add_action('delete_post', array(&$this, 'DeleteMetaValues'));
		add_action('admin_enqueue_scripts', array(&$this, 'EnqueueScripts'));

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
			
			if($meta['type'] == 'group'){
				$limit = (!empty($meta['limit'])) ? $meta['limit'] : 3;
				MetaBox::ShowMetaBoxGroupStart($limit);
				
				if(!empty($meta['fields'])){
					
					//TODO: Fix these
					//Get values for each field
					//Multiply fieldsets for each value
					//Insert this value into it.
					
					
					foreach($meta['fields'] as $newID => $field){
						$name = $type . '_' . preg_replace('/\s/', '_', $section) . '_' . $newID;
						call_user_func_array(array('MetaBoxOutput', $field['type']), array($post, $name . '[]', $newID, $field));
					}
				}
				
				MetaBox::ShowMetaBoxGroupEnd($meta);
			}
			else if(method_exists('MetaBoxOutput', $meta['type'])){
				$name = $type . '_' . preg_replace('/\s/', '_', $section) . '_' . $metaName;
				call_user_func_array(array('MetaBoxOutput', $meta['type']), array($post, $name, $metaName, $meta));
			}
		}
	}
	
	/**
	 * Saving values inputted in the metaboxes
	 * @param integer $post_id
	 * @return null
	 */
	function SaveMetaValues($post_id){
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
			return;
		}
		if(empty($_POST['MetaBox_nonce']) || !wp_verify_nonce($_POST['MetaBox_nonce'], plugin_basename(__FILE__))){
			return;
		}
		if(!current_user_can('edit_page', $post_id)){
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
					$oldValue = get_post_meta($post_id, $metaName . '_value', true);
					if(empty($oldValue)){
						if(!empty($_POST[$inputName])){
							add_post_meta($post_id, $metaName . '_value', $_POST[$inputName]);
						}
					}
					else{
						if(empty($_POST[$inputName])){
							delete_post_meta($post_id, $metaName . '_value');
						}
						else{
							update_post_meta($post_id, $metaName . '_value', $_POST[$inputName]);
						}
					}
				}
			}
		}
	}
	
	public function GetValue($post_id, $field_name, $default = ''){
		/*if(!empty($this->config[$post_type])){ //Om man vill behandla returdatan speciellt enligt config
			foreach($this->config[$post_type] as $metabox){
				if(isset($metabox[$field_name])){
					$config = $metabox[$field_name];
				}
			}
		}*/

		$value = get_post_meta($post_id, $field_name . '_value', true);
		return (empty($value)) ? $default : $value;


		return $default;
	}

	//TODO: Docblock här
	function EnqueueScripts(){
        wp_enqueue_script('validate-form-js', THEME_URL . '/toolkit/js/validate.js', array('jquery'), '1.0', true);
		wp_enqueue_script('WPToolkitMetabox-js', THEME_URL . '/toolkit/js/WPToolkitMetaBox.js', array('jquery'), '1.0', true);
        wp_enqueue_style('WPToolkitMetabox-css', THEME_URL . '/toolkit/css/WPToolkitMetabox.css', false, '1.0');
		
		$screen = get_current_screen();
		if(!empty($screen->post_type)){
			$activePostType = $screen->post_type;
			if(!empty($this->config[$activePostType])){
				foreach($this->config[$activePostType] as $metabox){
					if(!empty($metabox)){
						foreach($metabox as $fields){
							switch($fields['type']){
								case 'colorpicker':
									wp_enqueue_script('wp-color-picker');
									wp_enqueue_style('wp-color-picker');
									break;
								case 'date':
									wp_enqueue_script('jquery-ui-datepicker');	
									wp_enqueue_style('jquery-ui-lightness', THEME_URL . '/toolkit/css/ui-lightness/jquery-ui-1.10.3.custom.min.css', array(), '1.10.3');
									break;
							}
							
							if(!empty($fields['js'])){
								foreach($fields['js'] as $script){
									wp_enqueue_script($script);
								}
							}
							else if(!empty($fields['css'])){
								foreach($fields['css'] as $style){
									wp_enqueue_style($style);
								}
							}
						}
					}
				}
			}
		}
	}
	
	//TODO: Docblock här
	function ShowMetaBoxGroupStart($limit = 3){
		echo('<div data-field-limit="' . $limit . '">');
		echo('<fieldset style="border: 1px solid #000;">');
		echo('<legend>Title 1</legend>');
	}
	
	//TODO: Docblock här
	function ShowMetaBoxGroupEnd(&$meta){
		echo('</fieldset>');
		if(!empty($meta['add_more']) && $meta['add_more'] === true){
			echo('<a href="#" class="js-add-another-field">' . __('Add another', THEME_TEXTDOMAIN) . '</a>');
		}
		echo('</div>');
	}

	//TODO: Docblock här
	function DeleteMetaValues($post_id){
		//TODO: Ta bort alla custom värden här
	}
}

?>