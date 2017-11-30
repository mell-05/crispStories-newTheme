<?php

class PeThemeHaven extends PeThemeController {

	public $preview = array();

	public function __construct() {

		// custom post types
		add_action("pe_theme_custom_post_type",array(&$this,"pe_theme_custom_post_type"));

		// wp_head stuff
		add_action("pe_theme_wp_head",array(&$this,"pe_theme_wp_head"));

		// google fonts
		add_filter("pe_theme_font_variants",array(&$this,"pe_theme_font_variants_filter"),10,2);

		// menu
		add_filter("wp_nav_menu_objects",array(&$this,"wp_nav_menu_objects_filter"),10,2);

		// social links
		add_filter("pe_theme_social_icons",array(&$this,"pe_theme_social_icons_filter"));
		add_filter("pe_theme_content_get_social_link",array(&$this,"pe_theme_content_get_social_link_filter"),10,4);

		// comment submit button class
		add_filter("pe_theme_comment_submit_class",array(&$this,"pe_theme_comment_submit_class_filter"));

		// use prio 30 so gets executed after standard theme filter
		add_filter("the_content_more_link",array(&$this,"the_content_more_link_filter"),30);

		// hide the admin bar (known to cause issues with the theme when enabled)
		show_admin_bar(false);

		// remove junk from project screen
		add_action('pe_theme_metabox_config_project',array(&$this,'pe_theme_haven_metabox_config_project'),200);

		// add featured image to testimonial
		add_action('init',array(&$this,'pe_theme_haven_testimonial_supports'),200);

		// shortcodes
		add_filter("pe_theme_shortcode_columns_mapping",array(&$this,"pe_theme_shortcode_columns_mapping_filter"));
		add_filter("pe_theme_shortcode_columns_options",array(&$this,"pe_theme_shortcode_columns_options_filter"));
		add_filter("pe_theme_shortcode_columns_container",array(&$this,"pe_theme_shortcode_columns_container_filter"),10,2);

		// portfolio
		add_filter("pe_theme_filter_item",array(&$this,"pe_theme_project_filter_item_filter"),10,4);

		// remove staff meta
		add_action('pe_theme_metabox_config_staff',array(&$this,'pe_theme_metabox_config_staff_action'),11);

		// alter services meta
		add_action('pe_theme_metabox_config_service',array(&$this,'pe_theme_metabox_config_service_action'),11);

		// custom meta for gallery images
		add_filter( 'pe_theme_gallery_image_fields', array( $this, 'pe_theme_gallery_image_fields_filter' ) );

		// footer version filter
		add_filter( 'pe_theme_contact_footer', array( $this, 'pe_theme_contact_footer_filter' ) );

	}

	public function pe_theme_contact_footer_filter( $contactFooter ) {

		$template = is_page() ? get_page_template_slug() : false;

		if ( is_single() || $template == 'page_blog.php' ) {

			return false;

		} else {

			return true;

		}

	}

	public function the_content_more_link_filter($link) {
		return sprintf('<a class="button" href="%s">%s</a>',get_permalink(),__("Continue Reading..",'Pixelentity Theme/Plugin'));
	}

	public function pe_theme_project_filter_item_filter($html,$aclass,$slug,$name) {
		return sprintf('<li><a href="#" data-filter="%s"><div class="%s">%s</div></a></li>',$slug === "" ? "*" : ".filter-$slug",$slug === "" ? "btn active" : "btn",$name);
	}

	public function pe_theme_wp_head() {
		$this->font->apply();
		$this->color->apply();

		// custom CSS field
		if ($customCSS = $this->options->get("customCSS")) {
			printf('<style type="text/css">%s</style>',stripslashes($customCSS));
		}

		// custom JS field
		if ($customJS = $this->options->get("customJS")) {
			printf('<script type="text/javascript">%s</script>',stripslashes($customJS));
		}

	}

	public function pe_theme_font_variants_filter($variants,$font) {
		if ($font === "Open Sans") {
			$variants="$font:400italic,300,400,600,700";
		}
		else if ($font === "Lato") {
			$variants="$font:300";
		}
		else if ($font === "Montserrat") {
			$variants="$font:400,700";
		}
		else if ($font === "Vollkorn") {
			$variants="$font:400italic,700italic,400,700";
		}

		return $variants;
	}

	public function wp_nav_menu_objects_filter($items,$args) {
		if (is_array($items) && !empty($args->theme_location)) {
			$home = false;

			if (is_page()) {
				if ($this->content->pageTemplate() === "page_home.php") {
					$home = get_page_link(get_the_id());
				}
			}

			$i = 1;
			$len = count( $items );

			foreach ($items as $id => $item) {

				if (!empty($item->post_parent)) {
					if ($item->object === "page") {
						$page = get_page($item->object_id);
						if (!empty($page->post_name)) {
							$parent = get_page_link($item->post_parent);
							$slug = $page->post_name;
							$items[$id]->url = $home ? "#{$slug}" : $parent . "#{$slug}";
							$item->classes[] = $home ? "should-scroll" : "";
						}
					}
				} else if ($item->url === $home) {
					if ($item->object === "page") {

						$page = get_page($item->object_id);
						$items[$id]->url = $home ? "#" . $page->post_name : $items[$id]->url . "#" . $page->post_name;
						$item->classes[] = $home ? "should-scroll" : "";

					}
				}

				if ( $i != $len ) { // not a last menu item

					$items[$id]->title = $items[$id]->title . ' /';

				}

				$items[$id]->title = '<h6>' . $items[$id]->title . '</h6>';

				$i++;
			}
		}
		return $items;
	}

	public function pe_theme_social_icons_filter( $icons = null ) {

		return array(
			// label => icon | tooltip text
			__("E-Mail",'Pixelentity Theme/Plugin')    => "typcn typcn-social-at-circular|E-Mail",
			__("Dribbble",'Pixelentity Theme/Plugin')  => "typcn typcn-social-dribbble-circular|Dribbble",
			__("Facebook",'Pixelentity Theme/Plugin')  => "typcn typcn-social-facebook-circular|Facebook",
			__("Flickr",'Pixelentity Theme/Plugin')    => "typcn typcn-social-flickr-circular|Flickr",
			__("Github",'Pixelentity Theme/Plugin')    => "typcn typcn-social-github-circular|GitHub",
			__("Google+",'Pixelentity Theme/Plugin')   => "typcn typcn-social-google-plus-circular|Google+",
			__("Instagram",'Pixelentity Theme/Plugin') => "typcn typcn-social-instagram-circular|Instagram",
			__("Last.fm",'Pixelentity Theme/Plugin')   => "typcn typcn-social-last-fm-circular|Last.fm",
			__("LinkedIn",'Pixelentity Theme/Plugin')  => "typcn typcn-social-linkedin-circular|LinkedIn",
			__("Pinterest",'Pixelentity Theme/Plugin') => "typcn typcn-social-pinterest-circular|Pinterest",
			__("Skype",'Pixelentity Theme/Plugin')     => "typcn typcn-social-skype-outline|Skype",
			__("Tumblr",'Pixelentity Theme/Plugin')    => "typcn typcn-social-tumbler-circular|Tumblr",
			__("Twitter",'Pixelentity Theme/Plugin')   => "typcn typcn-social-twitter-circular|Twitter",
			__("Vimeo",'Pixelentity Theme/Plugin')     => "typcn typcn-social-vimeo-circular|Vimeo",
			__("RSS",'Pixelentity Theme/Plugin')       => "typcn typcn-rss-outline|RSS",
			__("Location",'Pixelentity Theme/Plugin')  => "typcn typcn-location-outline|Location",
			__("Website",'Pixelentity Theme/Plugin')   => "typcn typcn-world-outline|Website",
			__("YouTube",'Pixelentity Theme/Plugin')   => "typcn typcn-social-youtube-circular|YouTube",
		);

	}

	public function pe_theme_content_get_social_link_filter($html,$link,$tooltip,$icon) {
		return sprintf('<li class="social-li"><a href="%s" target="_blank" title="%s"><i class="%s"></i></a></li>',$icon === 'typcn typcn-social-at-circular' ? 'mailto:' . $link : $link,$tooltip,$icon);
	}

	public function pe_theme_comment_submit_class_filter() {
		return "contour-btn red";
	}

	public function init() {
		parent::init();

		if (PE_THEME_PLUGIN_MODE) {
			return;
		}
		
		if ($this->options->get("retina") === "yes") {
			add_filter("pe_theme_resized_img",array(&$this,"pe_theme_resized_retina_filter"),10,5);
		} else if ($this->options->get("lazyImages") === "yes") {
			add_filter("pe_theme_resized_img",array(&$this,"pe_theme_resized_img_filter"),10,4);
		}
	}

	public function pe_theme_custom_post_type() {
		$this->gallery->cpt();
		$this->video->cpt();
		$this->project->cpt();
		$this->ptable->cpt();
		$this->staff->cpt();
		$this->service->cpt();
		$this->testimonial->cpt();
		//$this->logo->cpt();
		//$this->slide->cpt();
		//$this->view->cpt();

		PeGlobal::$config["metaboxes-ptable"]["table"]["content"]["price"]["default"] = __('<span class="dollar">$</span>49<span class="terms">pm.</span>','Pixelentity Theme/Plugin'); // change default value of price container
		PeGlobal::$config["metaboxes-ptable"]["table"]["content"]["featured"] = array(
			"label"=>__("Featured?",'Pixelentity Theme/Plugin'),
			"type"=>"RadioUI",
			"description"=>__('Should this pricing table be featured?','Pixelentity Theme/Plugin'),
			"options" => Array(__("Yes",'Pixelentity Theme/Plugin')=>"yes",__("No",'Pixelentity Theme/Plugin')=>"no"),
			"default"=>"no"
		); // add extra field for "featured?" option

	}

	public function pe_theme_shortcode_columns_mapping_filter($array) {
		return array(
					"1/3" => "large-4 columns",
					"1/2" => "large-6 columns",
					"1/4" => "large-3 columns",
					"2/3" => "large-8 columns",
					"1/6" => "large-2 columns",
					"last" => ""
			  );
		}

	public function pe_theme_shortcode_columns_options_filter($array) {
		unset($array['2 Column layouts']['5/6 1/6']);
		unset($array['2 Column layouts']['1/6 5/6']);
		unset($array['2 Column layouts']['1/4 3/4']);
		unset($array['2 Column layouts']['3/4 1/4']);
		unset($array['3 Column layouts']['1/4 1/4 2/4']);
		unset($array['3 Column layouts']['2/4 1/4 1/4']);
		//unset($array['4 Column layouts']);
		//unset($array['6 Column layouts']);

		return $array;
	}

	public function pe_theme_shortcode_columns_container_filter( $template, $content ) {

		return sprintf('<div class="row">%s</div>',$content);

	}


	public function boot() {
		parent::boot();

		
		PeGlobal::$config["content-width"] = 990;
		PeGlobal::$config["post-formats"] = array("video","gallery");
		PeGlobal::$config["post-formats-project"] = array("video","gallery");

		PeGlobal::$config["image-sizes"]["thumbnail"] = array(120,90,true);
		PeGlobal::$config["image-sizes"]["post-thumbnail"] = array(260,200,false);
		

		// blog layouts
		PeGlobal::$config["blog"] =
			array(
				  __("Default",'Pixelentity Theme/Plugin') => "",
				  __("Search",'Pixelentity Theme/Plugin') => "search",
				  __("Alternate",'Pixelentity Theme/Plugin') => "project"
				  );

		PeGlobal::$config["shortcodes"] = 
			array(
				  //"BS_Badge",
				  //"BS_Label",
				  //"BS_Button",
				  //"HavenNumber",
				  //"HavenButton",
				  //"HavenService",
				  "HavenTestimonials",
				  "HavenStaff",
				  "HavenStats",
				  //"HavenNewsletter",
				  "BS_Columns",
				  "BS_Video"
				  );

		PeGlobal::$config["sidebars"] =
			array(
				  "default" => __("Default post/page",'Pixelentity Theme/Plugin'),
				  //"footer" => __("Footer Widgets",'Pixelentity Theme/Plugin')
				  );

		PeGlobal::$config["colors"] = 
			array(
				  "color1" => 
				  array(
						"label" => __("Primary Color",'Pixelentity Theme/Plugin'),
						"selectors" => 
						array(
							"#progress-bar" => "color",
							".social-icons ul li i" => "color",
							".project .btn" => "color",
							".filters li .btn" => "color",
							"blockquote" => "color",

							"#progress-bar" => "background-color",
							".btn" => "background-color",
							".divider:before" => "background-color",
							".social-icons .line" => "background-color",
							"#the-navigation.sticky-nav" => "background-color",
							"#the-navigation.inner-nav" => "background-color",
							".home-slider .slides li:before" => "background-color",
							".project .btn:hover" => "background-color",
							".filters li .active" => "background-color",
							".price-holder" => "background-color",
							"#contact-holder" => "background-color",
							"#contact .social-icons ul" => "background-color",
							".tags a" => "background-color",
							".tagcloud a" => "background-color",
							".post-password-form .btn" => "background-color",

							".page-title" => "border-color",
							".page-title h3" => "border-color",
							".team-member-holder" => "border-color",
							".project-img-holder" => "border-color",
							".project-title" => "border-color",
							".project .btn" => "border-color",
							".filters li .btn" => "border-color",
							".price-table" => "border-color",
							".widget_search input[type='text']" => "border-color",

						),
						"default" => "#222"
						),
				  );
		

		PeGlobal::$config["fonts"] = 
			array(
				  "fontBody" => 
				  array(
						"label" => __("General Font",'Pixelentity Theme/Plugin'),
						"selectors" => 
						array(
							  ".btn",
							  ".tags a",
							  ".comment-meta",
							  "h1",
							  "h2",
							  "h3",
							  "h4",
							  "h5",
							  "h6",
							  ".fn",
							  ".tagcloud a",
							  "#wp-calendar",
							  ".price-table .price",
							  ),
						"default" => "Montserrat"
						),
				  "fontSecondary" => 
				  array(
						"label" => __("Secondary Font",'Pixelentity Theme/Plugin'),
						"selectors" => 
						array(
							  "body",
							  ".alt-h",
							  ".price-table .terms",
							  ),
						"default" => "Vollkorn"
						)
				  );


		

		$options = array();

		$options = array_merge($options,
			array(
				  "import_demo" => $this->defaultOptions["import_demo"],
				  "logo" => 
				  array(
						"label"=>__("Logo",'Pixelentity Theme/Plugin'),
						"type"=>"Upload",
						"section"=>__("General",'Pixelentity Theme/Plugin'),
						"description" => __("This is the main site logo image. The image should be a .png file.",'Pixelentity Theme/Plugin'),
						"default"=> ""
						),
				  "siteTitle" => 
				  array(
						"wpml"=> true,
						"label"=>__("Header Title",'Pixelentity Theme/Plugin'),
						"type"=>"Text",
						"section"=>__("General",'Pixelentity Theme/Plugin'),
						"description" => __("Used if logo is left empty.",'Pixelentity Theme/Plugin'),
						"default"=> "Haven"
						),
				  "favicon" => 
						 array(
							   "label"=>__("Favicon",'Pixelentity Theme/Plugin'),
							   "type"=>"Upload",
							   "section"=>__("General",'Pixelentity Theme/Plugin'),
							   "description" => __("This is the favicon for your site. The image can be a .jpg, .ico or .png with dimensions of 16x16px ",'Pixelentity Theme/Plugin'),
							   "default"=> PE_THEME_URL."/favicon.png"
							   ),
				  "customCSS" => $this->defaultOptions["customCSS"],
				  "customJS" => $this->defaultOptions["customJS"],
				  "headerSocialLinks" => 
				  array(
						"label"=>__("Social Profile Links",'Pixelentity Theme/Plugin'),
						"type"=>"Items",
						"section"=>__("Header",'Pixelentity Theme/Plugin'),
						"description" => __("Add one or more links to social networks.",'Pixelentity Theme/Plugin'),
						"button_label" => __("Add Social Link",'Pixelentity Theme/Plugin'),
						"sortable" => true,
						"auto" => __("Layer",'Pixelentity Theme/Plugin'),
						"unique" => false,
						"editable" => false,
						"legend" => false,
						"fields" => 
						array(
							  array(
									"label" => __("Social Network",'Pixelentity Theme/Plugin'),
									"name" => "icon",
									"type" => "select",
									"options" => apply_filters('pe_theme_social_icons',array()),
									"width" => 185,
									"default" => ""
									),
							  array(
									"name" => "url",
									"type" => "text",
									"width" => 300, 
									"default" => "#"
									)
							  ),
						"default" => ""
						),
				  "colors" =>
				  array(
						"label"=>__("Custom Colors",'Pixelentity Theme/Plugin'),
						"type"=>"Help",
						"section"=>__("Colors",'Pixelentity Theme/Plugin'),
						"description" => __("In this page you can set alternative colors for the main colored elements in this theme. Four color options have been provided. A primary color, a secondary or complimentary color, a primary or dark grey and a secondary or light grey. To change the colors used on these elements simply write a new hex color reference number into the fields below or use the color picker which appears when each field obtains focus. Once you have selected your desired colors make sure to save them by clicking the <b>Save All Changes</b> button at the bottom of the page. Then just refresh your page to see the changes.<br/><br/><b>Please Note:</b> Some of the elements in this theme are made from images (Eg. Icons) and these items may have a color. It is not possible to change these elements via this page, instead such elements will need to be changed manually by opening the images/icons in an image editing program and manually changing their colors to match your theme's custom color scheme. <br/><br/>To return all colors to their default values at any time just hit the <b>Restore Default</b> link beneath each field.",'Pixelentity Theme/Plugin'),
						),
				  "googleFonts" => 
				  array(
						"label"=>__("Custom Fonts",'Pixelentity Theme/Plugin'),
						"type"=>"Help",
						"section"=>__("Fonts",'Pixelentity Theme/Plugin'),
						"description" => __("In this page you can set the typefaces to be used throughout the theme. For each elements listed below you can choose any front from the Google Web Font library. Once you have chosen a font from the list, you will see a preview of this font immediately beneath the list box. The icons on the right hand side of the font preview, indicate what weights are available for that typeface.<br/><br/><strong>R</strong> -- Regular,<br/><strong>B</strong> -- Bold,<br/><strong>I</strong> -- Italics,<br/><strong>BI</strong> -- Bold Italics<br/><br/>When decideing what font to use, ensure that the chosen font contains the font weight required by the element. For example, main headings are bold, so you need to select a new font for these elements which supports a bold font weight. If you select a font which does not have a bold icon, the font will not be applied. <br/><br/>Browse the online <a href='http://www.google.com/webfonts'>Google Font Library</a><br/><br/><b>Custom fonts</b> (Advanced Users):<br/> Other then those available from Google fonts, custom fonts may also be applied to the elements listed below. To do this an additional field is provided below the google fonts list. Here you may enter the details of a font family, size, line-height etc. for a custom font. This information is entered in the form of the shorthand 'font:' CSS declaration, for example:<br/><br/><b>bold italic small-caps 1em/1.5em arial,sans-serif</b><br/><br/>If a font is specified in this field then the font listed in the Google font drop menu above will not be applied to the element in question. If you wish to use the Google font specified in the drop down list and just specify a new font size or line height, you can do so in this field also, however the name of the Google font <b>MUST</b> also be entered into this field. You may need to visit the Google fonts web page to find the exact CSS name for the font you have chosen.",'Pixelentity Theme/Plugin'),
						),
				  "contactEmail" => $this->defaultOptions["contactEmail"],
				  "contactSubject" => $this->defaultOptions["contactSubject"],
				  "contactHeading" => 
				  array(
						"wpml"=> true,
						"label"=>__("Contact Form Title",'Pixelentity Theme/Plugin'),
						"type"=>"Text",
						"section"=>__("Contact Form",'Pixelentity Theme/Plugin'),
						"description" => __("Message displayed above contact form.",'Pixelentity Theme/Plugin'),
						"default"=> "Contact"
						),
				  "contactDescription" => 
				  array(
						"wpml"=> true,
						"label"=>__("Contact Form Description",'Pixelentity Theme/Plugin'),
						"type"=>"Text",
						"section"=>__("Contact Form",'Pixelentity Theme/Plugin'),
						"description" => __("Subtitle displayed above toggled contact form.",'Pixelentity Theme/Plugin'),
						"default"=> "Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates molestiae non recusandae."
						),
				  "msgOK" => 	
				  array(
						"wpml"=> true,
						"label"=>__("Mail Sent Message",'Pixelentity Theme/Plugin'),
						"type"=>"TextArea",
						"section"=>__("Contact Form",'Pixelentity Theme/Plugin'),
						"description" => __("Message shown when form message has been sent without errors",'Pixelentity Theme/Plugin'),
						"default"=>'<strong>Yay!</strong> Message sent.'
						),
				  "msgKO" => 	
				  array(
						"wpml"=> true,
						"label"=>__("Form Error Message",'Pixelentity Theme/Plugin'),
						"type"=>"TextArea",
						"section"=>__("Contact Form",'Pixelentity Theme/Plugin'),
						"description" => __("Message shown when form message encountered errors",'Pixelentity Theme/Plugin'),
						"default"=>'<strong>Error!</strong> Please validate your fields.'
						),
				  "sidebars" => 
				  array(
						"label"=>__("Widget Areas",'Pixelentity Theme/Plugin'),
						"type"=>"Sidebars",
						"section"=>__("Widget Areas",'Pixelentity Theme/Plugin'),
						"description" => __("Create new widget areas by entering the area name and clicking the add button. The new widget area will appear in the table below. Once a widget area has been created, widgets may be added via the widgets page.",'Pixelentity Theme/Plugin'),
						"default"=>""
						),
				  "footerCopyright" => 
				  array(
						"label"=>__("Copyright",'Pixelentity Theme/Plugin'),
						"wpml"=> true,
						"type"=>"TextArea",
						"section"=>__("Footer",'Pixelentity Theme/Plugin'),
						"description" => __("This is the footer copyright message.",'Pixelentity Theme/Plugin'),
						"default"=> sprintf('&copy; Copyright 2013 Haven',"\n")
						),
				  "footerSocialLinks" => 
				  array(
						"label"=>__("Social Profile Links",'Pixelentity Theme/Plugin'),
						"type"=>"Items",
						"section"=>__("Footer",'Pixelentity Theme/Plugin'),
						"description" => __("Add one or more links to social networks.",'Pixelentity Theme/Plugin'),
						"button_label" => __("Add Social Link",'Pixelentity Theme/Plugin'),
						"sortable" => true,
						"auto" => __("Layer",'Pixelentity Theme/Plugin'),
						"unique" => false,
						"editable" => false,
						"legend" => false,
						"fields" => 
						array(
							  array(
									"label" => __("Social Network",'Pixelentity Theme/Plugin'),
									"name" => "icon",
									"type" => "select",
									"options" => apply_filters('pe_theme_social_icons',array()),
									"width" => 185,
									"default" => ""
									),
							  array(
									"name" => "url",
									"type" => "text",
									"width" => 300, 
									"default" => "#"
									)
							  ),
						"default" => ""
						),
				  	"footerBg" =>
				  	array(
				  		"wpml"=> false,
						"label"=>__("Background color",'Pixelentity Theme/Plugin'),
						"type"=>"Color",
						"section"=>__("Footer",'Pixelentity Theme/Plugin'),
						"description" => __("Background color.",'Pixelentity Theme/Plugin'),
						"default"=> "#222"		  		
				  	),
				  	"footerContactForm" =>				
					array(
						"wpml"=> false,
						"label"=>__("Display Contact Form",'Pixelentity Theme/Plugin'),
						"type"=>"RadioUI",
						"section"=>__("Footer",'Pixelentity Theme/Plugin'),
						"description"=>__('Should contact form be displayed in the footer?','Pixelentity Theme/Plugin'),
						"options" => Array(__("Yes",'Pixelentity Theme/Plugin')=>"yes",__("No",'Pixelentity Theme/Plugin')=>"no"),
						"default"=>"yes"
					),
				  ));

		foreach( PeGlobal::$const->gmap->metabox["content"] as $key => $value ) {

			PeGlobal::$const->gmap->metabox["content"][ $key ]["section"] = __("Footer",'Pixelentity Theme/Plugin');

		}

		unset( PeGlobal::$const->gmap->metabox["content"]["title"] );
		unset( PeGlobal::$const->gmap->metabox["content"]["description"] );
		
		//$options = array_merge($options, PeGlobal::$const->gmap->metabox["content"]);

		$options = array_merge($options,$this->font->options());
		$options = array_merge($options,$this->color->options());

		$options["retina"] =& $this->defaultOptions["retina"];
		$options["lazyImages"] =& $this->defaultOptions["lazyImages"];
		$options["minifyJS"] =& $this->defaultOptions["minifyJS"];
		$options["minifyCSS"] =& $this->defaultOptions["minifyCSS"];

		$options["adminThumbs"] =& $this->defaultOptions["adminThumbs"];
		if (!empty($this->defaultOptions["mediaQuick"])) {
			$options["mediaQuick"] =& $this->defaultOptions["mediaQuick"];
			$options["mediaQuickDefault"] =& $this->defaultOptions["mediaQuickDefault"];
		}

		$options["updateCheck"] =& $this->defaultOptions["updateCheck"];
		$options["updateUsername"] =& $this->defaultOptions["updateUsername"];
		$options["updateAPIKey"] =& $this->defaultOptions["updateAPIKey"];

		$options["adminLogo"] =& $this->defaultOptions["adminLogo"];
		$options["adminUrl"] =& $this->defaultOptions["adminUrl"];

		
		
		PeGlobal::$config["options"] = apply_filters("pe_theme_options",$options);

	}

	public function pe_theme_metabox_config_post() {
		parent::pe_theme_metabox_config_post();

		unset( PeGlobal::$config["metaboxes-post"]['gallery']['content']['type'] );
	}

	public function pe_theme_metabox_config_page() {

		$galleries = $this->gallery->option();

		$galleries[__("Don't use gallery",'Pixelentity Theme/Plugin')] = 0;

		$pageIcon = 
			array(
				  "title" => __("Page header",'Pixelentity Theme/Plugin'),
				  "priority" => "core",
				  "where" =>
				  array(
						"post" => "all"
						),
				  "content" =>
				  array(
				  		"icon" =>				
							array(
								"label"=>__("Page icon",'Pixelentity Theme/Plugin'),
								"type"=>"Select",
								"options" => pe_theme_typicons_icons(),
								"description"=>__('Select icon that will be assigned to this page. For icons overview, Google "Typicons".','Pixelentity Theme/Plugin'),
								"default"=>"hide"
							),
						),

				  );

		$videos = $this->video->option();

		foreach ( $videos as $video => $postid ) {

			$meta = get_post_meta( $postid, 'pe_theme_meta', true );

			if ( '' !== $meta ) {

				if ( $meta->video->type === 'vimeo' )
					unset( $videos[ $video ] );

			}

		}

		if ( ! is_array( $videos ) )
			$videos = array(__("No videos defined",'Pixelentity Theme/Plugin')=>-1);

		$bgMbox = 
			array(
				  "title" => __("Settings.",'Pixelentity Theme/Plugin'),
				  "priority" => "core",
				  "where" =>
				  array(
						"post" => "page_home"
						),
				  "content" =>
				  array(
						"gallery" => 
						array(
							  "label"=>__("Slider Gallery",'Pixelentity Theme/Plugin'),
							  "type"=>"Select",
							  "options" => $this->gallery->option(),
							  "description" => __("Select a gallery used for a landing page.",'Pixelentity Theme/Plugin'),
							  "default"=> ""
							  ),
						)
				  );

		$background = 
			array(
				  "title" => __("Background",'Pixelentity Theme/Plugin'),
				  "priority" => "core",
				  "where" =>
				  array(
						"post" => "all"
						),
				  "content" =>
				  array(
						"background" => 
						array(
							"label"=>__("Background image",'Pixelentity Theme/Plugin'),
							"type"=>"Upload",
							"description" => __("Background image.",'Pixelentity Theme/Plugin'),
							"default"=> ""
						),
						"color" =>
						array(
							"label"=>__("Background color",'Pixelentity Theme/Plugin'),
							"type"=>"Color",
							"description" => __("Background color.",'Pixelentity Theme/Plugin'),
							"default"=> "#fff"
						),
					)
				  );

		$about = 
			array(
				  "title" => __("Social icons",'Pixelentity Theme/Plugin'),
				  "priority" => "core",
				  "where" =>
				  array(
						"post" => "page_about"
						),
				  "content" =>
				  array(
						"social" => 
						array(
						"label"=>__("Social Profile Links",'Pixelentity Theme/Plugin'),
						"type"=>"Items",
						"description" => __("Add one or more links to social networks.",'Pixelentity Theme/Plugin'),
						"button_label" => __("Add Social Link",'Pixelentity Theme/Plugin'),
						"sortable" => true,
						"auto" => __("Layer",'Pixelentity Theme/Plugin'),
						"unique" => false,
						"editable" => false,
						"legend" => false,
						"fields" => 
						array(
							  array(
									"label" => __("Social Network",'Pixelentity Theme/Plugin'),
									"name" => "icon",
									"type" => "select",
									"options" => apply_filters('pe_theme_social_icons',array()),
									"width" => 185,
									"default" => ""
									),
							  array(
									"name" => "url",
									"type" => "text",
									"width" => 300, 
									"default" => "#"
									)
							  ),
						"default" => ""
						),
				  )
			);

		$servicesMbox = 
			array(
				  "title" => __("Settings.",'Pixelentity Theme/Plugin'),
				  "priority" => "core",
				  "where" =>
				  array(
						"post" => "page_services"
						),
				  "content" =>
				  array(
				  		"background" => 
						array(
							  "label"=>__("Background",'Pixelentity Theme/Plugin'),
							  "type"=>"Upload",
							  "description" => __("Background images.",'Pixelentity Theme/Plugin'),
							  "default"=> PE_THEME_URL."/images/bg.jpg"
							  ),
						"services" => 
						array(
							  "label"=>__("Services",'Pixelentity Theme/Plugin'),
							  "type"=>"Links",
							  "options" => $this->service->option(),
							  "description" => __("Add one or more services.",'Pixelentity Theme/Plugin'),
							  "sortable" => true,
							  "default"=> array()
							  ),				
						)
				  );

		$serviceMbox = 
			array(
				  "type" =>"",
				  "title" =>__("Services",'Pixelentity Theme/Plugin'),
				  "priority" => "core",
				  "where" => 
				  array(
						"page" => "page_services",
						),
				  "content"=>
				  array(
						"members" => 
						array(
							  "label"=>__("Services",'Pixelentity Theme/Plugin'),
							  "type"=>"Links",
							  "options" => $this->service->option(),
							  "description" => __("Add one or more service.",'Pixelentity Theme/Plugin'),
							  "sortable" => true,
							  "default"=> array()
							  ),
						)
				  );

		$clientsMbox = 
			array(
				  "type" =>"",
				  "title" =>__("Testimonials",'Pixelentity Theme/Plugin'),
				  "priority" => "core",
				  "where" => 
				  array(
						"page" => "page_testimonials",
						),
				  "content"=>
				  array(
						"members" => 
						array(
							  "label"=>__("Testimonials",'Pixelentity Theme/Plugin'),
							  "type"=>"Links",
							  "options" => $this->testimonial->option(),
							  "description" => __("Add one or more testimonial.",'Pixelentity Theme/Plugin'),
							  "sortable" => true,
							  "default"=> array()
							  ),
						)
				  );

		$pTables = 
			array(
				  "title" => __("Pricing tables",'Pixelentity Theme/Plugin'),
				  "priority" => "core",
				  "where" =>
				  array(
						"post" => "page_ptables"
						),
				  "content" =>
				  array(
						"ptables" => 
						array(
							  "label"=>__("Pricing Tables",'Pixelentity Theme/Plugin'),
							  "type"=>"Links",
							  "options" => $this->ptable->option(),
							  "description" => __("Add one or more pricing tables.",'Pixelentity Theme/Plugin'),
							  "sortable" => true,
							  "default"=> array()
							  ),				
						)
				  );

		$blog = PeGlobal::$const->blog->metabox;

		$portfolioMbox =& PeGlobal::$const->portfolio->metabox;
		unset($portfolioMbox["content"]["pager"]);
		unset($portfolioMbox["content"]["filterable"]);
		unset($portfolioMbox["content"]["columns"]);

		$portfolioMbox["where"]["page"] = "page_portfolio";

		unset( $blog['content']['layout'] );
		unset( $blog['content']['media'] );
		//unset( $blog['content']['sticky'] );

		PeGlobal::$config["metaboxes-page"] = 
			array(
				"pagebg" => $background,
				"page_icon" => $pageIcon,
				"bg" => $bgMbox,
				"sidebar" => array_merge(PeGlobal::$const->sidebar->metabox,array("where"=>array("post"=>"page_blog"))),
				"blog" => array_merge($blog,array("where"=>array("post"=>"page_blog"))),
				"portfolio" => $portfolioMbox,
				"clients" => $clientsMbox,
				"about" => $about,
				"services" => $serviceMbox,
				"ptables" => $pTables,
			);
	}

	public function pe_theme_metabox_config_project() {
		parent::pe_theme_metabox_config_project();

		$galleryMbox = 
			array(
				  "title" => __("Slider",'Pixelentity Theme/Plugin'),
				  "type" => "GalleryPost",
				  "priority" => "core",
				  "where" =>
				  array(
						"post" => "gallery"
						),
				  "content" =>
				  array(
						"id" => PeGlobal::$const->gallery->id,
				  )
				);

		PeGlobal::$config["metaboxes-project"] = 
			array(
				  "gallery" => $galleryMbox,
				  "video" => PeGlobal::$const->video->metaboxPost
				  );

	}

	public function pe_theme_haven_testimonial_supports() {

		add_post_type_support( 'testimonial', 'thumbnail' );

	}

	public function pe_theme_haven_metabox_config_project() {

		PeGlobal::$config["metaboxes-project"]["ajax"] = 
			array(
				"title" => __("Ajax",'Pixelentity Theme/Plugin'),
				"type" => "GalleryPost",
				"priority" => "core",
				"where" =>
				array(
					"post" => "gallery, video, standard"
				),
				"content" =>
					array(
						"ajax" =>				
						array(
							  "label"=>__("Ajax-load",'Pixelentity Theme/Plugin'),
							  "type"=>"RadioUI",
							  "description"=>__('Should this project be ajax-loaded when clicked on in portfolio.','Pixelentity Theme/Plugin'),
							  "options" => Array(__("Yes",'Pixelentity Theme/Plugin')=>"yes",__("No",'Pixelentity Theme/Plugin')=>"no"),
							  "default"=>"yes"
							  ),
					)
			);

		unset( PeGlobal::$config["metaboxes-project"]['portfolio'] );
		unset( PeGlobal::$config["metaboxes-project"]['info'] );

	}

	public function pe_theme_metabox_config_staff_action() {

		PeGlobal::$config["metaboxes-staff"]["info"]["content"]["social"] = array(
			"label"=>__("Social Profile Links",'Pixelentity Theme/Plugin'),
			"type"=>"Items",
			"description" => __("Add one or more links to social networks.",'Pixelentity Theme/Plugin'),
			"button_label" => __("Add Social Link",'Pixelentity Theme/Plugin'),
			"sortable" => true,
			"auto" => __("Layer",'Pixelentity Theme/Plugin'),
			"unique" => false,
			"editable" => false,
			"legend" => false,
			"fields" => 
			array(
				array(
					"label" => __("Social Network",'Pixelentity Theme/Plugin'),
					"name" => "icon",
					"type" => "select",
					"options" => apply_filters('pe_theme_social_icons',array()),
					"width" => 185,
					"default" => ""
				),
				array(
					"name" => "url",
					"type" => "text",
					"width" => 300, 
					"default" => "#"
				)
			),
			"default" => ""
		);

	}

	public function pe_theme_metabox_config_service_action() {

		unset( PeGlobal::$config["metaboxes-service"]['info']['content']['features'] );

		PeGlobal::$config["metaboxes-service"]['info']['content']['icon'] = array(
			"label"=>__("Service icon",'Pixelentity Theme/Plugin'),
			"type"=>"Select",
			"options" => pe_theme_typicons_icons(),
			"description"=>__('Select icon that will be assigned to this service. For icons overview, Google "Typicons".','Pixelentity Theme/Plugin'),
			"default"=>"hide"
		);

		PeGlobal::$config["metaboxes-service"]['info']['content']['icon_position'] = array(
			"label"=>__("Icon position",'Pixelentity Theme/Plugin'),
			"type"=>"RadioUI",
			"description"=>__('Choose between icon placed at the top or at the bottom','Pixelentity Theme/Plugin'),
			"options" => Array(__("Top",'Pixelentity Theme/Plugin')=>"top",__("Bottom",'Pixelentity Theme/Plugin')=>"bottom"),
			"default"=>"top"
		);

	}

	public function pe_theme_gallery_image_fields_filter( $fields ) {

		unset( $fields['video'] );

		$link = $fields['link'];
		$save = $fields['save'];

		unset( $fields['link'] );
		unset( $fields['save'] );

		$fields['button'] = array(
			"label"=>__("Button text",'Pixelentity Theme/Plugin'),
			"type"=>"Text",
			"section"=>"main",
			"description" => __("Text of the button",'Pixelentity Theme/Plugin'),
			"default"=> ""
		);

		$fields['link'] = $link;
		$fields['save'] = $save;

		$fields['link']['label'] = __("Button link",'Pixelentity Theme/Plugin');
		$fields['link']['description'] = __("Link button will point to",'Pixelentity Theme/Plugin');

		return $fields;

	}

	protected function init_asset() {
		return new PeThemeHavenAsset($this);
	}

	protected function init_template() {
		return new PeThemeHavenTemplate($this);
	}

}


function pe_theme_typicons_icons() {

	$typicons = array(
		__("No icon",'Pixelentity Theme/Plugin') => "hide",
		"adjust-brightness" => "typcn typcn-adjust-brightness",
		"adjust-contrast" => "typcn typcn-adjust-contrast",
		"anchor-outline" => "typcn typcn-anchor-outline",
		"anchor" => "typcn typcn-anchor",
		"archive" => "typcn typcn-archive",
		"arrow-back-outline" => "typcn typcn-arrow-back-outline",
		"arrow-back" => "typcn typcn-arrow-back",
		"arrow-down-outline" => "typcn typcn-arrow-down-outline",
		"arrow-down-thick" => "typcn typcn-arrow-down-thick",
		"arrow-down" => "typcn typcn-arrow-down",
		"arrow-forward-outline" => "typcn typcn-arrow-forward-outline",
		"arrow-forward" => "typcn typcn-arrow-forward",
		"arrow-left-outline" => "typcn typcn-arrow-left-outline",
		"arrow-left-thick" => "typcn typcn-arrow-left-thick",
		"arrow-left" => "typcn typcn-arrow-left",
		"arrow-loop-outline" => "typcn typcn-arrow-loop-outline",
		"arrow-loop" => "typcn typcn-arrow-loop",
		"arrow-maximise-outline" => "typcn typcn-arrow-maximise-outline",
		"arrow-maximise" => "typcn typcn-arrow-maximise",
		"arrow-minimise-outline" => "typcn typcn-arrow-minimise-outline",
		"arrow-minimise" => "typcn typcn-arrow-minimise",
		"arrow-move-outline" => "typcn typcn-arrow-move-outline",
		"arrow-move" => "typcn typcn-arrow-move",
		"arrow-repeat-outline" => "typcn typcn-arrow-repeat-outline",
		"arrow-repeat" => "typcn typcn-arrow-repeat",
		"arrow-right-outline" => "typcn typcn-arrow-right-outline",
		"arrow-right-thick" => "typcn typcn-arrow-right-thick",
		"arrow-right" => "typcn typcn-arrow-right",
		"arrow-shuffle" => "typcn typcn-arrow-shuffle",
		"arrow-sync-outline" => "typcn typcn-arrow-sync-outline",
		"arrow-sync" => "typcn typcn-arrow-sync",
		"arrow-up-outline" => "typcn typcn-arrow-up-outline",
		"arrow-up-thick" => "typcn typcn-arrow-up-thick",
		"arrow-up" => "typcn typcn-arrow-up",
		"at" => "typcn typcn-at",
		"attachment-outline" => "typcn typcn-attachment-outline",
		"attachment" => "typcn typcn-attachment",
		"backspace-outline" => "typcn typcn-backspace-outline",
		"backspace" => "typcn typcn-backspace",
		"battery-charge" => "typcn typcn-battery-charge",
		"battery-full" => "typcn typcn-battery-full",
		"battery-high" => "typcn typcn-battery-high",
		"battery-low" => "typcn typcn-battery-low",
		"battery-mid" => "typcn typcn-battery-mid",
		"beaker" => "typcn typcn-beaker",
		"beer" => "typcn typcn-beer",
		"bell" => "typcn typcn-bell",
		"book" => "typcn typcn-book",
		"bookmark" => "typcn typcn-bookmark",
		"briefcase" => "typcn typcn-briefcase",
		"brush" => "typcn typcn-brush",
		"business-card" => "typcn typcn-business-card",
		"calculator" => "typcn typcn-calculator",
		"calender-outline" => "typcn typcn-calender-outline",
		"calender" => "typcn typcn-calender",
		"camera-outline" => "typcn typcn-camera-outline",
		"camera" => "typcn typcn-camera",
		"cancel-outline" => "typcn typcn-cancel-outline",
		"cancel" => "typcn typcn-cancel",
		"chart-area-outline" => "typcn typcn-chart-area-outline",
		"chart-area" => "typcn typcn-chart-area",
		"chart-bar-outline" => "typcn typcn-chart-bar-outline",
		"chart-bar" => "typcn typcn-chart-bar",
		"chart-line-outline" => "typcn typcn-chart-line-outline",
		"chart-line" => "typcn typcn-chart-line",
		"chart-pie-outline" => "typcn typcn-chart-pie-outline",
		"chart-pie" => "typcn typcn-chart-pie",
		"chevron-left-outline" => "typcn typcn-chevron-left-outline",
		"chevron-left" => "typcn typcn-chevron-left",
		"chevron-right-outline" => "typcn typcn-chevron-right-outline",
		"chevron-right" => "typcn typcn-chevron-right",
		"clipboard" => "typcn typcn-clipboard",
		"cloud-storage" => "typcn typcn-cloud-storage",
		"code-outline" => "typcn typcn-code-outline",
		"code" => "typcn typcn-code",
		"coffee" => "typcn typcn-coffee",
		"cog-outline" => "typcn typcn-cog-outline",
		"cog" => "typcn typcn-cog",
		"compass" => "typcn typcn-compass",
		"contacts" => "typcn typcn-contacts",
		"credit-card" => "typcn typcn-credit-card",
		"cross" => "typcn typcn-cross",
		"database" => "typcn typcn-database",
		"delete-outline" => "typcn typcn-delete-outline",
		"delete" => "typcn typcn-delete",
		"device-desktop" => "typcn typcn-device-desktop",
		"device-laptop" => "typcn typcn-device-laptop",
		"device-phone" => "typcn typcn-device-phone",
		"device-tablet" => "typcn typcn-device-tablet",
		"directions" => "typcn typcn-directions",
		"divide-outline" => "typcn typcn-divide-outline",
		"divide" => "typcn typcn-divide",
		"document-add" => "typcn typcn-document-add",
		"document-delete" => "typcn typcn-document-delete",
		"document-text" => "typcn typcn-document-text",
		"document" => "typcn typcn-document",
		"download-outline" => "typcn typcn-download-outline",
		"download" => "typcn typcn-download",
		"edit" => "typcn typcn-edit",
		"eject-outline" => "typcn typcn-eject-outline",
		"eject" => "typcn typcn-eject",
		"equals-outline" => "typcn typcn-equals-outline",
		"equals" => "typcn typcn-equals",
		"export-outline" => "typcn typcn-export-outline",
		"export" => "typcn typcn-export",
		"eye-outline" => "typcn typcn-eye-outline",
		"eye" => "typcn typcn-eye",
		"feather" => "typcn typcn-feather",
		"film" => "typcn typcn-film",
		"flag-outline" => "typcn typcn-flag-outline",
		"flag" => "typcn typcn-flag",
		"flash-outline" => "typcn typcn-flash-outline",
		"flash" => "typcn typcn-flash",
		"flow-children" => "typcn typcn-flow-children",
		"flow-merge" => "typcn typcn-flow-merge",
		"flow-parallel" => "typcn typcn-flow-parallel",
		"flow-switch" => "typcn typcn-flow-switch",
		"folder-add" => "typcn typcn-folder-add",
		"folder-delete" => "typcn typcn-folder-delete",
		"folder" => "typcn typcn-folder",
		"gift" => "typcn typcn-gift",
		"globe-outline" => "typcn typcn-globe-outline",
		"globe" => "typcn typcn-globe",
		"group-outline" => "typcn typcn-group-outline",
		"group" => "typcn typcn-group",
		"headphones" => "typcn typcn-headphones",
		"heart-outline" => "typcn typcn-heart-outline",
		"heart" => "typcn typcn-heart",
		"home-outline" => "typcn typcn-home-outline",
		"home" => "typcn typcn-home",
		"image-outline" => "typcn typcn-image-outline",
		"image" => "typcn typcn-image",
		"infinity-outline" => "typcn typcn-infinity-outline",
		"infinity" => "typcn typcn-infinity",
		"info-large-outline" => "typcn typcn-info-large-outline",
		"info-large" => "typcn typcn-info-large",
		"info-outline" => "typcn typcn-info-outline",
		"info" => "typcn typcn-info",
		"input-checked-outline" => "typcn typcn-input-checked-outline",
		"input-checked" => "typcn typcn-input-checked",
		"key-outline" => "typcn typcn-key-outline",
		"key" => "typcn typcn-key",
		"leaf" => "typcn typcn-leaf",
		"lightbulb" => "typcn typcn-lightbulb",
		"link-outline" => "typcn typcn-link-outline",
		"link" => "typcn typcn-link",
		"location-arrow-outline" => "typcn typcn-location-arrow-outline",
		"location-arrow" => "typcn typcn-location-arrow",
		"location-outline" => "typcn typcn-location-outline",
		"location" => "typcn typcn-location",
		"lock-closed-outline" => "typcn typcn-lock-closed-outline",
		"lock-closed" => "typcn typcn-lock-closed",
		"lock-open-outline" => "typcn typcn-lock-open-outline",
		"lock-open" => "typcn typcn-lock-open",
		"mail" => "typcn typcn-mail",
		"map" => "typcn typcn-map",
		"media-eject-outline" => "typcn typcn-media-eject-outline",
		"media-eject" => "typcn typcn-media-eject",
		"media-fast-forward-outline" => "typcn typcn-media-fast-forward-outline",
		"media-fast-forward" => "typcn typcn-media-fast-forward",
		"media-pause-outline" => "typcn typcn-media-pause-outline",
		"media-pause" => "typcn typcn-media-pause",
		"media-play-outline" => "typcn typcn-media-play-outline",
		"media-play" => "typcn typcn-media-play",
		"media-record-outline" => "typcn typcn-media-record-outline",
		"media-record" => "typcn typcn-media-record",
		"media-rewind-outline" => "typcn typcn-media-rewind-outline",
		"media-rewind" => "typcn typcn-media-rewind",
		"media-stop-outline" => "typcn typcn-media-stop-outline",
		"media-stop" => "typcn typcn-media-stop",
		"message-typing" => "typcn typcn-message-typing",
		"message" => "typcn typcn-message",
		"messages" => "typcn typcn-messages",
		"microphone-outline" => "typcn typcn-microphone-outline",
		"microphone" => "typcn typcn-microphone",
		"minus-outline" => "typcn typcn-minus-outline",
		"minus" => "typcn typcn-minus",
		"news" => "typcn typcn-news",
		"notes-outline" => "typcn typcn-notes-outline",
		"notes" => "typcn typcn-notes",
		"pen" => "typcn typcn-pen",
		"pencil" => "typcn typcn-pencil",
		"phone-outline" => "typcn typcn-phone-outline",
		"phone" => "typcn typcn-phone",
		"pi-outline" => "typcn typcn-pi-outline",
		"pi" => "typcn typcn-pi",
		"pin-outline" => "typcn typcn-pin-outline",
		"pin" => "typcn typcn-pin",
		"pipette" => "typcn typcn-pipette",
		"plane-outline" => "typcn typcn-plane-outline",
		"plane" => "typcn typcn-plane",
		"plug" => "typcn typcn-plug",
		"plus-outline" => "typcn typcn-plus-outline",
		"plus" => "typcn typcn-plus",
		"point-of-interest-outline" => "typcn typcn-point-of-interest-outline",
		"point-of-interest" => "typcn typcn-point-of-interest",
		"power-outline" => "typcn typcn-power-outline",
		"power" => "typcn typcn-power",
		"printer" => "typcn typcn-printer",
		"puzzle-outline" => "typcn typcn-puzzle-outline",
		"puzzle" => "typcn typcn-puzzle",
		"radar-outline" => "typcn typcn-radar-outline",
		"radar" => "typcn typcn-radar",
		"refresh-outline" => "typcn typcn-refresh-outline",
		"refresh" => "typcn typcn-refresh",
		"rss-outline" => "typcn typcn-rss-outline",
		"rss" => "typcn typcn-rss",
		"scissors-outline" => "typcn typcn-scissors-outline",
		"scissors" => "typcn typcn-scissors",
		"shopping-bag" => "typcn typcn-shopping-bag",
		"shopping-cart" => "typcn typcn-shopping-cart",
		"social-at-circular" => "typcn typcn-social-at-circular",
		"social-dribbble-circular" => "typcn typcn-social-dribbble-circular",
		"social-dribbble" => "typcn typcn-social-dribbble",
		"social-facebook-circular" => "typcn typcn-social-facebook-circular",
		"social-facebook" => "typcn typcn-social-facebook",
		"social-flickr-circular" => "typcn typcn-social-flickr-circular",
		"social-flickr" => "typcn typcn-social-flickr",
		"social-github-circular" => "typcn typcn-social-github-circular",
		"social-github" => "typcn typcn-social-github",
		"social-instagram-circular" => "typcn typcn-social-instagram-circular",
		"social-instagram" => "typcn typcn-social-instagram",
		"social-last-fm-circular" => "typcn typcn-social-last-fm-circular",
		"social-last-fm" => "typcn typcn-social-last-fm",
		"social-linkedin-circular" => "typcn typcn-social-linkedin-circular",
		"social-linkedin" => "typcn typcn-social-linkedin",
		"social-pinterest-circular" => "typcn typcn-social-pinterest-circular",
		"social-pinterest" => "typcn typcn-social-pinterest",
		"social-skype-outline" => "typcn typcn-social-skype-outline",
		"social-skype" => "typcn typcn-social-skype",
		"social-tumbler-circular" => "typcn typcn-social-tumbler-circular",
		"social-tumbler" => "typcn typcn-social-tumbler",
		"social-twitter-circular" => "typcn typcn-social-twitter-circular",
		"social-twitter" => "typcn typcn-social-twitter",
		"social-vimeo-circular" => "typcn typcn-social-vimeo-circular",
		"social-vimeo" => "typcn typcn-social-vimeo",
		"sort-alphabetically-outline" => "typcn typcn-sort-alphabetically-outline",
		"sort-alphabetically" => "typcn typcn-sort-alphabetically",
		"sort-numerically-outline" => "typcn typcn-sort-numerically-outline",
		"sort-numerically" => "typcn typcn-sort-numerically",
		"spanner-outline" => "typcn typcn-spanner-outline",
		"spanner" => "typcn typcn-spanner",
		"star-outline" => "typcn typcn-star-outline",
		"star" => "typcn typcn-star",
		"starburst-outline" => "typcn typcn-starburst-outline",
		"starburst" => "typcn typcn-starburst",
		"stopwatch" => "typcn typcn-stopwatch",
		"support" => "typcn typcn-support",
		"tabs-outline" => "typcn typcn-tabs-outline",
		"tag" => "typcn typcn-tag",
		"tags" => "typcn typcn-tags",
		"th-large-outline" => "typcn typcn-th-large-outline",
		"th-large" => "typcn typcn-th-large",
		"th-list-outline" => "typcn typcn-th-list-outline",
		"th-list" => "typcn typcn-th-list",
		"th-menu-outline" => "typcn typcn-th-menu-outline",
		"th-menu" => "typcn typcn-th-menu",
		"th-small-outline" => "typcn typcn-th-small-outline",
		"th-small" => "typcn typcn-th-small",
		"thermometer" => "typcn typcn-thermometer",
		"thumbs-down" => "typcn typcn-thumbs-down",
		"thumbs-up" => "typcn typcn-thumbs-up",
		"tick-outline" => "typcn typcn-tick-outline",
		"tick" => "typcn typcn-tick",
		"ticket" => "typcn typcn-ticket",
		"time" => "typcn typcn-time",
		"times-outline" => "typcn typcn-times-outline",
		"times" => "typcn typcn-times",
		"trash" => "typcn typcn-trash",
		"tree" => "typcn typcn-tree",
		"upload-outline" => "typcn typcn-upload-outline",
		"upload" => "typcn typcn-upload",
		"user-add-outline" => "typcn typcn-user-add-outline",
		"user-add" => "typcn typcn-user-add",
		"user-delete-outline" => "typcn typcn-user-delete-outline",
		"user-delete" => "typcn typcn-user-delete",
		"user-outline" => "typcn typcn-user-outline",
		"user" => "typcn typcn-user",
		"video-outline" => "typcn typcn-video-outline",
		"video" => "typcn typcn-video",
		"volume-down" => "typcn typcn-volume-down",
		"volume-mute" => "typcn typcn-volume-mute",
		"volume-up" => "typcn typcn-volume-up",
		"volume" => "typcn typcn-volume",
		"warning-outline" => "typcn typcn-warning-outline",
		"warning" => "typcn typcn-warning",
		"watch" => "typcn typcn-watch",
		"waves-outline" => "typcn typcn-waves-outline",
		"waves" => "typcn typcn-waves",
		"weather-cloudy" => "typcn typcn-weather-cloudy",
		"weather-downpour" => "typcn typcn-weather-downpour",
		"weather-night" => "typcn typcn-weather-night",
		"weather-partly-sunny" => "typcn typcn-weather-partly-sunny",
		"weather-shower" => "typcn typcn-weather-shower",
		"weather-snow" => "typcn typcn-weather-snow",
		"weather-stormy" => "typcn typcn-weather-stormy",
		"weather-sunny" => "typcn typcn-weather-sunny",
		"weather-windy-cloudy" => "typcn typcn-weather-windy-cloudy",
		"weather-windy" => "typcn typcn-weather-windy",
		"wi-fi-outline" => "typcn typcn-wi-fi-outline",
		"wi-fi" => "typcn typcn-wi-fi",
		"wine" => "typcn typcn-wine",
		"world-outline" => "typcn typcn-world-outline",
		"world" => "typcn typcn-world",
		"zoom-in-outline" => "typcn typcn-zoom-in-outline",
		"zoom-in" => "typcn typcn-zoom-in",
		"zoom-out-outline" => "typcn typcn-zoom-out-outline",
		"zoom-out" => "typcn typcn-zoom-out",
		"zoom-outline" => "typcn typcn-zoom-outline",
		"zoom" => "typcn typcn-zoom",
	);

	return $typicons;

}

?>