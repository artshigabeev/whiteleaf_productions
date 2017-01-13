<?php
    $small_title_product_list =$film_list_id = $big_title_product_list = $film_style = $film_perpage = $view_more_url = $view_more_text = $film_list_checkbox_link = $color_small_title = $color_big_title= $color_viewmore = $color_border_viewmore = $hover_color_viewmore = $hover_background_viewmore = $color_category_film = $color_name_film = $color_description_film = $color_link_film = $color_border_link_film = $hover_color_link_film = $hover_background_link_film ='';
    extract(shortcode_atts(array(
        'small_title_product_list' => '',
        'big_title_product_list' => '',
        'film_style' => '',
        'film_perpage' => '',
        'film_list_id' =>'',
        'view_more_url' => '',
        'film_list_checkbox_link' => '',
        'view_more_text' => '',
        'color_small_title' => '',
        'color_big_title'   => '',
        'color_viewmore'    => '',
        'hover_color_viewmore'  => '',
        'hover_background_viewmore'   => '',
        'color_category_film'   => '',
        'color_name_film'       => '',
        'color_description_film'=> '',
        'color_link_film'       => '',
        'color_border_link_film'=> '',
        'hover_color_link_film' => '',
        'hover_background_link_film'  => '',
    ), $atts));
    $cl_small_title = 'style="color:'.$color_small_title.'"';
    $cl_big_title   = 'style="color:'.$color_big_title.'"';
    $cl_viewmore    = 'style="color:'.$color_viewmore.'"';
    $cl_cat_film    = 'style="color:'.$color_category_film.'"';
    $cl_name_film   = 'style="color:'.$color_name_film.'"';
    $cl_desc_film   = 'style="color:'.$color_description_film.'"';
    $cl_link_film   = 'style="color:'.$color_link_film.'"';
 ?>
<style>
    .p-viewmore .more{
        border: 1px solid  <?php echo $color_border_viewmore; ?>;
    }
    .p-viewmore .more:hover{
        background: <?php echo $hover_background_viewmore; ?>;
    }
    .p-viewmore .more:hover a, .p-viewmore .more:hover .fa{
        color: <?php echo $hover_color_viewmore;?> !important;
    }
    .fl-viewmore .more-white{
        border: 1px solid <?php echo $color_border_link_film ?> ;
    }
    .fl-viewmore:hover .more-white{
        background: <?php echo $hover_background_link_film; ?>
    }
    .film-item .title-white:hover a, .film-item .title-bold-white:hover a, .p-category:hover a, .p-name:hover a{
        color: <?php echo  $hover_color_link_film; ?> !important;
    }
</style>
  <section class="product-item">
    <div class="container-fluid ">
        <div class="fl-title fl-bottom">

            <?php if($small_title_product_list !=""){ ?>
                <div class="fl-title-small" <?php echo $cl_small_title; ?>>
                    <?php echo esc_html($small_title_product_list); ?>
                </div>
            <?php  }else { ?>
              <?php  } ?>

             <?php if($big_title_product_list !=""){ ?>
                <div class="fl-title-big" <?php echo $cl_big_title; ?>>
                    <?php echo esc_html($big_title_product_list); ?>
                </div>
            <?php  }else { ?>
            <?php  } ?>
        </div>
            <?php
            $i = 0;
            $listID = explode(',',$film_list_id);
            if ($film_list_id == '') {
                $args = array(
                'post_type' => 'film',
                'orderby' => 'date',
                );
            }else{
                $args = array(
                    'post_type' => 'film',
                    'post__in' => $listID,
                );
            }
            $loop = new wp_Query($args);
            wp_reset_postdata();
         ?>
            <?php if($film_style == 0){ ?>
                <?php include( get_template_directory() . '/template/film-video-default.php'); ?>
            <?php }elseif($film_style == 1){ ?>
                <?php include( get_template_directory() . '/template/film-video-odd.php'); ?>
            <?php }elseif($film_style == 2){
                include( get_template_directory() . '/template/film-video-full.php');
            }elseif($film_style ==3){
                include( get_template_directory() . '/template/film-video-4.php');
            } ?>
    </div>
</section>