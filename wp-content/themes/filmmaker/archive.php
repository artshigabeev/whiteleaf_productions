<?php
get_header();
$beau_archive = '';
if (filmmaker_GetOption('archive-page') != '') {
	$beau_archive = filmmaker_GetOption('archive-page');
}else{
	$beau_archive = "postcatstandard";
}
get_template_part('template/section-'.$beau_archive);
get_footer();
?>