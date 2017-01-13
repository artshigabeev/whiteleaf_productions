<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 ">
    <div class="tabs">
        <?php
            $i=0;
            $args = array(
            'post_type' => $type,
            'showposts' => 2,
            'order'     => 'ASC',
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
                 while($loop->have_posts()) : $loop->the_post(); $i++;
        ?>
         <?php if($i==1){ ?>
            <div class="active col-md-8 col-sm-8 col-xs-12 nopadding-right tab ">
                <div class="tab-container">
                    <?php if(has_post_thumbnail()){
                        the_post_thumbnail('DR');
                    } ?>
                    <div class="tab-content">
                        <div class="direct-container">
                            <div class="crew-name" <?php echo $cl_tab_name; ?>>
                                <?php the_title(); ?>
                            </div>
                            <div class="crew-active" <?php echo $cl_tab_job ?>>
                                <?php if(get_field('crew_job')){
                                    the_field('crew_job');
                                } ?>
                            </div>
                            <div class="crew-desc" <?php echo $cl_tab_desc; ?>>
                                <?php echo esc_html(wp_trim_words( get_the_content(), 50, '...' )); ?>
                            </div>
                            <?php if( get_field('crew_social') ){ ?>
                                <div class="director-social director-social-tab">
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
            </div>
         <?php }else{?>
            <div class="col-md-8 col-sm-8 col-xs-12 nopadding-left tab" >
                 <div class="tab-container">
                    <?php if(has_post_thumbnail()){
                        the_post_thumbnail('DR');
                    } else {?>
                         <img src="http://placehold.it/363x500" alt="No images">
                    <?php } ?>
                        <div class="tab-content">
                            <div class="direct-container">
                                <div class="crew-name" <?php echo $cl_tab_name; ?>>
                                    <?php the_title(); ?>
                                </div>
                                <div class="crew-active"  <?php echo $cl_tab_job ?>>
                                    <?php if(get_field('crew_job')){
                                        the_field('crew_job');
                                    } ?>
                                </div>
                                <div class="crew-desc" <?php echo $cl_tab_desc; ?>>
                                    <?php
                                        the_content();
                                        ?>
                                </div>
                                <?php if( get_field('crew_social') ){ ?>
                                    <div class="director-social director-social-tab">
                                        <?php while( has_sub_field('crew_social') ) { ?>
                                        <span class="social">
                                            <a href="<?php the_sub_field('crew_social_link') ;?>" title="">
                                                <i class="fa <?php the_sub_field('screw_social_icon'); ?> "></i>
                                            </a>
                                        </span>
                                        <?php } ?>
                                        </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
    <?php endwhile; ?>
    <?php } ?>
    </div>
</div>
<script type="text/javascript">
    (function($){
        "use strict";
        if ($(window).width() > 767) {
            $(".tab_content:first").show();
            $(".tabs .tab").click(function() {
                $(".tabs .tab").removeClass("active");
                $(this).addClass("active");
                $(".tab_content").hide();
            });
        }
    })(jQuery)
</script>