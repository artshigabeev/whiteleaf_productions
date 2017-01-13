<?php
/*
* Template Name: Template blogs
*/
get_header();
?>
<!-- category -->
<section>
    <?php
        $argscat = array(
          'orderby' => 'name',
          'parent' => 0,
          'show_count'  => 1,
          'hide_empty'  => 0,
          );
        $categories = get_categories( $argscat );
        $ran_class= rand(11, 99999);
    ?>
    <div class="swiper-container cate-menu cate_rand<?php echo esc_attr($ran_class); ?>">
        <div class="swiper-wrapper ">
            <?php foreach ( $categories as $category ) {
            echo '<div class="swiper-slide">
                    <div class="category-item">
                        <div class="bg-video-category"></div>
                        <div class="img-black">
                            <a href="' . get_category_link( $category->term_id ) . '" title="'.$category->name.'"><img src="'.get_field('image_categories', 'category_' . $category->term_id).'" alt="" />
                            </a>
                        </div>
                        <div class="cat-1">
                            <ul>
                                <a href="' . get_category_link( $category->term_id ) . '" title="'.$category->name.'">
                                    <li class="centertxt">' . $category->name . '</li>
                                    <li class="title-white">('. $category->category_count .')</li>
                                </a>
                            </ul>
                        </div>
                    </div>
                </div>';
            } ?>
        </div>
    </div>
    <script>
        (function($){
            var swiper = new Swiper('.cate_rand<?php echo esc_attr($ran_class); ?>', {
                slidesPerView: 5,
                paginationClickable: true,
                autoplayDisableOnInteraction:true,
                speed:7000,
                breakpoints: {
                // when window width is <= 320px
                320: {
                    slidesPerView: 1,
                },
                // when window width is <= 480px
                480: {
                    slidesPerView: 2,
                },
                // when window width is <= 640px
                640: {
                    slidesPerView: 3,
                },
                1024:{
                    slidesPerView: 4,
                }
              }
            });
        })(jQuery);
    </script>

</section>
<section id="home-blog-template">
    <div class="container-fluid nopadding">
        <div class="row nopadding">
            <div class="wrap col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <ul class="list-post-item">
                    <?php
                    if ( have_posts() ) :
                        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                        $args = array(
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'paged' => $paged,
                            'showposts' => 12,
                        );
                        $postType = new WP_Query( $args);
                        if ($postType->have_posts()) ;
                    while ( $postType->have_posts() ) : $postType->the_post();
                        $post_format  = get_post_format();
                        $audio        = get_post_meta(get_the_ID(), '_beautheme_soud_cloud',TRUE);
                        $audio_file   = get_post_meta(get_the_ID(), '_beautheme_audio_file', TRUE);
                        $video        = get_post_meta(get_the_ID(), '_beautheme_video_embed',TRUE);
                        $video_file   = get_post_meta(get_the_ID(), '_beautheme_video_file',TRUE);
                        $image        = get_the_post_thumbnail(get_the_ID(), 'large');
                        if ($image =='') {
                          $image = '<img alt="'.get_the_title().'" src="http://placehold.it/940x540" alt="No Image">';
                        }
                        $video_thumb = $show_view = "";
                            switch ($post_format) {
                                case 'audio':
                                if ($audio !="") {
                                    $show_view = filmmaker_runshortcode($audio,'embed');
                                }
                                break;

                                case 'video':
                                    if ($video !="") {
                                        $video_thumb = "video_thumb";
                                    }else{
                                        $video_thumb = "";
                                    }
                                break;

                                default:
                                    $show_view = $image;
                                break;
                            }
                    ?>
                        <li class="post-item col-lg-4 col-md-4 col-sm-6 col-sx-12">
                            <a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                                <div class="image-post">
                                    <div class="date-post">
                                        <ul>
                                            <li><?php echo get_the_date('d'); ?></li>
                                            <li><?php echo get_the_date('m'); ?></li>
                                        </ul>
                                    </div>
                                    <div class="img-post <?php printf('%s', $video_thumb);?>">
                                        <?php
                                            if (has_post_thumbnail()) {
                                               the_post_thumbnail();
                                            }else{
                                                echo $image;
                                            } ?>
                                    </div>
                                </div>
                            </a>
                            <div class="info">
                                <ul class="more-info">
                                    <li class="name"><a href="<?php the_permalink();?>" title=""><?php the_category( ', ' ); ?></a></li>
                                    <li class="name1">
                                        <?php esc_html_e('by :','filmmaker'); ?>
                                        <?php the_author(); ?>
                                    </li>
                                    <li><?php filmmaker_setPostViews(get_the_ID()); ?> <?php $view = filmmaker_getPostViews(get_the_ID()); ?><?php echo esc_attr($view); ?> <?php (int) $view > 1 ? esc_html_e('Views','filmmaker') : esc_html_e('View','filmmaker'); ?></li>
                                    <li id="template_share" class="template_share"><a title="<?php esc_html('share','filmmaker'); ?>"><i class="fa fa-share-alt"></i>
                                    </a>
                                    <?php get_template_part('template/social','share'); ?></li>
                                </ul>
                            </div>
                            <a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                                <div class="title-post">
                                   <?php echo get_the_title(); ?>
                                </div>
                            </a>
                        </li>
                    <?php endwhile;?>

                </ul>
                <div class="clearfix"></div>
                <div class="fl-pagination"><?php echo filmmaker_pagination($postType); ?></div>
                <script>
                    (function($) {
                        "use strict";
                            $(window).load(function() {
                                $('.list-post-item').masonry({
                                  itemSelector: '.post-item',
                                  percentPosition: true
                                })
                          });
                    })(jQuery);
                </script>

            </div>
            <div class="side-bar temblog-sidebar col-lg-3 col-md-3 hidden-sm hidden-xs">
                <?php get_sidebar();?>
            </div>

        </div>
    </div>
</section>

<?php
wp_reset_postdata();
endif;
    the_content();

get_footer();
?>