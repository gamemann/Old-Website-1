<?php
	// Quick thing for links.
	$maindirectory = '../';
	$_SESSION['maindirectory'] = $maindirectory;
	$files = array('config.php', 'addons/execute.php');
	
	foreach ($files as $value) {
		require_once($maindirectory . $value);
	}
	$db = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
	
	
	$filename = 'editfile.php';
	
	// This returns the user information that is logged in. Will return false if they aren't logged in.
	$userinfo = $classes['users']->fetch_user_info($db);
	
	if (@$classes['tools']->check($_GET['filename'])) {
		$file = $maindirectory . $_GET['filename'];
	} else {
		$file = false;
	}
	
	// ADMIN
	if ($userinfo) {
		$admin = $classes['admin']->is_admin($userinfo['id'], $config);
	} else {
		$admin = false;
	}
	
	if (@$classes['tools']->check($_POST['edited']) && @$classes['tools']->check($_POST['editf'])) {
		// Good to go!
		$edit = $_POST['edited'];
		$editfile = $_POST['editf'];
		
		$result = file_put_contents($editfile, $edit);
		$temp = str_replace('../', '', $editfile);
		
		if ($result) {
			echo '<script type="text/javascript">alert("Successfully edited the file!");</script>';
			header('Location: ' . $filename . '?filename=' . $temp);
		} else {
			echo '<script type="text/javascript">alert("Did not successfully edit the file!");</script>';
			header('Location: ' . $filename . '?filename=' . $temp);
		}
	}
	
	if (@$classes['tools']->check($_POST['cfile'])) {
		$cfile = $maindirectory . $_POST['cfile'];
		if (file_exists($cfile)) {
			echo '<script type="text/javascript">alert("File already exists!");</script>';
		} else {
			// Make the file!
			$cfilet = fopen($cfile, 'w+');
				fwrite($cfilet, '');
			fclose($cfilet);
			
			echo '<script type="text/javascript">alert("File created successfully!");</script>';
			header('Location: ' . $filename . '?filename=' . $_POST['cfile']); 
		}
	}
	
	// Made by: [GFL] Roy (Christian Deacon)
	// Notes:
	/*
		This was my first advanced website I actually made. It was started on 9-14-14. I got some information (especially the drop downs and nav bars) from Google but changed them up a bit and made them work correctly.
		Point is, not entirely my custom code, but this website is optimized and unique by me.
	*/

	echo '<html>';
		$classes['page']->load_sounds($maindirectory);
		$classes['page']->print_header($maindirectory . 'styling.css', $config);

		echo '<body>';
			$classes['page']->print_navbar($db, $userinfo, $maindirectory, $config);
			echo '<div id="maincontent">';
				$classes['page']->print_cp($userinfo, $maindirectory, $config);
				
				echo '<h1>Editing Files</h1>';
				echo '<div id="editfile">';
					$classes['admin']->edit_files($admin, $file, $filename, $userinfo, $maindirectory, $config);
				echo '</div>';
			echo '</div>';
			$classes['page']->print_footer($config);
			
		echo '</body>';
	echo '</html>';
?>