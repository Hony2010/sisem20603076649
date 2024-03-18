$(document).ready(function() {
	/*$('.input-daterange').datepicker();//usado en filtros en fecha

  //usado en filtros como rango en  salarios
	$('.slider').ionRangeSlider({
		type: "double",
		grid: false,
		min: 0,
		max: 0,
		from: 0,
		to: 0,
		prefix: "$: ",
		decorate_both: false,
		onChange: function (data) {
			tables.draw();
		}
	});
*/
//usado en filtros como rango en  salarios
//	var slider = $(".slider").data("ionRangeSlider");

	// Table tab count update
	function tabInfo(table) {

		var id = $(table).closest('.tab-pane').attr('id'),
			tab = $('.nav-tabs a[aria-controls='+id+']'),
			length = $(table).DataTable().page.info().recordsDisplay,
			label = tab.find('span.label');
		if (label.length) { label.remove(); }
		tab.append('<span class="label">'+length+'</span>');
	}

	// Product chart init
	/*function productChart(el) {
		if (!$(el).find('canvas').length) {
			$(el).sparkline(
				'html',
				{
					width: '40px',
					height: 'auto',
					type: 'line',
					lineColor: '#ed4949',
					lineWidth: 1,
					fillColor: 'rgba(237, 73, 73, 0.6)',
					spotColor: '#FF5722',
					minSpotColor: '#FF5722',
					maxSpotColor: '#FF5722',
					highlightSpotColor: '#FF5722',
					spotRadius: 1
				}
			);
		}
	}
*/
	// Preview update
	/*function previewUpdate(data) {
		console.log(data);
		var product = $('.products-preview');
		product.find('.products-preview__name').text(data[2]).attr('title', data[2]);
		product.find('.products-preview__salary').text(data[4]).attr('title', data[4]);
		product.find('.products-preview__date').text(data[3]).attr('title', data[3]);
		product.find('.products-preview__type').text(data[7]).attr('title', data[7]);
		product.find('.products-preview__status').text(data[5]).attr('title', data[5]);


		var chartData = JSON.parse('['+$(data[6]).text()+']');
		product.find('.products-preview__stat').sparkline(
			chartData,
			{
				type: 'bar',
				height: '34px',
				barSpacing: 2,
				barColor: '#1e59d9',
				negBarColor: '#ed4949'
			}
		);
	}*/

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

});
