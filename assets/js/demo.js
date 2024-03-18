/*
 *
 *   Right - Responsive Admin Template
 *   v 0.3.0
 *   http://adminbootstrap.com
 *
 */

$(document).ready(function() {

	$('body').on('click', '.demo__ico', function() {
		$('.demo').toggleClass('demo_open');
	});

	$('body').on('click', '.demo__theme', function() {
		$('.demo__theme').removeClass('demo__theme_active');
		$(this).addClass('demo__theme_active');

		var demoCss = $(document.createElement('link')).addClass('demo__css').attr('rel', 'stylesheet').attr('href', $(this).data('css'));
		$('.demo__css').addClass('old');
		$('head').append(demoCss);
		setTimeout(function() {
			$('.demo__css.old').remove();
		}, 200);
		var TemaSistema = $(this).attr('name');
		$.ajax({
			type: 'POST',
			data : {"TemaSistema": TemaSistema},
			dataType: "json",
			url: SITE_URL+'/Seguridad/cSeguridad/ActualizarTemaUsuario',
			success: function (data) {
				$(".simple-pagination").removeClass("dark-theme");
				$(".simple-pagination").removeClass("light-theme");

				if (TemaSistema == "right.light.css") {
					if (!$(".simple-pagination").hasClass("light-theme")) {
						$(".simple-pagination").addClass("light-theme");
					}
				}
				else {
					if (!$(".simple-pagination").hasClass("dark-theme")) {
						$(".simple-pagination").addClass("dark-theme ");
					}
				}
			},
			error : function (jqXHR, textStatus, errorThrown) {
				$("#loader").hide();
			}
		});

	})
});
