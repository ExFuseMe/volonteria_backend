<?php
/**
 * Plugin Name: volonteria-get-users-info
 * Description:Get info to profile
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}

add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'get/info', [
        'methods' => ['POST'],
        'callback' => 'get_pi',
    ]);
});

add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'get/photo', [
        'methods' => ['POST'],
        'callback' => 'get_photo',
    ]);
});

function get_pi(){
    $user_id = $_POST['user_id'];
    if(isset($_POST)){
        global $wpdb;
        $req = $wpdb->get_results("SELECT meta_key, meta_value FROM wp_usermeta WHERE (meta_key = 'name' OR meta_key = 'surname' 
        OR meta_key = 'lastname' OR meta_key = 'Dateof_birth' OR meta_key = 'Phone_number' OR meta_key = 'telegram_id' 
        OR meta_key = 'ed_organization' OR meta_key = 'place_of_work' OR meta_key = 'address' OR meta_key = 'gender') AND user_id = $user_id");
        $i = 0;
        $el1 = $req[$i];
        $el2 = $req[$i+1];
        $el3 = $req[$i+2];
        $el4 = $req[$i+3];
        $el5 = $req[$i+4];
        $el6 = $req[$i+5];
        $el7 = $req[$i+6];
        $el8 = $req[$i+7];
        $el9 = $req[$i+8];
        $el10 = $req[$i+9];
        $meta_key = $el1->meta_key;
        $meta_key2 = $el2->meta_key;
        $meta_key3 = $el3->meta_key;
        $meta_key4 = $el4->meta_key;
        $meta_key5 = $el5->meta_key;
        $meta_key6 = $el6->meta_key;
        $meta_key7 = $el7->meta_key;
        $meta_key8 = $el8->meta_key;
        $meta_key9 = $el9->meta_key;
        $meta_value = $el1->meta_value;
        $meta_value2 = $el2->meta_value;
        $meta_value3 = $el3->meta_value;
        $meta_value4 = $el4->meta_value;
        $meta_value5 = $el5->meta_value;
        $meta_value6 = $el6->meta_value;
        $meta_value7 = $el7->meta_value;
        $meta_value8 = $el8->meta_value;
        $meta_value9 = $el9->meta_value;
        $meta_key10 = $el10->meta_key;
        $meta_value10 =  $el10->meta_value;
        $res = [
            $meta_key => $meta_value,
            $meta_key2 => $meta_value2,
            $meta_key3 => $meta_value3,
            $meta_key4 => $meta_value4,
            $meta_key5 => $meta_value5,
            $meta_key6 => $meta_value6,
            $meta_key7 => $meta_value7,
            $meta_key8 => $meta_value8,
            $meta_key9 => $meta_value9,
            $meta_key10 => $meta_value10,
        ];
        return wp_send_json($res);
    }
}

function get_photo(){
    $user_id = $_POST['user_id'];
    if(isset($_POST)){
        global $wpdb;
        $img = wp_get_attachment_image_url($wpdb->get_results("SELECT meta_value FROM wp_usermeta WHERE (meta_key = 'profile_photo') AND user_id = $user_id")[0]->meta_value);
        if ($img == false){
            $img = 'http://t0toro-wordpress.tw1.ru/wp-content/uploads/2021/10/user.png';
        }
        return $img;
    }
}