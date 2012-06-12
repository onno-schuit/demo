<?php

class theme_thnklink_core_renderer extends core_renderer {
	protected function render_custom_menu(custom_menu $menu) {
		// If the menu has no children return an empty string
		if (!$menu->has_children()) {
			return '';
		}
		// Initialise this custom menu
		$content = html_writer::start_tag('ul', array('class'=>'nav nav-pills'));
		// Render each child
		foreach ($menu->get_children() as $item) {
			$content .= $this->render_custom_menu_item($item);
		}
		// Close the open tags
		$content .= html_writer::end_tag('ul');
		// Return the custom menu
		return $content;
	}

	protected function render_custom_menu_item(custom_menu_item $menunode) {
		// Required to ensure we get unique trackable id's
		static $submenucount = 0;
		
		if ($menunode->has_children()) {
		    $content = html_writer::start_tag('li', array('class'=>'dropdown'));
			// If the child has menus render it as a sub menu
			$submenucount++;
			if ($menunode->get_url() !== null) {
				$url = $menunode->get_url();
			} else {
				$url = '#cm_submenu_'.$submenucount;
			}
			
			//$content .= html_writer::link($url, $menunode->get_text(), array('title'=>,));
			$content .= html_writer::start_tag('a', array('href'=>$url,'class'=>'dropdown-toggle','data-toggle'=>'dropdown'));
			$content .= $menunode->get_title();
			$content .= html_writer::start_tag('b', array('class'=>'caret'));
			$content .= html_writer::end_tag('b');
			$content .= html_writer::end_tag('a');
			$content .= html_writer::start_tag('ul', array('class'=>'dropdown-menu'));
			foreach ($menunode->get_children() as $menunode) {
				$content .= $this->render_custom_menu_item($menunode);
			}
			$content .= html_writer::end_tag('ul');
		} else {
		    $content = html_writer::start_tag('li');
			// The node doesn't have children so produce a final menuitem

			if ($menunode->get_url() !== null) {
				$url = $menunode->get_url();
			} else {
				$url = '#';
			}
			$content .= html_writer::link($url, $menunode->get_text(), array('title'=>$menunode->get_title()));
		}
		$content .= html_writer::end_tag('li');
		// Return the sub menu
		return $content;
	}
	
    public function navbar() {
    	return '';
        $items = $this->page->navbar->get_items();

        $htmlblocks = array();
        // Iterate the navarray and display each node
        $itemcount = count($items);
        $separator = get_separator();
        for ($i=0;$i < $itemcount;$i++) {
            $item = $items[$i];
            $item->hideicon = true;
            if ($i===0) {
                $content = html_writer::tag('li', $this->render($item));
            } else {
            	if ($i != 1) {
                $content = html_writer::tag('li', $separator.$this->render($item));
            	} else {
            		$content = '';
            	}
            }
            $htmlblocks[] = $content;
        }

        //accessibility: heading for navbar list  (MDL-20446)
        $navbarcontent = html_writer::tag('span', get_string('pagepath'), array('class'=>'accesshide'));
        $navbarcontent .= html_writer::tag('ul', join('', $htmlblocks));
        // XHTML
        return $navbarcontent;
    }
	
}
