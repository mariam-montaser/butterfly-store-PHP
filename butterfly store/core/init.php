<?php

$db = mysqli_connect('localhost', 'root', '', 'butterfly_store');

if (mysqli_connect_errno()) {
	echo 'faild to connet' . mysqli_connect_error();
	die();
}

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/butterfly store/config.php';
require_once BASEURL . 'helpers/helpers.php';
require BASEURL . '/vendor/autoload.php';


//define('BASEURL', '/butterfly store/');

//check cart
$cartID = '';
if(isset($_COOKIE[CART_COOKIE])) {
	$cartID = sanitize($_COOKIE[CART_COOKIE]);
}

//check session

if(isset($_SESSION['BUser'])) {
	$userID = $_SESSION['BUser'];
	$query = $db->query("SELECT * FROM users WHERE id = '$userID'");
	$userData = mysqli_fetch_assoc($query);
	$fullName = explode(' ', $userData['full_name']);
	$userData['first'] = $fullName[0];
	$userData['last'] = $fullName[1];
}

if(isset($_SESSION['success'])) {
	echo "<div class='bg-success'><p class='text-success text-center'>" . $_SESSION['success'] ."</p></div>";
	unset($_SESSION['success']);
}

if(isset($_SESSION['error'])) {
	echo "<div class='bg-danger'><p class='text-danger text-center'>" .$_SESSION['error'] ."</p></div>";
	unset($_SESSION['error']);
}

//session_destroy();

