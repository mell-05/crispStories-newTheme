<?php $t =& peTheme();?>
<?php $content =& $t->content; ?>
<?php $meta = $t->content->meta(); ?>

<div id="the-navigation" <?php echo is_front_page() && is_page() ? '' : 'class="inner-nav"'?>>
	
	<div class="row">
		<div class="large-4 columns">
			<a href="<?php echo home_url(); ?>">

			<?php $logo = $t->options->get("logo"); ?>

			<?php if ( ! empty( $logo ) ) : ?>

				<?php $t->image->retina($logo); ?>

			<?php else : ?>

				<h5 class="text-white"><?php echo $t->options->get("siteTitle"); ?></h5>

			<?php endif; ?>

			</a>
		</div>
		<div id="mobile-toggle" class="right"><i class="typcn typcn-th-menu text-white"></i></div>
		<div class="large-8 columns">
			<div id="menu">
				<?php $t->menu->show("main"); ?>

				<ul class="header-social hide">
					<?php $t->content->socialLinks($t->options->get("headerSocialLinks"),"header"); ?>
				</ul>
				
			</div>
		</div>
	</div>
	
</div><!--end of navigation-->