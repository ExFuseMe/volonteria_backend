<?php
/**
 * Plugin Name: volonteria-adding-qr-code
 * Description: Qr-code generation for our site
 * Text Domain: volonteria-plugin
 */
//protect from attacs
if(!defined('ABSPATH')){
    die;
}

class VolonteriaQr{
    public function register()
    {
        add_action('rest_api_init', function(){
            register_rest_route('vl/v1', 'posts/qr/(?P<slug>[0-9-]+)', [
                'methods' => ['POST'],
                'callback' => [$this, 'vol_qr_start'],
            ]);
        });
    }
    public function vol_qr_start($data)
    {
        global $wpdb;
        if(isset($_POST) && $_POST['user_id']!=''){
            $u_id = $_POST["user_id"];
            $user = get_user_by('id', $u_id);
            $roles = (array) $user->roles;
            if (in_array('administrator', $roles)){
                $id = $data['slug'];
                $url = 'http://t0toro-wordpress.tw1.ru/wp-json/vl/v1/prereg/event/'.$id;
                require_once __DIR__ . '/phpqrcode/qrlib.php';
                return QRcode::png($url);;
            }else{
                return '0';
            }
        }
    }
    
}

if (class_exists('VolonteriaQr')){
    $volon_qr = new VolonteriaQr();
    $volon_qr->register();
}

//activate, deactivate and uninstall plugin
register_activation_hook( __FILE__, array( '$volon_qr ', 'activation' ) );
register_deactivation_hook( __FILE__, array( '$volon_qr ', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( '$volon_qr ', 'uninstall' ) );