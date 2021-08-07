<h3 class="text-center">Shopping Cart</h3>
<div>
	
<?php if(empty($cartID)): ?>

<p>Your shopping cart is empty.</p>

<?php else:
	$cartQ = $db->query("SELECT * FROM cart WHERE id = '$cartID' ");
	$result = mysqli_fetch_assoc($cartQ);
	$items = json_decode($result['items'], true);
	$i = 1;
	$subTotal = 0;
?>

<table id="cart-widget" class="table table-condensed">
	<tbody>
		<?php foreach ($items as $item):

		$productQ = $db->query("SELECT * FROM products WHERE id = '{$item['id']}' ");
		$product = mysqli_fetch_assoc($productQ);
		 ?>
		 <tr>
		 	<td><?=$item['quantity']?></td>
		 	<td><?=substr($product['title'], 0, 15)?></td>
		 	<td><?=money($item['quantity'] * $product['price'])?></td>
		 </tr>
			
		<?php
		$i++;
		$subTotal += $item['quantity'] * $product['price'];
		endforeach; ?>
		<tr>
			<td></td>
			<td>sub Total</td>
			<td><?=money($subTotal)?></td>
		</tr>
	</tbody>
</table>
<a href="cart.php" class="btn btn-xs btn-primary pull-right">View Cart</a>
<div class="clearfix"></div>
<?php endif; ?>

</div> 