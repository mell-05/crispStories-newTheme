<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $isSingle = is_single(); ?>

<?php while ($content->looping() ) : ?>

	<?php $meta =& $content->meta(); ?>
	<?php $link = get_permalink(); ?>
	<?php $type = $content->type(); ?>
	<?php $hasFeatImage = $content->hasFeatImage(); ?>
	<?php $classes = is_sticky() ? 'post post-single sticky' : 'post post-single'; ?>

	<div <?php post_class($classes); ?>>

		<div class="inner-spacer-right-lrg">
			<div class="post-title">

				<?php if ($isSingle): ?>

					<h4><?php $content->title() ?></h4>

				<?php else: ?>

					<h4><a href="<?php echo $link ?>"><?php $content->title() ?></a></h4>

				<?php endif; ?>

				<div class="post-meta">

					<?php _e( 'By' ,'Pixelentity Theme/Plugin'); ?> <?php the_author_posts_link(); ?>

					<?php if ($isSingle): ?>

						<?php _e( 'on' ,'Pixelentity Theme/Plugin'); ?> <?php the_time( 'F j, Y' ); ?>

					<?php else: ?>

						<a href="<?php echo $link ?>">

							<?php _e( 'on' ,'Pixelentity Theme/Plugin'); ?> <?php the_time( 'F j, Y' ); ?>

						</a>

					<?php endif; ?>

					<?php if ($type === "post"): ?>

						<?php _e( 'in' ,'Pixelentity Theme/Plugin'); ?> <?php $content->category(); ?>

					<?php endif; ?>

				</div>

			</div>

			<?php if ( ! post_password_required( $post->ID ) ) : ?>

				<div class="post-media">

					<?php 

					switch($content->format()):

						case "gallery": // Gallery post ?>
						
							<?php $t->media->w(800); ?>
							<?php $t->media->h(450); ?>
							<?php $t->gallery->output($meta->gallery->id,'GalleryImages'); ?>

							<?php break; 

						case "video": // Video post ?>

							<?php $videoID = $t->content->meta()->video->id; ?>

								<?php if ($video = $t->video->getInfo($videoID)): ?>

									<div class="video-container">

										<?php switch($video->type): case "youtube": ?>

											<iframe width="800" height="450" src="http://www.youtube.com/embed/<?php echo $video->id; ?>?autohide=1&modestbranding=1&showinfo=0" class="fullwidth-video" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
										
										<?php break; case "vimeo": ?>

											<iframe src="http://player.vimeo.com/video/<?php echo $video->id; ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" class="fullwidth-video" width="800" height="450" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
										
										<?php endswitch; ?>

									</div>

								<?php endif; ?>

							<?php break; 

						default: // Standard post ?>

							<?php if ($hasFeatImage): ?>

								<?php $content->img(800,450); ?>

							<?php else: ?>

							<?php endif; ?>

					<?php endswitch; ?>

				</div>

				<?php endif; ?>

		</div>
		
		<div class="post-body pe-wp-default">
			<?php $content->content(); ?>
			<?php $content->linkPages(); ?>
		</div>

		<?php if ($type === "post" && has_tag()): ?>

			<div class="tags">
				<?php the_tags('',' ',''); ?>
			</div>

		<?php endif; ?>

	</div>

	<?php if (is_singular( 'post' )): ?>

		<?php get_template_part("common","prevnext"); ?>

	<?php endif; ?>

	<?php if ($isSingle): ?>

		<?php comments_template(); ?>

	<?php endif; ?>

<?php endwhile; ?>

<?php if (!$isSingle): ?>

	<?php $t->content->pager(); ?>

<?php endif; ?>