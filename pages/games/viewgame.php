<?php
	// Quick thing for links.
	$maindirectory = '../../';
	$_SESSION['maindirectory'] = $maindirectory;
	$files = array('config.php', 'addons/execute.php');
	
	foreach ($files as $value) {
		require_once($maindirectory . $value);
	}
	$db = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
	
	$filename = 'viewgame.php';
	
	if (!isset($_GET['id'])) {
		header('Location: ' . $filename . '?id=1');
	} else {
		$id = $_GET['id'];
	}
	
	if (!isset($_GET['width'])) {
		$width = '95%';
	} else {
		$width = $_GET['width'];
	}
	
	if (!isset($_GET['height'])) {
		$height = 400;
	} else {
		$height = $_GET['height'];
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
		$classes['page']->print_header(array($maindirectory . 'styling.css', 'games.css'), $config);
		echo '<body>';
			$classes['page']->print_navbar($db, $userinfo, $maindirectory, $config);
			echo '<div id="maincontent">';
				$classes['page']->print_cp($userinfo, $maindirectory, $config);
				echo '<div class="game_body">';
					$classes['games']->view_game($db, $id, $filename, $width, $height, $userinfo, $maindirectory, $config);
				echo '</div>';
			echo '</div>';
			$classes['page']->print_footer($config);
			
		echo '</body>';
	echo '</html>';
?>