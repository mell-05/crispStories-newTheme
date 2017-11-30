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
		<div class="large-8 large-centered columns page-spiel">

			<div class="page-body pe-wp-default">
				<?php $content->content(); ?>
			</div>
			
		</div>
	</div>

	<?php 

		if (empty($meta->ptables->ptables)) { 

			$ptables = get_posts( array( 'post_type' => 'ptable', 'posts_per_page' => 3 ) );

			if ( is_array( $ptables ) ) {

				foreach( $ptables as $ptable ) {

					$ptablesarray[] = $ptable->ID;

				}

				$meta->ptables->ptables = $ptablesarray;

			}

		}

		?>

		<?php if (!empty($meta->ptables->ptables)) : ?>

		<div class="row">		

		<?php
			if ($loop = $t->data->customLoop((object) array("post_type"=>"ptable","id" => $meta->ptables->ptables,"orderby" => "post__in",'posts_per_page' => 4))) {

				$count = 12 / count( $meta->ptables->ptables );

				while ($content->looping()) {

					$i = 0;
					$meta =& $content->meta();
					$features = isset( $meta->table->features ) ? $meta->table->features : '';
					$featured = $meta->table->featured == 'yes' ? 'trigger-value ' : '';

					?>

					<div class="large-<?php echo $count; ?> columns">

						<div class="<?php echo $featured; ?>price-table text-center">

							<h5><?php echo $meta->table->title; ?></h5>

							<div class="price-holder">
								<div class="price">
									<?php echo $meta->table->price; ?>
								</div>
							</div>

							<div class="hr"></div>

							<?php if ( is_array( $features ) ): ?>

								<ul class="plan-features">

									<?php foreach ( $features as $feature ) : ?>

										<li><?php echo $feature; ?></li>

									<?php endforeach; ?>

								</ul>

							<?php endif; ?>

							<?php if ( ! empty( $meta->table->button_label ) ): ?>

								<div class="btn-holder">
									<a href="<?php echo esc_attr( $meta->table->button_link ); ?>">
										<div class="btn"><?php echo $meta->table->button_label; ?> <i class="typcn typcn-chevron-right"></i></div>
									</a>
								</div>

							<?php endif; ?>

						</div>

					</div>

					<?php

				}

				$content->resetLoop();

			}
		?>

		</div>

		<?php endif; ?>

</section>