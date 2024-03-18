var RUTA_DATA_CLIENTES = SERVER_URL + URL_JSON_CLIENTES;

function optionsAutoCompletadoCliente(data, event, callback, condicional, ParametroPlaca) {
  var data = data;
  var event = event;
  var callback = callback;

  var options = {
    url: RUTA_DATA_CLIENTES,
    getValue: function (element) {
      if (element == undefined)
        return "";
      else {
        if (element.EstadoCliente == ESTADO_CLIENTE.VISIBLE) {
          if (condicional == CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS) {
            if (element.NumeroDocumentoIdentidad == "") {
              return element.RazonSocial
            } else {
              return element.NumeroDocumentoIdentidad + " - " + element.RazonSocial;
            }
          }
          else if (condicional == FILTRO_CLIENTE_SIN_RUC) {
            if (element.CodigoDocumentoIdentidad != CODIGO_TIPO_DOCUMENTO_IDENTIDAD.RUC) {
              if (element.NumeroDocumentoIdentidad == "") {
                return element.RazonSocial
              } else {
                return element.NumeroDocumentoIdentidad + " - " + element.RazonSocial;
              }
            } else {
              return '';
            }
          }
          else {
            if (element.CodigoDocumentoIdentidad == condicional) {
              return element.NumeroDocumentoIdentidad + " - " + element.RazonSocial;
            } else {
              return '';
            }
          }
        } else {
          return "";
        }
      }
    },
    list: {
      match: {
        enabled: true
      },
      onChooseEvent: function () {
        var elemento = $(data).getSelectedItemData();
        callback(elemento, event);
      },
      onKeyEnterEvent: function () {
        var elemento = $(data).getSelectedItemData();
        callback(elemento, event);
      }
    },
    theme: "square"
  };

  return options;
}

//extend jquery autoCompletadoCliente

jQuery.fn.extend({
  autoCompletadoCliente: function (event, callback, condicional, target, ParametroPlaca = 0) {
    if (event) {
      if (target)
        var id = target;
      else
        var id = "#" + this.attr("id");

      var options = new optionsAutoCompletadoCliente(id, event, callback, condicional, ParametroPlaca);
      $(id).easyAutocomplete(options);
    }
  }
});
