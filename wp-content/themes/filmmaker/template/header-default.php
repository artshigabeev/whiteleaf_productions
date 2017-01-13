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
<div class="menu-olay"></div>
<div class="container">
    <div class="menu-default">
        <div id="logo" class="logo1 ">
            <a href="<?php echo esc_url(home_url('/')) ?>" title="<?php esc_html_e('Logo ','filmmaker'); ?>">
                <img src="<?php echo esc_attr($logo_url)!=''?esc_attr($logo_url):AMOUR_IMAGES.'/logo-black.png'?>" alt="<?php printf('%s', bloginfo('name'))?>" />
            </a>
        </div>
        <nav id="primary_nav_wrap" >
            <span class="close-menu"><?php esc_html_e('X','filmmaker'); ?></span>
            <?php
                $nav_menu = 'main-menu';
                if ( has_nav_menu( $nav_menu ) ) {
                    wp_nav_menu(
                        array(
                            'theme_location' => $nav_menu,
                            'depth'          => 3,
                            'menu_class'     => 'active-menu-default',
                            'menu_id'        => 'main-nav',
                            'container'      => '',
                        )
                    );
                }else{
                    wp_nav_menu(
                        array(
                            'depth'          => 3,
                            'menu'           => 'main-menu',
                            'menu_class'     => 'active-menu-default',
                            'menu_id'        => 'main-nav',
                            'container'      => '',
                        )
                    );
                }
                echo ('<div class="searchbox">');
                if (filmmaker_GetOption('enable_searchbar')) {
                    echo get_search_form();
                }
                echo '</div>';
            ?>
            <a class="toggle-nav" href="#" title="<?php esc_html_e('toggle menu','filmmaker'); ?>"><i class="fa fa-bars"></i></a>
        </nav>
    </div>
</div>
