<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/butterfly store/core/init.php';
	if(!isLoggedIn()) {
		loginRedirect();
	}
	include 'includes/head.php';
	include 'includes/navbar.php';


	//get the data from db
	$archivedQuery = $db->query("SELECT * FROM products WHERE deleted = 1 ");
	//$ArchivedProducts = mysqli_fetch_assoc($ArchivedQuery);

	if(isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$db->query("UPDATE products SET deleted = 0 WHERE id = '$id' ");
		header('Location:archive.php');
	}

?>


<h2 class="text-capitalize text-center">Archived Products</h2>

<table class="table table-bordered">
	<thead class="text-capitalize">
		<th></th>
		<th>product</th>
		<th>price</th>
		<th>category</th>
	</thead>
	<tbody class="text-capitalize">
		<?php while($archivedProducts = mysqli_fetch_assoc($archivedQuery)):
				$childID = $archivedProducts['categories'];
				$childSql = $db->query("SELECT * FROM categories WHERE id = '$childID'");
				$childCat = mysqli_fetch_assoc($childSql);
				$parentID = $childCat['parent'];
				$parentSql = $db->query("SELECT * FROM categories WHERE id = '$parentID'");
				$parentCat = mysqli_fetch_assoc($parentSql);
				//print_r($parentCat);
				$category = $parentCat['category'] . '-' . $childCat['category'];
				//echo $category;

		 ?>
			<tr>
				<td>
					<a href="archive.php?edit=<?=$archivedProducts['id']?>">
						<span class="btn btn-default glyphicon glyphicon-refresh"></span>
					</a>
				</td>
				<td><?=$archivedProducts['title']?></td>
				<td><?=$archivedProducts['price']?></td>
				<td><?=$category;?></td>
			</tr>
		<?php endwhile; ?>
		
	</tbody>
</table>


<?php include 'includes/footer.php'; ?>
