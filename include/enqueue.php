<?php
//--------------------------- If Add Shortcode use any single page with design ------------- //
function pasce_add_css_js(){    
    //front-end unityg
    //wp_enqueue_style( 'jqueryscripttop', plugin_dir_url(__FILE__) . '../css/jqueryscripttop.css', array(), '1.0.0', 'all' );
    //wp_enqueue_style( 'normalize.min.css', plugin_dir_url(__FILE__) . '../css/normalize.min.css', array(), '1.0.0', 'all' );
    //wp_enqueue_style( 'sticky', plugin_dir_url(__FILE__) . '../css/sticky.css', array(), '1.0.0', 'all' );
    
    wp_enqueue_script('jquery.autoscroll', plugin_dir_url(__FILE__) . '../js/jquery.autoscroll.js' , array('jquery'),'1.0.0',true);   
    
}add_action('wp_enqueue_scripts','pasce_add_css_js');
?>
