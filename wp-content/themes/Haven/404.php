<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>

<section class="section-blog section-404" id="404">

	<div class="row">
		<div class="large-12 columns text-center">
			<div class="page-title no-icon">

				<h3>404</h3>

			</div>
		</div>
	</div>

	<div class="row blog-main">
		
		<div class="large-10 columns large-offset-1 blog-left">
			
			<div class="post-body pe-wp-default">
				<h3>
					<?php _e("The page you're looking for doesn't exist.",'Pixelentity Theme/Plugin'); ?>
				</h3>
			</div>
					
		</div>

	</div>

</section>

<?php get_footer(); ?>