<?php
$withus_text = $withus_text_link = $withus_url = $color_title = $color_link_submit = $color_border_link= $hover_color_link = $hover_bg_link = $bg_link_join ='';
extract(shortcode_atts(array(
    'withus_text'       => '',
    'withus_text_link'  =>'',
    'withus_url'        => '',
    'color_title'       => '',
    'color_link_submit' => '',
    'color_border_link' => '',
    'hover_color_link'  => '',
    'hover_bg_link'     => '',
    'bg_link_join'      => '',
    ), $atts));
    $cl_title = 'style="color:'.$color_title.'"';
 ?>
 <style>
    .dff-container{
        background:<?php echo $bg_link_join; ?>;
    }
    .dff-container .view-more{
        border-color: <?php echo $color_border_link; ?>
    }
    .dff-container .view-more a{
        color:<?php echo $color_link_submit; ?>;
    }
    .dff-container .view-more:hover{
        background:<?php echo $hover_bg_link; ?>;
    }
    .dff-container .view-more:hover a{
        color:<?php echo $hover_color_link; ?>;
    }
 </style>
 <div class="dff-container">
    <span class="dff-text" <?php echo $cl_title; ?>>
        <?php echo esc_html($withus_text); ?>
    </span>
    <span class="view-more">
        <a href="<?php echo esc_html($withus_url); ?>" title="<?php echo esc_html($withus_text_link); ?>" target="_blank">
            <?php echo esc_html($withus_text_link); ?>
        </a>
    </span>
</div>