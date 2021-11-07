<?php
/**
 * Plugin Name: volonteria-recent-events
 * Description: Return recent events
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}
add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'recent/posts', [
        'methods' => 'GET',
        'callback' => 'recent_events',
    ]);
});
function recent_events(){ 
    global $wpdb;
    //  or SELECT  WHERE (meta_key = 'event_name' OR meta_key = 'event_description')
    $post_ids = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'event_name'");
    function convertor_for_dump($data)
    {
        global $wpdb;
        $event = [];
        foreach($data as $i){
            $name = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $i->post_id and meta_key = 'event_name'")[0]->meta_value;
            $time = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $i->post_id and meta_key = 'event_time'")[0]->meta_value;
            $place = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $i->post_id and meta_key = 'event_place'")[0]->meta_value;
            $status = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $i->post_id and meta_key = 'status'")[0]->meta_value;
            if($status=="Открыто для регистрации"){
                $arr = ['post_id'=>$i->post_id, 
                'name_value'=>$name, 
                'time_value'=>$time, 
                'place_value'=>$place];
                array_push($event, $arr);
            }
        }
        return wp_send_json($event);
    }
    // convertor_for_dump
    return convertor_for_dump($post_ids);
}