<?php

// CONFIG
$config['footer'] =
'
<p>Website made by: Roy (Christian Deacon).</p>
<p>Website made on: 9-14-14.</p>
<p>Website Structure: Advance.</p>
';

$config['host'] = 'localhost';
$config['user'] = 'root';
$config['pass'] = 'mypass';
$config['name'] = 'site1';

$config['debug'] = false;

$config['owner'] = 8;

// New Topic Options
$config['t_options'] = array(
	array
	(
		'secure' => '1',
		'HTML' => '1',
		'autobreak' => '1'
	),
);

$config['bbcodes'] = array (
	array
	(
		'display' => 'Code',
		'code' => 'code',
		'description' => 'Wraps text with a nice coding-like area.',
		'example' => '<?php echo \'An Example\'; ?>',
		'format' => '%s',
		'special' => '1'
	),
	array
	(
		'display' => 'Quote',
		'code' => 'quote',
		'description' => 'Quote users (used to reply to others).',
		'example' => 'Yes, my day was awesome!',
		'additional' => 'name',
		'format' => '%name said: <br /> %s',
		'special' => '0'
	),
	
	array
	(
		'display' => 'Image',
		'code' => 'img',
		'description' => 'Add pictures to the site!.',
		'example' => 'http://gflclan.com/gamemann/screenshots/Screenshot_10-22-14-18-51-34.png',
		'format' => '<div class="images"><a href="%s" target=_blank><img src="%s" width="500" height="500" /></a></div>',
		'special' => '0'
	),
	
	array
	(
		'display' => 'URL',
		'code' => 'url',
		'description' => 'Add URL\'s to the site!.',
		'example' => 'My Community!',
		'additional' => 'url',
		'format' => '<div class="urls"><a href="%url" target=_blank>%s</a></div>',
		'special' => '0'
	)
);

// Admins (User ID's)
$config['admins'] = array('8', '14', '16');

// Editing files
$config['file_extensions'] = array('.php', '.css', '.html', '.js');
$config['file_blacklist'] = array('admin\editcss.php');

// Games
$config['game_blacklist'] = array();
$config['game_iexts'] = array('png', 'jpg', 'gif');
$config['game_gexts'] = array('swf');
$config['game_maxsize'] = 100; // 100 megabytes

// Rep
// How much rep you get for each action.
$config['rep']['reply'] = 1;
$config['rep']['thread'] = 2;
$config['rep']['game'] = 5;

// Moderate Tools
$config['moderatetools'] = array(
	array
	(
		'name' => 'Delete',
		'sname' => 'del'
	),

	array
	(
		'name' => 'Delete & ban user',
		'sname' => 'delban'
	),
	
	array
	(
		'name' => 'Delete & delete user',
		'sname' => 'deldel'
	)
	
);

$config['moderatetypes'] = array(
	array
	(
		'type' => 'news',
		'sql' => 'news'
	),
	
	array
	(
		'type' => 'ftopic',
		'sql' => 'forumthreads'
	),
	
	array
	(
		'type' => 'freply',
		'sql' => 'forumreplies'
	),
	
	array
	(
		'type' => 'games',
		'sql' => 'games'
	)
);

// Array => 'addons/classes/extension.php'
$config['extensions'] = array(
	// Main extensions/classes
	'admin',
	'forums',
	'games',
	'other',
	'page',
	'tools',
	'users',
	
	// Project extensions/classes
	'projects/project_pg',
	'projects/project_rates'
);
?>