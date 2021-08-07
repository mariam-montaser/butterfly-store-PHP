<?php
require_once '../core/init.php';
if(!isLoggedIn()) {
	header('Location:login.php');
}

include 'includes/head.php';
include 'includes/navbar.php';
?>




<?php include 'includes/footer.php'; ?>