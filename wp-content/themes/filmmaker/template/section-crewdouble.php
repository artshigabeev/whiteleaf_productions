<div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
    <?php
        $args = array(
            'post_type' => $type,
            'showposts' => 2,
            'order'=>'ASC',
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
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="crew-container">
                    <?php if(has_post_thumbnail()){
                        the_post_thumbnail('filmmaker_DC');
                    } ?>
                    <div class="fl-olay"></div>
                    <div class="crew-content wow fadeInUp" data-wow-delay="0.3s">
                        <div class="crew-name"><?php the_title(); ?></div>
                        <div class="crew-active">
                            <?php if(get_field('crew_job')){
                                the_field('crew_job');
                            } ?>
                        </div>
                        <div class="crew-desc">
                             <?php echo esc_html(wp_trim_words( get_the_content(), 50, '...' )); ?>
                        </div>
                        <?php if( get_field('crew_social') ){ ?>
                            <div class="crew-social">
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
             </div>
        <?php endwhile; ?>
    <?php } ?>
</div>