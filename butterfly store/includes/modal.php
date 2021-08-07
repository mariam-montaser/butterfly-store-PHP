<?php
require_once '../core/init.php';
$id = $_POST['id'];
$id = (int)$id;
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $db->query($sql);
$product = mysqli_fetch_assoc($result);
// get brand
$brandId = $product['brand'];
$sql = "SELECT brand FROM brands WHERE id = '$brandId' ";
$result = $db->query($sql);
$brand = mysqli_fetch_assoc($result);

// get size
$size_string = rtrim($product['size']);
$size_array = explode(',', $size_string);



//print_r($product);
ob_start();

?>


<!-- details modal -->

		<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" onclick="closeModal()">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title text-center"><?= $product['title'] ?></h4>
					</div>
					<div class="modal-body">
						<div class="container-fluid">
							<div class="row">
								<span id="modal-errors" class="bg-danger"></span>
								<div class="col-sm-6 fotorama">
									<?php $photos = explode(',', $product['image']); 
									foreach ($photos as $photo): ?>
								 		<img src="<?=$photo?>" alt="jeans" class="details img-responsive" />
									<?php endforeach; ?>

									 
									 
								</div>
								<div class="col-sm-6">
									<h4>Details</h4>
									<p><?= $product['description'] ?></p>
									<hr>
									<p>Price: <?= $product['price'] ?></p>
									<p>Brand: <?= $brand['brand'] ?></p>
									<form action="add_cart.php" method="post" id="add_product_form">
										<input type="hidden" name="productID" value="<?=$id;?>">
										<input type="hidden" name="available" id="available" value="">
										<div class="form-group">
											<div class="col-xs-3">
												<label for="quantity">Quantity:</label>
												<input type="number" name="quantity" class="form-control" id="quantity" min="0" />
											</div>
											<div class="col-xs-9"></div><br/>
											
										</div><br/><br/>
										<div class="form-group">
											<div class="">
												<label for="size">Size:</label>
												<select name="size" id="size" class="form-control">
													<option value=""></option>
													<?php
														foreach ($size_array as $string) {
															$s_array = explode(':', $string);
															$size = $s_array[0];
															$available = $s_array[1];

															echo "<option value='$size' data-available='$available'>$size ($available Available)</option>";
														}
													?>

													
													
												</select>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-default" onclick="closeModal()">Close</button>
						<button class="btn btn-warning" onclick="addToCart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Add To Cart</button>
					</div>
				</div>
			</div>
		</div>

		<!-- details modal -->
		<script type="text/javascript">

			//get the available count in the hidden input
			$('#size').change(function() {
				var available = $('#size option:selected').data('available');
				$('#available').val(available);
			})

			//fotorama slider
				$(function () {
				  	$('.fotorama').fotorama({
				  		'loop': true,
				  		'autoplay': true 
				  	});
				});

			function closeModal() {
				$('#details-modal').modal('hide');
				setTimeout(function() {
					$('#details-modal').remove();
					$('.modal-backdrop').remove();
				}, 500)
			}

			//add to cart function
			function addToCart() {
				//alert('added');
				$('#modal-errors').html("");
				var size = $('#size').val(),
				 	available = $('#available').val(),
					quantity = $('#quantity').val(),
					error = '',
					data = $('#add_product_form').serialize();
				if(size == '' || quantity == '' || quantity == 0) {
					error += '<p class="text-danger text-center">You must cho ose size and quantity.</p>';
					$('#modal-errors').html(error);
					return;
				} else if(quantity > available) {
					error += '<p class="text-danger text-center">Sorry. there\'s only ' + available + ' available.</p>';
					$('#modal-errors').html(error);
					return;

				} else {
					$.ajax({
						url: '/butterfly store/admin/parsers/add_cart.php',
						method: 'post',
						data: data,
						success: function() {
							location.reload();
						},
						error: function(){
							alert('Something went wrong.');
						}
					})
				}

			}
		</script>

<?php 
//include 'includes/footer.php';
echo ob_get_clean();
?>