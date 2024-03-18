/*
 *
 *   Right - Responsive Admin Template
 *   v 0.3.0
 *   http://adminbootstrap.com
 *
 */
function moversidebar() {
	if ($('.sidebar').is(':visible')) {
		$('.sidebar').addClass('hidden-sidebar');
		$('.navbar-header').addClass('hidden-navbar-header');
		$('.header-navbar .topnavbar').removeClass('topnavbar_size');
		$('.main').removeClass('main_size');
		$('.btn-menu-sidebar span').removeClass('glyphicon glyphicon-chevron-left');
		$('.btn-menu-sidebar span').addClass('glyphicon glyphicon-chevron-right');
	}
	else {
		$('.sidebar').removeClass('hidden-sidebar');
		$('.navbar-header').removeClass('hidden-navbar-header');
		$('.header-navbar .topnavbar').addClass('topnavbar_size');
		$('.main').addClass('main_size');
		$(' .btn-menu-sidebar span').removeClass('glyphicon glyphicon-chevron-right');
		$(' .btn-menu-sidebar span').addClass('glyphicon glyphicon-chevron-left');
	}
}
function sizeProductList() {
	if ($(".modal-full-screen").is(":visible")) {
		var main = $(".modal-full-screen:visible .modal-content").height() - 70;
	} else {
		var main = $(".main").height() - 30;
	}
	$(".container-products:visible fieldset").css({'max-height': main,'min-height': main});
	$(".filter-products-vertical").css({'max-height': main - 25,'min-height': main - 25});
	$(".list-products:visible").css({'max-height': main - 85,'min-height': main - 85 })
}

function sizeProductDetails() {

	$(".detail-products:visible, .detail-voucher").css({'max-height': 'auto','min-height': 'auto'});
	var containerproducts  = $(".container-products:visible").height(),
			keyboardvirtual = $(".keyboard-virtual:visible").height() + 5,
			detailvoucher = $(".detail-products:visible").height() + 5,
			fieldsetvoucher = $(".detail-voucher:visible fieldset").height(),
			fieldsetproducts = $(".detail-products:visible fieldset").height();

	if ($(".detail-voucher").is(":visible")) {
		var heightlist = containerproducts - (keyboardvirtual + detailvoucher);
		if (fieldsetvoucher < heightlist) {
			$(".detail-voucher:visible").css({'max-height': heightlist,'min-height': heightlist});
		}
	} else {
		var heightlist = containerproducts - keyboardvirtual;
		if (fieldsetproducts < heightlist) {
			$(".detail-products:visible").css({'max-height': heightlist,'min-height': heightlist});
		}
	}

}

$(document).ready(function() {
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();
	});

	quickmenu($('.quickmenu__item.active'));

	$('body').on('click', 'ul.dropdown-menu.dropdown-menu',function(event){
	    event.stopPropagation();
	});

	$('body').on('click', '.btn-familias', function() {
		$(".btn-familias").removeClass('active');
		$(this).addClass('active');
	});

	$('body').on('click', '.btn-subfamilias', function() {
		$(".btn-subfamilias").removeClass('active');
		$(this).addClass('active');
	});

	$('body').on('click', '.btn-tipodocumento', function() {
		$(".btn-tipodocumento").removeClass('active');
		$(this).addClass('active');
	});

	$('body').on('click', '.btn-mesa', function() {
		$(".btn-mesa").removeClass('active');
		$(this).addClass('active');
	});

	$('body').on('click', '.btn-preventa', function() {
		$(".btn-preventa").removeClass('active');
		$(this).addClass('active');
		$(".container-preventa").hide();
	});


	$('body').on('click', '.quickmenu__item', function() {
		quickmenu($(this))
	});

	$('body').on('click', '.panel-accordion', function() {
		$('.panel-accordion').removeClass('active');
		$(this).addClass('active');
	});

	$('body').on('click', '.list-products .products-preview__photo', function() {
		$('.list-products .products-preview__photo').removeClass('active');
		$(this).addClass('active');
		setTimeout(() => {
			$(this).removeClass('active');
		}, 300);
	});

	$('body').on('click', '.tr-list-products', function() {
		$('.tr-list-products').removeClass('selected');
		$(this).addClass('selected');
		setTimeout(() => {
			$(this).removeClass('selected');
		}, 300);
	});

	$('body').on('click', '.chapternav-link, .link-url', function() {
		window.AllKeys.Clear();
		var $url = $(this).data("url");
		if ($url != undefined) {
			$("#maincontent").removeClass("maincontent");
			$("#loader").show();
			$("#maincontent").load($url, function () {
				$("#loader").hide();
				$("#maincontent").addClass("maincontent");
			})
		}
	});

	$('body').on('click', '.btn-menu-sidebar', function() {
		moversidebar();
	});

	$( window ).resize(function(e) {
		sizeProductList();
		sizeProductDetails();
	});

	function quickmenu(item) {
		var menu = $('.sidebar__menu');
		menu.removeClass('active').eq(item.index()).addClass('active');
		$('.quickmenu__item').removeClass('active');
		item.addClass('active');
		menu.eq(0).css('margin-left', '-'+item.index()*200+'px');
	}

	$('.sidebar li').on('click', function(e) {
		e.stopPropagation();
		var second_nav = $(this).find('.collapse').first();
		if (second_nav.length) {
			second_nav.collapse('toggle');
			$(this).toggleClass('opened');
		}
	});

	$('body.main-scrollable .main__scroll').scrollbar();
	$('.scrollable').scrollbar({'disableBodyScroll' : true});
	$(window).on('resize', function() {
		$('body.main-scrollable .main__scroll').scrollbar();
		$('.scrollable').scrollbar({'disableBodyScroll' : true});
	});

	$('.selectize-dropdown-content').addClass('scrollable scrollbar-macosx').scrollbar({'disableBodyScroll' : true});
	$('.nav-pills, .nav-tabs').tabdrop();

	$('body').on('click', '.header-navbar-mobile__menu button', function() {
		$('.dashboard').toggleClass('dashboard_menu');
	});

	$('.sidestat__chart.sparkline.bar').each(function() {
		$(this).sparkline(
			'html',
			{
				type: 'bar',
				height: '30px',
				barSpacing: 2,
				barColor: '#1e59d9',
				negBarColor: '#ed4949'
			}
		);
	});

	$('.sidestat__chart.sparkline.area').each(function() {
		$(this).sparkline(
			'html',
			{
				width: '145px',
				height: '40px',
				type: 'line',
				lineColor: '#ed4949',
				lineWidth: 2,
				fillColor: 'rgba(237, 73, 73, 0.6)',
				spotColor: '#FF5722',
				minSpotColor: '#FF5722',
				maxSpotColor: '#FF5722',
				highlightSpotColor: '#FF5722',
				spotRadius: 2
			}
		);
	});

	$('.sidestat__chart.sparkline.bar_thin').each(function() {
		$(this).sparkline(
			'html',
			{
				type: 'bar',
				height: '30px',
				barSpacing: 1,
				barWidth: 2,
				barColor: '#FED42A',
				negBarColor: '#ed4949'
			}
		);
	});

	$('.sidestat__chart.sparkline.line').each(function() {
		$(this).sparkline(
			'html',
			{
				type: 'bar',
				height: '30px',
				barSpacing: 2,
				barWidth: 3,
				barColor: '#20c05c',
				negBarColor: '#ed4949'
			}
		);
	});

	$("input.bs-switch").bootstrapSwitch();

	$('.settings-slider').ionRangeSlider({
		decorate_both: false
	});

	if ($('input[type=number]').length) {
		$('input[type=number]').inputNumber({
			mobile: false
		});
	}
});

$("body").on("click", "div.bhoechie-tab-menu>div.list-group>a", function (event) {
	var nombre = $(this).prop('rel');
	event.preventDefault();
	$(this).siblings('a.active').removeClass("active");
	$(this).addClass("active");
	var index = $(this).index();

	$("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
	$("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
});



Number.prototype.DenominacionMonedaSOLES = function() {

  var numero = TruncarDecimal(this, 2);

  var texto = numeroALetras(numero, {
    plural: 'SOLES',
    singular: 'SOL',
    centPlural: 'CENTIMOS',
    centSingular: 'CENTIMO'
  });

  return texto;
}

Number.prototype.DenominacionMonedaDOLARES = function() {

  var numero = TruncarDecimal(this, 2);

  var texto = numeroALetras(numero, {
    plural: 'DOLARES',
    singular: 'DOLAR',
    centPlural: 'CENTAVOS',
    centSingular: 'CENTAVO'
  });

  return texto;
}


jQuery.fn.extend({
  autoDenominacionMoneda : function(data) {
    if(data) {
			var texto = parseFloatAvanzado(data).DenominacionMonedaSOLES();
			this.text(texto);
    }
  }
});

jQuery.fn.extend({
  	zFill : function(number, width) {
			// if (jQuery.isNumeric(number)) {

				// var numberOutput = Math.abs(number); /* Valor absoluto del número */
				var length = number.toString().length; /* Largo del número */
				var zero = "0"; /* String de cero */

				if (width <= length) {
					if (number < 0) {
						return ("-" + number.toString());
					} else {
						return number.toString();
					}
				}
				else {
					if (number < 0) {
						return ("-" + (zero.repeat(width - length)) + number.toString());
					} else {
						return ((zero.repeat(width - length)) + number.toString());
					}
				}
			// }
			// else {
				// return number;
			// }
		}
});

jQuery.fn.extend({
  	disabledElments : function(element, disable) {
		if (element) {
			var elements = $(element).find('input, select, button, textarea, a');
			if (disable) {
				elements.not('.btn-close').attr('disabled', 'disabled');
			} else {
				elements.not('.btn-close, .disabled').removeAttr('disabled');
			}
		}
	}
});

jQuery.fn.extend({
  mostrarAlerta : function(nombreAlerta) {
    if(nombreAlerta) {
			if (nombreAlerta == "error") {
				var id = '#'+this.attr('id');
				mostrarError(id);
				if (nombreAlerta == "warning") {
					var id = '#'+this.attr('id');
					mostrarWarning(id);
					if (nombreAlerta == "success") {
						var id = '#'+this.attr('id');
						mostrarSucces(id);
					}
				}
			}
    }
  },
	quitarAlerta : function(nombreAlerta){
		if (nombreAlerta) {
			if (nombreAlerta=="error") {
				var id = '#'+this.attr('id');
				quitarError(id);
				if (nombreAlerta=="warning") {
					var id = '#'+this.attr('id');
					quitarWarning(id);
					if (nombreAlerta=="success") {
						var id = '#'+this.attr('id');
						quitarSuccess(id);
					}
				}
			}
		}
	},
	paginador : function (data,callback) {
		$("#"+this.attr("id")).pagination({
			items:  data.totalfilas,
			selectPage : data.paginadefecto,
			itemsOnPage: data.numerofilasporpagina,
			cssStyle: theme___pagination,
			onPageClick : function(pageNumber, event)	 {
					data.pagina = pageNumber;
					event = event===undefined ? window : event;
					callback(data,event);
			},
			selectOnClick: true,
			prevText : 'Anterior',
			nextText : 'Siguiente'
		});
	},
	resetearValidaciones : function () {
		var id = '#'+this.attr("id");
		$(id).find('.has-success,.has-error').removeClass('has-error').removeClass('has-success');
		$(id).find('.error,.valid').css('border-color', '').removeClass('error').removeClass('valid');
		$(id).find('.form-error').remove();
	},
	enterToTab : function(event) {
		if(event) {
			var tecla = event.keyCode ? event.keyCode : event.which;
			if(tecla == TECLA_ENTER)  {

				var inputs = $(event.target).closest('form').find('button:not([class*="no-tab"]):visible:enabled,select:not([class*="no-tab"]):visible:enabled,input:not([class*="no-tab"]):visible:enabled,textarea:not([class*="no-tab"]):visible:enabled');
				var selectorindex = inputs.index(event.target)+ 1;
				var selector =inputs.eq(selectorindex);
				var $data = { focused : false, prevented : false};

				if(selector.attr("type")=="button") {
					$data.focused = true;
					$data.prevented =  true;
				}
				else if($(event.target).attr("type")=="button") {
					$data.prevented =  true;
					$(event.target).trigger("click");
				}
				else {
					$data.focused = true;
				}

				if($data.focused) selector.focus();
				if($data.prevented) event.preventDefault();
			}

		}
		return true;
	},
	bloquearSelector : function (blocked) {
		var id = '#'+this.attr("id");
		if (blocked === true) {
			$(id).find('input, textarea, button, select').attr('disabled','disabled');
			$(id).find("table").find("tr").find("td").find('input, textarea, button, select').attr('disabled','disabled');
			$(id).addClass("selector-blocked");
		}
		else {
			$(id).find('input, textarea, button, select').removeAttr('disabled');
			$(id).find("table").find("tr").find("td").find('input, textarea, button, select').removeAttr('disabled');
			$(id).removeClass("selector-blocked");
		}
	}
});

//LOGOUT sistemacomercial
$("body").on('click', '#Logout', function(){
	alertify.confirm("Cierre de Sesión","¿Desea salir realmente del sistema?", function(){
		window.location.href = SITE_URL + "/Seguridad/cSeguridad/Logout";
	},"");
});

$(document).ready(function(){


	$(window).keydown(function(event){
  // $("body").keydown(function(event){
    //alert(event.keyCode);
    //switch (event.keyCode) {
		//PARA DIRECCIONALES
		if(event.ctrlKey && event.altKey)
		{
			var code = event.keyCode;
			if($(".modal").filter(":visible").length > 0)
			{
				if(code == TECLA_ARROW_RIGHT || code == TECLA_ARROW_LEFT)
				{
					return false;
				}
			}
			console.log(event.shiftKey);
			var active = $("#menu").find("li.active");
			if(code == TECLA_ARROW_RIGHT) // ALT + N = Nuevo FAmilia
			{
				active.next(":not(.dropdown)").find("a").click();
				return false;
			}
			else if(code == TECLA_ARROW_LEFT)
			{
				active.prev(":not(.dropdown)").find("a").click();
				return false;
			}
			else if(code == TECLA_ARROW_UP || code == TECLA_ARROW_DOWN){
				window.RecorrerTabla.CorrerOpcion(code);
	      return false;
			}
			else {
				window.RecorrerPaginador.CorrerOpcion(code);
				return false;
			}
		}

		// if (event.altKey)
    // {
		// 	if($(".modal").filter(":visible").length > 0)
		// 	{
		// 		return false;
		// 	}
		// 	var code = event.keyCode;
		// 	if(code == 112)
		// 	{
		//
		// 		return false;
		// 	}
		// }

		//PARA CABECERAS
		if (event.altKey)
    {
			if($(".modal").filter(":visible").length > 0)
			{
				return false;
			}

			var code = event.keyCode;
			var a_link = $("#menu").find("a");
			var item_link = "";
			// console.log(items_a);
			$.each(a_link, function (key2, entrySub) {
				var num_key = $(entrySub).data("key");
				if(num_key == code)
				{
					item_link = $(entrySub).prop("id");
				}
			});
			if(item_link!="")
			{
				document.getElementById(item_link).click();
				return false;
			}
		}

		//PARA SUBOPCIONES
		if (event.altKey)
    {
			if($(".modal").filter(":visible").length > 0)
			{
				return false;
			}

			var code = event.keyCode;
			var active = $("#menu").find("li.active");
			var a_link = $(active).find("a");
			var href_a = $(a_link).prop("name");
			var items_a = $("#"+href_a).find("a");
			var item_link = "";
			$.each(items_a, function (key2, entrySub) {
				var num_key = $(entrySub).data("key");
				if(num_key == code)
				{
					item_link = $(entrySub).prop("id");
				}
			});
			// $("#"+item_link).click();
			// $("#"+item_link).trigger('click');
			if(item_link!="")
			{
				document.getElementById(item_link).click();
			}

			$(".accesos_menu").addClass('text-shadow-menu');
			return false;
		}

		});

		$(window).keyup(function(event){
			var code = event.keyCode;
			if(code == TECLA_ALT)
			{
				$(".accesos_menu").removeClass('text-shadow-menu');
				return false;
			}
		});

});

$.formUtils.addValidator({
	name : 'number_calc',
	validatorFunction : function(value, $el, config, language, $form) {
		// return $el || /^-?(?:\d+|\d{1,3}(?:[\s\.,]\d{3})+)(?:[\.,]\d+)?$/.test(value);
		var val_number = value.replace(/,/g, '');
		if(parseFloat(val_number) > 0.000)
		{
			return true;
		}
		else {
			return false;
		}
	},
	errorMessageKey: 'Ingrese un numero válido'
});

$.formUtils.addValidator({
	name : 'number_positive',
	validatorFunction : function(value, $el, config, language, $form) {
		var val_number = value.replace(/,/g, '');
		return parseFloat(val_number) >= 0.000 ? true : false;
	},
	errorMessageKey: 'Ingrese un numero válido'
});

$.formUtils.addValidator({
	name: "time",
	validatorFunction: function(value) {
		if (null === value.match(/^(\d{2}):(\d{2}):(\d{2})$/)) return !1;
		var b = parseInt(value.split(":")[0], 10),
		c = parseInt(value.split(":")[1], 10),
		d = parseInt(value.split(":")[2], 10);
		return !(b > 23 || c > 59 || d > 59)
	},
	errorMessage: "",
	errorMessageKey: "incorrect time"
});

$.formUtils.addValidator({
	name : 'number_desc',
	validatorFunction : function(value, $el, config, language, $form) {
		// return $el || /^-?(?:\d+|\d{1,3}(?:[\s\.,]\d{3})+)(?:[\.,]\d+)?$/.test(value);
		var val_number = value.replace(/,/g, '');
		if(parseFloat(val_number) >= 0.000)
		{
			return true;
		}
		else {
			return false;
		}
	},
	errorMessageKey: 'Ingrese un numero válido'
});

Array.prototype.uniqueObjects = function (props) {
  function compare(a, b) {
    var prop;
      if (props) {
          for (var j = 0; j < props.length; j++) {
            prop = props[j];
              if (a[prop] != b[prop]) {
                  return false;
              }
          }
      } else {
          for (prop in a) {
              if (a[prop] != b[prop]) {
                  return false;
              }
          }

      }
      return true;
  }
  return this.filter(function (item, index, list) {
      for (var i = 0; i < index; i++) {
          if (compare(item, list[i])) {
              return false;
          }
      }
      return true;
  });
};


// Conclusión
(function() {
	/**
	 * Ajuste decimal de un número.
	 *
	 * @param {String}  tipo  El tipo de ajuste.
	 * @param {Number}  valor El numero.
	 * @param {Integer} exp   El exponente (el logaritmo 10 del ajuste base).
	 * @returns {Number} El valor ajustado.
	 */
	function decimalAdjust(type, value, exp) {
	  // Si el exp no está definido o es cero...
	  if (typeof exp === 'undefined' || +exp === 0) {
		return Math[type](value);
	  }
	  value = +value;
	  exp = +exp;
	  // Si el valor no es un número o el exp no es un entero...
	  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
		return NaN;
	  }
	  // Shift
	  value = value.toString().split('e');
	  value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
	  // Shift back
	  value = value.toString().split('e');
	  return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
	}
  
	// Decimal round
	if (!Math.round10) {
	  Math.round10 = function(value, exp) {
		return decimalAdjust('round', value, exp);
	  };
	}
	// Decimal floor
	if (!Math.floor10) {
	  Math.floor10 = function(value, exp) {
		return decimalAdjust('floor', value, exp);
	  };
	}
	// Decimal ceil
	if (!Math.ceil10) {
	  Math.ceil10 = function(value, exp) {
		return decimalAdjust('ceil', value, exp);
	  };
	}
  })();