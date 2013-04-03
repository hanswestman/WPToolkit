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
        public function text($post, $name, $metaName, $options){
                $options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

                echo('<p>');
                if(!empty($options['label'])){
                        echo('<label for="' . $name . '">' . $options['label'] . '</label><br />');
                }
                echo('<input type="text" name="' . $name . '" id="' . $name . '" class="' . $options['class'] . '" value="' . $options['value'] . '" size="' . $options['size'] .'" style="' . $options['style'] .'" placeholder="' . $options['placeholder'] .'" />');
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
        public function textarea($post, $name, $metaName, $options){
                $options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

                echo('<p>');
                if(!empty($options['label'])){
                        echo('<label for="' . $name . '">' . $options['label'] . '</label><br />');
                }
                echo('<textarea name="' . $name . '" id="' . $name . '" class="' . $options['class'] . '" rows="' . $options['rows'] .'" cols="' . $options['cols'] .'" style="' . $options['style'] .'" placeholder="' . $options['placeholder'] .'">' . $options['value'] . '</textarea>');
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
        public function select($post, $name, $metaName, $options){
                $options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

                echo('<p>');
                if(!empty($options['label'])){
                        echo('<label for="' . $name . '">' . $options['label'] . '</label><br />');
                }
                echo('<select  name="' . $name . '" id="' . $name . '" style="' . $options['style'] .'" class="' . $options['class'] . '">');

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
        public function boolean($post, $name, $metaName, $options){
                $options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

                echo('<p>');
                if(!empty($options['label'])){
                        echo('<label for="' . $name . '">' . $options['label']);
                }
                echo('<input type="hidden" name="' . $name . '" id="' . $name . '" value="false" />');
                echo('<input type="checkbox" name="' . $name . '" id="' . $name . '" value="true" class="' . $options['class'] . '" style="' . $options['style'] .'" ' . (($options['value'] == 'true') ? ' checked="checked"' : '') . ' />');
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
        public function radio($post, $name, $metaName, $options){
                $options = MetaBoxOutput::_set_defaults($options, $post, $metaName);

                echo('<p>');
                if(!empty($options['label'])){
                        echo('<span>' . $options['label'] . '</span><br />');
                }
                foreach($options['values'] as $value => $label){
                        echo('<label for="' . $name . '_' . $value . '"><input type="radio" name="' . $name . '" id="' . $name . '_' . $value . '" value="' . $value . '" class="' . $options['class'] . '" style="' . $options['style'] .'" ' . (($value == $options['value']) ? 'checked="checked" ' : '') . '/>' . $label . '</label><br />');
                }

                if(!empty($options['description'])){
                        echo('<br /><em>' . $options['description'] . '</em>');
                }
                echo('</p>');
        }




        public function _set_defaults($options, $post, $metaName){
                $value = get_post_meta($post->ID, $metaName . '_value', true);

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

        public function _is_assoc($array){
                return array_keys($array) !== range(0, count($array) - 1);
        }


}
?>