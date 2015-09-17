<?php
/*
Plugin Name: Wp Do ShortCode
Version: 0.1-alpha
Description: do shortcode on admin page
Author: kurozumi
Author URI: http://a-zumi.net
Plugin URI: http://a-zumi.net
Text Domain: wp-do-shortcode
Domain Path: /languages
*/

$do_shortcode = new WP_Do_ShortCode;
$do_shortcode->register();

class WP_Do_ShortCode
{
	public function register()
	{
		add_action('plugins_loaded', array($this, 'plugins_loaded'));

	}

	public function plugins_loaded()
	{
		add_action('admin_head-post-new.php',array($this, 'add_mce_external_plugins'));
		add_action('admin_head-post.php'    ,array($this, 'add_mce_external_plugins'));
		
		add_action('wp_ajax_do_shortcode', array($this, 'do_shortcode'));
		add_action('wp_ajax_nopriv_do_shortcode', array($this, 'do_shortcode'));

		// ショートコード
		add_shortcode('template_url', array($this, 'template_url'));
	}

	public function template_url()
	{
		// 子テーマに対応させる
		return get_stylesheet_directory_uri();

	}
	
	public function do_shortcode()
	{
		if (isset($_REQUEST['shortcode']))
			echo do_shortcode(sprintf("[%s]", esc_html($_REQUEST['shortcode'])));
		
		die();

	}

	function add_mce_external_plugins()
	{
		// ビジュアルリッチエディタの場合
		if (get_user_option('rich_editing') == 'true')
			add_filter("mce_external_plugins", array($this, "mce_external_plugins"));
	}

	public function mce_external_plugins()
	{
		$plugin_array['open_shortcode'] = plugin_dir_url(__FILE__) . 'editor_plugin.js';
		return $plugin_array;

	}

}
