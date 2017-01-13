<?php
/**
 * create class lastest post
 */
class Recent_Comments extends WP_Widget {

    function __construct() {
        parent::__construct(
            'recent_comments', //id
            'Beau Recent Comments', //name widget
            array( 'description'  =>  'Comment show ' )
        );
    }
    function form( $instance ) {
        $default = array(
            'title' => 'Recent comments',
            'recentcomments_number' => 3
        );
        $instance = wp_parse_args( (array) $instance, $default );
        $title = esc_attr($instance['title']);
        $recentcomments_number = esc_attr($instance['recentcomments_number']);

        echo '<p>Input title <input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$title.'"/></p>';
        echo '<p>Number of comment to show: <input type="number" class="widefat" name="'.$this->get_field_name('recentcomments_number').'" value="'.$recentcomments_number.'" placeholder="'.$recentcomments_number.'" max="20" /></p>';

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['recentcomments_number'] = strip_tags($new_instance['recentcomments_number']);
        return $instance;
    }
    function widget( $args, $instance ) {
        extract($args);
        $title = apply_filters( 'widget_title', $instance['title'] );
        $recentcomments_number = $instance['recentcomments_number'];

        echo $before_widget;
        echo $before_title.$title.$after_title;

        $args = array(
            'status' => 'all',
            'number' => $recentcomments_number,
            'not post_id' => 1, // use post_id, not post_ID
        );
        $comments = get_comments($args);
        ?>
        <ol class="recent-comment">
           <?php foreach($comments as $comment) : ?>
                <li class="recent-item">
                    <div class="info"><?php echo(get_comment_date('j M Y', $comments[0]->comment_ID ) . '<br />' );?></div>
                    <div class="gray-desc">
                    <strong> <?php echo('@'.$comment->comment_author); ?></strong>
                    <?php
                        echo(' - ');
                        echo wp_trim_words($comment->comment_content, 20,'');

                     ?>
                    </div>
                </li>
            <?php endforeach;?>
        </ol>
        </div></div>
   <?php }
}
add_action( 'widgets_init', 'create_recent_comments' );
function create_recent_comments() {
    register_widget('Recent_Comments');
}?>