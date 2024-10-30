<h2>iFrame Admin - Page Management</h2>
<?php
if(!empty($_POST['servicerequest']) && $_POST['servicerequest'] == 'new')
{
	# NEW IFRAME
	if(!empty($_POST['newiframe']) && $_POST['newiframe'] == 'new')
	{
		# PROCESSING NEW IFRAME SUBMISSION
		if(empty($_POST['name']))
		{
			$error = 1;
			echo '<h2>Please provide a name for your new iFrame</h2>';
		}

		if(empty($_POST['url']))
		{
			$error = 1;
			echo '<h2>Please provide a url for your new iFrame</h2>';
		}

		if(empty($_POST['height']) || !is_numeric($_POST['height']))
		{
			$error = 1;
			echo '<h2>Please provide a numeric height for your new iFrame i.e. 700</h2>';
		}
		
		# IF NO SUBMISSION ERROR SAVE TO DATABASE
		if(empty($error) && $error != 1)
		{
			# VALIDATE DATE PROVIDED NAME AND MAKE SAFE FIRST
			$name = sanitize_user( $_POST['name'], 'New iFrame' );
			$url = clean_url( $_POST['url'] );
			$height = $_POST['height']; 
			
			global $wpdb;
			$sqlQuery = "INSERT INTO " .
			$wpdb->prefix . "iframeadmin_iframe(name, height)VALUES('$name','$height')";
			$wpdb->query($sqlQuery);
			
			$iframeid = mysql_insert_id();
			
			$sqlQuery = "INSERT INTO " .
			$wpdb->prefix . "iframeadmin_urls(url,iframeid)VALUES('$url','$iframeid')";
			$wpdb->query($sqlQuery);			
			
			$n = true;
		}
		
		if(isset($n) && $n = true){echo '<h3>Your New iFrame Page Has Been Created</h3>';}
	}

	if(!isset($newiframe) && $newiframe != 'complete')
	{
		# DISPLAY NEW IFRAME FORM ?>
        <h3>Create New iFrame Page</h3>
        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" name="newiframeform">
        	<!-- Visible Objects -->
            <label>Name:<input type="text" name="name" size="30" maxlength="30" value="<?php if(!empty($_POST['name'])){echo $_POST['name'];} ?>" /></label>
        	<label>URL:<input type="text" name="url" size="60" maxlength="200" value="<?php if(!empty($_POST['url'])){echo $_POST['url'];} ?>" /></label>
        	<label>Height:<input type="text" name="height" size="4" maxlength="4" value="<?php if(!empty($_POST['height'])){echo $_POST['height'];} ?>" /></label>
        	<!-- Hidden Values -->
            <input name="servicerequest" type="hidden" value="new" />
            <input name="newiframe" type="hidden" value="new" />
        	<!-- Submit Button -->
            <input name="submit" type="submit" value="Submit" />
        </form>
        
        <br  />
        <?php
	}
}
elseif(!empty($_POST['servicerequest']) && $_POST['servicerequest'] == 'edit')
{
	# EDIT IFRAME
	if(!empty($_POST['editiframe']) && $_POST['editiframe'] == 'edit')
	{ 	
		$id = $_POST['iframeid'];	

		$name = $_POST['name'];
		$url = $_POST['url'];
		$height = $_POST['height'];
		
		global $wpdb;
		$wpdb->query("UPDATE " .$wpdb->prefix . "iframeadmin_iframe SET name = '$name', height = '$height' WHERE id = $id");
		$wpdb->query("UPDATE " .$wpdb->prefix . "iframeadmin_urls SET url = '$url' WHERE iframeid = $id");
		
		echo '<h3>Your iFrame Page Was Updated</h3>';
	}
	
	if(empty($newiframe) && $newiframe != 'complete')
	{ 
		# DISPLAY EDIT IFRAME FORM 
		$id = $_POST['iframeid'];
		
		global $wpdb;
		
		$result = $wpdb->get_row("SELECT name,height FROM " .$wpdb->prefix . "iframeadmin_iframe WHERE id = '$id'");
		$name = $result->name;
		$height = $result->height;

		$result2 = $wpdb->get_var("SELECT url FROM " .$wpdb->prefix . "iframeadmin_urls WHERE iframeid = $id");
		$url = $result2;
		?>
        <h3>Edit iFrame Page</h3>
        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" name="editiframeform">
            <label>Name:<input type="text" name="name" size="30" maxlength="30" value="<?php echo $name; ?>" /></label>
        	<label>URL:<input type="text" name="url" size="60" maxlength="200" value="<?php echo $url; ?>" /></label>
        	<label>Height:<input type="text" name="height" size="4" maxlength="4" value="<?php echo $height; ?>" /></label>
        	<input name="servicerequest" type="hidden" value="edit" />
            <input name="editiframe" type="hidden" value="edit" />
			<input name="iframeid" type="hidden" value="<?php echo $id; ?>" />
        	<input name="submit" type="submit" value="Submit" />
        </form>
        
        <br />
        <?php
	}
}
elseif(!empty($_POST['servicerequest']) && $_POST['servicerequest'] == 'delete')
{
	$id = $_POST['iframeid'];	
	global $wpdb;
	$result = $wpdb->query("DELETE FROM " .$wpdb->prefix . "iframeadmin_iframe WHERE id = '$id' ");
	$result = $wpdb->query("DELETE FROM " .$wpdb->prefix . "iframeadmin_urls WHERE iframeid = '$id'");
	echo '<h3>Your iFrame Was Deleted</h3>';	
}

# LIST ALL IFRAMES IN A TABLE FOR SELECTION 
function wtg_iframeadmin_delete($iframeid)
{ ?>

	<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" name="iframemanagementlistform">
		<input name="servicerequest" type="hidden" value="delete" />
		<input name="iframeid" type="hidden" value="<?php echo $iframeid; ?>" />
		<input name="submit" type="submit" value="Delete" />
	</form>
	
<?php	
}//end  wtg_iframeadmin_delete function	

function wtg_iframeadmin_edit($iframeid)
{ ?>

	<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" name="iframemanagementlistform">
		<input name="servicerequest" type="hidden" value="edit" />
		<input name="deleteiframe" type="hidden" value="edit" />
		<input name="iframeid" type="hidden" value="<?php echo $iframeid; ?>" />
		<input name="submit" type="submit" value="Edit" />
	</form>
	
<?php	
}//end  wtg_iframeadmin_delete function	
?>
 
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" name="newiframestartform">
	<input name="servicerequest" type="hidden" value="new" />
	<input name="submit" type="submit" value="Make New iFrame Page" />
</form>

<br />

<table width="621">
	<tr>
		<td width="132"><strong>Name</strong></td>
		<td width="279"><strong>URL</strong></td>
		<td width="88"><strong></strong></td>
		<td width="102"><strong></strong></td>
	</tr>    	
	
	<?php
	# RETRIEVE THEN LOOP AND DISPLAY LIST OF iFRAME PAGES 
	global $wpdb;
	$result = $wpdb->get_results("SELECT * FROM " .$wpdb->prefix . "iframeadmin_iframe");
			
	foreach ($result as $result) 
	{	
		# GET THE URL DATA FOR THIS LOOPED iFRAME PAGE
		$id = $result->id;
		$result2 = $wpdb->get_var("SELECT url FROM " .$wpdb->prefix . "iframeadmin_urls WHERE iframeid = $id");
		
		?><tr>
		<td><?php echo $result->name; ?></td>
		<td><?php echo $result2; ?></td>
		<td><?php echo wtg_iframeadmin_delete($id); ?></td>
		<td><?php echo wtg_iframeadmin_edit($id); ?></td>
		</tr><?php
		
	}// end of for each loop
	?>
	
</table>
  