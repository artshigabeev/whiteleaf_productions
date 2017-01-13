<?php
get_header(); ?>

<section class="content-blog-1">
    <div class="container">
        <div class="col-lg-8 col-md-9 col-sm-12 col-xs-12 nopadding">
            <?php if (have_posts()) { ?>
            <?php if (is_search()) {
                    echo '<h2 class="film-title-news">';
                    esc_html_e('Search with keywords:','filmmaker'); printf('%s', $_REQUEST['s']);
                    echo '</h2>';
                }?>
            <ul class="list-post-item">
                <?php
                while (have_posts() ) :the_post();
                    ?>
                <li id="post-<?php the_ID(); ?>" <?php post_class("post-item col-lg-12 col-md-12 col-sm-12 col-xs-12"); ?>>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <div class="post-image col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="date-post">
                                <ul>
                                    <li><?php echo get_the_date('d'); ?></li>
                                    <li><?php echo get_the_date('m'); ?></li>
                                </ul>
                            </div>
                            <?php if (has_post_thumbnail( get_the_ID())) { ?>
                                <div class="img-black">
                                    <?php
                                        the_post_thumbnail('filmmaker_BE');
                                     ?>
                                </div>
                            <?php
                                }else{
                                    echo '<div class="no-img-fl"></div>';
                                }
                            ?>
                        </div>
                    </a>
                    <div class="post-info post-info2 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="info">
                            <ul class="more-info">
                                <li class="name1"><?php esc_html_e('by :','filmmaker'); ?><?php echo get_the_author_link();?> </li>
                                <li><i class="fa fa-comment-o"></i><a href="<?php the_permalink();?>"><?php echo get_comments_number()?></a></li>
                                <li><i class="fa fa-tag"></i><a href="<?php the_permalink();?>"><?php the_category( ', ' ); ?></a></li>
                            </ul>
                        </div>
                        <a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                            <div class="title-post">
                                <?php the_title()?>
                            </div>
                        </a>
                        <div class="gray-desc">
                            <?php echo wp_trim_words(get_the_content(), 40,'...') ?>
                        </div>

                        <span class="more"><a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php esc_html_e('View more','filmmaker')?><i class="fa fa-long-arrow-right"></i></a></span>
                    </div>
                </li>
                <?php endwhile; ?>
            </ul>
            <div class="clearfix"></div>
            <div class="fl-pagination"><?php echo filmmaker_pagination(); ?></div>
            <?php }?>
            <?php
                if (!have_posts()) {
                    echo '<div class="s-not-found">
                        '.esc_html__('Search not found','filmmaker').'
                        <p><a href="'.esc_url(home_url("/")).'" title="'.esc_html__('Home Page','filmmaker').'">
                                '.esc_html__('Back to home.','filmmaker').'
                            </a>
                        </p>
                        <div class="not-search-form">
                            <form method="GET" action="'.esc_url(home_url("/")).'">
                                <input  class="s-input" type="text" name="s" value="" placeholder="'.esc_attr('Your search','filmmaker').'">
                                <span class="sb-icon-search"><i class="fa fa-search"></i></span>
                            </form>
                        </div>
                    </div>';
                }
             ?>
        </div>

        <div class="side-bar-2 archive-sidebar side-bar col-lg-4 col-md-3 hidden-sm hidden-xs">
            <?php
                if (function_exists("dynamic_sidebar")) {
                    if ( is_active_sidebar( 'sidebar-blog-widget' ) ){
                        dynamic_sidebar( 'sidebar-blog-widget' );
                    }

                    else{ ?>
                        <div class="right-widget single-post-widget">
                            <h2 class="widget-title title-gray right-widget-title black"><?php _e('Recent posts','filmmaker')?></h2>
                            <?php
                            if(is_category() || is_single()){
                              $cat = get_category_by_path(get_query_var('category_name'),false);
                              $current = $cat->cat_ID;
                              $current_name = $cat->cat_name;
                              $current_cat_slug = $cat->slug;
                            }
                            else{
                                $current = $current_name = $current_cat_slug ='';
                            }
                            $args = array(
                                'post_type'         => 'post',
                                'posts_per_page'    => '3',
                                'paged'             => $paged,
                                'category'          => $current,
                                'category_name'     => $current_name,
                                'orderby'           => 'post_date',
                                'order'             => 'DESC',
                            );
                            $postType = new WP_Query( $args);
                            ?>
                            <div class="right-widget-content">
                                <ol>
                                    <?php
                                    if ($postType->have_posts()) {
                                        while ($postType->have_posts()) {
                                        $postType->the_post();
                                        $link_post      =  esc_attr(get_post_permalink());
                                        $title_post     =  esc_attr(get_the_title());
                                        $image          = get_the_post_thumbnail( $post->ID, 'thumbnail');
                                        // if ($image=="") {
                                        //  $image      = '<img src="http://placehold.it/940x540" alt="No image">';
                                        // }
                                    ?>
                                        <li class="post-latest-item">
                                            <?php if(has_post_thumbnail()){ ?>
                                            <div class="nopadding col-lg-6">
                                                <div class="img-post">
                                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                                        <?php
                                                                the_post_thumbnail('filmmaker_BE');

                                                        ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="nopadding col-lg-6">
                                                <div class="title-side-bar">
                                                    <div class="info"><?php echo get_the_date('j M Y'); ?></div>
                                                    <div class="title-post">
                                                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                                            <?php echo wp_trim_words(get_the_title(), 6, '...');  ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php }?>
                                </ol>
                            </div>
                            <?php }?>
                        </div>

                        <div class="right-widget category-post-widget">
                            <h2 class="widget-title title-gray right-widget-title black"><?php _e('Categories','filmmaker')?></h2>
                            <?php
                            $args = array(
                                'orderby'            => 'name',
                                'order'              => 'ASC',
                                'style'              => 'list',
                                'show_count'         => 0,
                                'hide_empty'         => 1,
                                'use_desc_for_title' => 1,
                                'child_of'           => 0,
                                'hierarchical'       => 1,
                                'number'             => null,
                                'echo'               => 1,
                                'taxonomy'           => 'category',
                            );
                            $categories = get_categories( $args);
                            $tags = get_tags();
                        ?>
                            <div class="right-widget-content">
                                <div class="category_post" >
                                    <ul>
                                        <?php foreach ($categories as $key => $cat) {?>
                                            <li><a href="<?php echo get_category_link($cat->cat_ID)?>" title="?php printf('%s', $cat->name) ?>"><?php printf('%s', $cat->name) ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="right-widget tags category-post-widget">
                            <h2 class="widget-title title-gray right-widget-title black"><?php _e('Tags','filmmaker')?></h2>
                            <div class="right-widget-content">
                                <div class="category_post" >
                                    <ul>
                                    <?php
                                    $tags = get_tags( array('orderby' => 'count', 'order' => 'DESC', 'number'=>10) );
                                        foreach ( (array) $tags as $tag ) {
                                    ?>
                                        <li><a href="<?php echo get_tag_link ($tag->term_id)?>" rel="tag" title="<?php printf('%s', $tag->name ); ?>"><?php printf('%s', $tag->name ); ?></a></li>
                                    <?php }?>
                                    </ul>
                                </div>
                            </div>
                        </div>
            <?php   }
                }
            ?>
        </div><!-- side-bar -->
    </div><!-- container -->
</section>

<?php get_footer();
?>