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

	class __page  {
		protected $c;
		
		function __construct() {
			global $classes;
			$this->c = $classes;
		}
		
		function load_sounds($maindirectory) {
			echo '<audio id="menusound1" src="' . $maindirectory . 'sounds/ms1.wav" preload="auto"></audio>';
			echo '<audio id="menusound2" src="' . $maindirectory . 'sounds/ms2.wav" preload="auto"></audio>';
		}
		
		/*
			function:
				print_cp
				-- Echo's the User's Control Panel.
			Arguements:
				$userinfo - User's Information array.
				$maindirectory - The main directory variable in the file (how many paths up from the main directory)
				$config - Main Config array (for all the settings).
		*/
		function print_cp($userinfo, $maindirectory, $config) {
			// HTML
			echo '<div id="usercp">';
				// Check if the User is logged in or not.
				if ($userinfo) {
					// User is logged in...
					echo 'Welcome back <a href="' . $maindirectory . 'pages/users/viewuser.php?id=' . $userinfo['id'] . '">' . ucfirst($userinfo['name']) . '</a>! <a href="' . $maindirectory . 'pages/users/login.php?logout">Logout</a>!';
				} else {
					echo '<a href="' . $maindirectory . 'pages/users/login.php">Login</a> | <a href="' . $maindirectory . 'pages/users/register.php">Register</a>';
				}
			
			// End of HTML
			echo '</div>';
		}
		
		/*
			function:
				print_navbar
				-- Echo's the NavBar.
			Arguements:
				$db - Database handle.
				$userinfo - User's Information array.
				$maindirectory - The main directory variable in the file (how many paths up from the main directory)
				$config - Main Config array (for all the settings).
		*/
		function print_navbar($db, $userinfo, $maindirectory, $config) {
			// Javascript comes first!
			echo '
				<script type="text/javascript">
					var isopen = false;
					function playmsound() {
						document.getElementById(\'menusound1\').play();
					}
					
					function showmenu(num) {
						document.getElementById(\'menusound2\').play();
						ev = document.getElementById("lol");
						if (ev) {
							if (isopen == false) {
								isopen = true;
								ev.style.display = \'inline\';
							} else {
								isopen = false;
								ev.style.display = \'none\';
							}
							
							
						}
					}
				</script>
			';
			
			// HTML
			echo '<div id="navbar"><nav><ul class="nav">';
		
				// Now for the NavBar Query code!
				$navquery = "SELECT * FROM `navbars` ORDER BY `norder` ASC";
				$navresult = mysqli_query($db, $navquery);
				
				while ($nav = mysqli_fetch_assoc($navresult)) {
					$navbar = '<li onmouseover="playmsound()"';
					$link = '';
					if ($nav['link'] && $maindirectory != '' && !preg_match('/^http/', strtolower($nav['link']))) {
						$link .= $maindirectory;
					}
					$link .= $nav['link'];
					$navbar .= '><a href="' . $link . '">' . $nav['name'] . '</a>';
					echo $navbar;
					if ($nav['dropdown']) {
						$ddquery = "SELECT * FROM `dropdowns` WHERE `navbarid`='" . $nav['id'] . "'";
						$ddresult = mysqli_query($db, $ddquery);
						if ($ddresult) {
							$rows = mysqli_num_rows($ddresult);
							if ($rows > 0 && $nav['dropdown']) {
									echo '<ul>';
										while ($dd = mysqli_fetch_assoc($ddresult)) {
											$ddlink = '';
											if ($dd['link'] && $maindirectory != '' && !preg_match('/^http/', strtolower($dd['link']))) {
												$ddlink .= $maindirectory;
											}
											$ddlink .= $dd['link'];
											$dropdown = '<li><a href="' . $ddlink . '"';
											if ($dd['newtab']) {
												$dropdown .= ' target=_blank';
											}
											$dropdown .= '>' . $dd['name'] . '</a></li>';
											echo $dropdown;
										}
									echo '</ul></li>';
							}
						}
					} else {
						echo '</li>';
					}
				}
			
			// End of HTML
			echo '</ul></nav></div>';
		}
		/*
			function:
				print_footer
				-- Echo's the Footer.
			Arguements:
				$config - Main Config array (for all the settings).
		*/
		function print_footer($config) {
			// HTML
			echo '<div id="sfooter">';
			
				echo $config['footer'];
				
			// End of HTML
			echo '</div>';
		}
		
		function print_header($cssfiles, $config) {
			echo '<header>';
				if (is_array($cssfiles)) {
					foreach($cssfiles as $value) {
						echo '<link rel="stylesheet" type="text/css" href="' . $value . '" />';
					}
				} else {
					echo '<link rel="stylesheet" type="text/css" href="' . $cssfiles . '" />';
				}
			echo '</header>';
		}

		function print_news($db, $userinfo, $maindirectory, $config) {
			$nquery = "SELECT * FROM `news` ORDER BY `id` DESC LIMIT 0,10";
			$nresult = mysqli_query($db, $nquery);
			
			if ($nresult) {
				$rows = mysqli_num_rows($nresult);
				
				if ($rows > 0) {
					while ($news = mysqli_fetch_assoc($nresult)) {
						$thetime = $news['thetime'];
						if ($thetime <= 200) {
							$thetime = 1413770683;
						}
						if ($thetime > 0) {
							$thetime = date('n-j-o', $thetime);
						}
						$user = $this->c['users']->fetch_profile_info($db, $news['authid']);
						if (!$user) {
							$user = $this->c['users']->fetch_profile_info($db, $config['owner']);
						}
						
						// User is still not coming up. Let's set them to unknown!
						if (!$user) {
							$user['name'] = 'Unknown';
							$user['id'] = '0';
						}
						
						if ($user['color'] != '') {
							$color = '<font color="#' . $user['color'] . '">';
							$color2 = '</font>';
						}
						echo '<div class="newsa">';
							echo '<h2>' . $news['headline'] . '</h2>';
							echo '<div id="newsa_date" style="font-size: 80%; color: #FF0000; text-align: center;">Date: ' . $thetime . '</div>';
							echo '<div id="newsa_date" style="font-size: 80%; color: #FF0000; text-align: center;">By: <a href="' . $maindirectory . 'addons/users/viewuser.php?id=' . $user['id'] . '">' . $color . ucfirst($user['name']) . '</font></a></div>';
							echo '<p>' . $this->c['tools']->Format_Post($news['body'], $config) . '</p>';
							if ($this->c['admin']->is_admin($userinfo['id'], $config)) {
								$this->c['tools']->moderatetools($news['id'], 'news', $maindirectory, $config);
							}
						echo '</div>';
						echo '<br />';
					}
				}
			}
		}
	}
?>