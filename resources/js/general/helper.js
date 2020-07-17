const main_url = window.mainurl = window.location.protocol + "//" + window.location.host + "/";

$(function() {
	$.ajaxSetup({
		headers: {
		'X-CSRF-Token': $('meta[name="_token"]').attr('content')
		}
	});
});

window.ajax_data = function (link, dt) {
	if (dt == undefined) {
		dt = "";
	}
	var res;
	$.ajax({
		type: "POST",
		url: main_url + link,
		data: dt,
		async: false,
		success: function(data) {
			res = data;
		}
	})
	return res;
}

window.delete_data = function (ele) {
	swal.fire({
		title: "Hapus data, Anda yakin?",
		text: "Data yang sudah dihapus tidak dapat dibatalkan.",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn-danger",
		confirmButtonText: 'Yes, delete it!',
		showLoaderOnConfirm: true,
		closeOnConfirm: false,
		preConfirm: function() {
			var id = $(ele).data("id");
			var method = $(ele).data("method") + "/";
		 	return new Promise(function(resolve) {
					$.ajax({
						url  : main_url + method + id,
						type : "POST",
						data : {
							"id": id,
							"_method": "delete",
						}
				    })
					.done(function(response){
						swal.fire('Deleted!', response.message, response.status);
					})
					.fail(function(){
						swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
					})
			});

		},
		allowOutsideClick: false
	 });
}


$("form .btn-cancel").click(function(){
	var method = $(this).data("method") + "/";
	swal.fire({
		title: "Are you sure?",
		type: "warning",
		showCancelButton: !0,
		confirmButtonText: "Yes, cancel it",
		cancelButtonText: "No"
	}).then(function(e) {
		e.value ? window.location.href = main_url + method : "";
	})
})
