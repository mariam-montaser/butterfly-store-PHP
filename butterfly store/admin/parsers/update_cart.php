<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/butterfly store/core/init.php';


$mode = sanitize($_POST['mode']);
$editID = sanitize($_POST['editID']);
$editSize = sanitize($_POST['editSize']);
$cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cartID}'");
$result = mysqli_fetch_assoc($cartQ);
$items = json_decode($result['items'], true);//return assoc array
$updatedItems = array();
$domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);


//check mode
if($mode == 'removeone') {
	foreach ($items as $item) {
		if($item['id'] == $editID && $item['size'] == $editSize) {
			$item['quantity'] = $item['quantity'] - 1;
		}
		if($item['quantity'] > 0) {
			$updatedItems[] = $item;
		}
	}
}

if($mode == 'addone') {
	foreach ($items as $item) {
		if($item['id'] == $editID && $item['size'] == $editSize) {
			$item['quantity'] = $item['quantity'] + 1;
		}
		$updatedItems[] = $item;
	}
}


//check cart change
if(!empty($updatedItems)) {
	$json_update = json_encode($updatedItems);
	$db->query("UPDATE cart SET items = '{$json_update}' WHERE id = '{$cartID}'");
	$_SESSION['success'] = 'Your shopping cart has been updated';
}

if(empty($updatedItems)) {
	$db->query("DELETE FROM cart WHERE id = '{$cartID}'");
	setcookie(CART_COOKIE,'',1,"/",$domain,false);
	echo CART_COOKIE;
}

?> 