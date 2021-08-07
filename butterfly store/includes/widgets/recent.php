<h3 class="text-center">Popular Items</h3>

<?php 
$transQ = $db->query("SELECT * FROM cart WHERE paid = 1 ORDER BY id DESC LIMIT 5");
$results = array();
while($row = mysqli_fetch_assoc($transQ)) {
	$results[] = $row;
}
$rowCount = $transQ->num_rows;
$usedIDs = array();
for($i=0;$i<$rowCount;$i++) {
	$jsonItems = $results[$i]['items'];
	$items = json_decode($jsonItems, true);// return assoc array
	foreach ($items as $item) {
		if(!in_array($item['id'], $usedIDs)) {
			$usedIDs[] = $item['id'];
		}
	}
}


?>

<div id="recent">
	<table class="table table-condensed">
		<?php foreach ($usedIDs as $id): 
		$productQ = $db->query("SELECT id,title FROM products WHERE id= '{$id}'");
		$product = mysqli_fetch_assoc($productQ);
		?>
		<tr>
			<td><?=substr($product['title'], 0, 15);?></td>
			<td>
				<a class="text-primary" onclick="detailModal(<?=$id?>);">View </a>
			</td>
		</tr>

	<?php endforeach; ?>
	</table>
</div>