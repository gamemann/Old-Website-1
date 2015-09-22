<?php
	// Quick thing for links.
	$maindirectory = '../../';
	$_SESSION['maindirectory'] = $maindirectory;
	$filename = 'register.php';
	
	$files = array('config.php', 'addons/execute.php');
	
	foreach ($files as $value) {
		require_once($maindirectory . $value);
	}
	$db = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
	
	
	// This returns the user information that is logged in. Will return false if they aren't logged in.
	$userinfo = $classes['users']->fetch_user_info($db);
	
	if (@$classes['tools']->check($_POST['username']) && @$classes['tools']->check($_POST['password']) && @$classes['tools']->check($_POST['email'])) {
		$classes['users']->register_user($db, $_POST['username'], $_POST['password'], $_POST['email']);
	}	
	
	// Made by: [GFL] Roy (Christian Deacon)
	// Notes:
	/*
		Register page.
	*/
?>

<html>
	<audio id="menusound1" src="<?php echo $maindirectory; ?>sounds/ms1.wav" preload="auto"></audio>
	<audio id="menusound2" src="<?php echo $maindirectory; ?>sounds/ms2.wav" preload="auto"></audio>
	<header>
		<link rel="stylesheet" type="text/css" href="<?php echo $maindirectory; ?>styling.css" />
		<script type="text/javascript">
			var isopen = false;
			function playmsound() {
				document.getElementById('menusound1').play();
			}
			
			function showmenu(num) {
				document.getElementById('menusound2').play();
				ev = document.getElementById("lol");
				if (ev) {
					if (isopen == false) {
						isopen = true;
						ev.style.display = 'inline';
					} else {
						isopen = false;
						ev.style.display = 'none';
					}
					
					
				}
			}
		</script>
	</header>
	<body>
		<div id="navbar">
			<nav>
			  <ul class="nav">
				<?php
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
												if ($dd['link'] && $maindirectory != '' && !preg_match('/^http/', strtolower($dd['link']))) {
													$link .= $maindirectory;
												}
												$link .= $dd['link'];
												$dropdown = '<li><a href="' . $link . '"';
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
						
				?>
			  </ul>
			</nav>
		</div>
		<div id="maincontent">
			<div id="usercp">
				<?php
					if ($userinfo) {
						// User is logged in...
					} else {
						echo '<a href="' . $maindirectory . 'addons/users/login.php">Login</a> | <a href="' . $maindirectory . 'addons/users/register.php">Register</a>';
					}
				?>
			</div>
			<h1>Register!</h1>
			<?php
				if ($userinfo) {
					echo 'You are alreadly logged in!';
				} else {
					echo '<div class="register">';
						echo '<form action="' . $filename . '" method="POST">';
							echo 'Username:<br /><input type="text" name="username" /><br />';
							echo 'Password:<br /><input type="password" name="password" /><br />';
							echo 'E-Mail:<br /><input type="text" name="email" /><br />';
							echo '<input type="submit" value="Register!">';
						echo '</form>';
					echo '</div>';
				}
			?>
			
		</div>
		
		<div id="sfooter">
			<?php
				echo $config['footer'];
			?>
		</div>
	</body>
</html>