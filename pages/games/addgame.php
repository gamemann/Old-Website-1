<?php
	// Quick thing for links.
	$maindirectory = '../../';
	$_SESSION['maindirectory'] = $maindirectory;
	$files = array('config.php', 'addons/execute.php');
	
	foreach ($files as $value) {
		require_once($maindirectory . $value);
	}
	$db = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
	
	
	$filename = 'addgame.php';
	
	// This returns the user information that is logged in. Will return false if they aren't logged in.
	$userinfo = $classes['users']->fetch_user_info($db);
	
	if (@$classes['tools']->check($_POST['g_name']) && @$classes['tools']->check($_POST['g_code']) && @$classes['tools']->check($_POST['g_desc']) && @$classes['tools']->check($_POST['g_cat'])) {
		$name = mysqli_real_escape_string($db, htmlentities($_POST['g_name']));
		$code = mysqli_real_escape_string($db, htmlentities(strtolower($_POST['g_code'])));
		$desc = mysqli_real_escape_string($db, htmlentities($_POST['g_desc']));
		$cat = mysqli_real_escape_string($db, htmlentities($_POST['g_cat']));
		
		// Strip spaces and set code to all lowercase!
		$code = str_replace(' ', '', $code);
		$code = strtolower($code);
		
		if (isset($_FILES['g_file'])) {
			$file = $_FILES['g_file'];
		} else {
			$file = '';
			echo '<script type="text/javascript">alert("Failed to upload game!");</script>';
			$proceed = false;
		}
		
		if (isset($_FILES['g_image'])) {
			$image = $_FILES['g_image'];
		} else {
			$image = '';
			$proceed = false;
		}
		
		if (!$image['tmp_name'] && $image['error'] > 0) {
			$image = '';
		}
		
		if (!$file['tmp_name'] && $file['error'] > 0) {
			$proceed = false;
			echo '<script type="text/javascript">alert("Failed to upload game! ' . $file['error'] . '");</script>';
		}
		// Now we need to check two things, we need to check the code name and if the directory/mysql entry exist.
		
		// #1
		$proceed = true;
		if (file_exists('games/' . $code)) {
			$proceed = false;
		}
		
		// #2
		$query = "SELECT * FROM `games` WHERE `codename`='" . $code . "'";
		$result = mysqli_query($db, $query);
		
		if ($result) {
			$rows = mysqli_num_rows($result);
			
			if ($rows > 0) {
				$proceed = false;
			}
		} else {
			$proceed = false;
		}
		
		if ($file['error'] > 0) {
			$proceed = false;
			echo '<script type="text/javascript">alert("Failed to upload game! ' . $file['error'] . '");</script>';
		}
		
		if ($proceed) {
			mkdir('games/' . $code, 0700);
			if ($image) {
				$imagename = $image['name'];
				$ext = pathinfo($imagename, PATHINFO_EXTENSION);
				
				if (!in_array($ext, $config['game_iexts'])) {
					echo '<script type="text/javascript">alert("Invalid extension for the image!");</script>';
				} else {
					// Now to move the file into that directory!
					move_uploaded_file($image['tmp_name'], 'games/' . $code . '/' . $image['name']);
				}
			}
			
			$filename = $file['name'];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			if (!in_array($ext, $config['game_gexts'])) {
				echo '<script type="text/javascript">alert("Invalid extension for the game!");</script>';
			} else {
				$temp = explode('.', $file['name']);
				$newfilename = $code . '.' . end($temp);
				move_uploaded_file($file['tmp_name'], 'games/' . $code . '/' . $newfilename);
				
				// Now proceed!
				if ($image['name']) {
					$imaget = $image['name'];
				} else {
					$imaget = '';
				}
				$query = "INSERT INTO `games` (name, image, description, codename, category, authid, thetime) VALUES
																								(
																									'" . $name . "',
																									'" . $imaget . "',
																									'" . $desc . "',
																									'" . $code . "',
																									'" . $cat . "',
																									'" . $userinfo['id'] . "',
																									'" . time() . "'
																								)";
				$result = mysqli_query($db, $query);
				
				if ($result) {
					$lastid = mysqli_insert_id($db);
					$query2 = "UPDATE `userinfo` SET `rep`= rep+" . $config['rep']['game'] . " WHERE `uid`='" . $userinfo['id'] . "'";
					$result2 = mysqli_query($db, $query2);
					echo '<script type="text/javascript">alert("Game inserted successfully!");</script>';
					header('Location: viewgame.php?id=' . $lastid);
				} else {
					die ('Couldn\'t insert game! MYSQL Error: ' . mysqli_error($db));
				}
			}
			
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
		$classes['page']->print_header(array($maindirectory . 'styling.css', 'games.css'), $config);
		echo '<body>';
			$classes['page']->print_navbar($db, $userinfo, $maindirectory, $config);
			echo '<div id="maincontent">';
			$classes['page']->print_cp($userinfo, $maindirectory, $config);
			$classes['games']->add_game($db, $filename, $userinfo, $maindirectory, $config);
		
			$classes['page']->print_footer($config);
		echo '</body>';
	echo '</html>';
?>