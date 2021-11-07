<?php
/**
 * Plugin Name: volonteria-create-db
 * Description: Create db
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}
add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'create', [
        'methods' => ['GET', 'POST'],
        'callback' => 'create_db',
    ]);
});
add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'check', [
        'methods' => ['GET', 'POST'],
        'callback' => 'check_db',
    ]);
});
add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'look', [
        'methods' => ['GET', 'POST'],
        'callback' => 'look',
    ]);
});
function look(){

    global $wpdb;
    return $wpdb->get_results("SELECT * FROM cur_events");
}

function check_db(){
    global $wpdb;
    return $wpdb->query("TRUNCATE table reg_events");
}

function create_db(){
    global $wpdb;
    include_once ABSPATH . '/wp-admin/includes/upgrade.php';
    return maybe_create_table('cur_events', 'CREATE TABLE cur_events(event_id varchar(255), users_id varchar(255), event_name varchar(255), event_time varchar(255), event_table varchar(255))');
}
