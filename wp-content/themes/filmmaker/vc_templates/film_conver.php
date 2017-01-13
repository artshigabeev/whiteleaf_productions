<?php
 $image = $name_small_title = $small_title = $link_to = $short_description = $color_title_cat = $color_name_film = $color_desc_film = '';
extract(shortcode_atts(array(
    'image'                 => '',
    'small_title'           => '',
    'name_small_title'      => '',
    'link_to'               => '',
    'short_description'     => '',
    'color_title_cat'       => '',
    'color_name_film'       => '',
    'color_desc_film'       => '',
), $atts));
$img = wp_get_attachment_image_src($image, 'filmmaker_BE');
$cl_title_cat = 'style="color:'.$color_title_cat.'"';
$cl_name_film = 'style="color:'.$color_name_film.'"';
$cl_desc_film = 'style="color:'.$color_desc_film.'"';

?>
<div class="more_film">
    <div class="bg-more-fl"></div>
    <a href="<?php echo esc_url($link_to);?>" title="<?php echo esc_html($name_small_title);?>">
        <img src="<?php echo esc_url($img[0]);?>" alt="<?php echo esc_html($name_small_title); ?>">
    </a>
    <div class="center centertxt">
            <?php
            if ($small_title == ""){
            } else{
            ?>
             <div class="title-white" <?php echo $cl_title_cat; ?>><?php echo esc_html($small_title);?></div>
            <?php } ?>

            <?php
            if ($name_small_title == ""){
            } else{
            ?>
             <div class="title-bold-white"><a href="<?php echo esc_url($link_to);?>" title="<?php echo esc_html($name_small_title);?>"  <?php echo $cl_name_film; ?>><?php echo esc_html($name_small_title);?></a></div>
            <?php } ?>

            <?php
            if ($short_description == ""){
            } else{
            ?>
             <div class="white-desc" <?php echo $cl_desc_film; ?>><?php echo esc_html($short_description);?></div>
            <?php } ?>

        </div>
</div>

