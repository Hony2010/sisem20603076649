$(document).ready(function() {

	// Table tab count update
	function tabInfo(table) {

		var id = $(table).closest('.tab-pane').attr('id'),
			tab = $('.nav-tabs a[aria-controls='+id+']'),
			length = $(table).DataTable().page.info().recordsDisplay,
			label = tab.find('span.label');
		if (label.length) { label.remove(); }
		tab.append('<span class="label">'+length+'</span>');
	}


	var tables = $('.datatable')
		.on('preInit.dt', function (e, settings) {
			  var api = new $.fn.dataTable.Api(settings);
		})
		.on('init.dt', function () {
      tabInfo(this);
		})
		.on('draw.dt', function () {
		})
		.on('search.dt', function () {
			tabInfo(this);
		})
		.DataTable({
			ordering: false,
			lengthChange: false,
			pagingType: 'numbers',
			select: {
				style: 'single'
			},
			/*columnDefs: [
				{
					"targets": [ 10 ],
					"visible": true
				}
			],*/
			initComplete: function () {
			}
		})
		.on( 'select', function ( e, dt, type, indexes ) {
			//var data = $(this).DataTable().rows( indexes ).data()[0];
			//previewUpdate(data);
		});

	$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		//$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
		/*$($.fn.dataTable.tables({visible: true})).find('.products__stat').each(function() {
			productChart(this);
		});*/
	} );

	$('.datalist-filter__search input').on( 'keyup', function () {
		//tables.search( this.value ).draw();
	} );


	$('.input-daterange').on('changeDate', function(e) {
		//tables.draw();
	});

	$('#datalist-filter__actives').on('change', function() {
		//tables.draw();
	});

	/*$.fn.dataTable.ext.search.push(
		function( settings, data, dataIndex ) {
			var from = new Date($('.datalist-filter__from').val());
			var to = new Date($('.datalist-filter__to').val());
			var date = new Date(data[3]) || 0;

			if ( ( from == 'Invalid Date' && to == 'Invalid Date' ) ||
				( date == 'Invalid Date' ) ||
				( from == 'Invalid Date' && date <= to ) ||
				( from <= date && to == 'Invalid Date' ) ||
				( from <= date && date <= to ) )
			{
				return true;
			}
			return false;
		}
	);*/

	/*$.fn.dataTable.ext.search.push(
		function( settings, data, dataIndex ) {
			var slider = $('#datalist-filter__salary').val().split(';');
			var min = slider[0];
			var max = slider[1];
			var salary = parseFloat(data[4].replace(/[^0-9\.]+/g, ''));

			if ( ( min == undefined && max == undefined ) ||
				( isNaN(salary) ) ||
				( min == undefined && salary <= max ) ||
				( min <= salary && max == undefined ) ||
				( min <= salary && salary <= max ) )
			{
				return true;
			}
			return false;
		}
	);*/

	/*$.fn.dataTable.ext.search.push(
		function( settings, data, dataIndex ) {
			var actives = $('#datalist-filter__actives').prop('checked') || false;
			var status = data[5].toLowerCase();

			if ( ( !actives ) || ( actives && status == 'active' ) )
			{
				return true;
			}
			return false;
		}
	);*/

});
