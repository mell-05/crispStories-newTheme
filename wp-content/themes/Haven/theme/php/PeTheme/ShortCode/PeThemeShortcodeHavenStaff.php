<?php

class PeThemeShortcodeHavenStaff extends PeThemeShortcode {

	public $instances = 0;
	protected $items;


	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "pstaff";
		$this->group = __("CONTENT",'Pixelentity Theme/Plugin');
		$this->name = __("Staff",'Pixelentity Theme/Plugin');
		$this->description = __("Staff member",'Pixelentity Theme/Plugin');
		$this->fields = array(
								"staff" => 
									array(
										  "label"=>__("Staff Member",'Pixelentity Theme/Plugin'),
										  "type"=>"Select",
										  "options" => peTheme()->staff->option(),
										  "description" => __("Select staff to insert",'Pixelentity Theme/Plugin'),
										  "default"=> array()
										  ),
							  );

		peTheme()->shortcode->blockLevel[] = $this->trigger;
		
	}


	public function output($atts,$content=null,$code="") {
		extract($atts);

		$t =& peTheme();
		$content =& $t->content;

		ob_start();

		?>

		<?php if (!empty($staff)) { ?>
		

		<?php
			if ($loop = $t->data->customLoop((object) array("post_type"=>"staff","id" => (array) $staff,"orderby" => "post__in",))) {

				while ($content->looping()) {

					$meta =& $content->meta();

					?>

					<div class="team-member-holder text-center">
						<?php $content->img(432,432); ?>
						<h5><?php $content->title(); ?></h5>
						<div class="hr"></div>
						<span><?php echo $meta->info->position; ?></span>

						<div class="staff-content"><?php $content->content(); ?></div>

						<div class="social-icons">
							<ul>
								<?php $t->content->socialLinks($meta->info->social,"staff"); ?>
							</ul>
						</div>
					</div>

					<?php

				}

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