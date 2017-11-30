<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<section class="section-portfolio pad-normal" id="<?php $content->slug(); ?>" style="background-color:<?php echo $meta->pagebg->color; ?>">

	<?php if ( ! empty( $meta->pagebg->background ) ) : ?>

		<img class="divider-bg" alt="" src="<?php echo esc_attr( $meta->pagebg->background ); ?>" />

	<?php endif; ?>

	<div class="row">
		<div class="large-12 columns text-center">
			<div class="page-title <?php if ( $meta->page_icon->icon == 'hide' ) echo 'no-icon'; ?>">
				<h3><?php $content->title(); ?></h3>

				<?php if ( $meta->page_icon->icon !== 'hide' ): ?>

					<i class="<?php echo $meta->page_icon->icon; ?>"></i>

				<?php endif; ?>

			</div>
		</div>
	</div>

	<div class="row">
		<div class="large-8 large-centered columns page-spiel">

			<div class="page-body pe-wp-default"><?php $content->content(); ?></div>
			
		</div>
	</div>

	<?php if (!post_password_required()): ?>
	
		<?php $t->project->portfolio($content->meta()->portfolio,false) ?>

	<?php else: ?>

		<?php get_template_part("password"); ?>
	
	<?php endif; ?>
	
</section>