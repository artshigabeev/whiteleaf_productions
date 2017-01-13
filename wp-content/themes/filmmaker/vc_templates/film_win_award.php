<?php
    $win_image = $win_url = '';
    extract(shortcode_atts(array(
        'win_image' =>'',
        'win_url' => '',
        ),$atts));

 ?>
<div class="win-img wow fadeInUp" data-wow-delay="0.3s">
    <a href="<?php echo esc_html($win_url);?>" title="<?php esc_html_e('winning award','filmmaker'); ?>">
        <img src="<?php  echo wp_get_attachment_url($win_image); ?>" alt="<?php esc_html_e('winning award','filmmaker'); ?>">
    </a>
</div>