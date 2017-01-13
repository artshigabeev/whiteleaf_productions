<?php
/*
* Template Name: Template blog and video slider
*/
get_header();
 ?>
    <?php
        $list_video = array(
            'posts_type' => 'post',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'post_format',
                    'field' => 'slug',
                    'terms' => 'post-format-video',
                )
            )
        );
        $loop_video = new WP_Query($list_video);
        wp_reset_postdata();
     ?>
    <?php $g_number = rand(11,999); ?>
    <section class="gallery-slider wow fadeInUp" data-wow-delay="0.3s">
        <div class="g-detail-slider template-video-fl">
            <div class="master-slider  ms-skin-default " id="masterslider<?php echo esc_html($g_number); ?>">
                <?php if($loop_video->have_posts()){
                    while($loop_video->have_posts()): $loop_video->the_post(); ?>
                        <div class="ms-slide">
                            <?php if(has_post_thumbnail()){
                                the_post_thumbnail();
                            }else{
                                echo ' <img alt="'.get_the_title().'" src="http://placehold.it/940x540" alt="No Image"/>';
                            } ?>
                            <div class="video_info">
                                <ul>
                                    <li><a href=""><?php the_category(','); ?></a></li>
                                    <li  ><a href="#" title="<?php esc_html_e('share','filmmaker'); ?>"><i class="fa fa-share-alt"></i></a>
                                     <?php get_template_part('template/social','share'); ?>
                                    </li>
                                </ul>
                                <div class="video-title-template">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                                </div>
                            </div>
                        </div>
                 <?php   endwhile;
                    } ?>

            </div>
        </div>
    </section>
    <script>
        (function($){
            "use strict";
            var sliderg<?php echo esc_html($g_number); ?> = new MasterSlider();
                sliderg<?php echo esc_html($g_number); ?>.control('arrows');
                sliderg<?php echo esc_html($g_number); ?>.setup('masterslider<?php echo esc_html($g_number); ?>' , {
                    width:1100,
                    height:662,
                    space:0,
                    loop:true,
                     view:'wave',
                    layout:'partialview'
            });
        })(jQuery);
    </script>

<section class="blog-03">
    <div class="container">
        <div class="col-lg-8 col-md-9 col-sm-12 col-xs-12 nopadding">
            <ul class="list-post-03">
            <?php
                $noimage = "";
                if ( have_posts() ) :
                // $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                $list = array(
                    'posts_type' => 'post',
                    'order' => 'DESC',
                    'paged' => $paged,
                    'posts_per_page' => 8,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'post_format',
                            'field' => 'slug',
                            'terms' => 'post-format-video',
                            'operator' => 'NOT IN'
                        )
                      )
                );
                $loop = new WP_Query($list);
                if ($loop->have_posts()) ;
                ?>
                <?php while ( $loop->have_posts() ) : $loop->the_post();?>
                <?php
                    if (! has_post_thumbnail()) {
                        $noimage = 'noimage';
                 }else{
                        $noimage = '';
                 }?>
                <?php
                    if ($noimage) {
                        switch ($noimage) {
                        case 'noimage':
                            $post_Class = "no-img-title";
                            $post_Class2 = "no-img";
                            break;
                        default:
                            $post_Class = "";
                            $post_Class2 ="";
                            break;
                        }
                    }else{
                        $post_Class ="";
                        $post_Class2 ="";
                    }
                ?>
                <li class="item">
                    <div class="post-img <?php printf('%s', $post_Class2);?>">
                        <a href="<?php the_permalink();?>">
                            <div class="date-post">
                                <ul>
                                    <li><?php echo get_the_date('d'); ?></li>
                                    <li><?php echo get_the_date('m'); ?></li>
                                </ul>
                            </div>
                            <?php
                                if (has_post_thumbnail()) {
                                    printf(get_the_post_thumbnail());
                                }else{?>
                            <?php }?>
                        </a>
                    </div>
                    <div class="post-t <?php printf('%s', $post_Class);?>">
                        <div class="info">
                            <ul class="more-info">
                                <li><a href="<?php the_permalink();?>"><?php the_category( ', ' ); ?></a></li>
                                <li class="name1"><?php esc_html_e('by:','filmmaker'); ?><?php the_author(); ?></li>
                                <li><a href="<?php the_permalink();?>"><?php filmmaker_setPostViews(get_the_ID()); ?> <?php $view = filmmaker_getPostViews(get_the_ID()); ?><?php echo esc_html($view); ?> <?php (int) $view > 1 ? esc_html_e('VIEWS','filmmaker') : esc_html_e('VIEW','filmmaker'); ?></a></li>
                                <li id="template_share" class="template_share" ><a href="#" title="<?php esc_html_e('share','filmmaker'); ?>"><i class="fa fa-share-alt"></i></a>
                                     <?php get_template_part('template/social','share'); ?>
                                    </li>
                            </ul>
                        </div>
                        <div class="title-post">
                            <a href="<?php the_permalink();?>">
                            <?php echo get_the_title(); ?>
                            </a>
                        </div>
                    </div>
                </li>
                <?php endwhile;
                ?>
            </ul>
            <div  class="centertxt pagi col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php echo filmmaker_pagination($loop); ?>
            </div>
        </div>
        <div class="side-bar side-no-padding col-lg-4 col-md-3 hidden-sm hidden-xs">
            <?php get_sidebar(); ?>
        </div>
    </div>
</section>
<?php wp_reset_postdata(); endif; ?>
<?php
    get_footer();
?>