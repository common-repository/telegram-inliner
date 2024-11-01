<?php
/**
 * @package Websima Inliner Search Bot
 * @version 1.2
 */
/*
Plugin Name: Telegram Inliner Search Bot
Plugin URI: http://websima.com/inliner
Description: Search on Wordpress site using Telegram Inline Bots.
Author: Websima Creative Agency
Version: 1.2
Author URI: http://websima.com
*/
add_action( 'plugins_loaded', 'inline_load_textdomain' );
function inline_load_textdomain() {
  load_plugin_textdomain( 'inline', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action('admin_menu', 'inline_custom_menu_page');

function inline_custom_menu_page()
{
	add_menu_page(__('Inliner Settings', 'inline'), __('Inlier Settings', 'inline'), 'manage_options', 'inliner', 'inliner_options_page', plugins_url('inliner/includes/dashicon.png') , 100);
}

include_once('includes/inliner-settings.php');
include_once('includes/inliner-pagetemplate.php');
?>