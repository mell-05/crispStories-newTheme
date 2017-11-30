<?php

class PeThemeShortcodeHavenStats extends PeThemeShortcode {

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "pservice";
		$this->group = __("CONTENT",'Pixelentity Theme/Plugin');
		$this->name = __("Stats",'Pixelentity Theme/Plugin');
		$this->description = __("Add a styled status",'Pixelentity Theme/Plugin');
		$this->fields = array(
							  "icon" =>				
								array(
									"label"=>__("Status icon",'Pixelentity Theme/Plugin'),
									"type"=>"Select",
									"options" => pe_theme_typicons_icons(),
									"description"=>__('Select icon that will be assigned to this status. For icons overview, Google "typicons".','Pixelentity Theme/Plugin'),
									"default"=>"hide"
								),
							  "title" =>
							  array(
									"label" => __("Title",'Pixelentity Theme/Plugin'),
									"type" => "Text",
									"description" => __("This should be a status title.",'Pixelentity Theme/Plugin'),
									"default" => __("Happy Clients",'Pixelentity Theme/Plugin')
									),
							  "number" =>
							  array(
									"label" => __("Number",'Pixelentity Theme/Plugin'),
									"type" => "Text",
									"description" => __("This should be a status number.",'Pixelentity Theme/Plugin'),
									"default" => 84
									),
							  );
		// add block level cleaning
		peTheme()->shortcode->blockLevel[] = $this->trigger;
	}

	public function output($atts,$content=null,$code="") {
		extract($atts);

		$html = 
			"<div class='stat'>
			<div class='stat-upper text-center'>
				<h1 class='text-white'>$number</h1>
			</div>
			<div class='stat-lower'>
				<i class='$icon text-white'></i>
			</div>	
			<span class='text-white'>$title</span></div>";


        return trim($html);
	}


}

?>