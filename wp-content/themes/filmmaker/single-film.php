<?php get_header(); ?>

<?php while(have_posts()) : the_post(); ?>
<?php
    $category = get_the_terms($post->id, 'film_category');
    $bg = 'url('.wp_get_attachment_url(get_post_thumbnail_id($post->ID)).')';
?>
<style>
    .fl-detail-item{
        background-image:linear-gradient( rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)),<?php echo esc_attr($bg); ?>;
        background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        -webkit-background-size: cover
    }
</style>
<section class="fl-detail-item" >
    <div class="fl-detail-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="fl-title">
                        <div class="fl-title-small st-title">
                            <?php echo esc_html($category[0]->name); ?>
                        </div>
                        <div class="fl-title-medium ftm">
                            <?php the_title();?>
                        </div>
                    </div>
                     <div class="d-share d-bottom2">
                         <?php get_template_part('template/social','share');?>
                    </div>
                    <div class="fl-detail-video ">
                        <?php $i=0; if(get_field('video')) { ?>
                            <?php while(has_sub_field('video')){ ?>
                                   
                                    <div class="tab-pane <?php  echo ($i>0?"":"active")?>" id="afl<?php echo esc_html($i); ?>" data-video="<?php echo get_sub_field('video_url')?>">
                                       <?php if(get_sub_field('video_url')){
                                            $show_view = $wp_embed->run_shortcode('[embed]'.get_sub_field('video_url').'[/embed]');
                                            printf('%s', $show_view);

                                            echo '<div class="img-fl-detail" id="flplay'.esc_html($i).'">';
                                                if(get_sub_field('image-film')){
                                                    echo '<img  src=" '.get_sub_field('image-film').'" alt="'.get_the_title().'" >';
                                                }
                                            echo '</div>';

                                            echo '<div class="click_play"  data-play="afl'.esc_html($i).'" id="click-img-play'.esc_html($i).'">
                                                <img alt="" src="'.get_template_directory_uri().'/asset/images/play.png"></div>';

                                        }elseif(get_sub_field('image-film')){ ?>
                                            <img  src="<?php the_sub_field('image-film'); ?>" alt="<?php the_title(); ?>" >
                                        <?php } ?>
                                        
                                    </div>
                            <?php  $i++;} ?>
                        <?php } ?>
                    </div>
                    <script type="text/javascript">
                        (function($){
                            "use strict";
                            reseturlDataVideo();
                            $('.click_play').click(function(){
                                var idContainer = $(this).attr('data-play');
                                var idconTainerPlay = '#'+idContainer;
                                var srcVideo = $(idconTainerPlay).find('iframe').attr('src');
                                $(idconTainerPlay).find('iframe').show().addClass(idContainer);
                                $(idconTainerPlay+' .img-fl-detail').slideUp('3000');
                                $(this).hide('fast');
                                $('.'+idContainer).show().attr('src', srcVideo+'?&autoplay=1');
                            });
                            $('body').on('click','#tablist-iframe li a',function(){
                                resetIframe();
                            });
                            function resetIframe(){
                                $('.tab-pane').each(function(index, el) {
                                    var classFrame = $(this).attr('id');
                                    var urlVideoReload = $(this).attr('data-video');
                                    console.log(urlVideoReload);
                                    $(this).find('iframe').attr('src',urlVideoReload);
                                    $('.click_play, .img-fl-detail').show();

                                });
                            }
                            function reseturlDataVideo(){
                                $('.tab-pane').each(function(index, el) {
                                    var urlIframe  = $(this).find('iframe').attr('src');
                                    if (urlIframe != undefined) {
                                        $(this).attr('data-video', urlIframe);
                                    }
                                });
                            }
                        })(jQuery);
                    </script>
                    <div class="film-number">
                        <ul id="tablist-iframe" >
                            <?php $i=0; if(get_field('video')) { ?>
                                <?php while(has_sub_field('video')){ ?>
                                    <?php if($i==0){ ?>
                                        <li class="active ">
                                            <a href="#afl<?php echo esc_html($i); ?>" role="tab" data-toggle="tab" title="<?php esc_html_e('Name','filmmaker'); ?>">
                                                <?php if(get_sub_field('name')){
                                                        the_sub_field('name');
                                                    }else{
                                                        esc_html_e('Name', 'filmmaker');
                                                } ?>
                                            </a>
                                        </li>
                                    <?php }else{ ?>
                                        <li class="">
                                            <a href="#afl<?php echo esc_html($i); ?>" role="tab" data-toggle="tab" title="<?php esc_html_e('Name','filmmaker'); ?>">
                                                <?php if(get_sub_field('name')){
                                                        the_sub_field('name');
                                                    }else{
                                                        esc_html_e('Name', 'filmmaker');
                                                } ?>
                                            </a>
                                        </li>
                                    <?php  } ?>
                                <?php  $i++;} ?>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
 <section class="fl-detail_story">
        <div class="container">
                <div class="fl-title">
                    <div class="fl-title-small ">
                        <?php esc_html_e('STORY','filmmaker'); ?>
                    </div>
                </div>
            <div class="row">
                    <div class="vision-box2">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="feature-video">
                                <?php if(get_field('image_movie')) {
                                        echo '<img src="'.get_field('image_movie').'" alt="'.esc_html__('image movie','filmmaker').'">';
                                    } ?>
                                <?php if(get_field('text_movie')) { ?>
                                    <p class="short-text-img-fl">
                                         <?php the_field('text_movie'); ?>
                                    </p>
                                <?php } ?>
                            </div>

                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="vision-container">
                                <?php if(get_field('description-movie')) { ?>
                                    <div class="vision-desc">
                                        <?php the_field('description-movie'); ?>
                                    </div>
                                <?php } ?>
                                <div class="vision-info">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </section>
<!-- section partners -->
<?php if(get_field('film_studio')){ ?>
    <section class="partner-item partner-item-detail">
        <div class="container">
            <div class="row">
                <div class="fl-title">
                    <div class="fl-title-small ">
                        <?php esc_html_e('PARTNERS','filmmaker'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php while(has_sub_field('film_studio')){ ?>
                    <div class="col-md-2 col-sm-3 col-xs-6">
                        <div class="partner-img">
                            <a href="<?php the_sub_field('studio_url'); ?>" title="<?php esc_html_e('partners','filmmaker'); ?>">
                                <?php if(get_sub_field('logo-studio')){ ?>
                                    <img src="<?php the_sub_field('logo-studio'); ?>" alt="<?php esc_html_e('logo studio','filmmaker'); ?>">
                                <?php }else{
                                         echo '<img src="http://placehold.it/370x370" alt="'.esc_html__('No images','filmmaker').'">';
                                    } ?>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>
<!-- Gallery film -->
<?php $posts = get_field('gallery_list'); if( $posts ): ?>
<?php $num = rand(11,4576); ?>
    <section class="gallery-film-detail">
        <div class="fl-title fl-bottom">
            <div class="fl-title-small"><?php esc_html_e('PHOTO','filmmaker'); ?></div>
            <div class="fl-title-big"><?php esc_html_e('Gallery','filmmaker'); ?></div>
        </div>
        <div class="slide-gallery">
            <div class="fl-gallery-detail">
                <!-- masterslider -->
                <div class="master-slider fl-gallery-view-detail ms-skin-default" id="g-detail<?php echo esc_html($num); ?>">
                        <?php foreach( $posts as $post): // variable must be called $post (IMPORTANT) ?>
                            <?php setup_postdata($post); ?>
                             <?php if(get_field('gallery_film')){
                                       while(has_sub_field('gallery_film')){ ?>
                                         <div class="ms-slide">
                                            <img src="<?php the_sub_field('image_gallery_film') ?>" alt="<?php the_title(); ?>">
                                            <p><?php the_sub_field('short_description_image'); ?></p>
                                        </div>
                                    <?php } ?>
                                <?php  }else{
                                    esc_html_e('Not Found Gallery','filmmaker');
                                } ?>

                        <?php endforeach; ?>

                        <?php wp_reset_postdata(); ?>
                </div>
                <!-- end of masterslider -->
             </div>
             <script type="text/javascript">
                (function($){
                    "use trict";
                    var flg<?php echo esc_html($num); ?> = new MasterSlider();
                    flg<?php echo esc_html($num); ?>.control('arrows');
                    flg<?php echo esc_html($num); ?>.setup('g-detail<?php echo esc_html($num); ?>' , {
                        width:1170,
                        height:575,
                        space:0,
                        loop:true,
                        view:'flow',
                        layout:'partialview'
                    });
                })(jQuery);
            </script>
        <!-- end of template -->
        </div>
</section>
<?php endif; ?>
 <!-- Section Winning awards -->
 <?php if(get_field('winning_award')){?>
    <section class="win-item-detail">
        <div class="container">
            <div class="fl-title">
            <div class="fl-title-small"><?php esc_html_e('WINNING AWARDS','filmmaker'); ?></div>
        </div>
            <div class="row">
                <?php while(has_sub_field('winning_award')){ ?>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                        <div class="win-img">
                            <a href="<?php the_sub_field('win_award_url') ?>"><img src="<?php the_sub_field('image-award'); ?>" alt="<?php esc_html_e('Winning award','filmmaker'); ?>"></a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>
<!-- Cast and Crew -->
<?php if( get_field('ingred_group') ): ?>
    <section class="ingred">
        <div class="container">
            <div class="fl-title">
                <div class="fl-title-small"><?php esc_html_e('CAST & CREW','filmmaker'); ?></div>
            </div>
            <div class="ingred-box">
                <ul class="ingred-list">
                    <?php while( has_sub_field('ingred_group') ): ?>
                        <li>
                        <div class="name-group-ingred"><?php the_sub_field('group_ingredient__name'); ?></div>
                            <?php if( get_sub_field('ingredient_actor') ): ?>
                                <?php while( has_sub_field('ingredient_actor') ): ?>
                                <span class="element-ingred"><?php the_sub_field('ingredient_actor_name'); ?></span><span class="gg">/</span>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </section>
<?php endif; ?>
<!-- section crew detail  -->
<?php if(get_field('character')){ ?>
    <section class="character-item" >
        <div class="swiper-container detail-character-container">
            <div class="swiper-wrapper">
                <?php while(has_sub_field('character')){ ?>
                    <div class="swiper-slide">
                            <?php if(get_sub_field('image-character')){
                                echo '<img src="'.get_sub_field('image-character').'" alt="'.esc_html__('image character','filmmaker').'">';
                            }else{
                                echo '<img src="http://placehold.it/363x500" alt="'.esc_html__('no images','filmmaker').'">';
                                } ?>
                        <div class="character-content">
                            <?php if(get_sub_field('name-character')){ ?>
                                <div class="team-name">
                                    <?php the_sub_field('name-character'); ?>
                                </div>
                            <?php } ?>
                            <?php if(get_sub_field('role')){ ?>
                                <div class="team-job">
                                    <?php the_sub_field('role'); ?>
                                </div>
                            <?php } ?>
                         </div>
                         <?php if(get_sub_field('description-character-film')){ ?>
                                <div class="charater-desc">
                                    <?php the_sub_field('description-character-film'); ?>
                                </div>
                            <?php } ?>
                    </div>
                <?php } ?>
            </div>
                <div class="swiper-button-next">
                    <img src="<?php echo get_template_directory_uri(); ?>/asset/images/icons/g-prev.png" alt="<?php esc_html_e('previous','filmmaker') ?>">
                </div>
                <div class="swiper-button-prev">
                    <img src="<?php echo get_template_directory_uri(); ?>/asset/images/icons/g-next.png" alt="<?php esc_html_e('next','filmmaker') ?>">
                </div>
        </div>

        <script>
            (function($){
                "use strict";
                var chswiper = new Swiper('.detail-character-container', {
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    slidesPerView: 4,
                    paginationClickable: true,
                    spaceBetween: 30,
                    loop: true,
                    speed:1000,
                    //autoplay:true,
                    parallax:true,
                    breakpoints: {
                        480: {
                          slidesPerView: 1,
                          spaceBetweenSlides: 20
                        },
                        767: {
                          slidesPerView: 2,
                          spaceBetweenSlides: 30
                        }
                    }
                });
            })(jQuery);

        </script>
    </section>
<?php } ?>
<?php
    // get the custom post type's taxonomy terms
    $custom_taxterms = wp_get_object_terms( $post->ID, 'film_category', array('fields' => 'ids') );
        // arguments
        $args = array(
        'post_type' => 'film',
        'post_status' => 'publish',
        'posts_per_page' => 2, // you may edit this number
        'orderby' => 'rand',
        'tax_query' => array(
            array(
                'taxonomy' => 'film_category',
                'field' => 'id',
                'terms' => $custom_taxterms
            )
        ),
        'post__not_in' => array ($post->ID),
        );
        $related_items = new WP_Query( $args ); ?>
        <section class="relate-film-detail">
            <ul>
            <?php  
                if ($related_items->have_posts()) :
                while ( $related_items->have_posts() ) : $related_items->the_post();
                $category = get_the_terms($post->id, 'film_category');
            ?>
                <li>
                    <div class="more_film">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php if(has_post_thumbnail()){
                                 the_post_thumbnail();
                                }else{
                                  echo '<img src="http://placehold.it/960x540" alt="'.esc_html_e('No images','filmmaker').'">';
                            } ?>
                        </a>
                        <div class="center centertxt">
                            <div class="title-white">
                                <?php echo esc_html($category[0]->name);  ?>
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
                <?php endwhile; endif; wp_reset_postdata();?>
            </ul>
        </section>
<?php endwhile; ?>
<?php get_footer(); ?>
