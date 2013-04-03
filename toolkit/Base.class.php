<?php

/**
 * This is the base class, used for miscellaneous automatic stuff in the admin panel, lika automatic inclusion of help pages.
 * @package TheFarm WP Toolkit
 * @author Hans Westman <hans@thefarm.se>
 */
class Base {

	var $title;

	var $modules = array();

	function __construct($title){
		$this->title = $title;
		add_action('admin_menu', array(&$this, 'RegisterMenuPages'));
	}

	/**
	 * Register main menu page
	 */
	function RegisterMenuPages(){
		add_menu_page($this->title, $this->title, 'add_users', strtolower($this->title), array(&$this, 'PrintBasePage'), THEME_URL . '/toolkit/admin-icon.png'); 
	}

	/**
	 * Output function for main page
	 */
	function PrintBasePage(){
		if(!empty($this->modules)){
?>
<div class="wrapper">

	<h2>Loaded modules</h2>

	<table border="1" cellpadding="3" style="border-collapse:collapse;">
		<tr><th>Module</th><th>Version</th><th>Author</th><th>Description</th></tr>
		<?php foreach($this->modules as $module => $data): ?>
			<tr><td><?php echo($module); ?></td><td><?php echo($data['version']); ?></td><td><?php echo($data['author']); ?></td><td><?php echo($data['description']); ?></td></tr>
		<?php endforeach; ?>
	</table>
</div>
<?php
		}
	}

	/**
	 * Register function for use by modules to display them on the main page.
	 * @param string $module Name
	 * @param string $version
	 * @param string $author
	 * @param string $description
	 */
	function RegisterModule($module, $version, $author = '', $description = ''){
		$this->modules[$module] = array('version' => $version, 'author' => $author, 'description' => $description);
	}


}

?>