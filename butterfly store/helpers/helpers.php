<?php 

//echo 'helpers';

//display errors function

function display_errors($errors) {
	$display = '<ul class="bg-danger">';
	foreach ($errors as $error) {
		$display .= '<li class="text-danger">'. $error .'</li>';
	}

	$display .= '</ul>';
	return $display;
}

//sanitize function

function sanitize($dirty) {
	return htmlentities($dirty, ENT_QUOTES, "UTF-8");
}

//function deal with money

function money($number) {
	return '$' . number_format($number. 2);
}

//login function 
function login($userID) {
	$_SESSION['BUser'] = $userID; //back session
	global $db;
	$date = date('Y-m-d H:i:s');
	$db->query("UPDATE users SET last_login = '$date' WHERE id = '$userID' ");
	$_SESSION['success'] = 'You nare now logged in. Welcome. ';
	header('Location:index.php');
}

// check session function
function isLoggedIn() {
	if(isset($_SESSION['BUser']) && $_SESSION['BUser'] > 0 ) {
		return true;
	}

	return false;
}

//redirect login function
function loginRedirect($url = 'login.php') {
	$_SESSION['error'] = 'You must log in to access that page.';
	header('Location: ' .$url);
}

// has permission function
function hasPermission($permission = 'admin') {
	global $userData;
	$permissions = explode(',', $userData['permissions']);
	if(in_array($permission, $permissions, true)) {
		return true;
	}
	return false;
}

//redirect permoission function
function permissionRedirect($url = 'login.php') {
	$_SESSION['error'] = 'You must has permissions to access that page.';
	header('Location: ' .$url);
}

//display date
function prettyDate($time) {
	return date('M d, Y h:i A', strtotime($time));
}



//get category function
function getCategory($childID) {
	global $db;
	$id = sanitize($childID);
	$sql = "SELECT p.id AS 'pid', p.category AS 'parent', c.id AS 'cid', c.category AS 'child'
			FROM categories c
			INNER JOIN categories p
			ON c.parent = p.id
			WHERE c.id = '$id'";
	$query = $db->query($sql);
	$category = mysqli_fetch_assoc($query);
	return $category;
}