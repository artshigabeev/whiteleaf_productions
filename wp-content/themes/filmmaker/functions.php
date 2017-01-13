<?php
define('FILMMAKER_BASE_URL', get_template_directory_uri());
define('FILMMAKER_BASE', get_template_directory());
define('FILMMAKER_IMAGES', FILMMAKER_BASE_URL .'/asset/images/');
define('FILMMAKER_JS', FILMMAKER_BASE_URL .'/asset/js');
define('FILMMAKER_CSS', FILMMAKER_BASE_URL .'/asset/css');
define('PLUGINS_PATH', 'http://plugins.beautheme.com');
define('PLUGINS_PATH_REQUIRE', FILMMAKER_BASE.'/includes/');
define('PLUGINS_PATH_LIBS', FILMMAKER_BASE.'/libs/');

$language_folder = FILMMAKER_BASE .'/languages';
load_theme_textdomain( 'filmmaker', $language_folder );

if (!class_exists('filmmaker_ThemeFunction')) {
    class filmmaker_ThemeFunction {
        public function __construct(){

            $this -> filmmaker_Get_files();
        }

        public function filmmaker_Get_files(){
            $files = scandir(get_template_directory().'/includes/');
            foreach ($files as $key => $file) {
                if (preg_match("/\.(php)$/", $file)) {
                    require_once(get_template_directory().'/includes/'.$file);
                }
            }
        }
    }
    new filmmaker_ThemeFunction;
}

if ( ! isset( $content_width ) ) $content_width = 900;

function filmmaker_GetOption($option){
    global $filmmaker_option;
    if (isset($filmmaker_option[$option])) {
        $option =  $filmmaker_option[$option];
    }else{
        $option = NULL;
    }
    return $option;
}

function filmmaker_theme_support() {
    add_theme_support( "nav-menus" );
    add_theme_support( "automatic-feed-links" );
    add_theme_support( "post-thumbnails" );
    add_theme_support( "title-tag" );
    add_theme_support( "custom-header", array());
    add_theme_support( "custom-background", array()) ;
    add_theme_support( 'page-attributes', array());
    add_editor_style();
    //cut thumbnail
    add_image_size("filmmaker_BE", 960,540, true);
    add_image_size("filmmaker_SM",320,180, true);
    add_image_size("filmmaker_DC",365,500, true);
    add_image_size("filmmaker_CF",1440,810, true);
    add_image_size("filmmaker_VD",565,316, true);
    add_theme_support( "post-formats", array(
         'image', 'video', 'audio',
    ));
    $nav_menus['main-menu'] = __( 'Main menu', 'filmmaker');
    register_nav_menus( $nav_menus );
}
add_action( 'after_setup_theme', 'filmmaker_theme_support' );


function filmmaker_scripts(){
    // Lib jquery
    // global $filmmaker_option;
    // Js count down
    wp_enqueue_script('swiper-slider', FILMMAKER_JS .'/swiper.min.js', array('jquery'), '3.2.0', FALSE);
    wp_enqueue_script('modernizr', FILMMAKER_JS .'/beetle.js', array('jquery'), '2.7.1', TRUE );
    wp_enqueue_script('coltrol-modernizr', FILMMAKER_JS .'/plugins.js', array('jquery'), '2.7.1', TRUE);
    wp_enqueue_script('filmmaker_js', FILMMAKER_JS .'/filmjs.js', array('jquery'), '1.0.0', TRUE);

    wp_enqueue_script('coltrol-master', FILMMAKER_JS .'/masterslider/jquery.easing.min.js', array('jquery'), '1.7.2', TRUE);
    wp_enqueue_script('tab-coltrol-master', FILMMAKER_JS .'/masterslider/tab.js', array('jquery'), '1.7.2', TRUE);
    wp_enqueue_script('master-slider', FILMMAKER_JS .'/masterslider/masterslider.min.js', array('jquery'), '1.7.2', FALSE);
    wp_enqueue_script('wow.min', FILMMAKER_JS .'/wow.min.js', array('jquery'), '1.1.2', FALSE);
    wp_enqueue_script('js-timelife', FILMMAKER_JS .'/js-timeline/jquery.timelinr-0.9.54.js', array('jquery'), '0.9.54', FALSE);
    wp_enqueue_script('layout-mode', FILMMAKER_JS .'/layout-mode.js', array('jquery'), '1.11.3', TRUE);
    wp_enqueue_script('isotope-pkgd', FILMMAKER_JS .'/isotope.pkgd.min.js', array('jquery'), '1.11.3', TRUE);
    wp_enqueue_script('jquery-isotop-layout-row', FILMMAKER_JS .'/layout-modes/fit-rows.js', array('jquery'), '1.0', TRUE);
    wp_enqueue_script('jquery-isotop-layout-masonry', FILMMAKER_JS .'/layout-modes/masonry.js', array('jquery'), '1.0', TRUE);
    wp_enqueue_script('jquery-isotop-layout-vertical', FILMMAKER_JS .'/layout-modes/vertical.js', array('jquery'), '1.0', TRUE);
    wp_enqueue_script('jquery-bootstrap', FILMMAKER_JS .'/bootstrap.min.js', array('jquery'), '3.3.5', TRUE);
    // Js timeline
    wp_enqueue_style('bootstrap', FILMMAKER_CSS .'/bootstrap.css', array(), '3.4.1');
    wp_enqueue_style('css-masterslide', FILMMAKER_CSS .'/ms-staff-style.css', array(), '1.7.2');
    wp_enqueue_style('font-awesome', FILMMAKER_CSS .'/font-awesome.min.css', array(), '4.4.0');
    wp_enqueue_style('css-swiper', FILMMAKER_CSS .'/swiper.min.css', array(), '3.2.0');
    wp_enqueue_style('css-animate', FILMMAKER_CSS .'/animate.css', array(), '3.0.4');

    wp_enqueue_style('fonts-Playfair_Display', '//fonts.googleapis.com/css?family=Playfair+Display:400,400italic,700,700italic,900', array(), '');
    wp_enqueue_style('fullpage-style', FILMMAKER_CSS .'/filmmaker.css', array(), '');
    wp_enqueue_style('customvc', FILMMAKER_CSS .'/customvc.css', array(), '1.0');
    wp_enqueue_style('main-css', FILMMAKER_BASE_URL.'/style.css', array(), '1.0');
}
if ( !is_admin() ) {
    add_action( 'wp_enqueue_scripts', 'filmmaker_scripts' );
}
////Register widget for page
function filmmaker_register_sidebar() {
    $col=3;
    $colums= intval(12/$col);
    for ($i=1; $i <= $colums; $i++) {
        register_sidebar(
            array(
                'name' => 'Footer sidebar '.$i,
                'id' => 'sidebar-footer-'.$i,
                'before_widget' => '<div class="footer-widget col-md-'.$col.' col-sm-'.$col.' col-xs-12">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>'
            )
        );
    }
    //Register sidebar for sidebar right
    register_sidebar(
        array(
            'name' =>  sprintf(esc_html__('Right sidebar', 'filmmaker' )),
            'id' => 'sidebar-right',
            'before_widget' => '<div class="right-widget ">',
            'after_widget' => '</div></div>',
            'before_title' => '<h2 class="widget-title title-gray right-widget-title black">',
            'after_title' => '</h2><div class="right-widget-content">'
        )
    );
    register_sidebar(
        array(
            'name' =>  sprintf(esc_html__('Single post sidebar', 'filmmaker' )),
            'id' => 'sidebar-blog-widget',
            'before_widget' => '<div class="right-widget single-post-widget col-md-12">',
            'after_widget' => '</div></div>',
            'before_title' => '<h2 class="widget-title title-gray right-widget-title black">',
            'after_title' => '</h2><div class="right-widget-content">'
        )
    );
}
add_action( 'widgets_init', 'filmmaker_register_sidebar' );
add_action('admin_head', 'filmmaker_custom_css');

function filmmaker_custom_css() {
  echo '<style>
        .wp-core-ui .notice.is-dismissible{
            width: 90%;
        }
  </style>';
}


function filmmaker_runshortcode($url_run = '',$short_code=''){
    global $wp_embed;
    switch ($short_code) {
        case 'embed':
            return $wp_embed->run_shortcode('[embed]'.$url_run.'[/embed]');
            break;
         case 'audio':
            return $wp_embed->run_shortcode('[embed]'.$url_run.'[/embed]');
            break;

        case 'video':
            return $wp_embed->run_shortcode('[embed]'.$url_run.'[/embed]');
            break;

        default:
            return $wp_embed->run_shortcode('[embed]'.$url_run.'[/embed]');
            break;
    }
}
//Theme menu
if ( function_exists('register_nav_menus') )
{
    register_nav_menus(
        array(
            'menu-nav-right' => esc_html__( 'Menu Nav Right', 'filmmaker'),
            'menu-nav-left' => esc_html__( 'Menu Nav Left', 'filmmaker'),
        )
    );
}
// Numbered Pagination
if ( !function_exists( 'filmmaker_pagination' ) ) {
    function filmmaker_pagination($loop='', $range = 4) {
        global $wp_query;
        if ($loop=="") {
            $loop = $wp_query;
        }
        $big = 999999999; // need an unlikely integer
        $curpage = get_query_var('paged');
        if (!$curpage) {
            $curpage = get_query_var('page');
        }        
      
        $pages = paginate_links(array(
            'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, $curpage ),
            'total'     => $loop->max_num_pages,
            'prev_next' => false,
            'type'      => 'array',
            'prev_next' => TRUE,
            'prev_text' => '<i class="fa fa-long-arrow-left"></i>'.esc_html__('PREV','filmmaker'),
            'next_text' => esc_html__('NEXT','filmmaker').'<i class="fa fa-long-arrow-right"></i>',
        ) );                
        
      
        
        if( is_array( $pages ) ) {
            $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
            echo '<ul id="pagination-flickr">';
            foreach ( $pages as $page ) {
                echo "<li>$page</li>";
            }
           echo '</ul>';
        }
    }

}
//search

function filmmaker_search_form( $form ) {
    $form = '<div id="fl-search" class="fl-search">
            <form method="GET" action="'.esc_url(home_url("/")).'">
                <input class="sb-search-input" type="text" name="s" value="" placeholder="'.esc_attr('Your search','filmmaker').'">
                <span class="fl-close">x</span>
                <span class="sb-icon-search sb-click"><i class="fa fa-search"></i></span>
             </form>
         </div>';
    return $form;
}

add_filter( 'get_search_form', 'filmmaker_search_form' );

function filmmaker_getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
        if($count==''){
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return "0 ";
        }
        return $count.' ';

    }
    function filmmaker_setPostViews($postID) {
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if($count==''){
            $count = 0;
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
          }else{
                $count++;
                update_post_meta($postID, $count_key, $count);
        }
    }

// Add specific CSS class by filter
add_filter( 'body_class', 'filmmket_body_class' );
function filmmket_body_class( $body_extra ) {
    $customHeader = get_post_meta(get_the_ID(),'_beautheme_your_custom_header', TRUE);
        if ($customHeader == 'header_imagecover') {
            $body_extra[] = "page-has-cover";
        }else{
            $body_extra[] ='';
        }
    return $body_extra;
}


function filmmaker_filter_image_sizes( $sizes) {
unset( $sizes['thumbnail']);
unset( $sizes['medium']);
unset( $sizes['large']);

return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'filmmaker_filter_image_sizes');

?>