<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/butterfly store/core/init.php';
	if(!isLoggedIn()) {
		loginRedirect();
	}
	include 'includes/head.php';
	include 'includes/navbar.php';

	//delete product
	if(isset($_GET['delete'])) {
		$delete_id = sanitize($_GET['delete']);
		$db->query("UPDATE products SET deleted = 1 WHERE id = '$delete_id'");
		header('Location:products.php');
	}

	$dbPath = '';

	//add product form
	if(isset($_GET['add'])  || isset($_GET['edit'])){
		$brand_query = $db->query("SELECT * FROM brands ORDER BY brand");
		$parent_query = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category ");
		$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
		$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
		$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
		$cCategory = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
		$price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):'');
		$list_price = ((isset($_POST['list-price']) && !empty($_POST['list-price']))?sanitize($_POST['list-price']):'');
		$desc = ((isset($_POST['desc']) && !empty($_POST['desc']))?sanitize($_POST['desc']):'');
		$size_q = ((isset($_POST['size-q']) && $_POST['size-q'] != '')?sanitize($_POST['size-q']):'');
		$size_q = rtrim($size_q, ',');
		$savedPhoto = '';

		//check edit page
		if(isset($_GET['edit'])) {
			$edit_id = (int)$_GET['edit'];
			$productResult = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
			$getProduct = mysqli_fetch_assoc($productResult);
			if(isset($_GET['delete_image'])) {
				$imgi = (int)$_GET['imgi'] - 1;
				$images = explode(',', $getProduct['image']);
				$image_url = $_SERVER['DOCUMENT_ROOT'] . $images[$imgi];
				unlink($image_url);
				unset($images[$imgi]);
				$imageString = implode(',', $images);
				$db->query("UPDATE products SET image = '{$imageString}' WHERE id = '$edit_id' ");
				header('Location:products.php?edit='.$edit_id);
			}
			$cCategory = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$getProduct['categories']);
			$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$getProduct['title']);
			$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$getProduct['brand']);
			$parentResult = $db->query("SELECT * FROM categories WHERE id = '$cCategory'");
			$getParent = mysqli_fetch_assoc($parentResult);
			$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$getParent['parent']);
			$price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$getProduct['price']);
			$list_price = (isset($_POST['list-price'])?sanitize($_POST['list-price']):$getProduct['list_price']);
			$desc = (isset($_POST['desc'])?sanitize($_POST['desc']):$getProduct['description']);
			$size_q = ((isset($_POST['size-q']) && $_POST['size-q'] != '')?sanitize($_POST['size-q']):$getProduct['size']);
			$size_q = rtrim($size_q, ',');
			$savedPhoto = ($getProduct['image'] != '')?$getProduct['image']:'';
			$dbPath = $savedPhoto;
		}
		if(!empty($size_q)) {
				$size_qString = rtrim(sanitize($size_q),',');
				$size_qArray = explode(',', $size_qString);
				$sizeArray = array();
				$quantityArray = array();
				foreach($size_qArray as $string ) {
					$s = explode(':', $string);
					$sizeArray[] = $s[0];
					$quantityArray = $s[1];
				}
			
			} else {
				$size_qArray = array();
			}

		if($_POST) {
			// get the data
			//$title = sanitize($_POST['title']);
			//$brand = sanitize($_POST['brand']);
			//$parent = sanitize($_POST['parent']);
			//$category = sanitize($_POST['child']);
			//$price = sanitize($_POST['price']);
			//$list_price = sanitize($_POST['list-price']);
			//$desc = sanitize($_POST['desc']);
			//$size_q = sanitize($_POST['size-q']);
			//$dbPath = '';
			$errors = array();
			

			// validate the form
			$required = array('title', 'brand', 'parent', 'child', 'price', 'size-q' );
			//allowed extensions
			$allowed = array('png', 'jpg', 'jpeg', 'gif');
			//$photoName = array();
			$tmpLocn = array();
			$uploadedPath = array();
			foreach ($required as $item) {
				if($_POST[$item] == '') {
				$errors[] = 'All Fields With Astrisk Are Required.';
				break;
				}
			}
			// check uploaded files
			$photoCount = count($_FILES['photo']['name']);
			var_dump($_FILES);//die();
			if($photoCount > 0){
				for($i=0; $i<$photoCount; $i++) {
					$name = $_FILES['photo']['name'][$i];
					$nameArray = explode('.', $name);
					$fileName = $nameArray[0];
					$fileExt = $nameArray[1];
					$mime = explode('/', $_FILES['photo']['type'][$i]);
					$mimeType = $mime[0];
					$mimeExt = $mime[1];
					$tmpLoc[] = $_FILES['photo']['tmp_name'][$i];
					$fileSize = $_FILES['photo']['size'][$i];
					// uploaded files path
					$uploadedName = md5(microtime()) . '.' . $fileExt;
					$uploadedPath[] = BASEURL . 'images/products/' . $uploadedName;
					if($i != 0) {
						$dbPath .= ',';
					}
					$dbPath .= '/butterfly store/images/products/' . $uploadedName;



					//check if uploaded file is an image
					if($mimeType != 'image') {
						$errors[] = 'The Uploaded File Must Be An Image.';
					}
					//check extension
					if(!in_array($fileExt, $allowed)) {
						$errors[] = 'The file extension must be png, jpg, jpeg or gif.';
					}
					//check the size 
					if($fileSize > 10000000) {
						$errors[] = 'The file size must be less than 10MB.';
					}
					//check if file extension is match with mime extension
					if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
						$errors[] = 'file extension dosen\'t match.';
					}
				}
			}

			//display errors
			if(!empty($errors)) {
				echo display_errors($errors);
			} else {
				//upload images and insert into database
				if($photoCount > 0) {
					for($i= 0; $i< $photoCount; $i++) {
						move_uploaded_file($tmpLoc[$i], $uploadedPath[$i]);
					}
					
				}
				$insert_query = ("INSERT INTO products (`title`, `brand`, `price`, `list_price`, `size`, `description`, `categories`, `image`) VALUES('$title', '$brand', '$price', '$list_price', '$size_q', '$desc', '$category', '$dbPath')");
				if(isset($_GET['edit'])) {
					$insert_query = "UPDATE products SET `title` = '$title', `brand` = '$brand', `price` = '$price', `list_price` = '$list_price', `size` = '$size_q', `description` = '$desc', `categories` = '$cCategory', `image` = '$dbPath' WHERE id = '$edit_id' ";
				}
				$db->query($insert_query);
				header('Location:products.php');
			}
		}
	?>

	<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A New')?> Product</h2>

	<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1')?>" method="post" enctype="multipart/form-data">
		<div class="form-group col-md-3">
			<label for="title">Title*:</label>
			<input type="text" name="title" id="title" value="<?php echo $title; ?>" class="form-control" />
		</div>
		<div class="form-group col-md-3">
			<label for="brand">Brand*:</label>
			<select class="form-control" id="brand" name="brand">
				<option value="" <?=(($brand == '')?'selected':'')?>></option>
				<?php while($b = mysqli_fetch_assoc($brand_query)): ?>
					<option class="text-capitalize" value="<?=$b['id'];?>" <?=(($brand == $b['id'])?'selected':'') ?>><?=$b['brand'];?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="parent">Parent Category*:</label>
			<select class="form-control" name="parent" id="parent">
				<option value="" <?=(($parent == '')?'selected':'')?>></option>
				<?php while($p = mysqli_fetch_assoc($parent_query)): ?>
					<option class="text-capitalize" value="<?=$p['id'];?>" <?=(( $parent == $p['id'])?'selected':'')?>><?=$p['category'];?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="child">Child Category*:</label>
			<select id="child" name="child" class="form-control">
				
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="price">Price*:</label>
			<input class="form-control" type="text" name="price" id="price" value="<?=$price?>"  />
		</div>
		<div class="form-group col-md-3">
			<label for="list-price">List Price:</label>
			<input class="form-control" type="text" name="list-price" id="list-price" value="<?=$list_price;?>"  />
		</div>
		<div class="form-group col-md-3">
			<label>Quantity & Size*:</label>
			<button class="btn btn-default form-control" onclick="$('#sizeModal').modal('toggle');return false;">Quantity & Size</button>
		</div>
		<div class="form-group col-md-3">
			<label for="size-q">Quantity & Size Review:</label>
			<input class="form-control" type="text" name="size-q" id="size-q" value="<?=$size_q?>" readonly  />
		</div>
		<div class="form-group col-md-6">
			<?php if($savedPhoto != ''):
				$imgi = 1;
				$images = explode(',', $savedPhoto);
				foreach ($images as $image):?>
				<div class="saved-image col-md-4">
					<img src="<?=$image?>" alt="product photo" /><br />
					<a href="products.php?delete_image=1&edit=<?=$edit_id;?>&imgi=<?=$imgi?>" class="text-danger text-capitalize">delete image</a>
				</div>
			<?php
				$imgi++;
			endforeach;
			 else: ?>
				<label for="photo">Photo</label>
				<input type="file" name="photo[]" id="photo" class="form-control" multiple />
			<?php endif; ?>
		</div>
		<div class="form-group col-md-6">
			<label for="desc">Description</label> 
			<textarea name="desc" id="desc" class="form-control" rows="6" ><?=$desc?></textarea>
		</div>
		<div class="form-group pull-right">
			<a href="products.php" class="btn btn-default">Cancel</a>
			<input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add')?> Product" class="btn btn-success" />
		</div>
		<div class="clearfix"></div>
	</form>

	<!-- size & quantity modal -->

	<div class="modal fade" id="sizeModal" tabindex="-1" role="dialog" aria-labelledby="sizeModalLabel" aria-hidden="true" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal=header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h2 class="modal-title" id="sizeModalLabel">Size &amp; Quantity</h2>
				</div>
				<div class="modal-body">
					<div class="container-fluid">
					<?php for($i=1;$i <= 12;$i++): ?>
						<div class="form-group col-md-4">
							<label for="size<?=$i;?>">Size:</label>
							<input class="form-control" type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sizeArray[$i-1]))?$sizeArray[$i-1]:'')?>" />
						</div>
						<div class="form-group col-md-2">
							<label for="quantity<?=$i;?>">Quantity:</label>
							<input class="form-control" type="number" name="quantity<?=$i;?>" id="quantity<?=$i;?>" value="<?=((!empty($quantityArray[$i-1]))?$quantityArray[$i-1]:'')?>" min="0" />
						</div>
					<?php endfor; ?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="updateSize();$('#sizeModal').modal('toggle');return false;">Save Changes</button>
				</div>
			</div>
		</div>
	</div>

	<?php
	} else {

	//get data from db
	$main_sql = "SELECT * FROM products WHERE deleted != 1";
	$main_result = $db->query($main_sql);//product result
	if(isset($_GET['featured'])) {
		$id = (int)$_GET['id'];
		$featured = (int)$_GET['featured'];
		$featured_sql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
		$db->query($featured_sql);
		header('Location:products.php');

	}

?>

<!-- display the data -->
<h2 class="text-center text-capitalize">products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-btn">Add Products</a>
<div class="clearfix"></div>
<hr>

<table class="table table-bordered table-condensed table-striped">
	<thead class="text-capitalize">
		<th></th>
		<th>products</th>
		<th>price</th>
		<th>category</th>
		<th>featured</th>
		<th>sold</th>
	</thead>
	<tbody>
		<?php while($products = mysqli_fetch_assoc($main_result)):
			$child_id = $products['categories'];
			$c_sql = "SELECT * FROM categories WHERE id = '$child_id'";
			$c_result = $db->query($c_sql);
			$child = mysqli_fetch_assoc($c_result);
			$parent_id = $child['parent'];
			$p_sql = "SELECT * FROM categories WHERE  id = '$parent_id'";
			$p_result = $db->query($p_sql);
			$parent = mysqli_fetch_assoc($p_result);
			$category = $parent['category'] . '-' . $child['category'];

		?>
			<tr class="text-capitalize">
				<td>
					<a href="products.php?edit=<?php echo $products['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
					<a href="products.php?delete=<?php echo $products['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
				</td>
				<td><?php echo $products['title']; ?></td>
				<td><?php echo money($products['price']); ?></td>
				<td><?php echo $category; ?></td>
				<td><a href="products.php?featured=<?php echo(($products['featured'] == 0 )?'1':'0')?>&id=<?php echo $products['id']?>" class="btn btn-xs btn-default "><span class="glyphicon glyphicon-<?php echo(($products['featured'] == 1)?'minus':'plus')?>"></span>
				</a>
				&nbsp <?php echo (($products['featured'] == 1)?'featured products':'');?></td>
				<td></td>
			</tr>

		<?php endwhile; ?>
	</tbody>
</table>

<?php }
	include 'includes/footer.php';
?>

<script type="text/javascript">
	$('document').ready(function() {
		getChildOptions('<?=$cCategory;?>');
	});
</script> 