<?php
    $logo = $logo_url = '';
    $logo_page_setting = get_post_meta( get_the_ID(), '_beautheme_header_main_logo', TRUE );
    if(filmmaker_GetOption('filmmaker-logo') !=NULL){
        $logo = filmmaker_GetOption('filmmaker-logo');
        $logo_url=$logo['url'];
    }
    if ($logo_page_setting) {
        $logo_url = $logo_page_setting;
    }
?>
<div class="overlay"></div>
<nav class="navbar navbar-fixed-top" id="humber" role="navigation">
    <?php
        if(function_exists('wp_nav_menu')){
            wp_nav_menu( 'theme_location=main-menu&menu_class=nav sidebar-nav');
        }
    ?>
</nav>
 <div id="page-content-wrapper">
    <button type="button" class="hamburger is-closed animated fadeInLeft" data-toggle="offcanvas">
        <i class="hamb-top"></i>
        <i class="hamb-middle"></i>
        <i class="hamb-bottom"></i>
    </button>
    <div id="logo" class="logo lg-home3 ">
        <a href="<?php echo esc_url(home_url('/')) ?>" title="<?php esc_html_e('Logo ','filmmaker'); ?>">
            <img src="<?php echo esc_attr($logo_url)!=''?esc_attr($logo_url):AMOUR_IMAGES.'/logo-black.png'?>" alt="<?php printf('%s', bloginfo('name'))?>" />
        </a>
    </div>
    <?php
        echo ('<div class="searchbox-humber">');
        if (filmmaker_GetOption('enable_searchbar')) {
           echo get_search_form();
        }
        echo '</div>';
    ?>
</div>