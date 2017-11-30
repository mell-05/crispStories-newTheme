<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php $hasFeatImage = $content->hasFeatImage(); ?>
<section class="section-testimonials divider <?php if ( ! $hasFeatImage ) echo 'no-margin'; ?>" id="<?php $content->slug(); ?>" style="background-color:<?php echo $meta->pagebg->color; ?>">

	<?php if ( ! empty( $meta->pagebg->background ) ) : ?>

		<img class="divider-bg" alt="" src="<?php echo esc_attr( $meta->pagebg->background ); ?>" />

	<?php endif; ?>
	
	<div class="row pad-normal <?php if ( ! $hasFeatImage ) echo 'no-margin'; ?>">
		<?php $content->content(); ?>
	</div>

	<?php 

		if (empty($meta->clients->members)) { 

			$clients = get_posts( array( 'post_type' => 'testimonial', 'posts_per_page' => -1 ) );

			if ( is_array( $clients ) ) {

				foreach( $clients as $client ) {

					$clientsarray[] = $client->ID;

				}

				$meta->clients->members = $clientsarray;

			}

		}

		?>

		<?php $count = 0; ?>

		<?php if (!empty($meta->clients->members)) : ?>

			<div class="clients">
				<div class="row">

					<?php

						if ($loop = $t->data->customLoop((object) array("post_type"=>"testimonial","id" => $meta->clients->members,"orderby" => "post__in",))) {

							while ($content->looping()) :

								$meta =& $content->meta();
								?>

								<div class="large-3 columns">
									<?php $content->img(154,0); ?>
								</div>

								<?php

							endwhile;

							$content->resetLoop();

						}
					?>

				</div>
			</div>

		<?php endif; ?>

</section>