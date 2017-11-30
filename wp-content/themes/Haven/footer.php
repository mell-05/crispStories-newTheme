<?php $t =& peTheme(); ?>
<?php $layout =& $t->layout; ?>

<?php $contactFooter = apply_filters( 'pe_theme_contact_footer', true ); ?>

<?php if ( $contactFooter ) : ?>

<section id="contact">
	
	<div id="contact-holder" style="background-color:<?php echo $t->options->get("footerBg"); ?>">

		<?php if ( $t->options->get("footerContactForm") == "yes" ) : ?>
	
			<div class="row pad-top">
				<div class="large-12 columns text-center">
					<div class="page-title">
						<h3 class="text-white"><?php echo $t->options->get("contactHeading"); ?></h3><i class="typcn typcn-mail text-white"></i>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="large-8 large-centered columns page-spiel">
					<p class="text-white">
						<?php echo $t->options->get("contactDescription"); ?>
					</p>
				</div>
			</div>
			
			<div id="contact-form" class="row">
				<form action="#" method="post" class="peThemeContactForm">
					<div class="large-10 large-centered columns">
						<div class="form-upper">
							<h5 class="text-white left"><?php _e('Name','Pixelentity Theme/Plugin'); ?></h5>
							<div class="input-holder">
								<input id="form-name" name="author" />
							</div>
						</div>
					
						
						<div class="form-upper form-last">
							<h5 class="text-white left"><?php _e('Email','Pixelentity Theme/Plugin'); ?></h5>
							<div class="input-holder">
								<input id="form-email" name="email" />
							</div>
						</div>
						
						<div class="form-lower">
							<h5 class="text-white left"><?php _e('Message','Pixelentity Theme/Plugin'); ?></h5>
							<div class="input-holder">
								<input id="form-message" name="message" />
							</div>
						</div>

						<div id="form-button">
							<h5><button type="submit"><?php _e('SEND','Pixelentity Theme/Plugin'); ?></button></h5>
						</div>

						<div class="clearfix"></div>
						<br>
						
						<div id="contactFormSent" class="formSent response-message form-sent">
							<i class="typcn typcn-thumbs-up"></i> <?php echo $t->options->get("msgOK"); ?>
						</div>
						<div id="contactFormError" class="formError response-message details-error">
							<i class="typcn typcn-warning"></i> <?php echo $t->options->get("msgKO"); ?>
						</div>
						
					</div>
				</form>
			</div>

		<?php else: ?>

			<br><br>
			<br><br>

		<?php endif; ?>
		
		<div class="row">
			<div class="large-12 columns text-center">
				<div class="social-icons">
					<ul style="background-color:<?php echo $t->options->get("footerBg"); ?>">
						<?php $t->content->socialLinks($t->options->get("footerSocialLinks"),"header"); ?>
					</ul>
					<div class="line"></div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="large-12 columns text-center">
				<h6 class="text-white copy-text"><?php echo $t->options->get("footerCopyright"); ?></h6>
			</div>
		</div>
		
	</div><!--end of contact-holder-->

</section><!--end of Contact section-->

<?php else : ?>

<div id="footer">
	<div class="row">
		<div class="large-12 columns text-center">
			<h6><?php echo $t->options->get("footerCopyright"); ?></h6>
		</div>
	</div>
</div>

<?php endif; ?>
							
<?php $t->footer->wp_footer(); ?>

</body>
</html>
