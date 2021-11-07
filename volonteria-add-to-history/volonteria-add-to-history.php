<?php
/**
 * Plugin Name: volonteria-add-to-history
 * Description: Add events to history
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}

function vol_add_history(){
// add to history
    if(isset($_POST) && $_POST['user_id']!='' && $_POST['event_id']){
        global $wpdb;
        $id = $_POST['event_id'];
        $user_id = $_POST['user_id'];
        $name = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $id and meta_key = 'event_name'")[0]->meta_value;
        $date = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=$id and meta_key = 'event_time'")[0]->meta_value;
        $timetable = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=$id and meta_key = 'event_timetable'")[0]->meta_value;
        // 'cur_events', ['event_id' => $id, 'users_id' => $user_id, 'event_name' => $name,'event_time' => $date,'event_table' => $timetable]
        $res = $wpdb->insert('cur_events', ['event_id' => $id, 'users_id' => $user_id, 'event_name' => $name, 'event_time' => $date,'event_table' => $timetable]);
        return 'added';
    }
}