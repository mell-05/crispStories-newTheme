<?php $t =& peTheme(); ?>
<?php if ($t->comments->supported()): ?>
<!--comment section-->
<div class="row-fluid" id="comments">
	<div class="span12 commentsWrap">
		<div class="inner-spacer-right-lrg">

			<!--title-->
			<div class="row">
				<div class="col-md-12">
					<h5 id="comments-title">
						<?php _e("Comments",'Pixelentity Theme/Plugin'); ?> <span>( <?php $t->content->comments(); ?> )</span>
					</h5>
				</div>
			</div>
			
			<?php $t->comments->show(); ?>
			
			<div class="row">
				<div class="col-md-12">
					<?php $t->comments->pager(); ?>
				</div>
			</div>
			
			<?php $t->comments->form(); ?>
		</div>
		
	</div>
	<!--end comments wrap-->
</div>
<!--end comments-->
<?php endif; ?>