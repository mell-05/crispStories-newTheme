<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $image =& $t->image; ?>
<?php $meta =& $content->meta(); ?>

<!-- Begin Services -->
<section class="services divider" id="<?php $content->slug(); ?>" style="background-color:<?php echo $meta->pagebg->color; ?>">
	
	<?php if ( ! empty( $meta->pagebg->background ) ) : ?>

		<img class="divider-bg" alt="" src="<?php echo esc_attr( $meta->pagebg->background ); ?>" />

	<?php endif; ?>

	<div class="row pad-top">
		<div class="large-12 columns text-center">
			<div class="page-title <?php if ( $meta->page_icon->icon == 'hide' ) echo 'no-icon'; ?>">
				<h3 class="text-white"><?php $content->title(); ?></h3>

				<?php if ( $meta->page_icon->icon !== 'hide' ): ?>

					<i class="<?php echo $meta->page_icon->icon; ?> text-white"></i>

				<?php endif; ?>

			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="large-10 large-centered columns">

			<div class="page-body pe-wp-default">
				<?php $content->content(); ?>
			</div>

		</div>
	</div>


	<?php 

	if (empty($meta->services->members)) {

		$filled = true;

		$services = get_posts( array( 'post_type' => 'service', 'posts_per_page' => -1 ) );

		if ( is_array( $services ) ) {

			foreach( $services as $service ) {

				$servicesarray[] = $service->ID;

			}

			$meta->services->members = $servicesarray;

		}

	} else {

		$filled = false;

	}

	?>

	<?php if (!empty($meta->services->members)) { ?>
	

	<?php
		if ($loop = $t->data->customLoop((object) array("post_type"=>"service","id" => $meta->services->members,"orderby" => "post__in",))) {

			$i = 0;

			while ($content->looping()) {

				$i++;

				$meta =& $content->meta();
				$features = isset( $meta->info->features ) ? $meta->info->features : '';
				?>

				<?php if ( $i % 2 == 1 ) : ?>

					<div class="row">

				<?php endif; ?>

					<div class="large-4 columns push-2">
						<div class="service <?php echo $meta->info->icon_position == 'bottom' ? 'bottom-service' : ''; ?>">
							<div class="service-container text-center">

								<?php if ( $meta->info->icon_position == 'top' ) : ?>

									<div class="service-top">
										<i class="<?php echo $meta->info->icon; ?>"></i>
									</div>

								<?php endif; ?>

								<div class="service-title">
									<h5 class="text-white"><?php $content->title(); ?></h5>
								</div>
								<div class="text-white">
									<?php $content->content(); ?>
								</div>
							</div>

							<?php if ( $meta->info->icon_position == 'bottom' ) : ?>

								<div class="service-top">
									<i class="<?php echo $meta->info->icon; ?>"></i>
								</div>
								
							<?php endif; ?>

						</div>
					</div><!--end of inidvidual service-->

				<?php if ( $i % 2 == 0 ) : ?>

					</div>

				<?php endif; ?>

				<?php

			}

			$content->resetLoop();

		}
	?>

	<?php } ?>

</section>