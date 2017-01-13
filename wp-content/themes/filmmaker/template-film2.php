<?php
    /*
    * Template Name: Template List Film2
    */
    get_header();
?>
<?php
    $args = array(
        'post_type' => 'film',
        'showposts' => 8,
        'orderby' => 'date',
        'paged'    => $paged,
    );
    $loop = new WP_Query($args);
    wp_reset_postdata();
 ?>
<?php
    $i = $j = 1;
?>
<section class="film-type2">
    <div class="list-film-title fl-title">
        <div class="fl-title-small"><?php the_field('small_title'); ?></div>
        <div class="fl-title-big"><?php the_field('main_title'); ?></div>
        <div class="fl-title-desc">
            <span><?php the_field('short_description'); ?></span>
        </div>
        <div class="fl-title-img">
            <img src="<?php  echo esc_url( get_template_directory_uri() ); ?>/asset/images/lau.png" alt="">
        </div>
        <div class="arrow-down">
            <img src="<?php  echo esc_url( get_template_directory_uri() ); ?>/asset/images/arrowdown.png" alt="">
        </div>
    </div>
    <ul class="film-box">
    <?php
        while ($loop->have_posts() ) : $loop->the_post();
            $category = get_the_terms($post->id, 'film_category');
            $size         = 'f-small';
            $float        = 'f-left';
            if ($i==6) {
                $i=1;
            }
            if ($j%2==0) {
               $float = 'f-right';
            }
            switch ($i) {
                case 1:
                    $size  = 'f-small';
                    $float = 'f-left';
                    break;
                case 2:
                    $size = 'f-big';
                    $float = 'f-right';
                    break;

                case 3:
                    $size = 'f-big';
                    $float = 'f-left';
                    break;
                case 4:
                    $size = 'f-small';
                    $float = 'f-right';
                    break;
                case 5:
                    $size = 'f-small';
                    $float = 'f-right';
                    break;
            }
        ?>
         <li class="film-type2-box <?php echo esc_attr($size).' '.esc_attr($float);?> ">
            <div class="film-type2-item">
                <div class="film2-img">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <?php if(has_post_thumbnail()){
                        the_post_thumbnail('filmmaker_BE');
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
                        <?php echo wp_trim_words(get_the_content(), 20, '...');  ?>
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
        </li>
    <?php
    $i++; $j++;
    endwhile;
    wp_reset_postdata();
    ?>
    </ul>
</section>
<div class="clearfix"></div>
<div class="fl-pagination"><?php echo filmmaker_pagination($loop); ?></div>

<?php while(have_posts()) : the_post();
    the_content();
    endwhile;
?>
<?php
    get_footer();
?>