<?php

require_once '../core/init.php';
if(!isLoggedIn()) {
	header('Location:login.php');
}

//check permission
// if(!hasPermission()) {
// 	permissionRedirect();
// }
include 'includes/head.php';
include 'includes/navbar.php';
//echo $_SEESION['BUser'];
?>

<!-- orders to fill -->

<?php 

$transQuery = "SELECT t.id, t.cart_id, t.full_name, t.description, t.trns_date, t.total, c.items, c.paid, c.shipped			
				FROM transactions t
				LEFT join cart c 
				ON t.cart_id = c.id 
				WHERE c.paid = 1 
				AND c.shipped = 0 
				ORDER BY t.trns_date";
$transResult = $db->query($transQuery);

?>
<div class="col-md-12">
	<h3 class="text-center">Orders To Ship</h3>
	<table class="table table-condensed table-bordered table-striped">
		<thead class="text-capitalize">
			<th></th>
			<th>name</th>
			<th>description</th>
			<th>total</th>
			<th>date</th>
		</thead>
		<tbody>
			<?php while($order = mysqli_fetch_assoc($transResult)): ?>
				<tr>
					<td><a href="orders.php?trns_id=<?=$order['id']?>" class="btn btn-xs btn-info">Details</a></td>
					<td><?=$order['full_name']?></td>
					<td><?=$order['description']?></td>
					<td><?=money($order['total'])?></td>
					<td><?=prettyDate($order['trns_date'])?></td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>

<?php
include 'includes/footer.php';
//session_destroy();
?>  