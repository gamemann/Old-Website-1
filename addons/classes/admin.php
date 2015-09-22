<?php
	/*
		Every class file should be set up with this template.
		These are classes, basically extensions that make coding easier and calling functions quicker and cleaner.
	*/
	
	// These are the only lines that are required to connect everything to the project.
	if (isset($_SESSION['maindirectory'])) {
		$maindirectory = $_SESSION['maindirectory'];
	} else {
		if (isset($_POST['maindirectory'])) {
			$maindirectory = $_POST['maindirectory'];
		} else {
			$maindirectory = '';
		}
	}
	require($maindirectory . 'addons/execute.php');
	
	class __admin  {
		protected $c;
		
		function __construct() {
			global $classes;
			$this->c = $classes;
		}
		
		/*
			function:
				is_admin
				-- Checks whether the User is an admin or not.
			Arguements:
				$id - The ID of the User you are checking.
				$config - Main Config array (for all the settings).
		*/
		function is_admin($id, $config) {
			$admin = false;
			foreach ($config['admins'] as $value) {
				if($value == $id || $id == $config['owner']) {
					$admin = true;
				}
			}
			return $admin;
		}
		
		function edit_files($admin, $file, $filename, $userinfo, $maindirectory, $config) {
			if ($admin) {
				echo '<form action="' . $filename . '" method="GET">';
					echo 'Edit File:<br />';
					echo '<select name="filename">';
						$files = new RecursiveDirectoryIterator($maindirectory);
						foreach (new RecursiveIteratorIterator($files) as $v => $vi) {
							$v = str_replace('..\\', '', $v);
							$good = false;
							foreach ($config['file_extensions'] as $e) {
								if (strpos($v, $e)) {
									$good = true;
								}
							}
							
							foreach ($config['file_blacklist'] as $b) {
								$newt = str_replace('../', '', $v);
								if ($newt == $b) {
									$good = false;
								}
							}
							
							if ($good) {
								echo '<option value="' . $v . '">' . $v . '</option>';
							}
						}
					echo '</select><br />';
					echo '<input type="submit" value="Edit File">';
				echo '</form>';
				if ($file && file_exists($file)) {
					$asd = str_replace('..\\', '', $file);
					$asd = str_replace('../', '', $asd);
					$asd = str_replace('\\', '/', $asd);
					echo 'Editing File: <a href="' . $maindirectory . $asd . '" target=_new>' . $asd . '</a>';
					// Alright, so user is an Admin and the file exists!
					$output = htmlspecialchars(file_get_contents($file));
					//echo $output;
					echo '<form action="' . $filename . '" method="POST">';
						echo '<textarea name="edited" rows="30" cols="80">' . $output . '</textarea><br />';
						echo '<input type="hidden" name="editf" value="' . $file . '" />';
						echo '<input type="submit" name="save" value="Save File!" />';
					echo '</form>';
					
				} else {
					echo 'No file selected!<br />';
				}
				
				echo '<form action="' . $filename . '" method="POST">';
					echo 'File Name (include paths): <br />';
					echo '<input type="text" name="cfile" /><br />';
					echo '<input type="submit" name="makefile" value="Create File" />';
				echo '</form>';
			} else {
				echo '<div class="error">You are not an admin!</div>';
			}
		}
	}
?>