<?php
/*

 It's not recommended to add functions to this file, as it will be lost if you ever update this child theme.
 Instead, consider adding your function into a plugin using Pluginception: https://wordpress.org/plugins/pluginception/
 
 */

add_action( 'admin_notices', 'statement_reset_customizer_settings' );
function statement_reset_customizer_settings() {
	global $pagenow;
	$generate_settings = get_option('generate_settings');
	
	if ( empty($generate_settings) )
		return;
		
	if ( is_admin() && $pagenow == "themes.php" && isset( $_GET['activated'] ) ) {
		echo '<div class="updated below-h2">';
			echo '<p>';
				_e('<strong>Almost done!</strong> Previous GeneratePress options detected in your database. Please <a href="' . admin_url('themes.php?page=generate-options#gen-delete') . '">click here</a> and delete your current options for new to take full effect.','statement');
			echo '</p>';
		echo '</div>';
	}
}

if ( !function_exists( 'statement_defaults' ) ) :
	add_filter( 'generate_option_defaults','statement_defaults' );
	function statement_defaults()
	{
		$statement_defaults = array(
			'hide_title' => '',
			'hide_tagline' => '',
			'logo' => '',
			'container_width' => '1300',
			'header_layout_setting' => 'fluid-header',
			'center_header' => 'true',
			'center_nav' => 'true',
			'nav_alignment_setting' => 'center',
			'header_alignment_setting' => 'center',
			'nav_layout_setting' => 'fluid-nav',
			'nav_position_setting' => 'nav-below-header',
			'nav_search' => 'enable',
			'nav_dropdown_type' => 'hover',
			'content_layout_setting' => 'separate-containers',
			'layout_setting' => 'right-sidebar',
			'blog_layout_setting' => 'right-sidebar',
			'single_layout_setting' => 'right-sidebar',
			'post_content' => 'full',
			'footer_layout_setting' => 'fluid-footer',
			'footer_widget_setting' => '4',
			'back_to_top' => '',
			'background_color' => '#fafafa',
			'text_color' => '#3a3a3a',
			'link_color' => '#1da5bd',
			'link_color_hover' => '#000000',
			'link_color_visited' => '',
		);
		
		return $statement_defaults;
	}
endif;

/**
 * Set default options
 */
if ( !function_exists( 'statement_get_color_defaults' ) ) :
	add_filter( 'generate_color_option_defaults','statement_get_color_defaults' );
	function statement_get_color_defaults()
	{
		$statement_color_defaults = array(
			'header_background_color' => '#1da5bd',
			'header_text_color' => '#ffffff',
			'header_link_color' => '#ffffff',
			'header_link_hover_color' => '#222222',
			'site_title_color' => '#ffffff',
			'site_tagline_color' => '#ffffff',
			'navigation_background_color' => '#ffffff',
			'navigation_text_color' => '#222222',
			'navigation_background_hover_color' => '#ffffff',
			'navigation_text_hover_color' => '#1da5bd',
			'navigation_background_current_color' => '#ffffff',
			'navigation_text_current_color' => '#1da5bd',
			'subnavigation_background_color' => '#1da5bd',
			'subnavigation_text_color' => '#ffffff',
			'subnavigation_background_hover_color' => '#1da5bd',
			'subnavigation_text_hover_color' => '#222222',
			'subnavigation_background_current_color' => '#1da5bd',
			'subnavigation_text_current_color' => '#222222',
			'content_background_color' => '#FFFFFF',
			'content_text_color' => '#3a3a3a',
			'content_link_color' => '',
			'content_link_hover_color' => '',
			'content_title_color' => '',
			'blog_post_title_color' => '#222222',
			'blog_post_title_hover_color' => '#1da5bd',
			'entry_meta_text_color' => '#888888',
			'entry_meta_link_color' => '#666666',
			'entry_meta_link_color_hover' => '#1da5bd',
			'h1_color' => '',
			'h2_color' => '',
			'h3_color' => '',
			'sidebar_widget_background_color' => '#FFFFFF',
			'sidebar_widget_text_color' => '#3a3a3a',
			'sidebar_widget_link_color' => '',
			'sidebar_widget_link_hover_color' => '',
			'sidebar_widget_title_color' => '#000000',
			'footer_widget_background_color' => '#1da5bd',
			'footer_widget_text_color' => '#ffffff',
			'footer_widget_link_color' => '#ffffff',
			'footer_widget_link_hover_color' => '#222222',
			'footer_widget_title_color' => '#ffffff',
			'footer_background_color' => '#ffffff',
			'footer_text_color' => '#222222',
			'footer_link_color' => '#1da5bd',
			'footer_link_hover_color' => '#222222',
			'form_background_color' => '#FAFAFA',
			'form_text_color' => '#666666',
			'form_background_color_focus' => '#FFFFFF',
			'form_text_color_focus' => '#666666',
			'form_border_color' => '#CCCCCC',
			'form_border_color_focus' => '#BFBFBF',
			'form_button_background_color' => '#1da5bd',
			'form_button_background_color_hover' => '#847f67',
			'form_button_text_color' => '#FFFFFF',
			'form_button_text_color_hover' => '#FFFFFF'
		);
		
		return $statement_color_defaults;
	}
endif;

/**
 * Set default options
 */
if ( !function_exists('statement_get_default_fonts') ) :
	add_filter( 'generate_font_option_defaults','statement_get_default_fonts' );
	function statement_get_default_fonts()
	{
		$statement_font_defaults = array(
			'font_body' => 'Open Sans',
			'body_font_weight' => 'normal',
			'body_font_transform' => 'none',
			'body_font_size' => '17',
			'font_site_title' => 'inherit',
			'site_title_font_weight' => 'bold',
			'site_title_font_transform' => 'none',
			'site_title_font_size' => '55',
			'font_site_tagline' => 'inherit',
			'site_tagline_font_weight' => '300',
			'site_tagline_font_transform' => 'none',
			'site_tagline_font_size' => '20',
			'font_navigation' => 'inherit',
			'navigation_font_weight' => 'bold',
			'navigation_font_transform' => 'none',
			'navigation_font_size' => '15',
			'font_widget_title' => 'inherit',
			'widget_title_font_weight' => 'bold',
			'widget_title_font_transform' => 'none',
			'widget_title_font_size' => '23',
			'widget_content_font_size' => '17',
			'font_heading_1' => 'inherit',
			'heading_1_weight' => 'bold',
			'heading_1_transform' => 'none',
			'heading_1_font_size' => '40',
			'font_heading_2' => 'inherit',
			'heading_2_weight' => 'bold',
			'heading_2_transform' => 'none',
			'heading_2_font_size' => '30',
			'font_heading_3' => 'inherit',
			'heading_3_weight' => 'bold',
			'heading_3_transform' => 'none',
			'heading_3_font_size' => '20',
			'font_heading_4' => 'inherit',
			'heading_4_weight' => 'normal',
			'heading_4_transform' => 'none',
			'heading_4_font_size' => '15',
			'footer_font_size' => '17'
		);
		
		return $statement_font_defaults;
	}
endif;

/**
 * Set default options
 */
if ( !function_exists( 'statement_get_spacing_defaults' ) ) :
	add_filter( 'generate_spacing_option_defaults','statement_get_spacing_defaults' );
	function statement_get_spacing_defaults()
	{
		$statement_spacing_defaults = array(
			'header_top' => '60',
			'header_right' => '60',
			'header_bottom' => '60',
			'header_left' => '60',
			'menu_item' => '20',
			'menu_item_height' => '70',
			'sub_menu_item_height' => '10',
			'content_top' => '50',
			'content_right' => '50',
			'content_bottom' => '50',
			'content_left' => '50',
			'separator' => '40',
			'left_sidebar_width' => '25',
			'right_sidebar_width' => '25',
			'widget_top' => '50',
			'widget_right' => '50',
			'widget_bottom' => '50',
			'widget_left' => '50',
			'footer_widget_container_top' => '50',
			'footer_widget_container_right' => '0',
			'footer_widget_container_bottom' => '50',
			'footer_widget_container_left' => '0',
			'footer_top' => '20',
			'footer_right' => '0',
			'footer_bottom' => '20',
			'footer_left' => '0',
		);
		
		return $statement_spacing_defaults;
	}
endif;