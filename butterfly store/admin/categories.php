<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/butterfly store/core/init.php';
	if(!isLoggedIn()) {
		loginRedirect();
	}
	include 'includes/head.php';
	include 'includes/navbar.php';

	//get data from db
	$sql = "SELECT * FROM categories WHERE parent = 0";
	$result = $db->query($sql);

	//variables
	$errors = array();
	$category = '';
	$parent = '';

	// delete category
	if(isset($_GET['delete']) && !empty($_GET['delete'])) {
		$delete_id = sanitize((int)$_GET['delete']);
		$sql = "SELECT * FROM categories WHERE id = '$delete_id' ";
		$result = $db->query($sql);
		$d_category = mysqli_fetch_assoc($result);
		if($d_category['parent'] == 0) {
			$sql = "DELETE * FROM categories WHERE parent = '$delete_id'";
			$db->query($sql);

		}
		$dSql = "DELETE FROM categories WHERE id = '$delete_id' "; // delete sql
		$db->query($dSql);
		header('Location:categories.php');

	}

	//edit category
	if(isset($_GET['edit']) && !empty($_GET['edit'])) {
		$edit_id = sanitize((int)$_GET['edit']);
		$edit_sql = "SELECT * FROM categories WHERE id = '$edit_id'";
		$edit_result = $db->query($edit_sql);
		$e_category = mysqli_fetch_assoc($edit_result);
	}

	// add cats form
	
	if(isset($_POST) && !empty($_POST)) {
		$parent = sanitize($_POST['parent']);
		$category = sanitize($_POST['category']);
		$sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$parent'";
		if(isset($_GET['edit'])) {
			$id = $e_category['id'];
			$sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$parent' AND id != '$id' ";
		}
		$fresult = $db->query($sqlform);//form result
		$count = mysqli_num_rows($fresult);

		//check if category input is empty
		if($category == '') {
			$errors[] .= 'The category cannot be left blank.';
		}

		//check if category is already exist in db
		if($count > 0 ) {
			$errors[] .= 'Already exist, Please choose a new category.';
		}

		//display errors or insert in db
		if(!empty($errors)) {
			//display errors
			$display = display_errors($errors);?>

			<script type="text/javascript">
				$('document').ready(function() {
					$('#error').html('<?php echo $display ?>');
				});
			</script>

		<?php
		} else {
			//update db
			$updateSql = "INSERT INTO categories (category, parent) VALUES ('$category', '$parent')";
			if(isset($_GET['edit'])) {
				$updateSql = "UPDATE categories SET category ='$category', parent = '$parent' WHERE id = '$edit_id'";
			}
			$db->query($updateSql);
			header('Location:categories.php');
		}

	}

	//change the input value when edit
	if(isset($_GET['edit'])) {
		$category_value = $e_category['category'];
		$parent_value = $e_category['parent'];
	} else {
		if(isset($_POST)) {
			$category_value = $category;
			$parent_value = $parent;
		} else {
			$category_value = '';
			$parent_value = 0;
		}
	}


?>

<h2 class="text-center text-capitalize">categories</h2>
<hr>
<div class="row">

	<!-- add category form  -->
	<div class="col-md-6">
		<form class="form" action="categories.php<?php echo ((isset($_GET['edit']))?'?edit='.$edit_id :'') ?>" method="post">
			<legend><?php echo ((isset($_GET['edit']))?'Edit':'Add A') ?> Category</legend>
			<div id="error"></div>
			<div class="form-group">
				<label for="parent">Parent</label>
				<select class="form-control text-capitalize" name="parent" id="parent">
					<option value="0" <?php echo (($parent_value == 0)?'selected':''); ?>>Parent</option>
					<?php while($parents = mysqli_fetch_assoc($result)):
					 ?>
						<option class="text-capitalize" value="<?php echo $parents['id'] ?>" <?php echo (($parent_value == $parents['id'])?'selected':''); ?>><?php echo $parents['category'] ?></option>
				<?php endwhile; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="category">Category</label>
				<input type="text" name="category" id="category" class="form-control" value="<?php echo $category_value; ?>" />
			</div>
			<div class="form-group">
				<input type="submit" value="<?php echo ((isset($_GET['edit']))?'Edit':'Add') ?> Category" class="btn btn-success" />
			</div>
		</form>
	</div>

	<!-- categories table -->
	<div class="col-md-6 category-table">
		<table class="table table-bordered">
			<thead>
				<th>Category</th>
				<th>Parent</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					$sql = "SELECT * FROM categories WHERE parent = 0";
					$result = $db->query($sql);
					 while($parents= mysqli_fetch_assoc($result)):
						$parent_id = (int)$parents['id'];
						$sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
						$cresult = $db->query($sql2); //child result

				?>
					<tr class="bg-primary text-capitalize">
						<td><?php echo $parents['category'] ?></td>
						<td>Parent</td>
						<td>
							<a href="categories.php?edit=<?php echo $parents['id'] ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="categories.php?delete=<?php echo $parents['id'] ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign"></span></a>
						</td>
					</tr>
					<?php while($child = mysqli_fetch_assoc($cresult)): ?>

						<tr class="text-capitalize">
							<td><?php echo $child['category'] ?></td>
							<td><?php echo $parents['category'] ?></td>
							<td>
								<a href="categories.php?edit=<?php echo $child['id'] ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="categories.php?delete=<?php echo $child['id'] ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign"></span></a>
							</td>
						</tr>
					<?php endwhile; ?>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>


<?php
	include 'includes/footer.php';
?>