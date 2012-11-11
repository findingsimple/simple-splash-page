<?php 
/*
Plugin Name: Simple Splash Page
Plugin URI: http://plugins.findingsimple.com
Description: Add a temporary splash page to your site. Useful for on-the-day 
front pages for events.
Version: 1.0
Author: Finding Simple (Jason Conroy & Brent Shepherd)
Author URI: http://findingsimple.com
License: GPL2
*/
/*
Copyright 2008 - 2012  Finding Simple  (email : plugins@findingsimple.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once dirname( __FILE__ ) . '/simple-splash-page-admin.php';

if ( ! class_exists( 'Simple_Splash_Page' ) ) : 

/**
 * So that themes and other plugins can customise the text domain, the Simple_Splash_Page
 * should not be initialized until after the plugins_loaded and after_setup_theme hooks.
 * However, it also needs to run early on the init hook.
 *
 * @author Jason Conroy <jason@findingsimple.com>
 * @package Simple Splash Page
 * @since 1.0
 */
function initialize_splash_page(){
	Simple_Splash_Page::init();
}
add_action( 'init', 'initialize_splash_page', -1 ); 

/**
 * Plugin Main Class.
 *
 * @package Simple Splash Page
 * @since 1.0
 */
class Simple_Splash_Page {
	
	/**
	 * Initialize the class
	 *
	 * @since 1.0
	 */
	public static function init() {
						
		//add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_styles_and_scripts' ) );
		
		add_action( 'template_redirect', array( __CLASS__, 'simple_splash_page_redirect' ) );
		
		add_filter( 'robots_txt', array( __CLASS__, 'simple_splash_page_robots') , 10, 2 );

		
	}
	
	/**
	 * Add admin scripts and styles
	 *
	 * @since 1.0
	 */
	public static function enqueue_admin_styles_and_scripts() {
				
		if ( is_admin() ) {
	
			wp_register_style( 'simple-splash-page-admin', self::get_url( '/css/simple-splash-page-admin.css', __FILE__ ) , false, '1.0' );
			wp_enqueue_style( 'simple-splash-page-admin' );
				
		}
		
	}
	

	/**
	 * Perform redirect to splash page if front page. Using a 302 (temporary).
	 *
	 * @since 1.0
	 */
	function simple_splash_page_redirect(){
	
		if ( get_option('simple_splash_page-redirect') != null ) {
			
			$location = get_permalink( get_option('simple_splash_page-redirect') );
								
			$redirect = ( !empty( $_GET['redirect'] ) && ( mysql_real_escape_string( $_GET['redirect'] ) == 'false' ) ) ? false : true ;
	
			if ( is_front_page() && $redirect ) {
				wp_safe_redirect( $location ); //default is a 302
				exit;
			}
		
		}
				
	}

	/**
	 * Disallow the /?redirect=false url in the robots txt to help with any seo issues
	 *
	 * @since 1.0
	 */	
	function simple_splash_page_robots( $output, $public ) {
	
		// Append rule
		$output .= "Disallow: /?redirect=false\n";
		
		// Return modified output
		return $output;
		
	}
	
	/**
	 * Helper function to get the URL of a given file. 
	 * 
	 * As this plugin may be used as both a stand-alone plugin and as a submodule of 
	 * a theme, the standard WP API functions, like plugins_url() can not be used. 
	 *
	 * @since 1.0
	 * @return array $post_name => $post_content
	 */
	public static function get_url( $file ) {

		// Get the path of this file after the WP content directory
		$post_content_path = substr( dirname( str_replace('\\','/',__FILE__) ), strpos( __FILE__, basename( WP_CONTENT_DIR ) ) + strlen( basename( WP_CONTENT_DIR ) ) );

		// Return a content URL for this path & the specified file
		return content_url( $post_content_path . $file );
	}	
	
}
 
endif;