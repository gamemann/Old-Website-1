<?php
	/*
		Every class file should be set up with $this->c['tools'] template.
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
	
	class __forums  {
		protected $c;
		
		function __construct() {
			global $classes;
			$this->c = $classes;
		}
		
		function print_forums($db, $id, $userinfo, $maindirectory, $config) {
			// The start to the threads!
			$query = "SELECT * FROM `forums` WHERE `id`='" . $id . "'";
			$result = mysqli_query($db, $query);
			
			if ($result) {
				$rows = mysqli_num_rows($result);
				
				if ($rows > 0) {
					while ($c = mysqli_fetch_assoc($result)) {
						echo '<div class="fcat">';
							echo $c['name'];
						echo '</div>';
					}
				}
			}
			$query = "SELECT * FROM `forumthreads` WHERE `fid`='" . $id . "' ORDER BY `id` DESC";
			$result = mysqli_query($db, $query);
			
			if ($result) {
				$rows = mysqli_num_rows($result);
				
				if ($rows > 0) {
					$trows = 0;
					while ($t = mysqli_fetch_assoc($result)) {
						$trows++;
						$user = $this->c['users']->fetch_profile_info($db, $t['authid']);
						$replies = 0;
						$lp = '';
						$tempq = "SELECT * FROM `forumreplies` WHERE `tid`='" . $t['id'] . "' ORDER BY `id` DESC";
						$tempr = mysqli_query($db, $tempq);
						
						if ($tempr) {
							$replies = mysqli_num_rows($tempr);
							
							if ($replies > 0) {
								$ran = 0;
								while ($temp = mysqli_fetch_assoc($tempr)) {
									$ran++;
									if ($ran == $replies) {
										$lp = $this->c['users']->fetch_profile_info($db, $temp['authid']);
									}
								}
							}
						}
						
						if ($user != '') {
							echo '<div id="t_' . $t['id'] . '" class="threads"';
							if ($trows == $rows) {
								echo ' style="border-radius: 0px 0px 15px 15px;"';
							}
							echo '>';
								// Now for the over-looker
								$color = '';
								$color2 = '';
								if ($lp != '') {
									if ($lp['color'] != '') {
										$color = '<font color="#' . $lp['color'] . '">';
										$color2 = '</font>';
									}
								}
								if ($lp == '') {
									$lp['name'] = $user['name'];
									if ($user['color'] != '') {
										$color = '<font color="#' . $user['color'] . '">';
										$color2 = '</font>';
									}
								}
								echo '<div class="threads_name"><a href="viewtopic.php?id=' . $t['id'] . '">' . $t['topic'] . '</a></div>';
								echo '<div class="threads_lp">Latest Post By:
								<br /><a href="' . $maindirectory . 'addons/users/viewuser.php?id=' . $user['id'] . '">' . $color . ucfirst($lp['name']) . $color2 . '</a></div>';
								$color = '';
								$color2 = '';
								if ($user['color'] != '') {
									$color = '<font color="#' . $user['color'] . '">';
									$color2 = '</font>';
								}
								echo '<div class="threads_info">Replies: ' . $replies . '
								<br />
								By: <a href="' . $maindirectory . 'addons/users/viewuser.php?id=' . $user['id'] . '">' . $color . ucfirst($user['name']) . $color2 . '</a></div>';
							echo '</div>';
							
						}
					}
				} else {
					echo '<div id="error">No threads to display!</div>';
				}
			}
			if ($userinfo) {
				echo '<div class="new_thread"><a href="newthread.php?id=' . $id . '">New Topic</a></div><br />';
			}
		}
		
		function print_newthread($db, $id, $filename, $userinfo, $maindirectory, $config) {
			echo '<div class="newthread">';
				if ($userinfo) {
					echo '<form action="' . $filename . '" method="POST">';
						echo 'Title: <br /><input type="text" name="title" size="100" /><br /><br />';
						echo 'Content: <br /><textarea rows="30" cols="100" name="content" id="content">This is your post!</textarea><br />';
						echo '<input type="hidden" name="id" value="' . $id . '" />';
						echo '<input type="submit" name="Submit" value="Post!" /><br /><br />';
						echo 'Options: <br />';
						echo '<div id="optionsformat" style="color: #FFF700; font-weight: bold;">';
							foreach ($config['bbcodes'] as $value) {
								echo '<div style="background-color: rgba(33, 33, 33, 0.5); border: 1px solid #000000; margin-left: 25%; margin-right: 25%;">';
									echo '<h1><a href="" style="text-decoration: none;">' . ucfirst($value['display']) . '</a></h1>';
									echo 'Usage:<br />';
									echo '[' . $value['code'];
									if (isset($value['additional']) && !empty($value['additional'])) {
										$additional = preg_split('/,/', $value['additional']);
										for($i = 0; $i < count($additional); $i++) {
											if ($additional[$i]) {
												echo ' ' . $additional[$i] . '="' . $additional[$i] . '"';
											}
										}
										
									}
								if ($value['format']) {
									$temp = $value['format'];
									if (isset($value['additional']) && !empty($value['additional'])) {
										$additional = preg_split('/,/', $value['additional']);
										for ($i=0; $i < count($additional); $i++) {
											if ($additional[$i]) {
												$num = $i+1;
												$temp = str_replace("%$num", ucfirst($additional[$i]), $temp);
											}
										}
									}
									$temp = str_replace('%s', $value['example'], $temp);
									$text = $temp;
									if ($value['special']) {
										$text = htmlspecialchars($text);
									}
								} else {
									$text = $value['example'];
								}
									echo ']' . htmlspecialchars($value['example']) . '[/' . $value['code'] . ']<br />';
									echo 'Example:<br />';
									echo '<div class="bbcode_' . $value['code'] . '" style="margin-left: 25%; margin-right: 25%;">' . $text . '</div>';
									echo '- ' . $value['description'] . '<br />';
								echo '</div>';
							}
						echo '</div>';
					echo '</form>';
				} else {
					echo 'You must be logged in to make new topics!';
				}
			echo '</div>';
		}
		
		function print_categories($db, $userinfo, $maindirectory, $config) {
			// The start of the forums!
			$query = "SELECT * FROM `forumcategories`";
			$result = mysqli_query($db, $query);
			
			if ($result) {
				$rows = mysqli_num_rows($result);
				
				if ($rows > 0) {
					while ($c = mysqli_fetch_assoc($result)) {
						
						echo '<div id="fcat_' . $c['id'] . '" class="fcat">';
							echo $c['name'];
						echo '</div>';
						
						$fquery = "SELECT * FROM `forums` WHERE `catid`='" . $c['id'] . "'";
						$fresult = mysqli_query($db, $fquery);
						
						if ($fresult) {
							$rows = mysqli_num_rows($fresult);
							
							if ($rows > 0) {
								$crow = 0;
								while ($f = mysqli_fetch_assoc($fresult)) {
									$threads = 0;
									$replies = 0;
									
									$tquery = "SELECT * FROM `forumthreads` WHERE `fid`='" . $f['id'] . "'";
									$tresult = mysqli_query($db, $tquery);
									
									if ($tresult) {
										$threads = mysqli_num_rows($tresult);
										
										if ($threads > 0) {
											while ($t = mysqli_fetch_assoc($tresult)) {
												$mquery = "SELECT * FROM `forumreplies` WHERE `tid`='" . $t['id'] . "'";
												$mresult = mysqli_query($db, $mquery);
												
												if ($mresult) {
													$replies += mysqli_num_rows($mresult);
												}
											}
										}
									}
									$crow++;
									echo '<div id="f_' . $f['id'] . '" class="forum"';
									if ($crow == $rows) {
										echo ' style="border-radius: 0px 0px 15px 15px;"';
									}
									echo '>';
										echo '<div class="forum_name"><a href="viewforum.php?id=' . $f['id'] . '">' . $f['name'] . '</a></div>';
										echo '<div class="forum_lp">Replies: ' . $replies . '</div>';
										echo '<div class="forum_info">Threads: ' . $threads . '</div>';
										
									echo '</div>';
								}
							} else {
								echo '* No Forums In This Category *';
							}
						}
					}
				} else {
					echo '<div id="error">There are no forums to display</div>';
				}
			}
		}
		function print_thread($db, $id, $filename='viewtopic.php', $userinfo, $maindirectory, $config) {
			$query = "SELECT * FROM `forumthreads` WHERE `id`='" . $id . "'";
			$result = mysqli_query($db, $query);
			
			if ($result) {
				$rows = mysqli_num_rows($result);
				
				if ($rows > 0) {
					while($t = mysqli_fetch_assoc($result)) {
			
						$topic = $t['topic'];
						if ($t['thetime'] > 10) {
							$thetime = date('D, F dS | g:i A', $t['thetime']);
						} else {
							$thetime = 'Unknown';
						}
						//echo '<h1>Topic (ID: ' . $t['id'] . ')</h1>'; // We don't need this anymore, it just makes it look bad!
						echo '<br /><br /><div id="b_thread">';
						$tuser = $this->c['users']->fetch_profile_info($db, $t['authid']);
						$body = $this->c['tools']->Format_Post($t['body'], $config);
						//$body = str_replace(' ', '&nbsp', $body);
						if ($tuser) {
							// Get the Topic User's Name color.
							$color = '';
							$color2 = '';
							if ($tuser['color'] != '') {
								$color = '<font color="#' . $tuser['color'] . '">';
								$color2 = '</font>';
							}
							
							echo '<div class="thread">';
								// Now for the information on the thread.
								echo '<div id="thread_all" class="thread_info">';
										echo '<div class="thread_info_t">' . $topic . '</div>';
										echo 'By: <a href="' . $maindirectory . 'addons/users/viewuser.php?id=' . $tuser['id'] . '">' . $color . ucfirst($tuser['name']) . $color2 . '</a><br />';
										echo 'Time: ' . $thetime;
								echo '</div>';
								echo '<div id="thread_all" class="thread_body">';
									if (isset($_GET['editmode']) && $tuser['id'] == $userinfo['id']) {
										echo '<form action="' . $filename . '" method="GET" style="text-align: center;">';
											echo '<input type="hidden" name="id" value=' . $id . ' />';
											echo '<textarea rows="50" cols="50" name="body">' . $body . '</textarea>';
											echo '<input type="submit" name="save_submit" value="Save!" />';
										echo '</form>';
									} else {
										$body = str_replace('<br />', '', $body);
										echo nl2br($body) . '<br />';
									}
									if ($tuser['id'] == $userinfo['id']) {
										// Editor.
										if (!isset($_GET['editmode'])) {
											echo '<form action="' . $filename . '" method="GET" style="text-align: center;">';
												echo '<input type="hidden" name="id" value=' . $id . ' />';
												echo '<input type="hidden" name="editmode" value="1" />';
												echo '<input type="submit" name="editmode_submit" value="Edit!" />';
											echo '</form>';
										}
									}
									
									// Extra addons
									$tempinfo = $this->c['users']->fetch_profile_info($db, $userinfo['id']);
							
									if ($tempinfo['signature'] != '') {
										echo '<hr />';
										echo $tempinfo['signature'];
									}
									if ($this->c['admin']->is_admin($userinfo['id'], $config)) {
										$this->c['tools']->moderatetools($t['id'], 'ftopic', $maindirectory, $config);
									}
									
									
								echo '</div>';

							echo '</div>';
						echo '</div>';
						}
						$thread['topic'] = $topic;
						$thread['id'] = $id;
						$thread['userinfo'] = $userinfo;
					}
				}
			}
			
			return $thread;
		}
		
		// Topic Replies
		function print_replies($db, $id, $topic, $filename='viewtopic.php', $userinfo, $maindirectory, $config) {
			$query = "SELECT * FROM `forumreplies` WHERE `tid`='" . $id . "'";
			$result = mysqli_query($db, $query);
			
			if ($result) {
				$rows = mysqli_num_rows($result);
				
				if ($rows > 0) {
					while ($r = mysqli_fetch_assoc($result)) {
						if ($r['thetime'] > 10) {
							$thetime = date('D, F dS | g:i A', $r['thetime']);
						} else {
							$thetime = 'Unknown';
						}
						
						echo '<br /><br /><div id="b_thread">';
						$tuser = $this->c['users']->fetch_profile_info($db, $r['authid']);
						$body = $this->c['tools']->Format_Post($r['body'], $config);
						//$body = str_replace(' ', '&nbsp', $body);
						if ($tuser) {
							// Get the Topic User's Name color.
							$color = '';
							$color2 = '';
							if ($tuser['color'] != '') {
								$color = '<font color="#' . $tuser['color'] . '">';
								$color2 = '</font>';
							}
							
							echo '<div class="thread">';
								// Now for the information on the thread.
								echo '<div id="thread_all" class="thread_info">';
										echo '<div class="thread_info_t">RE: ' . $topic . '</div>';
										echo 'By: <a href="' . $maindirectory . 'addons/users/viewuser.php?id=' . $tuser['id'] . '">' . $color . ucfirst($tuser['name']) . $color2 . '</a><br />';
										echo 'Time: ' . $thetime;
								echo '</div>';
								echo '<div id="thread_all" class="thread_body">';
									echo $body . '<br />';
									$tempinfo = $this->c['users']->fetch_profile_info($db, $tuser['id']);
									if ($tempinfo['signature'] != '') {
										echo '<hr />';
										echo $tempinfo['signature'];
									}
									if ($this->c['admin']->is_admin($userinfo['id'], $config)) {
										$this->c['tools']->moderatetools($r['id'], 'freply', $maindirectory, $config);
									}
								echo '</div>';
							echo '</div>';
						
						}
						echo '</div>';
					}
				} else {
					echo '<div class="thread_noreplies">There are no replies on this topic yet!</div>';
				}
			}
		}
	}	
?>