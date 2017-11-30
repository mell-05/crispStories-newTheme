<?php
/*
Template Name: Pricing Tables
*/
?>
<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php get_header(); ?>
<?php while ($content->looping() ) : ?>
<?php get_template_part("pagecontent","ptables"); ?>
<?php endwhile; ?>
<?php get_footer(); ?>