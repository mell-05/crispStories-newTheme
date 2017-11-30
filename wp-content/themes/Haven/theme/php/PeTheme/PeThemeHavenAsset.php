<?php

class PeThemeHavenAsset extends PeThemeAsset  {

	public function __construct(&$master) {
		$this->minifiedJS = "theme/compressed/theme.min.js";
		$this->minifiedCSS = "theme/compressed/theme.min.css";

		//define("PE_THEME_LOCAL_VIDEO_SUPPORT",true);

		parent::__construct($master);
	}

	public function registerAssets() {

		add_filter("pe_theme_js_init_file",array(&$this,"pe_theme_js_init_file_filter"));
		add_filter("pe_theme_js_init_deps",array(&$this,"pe_theme_js_init_deps_filter"));
		add_filter("pe_theme_minified_js_deps",array(&$this,"pe_theme_minified_js_deps_filter"));
		
		parent::registerAssets();

		if ($this->minifyCSS) {
			$deps = 
				array(
					  "pe_theme_compressed"
					  );
		} else {


			// theme styles
			$this->addStyle("css/foundation.css",array(),"pe_theme_haven-foundation");
			$this->addStyle("css/flexslider.css",array(),"pe_theme_haven-flexslider");
			$this->addStyle("css/typicons.css",array(),"pe_theme_haven-typicons");
			$this->addStyle("css/style.css",array(),"pe_theme_haven-style");
			$this->addStyle("css/responsive.css",array(),"pe_theme_haven-responsive");
			$this->addStyle("css/ie.css",array(),"pe_theme_haven-ie");
			$this->addStyle("css/custom.css",array(),"pe_theme_haven-custom");

			$deps = 
				array(
					  "pe_theme_haven-foundation",
					  "pe_theme_haven-flexslider",
					  "pe_theme_haven-typicons",
					  "pe_theme_haven-style",
					  "pe_theme_haven-responsive",
					  "pe_theme_haven-ie",
					  "pe_theme_flare",
					  "pe_theme_haven-custom",
					  );
		}

		$this->addStyle("style.css",$deps,"pe_theme_init");

		$this->addScript("theme/js/pe/pixelentity.controller.js",
						 array(
							   "pe_theme_mobile",
							   "pe_theme_utils_browser",
							   "pe_theme_selectivizr",
							   "pe_theme_lazyload",
							   //"pe_theme_flare",
							  "pe_theme_haven-modernizr",
							  "pe_theme_haven-foundation",
							  "pe_theme_haven-flexslider",
							  "pe_theme_haven-isotope",
							  "pe_theme_haven-fitvids",
							  "pe_theme_haven-smoothscroll",
							  "pe_theme_haven-inview",
							  "pe_theme_haven-scripts",
							  "pe_theme_widgets_contact",
							  "pe_theme_haven-custom"
							   ),"pe_theme_controller");

		$this->addScript("js/vendor/custom.modernizr.js",array(),"pe_theme_haven-modernizr");
		$this->addScript("js/foundation.min.js",array(),"pe_theme_haven-foundation");
		$this->addScript("js/jquery.flexslider-min.js",array(),"pe_theme_haven-flexslider");
		$this->addScript("js/isotope.js",array(),"pe_theme_haven-isotope");
		$this->addScript("js/jquery.fitvids.js",array(),"pe_theme_haven-fitvids");
		$this->addScript("js/smooth-scroll.js",array(),"pe_theme_haven-smoothscroll");
		$this->addScript("js/inview.js",array(),"pe_theme_haven-inview");
		$this->addScript("js/scripts.js",array(),"pe_theme_haven-scripts");
		$this->addScript("js/custom.js",array(),"pe_theme_haven-custom");
		
	}

	public function pe_theme_js_init_file_filter($js) {
		return $js;
		//return "js/custom.js";
	}

	public function pe_theme_js_init_deps_filter($deps) {
		return $deps;
		/*
		  return array(
		  "jquery",
		  );
		*/
	}

	public function pe_theme_minified_js_deps_filter($deps) {
		return $deps;
		//return array("jquery");
	}

	public function style() {
		bloginfo("stylesheet_url"); 
	}

	public function enqueueAssets() {
		$this->registerAssets();

		$t =& peTheme();
		
		if ($this->minifyJS && file_exists(PE_THEME_PATH."/preview/init.js")) {
			$this->addScript("preview/init.js",array("jquery"),"pe_theme_preview_init");
			wp_localize_script("pe_theme_preview_init", 'o',
							   array(
									 //"dark" => PE_THEME_URL."/css/dark_skin.css",
									 "css" => $this->master->color->customCSS(true,"color1")
									 ));
			wp_enqueue_script("pe_theme_preview_init");
		}	
		
		wp_enqueue_style("pe_theme_init");
		wp_enqueue_script("pe_theme_init");

		wp_localize_script("pe_theme_init","_haven",
			array(
				"ajax-loading" => PE_THEME_URL . '/images/ajax-loader.gif',
				"home_url"     => home_url('/'),
			));

		if ($this->minifyJS && file_exists(PE_THEME_PATH."/preview/preview.js")) {
			$this->addScript("preview/preview.js",array("pe_theme_init"),"pe_theme_skin_chooser");
			wp_localize_script("pe_theme_skin_chooser","pe_skin_chooser",array("url"=>urlencode(PE_THEME_URL."/")));
			wp_enqueue_script("pe_theme_skin_chooser");
		}

	}


}

?>