<?php
	// Quick thing for links.
	$maindirectory = '../../';
	$_SESSION['maindirectory'] = $maindirectory;
	$files = array('config.php', 'addons/execute.php');
	
	foreach ($files as $value) {
		require_once($maindirectory . $value);
	}
	$db = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
	
	
	$filename = 'viewtopic.php';
	$id = 0;
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
	} else {
		$id = -1;
	}
	
	// This returns the user information that is logged in. Will return false if they aren't logged in.
	$userinfo = $classes['users']->fetch_user_info($db);
	
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

				echo '<div id="forumcontent">';
					// Print the User Control Panel or the Log in and Register links!
					$classes['page']->print_cp($userinfo, $maindirectory, $config);
						
					echo '<div id="forums">';
						// Print out the topic!
						$thread = $classes['forums']->print_thread($db, $id, $filename, $userinfo, $maindirectory, $config);
						
						// Now for the replies!
						$classes['forums']->print_replies($db, $id, $thread['topic'], $filename, $userinfo, $maindirectory, $config);
					
						if ($userinfo) {
							echo '<br /><br /><div class="new_reply">';
								echo '<form action="reply.php" method="POST">';
									echo '<input type="hidden" name="id" value="' . $id . '" />';
									echo '<input type="hidden" name="authid" value="' . $userinfo['id'] . '" />';
									echo '<textarea name="body" rows="10" cols="50">Reply here.</textarea><br />';
									echo '<input type="submit" value="Add Reply!" />';
								echo '</form>';
							echo '</div>';
						}
						
					echo '</div>';
				echo '</div>';
				$classes['page']->print_footer($config);
				
			echo '</body>';
		echo '</html>';
?>