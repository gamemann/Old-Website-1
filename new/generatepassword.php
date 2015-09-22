<?php
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
		$lc = $_POST['lc'];
		$uc = $_POST['uc'];
		$sym = $_POST['sym'];
		$num = $_POST['num'];
		
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
		
		$password = get_random_string($validcharacters, (int)$length);
	}
	
	if (isset($_POST['length'])) {
		$temp = $_POST['length'];
	} else {
		$temp = 20;
	}
?>

<html>
	<body>
		<?php
			if ($password != '') {
				echo 'Password is: <div class="password"><strong>' . $password . '</strong></div><br />';
			}
			
			echo '<form action="generatepassword.php" method="POST">';
					echo 'Types:<br />';
						if ($lc) {
							$l[0] = 'checked';
						} else {
							$l[0] = '';
						}
						
						if ($uc) {
							$l[1] = 'checked';
						} else {
							$l[1] = '';
						}
						
						if ($sym) {
							$l[2] = 'checked';
						} else {
							$l[2] = '';
						}
						
						if ($num) {
							$l[3] = 'checked';
						} else {
							$l[3] = '';
						}
						
						echo '<input type="checkbox" name="lc" value="1" ' . $l[0] . '>Lowercase letters<br />';
						echo '<input type="checkbox" name="uc" value="1" ' . $l[1] . '>Uppercase letters<br />';
						echo '<input type="checkbox" name="sym" value="1" ' . $l[2] . '>Symbols<br />';
						echo '<input type="checkbox" name="num" value="1" ' . $l[3] . '>Numbers<br />';
						echo 'Length: <input type="text" name="length" value="' . $temp . '" /><br />';
					echo '<input type="submit" name="generate" value="Generate Password" /><br />';
			echo '</form>';
		?>
	</body>
</html>