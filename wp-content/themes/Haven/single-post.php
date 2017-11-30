<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>

<section class="section-blog section-blog-single" id="<?php $content->slug(); ?>">

	<div class="row">
		<div class="large-12 columns text-center">
			<div class="page-title">

				<h3><?php _e('Our Blog','Pixelentity Theme/Plugin'); ?></h3>
				<i class="typcn typcn-brush"></i>

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