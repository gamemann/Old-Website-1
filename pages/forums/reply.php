<?php
	// Quick thing for links.
	$maindirectory = '../../';
	$_SESSION['maindirectory'] = $maindirectory;
	$files = array('config.php', 'addons/execute.php');
	
	foreach ($files as $value) {
		require_once($maindirectory . $value);
	}
	$db = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
	
	
	$filename = 'reply.php';
	$id = 0;
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	} else {
		$id = -1;
	}
	
	$userinfo = 0;
	if (isset($_POST['authid'])) {
		$userinfo = $classes['users']->fetch_profile_info($db, $_POST['authid']);
	} else {
		$userinfo = -1;
	}
	
	if (@$classes['tools']->check($_POST['body']) && $id > 0 && $userinfo) {
		
		// Post ready!
		$body = $_POST['body'];
		
		foreach ($config['t_options'] as $value) {
			if ($value['HTML']) {
				$body = htmlentities($body);
			}
			if ($value['autobreak']) {
				$body = str_replace(' ', '&nbsp', $body);
				$body = nl2br($body);
			}
			if ($value['secure']) {
				$body = mysqli_real_escape_string($db, $body);
			}
		}
		$query = "INSERT INTO `forumreplies` (tid, body, authid, thetime) VALUES
																	(
																		'" . $id . "',
																		'" . $body ."',
																		'" . $userinfo['id'] . "',
																		'" . time() . "'
																	)";
		$result = mysqli_query($db, $query);
		
		if ($result) {
			$query2 = "UPDATE `userinfo` SET `rep`= rep+" . $config['rep']['reply'] . " WHERE `uid`='" . $userinfo['id'] . "'";
			$result2 = mysqli_query($db, $query2);
			
			echo '<script type="text/javascript">alert("Successfully replied!");</script>';
			header('Location: viewtopic.php?id=' . $id);
		} else {
			die ('Error inserting reply into the database! Error: (' . mysqli_error($db) . ')');
		}
	}
?>