<?php if($loop -> have_posts()){ ?>
<?php
     while($loop -> have_posts()) : $loop -> the_post();
    $category = get_the_terms($loop->id, 'film_category');
    $background= 'style="background: url('.wp_get_attachment_url(get_post_thumbnail_id($loop->ID)).') no-repeat center "' ;
?>
<div class="list-film" <?php echo $background; ?> >
    <div class="fl_olay"></div>
    <div class="list-film-content">
            <div class="list-film-cat">
                <a href="<?php echo get_category_link( $category[0]->term_id ); ?>" title="<?php echo esc_html($category[0]->name); ?>"><?php echo esc_html($category[0]->name); ?></a>
            </div>
            <div class="list-film-name">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <?php the_title(); ?>
                </a>
            </div>
            <div class="list-film-desc">
                <?php echo esc_html(wp_trim_words( get_the_content(), 50, '...' )); ?>
            </div>
            <div class="list-viewmore">
                <span class="view-more">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                        <?php esc_html_e('view more','filmmaker'); ?>
                        <i class="fa fa-long-arrow-right"></i>
                    </a>
                </span>
            </div>
    </div>
</div>
<?php endwhile; ?>
<?php } ?>