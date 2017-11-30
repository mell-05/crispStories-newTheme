<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>

<section id="<?php $content->slug(); ?>" class="about-section pad-normal" style="background-color:<?php echo $meta->pagebg->color; ?>">

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
		<div class="large-10 large-centered columns">

			<div class="page-body pe-wp-default">
				<?php $content->content(); ?>
			</div>

			<?php if ( ! empty( $meta->about->social ) ): ?>

				<div class="social-icons">
					<ul>
						<?php $t->content->socialLinks($meta->about->social,"about"); ?>
					</ul>
					<div class="line"></div>
				</div>

			<?php endif; ?>
			
		</div>
	</div>

</section><!--end of About section-->