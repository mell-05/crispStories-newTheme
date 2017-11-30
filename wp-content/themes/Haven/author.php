<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>
<?php 

	if(get_query_var('author_name')) :

		$curauth = get_user_by('slug', get_query_var('author_name'));

	else :

		$curauth = get_userdata(get_query_var('author'));

	endif;
?>

<section class="section-blog section-author">

	<div class="row">
		<div class="large-12 columns text-center">
			<div class="page-title no-icon">

				<h3><?php printf(__("Author: %s",'Pixelentity Theme/Plugin'),$curauth->nickname); ?></h3>

			</div>
		</div>
	</div>

	<div class="row blog-main">
		
		<div class="large-9 columns blog-left">
			
			<?php $t->content->loop(); ?>
					
		</div>

		<?php get_sidebar(); ?>

	</div>

</section>

<?php get_footer(); ?>