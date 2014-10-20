<?php

if ( ! class_exists( 'Simple_Splash_Page_Admin' ) ) {

/**
 * So that themes and other plugins can customise the text domain, the Simple_Splash_Page_Admin should
 * not be initialized until after the plugins_loaded and after_setup_theme hooks.
 * However, it also needs to run early on the init hook.
 *
 * @package Simple Splash Page
 * @since 1.0
 */
function initialize_simple_splash_page_admin() {
	Simple_Splash_Page_Admin::init();
}
add_action( 'init', 'initialize_simple_splash_page_admin', -1 );


class Simple_Splash_Page_Admin {

	public static function init() {  

		/* create custom plugin settings menu */
		add_action( 'admin_menu',  __CLASS__ . '::simple_splash_page_create_menu' );

	}

	public static function simple_splash_page_create_menu() {

		//create new top-level menu
		add_options_page( 'Splash Page Settings', 'Simple Splash Page', 'administrator', 'simple_splash_page', __CLASS__ . '::simple_splash_page_settings_page' );

		//call register settings function
		add_action( 'admin_init',  __CLASS__ . '::register_mysettings' );

	}


	public static function register_mysettings() {
	
		$page = 'simple_splash_page-settings'; 

		// General settings
		
		add_settings_section( 
			'simple_splash_page-general', 
			'General Settings',
			__CLASS__ . '::simple_splash_page_general_callback',
			$page
		);
		
		add_settings_field(
			'simple_splash_page-redirect',
			'Placement',
			__CLASS__ . '::simple_splash_page_redirect_callback',
			$page,
			'simple_splash_page-general'
		);

		//register our settings
		
		register_setting( $page, 'simple_splash_page-redirect' );

	}

	public static function simple_splash_page_settings_page() {
	
		$page = 'simple_splash_page-settings'; 
	
	?>
	<div class="wrap">
	
		<div id="icon-options-general" class="icon32"><br /></div><h2>Simple Splash Page Settings</h2>
		
		<?php settings_errors(); ?>
	
		<form method="post" action="options.php">
			
			<?php settings_fields( $page ); ?>
			
			<?php do_settings_sections( $page ); ?>
		
			<p class="submit">
				<input type="submit" class="button-primary" value="Save Changes" />
			</p>
		
		</form>
		
	</div>
	
	<?php 
	} 
	
	// General Settings Callbacks

	public static function simple_splash_page_general_callback() {
		
		//do nothing
		
	}

	public static function simple_splash_page_redirect_callback() {
		
		$selected = ( get_option('simple_splash_page-redirect') ) ? esc_attr( get_option('simple_splash_page-redirect') ) : 0 ;
		
		$args = array(
    		'depth'	=> 0,
    		'child_of' => 0,
    		'selected' => $selected,
    		'echo' => 1,
    		'name' => 'simple_splash_page-redirect',
    		'show_option_none' => '-- Disabled - select a page to enable --'
    	);
		
		wp_dropdown_pages( $args );
		
	}

}

}


