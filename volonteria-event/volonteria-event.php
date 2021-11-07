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

class VolonteriaEvent
{
    // adding all func
    public function register(){
        //register event
        add_action('init', [$this, 'custom_event_type']);
        // render posts[0] from author
        add_action('rest_api_init', function(){
            register_rest_route('vl/v1', 'posts', [
                'methods' => 'GET',
                'callback' => [$this, 'vol_posts'],
            ]);
        });
        add_action('rest_api_init', function(){
            register_rest_route('vl/v1', 'posts/(?P<slug>[0-9-]+)', [
                'methods' => 'POST',
                'callback' => [$this, 'vol_post'],
            ]);
        });
    }
    function vol_post($event){
        // $data = $wpdb->get_results("SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta 
        // WHERE (meta_key = 'event_name' OR meta_key = 'event_description' 
        // OR meta_key = 'event_vol' OR meta_key = 'event_place' 
        // OR meta_key = 'event_time' OR meta_key = 'telegram_id' 
        // OR meta_key = 'event_target' OR meta_key = 'event_requirements' 
        // OR meta_key = 'event_timetable') AND post_id = $id");
        global $wpdb;
        $id = $_POST['event_id'];
        $data = [];
        $name = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'event_name' AND post_id = $id")[0];
        $desc = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'event_description' AND post_id = $id")[0];
        $vol = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'event_vol' AND post_id = $id")[0];
        $place = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'event_place' AND post_id = $id")[0];
        $time = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'event_time' AND post_id = $id")[0];
        $tg_id = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'telegram_id' AND post_id = $id")[0];
        $target = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'event_target' AND post_id = $id")[0];
        $req = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'event_requirements' AND post_id = $id")[0];
        $timetable = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'event_timetable' AND post_id = $id")[0];
        array_push($data, [
            $name->meta_key => $name->meta_value,
            $desc->meta_key => $desc->meta_value,
            $vol->meta_key =>$vol->meta_value,
            $place->meta_key =>$palce->meta_value,
            $time->meta_key =>$time->meta_value,
            $tg_id->meta_key =>$tg_id->meta_value,
            $target->meta_key =>$target->meta_value,
            $req->meta_key =>$req->meta_value,
            $timetable->meta_key => $timetable->meta_value,
        ]);
        return $data;
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
    

    static function activation(){
        //update rewrite rules
        flush_rewrite_rules();
    }
    static function deactivation(){
        //update rewrite rules
        flush_rewrite_rules();
    }
    static function uninstall(){    
        //do smth with uninstall
    }
    // reg new event from ap(admin page)
    public function custom_event_type(){
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

}
if (class_exists('VolonteriaEvent')){
    $volon_event = new VolonteriaEvent();
    $volon_event->register();
}
