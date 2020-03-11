var BrowseRoles = function() {

	//set csrf token for ajax call
	var ajaxCSRFToken = function() {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	}

	// Select2 for length menu styling
	var _componentSelect2 = function() {
		if (!$().select2) {
			console.warn('Warning - select2.min.js is not loaded.');
			return;
		}

		// Initialize
		$('.datatable-footer select').select2({
			minimumResultsForSearch: Infinity,
			dropdownAutoWidth: true,
			width: 'auto'
		});


	};

	//data table section
	var DataTable = function() {


		var obj = $('#browse_acl_roles');
		if (obj.length === 0) return false;
		// Setting datatable defaults


		var dt = $('#browse_acl_roles').DataTable({
			// dom: "<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'Bi><'col-sm-12 col-md-7 dataTables_pager'pl>>",
			dom: '<"datatable-header"B><"datatable-scroll"t><"datatable-footer"ilp>',
			language: {

				paginate: {
					'first': 'First',
					'last': 'Last',
					'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
					'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
				}
			},
			"processing": true,
			"serverSide": true,
			"searchDelay": 500,
			// Set rows IDs
			rowId: function(a) {
				return 'id_' + a.id;
			},
			// "language": language,
			// "pageLength": implicit_nr_listing,
			// "lengthMenu": pages_range,
			"order": [
				[0, 'desc']
			],
			"ajax": {
				"url": 'getRoles',
				"type": "POST",

			},
			orderCellsTop: true,
			fixedHeader: true,
			"columns": [{
					"data": "id",
					"orderable": true,
					"searchable": false,
					"sortable": false,

					render: function(data, type, row) {
						return '<input type="checkbox" value="' + data + '" class="checkable-row"  />'
					}
				},


				{
					"data": "name",
					"visible": true,
					"searchable": true,
					"orderable": true,
				},
				{
					"data": "guard_name",
					"visible": true,
					"searchable": true,
					"orderable": true,
				},
				{
					"data": "action",
					"visible": true,
					"searchable": false,
					"orderable": false,
					"class": "text-center",
				},
			],
			buttons: [{
					extend: 'colvis',
					text: '<i class="icon-eye nr-2"></i>',
					className: 'btn bg-blue',

				},
				{
					extend: 'csv',
					exportOptions: {
						columns: [':visible']
					},
					title: report_title,
					text: '<i class="icon-file-text2 mr-2"></i><span class="d-none d-lg-block">CSV</span>',
					className: 'btn bg-blue',
					titleAttr: 'CSV'
				},
				{
					extend: 'excelHtml5',
					exportOptions: {
						columns: [':visible']
					},
					title: report_title,
					text: '<i class="icon-file-excel mr-2"></i><span class="d-none d-lg-block">Excel</span>',
					className: 'btn bg-blue',
					titleAttr: 'Excel'

				},
				{
					extend: 'pdfHtml5',
					exportOptions: {
						columns: [':visible']
					},
					title: report_title,
					customize: function(doc) {
						doc.content[1].table.widths =
							Array(doc.content[1].table.body[0].length + 1).join('*').split('');
					},
					text: '<i class="icon-file-pdf  mr-2"></i><span class="d-none d-lg-block">PDF</span>',
					className: 'btn bg-blue',
					titleAttr: 'PDF'

				},
				{
					extend: 'print',
					exportOptions: {
						columns: [':visible']
					},
					title: report_title,
					text: '<i class="icon-printer mr-2"></i><span class="d-none d-lg-block">Print</span>',
					className: 'btn bg-blue',
					titleAttr: 'Print',
					customize: function(win) {
						$(win.document.body)
							.css('font-size', '10pt');

						$(win.document.body).find('table')
							.addClass('')
							.removeClass(' table-bordered')
							.css('font-size', 'inherit');
					}
				}
			]
		});

		dt.buttons().container().appendTo('#buttons');
		/**
		 * Column filtering data table
		 */
		// Setup - add a text input to each header cell
		$('#browse_acl_roles thead tr:eq(1) th.searchable').each(function() {
			var title = $('#browse_acl_roles thead tr:eq(0) th').eq($(this).index()).text();
			if ($(this).hasClass('date'))
				$(this).html('<div class="input-group date" style="margin-bottom:5px;">' +
					'<input id="data_start" type = "text"  class= "form-control form-control-sm m-input input-sm  m-input--solid date_picker" placeholder = "' + data_start + '" /> ' +
					'<div class= "input-group-append" > <span class="input-group-text"><i class="la la-calendar"></i></span ></div ></div >' +
					'<div class="input-group date">' +
					'<input id="data_end" type = "text"  class= "form-control form-control-sm m-input input-sm  m-input--solid date_picker" placeholder = "' + data_end + '" /> ' +
					'<div class= "input-group-append" > <span class="input-group-text"><i class="la la-calendar"></i></span ></div ></div >'
				);
			else
				$(this).html('<input type="text"  class="form-control  input-sm" placeholder="' + title.trim() + '" />');
		});

		// Apply the search
		dt.columns().every(function(index) {
			//serach for input text
			$('#browse_acl_roles thead tr:eq(1) th:eq(' + index + ') input:not(:checkbox)').keyup(delay(function(e) {
				dt.column($(this).closest('th').index() + ':visible')
					.search(this.value)
					.draw();
			}, 500));

			//search if is date
			$('#browse_acl_roles thead tr:eq(1) th:eq(' + index + ') input:not(:checkbox)').change(delay(function(e) {
				dt.column($(this).closest('th').index() + ':visible')
					.search(this.value)
					.draw();
			}, 500));
		});


		function delay(callback, ms) {
			var timer = 0;
			return function() {
				var context = this,
					args = arguments;
				clearTimeout(timer);
				timer = setTimeout(function() {
					callback.apply(context, args);
				}, ms || 0);
			};
		}

		$('#browse_acl_roles').on('click', '.delete', function() {
			var d = $(this);
			d.closest('tr').find('.checkable-row').attr('checked', true);
			$("#delete_role_modal").modal('show');
		});
		$("#check_all").on("click", function(){
			if ($(this).is(":checked"))
				$("#delete_selected").show();
			else
				$("#delete_selected").hide();

			 $('input:checkbox.checkable-row').prop('checked', this.checked);
		});

			$('#browse_acl_roles').on('click', '.checkable-row', function () {
					if ($(this).prop('checked')) {
							$('#delete_selected').show();
							$('#check_all').prop('checked', true);
					}
					else {
							var count = $('.checkable-row:checkbox:checked').length;

							if (count == 0) {
									$('#delete_selected').hide();
									$('#check_all').prop('checked', false);
							}
					}
			});

		//delete roles
		$(".modalDeleteButton").on("click", function() {
			//$.blockUI({ message: '<h1><img src="busy.gif" /> Just a moment...</h1>' });
			deleteInvoices();
			$("#delete_role_modal").modal('hide');
		});
		$(".cancel_delete").on('click', function() {
			$(".checkable-row").attr("checked", false);
		});

		$("#delete_selected").on('click',function(){
			$("#delete_role_modal").modal('show');
		});


		var deleteInvoices = function(id) {
			var ids = [];

			$('.checkable-row:checked').each(function() {
				ids.push($(this).val());
			});
			ajax_data = "ids=" + ids + '&_method=DELETE';
			var route = 'destroy';

			$.ajax({
				type: "POST",
				url: route,
				dataType: 'json',
				data: ajax_data,
				success: function(response) {
					if (response.success === true) {

						var errorsHtml = '';
						$.each(response.messages, function(index, value) {
							errorsHtml += '<p>' + value + '</p>';
						});
						alert_type = 'success';
            class_pnotify = 'bg-success border-success';
						$.each(ids, function(index, val) {
							var check_obj = $('#browse_acl_roles tr#id_' + val);
							if (response.ids_error && $.inArray(val, response.ids_error) !== -1) {
								alert_type = 'error';
                class_pnotify = 'bg-danger border-danger';
							} else {
								check_obj.remove();
							}
						});

						dt.ajax.reload();

						$('#delete_selected').hide();
							$('#check_all').prop('checked', false);

						// Styled right
						new PNotify({
							// title: 'Right icon',
							text: errorsHtml,
                            addclass: class_pnotify,
							type: alert_type
						});
					}
				}
			});
		}




	}

	return {
		// public functions
		init: function() {
			ajaxCSRFToken();

			DataTable();
			_componentSelect2();



		}
	};
}()
jQuery(document).ready(function() {
	BrowseRoles.init()
});
