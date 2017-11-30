<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php $hasFeatImage = $content->hasFeatImage(); ?>
<section class="divider <?php if ( ! $hasFeatImage ) echo 'no-margin'; ?>" id="<?php $content->slug(); ?>" style="background-color:<?php echo $meta->pagebg->color; ?>">

	<?php if ( ! empty( $meta->pagebg->background ) ) : ?>

		<img class="divider-bg" alt="" src="<?php echo esc_attr( $meta->pagebg->background ); ?>" />

	<?php endif; ?>
	
	<div class="row pad-large <?php if ( ! $hasFeatImage ) echo 'no-margin'; ?>">
		<div class="large-10 large-centered columns text-center">
			<div class="divider-hero text-white"><?php $content->content(); ?></div>
		</div>
	</div>
	
	<div class="row">
		<div class="large-12 columns text-center">

			<?php if ( $hasFeatImage ) : ?>

				<img class="promo-pic" alt="" src="<?php echo $content->get_origImage(); ?>" />

			<?php endif; ?>

		</div>
	</div>

</section>