<?php
$current_db_version = '0.1';

# INSTALL DATABASE TABLES ON INSTALLATION
function install_iframeadmin_campaigndata_table () 
{
   global $wpdb;
   global $current_db_version;

   if($wpdb->get_var("show tables like '$table_name'") != $table_name) 
   {
	   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	   
		# TABLE 1
		$table_name = $wpdb->prefix . "iframeadmin_iframe";
		$sql = "CREATE TABLE `" . $table_name . "` (
			`id` int(10) unsigned NOT NULL auto_increment,
			`name` varchar(30) NOT NULL,
			`description` varchar(200) default NULL,
			`type` int(10) unsigned NOT NULL COMMENT '0=normal 1=multiview',
			`height` int(10) unsigned NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='Holds main data for each iframe page'";		
		dbDelta($sql);// executes sql object query
		
		# INSERT SET OF DATA 1
		$name1 = "Plugin Home Page";
		$height1 = "1700";			
		$insert = "INSERT INTO " . $table_name . " (name, height) " . "VALUES ('$name1','$height1')";
		$results = $wpdb->query( $insert );		
		$rowid1 = mysql_insert_id();

		# INSERT SET OF DATA 1
		$name2 = "Affiliate Window";
		$height2 = "1700";			
		$insert = "INSERT INTO " . $table_name . " (name, height) " . "VALUES ('$name2','$height2')";
		$results = $wpdb->query( $insert );		
		$rowid2 = mysql_insert_id();

		# TABLE 2
		$table_name = $wpdb->prefix . "iframeadmin_urls";
		$sql = "CREATE TABLE `" . $table_name . "` (
			`id` int(10) unsigned NOT NULL auto_increment,
			`iframeid` int(10) unsigned NOT NULL COMMENT 'id of iframe from iframeadmin_iframe table',
			`url` varchar(500) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='URLs of pages to be included per iframe page'";
		dbDelta($sql);// executes sql object query
		
		$url1 = "http://www.webtechglobal.co.uk/wordpress-services/wordpress-iframe-admin-plugin";
		$insert = "INSERT INTO " . $table_name . " (iframeid, url) " . "VALUES ('$rowid1','$url1')";
		$results = $wpdb->query( $insert );	
		
		$url2 = "http://www.awin1.com/cread.php?s=39137&v=3&q=11&r=86425";
		$insert = "INSERT INTO " . $table_name . " (iframeid, url) " . "VALUES ('$rowid2','$url2')";
		$results = $wpdb->query( $insert );
		
		add_option("iframeadmin_db_version", $current_db_version); // insert database version option
	}
	
	
	# DO TABLE UPDATES FOR UPDATING PREVIOUS INSTALLATION OF DATABASE TABLES


	# GET CURRENT DB VERSION IF ANY
	$installed_db_version = get_option( "iframeadmin_db_version" );

	# ADD OR UPDATE csvtopost_relationships TABLE
	$table_name = $wpdb->prefix . "iframeadmin_iframe";
	if($installed_db_version != $current_db_version) 
	{ 
		$sql = "CREATE TABLE `" . $table_name . "` (
			`id` int(10) unsigned NOT NULL auto_increment,
			`name` varchar(30) NOT NULL,
			`description` varchar(200) default NULL,
			`type` int(10) unsigned NOT NULL COMMENT '0=normal 1=multiview',
			`height` int(10) unsigned NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='Holds main data for each iframe page'";		
		dbDelta($sql);// executes sql object query
		
		# INSERT SET OF DATA 1
		$name1 = "Plugin Home Page";
		$height1 = "1700";			
		$insert = "INSERT INTO " . $table_name . " (name, height) " . "VALUES ('$name1','$height1')";
		$results = $wpdb->query( $insert );		
		$rowid1 = mysql_insert_id();

		# INSERT SET OF DATA 1
		$name2 = "Affiliate Window";
		$height2 = "1700";			
		$insert = "INSERT INTO " . $table_name . " (name, height) " . "VALUES ('$name2','$height2')";
		$results = $wpdb->query( $insert );		
		$rowid2 = mysql_insert_id();

		update_option( "iframeadmin_db_version", $current_db_version );
	}

	$table_name = $wpdb->prefix . "iframeadmin_urls";
	if($installed_db_version != $current_db_version) 
	{ 
		$sql = "CREATE TABLE `" . $table_name . "` (
			`id` int(10) unsigned NOT NULL auto_increment,
			`iframeid` int(10) unsigned NOT NULL COMMENT 'id of iframe from iframeadmin_iframe table',
			`url` varchar(500) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='URLs of pages to be included per iframe page'";
		dbDelta($sql);// executes sql object query
		
		$url1 = "http://www.webtechglobal.co.uk/wordpress-services/wordpress-iframe-admin-plugin";
		$insert = "INSERT INTO " . $table_name . " (iframeid, url) " . "VALUES ('$rowid1','$url1')";
		$results = $wpdb->query( $insert );	
		
		$url2 = "http://www.awin1.com/cread.php?s=39137&v=3&q=11&r=86425";
		$insert = "INSERT INTO " . $table_name . " (iframeid, url) " . "VALUES ('$rowid2','$url2')";
		$results = $wpdb->query( $insert );
	
		update_option( "iframeadmin_db_version", $current_db_version );
	}
}

?>