<?php
    /*
    * Template Name: Discover
    */
    get_header();
    while ( have_posts() ) : the_post();
?>

    <section class="discover-page">
        <div class="container">
            <div class="row">
                <div class="fl-title overview ">
                    <div class="fl-title-small "><?php esc_html_e('OVER VIEW','filmmaker'); ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="discover-page-content">
                        <div class="discover-page-group">
                            <?php if(get_field('title_origin')){
                                the_field('title_origin');
                            }else{
                                echo esc_html_e('OUR ORIGINS','filmmaker');
                            } ?>
                        </div>
                        <div class="discover-page-name">
                            <?php if(get_field('name_orgin')){
                                the_field('name_orgin');
                            }else{
                                echo esc_html_e('Hollywood','filmmaker');
                            } ?>
                        </div>
                        <div class="discover-page-desc">
                            <?php if(get_field('content_origin')){
                                the_field('content_origin');
                            }else{
                                echo esc_html_e('Welcom to Beau Cretive','filmmaker');
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div id="timeline">
                            <ul id="dates">
                                <?php if(get_field('timeline')){
                                    while(has_sub_field('timeline')){ ?>
                                        <?php  $i=0; if($i=1){ ?>
                                            <li><a href="#<?php the_sub_field('date'); ?>" class="selected"><?php the_sub_field('date'); ?></a></li>
                                        <?php } else{
                                              echo '<li><a href="#'.the_sub_field('date').' " title="'.the_sub_field('date').'">'.the_sub_field('date').'</a></li>';
                                            } ?>
                                    <?php $i++; } ?>
                                <?php }else{
                                         esc_html_e('Your Timeline empty','filmmaker');
                                    } ?>
                            </ul>
                            <ul id="issues">
                                <?php if(get_field('timeline')){
                                    while(has_sub_field('timeline')){ ?>
                                        <?php  $i=0; if($i=1){ ?>
                                            <li id="<?php the_sub_field('date') ?>" class="selected">
                                                <div class="timeline-box">
                                                    <?php if(get_sub_field('image')){ ?>
                                                         <img src="<?php the_sub_field('image'); ?>" alt="<?php the_sub_field('date') ?>">
                                                    <?php } ?>
                                                    <div class="date-group">
                                                        <?php the_sub_field('name'); ?>
                                                    </div>
                                                    <div class="sl-time-desc">
                                                        <?php the_sub_field('content'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php } else{ ?>
                                            <li id="<?php the_sub_field('date') ?>">
                                                <div class="timeline-box">
                                                    <?php if(get_sub_field('image')){ ?>
                                                         <img src="<?php the_sub_field('image'); ?>" alt="<?php the_sub_field('date') ?>">
                                                    <?php } ?>
                                                    <div class="date-group">
                                                        <?php the_sub_field('name'); ?>
                                                    </div>
                                                    <div class="sl-time-desc">
                                                        <?php the_sub_field('content'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    <?php $i++; } ?>
                                <?php }else{
                                        echo esc_html_e('Your Timeline empty','filmmaker');
                                    } ?>

                            </ul>

                            <a href="#" id="disnext" title="<?php esc_html_e('next','filmmaker'); ?>"><i class="fa fa-angle-up"></i></a>
                            <a href="#" id="disprev" title="<?php esc_html_e('previous','filmmaker'); ?>"><i class="fa fa-angle-down"></i></a>
                        </div>

                </div>
            </div>
        </div>
    </section>
    <script>
         (function($){
            "use strict";
            $().timelinr({
                orientation:    'vertical',
                issuesSpeed:    300,
                datesSpeed:     100,
                arrowKeys:      'true',
                startAt:        4
            })
        })(jQuery);
    </script>

<?php
 the_content();
endwhile;
get_footer();
?>