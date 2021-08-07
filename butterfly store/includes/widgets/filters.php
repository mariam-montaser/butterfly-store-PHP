<?php
$catID = (isset($_REQUEST['cat'])?sanitize($_REQUEST['cat']):'');
$price_sort = (isset($_REQUEST['price-sort'])?sanitize($_REQUEST['price-sort']):'');
$min_price = (isset($_REQUEST['min-price'])?sanitize($_REQUEST['min-price']):'');
$max_price = (isset($_REQUEST['max-price'])?sanitize($_REQUEST['max-price']):'');
$b = ((isset($_REQUEST['brand']))?sanitize($_REQUEST['brand']):'');
$brandQ = $db->query("SELECT * FROM brands ORDER BY brand");

?>


<h3 class="text-center">Search By:</h3>
<h4 class="text-center">Price</h4>
<form action="search.php" method="post">
	<input type="hidden" name="cat" value="<?=$catID?>">
	<input type="hidden" name="price-sort" value="0">
	<input type="radio" name="price-sort" value="low" <?=(($price_sort == 'low')?'checked':'')?> /> Low To High.<br />
	<input type="radio" name="price-sort" value="high" <?=(($price_sort == 'high')?'checked':'')?> /> High To Low.<br /><br />
	<input type="text" name="min-price" class="price-range" placeholder="min $" value="<?=$min_price?>"> To
	<input type="text" name="max-price" class="price-range" placeholder="max $" value="<?=$max_price?>"><br /><br />
	<h4 class="text-center">Brand</h4>
	<input type="radio" name="brand" value="" <?=(($b == '')?'checked':'')?> />All<br />
	<?php while($brand = mysqli_fetch_assoc($brandQ)): ?>

		<input type="radio" name="brand" value="<?=$brand['id']?>" <?=(($b == $brand['id'])?'checked':'')?> /><?=$brand['brand'];?><br />

	<?php endwhile; ?>
	<input type="submit" value="Search" class="btn btn-xs btn-primary" />
</form>