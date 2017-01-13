<?php get_header(); ?>
<?php while(have_posts()) : the_post(); ?>
    <section class="director_img_cover">
        <div class="director_detail_name">
            <?php the_title(); ?>
        </div>
        <?php if (get_field('image_cover')){ ?>
                <img src="<?php the_field('image_cover'); ?>" alt="<?php the_title(); ?>">
        <?php } ?>
    </section>
    <section class="director-detail">
        <div class="container">
            <div class="fl-title ">
                <div class="fl-title-small">
                    <?php esc_html_e('MY LIFE STORY','filmmaker'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php if(get_field('profile')){ ?>
                        <div class="detail-director-desc">
                            <?php the_field('profile'); ?>
                        </div>
                     <?php  } ?>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="detail-derector-content">
                        <?php the_content(); ?>
                    </div>
                    <div class="director-social">
                        <?php if(get_field('social')){
                             while(has_sub_field('social')) { ?>
                                <span class="social">
                                    <a href="<?php the_sub_field('social-url'); ?>" title="<?php esc_html_e('social','filmmaker') ?>">
                                        <i class="fa <?php the_sub_field('social-icon'); ?>" ></i>
                                    </a>
                                </span>

                            <?php } ?>
                         <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Timeline -->
    <section id="cd-timeline" class="cd-container">
        <div class="fl-title  fl-timeline">
            <div class="fl-title-small "><?php esc_html_e('TIMELINE','filmmaker'); ?></div>
        </div>
        <div class="container">
                <?php if(get_field('timeline')){ ?>
                    <?php while(has_sub_field('timeline')){ ?>
                        <div class="cd-timeline-block ">
                            <div class="cd-timeline-img "> </div>
                            <div class="cd-timeline-content">
                                <div class="cd-date"><?php the_sub_field('date'); ?></div>
                                <div class="cd-title"><?php the_sub_field('director_film'); ?></div>
                                <div class="cd-desc">
                                    <?php the_sub_field('description'); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
        </div>
    </section>
 <?php $posts = get_field('film_list'); if( $posts ): ?>
 <?php
    $listID = array();
    foreach($posts as $pd){
        $listID[] = $pd->ID;
    }
    $args=array(
    'post_type' => 'film',
    'post__in'       => $listID,
    'post_status'     => 'publish',
    'meta_query' => array(
        array(
            'key' => '_beautheme_feature_post',
            'value' => 'on',
            'compare' => '='
        ),
    ),
    'orderby'   => 'ASC',
    'posts_per_page'  => 1,
);

$loop = new WP_Query($args);
while($loop->have_posts()) : $loop->the_post();
 $category = get_the_terms($post->id, 'film_category');
?>
     <section class="fl-feature">
        <div class="container">
            <div class="row">
                <div class="fl-title feature-title ">
                    <div class="fl-title-small "><?php esc_html_e('FEATURED PROJECTS','filmmaker'); ?></div>
                    <div class="fl-title-big "><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></div>
                </div>
                <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12">
                    <div class="feature-video">
                        <a href="<?php the_permalink(); ?>">
                            <?php if(has_post_thumbnail()){
                                the_post_thumbnail('filmmaker_BE');
                            }else{
                                 echo '<img src="http://placehold.it/960x540" alt="No images">';
                            } ?>
                        </a>
                    </div>
                </div>
                <div class="feature-social">
                    <ul>
                        <li><?php echo esc_html($category[0] ->name ); ?></li>
                        <li>
                            <?php get_template_part('template/social','share' );?>

                        </li>
                        <li>
                            <span><?php filmmaker_setPostViews(get_the_ID()); ?>
                                <?php $view = filmmaker_getPostViews(get_the_ID()); ?><?php echo esc_html($view) ; ?>
                            </span>
                                <?php (int) $view > 1 ? esc_html_e('VIEWS','filmmaker') : esc_html_e('VIEW','filmmaker'); ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

<?php
 endwhile;
 wp_reset_postdata();
 ?>
 <section class="relation-project">
        <div class="container">
            <div class="row">
                <div class="fl-title  ">
                    <div class="fl-title-small "> <?php esc_html_e('PROJECTS','filmmaker') ?></div>
                </div>
            </div>
            <div class="row">
                 <?php foreach( $posts as $post): ?>
                    <?php setup_postdata($post); ?>
                    <?php $category = get_the_terms($post->id, 'film_category'); ?>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="tab-p-container relation-film">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                    <?php if(has_post_thumbnail()){
                                        the_post_thumbnail('filmmaker_BE');
                                        }else{
                                            echo '<img src="http://placehold.it/960x540" alt="No images">';
                                    } ?>
                                </a>
                                <div class="tab-p-content p-tab2">
                                    <div class="tab-p-category">
                                        <?php echo esc_html($category[0] ->name ); ?>
                                    </div>
                                    <div class="tab-p-name">
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title();?></a>
                                    </div>
                                    <div class="tab-p-desc">
                                        <span class="comment"><?php comments_number('0', '1', '%'); ?>
                                            <?php esc_html_e('Comments','filmmaker'); ?>
                                        </span><span class="view">
                                        <?php filmmaker_setPostViews(get_the_ID()); ?>
                                        <?php $view = filmmaker_getPostViews(get_the_ID()); ?><?php echo esc_html($view) ; ?>
                                            <?php (int) $view > 1 ? esc_html_e('VIEWS','filmmaker') : esc_html_e('VIEW','filmmaker'); ?>
                                         </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php wp_reset_postdata(); ?>

            </div>
        </div>
    </section>
<?php endif; ?>
<?php endwhile; ?>

<section class="relate-film-detail relate-director">
    <?php
        $args = array(
       'post_type' => 'director',
        'showposts' => 2,
        'orderby' => 'ASC',
        'taxonomy' => 'director_category',
        'post__not_in' => array ($post->ID),
        );
        $loop = new WP_Query($args);
     ?>
    <ul>
        <?php while($loop->have_posts()) : $loop->the_post();
            $category = get_the_terms($loop->id, 'director_category');
        ?>
        <li>
            <div class="more_film">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <?php if(has_post_thumbnail()){
                         the_post_thumbnail();
                        }else{
                          echo '<img src="http://placehold.it/960x540" alt="'.esc_html__('No images','filmmaker').'">';
                    } ?>
                </a>
                <div class="center centertxt">
                    <div class="title-white">
                        <?php echo $category[0]->name;  ?>
                    </div>
                    <div class="title-bold-white">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </div>
                    <div class="white-desc">
                        <?php echo wp_trim_words(get_the_content(),30); ?>
                    </div>
                </div>
            </div>
        </li>
        <?php endwhile;
         wp_reset_postdata();
         ?>
    </ul>
</section>
<?php get_footer(); ?>