var RUTA_DATA_TRANSPORTISTA = SERVER_URL + URL_JSON_TRANSPORTISTAS;

function optionsAutoCompletadoTransportista(data, event, callback) {
  var data = data;
  var event = event;
  var callback = callback;

  var options = {
    url: RUTA_DATA_TRANSPORTISTA,
    getValue: function (element) {
      if (element == undefined)
        return "";
      else {
        if (element.EstadoTransportista == ESTADO_TRANSPORTISTA.VISIBLE) {
          return element.NumeroDocumentoIdentidad + " - " + element.RazonSocial;
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
  autoCompletadoTransportista: function (id, event, callback) {
    if (event) {
      var options = new optionsAutoCompletadoTransportista(id, event, callback);
      $(id).easyAutocomplete(options);

    }
  }
});
