<?php 
/*
 Plugin Name: Users Gifts
 Description: Users Gifts
 Version: 1.0
 Author: Polyakov Vladimir
*/

if ( preg_match ('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

if ( ! class_exists('WpGifts') ) {

class WpGifts {

	public $data = array();

	function WpGifts() {

		global $wpdb;
		global $tab_gt_gifts;

		DEFINE('WpGifts', true);

		$this->plugin_name = plugin_basename(__FILE__);
		$this->plugin_url = trailingslashit(WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)));
		$this->tbl_gifts = $tab_gt_gifts = $wpdb->prefix . 'gt_gifts';


		register_activation_hook( $this->plugin_name, array(&$this, 'activate') );
		register_deactivation_hook( $this->plugin_name, array(&$this, 'deactivate') );
		register_uninstall_hook( $this->plugin_name, array(&$this, 'uninstall') );

		if ( is_admin() ) {
			add_action( 'wp_print_scripts', array(&$this, 'admin_load_scripts' ) );
			add_action( 'admin_menu', array(&$this, 'admin_generate_menu') );
			add_action( 'wp_ajax_gifts_action',array(&$this, 'gifts_action') );
		} 
	}


	function admin_load_scripts() {

		wp_register_script( 'jquery', $this->plugin_url . 'js/jquery-1.8.3.min.js' );
		wp_register_script('admin_gift', $this->plugin_url.'js/admin_gift.js');
		wp_register_script('jqueryiu', $this->plugin_url.'js/jquery-ui-1.9.2.custom.min.js');


		wp_register_style('GT_custom_css', $this->plugin_url . 'css/GT_custom_styles.css' );
		wp_register_style('jquery_ui_css', $this->plugin_url . 'css/smoothness/jquery-ui-1.9.2.custom.css' );

		wp_enqueue_script('jquery');
		wp_enqueue_script('admin_gift');
		wp_enqueue_script('jqueryiu');

		wp_enqueue_style('GT_custom_css');
		wp_enqueue_style('jquery_ui_css');
	}

	function admin_generate_menu() {
		add_menu_page( 'Welcome to the module user\'s gifts', 'Users gifts', 'manage_options', 'admin_edit_users_gifts', array( &$this, 'admin_edit_users_gifts' ) );
		add_submenu_page( 'admin_edit_users_gifts', 'About Users Gifts ', 'About plugin', 'manage_options', 'plugin_info', array( &$this, 'admin_plugin_info' ) );
	}

	function admin_edit_users_gifts(){
		include_once('admin_gift_manger.php');
		
	}

	function admin_plugin_info() {
		include_once('plugin_info.php');
	}

	function activate() {
		global $wpdb;
		
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

		$tbl_gifts = $this->tbl_gifts;

		if ( version_compare( mysql_get_server_info(), '4.1.0', '>=' ) ) {
			if ( ! empty( $wpdb->charset ) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty( $wpdb->collate ) )
				$charset_collate .= " COLLATE $wpdb->collate";
		}

		$sql_table_gifts = "
			CREATE TABLE IF NOT EXISTS `" . $tbl_gifts . "` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`Active` tinyint(1) NOT NULL DEFAULT '0',
			`GiftName` varchar(150) NOT NULL,
			`GiftDesc` varchar(255) NOT NULL,
			`CutoffDate` date DEFAULT NULL,
			`CutoffNumber` int(11) NOT NULL DEFAULT '0',
			`CurrentCount` int(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB  " . $charset_collate . " AUTO_INCREMENT=1 ;";

		if ( $wpdb->get_var( "show tables like '".$tbl_gifts."'" ) != $tbl_gifts ) {
			dbDelta($sql_table_gifts);
		}

	}
	
	function deactivate() {
		return true;
	}

	function uninstall() {
		global $wpdb;

		$wpdb->query("DROP TABLE IF EXISTS $this->tbl_gifts;");
	}

}
}

global $reviews;
$reviews = new WpGifts();