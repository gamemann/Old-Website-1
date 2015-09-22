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
	
	class __tools  {
		protected $c;
		
		function __construct() {
			global $classes;
			$this->c = $classes;
		}
		/*
			function:
				moderatetools
				-- Echo's the moderator tools (best used in a mysql_fetch while loop).
			Arguements:
				$id - The ID of the MYSQL Entry you are using the moderate tool on.
				$type - The type of page you are editing (example: `news`). This is used for getting the table name in the database.
				$maindirectory - The main directory variable in the file (how many paths up from the main directory)
				$config - Main Config array (for all the settings).
		*/
		function moderatetools($id, $type, $maindirectory, $config) {
			echo '<hr /><div style="text-align: center;"><form action="' . $maindirectory . 'addons/tools/tools.php" method="POST">';
				echo '<br />Moderate Tools:<br />';
				echo '<select name="type">';
					foreach($config['moderatetools'] as $value) {
						echo '<option value="' . $value['sname'] . '">' . $value['name'] . '</option>';
					}
				echo '</select><br />';
				echo '<input type="hidden" name="id" value="' . $id . '" />';
				echo '<input type="hidden" name="othertype" value="' . $type . '" />';
				echo '<input type="hidden" name="maindirectory" value="' . $maindirectory . '" />';
				echo '<input type="submit" value="Go!" />';
			echo '</form></div>';
		}
		
		/*
			function:
				Format_Post
				-- Formats the post to use BBCodes and more!
			Arguements:
				$post - The string of text you want to format.
				$config - Main Config array (for all the settings).
		*/
		function Format_Post($post, $config) {
			foreach ($config['bbcodes'] as $value) {
				$text = $value['format'];
				// Lets start by doing the format!
				if (isset($value['additional'])) {
					$delimiter = '#';
					$start = '[' . $value['code'];
					$end = ']';
					$regex = $delimiter . preg_quote($start, $delimiter) 
										. '(.*?)' 
										. preg_quote($end, $delimiter) 
										. $delimiter 
										. 's';
					preg_match_all($regex, $post, $matches);
					for ($g=0; $g < count($matches[1]); $g++ ) {
						
						$w1 = explode(' ', $matches[1][$g]);
						for ($w=0; $w < count($w1); $w++) {
							if (strpos($w1[$w], '=') !== false) {
		
								$ltemp = preg_split('/=/', $w1[$w]);
								if (isset($ltemp[1])) {
									$temp = preg_split('/,/', $value['additional']);
									
									for ($m=0; $m < count($temp); $m++) {
										$td = stripslashes(trim(str_replace(array(' ', '&nbsp'), '', $temp[$m])));
										$ts = stripslashes(trim(str_replace(array(' ', '&nbsp'), '', $ltemp[0])));
										if ($td == $ts) {
											// It's a match!
											$newltemp = addslashes($ltemp[1]);
											$newltemp = str_replace(array('"', "'", '&quot;'), '', $newltemp);
											$newtext[$m][$g] = str_replace('%' . $temp[$m], $newltemp, $text);
											$post = str_replace($ltemp[0] . '=' . $ltemp[1], '', $post);
										}
									}
								} else {
									
								}
							}
						}
					}
				}
				
				// Now replace the string!
				$delimiter = '#';
				$startTag = '[' . $value['code'] . ']';
				$endTag = '[/' . $value['code'] . ']';
				$regex = $delimiter . preg_quote($startTag, $delimiter) 
									. '(.*?)' 
									. preg_quote($endTag, $delimiter) 
									. $delimiter 
									. 's';
				preg_match_all($regex, $post, $matches2);
				for ($i=0; $i < count($matches2[0]); $i++ ) {
					if (!empty($matches2[0][$i])) {
						if (isset($value['additional']) && !empty($value['additional'])) {
							$temp3 = preg_split('/,/', $value['additional']);
								for ($k=0; $k < count($temp3); $k++) {
									if (!empty($newtext[$k][$i])) {
										$newtext[$k][$i] = str_replace('%s', $matches2[1][$i], $newtext[$k][$i]);
										// As a final thing
										$asd = preg_split('/,/', $value['additional']);
										
										for ($n=0; $n < count($asd); $n++ ){
											if (strpos($newtext[$k][$i], $asd[$n]) !== false) {
												$newtext[$k][$i] = str_replace('%' . $asd[$n], '', $newtext[$k][$i]);
											}
										}
										$post = str_replace($matches2[0][$i], '[' . $value['code'] . ']' . $newtext[$k][$i] . '[/' . $value['code'] . ']', $post);
									}
								}
						} else {
							$random = str_replace('%s', $matches2[1][$i], $text);
							$post = str_replace($matches2[0][$i], '[' . $value['code'] . ']' . $random . '[/' . $value['code'] . ']', $post);
						}
					}
					
				}
				
				// Now replace the tags!
				$delimiter = '#';
				$startTag = '[';
				$endTag = ']';
				$regex = $delimiter . preg_quote($startTag, $delimiter) 
									. '(.*?)' 
									. preg_quote($endTag, $delimiter) 
									. $delimiter 
									. 's';
				preg_match_all($regex, $post, $matches3);
				for ($i=0; $i < count($matches3[0]); $i++ ) {
					if (strpos($matches3[0][$i], $value['code']) !== false && strpos($matches3[0][$i], '/') === false) {
						$post = str_replace($matches3[0][$i], '<div class="bbcode_' . $value['code'] . '"><span>', $post);
					}
				}
				
				$delimiter = '#';
				$startTag = '[';
				$endTag = ']';
				$regex = $delimiter . preg_quote($startTag, $delimiter) 
									. '(.*?)' 
									. preg_quote($endTag, $delimiter) 
									. $delimiter 
									. 's';
				preg_match_all($regex, $post, $matches4);
				for ($i=0; $i < count($matches4[0]); $i++ ) {
					if (strpos($matches4[0][$i], $value['code']) !== false && strpos($matches4[0][$i], '/') !== false) {
						$post = str_replace($matches4[0][$i], '</span></div>', $post);
					}
				}
				
				
			}
			
			return $post;
		}
	
		function secure($what, $id=0, $type=0, $custom=0) {
			if ($type == 0) {
				if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
					return password_hash($what, PASSWORD_BCRYPT, ["cost" => 9, "salt" => md5($id)]);
				} else {
					return hash('sha512',$what);
				}
			} else {
				crypt($what, $custom);
			}
			
		}
		/*
			function:
				roy_check
				-- Checks for isset() and !empty(), if one returns false, it will return false.
			Arguements:
				$post - The string of text you want to format.
				$config - Main Config array (for all the settings).
		*/
		function check($what) {
			if (isset($what) && !empty($what)) {
				return true;
			} else {
				return false;
			}
		}
	}
?>