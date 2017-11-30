<?php

class PeThemeMBox {

	protected $master;
	protected $metaboxes;
	protected $revision = false;

	public function __construct(&$master) {
		$this->master =& $master;
	}

	public function add_meta_boxes($page,$object) {
		if (!isset($object->ID)) return;

		$values = get_post_meta($object->ID,PE_THEME_META,true);
		$metaboxes = $this->master->getMetaboxConfig($page);

		$count = 0;
		foreach ($metaboxes as $name => $data) {
			//if (in_array($page,explode(",",$data["where"]))) {
			if (isset($data["where"][$page])) {
				$type = isset($data["type"]) ? $data["type"] : "";
				$metaboxClass = "PeThemeMetaBox{$type}";
				if (isset($values) && isset($values->$name)) {
					$data["value"] = $values->$name;
				}

				$metabox = new $metaboxClass($this,$name,$data); 
				$this->metaboxes[] = $metabox;
				add_meta_box("pe_theme_meta_$name",$data['title'],array($metabox,"render"),$page,isset($data["context"]) ? $data["context"] : "normal",isset($data["priority"]) ? $data["priority"] : "default");
				$count++;
			}
		}
		if ($count > 0) {
			add_action("dbx_post_sidebar",array(&$this,"dbx_post_sidebar"));
		}

	}

	public function dbx_post_sidebar() {
		wp_nonce_field('pe_theme_meta','pe_theme_meta_nonce');
	}

	public function wp_insert_post_data_filter($data,$postarr) {
		
		if (!empty($postarr['ID']) && !empty($postarr["page_template"]) && $postarr["page_template"] === "page_builder.php" && isset($postarr[PE_THEME_META]['builder'])) {

			try {
				$view = new PeThemeViewLayout();
				$conf = (object) 
					array(
						  "id" => $postarr['ID'],
						  "settings" => (object) $postarr[PE_THEME_META]['builder']
						  );

				ob_start();
				$view->output($conf);
				$content = ob_get_clean();
				$data["post_content"] = $content;
			} catch (Exception $e) {
			}			
		}

		return $data;
	}

	public function getPOSTvalues() {
		// this is needed to convert window-style line feeds to unix format, without doing so
		// all serialized values will breaks once exported into xml file
		array_walk_recursive($_POST[PE_THEME_META],array("PeThemeUtils","dos2unix"));

		$mboxes = array();
		$values = new stdClass(); 
		foreach ($_POST["pe_theme_meta"] as $mbox=>$data) {
			$mboxes[] = $mbox;
			$values->$mbox = new stdClass();
			foreach($data as $key=>$value) {
				$values->$mbox->$key = $value;
			}
		}
		
		$values = apply_filters("pe_theme_update_metadata",$values,$id,$post);

		if (!PE_THEME_PLUGIN) {
			$oldvalues = maybe_unserialize(get_post_meta($id,PE_THEME_META,true));
			if (is_object($oldvalues)) {
				// here's the thing: there are previously saved pe meta but since the plugin is not active
				// we can't just overwrite them with new values or else some content may be lost (for instance, content builder)
				// so instead of reaplcing the whole object, we just replace the mboxes found in $_POST
				foreach ($mboxes as $mbox) {
					$oldvalues->$mbox = $values->$mbox;
				}
				$values = $oldvalues;
			}
		}
		
		return $values;

	}


	public function save_post($id,$post) {
		//if (!PE_THEME_PLUGIN || !isset($_POST) || (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) || !isset($_POST["pe_theme_meta_nonce"]) ) {
		if (!isset($_POST) || (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) || !isset($_POST["pe_theme_meta_nonce"]) ) {
			return;
		}

		if (is_object($post) && !empty($post->post_type) && $post->post_type == "nav_menu_item") {
			// do not set meta when a nav post item is autocreated for the page (which happens when menu -> auto add pages is active)
			return;
		}

		if (!wp_verify_nonce($_POST['pe_theme_meta_nonce'],PE_THEME_META) || wp_is_post_revision($id)) {
			return;
		}

		// this is needed to convert window-style line feeds to unix format, without doing so
		// all serialized values will breaks once exported into xml file
		array_walk_recursive($_POST[PE_THEME_META],array("PeThemeUtils","dos2unix"));

		$mboxes = array();
		$values = new stdClass(); 
		foreach ($_POST["pe_theme_meta"] as $mbox=>$data) {
			$mboxes[] = $mbox;
			$values->$mbox = new stdClass();
			foreach($data as $key=>$value) {
				$values->$mbox->$key = $value;
			}
		}
		
		$values = apply_filters("pe_theme_update_metadata",$values,$id,$post);

		if (!PE_THEME_PLUGIN) {
			$oldvalues = maybe_unserialize(get_post_meta($id,PE_THEME_META,true));
			if (is_object($oldvalues)) {
				// here's the thing: there are previously saved pe meta but since the plugin is not active
				// we can't just overwrite them with new values or else some content may be lost (for instance, content builder)
				// so instead of reaplcing the whole object, we just replace the mboxes found in $_POST
				foreach ($mboxes as $mbox) {
					$oldvalues->$mbox = $values->$mbox;
				}
				$values = $oldvalues;
			}
		}

		update_post_meta($id,PE_THEME_META,$values);
		do_action("pe_theme_post_update_metadata",$values,$id,$post);
	}

	public function edit_attachment($id) {

		if (!isset($_POST['pe_theme_meta']) || !isset($_POST['pe_theme_meta_nonce']) || !wp_verify_nonce($_POST['pe_theme_meta_nonce'],'pe_theme_meta')) {
			return;
		}

		// this is needed to convert window-style line feeds to unix format, without doing so
		// all serialized values will breaks once exported into xml file
		array_walk_recursive($_POST["pe_theme_meta"],array("PeThemeUtils","dos2unix"));

		$values = new stdClass(); 
		foreach ($_POST["pe_theme_meta"] as $box=>$data) {
			$values->$box = new stdClass();
			foreach($data as $key=>$value) {
				$values->$box->$key = $value;
			}
		}

		global $post;

		update_post_meta($id,PE_THEME_META,apply_filters("pe_theme_update_attachment_metadata",$values,$id,$post));
		do_action("pe_theme_post_update_attachment_metadata",$values,$id,$post);
	}

	public function save_post_revision($revision) {
		// save the revision id
		$this->revision = $revision;
		// update its meta later, after the post has been updated 
		add_action("pe_theme_post_update_metadata",array($this,"revision_metadata"),10,3);
	}

	public function revision_metadata($values,$id,$post) {
		if ($this->revision && $id == wp_is_post_revision($this->revision) && !empty($values)) {
			if (function_exists("update_metadata")) {
				// save revision meta (ours)
				update_metadata('post', $this->revision, PE_THEME_META, $values);
			}
		}
	}

	public function wp_restore_post_revision($id,$rid) {
		if (empty($id) || empty($rid)) {
			return;
		}
		
		$rmeta = maybe_unserialize(get_post_meta($rid,PE_THEME_META,true));

		if (!empty($rmeta) && (is_array($rmeta) || is_object($rmeta))) {
			// restore meta from revision
			update_post_meta($id,PE_THEME_META,$rmeta);
			if ($this->revision) {
				// WP created a new revision so set its meta as well
				update_metadata('post', $this->revision, PE_THEME_META, $rmeta);
			}
		}
	}

	public function wp_save_post_revision_check_for_changes($value,$rev,$post) {

		$screen = get_current_screen();
		if (!empty($screen) && $screen->base === "revision" && !empty($_REQUEST['action'] ) && $_REQUEST['action'] === "restore") {
			// force new revision to be created when restoring an old one
			return false;
		}

		if (empty($rev->ID) || empty($_POST[PE_THEME_META])) {
			return $value;
		}

		// get meta saved in last revision
		$rmeta = maybe_unserialize(get_post_meta($rev->ID,PE_THEME_META,true));

		// this is needed to convert window-style line feeds to unix format, without doing so
		// all serialized values will breaks once exported into xml file
		array_walk_recursive($_POST[PE_THEME_META],array("PeThemeUtils","dos2unix"));

		// get new values
		$values = new stdClass(); 
		foreach ($_POST[PE_THEME_META] as $box=>$data) {
			$values->$box = new stdClass();
			foreach($data as $key=>$value) {
				$values->$box->$key = $value;
			}
		}

		// get prev/current builder content
		$oldbuilder = isset($rmeta->builder) ? serialize($rmeta->builder) : false;
		//$newbuilder = isset($values->builder) ? serialize(wp_unslash($values->builder)) : false;
		$newbuilder = isset($values->builder) ? $values->builder : false;

		if ($newbuilder) {
			if (function_exists('wp_unslash')) {
				$newbuilder = wp_unslash($newbuilder);
			}
			$newbuilder = serialize($newbuilder);
		}

		if (($oldbuilder || $newbuilder) && ($newbuilder != $oldbuilder)) {
			// force new revision
			return false;
		}

		return $value;
	}

	public function builderLayout($items,$level = 1) {
		static $cache;

		empty ( $layout ) && $layout = '';

		if (is_array($items)) {
			foreach ($items as $item) {
				if (!empty($item["type"])) {
					$type = $item["type"];
					$text = $type === 'Text';
					if (isset($cache[$type])) {
						$type = $cache[$type];
					} else {
						$mclass = "PeThemeViewLayoutModule$type";
						if (class_exists($mclass)) {
							$type = new $mclass();
							$type = $type->name();
							$cache[$type] = $type;
						} 
					}
					$layout .= sprintf('%s [%s]',str_repeat('-',$level),$type);
					
					if ($text) {
						$layout .= "\n" . $item["data"]["content"];
					}

					if (!empty($item["data"]["title"])) {
						$layout .= sprintf(' %s',$item["data"]["title"]);
					} elseif ($item["type"] === 'View' && !empty($item["data"]["id"])) {
						$view = get_post($item["data"]["id"]);
						if (!empty($view->post_title)) {
							$layout .= sprintf(' %s',$view->post_title);
						}
					}					
				}
				$layout .= "\n";
				if (!empty($item["items"])) {
					$layout .= $this->builderLayout($item["items"],$level+1);
				}
			}
		}

		return $layout;
	}


	public function ajax_builder_save_revision() {
		if (empty($_POST['post_ID']) || !isset($_POST[PE_THEME_META]['builder']['builder'])) die();
		check_admin_referer('update-post_' . $_POST['post_ID']);

		$rev = wp_save_post_revision($_POST['post_ID']);

		if ($rev) {
			update_metadata('post', $rev, PE_THEME_META,$this->getPOSTvalues());
			// we need to update revision date since orig post has not been modified
			wp_update_post(
						   array(
								 'ID' => $rev,
								 'post_modified' => current_time('mysql'),
								 'post_modified_gmt' => current_time('mysql',1)
								 )
						   );
		}
		
		die();
	}

	public function _wp_post_revision_field_post_content($value, $field, $compare_to,$direction) {
		if (is_object($compare_to) && !empty($compare_to->ID)) {
			$meta = maybe_unserialize(get_post_meta($compare_to->ID,PE_THEME_META,true));
			$bvalue = "";
			if (!empty($meta->builder->builder["items"])) {
				$layout = $this->builderLayout($meta->builder->builder["items"]);
				$bvalue = sprintf("%s\n%s",__('Page Builder Content','Pixelentity Theme/Plugin'),$layout);
			} else if ( ! empty( $meta->{'settings-Layout'}->builder["items"] ) ) { // Append view builder layout to revisions browsing screen

				$layout = $this->builderLayout( $meta->{'settings-Layout'}->builder["items"] );
				$bvalue = sprintf("%s\n%s", __( 'Layout Builder Content' ,'Pixelentity Theme/Plugin'), $layout );

			}
			if ($bvalue) {
				$value = sprintf("%s\n\nEditor\n%s",$bvalue,$value);
			}
		}

		return $value;
	}

	


}

?>