<?php

define('BASEURL', $_SERVER['DOCUMENT_ROOT'] . '/butterfly store/');
define('CART_COOKIE', 'SBw172Uklwiqzz5');
define('CART_COOKIE_EXPIRE', time()+(86400*30));
define('TAXRATE', 0.007);// sales tax rate
//for composer
define('CURRENCY', 'usd');
define('CHECKMODE', 'TEST'); //change test to live

if(CHECKMODE == 'TEST') {
	define('STRIPE_PRIVATE', 'sk_test_Qts7FHsPOdWEds3CPRKp2G6A00kZJUqIoN');
	define('STRIPE_PUPLIC', 'pk_test_dGqddlrZd9AInOgV1G7j4ovm00omY3CrGS');
}

if(CHECKMODE == 'LIVE') {
	define('STRIPE_PRIVATE', '');
	define('STRIPE_PUPLIC', '');
}
//echo BASEURL;
//echo CART_COOKIE_EXPIRE, '-', time();