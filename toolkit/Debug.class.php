<?php

/**
 * Retrieves debug information about WordPress
 * @package TheFarm WP Toolkit
 * @author Hans Westman <hans@thefarm.se>
 */
class Debug extends ModuleBase {

	var $name = 'Debug';
	var $version = '0.8';
	var $author = 'Hans Westman';
	var $description = 'Collects debug information from PHP and SQL queries.';

	var $queries = array();
	var $time_sql;
	var $time_php;
	var $time_total;
	var $num_queries;
	var $time_php_percent;
	var $time_sql_precent;

	function __construct(){
		define('QUERY_CACHE_TYPE_OFF', true);

		if(!defined('SAVEQUERIES')){
			define('SAVEQUERIES', true);
		}

		add_action('wp_enqueue_scripts', array(&$this, 'LoadScriptsAndStyles'));
		add_action('wp_footer', array(&$this, 'Display'));
		add_action('wp_before_admin_bar_render', array(&$this, 'AddMenuLink'));

		parent::__construct();
	}

	function LoadScriptsAndStyles(){
		wp_enqueue_script('WPToolkitMetabox-js', THEME_URL . '/toolkit/js/WPToolkitDebug.js', array('jquery'), '1.0', true);
		wp_enqueue_style('WPToolkitDebug-css', THEME_URL . '/toolkit/css/WPToolkitDebug.css', false, '0.8');
	}

	function LoadQueries(){
		global $wpdb;
			
		if(QUERY_CACHE_TYPE_OFF){
			$wpdb->query('SET SESSION query_cache_type = 0;');
		}

		$this->num_queries = count($wpdb->queries);

		if($this->num_queries > 0){
			$this->time_total = timer_stop(false, 22);
			$this->time_sql = 0;
			foreach($wpdb->queries as $q){
				$q[0] = trim(ereg_replace('[[:space:]]+', ' ', $q[0]));
				$this->time_sql += $q[1];

				$tempQuery = array(
					'time' => $q[1],
					'query' => (!empty($q[1])) ? htmlentities($q[0]) : null,
					'call_from' => (!empty($q[2])) ? htmlentities($q[2]) : null,
				);

				$this->queries[] = (object)$tempQuery;
			}
		}

		$this->time_php = $this->time_total - $this->time_sql;

		$this->time_sql_precent = number_format_i18n($this->time_sql / $this->time_total * 100, 2);
		$this->time_php_precent = number_format_i18n($this->time_php / $this->time_total * 100, 2);
	}

	function Display(){
		$this->LoadQueries();
?>
		<div class="wptoolkit-debug-content">
			<table class="wptoolkit-debug-tabs">
				<tr class="wptoolkit-debug-tabs-header">
					<td class="active">
						<a href="#" class="wptoolkit-debug-change-tab" data-id="info">Info</a>
					</td>
					<td>
						<a href="#" class="wptoolkit-debug-change-tab" data-id="queries">Queries</a>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="wptoolkit-debug-tab active" data-id="info">
						
							<table>
								<tr>
									<th>Total execution time: </th>
									<td><?php echo($this->time_total); ?></td>
								</tr>
								<tr>
									<th>PHP execution time: </th>
									<td><?php echo($this->time_php); ?> (<?php echo($this->time_php_precent); ?>%)</td>
								</tr>
								<tr>
									<th>SQL execution time: </th>
									<td><?php echo($this->time_sql); ?> (<?php echo($this->time_sql_precent); ?>%)</td>
								</tr>
								<tr>
									<th>Number of queries: </th>
									<td><?php echo($this->num_queries); ?></td>
								</tr>
							</table>

						</div>
						<div class="wptoolkit-debug-tab" data-id="queries">
							<?php if(!empty($this->queries)): foreach($this->queries as $query): ?>
								<div style="margin: 10px 10px 0px 10px; padding-bottom: 10px; border-bottom: 1px dotted rgba(255,255,255,0.7);">
									<ul style="margin: 0; padding: 0; list-style: none;">
										<li><strong>Time: </strong><?php echo($query->time); ?></li>
										<li><strong>Query: </strong><?php echo($query->query); ?></li>
										<li><strong>Called from: </strong><?php echo($query->call_from); ?></li>
									</ul>
								</div>
							<?php endforeach; endif; ?>
						</div>
					</td>
				</tr>
			</table>

		</div>
<?php
	}

	function AddMenuLink(){
		if(!is_admin()){
			global $wp_admin_bar;
			$wp_admin_bar->add_menu( array(
				'parent' => false,
				'id' => 'wptoolkit_toggle_debug',
				'title' => 'Show debug',
				'href' => '#'
			));
		}
	}


}

?>