<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/butterfly store/core/init.php';

// get data from the modal form
$productID = sanitize($_POST['productID']);
$size = sanitize($_POST['size']);
$available = sanitize($_POST['available']);
$quantity = sanitize($_POST['quantity']);
$item = array();
$item[] = array(
	'id' 	   => $productID,
	'size'     => $size,
	'quantity' => $quantity
);

$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;

$query = $db->query("SELECT * FROM products WHERE id = '{$productID}'");
$product = mysqli_fetch_assoc($query);
$_SESSION['success'] = $product['title'] . ' was added to your cart.';

//check if cart cookie is exist
if($cartID != '') {
	$cartQ = $db->query("SELECT * FROM cart WHERE id = '$cartID'");
	$cart = mysqli_fetch_assoc($cartQ);
	$prev_items = json_decode($cart['items'], true); //true is return an array instead of object
	$itemMatch = 0;
	$newItems = array();
	foreach ($prev_items as $pitem) {
		if($item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size']) {
			$pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
			if($pitem['quantity'] > $available) {
				$pitem['quantity'] = $available;
			}
			$itemMatch = 1;
		}
		$newItems[] = $pitem;
	}
	if($itemMatch != 1) {
		$newItems = array_merge($item, $prev_items);
	}
	$items_json = json_encode($newItems);
	$cart_expire = date('Y-m-d h:i:s', strtotime('+30 days'));
	$db->query("UPDATE cart SET items = '{$items_json}',  expire_date = '{$cart_expire}' WHERE id = '$cartID'");
	setcookie(CART_COOKIE,'',1,"/",$domain,false);
	setcookie(CART_COOKIE,$cartID,CART_COOKIE_EXPIRE,'/',$domain,false);
} else {
	//add to cart db and set cookie
	$items_json = json_encode($item);
	$cart_expire = date('Y-m-d h:i:s', strtotime('+30 days'));
	$db->query("INSERT INTO cart (items, expire_date) VALUES ('{$items_json}', '{$cart_expire}')");
	$cartID = $db->insert_id;
	setcookie(CART_COOKIE,$cartID,CART_COOKIE_EXPIRE,'/',$domain,false);
}
?>