function CargarNotificacionDetallada()
{
  $.notify({
      icon: 'https://randomuser.me/api/portraits/med/men/77.jpg',
      title: 'Byron Morgan',
      message: 'Momentum reduce child mortality effectiveness incubation empowerment connect.'
    },{
      type: 'minimalist',
      delay: 5000,
      icon_type: 'image',
      template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
        '<img data-notify="icon" class="img-circle pull-left">' +
        '<span data-notify="title">{1}</span>' +
        '<span data-notify="message">{2}</span>' +
      '</div>'
    });
}

function CargarNotificacionNormal()
{
  $.notify({
	// options
	message: 'Hello World'
  },{
  	// settings
  	type: 'danger'
  });
}

function LoadNotificacion()
{
  $.notify({
  	// options
  	icon: 'glyphicon glyphicon-warning-sign',
  	title: 'Bootstrap notify',
  	message: 'Turning standard Bootstrap alerts into "notify" like notifications',
  	url: 'https://github.com/mouse0270/bootstrap-notify',
  	target: '_blank'
  },{
  	// settings
  	element: 'body',
  	position: null,
  	type: "info",
  	allow_dismiss: true,
  	newest_on_top: false,
  	showProgressbar: false,
  	placement: {
  		from: "bottom",
  		align: "right"
  	},
  	offset: 20,
  	spacing: 10,
  	z_index: 1031,
  	delay: 5000,
  	timer: 1000,
  	url_target: '_blank',
  	mouse_over: null,
  	animate: {
  		enter: 'animated fadeInDown',
  		exit: 'animated fadeOutUp'
  	},
  	onShow: null,
  	onShown: null,
  	onClose: null,
  	onClosed: null,
  	icon_type: 'class',
  	template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
  		'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
  		'<span data-notify="icon"></span> ' +
  		'<span data-notify="title">{1}</span> ' +
  		'<span data-notify="message">{2}</span>' +
  		'<div class="progress" data-notify="progressbar">' +
  			'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
  		'</div>' +
  		'<a href="{3}" target="{4}" data-notify="url"></a>' +
  	'</div>'
  });
}

function LoadNotificacionEmail(data, callback)
{
  $.notify({
  	// options
  	title: data.title,
  	message: data.message,
  	target: '_blank'
  },{
  	// settings
  	element: 'body',
  	position: null,
  	type: "confim  ",
  	allow_dismiss: true,
  	newest_on_top: false,
  	showProgressbar: false,
  	placement: {
  		from: "bottom",
  		align: "right"
  	},
  	offset: 20,
  	spacing: 10,
  	z_index: 2031,
  	delay: 5000,
  	timer: 6000,
  	url_target: '_blank',
  	mouse_over: null,
  	animate: {
  		enter: 'animated fadeInDown',
  		exit: 'animated fadeOutUp'
  	},
  	onShow: null,
  	onShown: null,
  	onClose: null,
  	onClosed: null,
    onConfirm: function(){callback(true);},
  	icon_type: 'class',
  	template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
  		'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
      '<div class="col-md-12 col-sm-12">'+
      '<div class="form-group text-uppercase">'+
      '<span style = "font-weight: bold;" data-notify="title">{1}</span> ' +
      '</div>'+
      '<div class="form-group">'+
      '<span data-notify="message">{2}</span>' +
      '</div>'+
      '</div>'+
  		'<div class="progress" data-notify="progressbar">' +
  			'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
  		'</div>' +
      '</p>' +
      '<div class="form-group">'+
      '<div class="col-md-6 col-sm-6">'+
      '<button data-notify="confirm" type="button" class="btn btn-primary btn-control">Enviar</button>'+
      '</div>'+
      '<div class="col-md-6 col-sm-6">'+
      '<button data-notify="exit" type="button" class="btn btn-default btn-control">Enviar luego</button>'+
      '</div>'+
      '</div>'+
  	  '</div>'
  });
}

function CargarNotificacionDetallada(data)
{
  $.notify({
	   // options
   title: data.title,
	 message: data.message
  },{
  	// settings
  	type: data.type,
 	  z_index: 9999,
    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert ' + data.clase +' " role="alert">' +
  		'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
  		'<span data-notify="title">{1}</span> ' +
  		'<span data-notify="message">{2}</span>' +
  	'</div>'
  });
}

function CargarNotificacionBusquedaProducto(data)
{
  $.notify({
	   // options
   title: data.title,
	 message: data.message
  },{
  	// settings
  	type: data.type,
    placement: {
  		from: "bottom",
  		align: "right"
  	},
    delay: 600,
    timer: 700,
 	  z_index: 9999,
    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert ' + data.clase +' " role="alert">' +
  		'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
  		'<span data-notify="title">{1}</span> ' +
  		'<span data-notify="message">{2}</span>' +
  	'</div>'
  });
}

function ObtenerJSONDesdeURL(url_json)
{
  var request = new XMLHttpRequest();
  // request.open('GET', url_json, true);
  request.open('GET', url_json, false);
  // request.setRequestHeader("Cache-Control", "no-cache, no-store, must-revalidate");
  // request.setRequestHeader("Access-Control-Allow-Origin", "*");

  var response = {};
  request.onload = function() {
    if (request.status >= 200 && request.status < 400) {
      // Success!
      var data = JSON.parse(request.responseText);//JSON.parse(request.responseText);
      response = data;
    } else {
      // We reached our target server, but it returned an error

    }
  };

  request.onerror = function() {
    // There was a connection error of some sort
  };

  request.send();
  return response;
}

function ObtenerJSONCodificadoDesdeURL(url_json)
{
  var request = new XMLHttpRequest();
  // request.open('GET', url_json, true);
  request.open('GET', url_json, false);
  // request.setRequestHeader("Cache-Control", "no-cache, no-store, must-revalidate");
  // request.setRequestHeader("Access-Control-Allow-Origin", "*");

  var response = {};
  request.onload = function() {
    if (request.status >= 200 && request.status < 400) {
      // Success!
      var data = JSONH.parse(request.responseText);//JSON.parse(request.responseText);
      response = data;
    } else {
      // We reached our target server, but it returned an error

    }
  };

  request.onerror = function() {
    // There was a connection error of some sort
  };

  request.send();
  return response;
}

function removeDuplicates(myArr, prop) {
    return myArr.filter((obj, pos, arr) => {
        return arr.map(mapObj => mapObj[prop]).indexOf(obj[prop]) === pos;
    });
}

function validarEmail(valor) {
  if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(valor)){
   return true;
  } else {
   return false;
  }
}

function isDate(value) {
    switch (typeof value) {
        case 'number':
            return true;
        case 'string':
            return !isNaN(Date.parse(value));
        case 'object':
            if (value instanceof Date) {
                return !isNaN(value.getTime());
            }
        default:
            return false;
    }
}

function parseFloatAvanzado(value)
{
  return parseFloat(String(value).replace(/,/g, ''));
}

//ORDENAMIENTO DE OBJETOS JSON - PARA LA BUSQUEDA DE PRODUCTOS DESDE JAVASCRIPT
function SortByID(x,y) {
  return x.IdProducto - y.IdProducto;
}

function SortByName(x,y) {
  return ((x.NombreProducto == y.NombreProducto) ? 0 : ((x.NombreProducto > y.NombreProducto) ? 1 : -1 ));
}
