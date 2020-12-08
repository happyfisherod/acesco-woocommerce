<?php
/*
 * Plugin Name: PRyC WP: Add custom content to post and page (top/bottom)
 * Plugin URI: http://PRyC.pl
 * Description: Add custom content to post and/or page (top/bottom). You may use text, HTML, Shortcodes and JavaScript. Simple, but work...
 * Author: PRyC
 * Author URI: http://PRyC.pl
 * Version: 2.6.1
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

 
/* Copyright PRyC (email: kontakt@PRyC.pl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/


if ( ! defined( 'ABSPATH' ) ) exit;


if ( !function_exists("pryc_wp_add_custom_content_to_bottom_of_post") ) {
	
	
	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
	
	if ( ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_2'] ) ) && ( !empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_2'] ) ) && is_numeric( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_2'] ) ) {
					
			$function_custom_priotyty = ( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_2'] );
	
	}
	
	//$function_custom_priotyty = '99999';
	
		
	function pryc_wp_add_custom_content_to_bottom_of_post($content) {
	
		$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
		
		// Exclude some page(s)
		$page_id_exclude_array = explode(',', $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_1']);
		
		// Exclude some post(s)
		$post_id_exclude_top_array = explode(',', $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_text_field_1']);
		$post_id_exclude_bottom_array = explode(',', $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_text_field_1']);
		
		
		
		#if ( is_single() ) {
		if ( ( is_single() ) || ( ( ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_2'] ) ) && ( !empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_2'] ) ) ) && is_singular() ) && ( ( empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_1'] ) ) || ( !is_page( $page_id_exclude_array ) ) ) ) {
			
			/* Top contet */
			if ( ( ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_textarea_field_1'] ) ) && ( !empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_textarea_field_1'] ) ) && ( !isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_checkbox_field_1'] ) ) && ( empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_checkbox_field_1'] ) ) ) && ( !is_single( $post_id_exclude_top_array ) ) ) {
			
			$content = '<!-- PRyC WP: Add custom content to top of post/page --><div id="pryc-wp-acctp-top">' . stripslashes( $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_textarea_field_1'] ) . '</div><!-- /PRyC WP: Add custom content to top of post/page --><!-- PRyC WP: Add custom content to bottom of post/page: Standard Content START --><div id="pryc-wp-acctp-original-content">' . $content;
			
			} else {
				$content = '<!-- PRyC WP: Add custom content to bottom of post/page: Standard Content START --><div id="pryc-wp-acctp-original-content">' . $content;
			}
			
			/* Bottom contet */
			if ( ( ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_textarea_field_1'] ) ) && ( !empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_textarea_field_1'] ) ) && ( !isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_checkbox_field_1'] ) ) && ( empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_checkbox_field_1'] ) ) ) && ( !is_single( $post_id_exclude_bottom_array ) ) ) {
			
			$content = $content . '<!-- PRyC WP: Add custom content to bottom of post/page: Standard Content START --></div><!-- PRyC WP: Add custom content to bottom of post/page --><div id="pryc-wp-acctp-bottom">' . stripslashes( $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_textarea_field_1'] ) . '</div><!-- /PRyC WP: Add custom content to bottom of post/page -->';
			
			} else {
				$content = $content . '<!-- PRyC WP: Add custom content to bottom of post/page: Standard Content END --></div>';
			}
			
		

			return $content;
		
		} else { return $content; } # Page etc.
	}

	add_filter( 'the_content', 'pryc_wp_add_custom_content_to_bottom_of_post', $function_custom_priotyty );
}

/* ----- WP Admin ----- */

add_action( 'admin_menu', 'pryc_wp_add_custom_content_to_bottom_of_post_add_admin_menu' );
add_action( 'admin_init', 'pryc_wp_add_custom_content_to_bottom_of_post_settings_init' );

# Menu
function pryc_wp_add_custom_content_to_bottom_of_post_add_admin_menu() { 

	add_options_page( 'PRyC WP: Add custom content', 'PRyC WP: Add custom content', 'manage_options', 'pryc_wp_add_custom_content_to_bottom_of_post', 'pryc_wp_add_custom_content_to_bottom_of_post_options_page' );

}

# Prepare
function pryc_wp_add_custom_content_to_bottom_of_post_settings_init() { 

	register_setting( 'PRyC WP: Add custom content (pluginPage)', 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );

	# Define section 2 + header h2 (info)
	add_settings_section(
		'pryc_wp_add_custom_content_to_bottom_of_post_section2', 
		__( 'Top content settings:', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_settings_section_callback2', 
		'PRyC WP: Add custom content (pluginPage)'
	);
	
	# Define section 1 + header h2 (info)
	add_settings_section(
		'pryc_wp_add_custom_content_to_bottom_of_post_section1', 
		__( 'Bottom content settings:', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_settings_section_callback1', 
		'PRyC WP: Add custom content (pluginPage)'
	);
	
	# Define section 3 + header h2 (info)
	add_settings_section(
		'pryc_wp_add_custom_content_to_bottom_of_post_section3', 
		__( 'Other settings:', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_settings_section_callback3', 
		'PRyC WP: Add custom content (pluginPage)'
	);
	
	# Disable content (top)
	add_settings_field( 
		'pryc_wp_add_custom_content_to_bottom_of_post_section2_checkbox_field_1', 
		__( 'Don\'t display content (top):', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_section2_checkbox_field_1_render', 
		'PRyC WP: Add custom content (pluginPage)', 
		'pryc_wp_add_custom_content_to_bottom_of_post_section2' 
	);
	
	# Define content field (top)
	add_settings_field( 
		'pryc_wp_add_custom_content_to_bottom_of_post_section2_textarea_field_1', 
		__( 'Content (top):', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_section2_textarea_field_1_render', 
		'PRyC WP: Add custom content (pluginPage)', 
		'pryc_wp_add_custom_content_to_bottom_of_post_section2' 
	);
	
	# Add post exclude (top)
	add_settings_field( 
		'pryc_wp_add_custom_content_to_bottom_of_post_section2_text_field_1', 
		__( 'Don\'t show at post:', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_section2_text_field_1_render', 
		'PRyC WP: Add custom content (pluginPage)', 
		'pryc_wp_add_custom_content_to_bottom_of_post_section2' 
	);
	

	# Disable content (bottom)
	add_settings_field( 
		'pryc_wp_add_custom_content_to_bottom_of_post_section1_checkbox_field_1', 
		__( 'Don\'t display content (bottom):', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_section1_checkbox_field_1_render', 
		'PRyC WP: Add custom content (pluginPage)', 
		'pryc_wp_add_custom_content_to_bottom_of_post_section1' 
	);
	
	# Define content field (bottom)
	add_settings_field( 
		'pryc_wp_add_custom_content_to_bottom_of_post_section1_textarea_field_1', 
		__( 'Content (bottom):', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_section1_textarea_field_1_render', 
		'PRyC WP: Add custom content (pluginPage)', 
		'pryc_wp_add_custom_content_to_bottom_of_post_section1' 
	);
	
	# Add post exclude (bottom)
	add_settings_field( 
		'pryc_wp_add_custom_content_to_bottom_of_post_section1_text_field_1', 
		__( 'Don\'t show at post:', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_section1_text_field_1_render', 
		'PRyC WP: Add custom content (pluginPage)', 
		'pryc_wp_add_custom_content_to_bottom_of_post_section1' 
	);
	

	# Add to page
	add_settings_field( 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_2', 
		__( 'Add to pages:', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_2_render', 
		'PRyC WP: Add custom content (pluginPage)', 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3' 
	);
	
	# Add to page exclude
	add_settings_field( 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_1', 
		__( 'Don\'t show at pages:', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_1_render', 
		'PRyC WP: Add custom content (pluginPage)', 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3' 
	);
	
	# Function priority
	add_settings_field( 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_2', 
		__( 'Set custom priority:', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_2_render', 
		'PRyC WP: Add custom content (pluginPage)', 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3' 
	);
	
	# Clear at uninstall
	add_settings_field( 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_1', 
		__( 'Clear plugin data:', 'pryc_wp_add_custom_content_to_bottom_of_post' ), 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_1_render', 
		'PRyC WP: Add custom content (pluginPage)', 
		'pryc_wp_add_custom_content_to_bottom_of_post_section3' 
	);

}	
	
# Make disable (top)
function pryc_wp_add_custom_content_to_bottom_of_post_section2_checkbox_field_1_render() { 

	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
	?>
	<input type='checkbox' name='pryc_wp_add_custom_content_to_bottom_of_post_settings[pryc_wp_add_custom_content_to_bottom_of_post_section2_checkbox_field_1]' <?php if ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_checkbox_field_1'] ) ) { checked( $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_checkbox_field_1'], 1 ); } ?> value='1'>
	
	<?php
	
	echo __( 'If chcecked - don\'t show content (top)', 'pryc_wp_add_custom_content_to_bottom_of_post' );

}	
	
# Make disable (bottom)
function pryc_wp_add_custom_content_to_bottom_of_post_section1_checkbox_field_1_render() { 

	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
	?>
	<input type='checkbox' name='pryc_wp_add_custom_content_to_bottom_of_post_settings[pryc_wp_add_custom_content_to_bottom_of_post_section1_checkbox_field_1]' <?php if ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_checkbox_field_1'] ) ) { checked( $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_checkbox_field_1'], 1 ); } ?> value='1'>
	
	<?php
	
	echo __( 'If chcecked - don\'t show content (bottom)', 'pryc_wp_add_custom_content_to_bottom_of_post' );

}	

# Make content field (top)
function pryc_wp_add_custom_content_to_bottom_of_post_section2_textarea_field_1_render() { 

	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
	?>
	
	<textarea cols='' rows='21' style='width:100%' name='pryc_wp_add_custom_content_to_bottom_of_post_settings[pryc_wp_add_custom_content_to_bottom_of_post_section2_textarea_field_1]'><?php if ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_textarea_field_1'] ) && !empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_textarea_field_1'] )) { echo $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_textarea_field_1']; } else { echo ""; } ?></textarea>
	
	<?php
	echo '<br /><br />';
	echo __( 'You may use text, HTML, Shortcodes and JavaScript.', 'pryc_wp_add_custom_content_to_bottom_of_post' );
}

# Make content field (bottom)
function pryc_wp_add_custom_content_to_bottom_of_post_section1_textarea_field_1_render() { 

	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
	?>
	
	<textarea cols='' rows='21' style='width:100%' name='pryc_wp_add_custom_content_to_bottom_of_post_settings[pryc_wp_add_custom_content_to_bottom_of_post_section1_textarea_field_1]'><?php if ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_textarea_field_1'] ) && !empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_textarea_field_1'] )) { echo $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_textarea_field_1']; } else { echo ""; } ?></textarea>
	
	<?php
	echo '<br /><br />';
	echo __( 'You may use text, HTML, Shortcodes and JavaScript.', 'pryc_wp_add_custom_content_to_bottom_of_post' );
}


# Add to post exclude (top)
function pryc_wp_add_custom_content_to_bottom_of_post_section2_text_field_1_render() { 

	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
	?>
	<input type='text' name='pryc_wp_add_custom_content_to_bottom_of_post_settings[pryc_wp_add_custom_content_to_bottom_of_post_section2_text_field_1]' value='<?php 
	
	if ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_text_field_1'] ) && !empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_text_field_1'] )) {	
		echo $options['pryc_wp_add_custom_content_to_bottom_of_post_section2_text_field_1']; 
	} else { echo __( '', 'pryc_wp_add_custom_content_to_bottom_of_post' ); }
	
	?>' cols='' style='width:100%' >
	<?php
	
	echo __( 'Post(s) ID, comma separated' );

}

# Add to post exclude (bottom)
function pryc_wp_add_custom_content_to_bottom_of_post_section1_text_field_1_render() { 

	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
	?>
	<input type='text' name='pryc_wp_add_custom_content_to_bottom_of_post_settings[pryc_wp_add_custom_content_to_bottom_of_post_section1_text_field_1]' value='<?php 
	
	if ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_text_field_1'] ) && !empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_text_field_1'] )) {	
		echo $options['pryc_wp_add_custom_content_to_bottom_of_post_section1_text_field_1']; 
	} else { echo __( '', 'pryc_wp_add_custom_content_to_bottom_of_post' ); }
	
	?>' cols='' style='width:100%' >
	<?php
	
	echo __( 'Post(s) ID, comma separated' );

}




# Add to page
function pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_2_render() { 

	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
	?>
	<input type='checkbox' name='pryc_wp_add_custom_content_to_bottom_of_post_settings[pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_2]' <?php if ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_2'] ) ) { checked( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_2'], 1 ); } ?> value='1'>
	
	<?php
	
	echo __( 'Add custom content to pages', 'pryc_wp_add_custom_content_to_bottom_of_post' );

}

# Add to page exclude
function pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_1_render() { 

	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
	?>
	<input type='text' name='pryc_wp_add_custom_content_to_bottom_of_post_settings[pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_1]' value='<?php 
	
	if ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_1'] ) && !empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_1'] )) {	
		echo $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_1']; 
	} else { echo __( '', 'pryc_wp_add_custom_content_to_bottom_of_post' ); }
	
	?>' cols='' style='width:100%' >
	<?php
	
	echo __( 'Page(s) ID, comma separated (if active "add to pages" option)' );

}



# Function priority
function pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_2_render() { 

	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
	?>
	<input type='text' name='pryc_wp_add_custom_content_to_bottom_of_post_settings[pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_2]' value='<?php 
	
	if ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_2'] ) && !empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_2'] )) {	
		echo $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_text_field_2']; 
	} else { echo __( '', 'pryc_wp_add_custom_content_to_bottom_of_post' ); }
	
	?>' cols='' style='width:100%' >
	<?php
	
	echo __( 'Here You can set own priority for the content modifying function. It\'s useful when You use short codes or page builders. E.g. 99, 999, 9999, 99999, 999999... Only digits!' );

}





# Make clear data checkbox
function pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_1_render() { 

	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );
	?>
	<input type='checkbox' name='pryc_wp_add_custom_content_to_bottom_of_post_settings[pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_1]' <?php if ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_1'] ) ) { checked( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_1'], 1 ); } ?> value='1'>
	
	<?php
	
	echo __( 'Remove all plugin data when uninstall this plugin', 'pryc_wp_add_custom_content_to_bottom_of_post' );

}


# Section 1 text/description
function pryc_wp_add_custom_content_to_bottom_of_post_settings_section_callback1() { 

	echo __( 'Add custom content to bottom of post. You may use text, HTML, Shortcodes and JavaScript. Simple, but work...', 'pryc_wp_add_custom_content_to_bottom_of_post' );
	
}

# Section 2 text/description
function pryc_wp_add_custom_content_to_bottom_of_post_settings_section_callback2() { 

	echo __( 'Add custom content to top of post. You may use text, HTML, Shortcodes and JavaScript. Simple, but work...', 'pryc_wp_add_custom_content_to_bottom_of_post' );
	
}

# Section 3 text/description
function pryc_wp_add_custom_content_to_bottom_of_post_settings_section_callback3() { 

	echo __( 'Other plugin settings', 'pryc_wp_add_custom_content_to_bottom_of_post' );
	
}


# Save
function pryc_wp_add_custom_content_to_bottom_of_post_options_page() { 

	?>
	<form action='options.php' method='post'>
		
		<h2>PRyC WP: Add custom content to top/bottom of post</h2>
		
		<?php
				
		settings_fields( 'PRyC WP: Add custom content (pluginPage)' );
		do_settings_sections( 'PRyC WP: Add custom content (pluginPage)' );
		submit_button();
		?>
		
	</form>
	<?php
	
	echo __( 'Remember clear CACHE after change settings/content!', 'pryc_wp_add_custom_content_to_bottom_of_post' );
	
	echo '<br /><br />';
	
	echo '<a href="https://cdn.pryc.eu/add/link/?link=paypal-wp-plugin-pryc-wp-add-custom-content-to-post-and-page" target="_blank">' . __( 'Like my plugin? Give for a tidbit for my dogs :-)', 'pryc_wp_antyspam' ) . '</a>';	
	
}

# Uninstall plugin

register_uninstall_hook( __FILE__, 'pryc_wp_add_custom_content_to_bottom_of_post_uninstall' );
#register_deactivation_hook( __FILE__, 'pryc_wp_add_custom_content_to_bottom_of_post_uninstall' );

function pryc_wp_add_custom_content_to_bottom_of_post_uninstall() {

	$options = get_option( 'pryc_wp_add_custom_content_to_bottom_of_post_settings' );

	if ( ( isset( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_1'] ) ) && ( !empty( $options['pryc_wp_add_custom_content_to_bottom_of_post_section3_checkbox_field_1'] ) ) ) {
		
		# Clear at uninstall
		$option_to_delete = 'pryc_wp_add_custom_content_to_bottom_of_post_settings';
		delete_option( $option_to_delete );
	}
	
}


