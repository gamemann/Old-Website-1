<?php
	// This should be executed on every file this project contains.	
	
	if (isset($_SESSION['maindirectory'])) {
		$maindirectory = $_SESSION['maindirectory'];
	} else {
		if (isset($_POST['maindirectory'])) {
			$maindirectory = $_POST['maindirectory'];
		} else {
			$maindirectory = '';
		}
	}
	require_once($maindirectory . 'config.php');
	
	foreach ($config['extensions'] as $value) {
		require_once($maindirectory . 'addons/classes/' . $value . '.php');
	}
	
	if (!isset($classes)) {
		// Make sure to add the class/extension here!
		$classes['admin'] = new __admin;
		$classes['other'] = new __other;
		$classes['tools'] = new __tools;
		$classes['users'] = new __users;
		$classes['page'] = new __page;
		$classes['games'] = new __games;
		$classes['forums'] = new __forums;
		$classes['project_pg'] = new __project_pg;
	}
	
?>