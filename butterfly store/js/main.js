// $(window).scroll(function() {
// 	var vscroll = $(this).scrollTop();
// 	$("#logotext").css({
// 		"transform": "translate(0px, " + vscroll/2 + "px)"
// 	})
// });

// // detail modal function with ajax request

// function detailModal(id) {
// 	console.log(id);
// 	var data = {"id": id};
// 	$.ajax({
// 		url: <?php echo BASEURL; ?> + 'includes/modal.php',
// 		method: "post",
// 		data: data,
// 		success: function(data) {
// 			$('body').append(data);
// 			$('#details-modal').modal("toggle");
// 		},
// 		error: function() {
// 			alert("error")
// 		}
// 	})
// }