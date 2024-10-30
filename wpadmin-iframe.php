<?php
/*
Plugin Name: iFrame Admin Pages
Version: 0.1
Plugin URI: http://www.webtechglobal.co.uk/wordpress-services/wordpress-iframe-admin-plugin
Description: Quickly create new pages in your WordPress dashboard administration using WPBlogs iFrame plugin.
Author: Ryan Bayne
Author URI: http://www.webtechglobal.co.uk

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

# GET GLOBAL FUNCTIONS
include('db_tables.php');

# INITIALISE HOOKS
register_activation_hook(__FILE__,'install_iframeadmin_campaigndata_table');

// Hook for adding admin menus
add_action('admin_menu', 'wpblogs_iframeadmin_add_pages');

// action function for above hook
function wpblogs_iframeadmin_add_pages() 
{
    add_menu_page('iFrame Admin', 'iFrame Admin', 8, __FILE__, 'wpblogs_iframeadmin_toplevel_page');

	# GET ALL IFRAME PAGES AND LOOP SUBMENU PAGE LINKS
	global $wpdb;
	$result = $wpdb->get_results("SELECT * FROM " .$wpdb->prefix . "iframeadmin_iframe");
	
	$i = 0;
	
	foreach ($result as $result) 
	{	
		$pagefile = 'iframe'.$i;
		
		$functionname = 'wpblogs_iframeadmin_sublevel_page_'.$i;
		
	    add_submenu_page(__FILE__, $result->name, $result->name, 8, $pagefile, $functionname);
				
		$i++;
	}
}

# PAGE FUNCTIONS CALLED WHEN MENU ITEMS ARE CLICKED ON
function wpblogs_iframeadmin_toplevel_page() 
{
    require('main_page.php');
}

# GET ALL IFRAME PAGES AND LOOP SUBMENU PAGE LINKS
global $wpdb;
$result = $wpdb->get_results("SELECT * FROM " .$wpdb->prefix . "iframeadmin_iframe");

$i = 0;

$code = "";

foreach ($result as $result) 
{	
	$code .= 'function wpblogs_iframeadmin_sublevel_page_';
	
	$code .= $i . ' ()';
	
	# GET THE URL DATA FOR THIS LOOPED iFRAME PAGE
	$id = $result->id;

	$result2 = $wpdb->get_var("SELECT url FROM " .$wpdb->prefix . "iframeadmin_urls WHERE iframeid = $id");

	$code .= '
	{ ?>
		<iframe src ="';
	
	$code .= $result2;
	
	$code .= '" width="100%" height="';
	
	$code .= $result->height;
	
	$code .= '"><p>Your browser does not support iframes.</p></iframe><?php
	}
	';
	
	$i++;
}

eval($code);
?>