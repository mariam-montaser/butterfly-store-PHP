<?php
require_once 'core/init.php';

// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
$token = $_POST['stripeToken'];
//get the rest of the post data
$full_name = sanitize($_POST['full-name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zip = sanitize($_POST['zip']);
$country = sanitize($_POST['country']);
$tax = sanitize($_POST['tax']);
$subTotal = sanitize($_POST['subTotal']);
$total = sanitize($_POST['total']);
$cartID = sanitize($_POST['cartID']);
$description = sanitize($_POST['description']);
$chargeAmount = number_format($total, 2) * 100;
$metaData = array(
	'cartID'   => $cartID,
	'tax' 	   => $tax,
	'subTotal' => $subTotal
);

try{
$charge = \Stripe\Charge::create(array(
    'amount' 		=> $chargeAmount,
    'currency' 		=> CURRENCY,
    'description' 	=> $description,
    'source' 		=> $token,
    'receiptEmail'  => $email,
    'metaData'      => $metaData)
);

$db->query("UPDATE cart SET paid = 1 WHERE id = '$cartID'");
$db->query("INSERT INTO transactions(charge_id, cart_id, full_name, email, street, street2, city, state, zip_code, country, sub_total, tax, total, description, trns_type) VALUES('$charge->id', '$cartID', '$full_name', '$email', '$street', '$street2', '$city', '$state', '$zip', '$country', '$subTotal', '$tax', '$total', '$description', '$charge->object')");
$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?$_SERVER['HTTP_HOST']:false;
setcookie(CART_COOKIE,'',"/",$domain,false);
include 'includes/head.php';
include 'includes/navbar.php';
include 'includes/headerpartial.php';


include 'includes/footer.php';
?>

<h1 class="text-center text-success">Thank You</h1>
<p>Your card has been successfully charged <?=money($total);?>. You have been emailed reciept. Please check your span folder if it not in your inbox. Additionally you can print this page as a receipt. </p>
<p>Your receipt number is: <strong><?=$cartID;?></strong></p>
<p>Your order will be shipped to the address blew.</p>
<address>
	<?=$full_name;?><br />
	<?=$street;?><br />
	<?=(($street2 != '')?$street2. '<br />':'')?><br />
	<?=$city. ', ' . $state . $zip;?><br />
	<?=$country?><br />
</address>


<?php
} catch(\Stripe\Error\Card $e) {
	echo $e;
}
?>