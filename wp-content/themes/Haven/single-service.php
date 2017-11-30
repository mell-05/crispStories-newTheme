<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>

<section class="section-blog section-single-service" id="<?php $content->slug(); ?>">

	<div class="row">
		<div class="large-12 columns text-center">
			<div class="page-title no-icon">

				<h3><?php $content->title(); ?></h3>

			</div>
		</div>
	</div>

	<div class="row blog-main">
		
		<div class="large-9 columns blog-left">
			
			<div class="post-body pe-wp-default">
				<?php while ($content->looping() ) : ?>
					<?php $content->content(); ?>
				<?php endwhile; ?>
			</div>
					
		</div>

		<?php get_sidebar(); ?>

	</div>

</section>

<?php get_footer(); ?>