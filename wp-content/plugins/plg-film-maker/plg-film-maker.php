<?php
/*
Plugin Name: Film Maker Postype
Plugin URI: http://beautheme.com
Description: This plugin create all postype and function for Film maker page theme.
Version: 1.0.8
Author: BeauTheme
Author URI: http://beautheme.com
Copyright: BeauTheme
*/
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!defined('BEAU_PLUGIN_URL')){
	define('BEAU_PLUGIN_URL',untrailingslashit( plugins_url( '/', __FILE__ ) ));
}
if(!defined('BEAU_PLUGIN_DIR')){
	define('BEAU_PLUGIN_DIR',untrailingslashit( plugin_dir_path( __FILE__ ) ));
}
define('BEAU_PLUGIN_VER','1.0.2');
define( 'ACF_LITE' , false );
define('BEAU_INCLUDE', BEAU_PLUGIN_DIR.'/includes/');
define('BEAU_ASSET', BEAU_PLUGIN_DIR.'/asset/');
define('BEAU_ASSET_URL', BEAU_PLUGIN_URL.'/asset/');
define('BEAU_ASSET_IMG', BEAU_PLUGIN_URL.'/asset/images/');
function beau_theme_plugin_init() {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( 'beautheme', false, $plugin_dir.'/languages' );
}
add_action('plugins_loaded', 'beau_theme_plugin_init');

/*
 * My really nice dump
 */
if(!function_exists('beau_dump')){
    function beau_dump($var, $label=null, $echo=true)
    {
        // format the label
        $label = ($label===null) ? '' : rtrim($label) . ' ';
        // var_dump the variable into a buffer and keep the output
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        // neaten the newlines and indents
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);       
        if(!extension_loaded('xdebug')) {
            $flags = ENT_QUOTES;
            // PHP 5.4.0+
            if (defined('ENT_SUBSTITUTE')) {
                $flags = ENT_QUOTES | ENT_SUBSTITUTE;
            }
            $output = htmlspecialchars($output, $flags);
        }
        $output = '<pre>'
                . $label
                . $output
                . '</pre>';        
        if ($echo) {
            echo($output);
        }
        return $output;
    }
}

if (!class_exists('beau_ThemePlugin')) {
	class beau_ThemePlugin {
		public function __construct(){
            $this -> beau_getMultipleFile(BEAU_INCLUDE.'/widgets/','php');
            $this -> beau_getMultipleFile(BEAU_INCLUDE.'/postypes/','php');
            add_action('admin_head', array( $this, 'beau_print_styles' ));
            add_action('admin_head', array( $this, 'beau_print_scripts' ));
            add_action('after_setup_theme', array( $this, 'beau_getShortcode' ));
            add_action('after_setup_theme', array( $this, 'beau_getFunction' ));
            add_action('after_setup_theme', array( $this, 'beau_Visualcomposer' ));
            add_action('wp_loaded', array( $this, 'beau_getApis' ));
            add_action('wp_loaded', array( $this, 'beau_getAjaxs' ));
		}
        public function beau_Visualcomposer(){
            if (class_exists('WPBakeryShortCode')) {
                $this -> beau_getMultipleFile(BEAU_INCLUDE.'/visualcomposer/','php');
            }
        }
        public function beau_getFunction(){
            $this -> beau_getMultipleFile(BEAU_INCLUDE.'/functions/','php');
        }
        public function beau_getApis(){
            $this -> beau_getMultipleFile(BEAU_INCLUDE.'/apis/','php');
        }
        public function beau_getAjaxs(){
            $this -> beau_getMultipleFile(BEAU_INCLUDE.'/ajaxs/','php');
        }
        public function beau_getShortcode(){
            $this -> beau_getMultipleFile(BEAU_INCLUDE.'/shortcode/','php');
        }
        public function beau_getWidgets(){
            $this -> beau_getMultipleFile(BEAU_INCLUDE.'/widgets/','php');
        }
		public function beau_print_styles(){
			$this -> beau_getMultipleFile(BEAU_ASSET,'css');
		}
		public function beau_print_scripts(){
			$this -> beau_getMultipleFile(BEAU_ASSET,'js');
		}
		public function beau_getMultipleFile($path, $ext){
			if ($ext == 'css' || $ext == 'js') {
				$files = scandir($path.$ext.'/');
			}else{
				$files = scandir($path.'/');
			}
			$i = rand(10000,99999);
			foreach ($files as $key => $file) {
				if (preg_match("/\.(".$ext.")$/", $file)) {
					$file_id = $ext.'beau-theme'.$i;
					if ($ext == 'css') {
					 	wp_enqueue_style($file_id, BEAU_ASSET_URL.$ext.'/'.$file, array(), BEAU_PLUGIN_VER);
					}
					if ($ext == 'js') {
					 	wp_enqueue_script($file_id, BEAU_ASSET_URL.$ext.'/'.$file, array(), BEAU_PLUGIN_VER);
					}
					if ($ext == 'php') {
					 	require($path.$file);
					}
					$i++;
				}
			}
		}
	}
	new beau_ThemePlugin;
}

