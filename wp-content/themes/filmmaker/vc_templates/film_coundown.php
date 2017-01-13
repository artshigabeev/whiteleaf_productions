<?php
$coundown_number = $subcribe_title = $color_number= $color_title= $hover_color ='';
extract(shortcode_atts(array(
    'coundown_number'     => '',
    'subcribe_title'    => '',
    'color_title'		=> '',
    'color_number'		=> '',
    'hover_color'		=> '',
), $atts));
$cl_number = 'style="color:'.$color_number.'"';
?>
<style>
	.count-item .count-subject{
		color: <?php echo $color_title; ?>;
	}
	.count-item:hover .count-subject{
		color: <?php echo $hover_color; ?>;
	}
</style>
<div class="count-item centertxt wow fadeInUp" data-wow-delay="0.3s">
    <div class="count-number" data-from="0" data-to="<?php echo esc_html($coundown_number); ?>" data-speed="1000" data-refresh-interval="25" <?php echo $cl_number; ?>></div>
    <div class="count-subject" >
        <?php echo esc_html($subcribe_title); ?>
    </div>
</div>