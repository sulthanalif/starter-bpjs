$(document).ready(function () {
	if ($("#form-create").length > 0) {
		$("#form-create").validate({
			rules: {
				clas_id: {
					required: true,
				},
				code_margin_id: {
					required: true,
				},
				starting_price: {
					required: true,
				},
				ending_price: {
					required: true,
				},
				margin_basic: {
					required: true,
				},
				bonus: {
					required: true,
				},
				bonus_percentage: {
					required: true,
				},
			},
			messages: {
				clas_id: {
					required: "CLASS wajib diisi",
				},
				code_margin_id: {
					required: "CODE wajib diisi",
				},
				starting_price: {
					required: "Minimum Harga wajib diisi",
				},
				ending_price: {
					required: "Maximum Harga Wajib diisi",
				},
				margin_basic: {
					required: "Margi Basic Minimum wajib diisi",
				},
				bonus: {
					required: "Bonus wajib diisi",
				},
				bonus_percentage: {
					required: "Bonus Percentage wajib diisi",
				},
			},
			debug: true,
			submitHandler : function(form) {
				form.simpan[0].disabled = true;
				console.log(form);
				form.submit();
				return false;
			}
		})
	}
});
