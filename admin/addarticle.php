<?php
	// Quick thing for links.
	$maindirectory = '../';
	$_SESSION['maindirectory'] = $maindirectory;
	$files = array('config.php', 'addons/execute.php');
	
	foreach ($files as $value) {
		require_once($maindirectory . $value);
	}
	$db = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
	
	
	$filename = 'addarticle.php';
	
	// This returns the user information that is logged in. Will return false if they aren't logged in.
	$userinfo = $classes['users']->fetch_user_info($db);
	
	if (@$classes['tools']->check($_GET['filename'])) {
		$file = $maindirectory . $_GET['filename'];
	} else {
		$file = false;
	}
	
	// ADMIN
	$admin = $classes['admin']->is_admin($userinfo['id'], $config);
	
	if (@$classes['tools']->check($_POST['aname']) && @$classes['tools']->check($_POST['abody'])) {
		$name = $_POST['aname'];
		$body = $_POST['abody'];
		$thetime = time();
		
		foreach ($config['t_options'] as $value) {
			if ($value['HTML']) {
				$name = htmlentities($name);
				$body = htmlentities($body);
			}
			if ($value['autobreak']) {
				$body = str_replace(' ', '&nbsp', $body);
				$name = nl2br($name);
				$body = nl2br($body);
			}
			if ($value['secure']) {
				$name = mysqli_real_escape_string($db, $name);
				$body = mysqli_real_escape_string($db, $body);
			}
		}
		
		if ($userinfo) {
			$query = "INSERT INTO `news`(headline, body, thetime, authid) VALUES 
																				(
																					'" . $name . "',
																					'" . $body . "',
																					'" . $thetime . "',
																					'" . $userinfo['id'] . "'
																				)";
			$result = mysqli_query($db, $query);
			
			if ($result) {
				$classes['other']->alert('Successfully added the article!');
			} else {
				$classes['other']->alert('Did not successfully add the article!');
			}
		}
		
		
	}
	
	// Made by: [GFL] Roy (Christian Deacon)
	// Notes:
	/*
		This was my first advanced website I actually made. It was started on 9-14-14. I got some information (especially the drop downs and nav bars) from Google but changed them up a bit and made them work correctly.
		Point is, not entirely my custom code, but this website is optimized and unique by me.
	*/
?>

<html>
	<audio id="menusound1" src="<?php echo $maindirectory; ?>sounds/ms1.wav" preload="auto"></audio>
	<audio id="menusound2" src="<?php echo $maindirectory; ?>sounds/ms2.wav" preload="auto"></audio>
	<header>
		<link rel="stylesheet" type="text/css" href="<?php echo $maindirectory; ?>styling.css" />
		<link rel="stylesheet" type="text/css" href="forums.css" />
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
						echo 'Welcome back <a href="' . $maindirectory . 'addons/users/viewuser.php?id=' . $userinfo['id'] . '">' . ucfirst($userinfo['name']) . '</a>! <a href="' . $maindirectory . 'addons/users/login.php?logout">Logout</a>!';
					} else {
						echo '<a href="' . $maindirectory . 'addons/users/login.php">Login</a> | <a href="' . $maindirectory . 'addons/users/register.php">Register</a>';
					}
				?>
			</div>
			<h1>Add An Article</h1>
			<div id="addarticle">
				<?php
					if ($admin) {
						echo '<form action="' . $filename . '" method="POST">';
							echo 'Article Name<br /><input type="text" name="aname" size="70" /><br /><br />';
							echo 'Article Body<br /><textarea name="abody" rows="10" cols="50">Today is a good day!</textarea><br /><br />';
							echo '<input type="submit" value="Add Article!" />';
						echo '</form>';
					} else {
						echo 'This page is only available for admins!';
					}
				?>
			</div>

		</div>
		
		<div id="sfooter">
			<?php
				echo $config['footer'];
			?>
		</div>
	</body>
</html>