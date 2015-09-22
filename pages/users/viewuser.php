<?php
	// Quick thing for links.
	$maindirectory = '../../';
	$_SESSION['maindirectory'] = $maindirectory;

	$files = array('config.php', 'addons/execute.php');
	
	foreach ($files as $value) {
		require_once($maindirectory . $value);
	}
	$db = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
	
	$filename = 'viewuser.php';
	
	$id = 0;
	if (!isset($_GET['id'])) {
		$id = -1;
	} else {
		$id = $_GET['id'];
	}
	
	// What this does is transfer the ID over a separate form.
	if (isset($_POST['id'])) {
		header('Location: ' . $filename . '?id=' . $_POST['id']);
	}

	$editmode = false;
	
	if (isset($_REQUEST['editmode'])) {
		$editmode = true;
	}
	
	// This returns the user information that is logged in. Will return false if they aren't logged in.
	$userinfo = $classes['users']->fetch_user_info($db);
	
	if (@$classes['tools']->check($_POST['about']) && @$classes['tools']->check($_POST['age']) && @$classes['tools']->check($_POST['website']) && @$classes['tools']->check($_POST['bio']) && @$classes['tools']->check($_POST['signature'])) {
		if ($userinfo) {
			$about = mysqli_real_escape_string($db, htmlspecialchars($_POST['about']));
			$age = mysqli_real_escape_string($db, htmlspecialchars($_POST['age']));
			$website = mysqli_real_escape_string($db, htmlspecialchars($_POST['website']));
			$bio = mysqli_real_escape_string($db, htmlspecialchars($_POST['bio']));
			$signature = mysqli_real_escape_string($db, htmlspecialchars($_POST['signature']));
			
			$query = "UPDATE `userinfo` SET `about`='" . $about . "', `age`='" . $age . "', `website`='" . $website . "', `bio`='" . $bio . "', `signature`='" . $signature . "' WHERE `uid`='" . $userinfo['id'] . "'";
			$result = mysqli_query($db, $query);
			
			if ($result) {
			} else {
				echo '<script type="text/javascript">alert("Profile not edited successfully!");</script>';
			}
		}
	}
	
	$profileinfo = $classes['users']->fetch_profile_info($db, $id);
	
	// Made by: [GFL] Roy (Christian Deacon)
	// Notes:
	/*
		This was my first advanced website I actually made. It was started on 9-14-14. I got some information (especially the drop downs and nav bars) from Google but changed them up a bit and made them work correctly.
		Point is, not entirely my custom code, but this website is optimized and unique by me.
	*/

	echo '<html>';
		$classes['page']->load_sounds($maindirectory);
		$classes['page']->print_header(array($maindirectory . 'styling.css'), $config);
		
		echo '<body>';
			$classes['page']->print_navbar($db, $userinfo, $maindirectory, $config);
			echo '<div id="maincontent">';
					$classes['page']->print_cp($userinfo, $maindirectory, $config);
					$classes['users']->display_profile($db, $id, $profileinfo, $filename, $editmode, $userinfo, $maindirectory, $config);
				
			echo '</div>';
			
			$classes['page']->print_footer($config);
		echo '</body>';
	echo '</html>';
?>