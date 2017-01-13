<?php
    $text_small = $text_big = $distance_number = $color_text_small = $color_text_big='';
    extract(shortcode_atts(array(
        'text_small' => '',
        'text_big' => '',
        'distance_number' => '',
        'color_text_small' => '',
        'color_text_big'    => '',
        ), $atts));
    $cl_text_small = 'style="color:'.$color_text_small.'"';
    $cl_text_big = 'style="color:'.$color_text_big.'"';
 ?>

    <div class="fl-title wow fadeInDown" data-wow-delay="0.3s">
        <?php if($text_small !=""){ ?>
            <div class="fl-title-small" <?php echo $cl_text_small; ?>><?php echo esc_html($text_small); ?></div>
        <?php } ?>
        <?php if($text_big !=""){ ?>
        <div class="fl-title-big bottom-title-big" <?php echo $cl_text_big; ?>><?php echo esc_html($text_big); ?></div>
        <?php } ?>
    </div>