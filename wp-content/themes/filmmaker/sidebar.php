<div class="sidebar-content about about-d col-md-12 col-sm-12 col-xs-12 pull-right">
<?php
	if (function_exists("dynamic_sidebar")) {
	 	if ( is_active_sidebar( 'sidebar-right' ) ){
			dynamic_sidebar( 'sidebar-right' );
		}
	}
?>
</div>