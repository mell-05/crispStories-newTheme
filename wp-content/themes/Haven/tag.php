<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>

<section class="section-blog section-author">

	<div class="row">
		<div class="large-12 columns text-center">
			<div class="page-title no-icon">

				<h3><?php printf(__("Tag: %s",'Pixelentity Theme/Plugin'),single_tag_title("",false)); ?></h3>

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