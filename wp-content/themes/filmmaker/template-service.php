<?php
    /*
    * Template Name: Template Service
    */
    get_header();
    while ( have_posts() ) : the_post();
?>
 <section class="service-page">
        <div class="container-fluid nopadding">
            <div class="row nopadding">
                <?php if(get_field('service')){ ?>
                     <?php while(has_sub_field('service')){ ?>
                        <div class="col-md-6 col-sm-6 col-xs-12 nopadding">
                            <div class="service-container">
                                <img src="<?php the_sub_field('image'); ?>" alt="<?php the_sub_field('title'); ?>">
                                <div class="service-content">
                                    <div class="service-group">
                                        <div class="service-icon"><span class="<?php the_sub_field('icon'); ?>"></span></div>
                                        <div class="service-name"><?php the_sub_field('title'); ?></div>
                                    </div>
                                    <div class="service-desc">
                                        <?php the_sub_field('description'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </section>
<?php
 the_content();
endwhile;
get_footer();
?>