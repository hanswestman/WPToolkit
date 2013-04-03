<?php


new PostType('Demo', 'Demos', array('taxonomies'=>array('category')));

new MetaBox(array(
	'post' => array(
		'Links' => array(
			'out' => array(
				'type' => 'text',
				'label' => 'Outgoing: ',
				'description' => 'Test description',
				'default' => '',
				'placeholder' => 'http://...',
				'style' => 'width:99%',
			),
			'incoming' => array(
				'type' => 'text',
				'label' => 'Incoming: ',
				'description' => 'Test description',
				'default' => '',
				'placeholder' => 'http://...',
				'style' => 'width:99%',
			),
		)
	)
));

new AjaxAPI();


?>