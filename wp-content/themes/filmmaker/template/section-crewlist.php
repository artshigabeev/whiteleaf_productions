    <?php
        $args= array(
            'post_type' => $type,
            'showposts' => $crew_number,
            'order'     =>'ASC',
            'tax_query' => array(
                array(
                    'taxonomy'  => 'crew_category',
                    'terms'     => $crew_id,
                ),
            'orderby' => 'date',
            ),
        );
        $loop = new WP_Query($args);
        wp_reset_postdata();
    ?>
    <?php
        if($loop->have_posts()){
            while($loop->have_posts()) : $loop->the_post();
    ?>
             <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="crew-container wow slideInUp" data-wow-delay="0.3s">
                    <?php
                        if(has_post_thumbnail()){
                            the_post_thumbnail('filmmaker_DC');
                         }
                     ?>
                    <div class="fl-olay"></div>
                    <div class="crew-content">
                        <div class="crew-name" <?php  echo $cl_list_name; ?>>
                            <?php the_title(); ?>
                        </div>
                        <div class="crew-active" <?php  echo $cl_list_job; ?>>
                            <?php if(get_field('crew_job')){
                                  the_field('crew_job');
                            } ?>
                        </div>
                        <div class="crew-desc" <?php  echo $cl_list_desc; ?>>
                            <?php echo esc_html(wp_trim_words( get_the_content(), 50, '...' )); ?>
                        </div>
                        <?php if( get_field('crew_social') ){ ?>
                            <div class="crew-social">
                                <?php while( has_sub_field('crew_social') ) { ?>
                                    <span class="social">
                                        <a href="<?php the_sub_field('crew_social_link') ;?>" title="<?php esc_html_e('social','filmmaker'); ?>" >
                                            <i class="fa <?php the_sub_field('screw_social_icon'); ?> "></i></a>
                                    </span>
                                 <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php
             endwhile; }
         ?>
        <?php $img_Crew = wp_get_attachment_image_src($crewlist_image, 'filmmaker_DC'); ?>
        <?php if($crewlist_checkbox == 'yes'){ ?>
             <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="crew-container wow fadeInUp" data-wow-delay="0.3s">
                    <?php
                        if($crewlist_image != ""){ ?>
                            <img src="<?php echo esc_url($img_Crew[0]); ?>" alt="<?php esc_html_e('and you','filmmaker'); ?>">
                         <?php }else{
                            echo '<img src="http://placehold.it/363x500" alt="No images">';
                            }
                     ?>
                    <div class="crew-content2">
                        <?php if($crewlist_textfield != ""){ ?>
                            <div class="crew-name2">
                                <?php echo esc_html($crewlist_textfield); ?>
                            </div>
                        <?php } ?>
                        <div class="join-crew">
                            <span class="more">
                                <a href="<?php echo esc_url($crewlist_link); ?>" title="<?php esc_html_e('join with us','filmmaker') ?>">
                                    <?php echo esc_html($crewlist_textlink); ?>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>