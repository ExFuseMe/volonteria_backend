<?php
/**
 * Plugin Name: volonteria-edit-profile
 * Description: Edit user's profile
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}

add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'edit/profile', [
        'methods' => 'POST',
        'callback' => 'edit_profile',
    ]);
});

function edit_profile(){
    if(isset($_POST)&&$_POST['user_id']!=''){
        global $wpdb; 
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $lastname = $_POST['lastname'];
        $Phone_number = $_POST['Phone_number'];
        $telegram_id = $_POST['telegram_id'];
        $ed_organization = $_POST['ed_organization'];
        $place_of_work = $_POST['place_of_work'];
        $address = $_POST['address'];

        // create all magic tricks
        $arr = [(update_user_meta($user_id, 'surname', $surname)),
        (update_user_meta($user_id, 'lastname',$lastname)),
        (update_user_meta($user_id, 'Phone_number',$Phone_number)),
        (update_user_meta($user_id, 'telegram_id',$telegram_id)),
        (update_user_meta($user_id, 'ed_organization',$ed_organization)),
        (update_user_meta($user_id, 'place_of_work',$place_of_work)),
        (update_user_meta($user_id, 'address', $address))];
        if(in_array('', $arr)){
            return 'Something went wrong, try again later or write to us: volonteriaNk@gmail.com';
        }else{
            return 'Edited';
        }
    }
}