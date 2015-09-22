<?php
	// Quick thing for links.
	$maindirectory = '../../';
	$_SESSION['maindirectory'] = $maindirectory;
	$files = array('config.php', 'addons/execute.php');
	
	foreach ($files as $value) {
		require_once($maindirectory . $value);
	}
	$db = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
	
	
	$filename = 'newthread.php';
	$id = 0;
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
	} elseif (isset($_POST['id'])) {
		$id = $_POST['id'];
	} else {
		$id = -1;
	}
	
	
	// This returns the user information that is logged in. Will return false if they aren't logged in.
	$userinfo = $classes['users']->fetch_user_info($db);
	
	if ($config['debug']) {
		echo 'Debug Information: <br />';
		echo 'ID: ' . $id . '<br />';
		echo 'User ID: ' . $userinfo['id'] . '<br />';
	}
	// The post!
	if (@$classes['tools']->check($_POST['title']) && @$classes['tools']->check($_POST['content']) && $userinfo && $id > 0) {
		// Post ready!
		$title = $_POST['title'];
		$content = $_POST['content'];
		
		foreach ($config['t_options'] as $value) {
			if ($value['HTML']) {
				$title = htmlentities($title);
				$content = htmlentities($content);
			}
			if ($value['autobreak']) {
				$content = str_replace(' ', '&nbsp', $content);
				$title = nl2br($title);
				$content = nl2br($content);
			}
			if ($value['secure']) {
				$title = mysqli_real_escape_string($db, $title);
				$content = mysqli_real_escape_string($db, $content);
			}
		}
		
		$query = "INSERT INTO `forumthreads` (fid, topic, body, authid, thetime) VALUES
																			(
																				'" . $id . "',
																				'" . $title . "',
																				'" . $content . "',
																				'" . $userinfo['id'] . "',
																				'" . time() . "'
																			)";
		$result = mysqli_query($db, $query);
		
		
		if ($result) {
			$newid = mysqli_insert_id($db);
			$query2 = "UPDATE `userinfo` SET `rep`= rep+" . $config['rep']['thread'] . " WHERE `uid`='" . $userinfo['id'] . "'";
			$result2 = mysqli_query($db, $query2);
			echo '<script type="text/javascript">alert("Topic inserted into the database!")</script>';
			header('Location: viewtopic.php?id=' . $newid);
		} else {
			echo 'Error Inserting the topic into the database! (ERROR: ' . mysqli_error($db) . ')';
		}
	}
	
	// Made by: [GFL] Roy (Christian Deacon)
	// Notes:
	/*
		This was my first advanced website I actually made. It was started on 9-14-14. I got some information (especially the drop downs and nav bars) from Google but changed them up a bit and made them work correctly.
		Point is, not entirely my custom code, but this website is optimized and unique by me.
	*/
?>

<?php
	echo '<html>';
		$classes['page']->load_sounds($maindirectory);
		$classes['page']->print_header(array($maindirectory . 'styling.css', 'forums.css'), $config);
		echo '<body>';
			$classes['page']->print_navbar($db, $userinfo, $maindirectory, $config);
			echo '<div id="maincontent">';
				
				$classes['page']->print_cp($userinfo, $maindirectory, $config);
					
				
				echo '<h1>New Thread</h1>';
				$classes['forums']->print_newthread($db, $id, $filename, $userinfo, $maindirectory, $config);

			echo '</div>';
			$classes['page']->print_footer($config);
		echo '</body>';
	echo '</html>';
?>