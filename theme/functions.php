<?php
/**
 * Theme related functions. 
 *
 */

/**
 * Get title for the webpage by concatenating page specific title with site-wide title.
 *
 * @param string $title for this page.
 * @return string/null wether the favicon is defined or not.
 */
function get_title($title) {
  global $zeus;
  return $title . (isset($zeus['title_append']) ? $zeus['title_append'] : null);
}

/**
 * Create a navigation bar / menu for the site.
 *
 * @param string $menu for the navigation bar.
 * @return string as the html for the menu.
 */
function get_navbar($menu)
{
    // Keep default options in an array and merge with incoming options that can override the defaults.
    $default = array(
      'id'          => null,
      'class'       => null,
	  'class2'       => null,
	  'data' => null,
      'wrapper'     => 'nav',
      'create_url'  => function ($url) {
        return $url;
      },
    );
    $menu = array_replace_recursive($default, $menu);
 
    // Function to create urls
    $createUrl = $menu['create_url'];
 
    // Create the ul li menu from the array, use an anonomous recursive function that returns an array of values.
    $createMenu = function ($items, $callback) use (&$createMenu, $createUrl) {
 
        $html = null;
		$ulclass = 'class="dropdown-menu"';
		$width = '100%';
        $hasItemIsSelected = false;
 
        foreach ($items as $item) {
 
            // has submenu, call recursivly and keep track on if the submenu has a selected item in it.
            $submenu        = null;
            $selectedParent = null;
 
            if (isset($item['submenu'])) {
                list($submenu, $selectedParent) = $createMenu($item['submenu']['items'], $callback);
                $selectedParent = $selectedParent
                    ? "dropdown active"
                    : null;
				$ulclass = 'class="nav navbar-nav"';
            }
 
            // Check if the current menuitem is selected
            $selected = $callback($item['url'])
                ? "active  "
                : null;
 
            // Is there a class set for this item, then use it
            $class = isset($item['class']) && ! is_null($item['class'])
                ? $item['class']
                : null;
 
            // Prepare the class-attribute, if used
            $class = ($selected || $selectedParent || $class)
                ? " class='{$selected}{$selectedParent}{$class}' "
                : null;
			
			// Is there a class set for this item, then use it //eget
			$class2 = isset($item['class2']) && ! is_null($item['class2'])
                ? $item['class2']
                : null;
			
			// Prepare the class2-attribute, if used
            $class2 = ($class2)
                ? " class='{$class2}' "
                : null;
				
			// Is there a class set for this item, then use it //eget
			$data = isset($item['data']) && ! is_null($item['data'])
                ? $item['data']
                : null;
			
			// Prepare the class2-attribute, if used
            $data = ($data)
                ? " data-toggle='{$data}' "
                : null;	

				
 
            // Add the menu item
            $url = $createUrl($item['url']);
            $html .= "\n<li{$class}><a {$class2} {$data} href='{$url}' title='{$item['title']}'>{$item['text']}</a>{$submenu}</li>\n";
 
            // To remember there is selected children when going up the menu hierarchy
            if ($selected) {
                $hasItemIsSelected = true;
            }
        }
        return array("\n<ul style='width:100%;' {$ulclass}>$html</ul>\n", $hasItemIsSelected);
    };
 
    // Call the anonomous function to create the menu, and submenues if any.
    list($html, $ignore) = $createMenu($menu['items'], $menu['callback']);
 
 
    // Set the id & class element, only if it exists in the menu-array
    $id      = isset($menu['id'])    ? " id='{$menu['id']}'"       : null;
    $class   = isset($menu['class']) ? " class='{$menu['class']}'" : null;
    $wrapper = $menu['wrapper'];
 
    return "\n<{$wrapper}{$id}{$class}>{$html}</{$wrapper}></div></div>\n";
}