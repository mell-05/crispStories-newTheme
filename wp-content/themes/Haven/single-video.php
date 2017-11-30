<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>

<section class="section-blog section-single-video" id="<?php $content->slug(); ?>">

	<div class="row">
		<div class="large-12 columns text-center">
			<div class="page-title no-icon">

				<h3><?php $content->title(); ?></h3>

			</div>
		</div>
	</div>

	<div class="row blog-main">
		
		<div class="large-10 columns large-offset-1 blog-left">
			
			<div class="post-body pe-wp-default">

				<?php if ( ! post_password_required( $post->ID ) ) : ?>
					
					<?php $t->video->output(get_the_id()); ?>

				<?php else : ?>

					<?php echo get_the_password_form(); ?>

				<?php endif; ?>

			</div>
					
		</div>

	</div>

</section>

<?php get_footer(); ?>