<?php $t =& peTheme(); ?>
<?php $project =& $t->project; ?>
<?php list($portfolio) = $t->template->data(); ?>

<div class="projects-instance offix">

	<div class="row">
		<div class="large-12 columns text-center">
			<ul class="filters" data-container="">

				<?php $project->filter('',"button","active"); ?>

			</ul>
		</div>
	</div>

	<div class="row">
	
		<div class="project-container" data-container="">

			<?php $content =& $t->content; ?>

			<?php while ($content->looping()): ?>

				<?php $meta =& $content->meta(); ?>
				<?php $class = isset( $meta->ajax->ajax ) && $meta->ajax->ajax === 'yes' ? 'doajax' : 'noajax'; ?>

				<div class="large-4 columns project text-center <?php echo $class; ?> <?php $project->filterClasses(); ?>">

					<div class="project-img-holder">
						<?php $content->img(800,533); ?>
						<div class="arrow-holder">
							<div class="arrow-up"></div>
						</div>
					</div>

					<div class="project-title">
						<h5><?php $content->title(); ?></h5>
						<div class="hr"></div>
						<span><?php 

								$terms = get_the_terms( get_the_id(), 'prj-category' );
								$output = '';

								if ( $terms && ! is_wp_error( $terms ) ) :

									foreach ( $terms as $term ) {
										$output .= $term->name . ' / ';
									}

									$output = substr( $output, 0, -3 );

									echo $output;

								endif;

								?></span>
						
						<div class="project-btn-holder">
							<a href="<?php echo get_permalink(); ?>">
								<div class="btn"><?php _e('VIEW','Pixelentity Theme/Plugin'); ?><i class="typcn typcn-chevron-right"></i></div>
							</a>
						</div>
					</div>

				</div>

			<?php endwhile; ?>
		
		</div>
	
	</div>

	<div class="ajax-container" data-container=""></div>

</div>