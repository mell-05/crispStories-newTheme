<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta = $t->content->meta(); ?>
<?php $isSlider = true; //(! empty( $meta->bg->type ) && $meta->bg->type == 'slider') ? true : false;  ?>

<?php if ($isSlider): ?>

<?php $loop = $t->gallery->getSliderLoop( $meta->bg->gallery, 'ititle','caption' ); ?>

	<?php if ( $loop ): ?>

		<section id="<?php $content->slug(); ?>" class="home-section">

			<div class="home-slider">
				<ul class="slides">

					<?php while ($slide =& $loop->next()): ?>

						<li>
							<div class="row slide-content">
								<div class="large-10 large-centered columns text-center">

									<?php if ( ! empty( $slide->caption_title ) || ! empty( $slide->caption_description ) ) : ?>

										<div class="hero-title">
											<div class="large-3 columns nopad border-bottom"></div>
											<div class="large-6 columns text-center">

												<?php if ( ! empty( $slide->caption_title ) ) : ?>

													<h4 class="text-white alt-h"><?php echo $slide->caption_title; ?></h4>

												<?php endif; ?>

											</div>

											<?php if ( empty( $slide->caption_title ) ) : ?>

													<div class="large-6 columns nopad border-bottom"></div>

											<?php endif; ?>

											<div class="large-3 columns nopad border-bottom"></div>
											<h1 class="text-white"><?php echo $slide->caption_description; ?></h1>
										</div><!--end of hero title-->

									<?php endif; ?>
									
									<?php if ( ! empty( $slide->button ) ) : ?>

										<a class="smooth-scroll" href="<?php echo esc_attr( $slide->link ); ?>">
											<div class="btn"><?php echo $slide->button; ?> <i class="typcn typcn-chevron-right"></i></div>
										</a>

									<?php endif; ?>
									
								</div>
							</div>

							<img class="slider-bg" alt="<?php echo esc_attr( $slide->alt ); ?>" src="<?php echo esc_attr( $slide->img ); ?>" />
						</li>

					<?php endwhile; ?>

				</ul>
			</div><!--end of Home slider-->

		</section>

	<?php else: ?>

		<p><?php _e("Gallery you selected as a Slider Gallery in Home page settings contains no slides, make sure to upload at least one image for selected gallery.",'Pixelentity Theme/Plugin'); ?></p>

	<?php endif; ?>


<?php endif; ?>