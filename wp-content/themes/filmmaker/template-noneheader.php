<?php
	/*
	* Template Name: Blank no header & no footer
	*/
	get_header('blank');
	while ( have_posts() ) : the_post();
		the_content();
	endwhile;
	get_footer();
?>