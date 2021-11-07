<?php
/**
 * Plugin Name: volonteria-check-points
 * Description: Check users points
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}

add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'points/check',[
        'callback' => "vol_add",
        'methods' => ["POST"],
    ]);
});

function vol_add()
{
    global $wpdb;
    if(isset($_POST) && $_POST['user_id']!=''){
        $id = $_POST["user_id"];
        $data = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->usermeta WHERE meta_key = 'mycred_default' AND user_id = $id");
        $res = ['user_id'=>$id,$data[0]->meta_key=>$data[0]->meta_value];
        return $res;
    }
    
}
