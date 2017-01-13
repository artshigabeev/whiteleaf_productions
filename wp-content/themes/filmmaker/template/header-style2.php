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
<!-- header -->
<div class="menu-olay"></div>
<div class="container">
    <div class="top-header menu-default  ">
        <div id="logo" >
            <a href="<?php echo esc_url(home_url('/')) ?>" title="<?php esc_html_e('Logo ','filmmaker'); ?>">
                    <img src="<?php echo esc_attr($logo_url)!=''?esc_attr($logo_url):AMOUR_IMAGES.'/logo-black.png'?>" alt="<?php printf('%s', bloginfo('name'))?>" />
            </a>
        </div>
        <nav  class="menu-style2" >
            <span class="close-menu"><?php esc_html_e('X','filmmaker'); ?></span>
            <?php
                if(function_exists('wp_nav_menu')){
                    wp_nav_menu( 'theme_location=menu-nav-left&menu_class=active-menu-type2  fl-menu-left');
                }
            ?>
            <?php
                if(function_exists('wp_nav_menu')){
                    wp_nav_menu( 'theme_location=menu-nav-right&menu_class=active-menu-type2 fl-menu-right ');
                }
            ?>
        </nav>
        <a class="toggle-nav" href="#" title="<?php esc_html_e('toggle menu','filmmaker'); ?>"><i class="fa fa-bars"></i></a>
        <?php
            echo '<div class="searchbox">';
            if (filmmaker_GetOption('enable_searchbar')) {
               echo get_search_form();
            }
            echo '</div>';
         ?>
    </div>
</div>