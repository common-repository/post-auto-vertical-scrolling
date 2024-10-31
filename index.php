<?php
/*
Plugin Name: Post Auto Vertical Scrolling
Plugin URI: https://wordpress.org/plugins/post-auto-vertical-scrolling
Description: Any Post or any custom post category you can view Auto Vertical Scrolling bottom to top as like news tiker. Take full control over your WordPress site, build any shortcode paste you can imagine – no programming knowledge required.
Version: 2.2.2
Author: Md. Shahinur Islam
Author URI: https://profiles.wordpress.org/shahinurislam/
*/
//--------------------- Create custom post type ---------------------------//
define( 'PASCS_PLUGIN', __FILE__ );
define( 'PASCS_PLUGIN_DIR', untrailingslashit( dirname( PASCS_PLUGIN ) ) );
require_once PASCS_PLUGIN_DIR . '/include/enqueue.php';
//-------------All post show------------//
function pascs_shortcode_wrapper($atts) {
ob_start();
//set attributies
$atts = shortcode_atts(
	array(
		'post_type' => '',
		'categories' => ''
	), $atts, 'helloshahin'); 
?>	
	<style type="text/css">			 
			.containerScrolling { 			    
			    max-width: 960px; }
			.containerScrolling ul,.containerScrolling li {
				padding: 0;
				list-style-type: none;
				margin: 0;
			}
			.containerScrolling li {
				padding-right: 10px;
				padding-bottom: 10px;
				padding-top: 10px;
				list-style-type: none;
				margin: 0; 
			}

			.hide-scrollbar::-webkit-scrollbar {
				display: none;
			}

			.data-list-Scrolling {
				height: 380px;
                width: 300px;
				padding: 2rem;
				overflow-y: hidden;
			}
			.scrollColor:hover{color:#682607;}
		</style>
		<div class="containerScrolling">
			<ul class="data-list-Scrolling" data-autoscroll style="overflow-y: hidden;"> 
				<?php
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$pascs_main_blog = new WP_Query(array(
						'post_type'=> esc_html($atts['post_type']),
						'posts_per_page'=> get_option('blogidpascs'),
						'paged' => $paged,
						'category_name' => esc_html($atts['categories'])
					));
					if($pascs_main_blog->have_posts())	: 
					while($pascs_main_blog->have_posts())	: $pascs_main_blog->the_post();   ?>
						<li><a class="scrollColor" href="<?php the_permalink();?>"><?php the_title();?>
						</a></li> 
					<?php endwhile; ?>		
				<?php endif;?>
			</ul>
		</div> 			
<?php
    return ob_get_clean();
}
add_shortcode('pascs_autoscrolling_show','pascs_shortcode_wrapper');
// Dashboard Front Show settings page
register_activation_hook(__FILE__, 'pascs_plugin_activate');
add_action('admin_init', 'pascs_plugin_redirect');
function pascs_plugin_activate() {
    add_option('pascs_plugin_do_activation_redirect', true);
}
function pascs_plugin_redirect() {
    if (get_option('pascs_plugin_do_activation_redirect', false)) {
        delete_option('pascs_plugin_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("edit.php?post_type=post&page=pascs_settings");
        }
    }
}
//side setting link
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'pascs_plugin_action_links' );
function pascs_plugin_action_links( $actions ) {
   $actions[] = '<a href="'. esc_url( get_admin_url(null, 'edit.php?post_type=post&page=pascs_settings') ) .'">Settings</a>';
   $actions[] = '<a href="https://m.me/md.shahinur.islam.96" target="_blank">Support for contact</a>';
   return $actions;
}
add_action('admin_menu', 'pascs_register_my_custom_submenu_page'); 
function pascs_register_my_custom_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=post',
        'Settings',
        'Settings',
        'manage_options',
        'pascs_settings',
        'pascs_my_custom_submenu_page_callback' );
} 
function pascs_my_custom_submenu_page_callback() {
    ?>
<h1>
<?php esc_html_e( 'Welcome to Post Auto Scrolling.', 'pascs' ); ?>
</h1>
<h3><?php esc_html_e( 'Copy and paste this shortcode here:', 'pascs' );?></h3>
<p><?php esc_html_e( '[pascs_autoscrolling_show post_type="post" categories="name"]', 'pascs' );?></p>
<br/>
<?php esc_html_e(get_option('blogidpascs')); ?>
<form method="post" action="options.php">
	<?php wp_nonce_field('update-options') ?>	
	<p><strong><?php esc_html_e( 'Put how many item per pages:', 'pascs' );?></strong><br />
	<input type="text" name="blogidpascs" size="45" value="<?php esc_html_e(get_option('blogidpascs')); ?>" />
	<a><?php esc_html_e( 'Example: 10', 'pascs' );?></a>
	</p>
	<p><input type="submit" name="Submit" value="Store Options" /></p>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="blogidpascs" />
</form>
<?php
}
