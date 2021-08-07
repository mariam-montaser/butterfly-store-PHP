<?php 
	require_once 'core/init.php';
	include 'includes/head.php';
	include 'includes/navbar.php';
	include 'includes/headerpartial.php';
 	include 'includes/leftbar.php';

 	if(isset($_GET['cat'])) {
 		$catID = sanitize($_GET['cat']);

 	} else {
 		$catID = '';
 	}


 	$sql = "SELECT * FROM products WHERE categories = '$catID'";
 	$products = $db->query($sql);
 	$category = getCategory($catID);
?>
		
			<!-- start main content -->

			<div class="col-md-8">
				<div class="row">
					<h2 class="text-center text-capitalize"><?=$category['parent'] . ' ' . $category['child'];?></h2>
					<!-- featured products -->
					<?php

					while($product = mysqli_fetch_assoc($products)) :

					?>
					<div class="col-md-3">
						<h4 class="text-capitalize"><?php echo $product['title']; ?></h4>
						<?php $photos = explode(',', $product['image']); ?>
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