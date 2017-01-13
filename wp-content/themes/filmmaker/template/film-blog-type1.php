<div class="<?php echo esc_html($film_column_in_row); ?> blog-box blog-item">
    <div class="blog-content">
        <div class="blog-img wow fadeInUp" data-wow-delay="0.3s">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php if(has_post_thumbnail()){
                    the_post_thumbnail();
                 } ?>
             </a>
        </div>
        <div class="blog-time wow fadeInUp" data-wow-delay="0.4s">
            <span><?php  the_time('j M, Y') ?></span>
            <span><i class="fa fa-comment-o"></i>
                <?php comments_number('0', '1', '%'); ?>
            </span>
        </div>
        <div class="blog-name wow fadeInUp" data-wow-delay="0.5s">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_title(); ?>
            </a>
        </div>
        <div class="blog-desc wow fadeInUp" data-wow-delay="0.6s">
            <?php echo wp_trim_words( get_the_content(), 20, '...' ); ?>
        </div>
    </div>
 </div>