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
	
	class __other  {
		protected $c;
		
		function __construct() {
			global $classes;
			$this->c = $classes;
		}
		/*
			function:
				alert
			Arguements:
				$text - The text you want to send out with Javascript alert.
		*/
		function alert($text) {
			echo '<script type="text/javascript">alert("' . $text . '");</script>';
		}
	}
?>