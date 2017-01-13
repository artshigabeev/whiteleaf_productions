<?php
    /*
    * Template Name: Director
    */
    get_header();
?>
 <?php
        $args = array(
            'post_type' => 'director',
            'showposts' => 4,
            'orderby' => 'ASC',
            'taxonomy' => 'director_category',
        );
        $loop = new WP_Query($args);
        wp_reset_postdata();

     ?>
    <section class="director-item">
        <ul class="director-container">
            <?php if($loop->have_posts()){
                while($loop->have_posts()) : $loop->the_post();
                 $category = get_the_terms($post->id, 'director_category');
            ?>
                <li>
                    <div class="director-box director-img wow fadeInUp" data-wow-delay="0.3s">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                            <?php if(has_post_thumbnail()){
                                the_post_thumbnail();
                                } ?>
                        </a>
                    </div>
                    <div class="director-box director-content">
                        <div class="director-list">
                            <div class="director-name wow fadeInUp" data-wow-delay="0.3s">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                            </div>
                            <div class="director-category wow fadeInUp" data-wow-delay="0.4s">
                                <?php echo esc_html($category[0]->name); ?>
                            </div>
                            <div class="director-desc wow fadeInUp" data-wow-delay="0.5s">
                                <?php echo wp_trim_words(get_the_content(),80,''); ?>
                            </div>
                            <div class="view wow fadeInUp" data-wow-delay="0.6s">
                                <span class="more"><a href="<?php the_permalink(); ?>" title="<?php esc_html_e('MY LIFE STORY','filmmaker'); ?>"><?php esc_html_e('MY LIFE STORY','filmmaker'); ?><i class="fa fa-long-arrow-right"></i></a></span>
                            </div>
                        </div>
                    </div>
                </li>

            <?php endwhile; ?>
            <?php } ?>
        </ul>
    </section>
    <div class="clearfix"></div>
<?php get_footer(); ?>