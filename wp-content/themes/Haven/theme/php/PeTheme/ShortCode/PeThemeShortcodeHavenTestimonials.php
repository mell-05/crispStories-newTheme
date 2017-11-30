<?php

class PeThemeShortcodeHavenTestimonials extends PeThemeShortcode {

	public $instances = 0;
	protected $items;


	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "ptestimonials";
		$this->group = __("CONTENT",'Pixelentity Theme/Plugin');
		$this->name = __("Testimonials",'Pixelentity Theme/Plugin');
		$this->description = __("Testimonials slider",'Pixelentity Theme/Plugin');
		$this->fields = array(
								"testimonials" => 
									array(
										  "label"=>__("Testimonials",'Pixelentity Theme/Plugin'),
										  "type"=>"Links",
										  "options" => peTheme()->testimonial->option(),
										  "description" => __("Add one or more testimonials to slider.",'Pixelentity Theme/Plugin'),
										  "sortable" => true,
										  "default"=> array()
										  ),
							  );

		peTheme()->shortcode->blockLevel[] = $this->trigger;
		
	}


	public function output($atts,$content=null,$code="") {
		extract($atts);

		$testimonials = explode( ',', $testimonials );

		$t =& peTheme();
		$content =& $t->content;

		ob_start();

		?>

		<?php if (!empty($testimonials)) { ?>
		

		<?php
			if ($loop = $t->data->customLoop((object) array("post_type"=>"testimonial","id" => $testimonials,"orderby" => "post__in",))) {



				?>
				<div class="testimonials-slider">
					<ul class="slides">
						<?php

						while ($content->looping()) {

							$meta =& $content->meta();

							?>

							<li>
								<div class="large-9 large-centered columns">
									<div class="text-white">
										<?php $content->content(); ?>
									</div>
									<h5 class="text-white"><?php echo $meta->info->type; ?></h5>
								</div>
							</li>							

							<?php

						}

						?>
					</ul>
				</div>
				<?php

				$content->resetLoop();

			}
		?>

		<?php } ?>


		<?php

		$html = ob_get_contents();
		ob_end_clean();

		return trim($html);
	}


}

?>