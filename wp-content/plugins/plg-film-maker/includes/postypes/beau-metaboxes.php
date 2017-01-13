<?php
// Create metabox for custom postype and some post
add_filter( 'cmb_meta_boxes', 'beau_theme_metaboxes' );
function beau_theme_metaboxes( array $meta_boxes ) {
    $prefix = '_beautheme_';
    if(function_exists('get_masterslider_names')){
        $master_sliders = get_masterslider_names();
        if (count($master_sliders) == 0) {
            $master_sliders = array(0=>__('No slider found','filmmaker'));
        }
    }else{
        $master_sliders = array(__('No slider found','filmmaker'));
    }

    $custom_header = array(
        ''          => __('Default on theme option', 'filmmaker' ),
        'default'   => __('Default menu','filmmaker' ),
        'style1'    => __('Header hamburger menu left side','filmmaker' ),
        'style2'    => __('Header two nav','filmmaker' ),
        'style3'    => __('Header 3','filmmaker' ),
    );
    //Custom footer
    $custom_footer = array(
        ''          => __( 'Default on theme option', 'filmmaker' ),
        'default'   => __( 'Standard footer', 'filmmaker' ),
        'noop'      => __( 'No footer display', 'filmmaker' ),
        'style1'    => __( 'Footer using cover image default', 'filmmaker' ),
        'style2'    => __( 'Footer using cover background color default', 'filmmaker' ),
        'style3'    => __( 'Footer text center', 'filmmaker' ),
    );

    //For page and post options header
    $meta_boxes['header_metabox'] = array(
        'id'         => 'header_metabox',
        'title'      => __( 'Chose your custom header & footer', 'filmmaker' ),
        'pages'      => array('page'), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields'     => array(
            array(
                'name'    => __('Chose header type','filmmaker'),
                'id'      => $prefix . 'your_custom_header',
                'type'    => 'radio_inline',
                'options' => array(
                    'header_standard' => __( 'Standard', 'filmmaker' ),
                    'header_imagecover'   => __( 'Image cover', 'filmmaker' ),
                    'header_slider'     => __( 'Using slider', 'filmmaker' ),
                ),
                'default' => 'header_standard',
            ),
            array(
                'name' => __('Your cover','filmmaker'),
                'desc' => __('Upload an image or enter an URL.','filmmaker'),
                'id' => $prefix . 'header_using_cover',
                'allows' => array( 'url', 'attachment' ), // limit to just attachments with array( 'attachment' )
                'type' => 'file',
            ),
            array(
                'name'    => __('Select your slider','filmmaker'),
                'desc'    => __('You must create your slider in Master slider','filmmaker'),
                'id'      => $prefix . 'header_using_slider',
                'type'    => 'select',
                'options' => $master_sliders,
            ),
            array(
                'name'    => __('Select header','filmmaker'),
                'desc'    => __('Chose your header or default on theme option','filmmaker'),
                'id'      =>  $prefix . 'custom_header',
                'type'    =>  'select',
                'options' =>  $custom_header,
                'default' =>  'Default on theme option'
            ),
            array(
                'name' => __('Set logo for page','florist'),
                'desc' => __('Upload an image logo or enter an URL.','florist'),
                'id' => $prefix . 'header_main_logo',
                'allows' => array( 'url', 'attachment' ),
                'type' => 'file',
            ),
        //footer
            array(
                'name'    => __('Select footer','filmmaker'),
                'desc'    => __('Chose your footer or default on theme option','filmmaker'),
                'id'      => $prefix . 'footer_custom',
                'type'    => 'select',
                'options' => $custom_footer,
            ),
        ),
    );

    $meta_boxes['image_metabox'] = array(
        'id'         => 'image_metabox',
        'title'      => __( 'Type image', 'beautheme' ),
        'pages'      => array( 'post', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name' => __( 'Image', 'beautheme' ),
                'desc' => __( 'Upload an image or enter a URL to image.', 'beautheme' ),
                'id'   => $prefix . 'type_image',
                'type' => 'file',
                'allows' => 'url',
            ),
        ),
    );

    //For post type video
    $meta_boxes['video_metabox'] = array(
        'id'         => 'video_metabox',
        'title'      => __( 'Type video', 'beautheme' ),
        'pages'      => array( 'post', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name' => __( 'Embed', 'beautheme' ),
                'desc' => __('Enter Youtube, Vimeo, Soundcloud, etc URL. See supported services at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.','beautheme'),
                'id'   => $prefix . 'video_embed',
                'type' => 'oembed',
            ),
            array(
                'name' => __( 'Upload file', 'beautheme' ),
                'desc' => __( 'Upload an media file in mp4, ogv/ogg webm or enter a URL to video file.', 'beautheme' ),
                'id'   => $prefix . 'video_file',
                'type' => 'file',
                'allows' => 'url',
            ),
        ),
    );

    //For post type audio
    $meta_boxes['audio_metabox'] = array(
        'id'         => 'audio_metabox',
        'title'      => __( 'Type audio', 'beautheme' ),
        'pages'      => array( 'post', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name' => __( 'Soud cloud embed', 'beautheme' ),
                'desc' => __('Enter soud cloud url here','beautheme'),
                'id'   => $prefix . 'soud_cloud',
                'type' => 'oembed',
            ),
            array(
                'name' => __( 'Upload file', 'beautheme' ),
                'desc' => __('You can upload file in mp3, oga, ogg file or type your url in here','beautheme'),
                'id'   => $prefix . 'audio_file',
                'type' => 'file',
                'allows' => 'url',
            ),
        ),
    );
     $meta_boxes['feature_metabox'] = array(
        'id'         => 'feature_metabox',
        'title'      => __( 'Feature post', 'beautheme' ),
        'pages'      => array( 'post', 'film' ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name' => __( 'Feature post ?', 'beautheme' ),
                'desc' => __('You want set this feature_post','beautheme'),
                'id'   => $prefix . 'feature_post',
                'type' => 'checkbox',
                'value' => array( __( 'Yes', 'beautheme' ) => 'yes' ),
            ),
        ),
    );
    // Add other metaboxes as needed
    return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {
    if ( ! class_exists( 'cmb_Meta_Box' ) )
        require_once BEAU_PLUGIN_DIR .'/libs/metaboxes/init.php';

}
