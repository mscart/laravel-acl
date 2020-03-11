var AddRoles = function() {

	//set csrf token for ajax call
	var ajaxCSRFToken = function() {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	}



	var addRole = function() {

		// Basic initialization
		$('.multiselect').multiselect();

		var validator = $('.validate').validate({
			errorClass: 'validation-invalid-label',
			successClass: 'validation-valid-label',
			validClass: 'validation-valid-label',
			highlight: function(element, errorClass) {
				$(element).removeClass(errorClass);
			},
			unhighlight: function(element, errorClass) {
				$(element).removeClass(errorClass);
			},
			// success: function(label) {
			// 	label.addClass('validation-valid-label').text('Success.'); // remove to hide Success message
			// },
			// Different components require proper error label placement
			// errorPlacement: function(error, element) {
      //
			// 	// Unstyled checkboxes, radios
			// 	if (element.parents().hasClass('form-check')) {
			// 		error.appendTo(element.parents('.form-check').parent());
			// 	}
			// 	// Input with icons and Select2
			// 	else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
			// 		error.appendTo(element.parent());
			// 	}
      //
			// 	// Input group, styled file input
			// 	else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
			// 		error.appendTo(element.parent().parent());
			// 	}
			// 	// Other elements
			// 	else {
			// 		error.insertAfter(element);
			// 	}
			// }
		});
		// Reset form
		$('#reset').on('click', function() {
			validator.resetForm();
		});

    $("#role_name").on('blur',function(){
      var role_name = $(this).val();
      ajax_data = "role_name=" + role_name + '&_method=POST';
      var route = 'checkRoleName';

      $.ajax({
        type: "POST",
        url: route,
        dataType: 'json',
        data: ajax_data,
        success: function(response) {
          if (response.success === true) {
            var errorsHtml = '';
            $.each(response.messages, function(index, value) {
              errorsHtml +=  value +' <br/>';
            });
            alert_type = "success";
            if(response.role_exist === true)
            {
              $("#role_name").val('');
              alert_type = "error";
            }
            showNotify(alert_type,errorsHtml);
          }
        }
      });
    });
	}
	return {
		// public functions
		init: function() {
			ajaxCSRFToken();
			addRole();
		}
	};
}()
jQuery(document).ready(function() {
	AddRoles.init()
});
