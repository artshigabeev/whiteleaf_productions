<?php

/**
 * Create class Aboutme_Widget
 */
class aboutme_Widget extends WP_Widget {

        /**
         * setting: neme, base ID
         */
        function __construct() {
            parent::__construct (
              'aboutme_widget', // id widget
              'Aboutme Widget', // name widget
              array(
                  'description' => 'about me beautheme widget'
              )
            );
        }

        /**
         * form option widget
         */
        function form( $instance ) {
              $default = array(
            'title' => __('Title widget','filmmaker'),
            'url_img' => __('link image author','filmmaker'),
            'name_author' => __('Dean Bui','filmmaker'),
            'address' => __('content about','filmmaker'),
            'social'  => "",
            );
            $instance = wp_parse_args( (array) $instance, $default );
            $title = esc_attr($instance['title']);
            $url = esc_attr( $instance['url_img'] );
            $name_author = esc_attr( $instance['name_author'] );
            $address = esc_attr( $instance['address'] );
            $social = esc_attr( $instance['social'] );
            echo '<p>Title <input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$title.'"/></p>';
            echo '<p>URL avatar <input type="text" class="widefat" name="'.$this->get_field_name('url_img').'" value="'.$url.'"/></p>';
            echo '<p>Title <input type="text" class="widefat" name="'.$this->get_field_name('name_author').'" value="'.$name_author.'"/></p>';
            echo '<p>Title <input type="text" class="widefat" name="'.$this->get_field_name('address').'" value="'.$address.'"/></p>';
            echo '<p>Enable  <input type="checkbox" class="widefat" name="'.$this->get_field_name('social').'" value="'.$social.'"/></p>';
        }
        /**
         * save widget form
         */

        function update( $new_instance, $old_instance ) {

            parent::update( $new_instance, $old_instance );
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['url_img'] = strip_tags($new_instance['url_img']);
            $instance['name_author'] = strip_tags($new_instance['name_author']);
            $instance['address'] = strip_tags($new_instance['address']);
            $instance['social'] = strip_tags($new_instance['social']);
            return $instance;
        }

        /**
         * Show widget
         */

        function widget( $args, $instance ) {
            extract( $args );
            $title = apply_filters( 'widget_title', $instance['title'] );
            $url = apply_filters( 'widget_avatar', $instance['url_img'] );
            $name_author = apply_filters( 'widget_name_author', $instance['name_author'] );
            $address = apply_filters( 'widget_address', $instance['address'] );
            $social = apply_filters( 'social', $instance['social'] );?>

                <?php echo $args['before_widget'];
                if ( ! empty( $title ) )?>
            <div class="title-about about-title-sidebar">
                <?php echo ($args['before_title'] . $title . $args['after_title']);?>
            </div>
            <div class="avt">
                <?php echo('<img src="'.esc_url($url).'">'); ?>
            </div>
            <div class="name-bloger box-bottom black1">
                <?php echo( $name_author ); ?>
            </div>
            <div class="gray-desc line-height box-bottom">
                <?php echo( $address );?>
            </div>

            <div class="subcribe box-bottom">
                <div class="box-center padding3">
                    <?php
                    global $filmmaker_option;
                    if ($filmmaker_option) {
                        if (isset($filmmaker_option['show-social-link'])) {
                            echo '
                            <div class="widget-footer">
                            <ul class="list-social">';
                            foreach($filmmaker_option['show-social-link'] as $key=> $social){
                                if(isset($filmmaker_option['beau-'.$social])){
                                    echo '<li><a href="'.esc_url($filmmaker_option['beau-'.$social]).'" target="_blank" title="'.esc_html('social','filmmaker').'"><i class="fa fa-'.esc_attr($social).'"></i></a></li>';
                                }
                            }
                            echo '</ul></div>';
                        }else{
							esc_html_e('Social link not config','filmmaker');
						}
                    }
                    printf('%s',$after_widget);?>
                </div>
            </div>
       <?php }
}

add_action( 'widgets_init', 'create_aboutme_widget' );
function create_aboutme_widget() {
        register_widget('aboutme_Widget');
}
