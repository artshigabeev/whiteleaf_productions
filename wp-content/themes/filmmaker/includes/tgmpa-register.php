<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.5.2
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once(get_template_directory().'/libs/class-tgm-plugin-activation.php');
add_action( 'tgmpa_register', 'filmmaker_theme_register_required_plugins' );
function filmmaker_theme_register_required_plugins() {
    $plugins = array(
        array (
            'name' => 'Master Slider WP',
            'slug' => 'masterslider',
            'source' => PLUGINS_PATH.'/general/masterslider-installable.zip',
            'required' =>true,
            'version' => '2.29.0',
            'force_activation' => false,
            'force_deactivation' => false,
            'external_url' => 'http://codecanyon.net/item/masterslider-pro/7467925?ref=Beautheme'
        ),
        array (
            'name' => 'WPBakery Visual Composer',
            'slug' => 'js_composer',
            'source' => PLUGINS_PATH.'/general/js_composer.zip',
            'required' =>true,
            'version' => '4.12',
            'force_activation' => false,
            'force_deactivation' => false,
            'external_url' => 'http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=Beautheme'
        ),
		array (
            'name' => 'Plugin Filmmaker',
            'slug' => 'plg-film-maker',
            'source' => PLUGINS_PATH.'/filmmaker/plg-film-maker.zip',
            'required' =>true,
            'version' => '1.0.4',
            'force_activation' => false,
            'force_deactivation' => false,
        ),
        array (
            'name' => 'Contact Form 7',
            'slug' => 'contact-form-7',
            'source' => 'https://downloads.wordpress.org/plugin/contact-form-7.4.4.2.zip',
            'required' =>true,
            'version' => '4.4.2',
            'force_activation' => false,
            'force_deactivation' => false,
            'external_url' => 'https://wordpress.org/plugins/contact-form-7/'
        ),
    );
    $config = array(
        'id'           => 'tgmpa',
        'default_path' => 'filmmaker-active-plugins',
        'menu'         => 'beautheme-install-plugins',
        'parent_slug'  => 'themes.php',
        'capability'   => 'edit_theme_options',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
    );


    tgmpa( $plugins, $config );
}
