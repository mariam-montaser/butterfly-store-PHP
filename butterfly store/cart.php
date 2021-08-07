<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navbar.php';
include 'includes/headerpartial.php';


//$cartID = '';

if($cartID != '') {
	$cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cartID}'");
	$result = mysqli_fetch_assoc($cartQ);
	$items = json_decode($result['items'], true);//return an assoc array
	$i = 1;
	$subTotal = 0;
	$itemCount = 0;
	var_dump($items);
}
?>

<div class="col-md-12">
	<div class="row">
		<h2 class="text-center">My Shopping Cart</h2>
		<hr>
		<?php if($cartID == ''): ?>
			<div class="bg-danger">
				<p class="text-center text-danger">
					Your shopping cart is empty
				</p>
			</div>
		<?php else: ?>
			<table class="table table-bordered table-striped table-condensed">
				<thead class="text-capitalize">
					<th>#</th>
					<th>item</th>
					<th>price</th>
					<th>quantity</th>
					<th>size</th>
					<th>sub total</th>
				</thead>
				<tbody>
					<?php
						foreach ($items as $item) {
							$productID = $item['id'];
							$productQ = $db->query("SELECT * FROM  products WHERE id = '{$productID}'");
							$product = mysqli_fetch_assoc($productQ);
							$sArray = explode(',', $product['size']); //size array
							foreach ($sArray as $sString) {//size string
								$s = explode(':', $sString);
								if($s[0] == $item['size']) {
									$available = $s[1];
								}
							}
							?>

							<tr>
								<td><?=$i;?></td>
								<td><?=$product['title'];?></td>
								<td><?=money($product['price']);?></td>
								<td>
									<button class="btn btn-default btn-xs" onclick="updateCart('removeone', <?=$product['id']?>, <?=$item['size']?>);">-</button>
									<?=$item['quantity'];?>
									<?php if($item['quantity'] < $available): ?>
										<button class="btn btn-default btn-xs" onclick="updateCart('addone', <?=$product['id']?>, <?=$item['size']?>);">+</button>	
									<?php else: ?>
										<span class="text-danger">Max</span>
									<?php endif; ?>
								</td>
								<td><?=$item['size'];?></td>
								<td><?=money($item['quantity'] * $product['price']);?></td>
							</tr>
 
							<?php 
							$i++;
							$itemCount += $item['quantity'];
							$subTotal += ($product['price'] * $item['quantity']);  
						}

						$tax = TAXRATE * $subTotal;
						$tax = number_format($tax, 2);
						$total = $tax + $subTotal;

					?>
					
				</tbody>
			</table>
			<table class="table table-bordered table-condensed text-right totals">
				<legend class="text-capitalize">total</legend>
				<thead class="total-header text-capitalize">
					<th>total items</th>
					<th>sub total</th>
					<th>tax</th>
					<th>total</th>
				</thead>
				<tbody>
					<tr>
						<td><?=$itemCount?></td>
						<td><?=money($subTotal)?></td>
						<td><?=money($tax)?></td>
						<td class="bg-success"><?=money($total)?></td>
					</tr>
				</tbody>
			</table>
			<!-- check out button -->
			<button type="button" class="pull-right btn btn-primary" data-toggle="modal" data-target="#checkModal">
				<span class="glyphicon glyphicon-caret"></span>
				Check Out >>
			</button>

			<!-- modal -->
			<div class="modal fade" id="checkModal" tabindex="-1" role="dialog" aria-labelledby="#checkModalLabel" >
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title" id="checkModalLabel">Shipping Address</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<form action="thankYou.php" method="post" id="payment-form">
									<span class="bg-danger" id="payment-error"></span>
									<input type="hidden" name="tax" value="<?=$tax?>">
									<input type="hidden" name="subTotal" value="<?=$subTotal?>">
									<input type="hidden" name="total" value="<?=$total?>">
									<input type="hidden" name="cartID" value="<?=$cartID?>">
									<input type="hidden" name="description" value="<?=$itemCount . ' item' . (($itemCount > 1)?'s':''). ' from butterfly Store.'?>"> 
									<div id="step1" style="display: block;">
										<div class="col-md-6 form-group">
											<label for="full-name">Full Name:</label>
											<input type="text" name="full-name" id="full-name" class="form-control">
										</div>
										<div class="col-md-6 form-group">
											<label for="email">Email:</label>
											<input type="email" name="email" id="email" class="form-control">
										</div>
										<div class="col-md-6 form-group">
											<label for="street">Street Address:</label>
											<input type="text" name="street" id="street" class="form-control" data-stripe="address_line1">
										</div>
										<div class="col-md-6 form-group">
											<label for="street2">Street Address 2:</label>
											<input type="text" name="street2" id="street2" class="form-control" data-stripe="address_line2">
										</div>
										<div class="col-md-6 form-group">
											<label for="city">City:</label>
											<input type="text" name="city" id="city" class="form-control" data-stripe="address_city">
										</div>
										<div class="col-md-6 form-group">
											<label for="state">State:</label>
											<input type="text" name="state" id="state" class="form-control" data-stripe="address_state">
										</div>
										<div class="col-md-6 form-group">
											<label for="zip">Zip Code:</label>
											<input type="text" name="zip" id="zip" class="form-control" data-stripe="address_zip">
										</div>
										<div class="col-md-6 form-group">
											<label for="country">Country:</label>
											<input type="text" name="country" id="country" class="form-control" data-stripe="address_country">
										</div>
									</div>
									<div id="step2" style="display: none;">
										<div class="form-group col-md-3">
											<label for="name">Name On Card:</label>
											<input type="text" id="name" class="form-control" data-strip="namer" />
										</div>
										<div class="form-group col-md-3">
											<label for="number">Card Number:</label>
											<input type="text" id="number" class="form-control" data-strip="number" />
										</div>
										<div class="form-group col-md-2">
											<label for="cvc">CVC:</label>
											<input type="text" id="cvc" class="form-control" data-strip="cvc" />
										</div>
										<div class="form-group col-md-2">
											<label for="exp-month">Expire Month:</label>
											<select id="exp-month" class="form-control" data-strip="exp-month">
												<option value=""></option>
												<?php for($i=1; $i<13;$i++): ?>
													<option value="<?=$i;?>"><?=$i;?></option>
												<?php endfor; ?>
											</select>
										</div>
										<div class="form-group col-md-2">
											<label for="exp-year">Expire Year:</label>
											<select id="exp-year" class="form-control" data-strip="exp-year">
												<option value=""></option>
												<?php
													$y = date('Y');
													for($i=0; $i<11 ;$i++): ?>
													<option value="<?=$y + $i;?>"><?=$y + $i;?></option>
												<?php endfor; ?>
											</select>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button id="next-btn" type="button" class="btn btn-primary" onclick="checkAddress();">Next >></button>
										<button id="back-btn" type="button" class="btn btn-primary" style="display: none" onclick="backAddress();">Back >></button>
										<button id="check-btn" type="submit" class="btn btn-primary" style="display: none" >Check Out >></button>
									</div>
								</form>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	//update cart function
	function updateCart(mode, editID, editSize) {
		var data = {
			'mode': mode,
			'editID': editID,
			'editSize': editSize
		};
		$.ajax({
			url: '/butterfly store/admin/parsers/update_cart.php',
			method: 'post',
			data: data,
			success: function() {
				location.reload();
			},
			error: function() {
				alert('Something went wrong');
			}
		});
	}

	//check address
	function checkAddress() {
		var data = {
			'full-name': $('#full-name').val(),
			'email': $('#email').val(),
			'street': $('#street').val(),
			'street2': $('#street2').val(),
			'city': $('#city').val(),
			'state': $('#state').val(),
			'zip': $('#zip').val(),
			'country': $('#country').val()
		};
		$.ajax({
			url: '/butterfly store/admin/parsers/check_address.php',
			method: 'post',
			data: data,
			success: function(data) {
				if(data != 'passed') {
					$('#payment-error').html(data)
				}
				if(data == 'passed') {
					$('#payment-error').html('');
					$('#step1').css('display', 'none');
					$('#step2').css('display', 'block');
					$('#next-btn').css('display', 'none');
					$('#back-btn').css('display', 'inline-block');
					$('#check-btn').css('display', 'inline-block');
					$('#checkModalLabel').html('Enter your card details.')
				}
			},
			error: function() {
				alert('Something went wrong.');
			}
		})
	}
	Stripe.setPublishablekey('<?=STRIPE_PUPLIC?>');

	function stripeResponseHandler(status, response) {
		var $form = $('#payment-error');

		if(response.error) {
			//show the errors on the form
			$form.find('#payment-error').text(response.error.message);
			$form.find('button').prop('disabled', false);
		} else {
			//response contains id and card which contains additional card details
			var token = response.id;
			//insert the token into form so it gets submitted to the server
			$form.append($('<input type="hidden" name="stripeToken" />').val(token));
			//and submit
			$form.get(0).submit(); 
		}
	}

	jQuery(function($) {
		jQuery('#payment-form').submit(function(event) {
			var $form = $(this);

			//disable the submit btn to prevent clicks
			$form.find('button').prop('disabled', true);

			Stripe.card.createToken($form, stripeResponseHandler);

			//prevent the form from submitting with the default action
			return false;
		});
	});

	//back Address function
	function backAddress() {
		$('#payment-error').html('');
		$('#step1').css('display', 'block');
		$('#step2').css('display', 'none');
		$('#next-btn').css('display', 'inline-block');
		$('#back-btn').css('display', 'none');
		$('#check-btn').css('display', 'none');
		$('#checkModalLabel').html('Shipping Address.')
	}
</script>
<?php
include 'includes/footer.php';
?>