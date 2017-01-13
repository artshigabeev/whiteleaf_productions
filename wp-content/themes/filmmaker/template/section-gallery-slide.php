<?php get_header(); ?>
    <?php
        $projectCat = wp_get_post_terms('gallery_category');
        if(count($projectCat)){
        $projectC = $projectCat[0]->slug;
        }
        else{
        $projectC = "";
        }
        $args = array(
            'post_type'=>'gallery',
            'showposts' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'gallery_category',
                    'terms' => '{$projectC}',
                    'field' => 'slug',
                )
            ),
        );
        $postlist = get_posts($args);
        $posts = array();
        foreach ($postlist as $post) {
            $posts[] += $post->ID;
        }
    ?>
      <?php $g_number = rand(33,999); ?>
        <section class="gallery-slider">
            <div class="g-detail-slider">
                <div class="master-slider  ms-skin-default" id="masterslider<?php echo esc_html($g_number); ?>">
                    <?php if(get_field('gallery_film')){
                           while(has_sub_field('gallery_film')){ ?>
                             <div class="ms-slide">
                                <img src="<?php the_sub_field('image_gallery_film') ?>" alt="<?php the_title(); ?>">
                                <div class="transition-none"><?php the_sub_field('short_description_image'); ?></div>
                                <?php
                                    $video_url = get_sub_field('video_url');
                                    if ($video_url !="") {
                                ?>
                                <a href="<?php echo esc_url($video_url);?>?hd=1&wmode=opaque&controls=1&showinfo=0" data-type="video"><?php esc_html_e('Gallery video','filmmaker');?></a>
                                <?php }?>
                            </div>
                        <?php } ?>
                    <?php  }else{
                        esc_html_e('Not Found image in Gallery','filmmaker');
                    } ?>
                </div>
            </div>
                <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    if (is_object( $prev_post)) {
                        $prevID = $prev_post->ID ;
                    }else{
                        $prevID = '';
                    }
                    if (is_object($next_post)) {
                        $nextID = $next_post->ID ;
                    }else{
                        $nextID = '';
                    }
                 ?>
                <div class="container">
                    <div class="register">
                        <?php if (!empty($prevID)) { ?>
                            <nav class="g-detail-previous">
                                <div class="img">
                                    <div class="title-px textnp">
                                        <p class="icon-long-right"><i class="fa fa-long-arrow-left"></i></p>
                                         <a href="<?php echo get_permalink($prevID); ?>" title="<?php echo get_the_title($prevID); ?>">
                                            <?php esc_html_e('PREVIOUS POST','filmmaker'); ?>
                                        </a>
                                    </div>
                                    <div class="img-black">
                                        <a href="<?php echo get_permalink($prevID); ?>" title="<?php echo get_the_title($prevID); ?>">
                                            <?php echo get_the_post_thumbnail($prevID); ?></a>
                                    </div>
                                    <p class="title-desc">
                                        <?php echo get_the_title($prevID); ?>
                                    </p>
                                </div>
                            </nav>
                        <?php } ?>
                        <?php if (!empty($nextID)) { ?>
                            <nav class="g-detail-next">
                                <div class="img">
                                    <div class="title-px textnp">
                                        <p class="icon-long-right"><i class="fa fa-long-arrow-right"></i></p>
                                        <a href="<?php echo get_permalink($nextID); ?>" title="<?php echo get_the_title($nextID); ?>">
                                            <?php esc_html_e('NEXT POST','filmmaker'); ?>
                                        </a>

                                    </div>
                                    <div class="img-black">
                                         <a href="<?php echo get_permalink($nextID); ?>" title="<?php echo get_the_title($nextID); ?>">
                                            <?php echo get_the_post_thumbnail($nextID); ?></a>
                                    </div>
                                     <p class="title-desc">
                                          <?php echo get_the_title($nextID); ?>
                                     </p>
                                </div>
                            </nav>
                        <?php } ?>
                    </div>
                </div>
        </section>
    <?php
    wp_reset_postdata();
    ?>
    <script>
        (function($){
            "use strict";
            var slider<?php echo esc_html($g_number); ?> = new MasterSlider();
                slider<?php echo esc_html($g_number); ?>.control('arrows');
                slider<?php echo esc_html($g_number); ?>.setup('masterslider<?php echo esc_html($g_number); ?>' , {
                    width:1170,
                    height:575,
                    space:0,
                    loop:true,
                    view:'wave',
                    layout:'partialview',
            });
        })(jQuery);
    </script>
<?php get_footer(); ?>