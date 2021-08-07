<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/butterfly store/core/init.php';

$name = sanitize($_POST['full-name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zip = sanitize($_POST['zip']);
$country = sanitize($_POST['country']);

$errors = array();
$required = array(
	'full-name' => 'Full Name',
	'email' 	=> 'Email',
	'street' 	=> 'Street',
	'street2' 	=> 'Street2',
	'city' 		=> 'City',
	'state' 	=> 'State',
	'zip' 		=> 'Zip Code',
	'country' 	=> 'Country'
);

//check if all requied filled out
foreach ($required as $field => $display) {
	if(empty($_POST[$field]) || $_POST[$field] == '') {
		$errors[] = $display . ' is required.';
	}
}

// check valid email
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$errors[] = 'You must enter valid email.';
}


if(!empty($errors)) {
	echo display_errors($errors);
} else {
	echo "passed";
}

?>