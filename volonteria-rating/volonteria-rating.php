<?php
/**
 * Plugin Name: volonteria-rating
 * Description: Return rating of users
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}

add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'rating', [
        'methods' => 'GET',
        'callback' => 'vol_rating',
    ]);
});

function vol_rating(){
    global $wpdb;
    $list = $wpdb->get_results("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'mycred_hours'");
    $arr = [];
    foreach($list as $i){
        $hours = intval($i->meta_value);
        array_push($arr, $hours);
    }
    rsort($arr);
    $arr = array_unique($arr);
    $res = [];
    foreach($arr as $i){
        $req = [];
        $list = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'mycred_hours' AND meta_value = $i");
        foreach($list as $j){
            $nickname = $wpdb->get_results("SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'nickname' and user_id = $j->user_id")[0]->meta_value;
            $img = wp_get_attachment_image_url($wpdb->get_results("SELECT meta_value FROM wp_usermeta WHERE (meta_key = 'profile_photo') AND user_id = $j->user_id"));
            if ($img == false){
                $img = 'http://t0toro-wordpress.tw1.ru/wp-content/uploads/2021/10/user.png';
            }
            array_push($req, ['user_login' => $nickname, 'avatar'=>$img]);
        }
        array_push($res, ['hours' =>$i, 'volonteers'=>$req]);
    }
    return $res;
}