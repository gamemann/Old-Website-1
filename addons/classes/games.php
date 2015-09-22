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
	
	class __games  {
		protected $c;
		
		function __construct() {
			global $classes;
			$this->c = $classes;
		}
		
		function display_games($db, $cat, $filename, $userinfo, $maindirectory, $config) {
			// Little bit of Javascript first!
			echo '
				<script type="text/javascript">
					function gotogame(id) {
						window.location.href = "' . $maindirectory . 'pages/games/viewgame.php?id=" + id;
					}
					
					function changecat(id) {
						window.location.href= "' . $filename . '?cat=" + id;
					}
				</script>';
			if ($userinfo) {
				echo '<div class="addgamebutton"><a href="' . $maindirectory . 'pages/games/addgame.php">Add a game!</a></div>';
			} else {
				echo 'Log in / Register to add games!<br/ >';
			}
			
			echo '<br />';
			echo 'Cateogry:';
			echo '<form action="' . $filename . '" method="GET">';
				echo '<SELECT name="category" onchange="changecat(this.value);">';
					echo '<option value="255">All</option>';
						$cquery = "SELECT * FROM `gamecategories`";
						$cresult = mysqli_query($db, $cquery);
						
						if ($cresult) {
							$rows = mysqli_num_rows($cresult);
							
							if ($rows > 0) {
								while ($cate = mysqli_fetch_assoc($cresult)) {
									
									$temp = '<option value="' . $cate['codename'] . '"';
									if ($cate['codename'] == $cat) {
										$temp .= 'selected="selected"';
									}
									$temp .= '>' . $cate['name'] . '</option>';
									echo $temp;
								}
							}
						}
				echo '</SELECT>';
			echo '</form>';
			
			// Now to display the games!
			$query = "SELECT * FROM `games`";
			if ($cat != 255) {
				$query .= " WHERE `category`='" . $cat . "'";
			}
			$query .= " ORDER BY `category` ASC, `id` DESC"; 
			$result = mysqli_query($db, $query);
			
			if ($result) {
				$rows = mysqli_num_rows($result);
				
				if ($rows > 0) {
					while ($game = mysqli_fetch_assoc($result)) {
						$cg = $game['category'];
						if (!isset($category[$cg])) {
							$category[$cg] = 0;
						}
						if ($category[$cg] == 0) {
							$temp3 = 'Unknown';
							$cquery = "SELECT * FROM `gamecategories` WHERE `codename`='" . $cg . "'";
							$cresult = mysqli_query($db, $cquery);
							
							if ($cresult) {
								$rows = mysqli_num_rows($cresult);
								
								if ($rows > 0) {
									while ($l = mysqli_fetch_assoc($cresult)) {
										$temp3 = $l['name'];
									}
								}
							}
							echo '<br /><h2>' . $temp3 . '</h2><br />';
						}
						// Now to print out all these games.
						echo '<div id="game_' . $game['id'] . '" class="sgame" onclick="gotogame(' . $game['id'] . ');">';
							$imagepath = 'games/' . $game['codename'] . '/' . $game['image'];
							$defaultimage = 'games/default.png';
							if (file_exists($imagepath)) {
								// The image exists!
								echo '<img src="' . $imagepath . '" width="120" height="120" /><br />';
							} else {
								echo '<img src="' . $defaultimage . '" width="120" height="120" /><br />';
							}
							echo $game['name'] . '<br />';
							//echo '<div id="sgame_cat">' . $game['category'] . '</div>';
							
						echo '</div>';
						$category[$cg]++;
					}
				}
			}
			echo '<br />';
			echo '<br />';
		}
		
		function view_game($db, $id, $filename, $width, $height, $userinfo, $maindirectory, $config) {
			// Javascript
			echo '
				<script type="text/javascript">
					var width = 95%;
					var height = 400;
					
					function change(type, size) {
						if (type == "width") {
							width = size;
						} else {
							height = size;
						}
					}
					
					function changesize() {
						window.location.href = "' . $filename . '?id=' . $id . '&width=" + width + "&height=" + height;
					}
				</script>
			';
	
			$query = "SELECT * FROM `games` WHERE `id`='" . $id . "'";
			$result = mysqli_query($db, $query);
			
			if ($result) {
				$rows = mysqli_num_rows($result);
				
				if ($rows > 0) {
					while ($g = mysqli_fetch_assoc($result)) {
						$path = 'games/' . $g['codename'] . '/' . $g['codename'] . '.swf';
						
						if ($g['authid']) {
							$author = $this->c['users']->fetch_profile_info($db, $g['authid']);
						} else {
							$author = $this->c['users']->fetch_profile_info($db, $config['owner']);
						}

						
						if (!$author) {
							$author = 'Unknown';
						} else {
							// Get the Author User's Name color.
							$color = '';
							$color2 = '';
							if ($author['color'] != '') {
								$color = '<font color="#' . $author['color'] . '">';
								$color2 = '</font>';
							}
						}
						
						// Final piece
						if ($author != 'Unknown') {
							$temp2 = '<a href="' . $maindirectory . 'pages/users/viewuser.php?id=' . $author['id'] . '">' . $color . ucfirst($author['name']) . '</font></a>';
						} else {
							$temp2 = 'Unknown';
						}
					
						if ($g['thetime'] > 10) {
							$thetime = date('D, F dS | g:i A', $g['thetime']);
						} else {
							$thetime = date('D, F dS | g:i A', 1413770683);
						}
						
						$temp = $g['authid'];
						echo '<h1>' . $g['name'] . ' (ID: ' . $g['id'] . ')</h1>';
						echo 'Description: ' . $g['description'] . '<br />';
						echo 'Author: ' . $temp2 . '<br />';
						echo 'Added: ' . $thetime . '<br />';
						echo 'Category Code: ' . ucfirst($g['category']) . '<br /><br />';
						echo '<form action="' . $filename . '" method="GET">';
							echo 'Width: <input type="text" name="width" value="' . $width . '" onchange="change(\'width\', this.value);" /><br />';
							echo 'Height: <input type="text" name="height" value=" ' . $height . '" onchange="change(\'height\', this.value);" /><br />';
							echo '* Use 100% for each one if you want it full screen!<br />';
							echo '<input type="button" value="Resize Game!" onclick="changesize();" />';
						echo '</form>';
						
						 echo '<object height="' . $height . '" width="' . $width . '" data="' . $path . '">
						 <PARAM NAME="SCALE" VALUE="exactfit">
						 </object>';
						if ($this->c['admin']->is_admin($userinfo['id'], $config)) {
							$this->c['tools']->moderatetools($g['id'], 'games', $maindirectory, $config);
						}
						echo '<br />';
						echo '<br />';
					}
				} else {
					echo 'Game was not found in the database!';
				}
			} else {
				echo 'Bad MYSQL query! (Error: ' . mysqli_error($db) . ')';
			}
			
			
			// Now for our lovely PHP code!
			/*

			*/
		}
		
		function add_game($db, $filename, $userinfo, $maindirectory, $config) {
			echo '<h1>Add a game!</h1>';
			echo '<div id="addgame">';
				$good = true;
				foreach ($config['game_blacklist'] as $v) {
					if ($userinfo['id'] == $v) {
						$good = false;
					}
				}
				
				if ($good && $userinfo) {
					echo '<form action="' . $filename . '" method="POST" enctype="multipart/form-data">';
						echo 'Game Name:<br /><input type="text" name="g_name" /><br />';
						echo '<br />Game Code:<br /><input type="text" name="g_code" /><br />';
						echo '<br />Description:<br /><textarea name="g_desc" rows="10" cols="30">Game description here!</textarea><br />';
						// Now for the categories!
						echo '<br />Category:<br/ ><select name="g_cat">';
							$query = "SELECT * FROM `gamecategories`";
							$result = mysqli_query($db, $query);
							
							if ($result) {
								$rows = mysqli_num_rows($result);
								
								if ($rows > 0) {
									while ($t = mysqli_fetch_assoc($result)) {
										echo '<option value="' . $t['codename'] . '">' . $t['name'] . '</option>';
									}
								}
							}
						echo '</select><br />';
						
						
						echo '<br />SWF File:<br /><input type="file" name="g_file" /><br />';
						echo '<br />Image:<br /><input type="file" name="g_image" /><br />';
						echo '<br /><input type="submit" value="Add Game!" />';
					echo '</form>';
					echo 'Example:<br />';
					echo 'Game Name: Balloon Tower Defense<br />';
					echo 'Game Code: btd<br />';
					echo '<br /><br />Tutorial:<br/ >';
					echo '<object width="500" height="500" data="http://www.youtube.com/v/IAQBx137Gmc"></object>';
				}
				
				if (!$userinfo) {
					echo 'Only for registered users!<br />';
				}
				
				if (!$good) {
					echo 'Your account is blacklisted. You are not allowed to add games.';
				}
			echo '</div>';
		}
	}
?>