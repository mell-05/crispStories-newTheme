<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>

<section id="<?php $content->slug(); ?>" class="page-section pad-normal" style="background-color:<?php echo $meta->pagebg->color; ?>">

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
		<div class="large-12 large-centered columns">

			<div class="page-body pe-wp-default">
				<?php $content->content(); ?>
			</div>
			
		</div>
	</div>

</section>