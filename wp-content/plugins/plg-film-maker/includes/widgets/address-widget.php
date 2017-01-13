<?php
class beau_Adress_Widget extends WP_Widget {

	/**
	 * Setup widget: Name, base ID
	 */
	function beau_Adress_Widget() {
		$tpwidget_options = array(
			'classname' => 'beau_adress_widget', //ID của widget
			'description' => __('This show address and link social for footer','bebostore')
		);
		parent::__construct('beau_adress_widget', 'Address with social', $tpwidget_options);
	}

	/**
	 * Create option for widget
	 */
	function form( $instance ) {

		$default = array(
			'title' => __('Title','bebostore'),
			'address' => __('Your address in html','bebostore'),
		);

		$instance = wp_parse_args( (array) $instance, $default);

		$title = esc_attr( $instance['title'] );
		$address = esc_attr( $instance['address'] );

		//Show options for admin panel
		echo "<p>".__("Title", 'bebostore')."<input type='text' class='widefat' name='".$this->get_field_name('title')."' value='".$title."' /></p>";
		echo "<p>".__("Address",'bebostore')."<textarea class='widefat' rows='16' cols='20' name='".$this->get_field_name('address')."'>".$address."</textarea></p>";


	}

	/**
	 * save widget form
	 */

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['address'] = $new_instance['address'];
		return $instance;
	}

	/**
	 * Show widget
	 */

	function widget( $args, $instance ) {
		extract( $args );
		$title 	 = apply_filters( 'widget_title', $instance['title'] );
		$address = apply_filters( 'widget_body', $instance['address'] );
		printf('%s', $before_widget);
		printf('%s%s%s', $before_title,$title,$after_title) ;
		// printf('%s',$arr);
		printf('%s', $address);
		global $filmmaker_option;
		if ($filmmaker_option) {
			if ($filmmaker_option['show-social-link']) {
				echo '
				<div class="widget-footer">
				<ul class="list-social">';
				foreach($filmmaker_option['show-social-link'] as $key=> $social){
					if(isset($filmmaker_option['beau-'.$social])){
						echo '<li><a href="'.esc_url($filmmaker_option['beau-'.$social]).'" target="_blank"><i class="fa fa-'.esc_attr($social).'"></i></a></li>';
					}
				}
				echo '</ul></div>';
			}
		}
		printf('%s',$after_widget);
	}
}

/*
 * Create widget item
 */
add_action( 'widgets_init', 'beau_store_widget' );
function beau_store_widget() {
	register_widget('beau_Adress_Widget');
}