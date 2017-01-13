<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if( !class_exists( 'ReduxFramewrk' ) ) {
        if (file_exists( BEAU_PLUGIN_DIR. '/libs/ReduxCore/framework.php')) {
            require_once( BEAU_PLUGIN_DIR. '/libs/ReduxCore/framework.php' );
        }
    }
    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    $archive_page = $mailchimp_list = $custom_header = $custom_footer ="";

    //Columns
    $beau_column = array();
    for($i=1; $i<=4; $i++){
        $beau_column[$i] = $i;
    }
     //Archive
    $archive_page = array(
        'postcatstandard'   => __('Full page','filmmaker'),
        'postcattabs'       => __('Layout with tab','filmmaker'),
        'sidebar'           => __('Archive blog with sidebar','filmmaker'),
    );
    //single
    $single_post  = array(
        'standard'          =>  __('Full page','filmmaker'),
        'showsidebar'       => __('Layout with sidebar','filmmaker'),
    );
    $single_post_video = array(
        'bgblack'           => __('BG black in detail video','filmmaker'),
        'nonebg'            =>__('None background in detai video','filmmaker'),
    );
    //archive film_category
    $archive_film = array(
			'full'        => __('Display list film type Full width','filmmaker'),
            'zigzag'        => __('Display list film type zigzag','filmmaker'),
        );

    $single_gallery = array(
            'slide'         => __('Display with slide' ,'filmmaker'),
            'list'          => __('Full page with image and lightbox','filmmaker'),
        );

    //Beau perpage
    $beau_perpage = array('-1'=>'Show all');
    for($i=1; $i<=50; $i++){
        $beau_perpage[$i] = $i;
    }


    // 404 Page
    $notfound_page = array(
        'section-404'   => __('Type one with 404 image','filmmaker'),
        'section-404-1' => __('Type 2 only search box','filmmaker'),
    );
    // Sidebar list
    $sidebar_list = array(
        '1'  => __('One widget','filmmaker'),
        '2'  => __('Two widgets','filmmaker'),
        '3'  => __('Three widgets','filmmaker'),
        '4'  => __('Four widgets','filmmaker'),

    );

    // Social list
    $social_list = array(
        'facebook'      => 'Facebook',
        'twitter'       => 'Twitter',
        'youtube'       => 'Youtube',
        'google-plus'   => 'Google Plus',
        'pinterest'     => 'Pinterest',
        'linkedin'      => 'Linked in',
        'instagram'     => 'Instagram',
        'github'        => 'GitHub',
        'behance'       => 'Behance',
        'tumblr'        => 'Tumblr',
        'soundcloud'    => 'Sound cloud',
        'dribbble'      => 'Dribbble',
        'rss'           => 'Rss',
        'vimeo'         => 'Vimeo',
        'imdb'         => 'IMDB',
    );

    //Custom footer
    $custom_footer = array(
        'default'       => __( 'Standard footer', 'filmmaker' ),
        'noop'          => __( 'No footer display', 'filmmaker' ),
        'style1'        => __( 'Footer using cover image default', 'filmmaker' ),
        'style2'        => __( 'Footer using cover background color default', 'filmmaker' ),
        'style3'        => __( 'Footer text center', 'filmmaker' ),
    );

    //Get all page
    $allPage = array();
    $args = array(
        'sort_order'    => 'asc',
        'sort_column'   => 'post_title',
        'post_type'     => 'page',
        'post_status'   => 'publish'
    );
    $pages = get_pages($args);
    wp_reset_postdata();
    foreach ($pages as $page) {
        $allPage[$page->post_name] = $page->post_title;
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "filmmaker_option";


    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'submenu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => false,
        // Show the sections below the admin menu item or not
        'menu_title'           => __( 'Theme Options', 'beau-theme-setting' ),
        'page_title'           => __( 'Theme Options', 'beau-theme-setting' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => true,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'     => 'red',
                'shadow'    => true,
                'rounded'   => false,
                'style'     => '',
            ),
            'tip_position'  => array(
                'my'        => 'top left',
                'at'        => 'bottom right',
            ),
            'tip_effect'    => array(
                'show'      => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $args['admin_bar_links'][] = array(
        'id'    => 'redux-docs',
        'href'  => 'http://docs.filmmaker.com/hugo/',
        'title' => __( 'Documentation','filmmaker' ),
    );

    $args['admin_bar_links'][] = array(
        //'id'    => 'redux-support',
        'href'  => 'http://support.filmmaker.com/',
        'title' => __( 'Support Team','filmmaker' ),
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/filmmaker',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/filmmakers',
        'title' => 'Follow us on Twitter',
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://behance.net/filmmaker',
        'title' => 'Find us on behance',
        'icon'  => 'el el-behance'
    );
    $args['share_icons'][] = array(
        'url'   => 'https://dribbble.com/beauvn',
        'title' => 'Find us on dribbble',
        'icon'  => 'el el-dribbble'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        //$args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>','filmmaker' ), $v );
    } else {
        //$args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>','filmmaker' );
    }

    // Add content after the form.
    $args['footer_text'] = __( '<p>Thanks for used our theme <a href="http://filmmaker.com" target="_blank  ">Beau Theme</a>.</p>','filmmaker' );

    Redux::setArgs( $opt_name, $args );

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1','filmmaker' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>','filmmaker' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2','filmmaker' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>','filmmaker' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>','filmmaker' );
    Redux::setHelpSidebar( $opt_name, $content );



     // -> START General option
    Redux::setSection( $opt_name, array(
        'title'            => __( 'General setting','filmmaker' ),
        'id'               => 'general',
        'desc'             => __( 'These are something setting for all page!','filmmaker' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-cogs',
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'General options','filmmaker' ),
        'id'               => 'general-options',
        'subsection'       => true,
        'customizer_width' => '450px',
        'fields'           => array(
            array(
                'id'       => 'filmmaker-logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Main logo', 'filmmaker' ),
                'compiler' => 'true',
                'subtitle' => __( 'Upload any image using the WordPress native uploader', 'filmmaker' ),
                'default'  => array( 'url' => '' ),
            ),
            array(
                'id'       => 'white-logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( '404 Logo', 'filmmaker' ),
                'compiler' => 'true',
                'subtitle' => __( 'Using on 404 page', 'filmmaker' ),
                'default'  => array( 'url' => '' ),
            ),
            array(
                'id'       => 'image404',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Upload 404 image', 'filmmaker' ),
                'compiler' => 'true',
                'subtitle' => __( 'Upload any image using the WordPress native uploader', 'filmmaker' ),
            ),
            array(
                'id'       => 'enable_searchbar',
                'type'     => 'checkbox',
                'title'    => __('Enable search bar', 'filmmaker'),
                'subtitle' => __('Default show search', 'filmmaker'),
                'desc'     => __('This option enable search or not', 'filmmaker'),
                'default'  => '1'
            ),
        ),
    ) );



//Social setting
// -> START blog option
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Social setting','filmmaker' ),
        'id'               => 'social',
        'customizer_width' => '500px',
        'icon'             => 'el el-thumbs-up',
    ) );



    Redux::setSection( $opt_name, array(
        'title'            => __( 'Social link','filmmaker' ),
        'id'               => 'social-link',
        'subsection'       => true,
        'customizer_width' => '450px',
        'fields'           => array(
            array(
                'id'       => 'beau-facebook',
                'type'     => 'text',
                'title'    => __( 'Your facebook url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-twitter',
                'type'     => 'text',
                'title'    => __( 'Your twitter url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-linkedin',
                'type'     => 'text',
                'title'    => __( 'Your linkedin url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-youtube',
                'type'     => 'text',
                'title'    => __( 'Your youtube url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-google-plus',
                'type'     => 'text',
                'title'    => __( 'Your google plus url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-pinterest',
                'type'     => 'text',
                'title'    => __( 'Your pinterest url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-linkedin',
                'type'     => 'text',
                'title'    => __( 'Your linkedin url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-instagram',
                'type'     => 'text',
                'title'    => __( 'Your instagram url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-github',
                'type'     => 'text',
                'title'    => __( 'Your github url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-behance',
                'type'     => 'text',
                'title'    => __( 'Your behance url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-tumblr',
                'type'     => 'text',
                'title'    => __( 'Your tumblr url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-soundcloud',
                'type'     => 'text',
                'title'    => __( 'Your soundcloud url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-dribbble',
                'type'     => 'text',
                'title'    => __( 'Your dribbble url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-rss',
                'type'     => 'text',
                'title'    => __( 'Your rss url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-vimeo',
                'type'     => 'text',
                'title'    => __( 'Your vimeo url', 'filmmaker' ),
            ),
             array(
                'id'       => 'beau-imdb',
                'type'     => 'text',
                'title'    => __( 'Your IMDB url', 'filmmaker' ),
            ),
            array(
                'id'       => 'beau-imdb-pro',
                'type'     => 'text',
                'title'    => __( 'Your IMDB Pro url', 'filmmaker' ),
            ),
        )
    ) );


    Redux::setSection( $opt_name, array(
        'title'            => __( 'Social to show','filmmaker' ),
        'id'               => 'social-link-show',
        'subsection'       => true,
        'customizer_width' => '450px',
        'fields'           => array(
            array(
                'id'       => 'show-social-link',
                'type'     => 'select',
                'multi'    => true,
                'title'    => __( 'Social to show','filmmaker' ),
                'subtitle' => __( 'Select your social link you want to show','filmmaker' ),
                'desc'     => __( 'Chose your social you want to show in your website.','filmmaker' ),
                //Must provide key => value pairs for radio options
                'options'  => array(
                    'facebook'      => 'Facebook',
                    'twitter'       => 'Twitter',
                    'youtube'       => 'Youtube',
                    'google-plus'   => 'Google Plus',
                    'pinterest'     => 'Pinterest',
                    'linkedin'      => 'Linked in',
                    'instagram'     => 'Instagram',
                    'github'        => 'GitHub',
                    'behance'       => 'Behance',
                    'tumblr'        => 'Tumblr',
                    'soundcloud'    => 'Sound cloud',
                    'dribbble'      => 'Dribbble',
                    'rss'           => 'Rss',
                    'vimeo'           => 'Vimeo',
                    'imdb'           => 'IMDB',
                    'imdb-pro'      => 'IMDB Pro'
                ),
                'default'  => array( 'facebook', 'twitter','google-plus' )
            ),

        )
    ) );


    Redux::setSection( $opt_name, array(
        'title'            => __( 'Mailchimp API','filmmaker' ),
        'id'               => 'social-mailchimp',
        'subsection'       => true,
        'customizer_width' => '450px',
        'fields'           => array(
            array(
                'id'       => 'mailchimp-api',
                'type'     => 'text',
                'title'    => __( 'Your mailchimp API','filmmaker' ),
                'subtitle' => __( 'Grab an API Key from <a href="http://admin.mailchimp.com/account/api/" target="_blank">here</a>.','filmmaker' ),
            ),

             array(
                'id'        => 'mailchimp-groupid',
                'type'      => 'text',
                'title'     => __( 'Your group id','filmmaker' ),
                'subtitle'  => __( 'Grab your List\'s Unique Id by going <a href="http://admin.mailchimp.com/lists/" target="_blank">here</a>.<br> Click the "settings" link for the list - the Unique Id is at the bottom of that page.','filmmaker' ),
            ),

        )
    ) );


    Redux::setSection( $opt_name, array(
        'title'            => __( 'Typo setting','filmmaker' ),
        'id'               => 'typo',
        'customizer_width' => '500px',
        'icon'             => 'el el-font',
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'Your custom typo','filmmaker' ),
        'id'               => 'typo-custom',
        'subsection'       => true,
        'customizer_width' => '450px',
        'fields'           => array(
            array(
                'id'       => 'body-text',
                'type'     => 'typography',
                'title'    => __( 'Body Font','filmmaker' ),
                // 'compiler' => array('body *'),
                'output'   => array('body'),
                'subtitle' => __( 'Specify the body font properties.','filmmaker' ),
                'google'   => true,
            ),
            array(
                'id'       => 'h1-element',
                'type'     => 'typography',
                'title'    => __( 'H1 element','filmmaker' ),
                'subtitle' => __( 'Specify the h1 font properties.','filmmaker' ),
                'output'    => array('h1'),
                // 'compiler' => array('h1'),
                'google'   => true,
            ),
            array(
                'id'       => 'h2-element',
                'type'     => 'typography',
                'title'    => __( 'H2 element','filmmaker' ),
                'subtitle' => __( 'Specify the h2 font properties.','filmmaker' ),
                'compiler' => array('h2'),
                'output' => array('h2'),
                'google'   => true,
            ),
            array(
                'id'       => 'h3-element',
                'type'     => 'typography',
                'title'    => __( 'H3 element','filmmaker' ),
                'subtitle' => __( 'Specify the h3 font properties.','filmmaker' ),
                // 'compiler' => array('h3'),
                'output' => array('h3'),
                'google'   => true,
            ),
            array(
                'id'       => 'h4-element',
                'type'     => 'typography',
                'title'    => __( 'H4 element','filmmaker' ),
                'subtitle' => __( 'Specify the h4 font properties.','filmmaker' ),
                // 'compiler' => array('h4'),
                'output'   => array('h4'),
                'google'   => true,
            ),
            array(
                'id'       => 'h5-element',
                'type'     => 'typography',
                'title'    => __( 'H5 element','filmmaker' ),
                'subtitle' => __( 'Specify the h5 font properties.','filmmaker' ),
                // 'compiler' => array('h5'),
                'output'   => array('h5'),
                'google'   => true,
            ),
            array(
                'id'       => 'h6-element',
                'type'     => 'typography',
                'title'    => __( 'H6 element','filmmaker' ),
                'subtitle' => __( 'Specify the h6 font properties.','filmmaker' ),
                // 'compiler' => array('h6'),
                'output' => array('h6'),
                'google'   => true,
            ),
        )
    ) );


// Your header and footer custom
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Header & footer','filmmaker' ),
        'id'               => 'headerfooter',
        'customizer_width' => '500px',
        'icon'             => 'el el-magic',
    ) );

    Redux::setSection( $opt_name, array(
        'title'             => __( 'Custom header','filmmaker' ),
        'id'                => 'headerfooter-header',
        'subsection'        => true,
        'customizer_width'  => '450px',
        'fields'            => array(
            array(
                'id'       => 'header-type',
                'type'     => 'select',
                'title'    => __( 'Chose your header type','filmmaker' ),
                'subtitle' => __( 'This header default for all page','filmmaker' ),
                'options'  => array(
                    'default' => 'Default menu',
                    'style1' => 'Header hamburger menu left side',
                    'style2' => 'Header two nav',
                ),
                'default'=>'default',
            ),

            array(
                'id'        => 'humberger-icon-color',
                'type'      => 'color_rgba',
                'title'     => __( 'Humberger menu color','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                // 'compiler' => array('.header'),
                'output' => array(
                    '.header-white .hamburger.is-closed .hamb-top, .header-white .hamburger.is-closed .hamb-middle, .header-white .hamburger.is-closed .hamb-bottom', '.header-white .hamburger.is-open .hamb-top, .header-white .hamburger.is-open .hamb-bottom'
                ),
                'mode'      => 'background',
            ),


            array(
                'id'        => 'header-text-color1',
                'type'      => 'color_rgba',
                'title'     => __( 'Header Text Color','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                // 'compiler' => array('.header'),
                'output' => array(
                    'header','#primary_nav_wrap .menu-item a','.header-flim .menu-item a','.menu-item a','#primary_nav_wrap ul li a','#primary_nav_wrap ul.active-menu-default > li a'
                ),
                'mode'      => 'color',
                //'validate' => 'colorrgba',
            ),

            array(
                'id'        => 'header-text-sub-cl',
                'type'      => 'color_rgba',
                'title'     => __( 'Header Text Color sub','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                // 'compiler' => array('.header'),
                'output' => array(
                    '#primary_nav_wrap ul ul.sub-menu li a'
                ),
                'mode'      => 'color',
            ),

            array(
                'id'        => 'header-text-color-hover',
                'type'      => 'color_rgba',
                'title'     => __( 'Header Text Color Hover','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                'output' => array(
                    'header','#primary_nav_wrap .menu-item a:hover',
                    '#primary_nav_wrap .menu-item a:focus','#primary_nav_wrap ul li a:focus','#primary_nav_wrap ul li a:hover','#primary_nav_wrap ul li a:focus'
                ),
                'mode'      => 'color',
            ),

            array(
                'id'        => 'header_bg',
                'type'      => 'color_rgba',
                'title'     => __( 'Header background Color','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                'output' => array(
                    'header',
                ),
                'mode'      => 'background',
            ),

            array(
                'id'        => 'dropdown_bg_header',
                'type'      => 'color_rgba',
                'title'     => __( 'Header dropdown hover Color','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                'output' => array(
                    '#primary_nav_wrap .menu-item .sub-menu',
                ),
                'mode'      => 'background',
            ),

            array(
                'id'        => 'dropdown_bg_header111',
                'type'      => 'color_rgba',
                'title'     => __( 'Header dropdown BG Color','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                'output' => array(
                    '#primary_nav_wrap .menu-item .sub-menu','.top-header ul.active-menu-type2 li ul'
                ),
                'mode'      => 'background',
            ),
            array(
                'id'        => 'active_menu',
                'type'      => 'color_rgba',
                'title'     => __( 'Menu default active  color ','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                'output' => array(
                    '#primary_nav_wrap ul.active-menu-default > li.current-menu-item > a','#primary_nav_wrap ul.active-menu-default ul > li.current-menu-item > a','.menu-style2 ul.active-menu-type2 > li.current-menu-item > a, .menu-style2 ul.active-menu-type2 ul > li.current-menu-item > a'
                ),
                'mode'      => 'color',
            ),
            array(
                'id'        => 'header-fixed-menu',
                'type'      => 'button_set',
                'title'     => __( 'Fixed menu','filmmaker' ),
                //'subtitle'  => __( 'On Off subcrible','filmmaker'),
                'options'   => array(
                    '1'     => 'No',
                    '2'     => 'Yes',
                ),
                'default'   => '2'
            ),
            array(
                'id'        => 'background-menu-fixed',
                'type'      => 'color_rgba',
                'title'     => __( 'Back ground menu fixed','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                'output' => array(
                    '.stick-scroll',
                ),
                'mode'      => 'background',
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'             => __( 'Custom footer','filmmaker' ),
        'id'                => 'headerfooter-footer',
        'subsection'        => true,
        'customizer_width'  => '450px',
        'fields'            => array(
            array(
                'id'       => 'footer-layouts',
                'type'     => 'select',
                'multi'    => false,
                'title'    => __( 'Chose your default footer', 'filmmaker' ),
                'subtitle' => __( 'Select defaulr footer for default page', 'filmmaker' ),
                'options'  =>  $custom_footer,
                'default'=>'default',
            ),

            array(
                'id'       => 'img-footer-bg',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Setting footer using cover image ', 'filmmaker' ),
                'compiler' => 'true',
                'subtitle' => __( 'Upload any image using the WordPress native uploader', 'filmmaker' ),
            ),
            array(
                'id'       => 'img-footer-bg-color',
                'type'     => 'color_rgba',
                'url'      => true,
                'title'    => __( 'Setting background footer color', 'filmmaker' ),
                'compiler' => 'true',
                'subtitle' => __( 'Choose Color background', 'filmmaker' ),
                'output'   => array(
                    '.fl-footer3 .fl-footer'
                    ),
                'mode'     => 'background',
            ),
            array(
                'id'        => 'footer-text',
                'type'      => 'color_rgba',
                'title'     => __( 'Footer Text Color','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                // 'compiler' => array('footer'),
                'output'   => array(
                    'footer', '.copyright .text-copyright','.footer-landing .bottom-footer .copyright a','footer .footer-widget .widget-title',
                    'footer .footer-widget .widget-body .menu li a',
                    '.widget-footer .list-social a','.footer-landing .landing-social-author a','footer .bottom-footer .copyright a','.textwidget','.widget-title','.copyright a','top-footer','.widget-title a','.sub-text','.widget-title ul li a','footer .landing-social-author a','.footer-widget ul.menu li a'
                ),
                'mode'      => 'color',
            ),



            array(
                'id'        => 'footer-bottom-bg',
                'type'      => 'color_rgba',
                'title'     => __( 'Footer bottom Color','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                // 'compiler' => array('.bottom-footer'),
                'output'   => array( 'footer .bottom-footer' ),
                'mode'      => 'background',
            ),
            array(
                'id'        => 'footer-bottom-text',
                'type'      => 'color_rgba',
                'title'     => __( 'Footer bottom Text Color','filmmaker' ),
                'subtitle'  => __( 'Gives you the RGBA color.','filmmaker' ),
                // 'compiler' => array('.bottom-footer'),
                'output'   => array( 'footer .bottom-footer .copyright','.copyright a','.text-copyright' ),
                'mode'      => 'color',
            ),
            array(
                'id'       => 'subcribe-footer',
                'type'     => 'text',
                'title'    => __( 'Placeholder subcrible','filmmaker' ),
                'subtitle' => __( 'Feature change text placeholder subcribe','filmmaker' ),
                'default'  => 'SUBCRIBE TO OUR NEWLETTER',
            ),
            array(
                'id'       => 'sub-footer-text',
                'type'     => 'editor',
                'title'    => __( 'Sub text footer','filmmaker' ),
                'subtitle' => __( 'Use any of the features of WordPress editor inside your panel!','filmmaker' ),
                'default'  => 'Spectators are our passion. Creation is our core.',
            ),
            array(
                'id'       => 'filmmaker-footer-text',
                'type'     => 'editor',
                'title'    => __( 'Custom footer','filmmaker' ),
                'subtitle' => __( 'Feature change text Footer bottom','filmmaker' ),
                'default'  => '&copy; 2015 Filmmaker. All rights reserved. Designed by <a href="http://filmmaker.com">filmmaker</a>',
            ),

            array(
                'id'        => 'footer-subcrible',
                'type'      => 'button_set',
                'title'     => __( 'Show Subcribe','filmmaker' ),
                'subtitle'  => __( 'On Off subcrible','filmmaker'),
                'options'   => array(
                    '1'     => 'No',
                    '2'     => 'Yes',
                ),
                'default'   => '2'
            ),

        )
    ) );


/*********************** End option header && footer ******************/
 Redux::setSection( $opt_name, array(
        'title'             => __( 'Custom Single Gallery','filmmaker' ),
        'id'                => 'gallerysingle-list',
        'customizer_width'  => '450px',
        'fields'            => array(
            array(
                'id'       => 'gallery-single',
                'type'     => 'select',
                'multi'    => false,
                'title'    => __( 'Your type display gallery ', 'filmmaker' ),
                'subtitle' => __( 'Select type show gallery', 'filmmaker' ),
                'options'  => $single_gallery,
                'default'  => '1',
            ),
            array(
                'id'       => 'title-single-gallery',
                'type'     => 'textarea',
                'multi'    => false,
                'title'    => __( 'Your Title single gallery', 'filmmaker' ),
            ),
            array(
                'id'       => 'g-lightbox',
                'type'     => 'button_set',
                'title'    => __( 'Enable lightbox','filmmaker' ),
                'subtitle'    => __( 'Function Enable lightbox not spend for type slide','filmmaker' ),
                'options'  => array(
                    '1'    => 'No',
                    '2'    => 'Yes',
                ),
                'default'  => '1'
            ),
        )
    ) );
Redux::setSection( $opt_name, array(
        'title'             => __( 'Option Taxonomy film ','filmmaker' ),
        'id'                => 'option-film',
        'customizer_width'  => '450px',
        'fields'            => array(
            array(
                'id'       => 'option-arch-film',
                'type'     => 'select',
                'multi'    => false,
                'title'    => __( 'Your type display film view list ', 'filmmaker' ),
                'subtitle' => __( 'Select type show film list', 'filmmaker' ),
                'options'  => $archive_film,
            ),
			array(
                'id'       => 'tax-film-enable-pagination',
                'type'     => 'checkbox',              
                'title'    => __('Enable Pagination', 'filmmaker' ),          
            ),
			array(
                'id'       => 'tax-film-order-by',
                'type'     => 'select',
                'options'	=> array(
					'menu_order' => 'Menu Order' ,
					'date' => 'Date published'
				),
                'title'    => __( 'Order By', 'filmmaker' ),
                //'subtitle' => __( 'Function for type zigzag', 'filmmaker' ),
            ),
			array(
                'id'       => 'tax-film-order',
                'type'     => 'select',
                'options'	=> array(
					'asc' => 'ASC' ,
					'desc' => 'DESC'
				),
                'title'    => __( 'Order', 'filmmaker' ),             
            ),
            array(
                'id'       => 'fl-text-small',
                'type'     => 'text',
                'multi'    => false,
                'title'    => __( 'Your small Title type', 'filmmaker' ),
                //'subtitle' => __( 'Function for type zigzag', 'filmmaker' ),
            ),
			array(
                'id'       => 'fl-text-big',
                'type'     => 'text',
                'multi'    => false,
                'title'    => __( 'Your Big Title type', 'filmmaker' ),
                //'subtitle' => __( 'Function for type zigzag', 'filmmaker' ),
            ),
           
            array(
                'id'       => 'fl-description',
                'type'     => 'editor',
                'title'    => __( 'Your Description for type Film', 'filmmaker' ),
                'subtitle' => __( 'Function for type zigzag', 'filmmaker' ),
            ),

        )
    ) );

/*Option archive*/
Redux::setSection( $opt_name, array(
        'title'             => __( 'Option Archive Blog','filmmaker' ),
        'id'                => 'option-layout-archive',
        'customizer_width'  => '450px',
        'fields'            => array(
            array(
                'id'       => 'archive-page',
                'type'     => 'select',
                'multi'    => false,
                'title'    => __( 'Your type display archiver page ', 'filmmaker' ),
                //'subtitle' => __( 'Select type show film list', 'filmmaker' ),
                'options'  => $archive_page,
                'default' => 'postcatstandard'
            ),
        )
    ) );
/* Option Single*/
Redux::setSection( $opt_name, array(
        'title'             => __( 'Option single Post','filmmaker' ),
        'id'                => 'option-layout-single',
        'customizer_width'  => '450px',
        'fields'            => array(
            array(
                'id'       => 'single-post',
                'type'     => 'select',
                'multi'    => false,
                'title'    => __( 'Your type layout display single post', 'filmmaker' ),
                //'subtitle' => __( 'Select type show film list', 'filmmaker' ),
                'options'  => $single_post,
                'default' => 'standard'
            ),
            array(
                'id'       => 'video-detail',
                'type'     => 'select',
                'multi'    => false,
                'title'    => __( 'Your type layout display single post video', 'filmmaker' ),
                //'subtitle' => __( 'Select type show film list', 'filmmaker' ),
                'options'  => $single_post_video,
                'default' => 'nonebg'
            ),
        )
    ) );


