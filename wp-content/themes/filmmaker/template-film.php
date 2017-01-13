<?php
    /*
    * Template Name: Template list film
    */
    get_header();
?>
    <?php
        $args = array(
            'post_type' => 'film',
            'showposts' => '7',
            'orderby' => 'date',
            'paged'    => $paged,
        );
        $loop = new WP_Query($args);
        wp_reset_postdata();
     ?>

    <section class="page-film-item">
        <?php if($loop -> have_posts()){ ?>
            <?php
                 while($loop -> have_posts()) : $loop -> the_post();
                $category = get_the_terms($post->id, 'film_category');
               // var_dump($category);
        ?>
        <div class="list-film">
            <div class="fl_olay"></div>
            <div class="img-film-item">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <?php if(has_post_thumbnail()){
                        the_post_thumbnail();
                    } ?>
                </a>
            </div>
            <div class="list-film-content">
                    <div class="list-film-cat">
                        <a href="<?php echo get_category_link( $category[0]->term_id ); ?>" title="<?php echo esc_html($category[0]->name); ?>"><?php echo esc_html($category[0]->name); ?>
                    </a>
                    </div>
                    <div class="list-film-name">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </div>
                    <div class="list-film-desc">
                            <?php echo esc_html(wp_trim_words( get_the_content(), 50, '...' )); ?>
                     </div>
                    <div class="list-viewmore">
                        <span class="view-more">
                            <a href="<?php the_permalink(); ?>" title="<?php esc_html_e('view more','filmmaker'); ?>">
                                <?php esc_html_e('view more','filmmaker'); ?>
                                <i class="fa fa-long-arrow-right"></i>
                            </a>
                        </span>
                    </div>
            </div>
        </div>
        <?php endwhile; ?>
        <?php } ?>
    </section>
    <div class="clearfix"></div>
    <div class="fl-pagination"><?php echo filmmaker_pagination($loop); ?></div>
<?php
    get_footer();
 ?>
