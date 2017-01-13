<?php get_header(); ?>
<?php
    $i = $j = 1;
?>
<section class="film-type2">
    <div class="list-film-title fl-title">
        <?php if(filmmaker_GetOption('fl-text-small') != "") {
            echo '<div class="fl-title-small">';
            echo filmmaker_GetOption('fl-text-small');
            echo '</div>';
        } ?>
        <?php if(filmmaker_GetOption('fl-text-big') != "") {
            echo '<div class="fl-title-big">';
            echo filmmaker_GetOption('fl-text-big');
            echo '</div>';
        } ?>
            <?php if(filmmaker_GetOption('fl-description') != "") {
            echo '<div class="fl-title-desc">';
            echo filmmaker_GetOption('fl-description');
            echo '</div>';
        } ?>


        </div>
    <ul class="film-box">
    <?php
        while (have_posts() ) : the_post();
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
                    $float = 'right';
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
         <li class="film-type2 <?php echo esc_attr($size).' '.esc_attr($float);?> ">
            <div class="film-type2-item">
                <div class="film2-img">
                    <a href="<?php the_permalink(); ?>">
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
                        <?php echo wp_trim_words(get_the_content(), 50, '...');  ?>
                    </div>
                    <div class="list-viewmore">
                        <span class="view-more"><a href="<?php the_permalink(); ?>" title="<?php esc_html_e('view more','filmmaker'); ?>">
                            <?php esc_html_e('view more','filmmaker'); ?>
                            <i class="fa fa-long-arrow-right"></i></a></span>
                    </div>
                </div>
            </div>
        </li>
    <?php
    $i++; $j++;
    endwhile; ?>
    </ul>
    
</section>
 <?php $enable_pagination = filmmaker_GetOption('tax-film-enable-pagination') == NULL ? 0 : filmmaker_GetOption('tax-film-enable-pagination'); ?>
    <?php if($enable_pagination) : ?>
    <div class="pagination-film">
        <?php filmmaker_pagination();?>
    </div>>
    <?php endif; ?>
<?php get_footer(); ?>
