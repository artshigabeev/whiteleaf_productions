<?php
/**
 * create class lastest post
 */
class Last_Post extends WP_Widget {

    function __construct() {
        parent::__construct(
            'last_post', //id
            'Beau Lastest post', //name widget
            array( 'description'  =>  'Widget show lastest posts ' )
        );
    }

    function form( $instance ) {

        $default = array(
            'title' => 'Lastest post',
            'post_number' => 5
        );
        $instance = wp_parse_args( (array) $instance, $default );
        $title = esc_attr($instance['title']);
        $post_number = esc_attr($instance['post_number']);

        echo '<p>Input title <input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$title.'"/></p>';
        echo '<p>Number of posts to show: <input type="number" class="widefat" name="'.$this->get_field_name('post_number').'" value="'.$post_number.'" placeholder="'.$post_number.'" max="30" /></p>';

    }
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['post_number'] = strip_tags($new_instance['post_number']);
        return $instance;
    }
    function widget( $args, $instance ) {
        extract($args);
        $title = apply_filters( 'widget_title', $instance['title'] );
        $post_number = $instance['post_number'];

        echo $before_widget;
        echo $before_title.$title.$after_title;
        $random_query = new WP_Query('posts_per_page='.$post_number.'&orderby=ASC');

        if ($random_query->have_posts()):
            echo "<ol>";
            while( $random_query->have_posts() ) :
                $random_query->the_post(); ?>
                <li class="post-latest-item">
                    <div class="nopadding col-lg-6">
                        <div class="img-post">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                             <?php if ( has_post_thumbnail() ) {
                                    the_post_thumbnail('BE');
                                } else{ ?>
                                    <img src="http://placehold.it/120x80" alt="<?php esc_html_e('No image','filmmaker');?>">
                               <?php  } ?>
                            </a>
                        </div>
                    </div>
                    <div class="nopadding col-lg-6">
                        <div class="title-side-bar">
                            <div class="info"><?php echo get_the_date('j M Y'); ?></div>
                            <div class="title-post">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo wp_trim_words( get_the_title(), 5, '...' ); ?></a>
                            </div>
                        </div>
                    </div>
                </li>

            <?php endwhile;
            echo "</ol>";
        endif;
        echo $after_widget;

    }
}
add_action( 'widgets_init', 'create_lastpost_widget' );
function create_lastpost_widget() {
    register_widget('Last_Post');
}?>