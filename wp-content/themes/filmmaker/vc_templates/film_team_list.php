<?php
    $team_num = $team_text = $color_name = $color_job = $color_social = $bg_social = $hover_color_social = $hover_bg_social ='';
    extract(shortcode_atts(array(
        'team_num' => '',
        'team_text' =>'',
        'color_name'    => '',
        'color_job'     => '',
        'color_social'  => '',
        'bg_social'     => '',
        'hover_color_social'    => '',
        'hover_bg_social'       => '',
        ), $atts ));
    $args = array (
        'post_type' => 'crew',
        'orderby'   => 'date',
        'showposts' => $team_num,
        );
    $loop= new WP_Query($args);
    wp_reset_postdata();
    $random = rand(111,9999);
    $cl_name = 'style="color:'.$color_name.'"';
    $cl_job = 'style="color:'.$color_job.'"';
 ?>
 <style>
    .team-content .team-social span{
        background: <?php echo $bg_social; ?>
    }
    .team-content .team-social span a{
        color: <?php echo $color_social; ?>;
    }
    .team-content .team-social span:hover{
        background: <?php echo $hover_bg_social; ?>
    }
    .team-content .team-social span:hover a{
        color: <?php echo $hover_color_social; ?>;
    }
 </style>
    <div class="team-film-item">
        <div class="ms-staff-carousel">
            <div class="master-slider" id="masterslider<?php echo esc_html($random); ?>">
                <?php while($loop->have_posts()) : $loop->the_post(); ?>
                <div class="ms-slide">
                    <div class="team-film-container">
                        <a href="<?php the_permalink();?>" alt="<?php the_title();?>"><?php the_post_thumbnail(); ?></a>
                        <div class="team-content">
                            <div class="team-name" ><a <?php echo $cl_name; ?> href="<?php the_permalink();?>" alt="<?php the_title();?>"><?php the_title(); ?></a></div>
                            <?php if(get_field('crew_job')){ ?>
                                <div class="team-job" <?php echo $cl_job; ?>>
                                    <?php the_field('crew_job'); ?>
                                </div>
                            <?php } ?>
                            <?php if(get_field('crew_social')){ ?>
                                <div class="team-social">
                                    <?php while(has_sub_field('crew_social')){ ?>
                                    <span><a href="<?php the_sub_field('crew_social_link'); ?>" >
                                        <i class="fa <?php the_sub_field('screw_social_icon'); ?>"></i>
                                    </a></span>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <!-- end of masterslider -->
            <div class="ms-staff-info" id="staff-info"> </div>
        </div>
    </div>
    <script type="text/javascript">
        (function($){
            "use trict";
            var slider<?php echo esc_html($random); ?> = new MasterSlider();
            slider<?php echo esc_html($random); ?>.setup('masterslider<?php echo esc_html($random); ?>' , {
                loop:true,
                width:500,
                height:500,
                speed:10,
                view:'focus',
                preload:'all',
                space:60,
                autoplay:true,
                autoHeight:true,
            });
        })(jQuery);
    </script>