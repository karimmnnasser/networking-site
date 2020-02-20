<?php


	include "config/connect.php";

	$css  = "layout/css/"; 				// Css Directory
	$js   = "layout/js/"; 				// Js Directory

	$temp = "includes/templates/";		 // Template Directory
	$lang = "includes/Languages/";	 	// Language Directory
	$func = "includes/functions/";		// Functions Directory


	include $temp . 'heder.php';

	include $func . 'functions.php';




  if(!isset($navbar)){ include $temp . 'navbar.php';}
