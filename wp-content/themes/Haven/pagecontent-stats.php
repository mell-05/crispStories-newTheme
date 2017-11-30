<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php $hasFeatImage = $content->hasFeatImage(); ?>
<section class="stats-section divider" id="<?php $content->slug(); ?>" style="background-color:<?php echo $meta->pagebg->color; ?>">

	<?php if ( ! empty( $meta->pagebg->background ) ) : ?>

		<img class="divider-bg" alt="" src="<?php echo esc_attr( $meta->pagebg->background ); ?>" />

	<?php endif; ?>
	
	<div class="row pad-normal">
		<?php $content->content(); ?>
	</div>

	<?php if ( $hasFeatImage ) : ?>
	
	<div class="row">
		<div class="large-12 columns text-center">

			

				<img class="promo-pic" alt="" src="<?php echo $content->get_origImage(); ?>" />

		</div>
	</div>

	<?php endif; ?>

</section>