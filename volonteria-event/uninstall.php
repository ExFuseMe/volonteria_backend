<?php

if(!defined('WP_UNINSTALL_PLUGIN')){
    die;
}

//delete event type from db

// global $wpdb;
// $wpdb -> qiery("DELETE FROM {wpdb-> posts} WHERE custom_event_type IN ("event");");

// remove meta and tax and comments
$events = get_post(arreay('post_type' => 'event', 'numberposts' =>-1));
foreach($events as $event){
    wp_delete_post($event -> ID, true);
}