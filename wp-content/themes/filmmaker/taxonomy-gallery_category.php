<?php get_header(); ?>
<?php
        $args = array(
            'post_type' => 'gallery',
            'showposts' => '9',
            'orderby' => 'date',
        );
        $loop = new WP_Query($args);
        wp_reset_postdata();
     ?>
<?php
    $i = $j = $k = 0;
?>
    <section class="gallery-page">
        <div class="container-fluid nopadding">
            <ul class="gallery-film" >
                <?php
                while ($loop->have_posts() ) : $loop->the_post();
                    $size         = 'gallery-big';
                    $float        = 'g-left';
                    $k = $k+1;
                    if ($i%3==0) {$j+=1; $k=1;}
                    if ($j%2==0) {
                      switch ($k) {
                        case 1:
                          $size         = 'gallery-small';
                          $float        = 'g-left';
                          break;
                        case 3:
                          $size         = 'gallery-small';
                          $float        = 'g-left';
                          break;
                        default:
                          $size         = 'gallery-big';
                          $float        = 'g-right';
                          break;
                      }
                    }else{
                      switch ($k) {
                        case 2:
                          $size         = 'gallery-small';
                          $float        = 'g-right';
                          break;
                        case 3:
                          $size         = 'gallery-small';
                          $float        = 'g-right';
                          break;
                        default:
                          $size         = 'gallery-big';
                          $float        = 'g-left';
                          break;
                      }
                    }
                 ?>
                    <li class="gallery-container <?php echo esc_attr($size).' '.esc_attr($float);?>">
                        <div class="gallery-image">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                               <?php if(has_post_thumbnail()){
                                    the_post_thumbnail('filmmaker_BE');
                                    }else{
                                      echo '<img alt="'.get_the_title().'" src="http://placehold.it/940x540" alt="'.esc_html__('No Image','filmmaker').'">';
                                    } ?>
                            </a>
                        </div>
                        <div class="gallery-content">
                            <div class="title-white"><?php the_title(); ?></div>
                            <div class="title-bold-white">
                                <a href="<?php the_permalink(); ?>"><?php $category = get_the_terms($post->id, 'gallery_category');
                                   echo esc_html($category[0]->name);
                                 ?></a>
                            </div>
                            <div class="white-desc">
                                <?php echo wp_trim_words(get_the_content(),20, '...') ?>
                            </div>
                             <div class="g-viewmore">
                                <span class="view-more"><a href="<?php the_permalink(); ?>" title="<?php esc_html_e('view more','filmmaker'); ?>"><?php esc_html_e('VIEW MORE ','filmmaker'); ?><i class="fa fa-long-arrow-right"></i></a></span>
                            </div>
                        </div>
                    </li>
                <?php $i++;
                endwhile; ?>


            </ul>

        </div>
    </section>
<?php
the_content();
get_footer();
?>