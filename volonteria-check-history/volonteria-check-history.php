<?php
/**
 * Plugin Name: volonteria-check-history
 * Description: Check users history
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}

add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'history',[
        'callback' => "vol_get_history",
        'methods' => ["POST"],
    ]);
});

function vol_get_history()
{
    global $wpdb;
    if(isset($_POST) && $_POST['user_id']!=''){
        $id = $_POST["user_id"];
        $req = $wpdb->get_results("SELECT * FROM cur_events WHERE users_id= $id");
        return $req;
    }
}

