</div>
			
		<!-- content -->

		<!-- footer -->

		<footer class="text-center" id="footer">&copy; Copyright 2013-<?=date('Y');?> Butterfly Store</footer>

		<!-- footer -->

		<script type="text/javascript">

			// update sizes and quantity function
			function updateSize() {
				//alert('update');
				var sizeString = '';
				for(var i = 1; i <= 12; i++) {
					if($('#size'+i).val() != '') {
						sizeString += $('#size'+i).val() + ':' + $('#quantity'+i).val() + ',';
					}
				}
				$('#size-q').val(sizeString);

			}

			// get child category depand on parent with ajax
			function getChildOptions(selected) {
				if(typeof(selected) === 'undefined') {
					var selected = '';
				}
				var parentID = $('#parent').val();
				$.ajax({
					url: '/Butterfly store/admin/parsers/child_categories.php',
					type: "POST",
					data: {parentID: parentID, selected: selected},
					success: function(data){
						$('#child').html(data);
					},
					error: function(){
						alert('something went wrong with child category.')
					}
				})
			}

			$('select[name="parent"]').change(function() {
				getChildOptions();
			});

			// //add to cart function
			// function addToCart() {
			// 	alert('added');
			// }
		</script>

		
	</body>
</html>