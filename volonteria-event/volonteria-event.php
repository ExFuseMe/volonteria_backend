<?php
/**
 * Plugin Name: volonteria-registration-events
 * Description: Event generation for our site
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}

add_action('init', 'custom_event_type');
// render posts[0] from author
add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'posts', [
        'methods' => 'GET',
        'callback' => 'vol_posts',
    ]);
});
add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'posts/(?P<slug>[0-9-]+)', [
        'methods' => ['GET','POST'],
        'callback' => 'vol_post',
    ]);
});

function vol_post(){
    $id = $_POST['event_id'];
    global $wpdb;
    $data = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta 
    WHERE (meta_key = 'event_name' OR meta_key = 'event_description' 
    OR meta_key = 'event_vol' OR meta_key = 'event_place' 
    OR meta_key = 'event_time' OR meta_key = 'telegram_id' 
    OR meta_key = 'event_target' OR meta_key = 'event_requirements' 
    OR meta_key = 'event_timetable') AND post_id = $id");
    $name = $data[0];
    $desc = $data[1];
    $vol = $data[2];
    $place = $data[3];
    $time = $data[4];
    $telegram_id = $data[5];
    $event_target = $data[6];
    $req = $data[7];
    $timetable = $data[8];
    $res = [
        $name->meta_key=>$name->meta_value,
        $desc->meta_key=>$desc->meta_value,
        $vol->meta_key=>$vol->meta_value,
        $place->meta_key=>$place->meta_value,
        $time->meta_key=>$time->meta_value,
        $telegram_id->meta_key=>$telegram_id->meta_value,
        $event_target->meta_key=>$event_target->meta_value,
        $req->meta_key=>$req->meta_value,
        $timetable->meta_key=>$timetable->meta_value,
        'event_id'=>$id,
    ];
    return [$res];
}

function vol_posts(){ 
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

function custom_event_type(){
    register_post_type('event',[
        'label' => 'Мероприятие',
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'custom-fields'],
    ]);
    $labels = array(
        'name' => 'Event', // Основное название типа записи
        'add_new' => 'Добавить новое',
    );
    $args = [
        'show_admin_column' => true,
        'labels' => $labels,
        'rewrite' => true,
        'query_var' => true,
    ];
    register_taxonomy('events', 'event', $args);
    
}