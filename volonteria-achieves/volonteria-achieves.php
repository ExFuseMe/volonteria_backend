<?php
/**
 * Plugin Name: volonteria-achieves
 * Description: Render achieves
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}
 // return do_shortcode("[mycred_my_rank ctype='mycred_hours' user_id = $user_id]");
 add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'achieves', [
        'methods' => ['POST'],
        'callback' => 'achieve',
    ]);
});
function achieve(){
    if(isset($_POST)&& $_POST['user_id']!=''){
        $user_id = $_POST['user_id'];
        return do_shortcode("[mycred_my_rank ctype='mycred_hours' user_id = $user_id]"); 
    }
}