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

	class __users  {
		protected $c;
		
		function __construct() {
			global $classes;
			$this->c = $classes;
		}
		function display_profile($db, $id, $profileinfo, $filename, $editmode, $userinfo, $maindirectory, $config) {
			// Javascript
			echo '
				<script type="text/javascript">
					function editme() {
						window.location.href = "' . $filename . '?id=' . $id . '&editmode";
					}
				</script>
			';
			if ($profileinfo) {
				$config['rep'] = '';
				if ($profileinfo['rep'] > 0) {
					$color = '1EFF00';	// Lime Green
				} else {
					$color = 'FF0000';	// Red
				}
				$config['rep'] = '<font color="#' . $color . '">' . $profileinfo['rep'] . '</font>';
				echo '<h1>' . ucfirst($profileinfo['name']) . '\'s Profile (ID: ' . $profileinfo['id'] . ')</h1>';
				echo '<div id="profiledata">';
					if ($editmode == 0) {
						echo 'About: ' . $profileinfo['about'] . '<br />';
						echo 'Age: ' . $profileinfo['age'] . '<br />';
						echo 'Website URL: <a href="' . $profileinfo['website'] . '" target=_blank>' . $profileinfo['website'] . '</a><br />';
						echo 'Bio: ' . $profileinfo['bio'] . '<br />';
						echo 'Rep: ' . $config['rep'] . '<br/ >';
						if ($profileinfo['id'] == $userinfo['id'] && $editmode == 0) {
							echo '<form action="' . $filename . '" method="POST">';
								echo '<input type="button" name="edit" value="Edit!" onclick="editme();" /><br />';
							echo '</form>';
						}
					} else {
						echo '<form action="' . $filename . '" method="POST">';
							echo 'About: <br /><textarea name="about">' . $profileinfo['about'] . '</textarea><br />';
							echo 'Age: <br /><input type="text" name="age" value="' . $profileinfo['age'] . '" /><br />';
							echo 'Website: <br /><input type="text" name="website" value="' . $profileinfo['website'] . '" /><br />';
							echo 'Bio: <br /><textarea name="bio">' . $profileinfo['bio'] . '</textarea><br />';
							echo 'Signature: <br /><textarea name="signature">' . $profileinfo['signature'] . '</textarea><br />';
							echo 'Rep: ' . $config['rep'] . '<br />';
							echo '<input type="submit" name="editdata" value="Edit!" /><br />';
							echo '<input type="hidden" name="id" value="' . $profileinfo['id'] . '">';
						echo '</form>';
					}
				echo '</div>';
			} else {
				echo '<h1>INVALID ID/USER</h1>';
			}
		}
		
		function fetch_user_info ($db) {
			// The Defaults
			$ln = false;
			$id = 0;
			$user = '';
			$pass = '';
			$suser = '';
			$spass = '';
			$ruser = '';
			$rpass = '';
			
			if (isset($_COOKIE['u_id'])) {
				$id = $_COOKIE['u_id'];
			}
			
			if (isset($_COOKIE['u_user'])) {
				$suser = $_COOKIE['u_user'];
				$user = $_COOKIE['u_user'];
			}
			
			if (isset($_COOKIE['u_pass'])) {
				$spass = $this->c['tools']->secure($_COOKIE['u_pass'], $id);
				$pass = $_COOKIE['u_pass'];
			}
			
			if ($id > 0 && $suser != '' && $spass != '') {
				$query = "SELECT * FROM `users` WHERE `id`='" . $id . "'";
				$result = mysqli_query($db, $query);
				
				if ($result) {
					$rows = mysqli_num_rows($result);
					
					if ($rows > 0) {
						while ($temp = mysqli_fetch_assoc($result)) {
							$ruser = $temp['username'];
							$rpass = $this->c['tools']->secure($temp['password'], $id);
						}
					}
				}
			}
			
			if ($id && $suser && $spass && $suser == $ruser && $spass = $rpass) {
				$ln['name'] = $user;
				$ln['password'] = $pass;
				$ln['id'] = $id;
			}
			
			// Returns user information or returns false (not logged in)
			return $ln;
		}
		
		function register_user($db, $user, $pass, $email) {
			$success = false;
			
			if (!isset($user) || !isset($pass) || !isset($email)) {
				// Straight done, not successful.
				return $success;
			}
			
			$tuser = mysqli_real_escape_string($db, htmlentities($user));
			
			$temail = mysqli_real_escape_string($db, htmlentities($email));
			
			// The check for multiple names or emails
			$cquery = "SELECT * FROM `users`";
			$cresult = mysqli_query($db, $cquery);
			
			if ($cresult) {
				while ($l = mysqli_fetch_assoc($cresult)) {
					if (strtolower($l['username']) == strtolower($tuser)) {
						$this->c['other']->alert('User: ' . $tuser . ' already exists! Please choose a different username!');
						return false;
					}
					
					if (strtolower($l['email']) == strtolower($temail)) {
						$this->c['other']->alert('E-mail: ' . $temail . ' already exists! Please choose a different E-Mail!');
						return false;
					}
				}
			}
			
			$query = "INSERT INTO `users` (username, email) VALUES
																		(
																			'" . $tuser . "',
																			'" . $temail . "'
																		)";
			$result = mysqli_query($db, $query);
			
			if ($result) {
				$id = mysqli_insert_id($db);
				$tpass = mysqli_real_escape_string($db, $this->c['tools']->secure(htmlentities($pass), $id));
				$query = "UPDATE `users` SET `password`='" . $tpass . "' WHERE `id`='" . $id . "'";
				$result = mysqli_query($db, $query);
				
				if ($result) {
					$success = true;
					echo '<script type="text/javascript">alert("Registered successfully!");</script>';
				} else {
					die ('Did not register successfully. MYSQL Error: ' . mysqli_error($db));
				}
			} else {
				$success = false;
				die ('Did not register successfully. MYSQL Error: ' . mysqli_error($db));
			}
			
			return $success;
		}
		
		function login_user($db, $user, $pass, $redirecturl) {
			$li = false;
			
			$id = 0;
			$ruser = '';
			$rpass = '';
			
			$query = "SELECT * FROM `users` WHERE `username`='" . $user . "'";
			$result = mysqli_query($db, $query);
			
			if ($result) {
				$rows = mysqli_num_rows($result);
				
				if ($rows > 0) {
					while ($temp = mysqli_fetch_assoc($result)) {
						$id = $temp['id'];
						$rpass = $temp['password'];
						if ($this->c['tools']->secure($pass, $id) == $rpass) {
							$li = true;
							
							// Set the cookies!
							setcookie('u_id', $id, time()+999999, '/');
							setcookie('u_user', $user, time()+999999, '/');
							setcookie('u_pass', $pass, time()+999999, '/');
						}
					}
				}
			}
			if ($li) {
				echo '<script type="text/javascript">alert("Logged in successfully!");</script>';
				header('Location: ' . $redirecturl);
			} else {
				echo '<script type="text/javascript">alert("Wrong Username or Password");</script>';
			}
			
			return $li;
		}
		
		function logout_user($redirecturl) {
			if (isset($_COOKIE['u_id'])) {
				setcookie('u_id', '', time()-999999, '/');
			}
			
			if (isset($_COOKIE['u_user'])) {
				setcookie('u_user', '', time()-999999, '/');
			}
			
			if (isset($_COOKIE['u_pass'])) {
				setcookie('u_pass', '', time()-999999, '/');
			}
			
			header('Location: ' . $redirecturl);
		}
		
		function is_valid($db, $id) {
			$isvalid = false;
			if ($id > 0) {
				$query = "SELECT * FROM `users` WHERE `id`='" . $id . "'";
				$result = mysqli_query($db, $query);
				
				if ($result) {
					$rows = mysqli_num_rows($result);
					
					if ($rows > 0) {
						while ($t = mysqli_fetch_assoc($result)) {
							$isvalid['name'] = $t['username'];
							$isvalid['email'] = $t['email'];
						}
					}
				}
			}
			return $isvalid;
		}
		
		function fetch_profile_info($db, $id) {
			$isvalid = false;
			$other = $this->is_valid($db, $id);
			if ($other) {
				$query = "SELECT * FROM `userinfo` WHERE `uid`='" . $id . "'";
				$result = mysqli_query($db, $query);
				
				if ($result) {
					$rows = mysqli_num_rows($result);
					
					if ($rows > 0) {
						while ($l = mysqli_fetch_assoc($result)) {
							$isvalid['id'] = $l['uid'];
							$isvalid['age'] = $l['age'];
							$isvalid['about'] = $l['about'];
							$isvalid['bio'] = $l['bio'];
							$isvalid['website'] = $l['website'];
							$isvalid['color'] = $l['color'];
							$isvalid['name'] = $other['name'];
							$isvalid['email'] = $other['email'];
							$isvalid['rep'] = $l['rep'];
							$isvalid['signature'] = $l['signature'];
						}
					} else {
						$query2 = "INSERT INTO `userinfo` (uid, age, about, website, bio) VALUES ('" . $id . "','','','','')";
						$result2 = mysqli_query($db, $query2);
						
						if ($result2) {
							$isvalid = true;
						}
					}
				}
			}
			return $isvalid;
		}
	}
?>