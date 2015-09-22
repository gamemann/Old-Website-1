<?php
	// Quick thing for links.
	$maindirectory = '../../';
	$_SESSION['maindirectory'] = $maindirectory;
	$files = array('config.php', 'addons/execute.php');
	
	foreach ($files as $value) {
		require_once($maindirectory . $value);
	}
	$db = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
	
	
	$filename = 'index.php';
	
	// This returns the user information that is logged in. Will return false if they aren't logged in.
	$userinfo = $classes['users']->fetch_user_info($db);
	
	// Made by: [GFL] Roy (Christian Deacon)
	// Notes:
	/*
		This was my first advanced website I actually made. It was started on 9-14-14. I got some information (especially the drop downs and nav bars) from Google but changed them up a bit and made them work correctly.
		Point is, not entirely my custom code, but this website is optimized and unique by me.
	*/
	echo '<html>';
		$classes['page']->load_sounds($maindirectory);
		$classes['page']->print_header(array($maindirectory . 'styling.css', 'rates.css'), $config);
		echo '<body>';
			$classes['page']->print_navbar($db, $userinfo, $maindirectory, $config);
			echo '<div id="maincontent">';
				$classes['page']->print_cp($userinfo, $maindirectory, $config);
				echo '<h1>Rates</h1>';
					echo '<div id="rates">';
						if (isset($_POST['speed']) && isset($_POST['tickrate']) && !empty($_POST['speed']) && !empty($_POST['tickrate']) && isset($_POST['cpuspeed']) && !empty($_POST['cpuspeed'])) {
							// Is set.
							$speed = $_POST['speed'];
							$tickrate = $_POST['tickrate'];
							$cpuspeed = $_POST['cpuspeed'] * 2;
							$defaultsplitpacket = 15000;
							
							// Now for the math!
							$maxrate = $speed * 5000;
							$minrate = $maxrate / 2;
							$maxupdates = $tickrate;
							$minupdates = $tickrate - 2;
							$splitpacket = $defaultsplitpacket * $cpuspeed;
							
							echo 'Rates (copy to server.cfg): <br />';
							if ($speed >= 25.00) {
								$maxrate = 0;
								$minrate = 100000;
							} else {
								if ($tickrate <= 33) {
									$maxrate *= 1.3;
									$minrate *= 1.3;
								}
							}
							
							if ($maxrate < 25000 && $maxrate != 0) {
								// No maxrate should be under 25000, no matter what. In all honesty, sv_maxrate 0 is the absolute best.
								$maxrate = 25000;
								$minrate = 20000;
							}
							
							if ($speed < 10.00) {
								echo '//<font color="yellow">You\'re upload speed will not provide a quality experience for anybody connecting to your game server. We highly recommend you use a quality game host such as <a href="http://NFOServers.com/">NFOServers.com</a>.</font><br />';
							}
							
							echo 'sv_maxrate ' . $maxrate . '<br />';
							echo 'sv_minrate ' . $minrate . '<br />';
							echo 'sv_maxupdaterate ' . $maxupdates . '<br />';
							echo 'sv_minupdaterate ' . $minupdates . '<br />';
							echo 'net_splitpacket_maxrate ' . $splitpacket . '<br />';
							echo 'net_maxcleartime 0.01 <br />';
							echo 'sv_parallel_sendsnapshot 1<br />';
							echo 'sv_parallel_packentities 1<br />';
							echo '// Uncomment (remove "//") the line below this if you are using these rates for a CS:GO server.<br />';
							echo '//sv_force_transmit_players 0<br /><br />';
							
						}
				echo '</div>';
				echo '<div id="theforms">';
					echo '<form action="' . $filename . '" method="POST">';
						echo 'Upload speed (in megabits): <br /><input type="text" name="speed" value="10.00" />';
						echo '<br />';
						echo 'Tickrate: <br /><input type="text" name="tickrate" value="66" />';
						echo '<br />';
						echo 'CPU Speed (GHz): <br /><input type="text" name="cpuspeed" value="3.00" />';
						echo '<br />';
						echo '<input type="submit" name="submitrates" value="Get Rates!" />';
					echo '</form>';
				echo '</div>';
				
				echo '<div id="notes">';
					echo '<ul>';
						echo '<li>Do not host servers off of your own computer, please rent a server somewhere such as <a href="http://NFOServers.com/" target=_blank>NFOServers.com</a>.</li>';
						echo '<li>We recommend you rent from a provider with gigabit connection at least.</li>';
						echo '<li>These rates are based off of a regular server (no mods).</li>';
						echo '<li>Find your upload speed at <a href="http://speedtest.net" target=_blank>Speedtest.net</a>.</li>';
					echo '</ul>';
				echo '</div>';
			echo '</div>';
			
			$classes['page']->print_footer($config);
		echo '</body>';
	echo '</html>';
?>