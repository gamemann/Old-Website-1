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
	
	class __project_pg  {
		protected $c;
		
		function __construct() {
			global $classes;
			$this->c = $classes;
		}
		
		function project_pg() {
			function get_random_string($valid_chars, $length) {
				// start with an empty random string
				$random_string = "";

				// count the number of chars in the valid chars string so we know how many choices we have
				$num_valid_chars = strlen($valid_chars);

				// repeat the steps until we've created a string of the right length
				for ($i = 0; $i < $length; $i++) {
					// pick a random number from 1 up to the number of valid chars
					$random_pick = mt_rand(1, $num_valid_chars);

					// take the random character out of the string of valid chars
					// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
					$random_char = $valid_chars[$random_pick-1];

					// add the randomly-chosen char onto the end of our string so far
					$random_string .= $random_char;
				}

				// return our finished random string
				return $random_string;
			}
			
			$password = '';
			$lc = 0;
			$uc = 0;
			$sym = 0;
			$num = 0;
			
			if (isset($_REQUEST['generate'])) {
				$validcharacters = '';
				
				if (isset($_POST['lc'])) {
					$lc = $_POST['lc'];
				} else {
					$lc = 0;
				}
				
				if (isset($_POST['uc'])) {
					$uc = $_POST['uc'];
				} else {
					$uc = 0;
				}
				
				if (isset($_POST['sym'])) {
					$sym = $_POST['sym'];
				} else {
					$sym = 0;
				}
				
				if (isset($_POST['num'])) {
					$num = $_POST['num'];
				} else {
					$num = 0;
				}
				
				
				$length = $_POST['length'];
				
				if ($length < 1 || preg_match('/^.*(?=.*[a-z|A-Z]).*$/', $length)) {
					$length = 20;
				}
				
				if ($lc) {
					$validcharacters .= 'abcdefghijklmnopqrstuvwxyz';
				}
				
				if ($uc) {
					$validcharacters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				}
				
				if ($sym) {
					$validcharacters .= '!@#$%^&*()_+=-[{]};:\'",<.>/?`~';
				}		
				
				if ($num) {
					$validcharacters .= '0123456789';
				}
				
				if ($lc || $uc || $sym || $num) {
					$password = get_random_string($validcharacters, (int)$length);
				} else {
					$password = 'Check something';
				}
			}
			
			if (isset($_POST['length'])) {
				$temp = $_POST['length'];
			} else {
				$temp = 20;
			}
			
			// More
			if ($password != '') {
				echo 'Password is: <div class="password"><strong>' . htmlspecialchars($password) . '</strong></div><br />';
			}
			
			echo '<form action="index.php" method="POST">';
					echo 'Types:<br />';
						if (isset($lc) && $lc) {
							$l[0] = 'checked';
						} else {
							$l[0] = '';
						}
						
						if (isset($uc) && $uc) {
							$l[1] = 'checked';
						} else {
							$l[1] = '';
						}
						
						if (isset($sym) && $sym) {
							$l[2] = 'checked';
						} else {
							$l[2] = '';
						}
						
						if (isset($num) && $num) {
							$l[3] = 'checked';
						} else {
							$l[3] = '';
						}
						
						echo '<input type="checkbox" name="lc" value="1" ' . $l[0] . '>Lowercase letters<br />';
						echo '<input type="checkbox" name="uc" value="1" ' . $l[1] . '>Uppercase letters<br />';
						echo '<input type="checkbox" name="sym" value="1" ' . $l[2] . '>Symbols<br />';
						echo '<input type="checkbox" name="num" value="1" ' . $l[3] . '>Numbers<br />';
						echo 'Length: <input type="text" name="length" value="' . $temp . '" size="5" /><br />';
					echo '<input type="submit" name="generate" value="Generate Password" /><br />';
			echo '</form>';
		}
	}
?>