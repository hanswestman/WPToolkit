<?php

/**
 * Contains the functions used for each input type used in metaboxes.
 * @package TheFarm WP Toolkit
 * @author Hans Westman <hans@thefarm.se>
 */
class MetaBoxOutput {

		/**
		 * Text input
		 * Supports options: label, default, description, class, style, size, placeholder
		 * @param object [$post] post-object.
		 * @param string [$name] name and ID for input.
		 * @param array [$options] associative array with options.
		 * @author Hans Westman <hans@thefarm.se>
		 */
		static function text($post, $name, $metaName, $options){
				$options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

				echo('<p>');
				if(!empty($options['label'])){
						echo('<label for="' . $name . '">' . $options['label'] . '</label><br />');
				}
				$attributes = array(
						'type' => 'text',
						'name' => $name,
						'id' => $name,
						'class' => $options['class'],
						'value' => $options['value'],
						'style' => $options['style'],
						'placeholder' => $options['placeholder'],
				);

				if(!empty($options['validate-pattern'])){
						$attributes['data-pattern'] = $options['validate-pattern'];
				}

				echo('<input' . MetaBoxOutput::_build_attributes_string($attributes) . ' />');
				if(!empty($options['description'])){
						echo('<br /><em>' . $options['description'] . '</em>');
				}
				echo('</p>');
		}

		/**
		 * Textarea input
		 * Supports options: label, default, description, class, style, cols, rows, placeholder
		 * @param object [$post] post-object.
		 * @param string [$name] name and ID for input.
		 * @param array [$options] associative array with options.
		 * @author Hans Westman <hans@thefarm.se>
		 */
		static function textarea($post, $name, $metaName, $options){
				$options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

				echo('<p>');
				if(!empty($options['label'])){
						echo('<label for="' . $name . '">' . $options['label'] . '</label><br />');
				}

				$attributes = array(
						'name' => $name,
						'id' => $name,
						'class' => $options['class'],
						'style' => $options['style'],
						'placeholder' => $options['placeholder'],
						'rows' => $options['rows'],
						'cols' => $options['cols']
				);

				if(!empty($options['validate-pattern'])){
						$attributes['data-pattern'] = $options['validate-pattern'];
				}

				echo('<textarea' . MetaBoxOutput::_build_attributes_string($attributes) . '>' . $options['value'] . '</textarea>');
				if(!empty($options['description'])){
						echo('<br /><em>' . $options['description'] . '</em>');
				}
				echo('</p>');
		}

		/**
		 * Select input
		 * Supports options: label, values, default, description, class, style
		 * @param object [$post] post-object.
		 * @param string [$name] name and ID for input.
		 * @param array [$options] associative array with options.
		 * @author Hans Westman <hans@thefarm.se>
		 */
		static function select($post, $name, $metaName, $options){
				$options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

				echo('<p>');
				if(!empty($options['label'])){
						echo('<label for="' . $name . '">' . $options['label'] . '</label><br />');
				}

				$attributes = array(
						'name' => $name,
						'id' => $name,
						'class' => $options['class'],
						'style' => $options['style'],
				);

				echo('<select' . MetaBoxOutput::_build_attributes_string($attributes) . '>');

				if(MetaBoxOutput::_is_assoc($options['values'])){
						foreach($options['values'] as $key => $value){
								echo('<option value="' . $key . '"' . (($options['value'] == $key) ? ' selected="selected"' : '') . '>' . $value . '</option>');
						}
				}
				else{
						foreach($options['values'] as $key => $value){
								echo('<option value="' . $value . '"' . (($options['value'] == $value) ? ' selected="selected"' : '') . '>' . $value . '</option>');
						}
				}

				echo('</select>');

				if(!empty($options['description'])){
						echo('<br /><em>' . $options['description'] . '</em>');
				}
				echo('</p>');
		}


		/**
		 * Boolean (true / false)
		 * Supports options: label, default, description, class, style
		 * @param object [$post] post-object.
		 * @param string [$name] name and ID for input.
		 * @param array [$options] associative array with options.
		 * @author Hans Westman <hans@thefarm.se>
		 */
		static function boolean($post, $name, $metaName, $options){
				$options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

				echo('<p>');
				if(!empty($options['label'])){
						echo('<label for="' . $name . '">' . $options['label']);
				}

				$attributes = array(
						'type' => 'checkbox',
						'name' => $name,
						'id' => $name,
						'class' => $options['class'],
						'style' => $options['style'],
						'value' => 'true',
				);

				if($options['value'] == 'true'){
						$attributes['checked'] = 'checked';
				}

				echo('<input type="hidden" name="' . $name . '" id="' . $name . '" value="false" />');
				echo('<input' . MetaBoxOutput::_build_attributes_string($attributes) . ' />');
				echo('</label>');
				if(!empty($options['description'])){
						echo(' <em>' . $options['description'] . '</em>');
				}
				echo('</p>');
		}

		/**
		 * Radio
		 * Supports options: label, values, default, description, class, style
		 * @param object [$post] post-object.
		 * @param string [$name] name and ID for input.
		 * @param array [$options] associative array with options.
		 * @author Hans Westman <hans@thefarm.se>
		 */
		static function radio($post, $name, $metaName, $options){
				$options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

				echo('<p>');
				if(!empty($options['label'])){
						echo('<span>' . $options['label'] . '</span><br />');
				}
				foreach($options['values'] as $value => $label){

						$attributes = array(
								'type' => 'radio',
								'name' => $name,
								'id' => $name . '_' . $value,
								'class' => $options['class'],
								'style' => $options['style'],
								'value' => 'true',
						);

						if($value == $options['value']){
								$attributes['checked'] = 'checked';
						}

						echo('<label for="' . $name . '_' . $value . '"><input' . MetaBoxOutput::_build_attributes_string($attributes) . ' />' . $label . '</label><br />');
				}

				if(!empty($options['description'])){
						echo('<br /><em>' . $options['description'] . '</em>');
				}
				echo('</p>');
		}
		
		/**
		* Checkbox
		* Supports options: label, values, default, description, class, style
		* @param object [$post] post-object.
		* @param string [$name] name and ID for input.
		* @param array [$options] associative array with options.
		* @author Hans Westman <hans@thefarm.se>
		*/
		static function checkbox($post, $name, $metaName, $options){
				$options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

				echo('<p>');
				if(!empty($options['label'])){
						echo('<span>' . $options['label'] . '</span><br />');
				}
				foreach($options['values'] as $value => $label){

						$attributes = array(
								'type' => 'checkbox',
								'name' => $name . '[]',
								'id' => $name . '_' . $value,
								'class' => $options['class'],
								'style' => $options['style'],
								'value' => $value,
						);
						
						if(in_array($value, $options['value'])){
								$attributes['checked'] = 'checked';
						}

						echo('<label for="' . $name . '_' . $value . '"><input' . MetaBoxOutput::_build_attributes_string($attributes) . ' /> ' . $label . '</label><br />');
				}

				if(!empty($options['description'])){
						echo('<br /><em>' . $options['description'] . '</em>');
				}
				echo('</p>');
		}

		/**
		 * Color input
		 * Supports options: label, default, description, class, style, size, placeholder
		 * @param object [$post] post-object.
		 * @param string [$name] name and ID for input.
		 * @param array [$options] associative array with options.
		 * @author Hans Westman <hans@thefarm.se>
		 */
		static function colorpicker($post, $name, $metaName, $options){
				$options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

				echo('<p>');
				if(!empty($options['label'])){
						echo('<label for="' . $name . '">' . $options['label'] . '</label><br />');
				}
				$attributes = array(
						'type' => 'text',
						'name' => $name,
						'id' => $name,
						'class' => $options['class'] . ' js-wptoolkit-colorpicker',
						'value' => $options['value'],
						'style' => $options['style'],
						'placeholder' => $options['placeholder'],
				);

				if(!empty($options['validate-pattern'])){
						$attributes['data-pattern'] = $options['validate-pattern'];
				}

				echo('<input' . MetaBoxOutput::_build_attributes_string($attributes) . ' />');
				if(!empty($options['description'])){
						echo('<br /><em>' . $options['description'] . '</em>');
				}
				echo('</p>');
		}

		/**
		 * Datepicker input
		 * Supports options: label, default, description, class, style, size, placeholder
		 * @param object [$post] post-object.
		 * @param string [$name] name and ID for input.
		 * @param array [$options] associative array with options.
		 * @author Hans Westman <hans@thefarm.se>
		 */
		static function date($post, $name, $metaName, $options){
				$options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

				echo('<p>');
				if(!empty($options['label'])){
						echo('<label for="' . $name . '">' . $options['label'] . '</label><br />');
				}
				$attributes = array(
						'type' => 'text',
						'name' => $name,
						'id' => $name,
						'class' => $options['class'] . ' js-wptoolkit-datepicker',
						'value' => $options['value'],
						'style' => $options['style'],
						'placeholder' => $options['placeholder'],
				);

				if(!empty($options['validate-pattern'])){
						$attributes['data-pattern'] = $options['validate-pattern'];
				}

				echo('<input' . MetaBoxOutput::_build_attributes_string($attributes) . ' />');
				if(!empty($options['description'])){
						echo('<br /><em>' . $options['description'] . '</em>');
				}
				echo('</p>');
		}
		

		/**
		 * Internal helper function to set default values 
		 * @author Hans Westman <hanswestman@gmail.com>
		 * @param array $options
		 * @param object $post
		 * @param string $metaName
		 * @return array
		 */
		static function _set_defaults($options, $post, $metaName){
				$value = get_post_meta($post->ID, $metaName . '_value', true);

				if(isset($options['required']) && $options['required'] === true){
					$options['class'] .= ' required';
				}
				
				return array_merge(array(
						'size' =>  (empty($options['size'])) ? 25 : $options['size'],
						'cols' =>  (empty($options['cols'])) ? 80 : $options['cols'],
						'rows' =>  (empty($options['rows'])) ? 7 : $options['rows'],
						'placeholder' =>  (empty($options['placeholder'])) ? '' : $options['placeholder'],
						'style' =>  (empty($options['style'])) ? '' : $options['style'],
						'class' =>  (empty($options['class'])) ? '' : $options['class'],
						'value' => (empty($value)) ? ((empty($options['default'])) ? '' : $options['default']) : $value,
				), $options);
		}

		/**
		 * Check if array is associative
		 * @author Hans Westman <hanswestman@gmail.com>
		 * @param array $array
		 * @return boolean
		 */
		static function _is_assoc($array){
				return array_keys($array) !== range(0, count($array) - 1);
		}

		/**
		 * Internal helper function that builds a attribute string from an array of attributes and values.
		 * @param array $attributes Associative array of attributes and values
		 * @return string
		 */
		static function _build_attributes_string($attributes = array()){
				$attributeString = '';

				if(!empty($attributes) && is_array($attributes) && MetaBoxOutput::_is_assoc($attributes)){
						foreach($attributes as $key => $value){
								$attributeString .= ' ' . $key . '="' . $value . '"';
						}
				}

				return $attributeString;
		}


}
?>