<div class="slide-flim">
    <?php
        if (function_exists('masterslider')) {
            $beau_master_slider_id = get_post_meta(get_the_ID(),'_beautheme_header_using_slider', TRUE);
            masterslider($beau_master_slider_id);
        }else{
            esc_html_e('You must active master slider plugin','filmmaker');
        }
    ?>
</div>