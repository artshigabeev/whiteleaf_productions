<?php
	/*
	* Template Name: Blank default with header and footer
	*/
	get_header();
	while ( have_posts() ) : the_post();
		the_content();
	endwhile;
	get_footer();
?>