<?php
/**
 * Plugin Name: volonteria-reg-users-on-events
 * Description: Registration and end on events
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}


add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'end/event/(?P<slug>[0-9-]+)', [
        'methods' => ['POST'],
        'callback' => 'vol_reg_event',
    ]);
});
add_action('rest_api_init', function(){
    register_rest_route('vl/v1', 'prereg/event/(?P<slug>[0-9-]+)', [
        'methods' => ['POST', 'GET'],
        'callback' => 'vol_prereg_event',
    ]);
});
function vol_reg_event()
{
    global $wpdb;
    if(isset($_POST) && $_POST['user_id']!='' && $_POST['event_id']){
        global $wpdb;
        $user_id = $_POST['user_id'];
        $id = $_POST['event_id'];
        // 2021-10-01 12:00:00
        $status = $wpdb->get_results("SELECT * from reg_events WHERE users_id = $user_id AND event_id = $id");
        $current_time = strtotime(current_time('Y/m/d'));
        // проверка наличия регистрации на мероприятие
        if(count($status)==0){
            return '404';
        }else{
            $delete = $wpdb->delete('reg_events',['users_id'=>$user_id, 'event_id'=>$id]);
            // add hours
            $points = intval($wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $id and meta_key = 'hours_points'")[0]->meta_value);
            $hours = intval($wpdb->get_results("SELECT meta_value FROM wp_usermeta WHERE meta_key = 'mycred_hours' AND user_id = $user_id")[0]->meta_value);
            $hours = strval($hours + $points);
            $res = $wpdb->update('wp_usermeta', ['meta_value'=>$hours], ['meta_key'=>'mycred_hours','user_id' => $user_id]);
            // add points
            $balance = intval($wpdb->get_results("SELECT meta_value FROM wp_usermeta WHERE meta_key = 'mycred_default' AND user_id = $user_id")[0]->meta_value);
            $balance += 10;
            $res2 = $wpdb->update('wp_usermeta', ['meta_value'=>$balance], ['meta_key'=>'mycred_default','user_id' => $user_id]);
            // add to history
            $name = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $id and meta_key = 'event_name'")[0]->meta_value;
            $date = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=$id and meta_key = 'event_time'")[0]->meta_value;
            $timetable = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=$id and meta_key = 'event_timetable'")[0]->meta_value;
            $res3 = $wpdb->insert('cur_events', ['event_id' => $id, 'users_id' => $user_id, 'event_name' => $name, 'event_time' => $date,'event_table' => $timetable]);
            return 'added';
        }
    }
}

function vol_prereg_event()
{
    global $wpdb;
    if(isset($_POST)){
        global $wpdb;
        $user_id = $_POST['user_id'];
        $id = $_POST['event_id'];
        // var_dump($user_id);
        // var_dump($id);
        if(!empty($user_id) && !empty($id)){
            $event_time = strtotime($wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id=$id and meta_key = 'event_time'")[0]->meta_value);
            $us_ids = $wpdb->get_col("SELECT users_id FROM reg_events WHERE event_id = $id");   
            $current_time = strtotime(current_time('Y/m/d'));
            $vol_regged = count($wpdb->get_col("SELECT users_id FROM reg_events WHERE event_id = $id"));
            $vol_need = intval($wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $id AND meta_key = 'event_vol'")[0]->meta_value);
            if ($vol_regged < $vol_need){
                if($current_time < $event_time){
                    if(in_array($user_id, $us_ids)){
                        $res = 'U have been registrated on this event yet';
                    }else{
                        $res = $wpdb->replace('reg_events', ['event_id'=>$id, 'users_id'=>$user_id]);
                    }
                    return $res;
                }else{
                    return 'time is out';
                }
            }else{
                return 'there are no any places';
            }
        }else{
            return 0;
        }
    }
}
