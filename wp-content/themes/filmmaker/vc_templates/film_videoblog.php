<?php 
  $film_videoblog_id = '';
  extract(shortcode_atts(array(
    'film_videoblog_id' => '',
  ), $atts));
?>
<?php
  $vd_blog = explode(',',$film_videoblog_id);
  
  if ($vd_blog ='') {
    $list_videoblog = array(
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
  }else{
    $list_videoblog = array(
      'posts_type' => 'post',
      'category__in' => $vd_blog,
      'order' => 'DESC',
      'tax_query' => array(
          array(
            'taxonomy' => 'post_format',
            'field' => 'slug',
            'terms' => 'post-format-video',
          )
        )
    ); 
  }       
  $loop_videoblog = new WP_Query($list_videoblog);
  wp_reset_postdata();
?>
<?php $g_number = rand(11,99999); ?>
    <section class="gallery-slider wow fadeInUp" data-wow-delay="0.3s">
        <div class="g-detail-slider template-video-fl">
            <div class="master-slider  ms-skin-default " id="masterslider<?php echo esc_html($g_number); ?>">
                <?php if($loop_videoblog->have_posts()){
                    while($loop_videoblog->have_posts()): $loop_videoblog->the_post(); ?>
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