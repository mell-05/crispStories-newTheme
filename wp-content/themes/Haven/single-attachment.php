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
			
			<?php if ( ! post_password_required( $post->ID ) ) : ?>

				<?php while ($content->looping() ) : ?>

					<?php if ( wp_attachment_is_image( $post->id ) ) : ?>

						<div class="post-media clearfix">
							<?php $img = wp_get_attachment_image_src( $post->id, "full"); ?>
							<?php $content->img(940,0,$img[0]); ?>
						</div>

					<?php endif; ?>

				<?php endwhile; ?>

			<?php endif; ?>
					
		</div>

	</div>

</section>

<?php get_footer(); ?>