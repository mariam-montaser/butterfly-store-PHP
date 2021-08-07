  <?php 
	require_once 'core/init.php';
	include 'includes/head.php';
	include 'includes/navbar.php';
	include 'includes/headerpartial.php';
 	include 'includes/leftbar.php';


 	$sql = "SELECT * FROM products";
 	$catID = (($_POST['cat'])?sanitize($_POST['cat']):'');
 	if($_POST['cat'] == '') {
 		$sql .= " WHERE deleted = 0";
 	} else {
 		$sql .= " WHERE categories = '{$catID}' AND deleted = 0";
 	}
 	$price_sort = (($_POST['price-sort'])?sanitize($_POST['price-sort']):'');
 	$min_price = (($_POST['min-price'])?sanitize($_POST['min-price']):'');
 	$max_price = (($_POST['max-price'])?sanitize($_POST['max-price']):'');
 	$brand = (($_POST['brand'])?sanitize($_POST['brand']):'');
	if($min_price != '') {
		$sql .= " AND price >= '{$min_price}'";
	}
	if($max_price != '') {
		$sql .= " AND price <= '{$max_price}'";
	}
	if($brand != '') {
		$sql .= " AND brand = '$brand'";
	}
	if($price_sort == 'low') {
		$sql .= " ORDER BY price";
	}
	if($price_sort == 'high') {
		$sql .= " ORDER BY price DESC";
	}
 	$products = $db->query($sql);
 	$category = getCategory($catID);
?>
		
			<!-- start main content -->

			<div class="col-md-8">
				<div class="row">
					<?php if($catID != ''): ?>
						<h2 class="text-center text-capitalize"><?=$category['parent'] . ' ' . $category['child'];?></h2>
					<?php else: ?>
						<h2 class="text-center">Butterfly Store</h2>

					<?php endif; ?>

					<!-- featured products -->
					<?php

					while($product = mysqli_fetch_assoc($products)) :

					?>
					<div class="col-md-3">
						<h4 class="text-capitalize"><?php echo $product['title']; ?></h4>
						<?php $photos = explode(',', $product['image']);?>
						<img src="<?=$photos[0]?>" alt="<?php echo $product['title'] ?>" class="img-thumb"/>
						<p class="list-price text-danger">Last Price: <s><?php echo $product['list_price'] ?></s></p>
						<p class="price">Our Price: <?php echo $product['price'] ?></p>
						<button type="button" class="btn btn-sm btn-success" onclick="detailModal(<?= $product['id'] ?>)">Details</button>
					</div>

					<?php endwhile; ?>

				</div>
			</div>

			<!-- end main content -->

			

		<?php
		//include 'includes/modal.php';
		include 'includes/rightbar.php';
		include 'includes/footer.php';
		?>