<?php
    /*
    * Template Name: Template history
    */
    get_header();
    while ( have_posts() ) : the_post();
?>
 <section class="timeline-item">
    <div class="container">
        <div class="row">
            <div class="timline-history">
                <div class="title-gray centertxt title-1 wow fadeInDown" data-wow-delay="0.3s">
                <?php esc_html_e('history','filmmaker') ?></div>
                <ul class="timeline-box timeline-page-box">
                    <?php if(get_field('event')) { ?>
                        <?php while(has_sub_field('event')){ ?>
                        <li>
                            <div class="d-even"> </div>
                            <div class="d-img  wow fadeInUp" data-wow-delay="0.3s">
                                    <img src="<?php the_sub_field('event_image');?>" alt="<?php the_sub_field('event_title'); ?>">
                            </div>
                            <div class="d-content ">
                                <div class="title-gray wow fadeInUp" data-wow-delay="0.3s">
                                    <?php the_sub_field('event_year'); ?>
                                </div>
                                <div class="title-desc title-time wow fadeInUp" data-wow-delay="0.4s">
                                    <?php the_sub_field('event_title'); ?>
                                </div>
                                <div class="gray-desc wow fadeInUp" data-wow-delay="0.5s">
                                    <?php the_sub_field('event_description'); ?>
                                </div>
                            </div>
                        </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
            <div class="endhistory">
                <?php if(get_field('end_history')) { ?>
                     <?php while(has_sub_field('end_history')) { ?>
                        <div class="d-even"></div>
                        <div class="cunhat">
                            <div class="title-gray wow fadeInUp" data-wow-delay="0.3s">
                                <?php the_sub_field('year');  ?>
                            </div>
                            <div class="title-desc title-time wow fadeInUp" data-wow-delay="0.4s">
                                <?php the_sub_field('title'); ?>
                            </div>
                            <div class="gray-desc wow fadeInUp" data-wow-delay="0.5s">
                                <?php the_sub_field('description'); ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php
 the_content();
endwhile;
get_footer();
?>