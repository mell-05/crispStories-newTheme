<?php

class PeThemeHavenTemplate extends PeThemeTemplate  {

	public function __construct(&$master) {
		parent::__construct($master);
	}

	public function paginate_links($loop) {
		if (!$loop) return "";
?>
<hr>
<div class="row-fluid post-pagination">
	<div class="<?php echo $loop->main->class ?>">
		<div class="pagination">
				<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
				<span class="page-info">
					<?php _e("Page",'Pixelentity Theme/Plugin'); ?> 
					<?php echo ' ' . $paged . ' '; ?>
					<?php _e("of",'Pixelentity Theme/Plugin'); ?>
					 <?php echo $loop->last + 1; ?>
				</span>
				<?php while ($page =& $loop->next()): ?>

					<?php if ( $page->class == 'active' ) : ?>

						<span class="current"><?php echo $page->num; ?></span>

					<?php else: ?>

						<a href="<?php echo $page->link; ?>" class="inactive"><?php echo $page->num; ?></a>

					<?php endif; ?>

				<?php endwhile; ?>
		</div>  
	</div>
</div>
<?php
	}


}

?>