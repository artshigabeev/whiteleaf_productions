<?php
class beau_Instagram_Widget extends WP_Widget {

    /**
     * Setup widget: Name, base ID
     */
    function beau_Instagram_Widget() {
        $tpwidget_options = array(
            'classname' => 'beau_instagram_widget', //ID của widget
            'description' => __('This widget show your instagram image','beautheme')
        );
        parent::__construct('beau_instagram_widget', 'Beau Instagram', $tpwidget_options);
    }

    /**
     * Create option for widget
     */
    function form( $instance ) {

        $default = array(
            'title' => __('Instagram','beautheme'),
            'instagram_user' => __('beautheme','beautheme'),
            'number_image' => 6,
        );

        $instance = wp_parse_args( (array) $instance, $default);

        $title = esc_attr( $instance['title'] );
        $instagram_user = esc_attr( $instance['instagram_user'] );
        $number_image = intval( $instance['number_image'] );

        //Show options for admin panel
        echo "<p>".__("Title", 'beautheme')."<input type='text' class='widefat' name='".$this->get_field_name('title')."' value='".$title."' /></p>";
        echo "<p>".__("Instagram User", 'beautheme')."<input type='text' class='widefat' name='".$this->get_field_name('instagram_user')."' value='".$instagram_user."' /></p>";
        echo "<p>".__("Number of images: ", 'beautheme')."<input type='number' class='widefat small-text' name='".$this->get_field_name('number_image')."' value='".$number_image."' /></p>";
    }

    /**
     * save widget form
     */

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['instagram_user'] = $new_instance['instagram_user'];
        $instance['number_image'] = $new_instance['number_image'];
        return $instance;
    }

    /**
     * Show widget
     */

    function widget( $args, $instance ) {

        extract( $args );
        $title   = apply_filters( 'widget_title', $instance['title'] );
        $instagram_user = apply_filters( 'widget_body', $instance['instagram_user'] );
        $number_image = $instance['number_image'];
        // $number_image = $instance['number_image'];
        printf('%s', $before_widget);
        printf('%s%s%s', $before_title,$title,$after_title) ;

        echo '<ul class="beau-list-istagram">';
        if (function_exists('beau_instagram_image')) {
            $instag = beau_instagram_image($instagram_user, $number_image);
        }else{
            $instag = array();
        }

        if (count($instag) > 0) {
            foreach ($instag as $key => $value) {
                echo '<li><a href="'.esc_url($value['link_to']).'" target="_blank" ><img src="'.esc_url($value['link']).'" alt="'.esc_html__('Takent by','beautheme').' '.$instagram_user.'"></a></li>';
            }
        }else{
            esc_html_e('No image found','bebo');
        }
        echo '</ul>
         <p class="beau-instagram-link"><i class="fa fa-instagram"></i><a target="_blank" href="http://instagram.com/'.$instagram_user.'" title="'.$instagram_user.'">'.esc_html__('View on instagram','beautheme').'</a></p>';

        printf('%s',$after_widget);
    }
}

/*
 * Create widget item
 */
add_action( 'widgets_init', 'beau_register_instagram' );
function beau_register_instagram() {
    register_widget('beau_Instagram_Widget');
}