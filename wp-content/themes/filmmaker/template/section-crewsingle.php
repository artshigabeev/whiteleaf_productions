<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
<?php
       $args = array(
            'post_type' => $type,
            'showposts' => 1,
            'order'     =>'ASC',
            'tax_query' => array(
                array(
                    'taxonomy'  => 'crew_category',
                    'terms'     => $crew_id,
                ),
            ),
         );

         $loop = new WP_Query($args);
        wp_reset_postdata();
     ?>
     <?php
        if($loop->have_posts()) {
            while($loop->have_posts()) : $loop->the_post();
      ?>
        <div class="col-md-8 col-sm-7 col-xs-12">
            <div class="crew-img wow fadeInLeft" data-wow-delay="0.3s">
                <?php if(has_post_thumbnail()){
                        the_post_thumbnail('DR');
                    } ?>
            </div>
        </div>
        <div class="col-md-4 col-sm-5 col-xs-12">
            <div class="direct-container d-padding">
                <div class="crew-name wow fadeInRight" data-wow-delay="0.3s" <?php echo $cl_one_name; ?>><?php the_title(); ?></div>
                <div class="crew-active wow fadeInRight" data-wow-delay="0.4s" <?php echo $cl_one_job; ?>>
                       <?php if(get_field('crew_job')){
                            the_field('crew_job');
                        } ?>
                </div>
                <div class="crew-desc wow fadeInRight" data-wow-delay="0.5s" <?php echo $cl_one_desc; ?>>
                    <?php echo esc_html(wp_trim_words( get_the_content(), 50, '...' )); ?>
                </div>
                <?php if( get_field('crew_social') ){ ?>
                    <div class="director-social wow fadeInRight" data-wow-delay="0.6s">
                        <?php while( has_sub_field('crew_social') ) { ?>
                            <span class="social">
                                <a href="<?php the_sub_field('crew_social_link') ;?>" title="<?php esc_html_e('social','filmmaker'); ?>">
                                    <i class="fa <?php the_sub_field('screw_social_icon'); ?> "></i></a>
                            </span>
                        <?php } ?>
                     </div>
                <?php } ?>
            </div>
        </div>
    <?php
        endwhile; }
     ?>
</div>