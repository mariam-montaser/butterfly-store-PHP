<?php

require_once '../core/init.php';
if(!isLoggedIn()) {
	loginRedirect();
}
include 'includes/head.php';
include 'includes/navbar.php';

//get brands from db
$sql = "SELECT * FROM brands ORDER BY brand";
$result = $db->query($sql);

// check errors in add brand form
$errors = array();

// delete brand
if(isset($_GET['delete']) && !empty($_GET['delete'])) {
	$delete_id = sanitize((int)$_GET['delete']);
	$sql = "DELETE FROM brands WHERE id = '$delete_id'";
	$db->query($sql);
	header('Location:brands.php');
}

//edit brand
if(isset($_GET['edit']) && !empty($_GET['edit'])) {
	$edit_id = sanitize((int)$_GET['edit']);
	//echo $edit_id;
	$sql = "SELECT * FROM brands WHERE id = '$edit_id'";
	$e_result = $db->query($sql);
	$eBrand = mysqli_fetch_assoc($e_result);
	//header('Location:brands.php');
}

//add brand
if(isset($_POST['add_submit'])) {
	//chek if empty
	$brand = sanitize($_POST['brand']);
	if($brand == '') {
		$errors[] .= 'You Must Enter A Brand';
	}
	//check if brand in db
	$sql = "SELECT * FROM brands WHERE brand = '$brand'";
	if(isset($_GET['edit'])) {
		$sql = "SELECT * FROM brands WHERE brand = '$brand' AND id != 'edit_id'";
	}
	$result2 = $db->query($sql);
	$count = mysqli_num_rows($result2);

	if($count > 0) {
		$errors[] .= $brand .' Brand Already Exist, Please Chose A New Brand.';
	}
	//display errors
	if(!empty($errors)) {

		echo display_errors($errors);

	} else {
		//add to db
		$sql = "INSERT INTO brands(brand) VALUES ('$brand')";
		if(isset($_GET['edit'])) {
			$sql = "UPDATE brands SET brand = '$brand' WHERE id = '$edit_id' ";
		}
		$db->query($sql);
		header('location:brands.php');
		

	}
}

?>

<!-- <a href=""></a> -->

<h2 class="text-center ">Brands</h2>
<hr>
<!-- brand add form -->
<div class="text-center">
	<form class="form-inline" action="brands.php<?php echo ((isset($_GET['edit']))?'?edit=' . $edit_id:'') ?>" method="post">
		<div class="form-group">
			<?php
			if(isset($_GET['edit'])) {
				$brand_value = $eBrand['brand'];
			} else {
				if(isset($_POST['brand'])) {
					$brand_value = $_POST['brand'];
				} else {
					$brand_value = '';
				}
			}
			?>
			<label for="brand"><?php echo (isset($_GET['edit'])?'Edit':'Add A'); ?> Brand: </label>
			<input type="text" name="brand" id="brand" value="<?php echo $brand_value;?>" class="form-control text-capitalize" />
			<?php if(isset($_GET['edit'])): ?>
				<a href="brands.php" class="btn btn-default">Cancel</a>
			<?php endif; ?>
			<input type="submit" name="add_submit" value="<?php echo (isset($_GET['edit'])?'Edit':'Add'); ?> Brand" class="btn btn-success">
		</div>
	</form>
</div>
<hr>

<table class="table table-bordered table-striped table-auto text-center table-condensed">
	<thead>
		<th></th>
		<th>Brand</th>
		<th></th>
	</thead>
	<tbody>
		<?php while($brand = mysqli_fetch_assoc($result)): ?>
			<tr>
				<td><a href="brands.php?edit=<?php echo $brand['id'] ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></a></td>
				<td class="text-capitalize"><?php echo $brand['brand'] ?></td>
				<td><a href="brands.php?delete=<?php echo $brand['id'] ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
			</tr>
		<?php endwhile; ?>
	</tbody>
</table>

<?php
include 'includes/footer.php';
?>