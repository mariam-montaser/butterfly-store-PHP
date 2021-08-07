</div>
			
		<!-- content -->

		<!-- footer -->

		<footer class="text-center" id="footer">&copy; Copyright 2013-<?=date('Y');?> Butterfly Store</footer>

		<!-- footer -->

		<script type="text/javascript" src="js/main.js"></script>
		<script type="text/javascript">
			$(window).scroll(function() {
				var vscroll = $(this).scrollTop();
				$("#logotext").css({
					"transform": "translate(0px, " + vscroll/2 + "px)"
				})
			})

			// detail modal function with ajax request

			function detailModal(id) {
				//alert(id);
				var data = {"id": id};
				$.ajax({
					url: '/butterfly store/includes/modal.php',
					method: "post",
					data: data,
					success: function(data) {
						$('body').append(data);
						$('#details-modal').modal("toggle");
					},
					error: function() {
						alert("error")
					}
				}); 
			}
		</script>
	</body>
</html>