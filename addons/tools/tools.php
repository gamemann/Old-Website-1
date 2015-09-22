<?php
	// Quick thing for links.
	if (isset($_SESSION['maindirectory'])) {
		$maindirectory = $_SESSION['maindirectory'];
	} else {
		if (isset($_POST['maindirectory'])) {
			$maindirectory = $_POST['maindirectory'];
		} else {
			$maindirectory = '../../';
		}
	}
	
	$files = array('config.php', 'addons/execute.php');
	
	foreach ($files as $value) {
		require_once($maindirectory . $value);
	}
	$db = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
	
	
	// This returns the user information that is logged in. Will return false if they aren't logged in.
	$userinfo = $classes['users']->fetch_user_info($db);
	
	if ($userinfo) {
		if ($classes['admin']->is_admin($userinfo['id'], $config)) {
			if (@$classes['tools']->check($_POST['type']) && @$classes['tools']->check($_POST['id']) && @$classes['tools']->check($_POST['othertype'])) {
				$type = $_POST['type'];
				$othertype = $_POST['othertype'];
				$id = $_POST['id'];
				
				$sql = '';
				foreach ($config['moderatetypes'] as $value) {
					if ($value['type'] == $othertype) {
						$sql = $value['sql'];
					}
				}
				
				if (isset($sql) && !empty($sql)) {
					if ($type == 'del') {
						$query = "DELETE FROM `" . $sql . "` WHERE `id`='" . $id . "'";
						$result = mysqli_query($db, $query);
					
						if ($result) {
							header('Location: ' . $maindirectory . 'index.php');
						} else {
							$classes['other']->alert('Did not successfully delete!');
						}
					} elseif ($type == 'delban') {
						$author = 0;
						$result = -1;
						$result2 = -1;
						$temp2q = "SELECT * FROM `" . $sql . "` WHERE `id`='" . $id . "'";
						$temp2r = mysqli_query($db, $temp2q);

						if ($temp2r) {
							$rows = mysqli_num_rows($temp2r);
							
							if ($rows > 0) {
								while ($t = mysqli_fetch_assoc($temp2r)) {
									$author = $t['authid'];
								}
							}
							
							if ($author > 0) {
								$query = "UPDATE `userinfo` SET `group`='-1', `permissions`='-1' WHERE `uid`='" . $author . "'";
								$result = mysqli_query($db, $query);
							}
							if ($result) {
								$query2 = "DELETE FROM `" . $sql . "` WHERE `id`='" . $id . "'";
								$result2 = mysqli_query($db, $query2);
							}
						}
						
						if ($result && $result2) {
							header('Location: ' . $maindirectory . 'index.php');
						} else {
							$classes['other']->alert('Did not successfully delete or ban the user!');
						}
					} elseif($type == 'deldel') {
						$author = 0;
						$result = -1;
						$result2 = -1;
						$temp2q = "SELECT * FROM `" . $sql . "` WHERE `id`='" . $id . "'";
						$temp2r = mysqli_query($db, $temp2q);

						if ($temp2r) {
							$rows = mysqli_num_rows($temp2r);
							
							if ($rows > 0) {
								while ($t = mysqli_fetch_assoc($temp2r)) {
									$author = $t['authid'];
								}
							}
							
							if ($author > 0) {
								if ($classes['admin']->is_admin($author, $config)) {
									if ($userinfo['id'] != $config['owner']) {
										$classes['other']->alert('You are not allowed to delete admins! Please contact Roy about this issue!');
										die();
									}
									if($author == $config['owner']) {
										$classes['other']->alert('We will not allow the owner to delete the owner? Are you out of your mind Roy?');
										die();
									}
								}
								$query = "DELETE FROM `users` WHERE `id`='" . $author . "'";
								$result = mysqli_query($db, $query);
								
								if ($result) {
									$query = "DELETE FROM `userinfo` WHERE `uid`='" . $author . "'";
									$result = mysqli_query($db, $query);
								}
							}
							if ($result) {
								$query2 = "DELETE FROM `" . $sql . "` WHERE `id`='" . $id . "'";
								$result2 = mysqli_query($db, $query2);
							}
						}
						
						if ($result && $result2) {
							header('Location: ' . $maindirectory . 'index.php');
						} else {
							$classes['other']->alert('Did not successfully delete or delete the user!');
						}
					} else {
						$classes['other']->alert('Invalid type! Please contact Roy!');
					}
				}
				
				// If it got here, obviously it failed.
				echo 'SQL Error: ' . mysqli_error($db) . '!';
			}
		}
	}
	
	// Made by: [GFL] Roy (Christian Deacon)
	// Notes:
	/*
		This was my first advanced website I actually made. It was started on 9-14-14. I got some information (especially the drop downs and nav bars) from Google but changed them up a bit and made them work correctly.
		Point is, not entirely my custom code, but this website is optimized and unique by me.
	*/
?>
