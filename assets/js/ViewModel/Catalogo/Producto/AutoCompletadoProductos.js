var RUTA_DATA_MERCADERIAS = SERVER_URL + URL_JSON_MERCADERIAS;
var RUTA_DATA_SERVICIOS = SERVER_URL + URL_JSON_SERVICIOS;
var RUTA_DATA_ACTIVOSFIJO = SERVER_URL + URL_JSON_ACTIVOSFIJOS;
var RUTA_DATA_OTRAVENTA = SERVER_URL + URL_JSON_OTRASVENTAS;

var FuncionBusquedaAvanzada = function (row, val) {
  var f_avanzada = val.indexOf(' ');
  if (f_avanzada != -1) {
    // var l_avanzada = val.replace(/ /g,'').split(",").filter(v=>v!=''); //or val.replace(/\s/g,'')
    var l_avanzada = val.split(" ").filter(v => v != ''); //or val.replace(/\s/g,'')
    if (l_avanzada.length > 0) {
      var compare = true;
      l_avanzada = l_avanzada.map(Function.prototype.call, String.prototype.trim);
      l_avanzada.forEach(function (value, key) {
        var matcher = new RegExp($.ui.autocomplete.escapeRegex(value), "i");
        if (!matcher.test(row)) {
          compare = false;
        }
      });
      return (compare) ? true : false;
    }
    else {
      var matcher = new RegExp($.ui.autocomplete.escapeRegex(val), "i");
      return matcher.test(row);
    }
  }
  else {
    var matcher = new RegExp($.ui.autocomplete.escapeRegex(val), "i");
    return matcher.test(row);
  }
};

function optionsAutoCompletadoProducto(data, event, callback, condicional, codigo) {
  var data = data;
  var event = event;
  var callback = callback;
  var NombreLargoProducto = data.NombreLargoProducto ? data.NombreLargoProducto : 0;
  var CodigoProducto = codigo;
  
  var ruta;
  if (data.TipoVenta == TIPO_VENTA.MERCADERIAS) ruta = RUTA_DATA_MERCADERIAS;
  else if (data.TipoVenta == TIPO_VENTA.SERVICIOS) ruta = RUTA_DATA_SERVICIOS;
  else if (data.TipoVenta == TIPO_VENTA.ACTIVOS) ruta = RUTA_DATA_ACTIVOSFIJO;
  else if (data.TipoVenta == TIPO_VENTA.OTRASVENTAS) ruta = RUTA_DATA_OTRAVENTA;

  if (data.NombreLargoProducto) {
    NombreLargoProducto = data.TipoVenta == TIPO_VENTA.MERCADERIAS ? data.NombreLargoProducto : 0;
  }

  
  var options = {
    selectedItemIndexPreview: -1,
    url: ruta,
    // data: ObtenerJSONCodificadoDesdeURL(ruta),
    getValue: function (element) {
      if (element == undefined) {
        return "";
      }
      else {
        if (element.EstadoProducto == ESTADO_PRODUCTO.VISIBLE) {
          if (condicional == ORIGEN_MERCADERIA.TODOS) {
            return obtenerDescripcion(element, {NombreLargoProducto, CodigoProducto});
          }
          else if (condicional == ORIGEN_MERCADERIA.GENERALVENTA) {
            if (element.IdOrigenMercaderia == ORIGEN_MERCADERIA.GENERAL || element.IdOrigenMercaderia == ORIGEN_MERCADERIA.DUA) {
              return obtenerDescripcion(element, {NombreLargoProducto, CodigoProducto});
            }
            else {
              return "";
            }
          }
          else if (condicional == ORIGEN_MERCADERIA.GENERAL) {
            if (element.IdOrigenMercaderia == ORIGEN_MERCADERIA.GENERAL) {
              return obtenerDescripcion(element, {NombreLargoProducto, CodigoProducto});
            }
            else {
              return "";
            }
          }
          else if (condicional == ORIGEN_MERCADERIA.ZOFRA) {
            if (element.IdOrigenMercaderia == ORIGEN_MERCADERIA.ZOFRA) {
              return obtenerDescripcion(element, {NombreLargoProducto, CodigoProducto});
            }
            else {
              return "";
            }
          }
          else if (condicional == ORIGEN_MERCADERIA.DUA) {
            if (element.IdOrigenMercaderia == ORIGEN_MERCADERIA.DUA) {
              return obtenerDescripcion(element, {NombreLargoProducto, CodigoProducto});
            }
            else {
              return "";
            }
          }
          else if (condicional == ORIGEN_MERCADERIA.DUAZOFRA) {
            if (element.IdOrigenMercaderia == ORIGEN_MERCADERIA.DUA || element.IdOrigenMercaderia == ORIGEN_MERCADERIA.ZOFRA) {
              return obtenerDescripcion(element, {NombreLargoProducto, CodigoProducto});
            }
            else {
              return "";
            }
          }
          else {
            return "";
          }
        } else {
          return "";
        }
      }
    },
    list: {
      match: {
        enabled: true,
        method: function (row, val) {
          return FuncionBusquedaAvanzada(row, val);
        }
      },
      onChooseEvent: function () {
        var elemento = $(data.id).getSelectedItemData();
        callback(elemento, event);
      },
      onKeyEnterEvent: function () {
        var elemento = $(data.id).getSelectedItemData();
        callback(elemento, event);
      }
    },
    theme: "square"
  };

  return options;
}

function obtenerDescripcion (element, condicion) {
  var descripcion = "";
  
  if (condicion.NombreLargoProducto == 1) {
    descripcion = element.NombreLargoProducto;
  } else {
    if (condicion.CodigoProducto == 1) {
      descripcion = `${element.CodigoMercaderia} - ${element.NombreProducto}`;
    } else {
      descripcion = element.NombreProducto;
    }
  }
  return descripcion
}

//extend jquery autoCompletadoProducto

jQuery.fn.extend({
  autoCompletadoProducto: function (data, event, callback, condicional, codigo = 0) {
    if (event) {
      var options = new optionsAutoCompletadoProducto(data, event, callback, condicional, codigo);
      $(data.id).easyAutocomplete(options);
    }
  }
});
