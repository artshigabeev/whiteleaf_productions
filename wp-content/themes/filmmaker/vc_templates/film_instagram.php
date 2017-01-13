<?php
$title_section = $instagram_user ="";
extract(shortcode_atts(array(
    'title_section' => '',
    'instagram_user' =>'',
    'perpage' => 8,
), $atts));
if (function_exists('beau_instagram_image')) {
    $instag = beau_instagram_image($instagram_user, $perpage);
}else{
    $instag = array();
}
?>
<!-- instagram -->
<div id="fl_instagram" >
    <div class="box-center">
         <span class="title-gray"><i class="fa fa-instagram"></i><?php echo esc_html($title_section)?></span>
    </div>
    <div class="list-item-instagram">
        <?php
            if (count($instag) > 0) {
                foreach ($instag as $key => $value) {
        ?>
                <div class="col-insta item-instagram">
                    <a href="<?php echo esc_url($value['link_to']);?>" title="<?php echo esc_html($instagram_user); ?>" target="_blank"><img src="<?php echo esc_url($value['link']);?>" alt="<?php printf('Taken by %s', $instagram_user);?>"></a>
                </div>
        <?php
                }
            }else{
                esc_html_e('No image found','filmmaker');
            }
        ?>
    </div>
</div>