<?php 
/*
 * Plugin Name: Direct
 * Plugin URI: https://wordpress.org/plugins/direct/
 * Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
 * Author: Alex
 * Version: 1.0 Beta
 */

if(!defined('ABSPATH')){
    die;
}

define('PLUGIN_PATH', plugin_dir_path(__FILE__));


var_dump(PLUGIN_PATH);
if(!class_exists('DirectPostType')){
    require_once PLUGIN_PATH . '/inc/class-post-type.php';
}

if(!class_exists('Gamajo_Template_Loader')){
    require_once PLUGIN_PATH . '/inc/class-gamajo-template-loader.php';
    require_once PLUGIN_PATH . '/inc/class-direct-template-loader.php';
}
 
class Direct{

    public function register(){
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_front']);
    }

    public function enqueue_admin(){
        wp_enqueue_style('direct_style_admin', plugins_url('/assets/css/admin/style.css', __FILE__));
        wp_enqueue_script('direct_script_admin', plugins_url('/assets/js/admin/scripts.js', __FILE__), ['jquery'], null, true);
    }

    public function enqueue_front(){
        wp_enqueue_style('direct_style', plugins_url('/assets/css/front/style.css', __FILE__));
        wp_enqueue_script('direct_script', plugins_url('/assets/js/front/scripts.js', __FILE__), ['jquery'], null, true);
    }

    static function activation(){
        flush_rewrite_rules();
    }

    static function deactivation(){
        flush_rewrite_rules();
    }
}
if(class_exists('direct')){
    $direct = new Direct();
    $direct->register();
}

register_activation_hook(__FILE__, [$direct, 'activation']);
register_deactivation_hook(__FILE__, [$direct, 'deactivation']);