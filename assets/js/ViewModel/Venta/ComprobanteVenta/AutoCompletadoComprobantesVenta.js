var RUTA_DATA_COMPROBANTE_VENTA = SERVER_URL + URL_JSON_COMPROBANTE_VENTA;

function optionsAutoCompletadoComprobantesVenta(data, event, callback) {
  var id = data;
  var event = event;
  var callback = callback;

  var options = {
    selectedItemIndexPreview: -1,
    url: RUTA_DATA_COMPROBANTE_VENTA,
    getValue: function (element) {
      if (element == undefined) {
        return "";
      } else {
        return element.Documento || element;
      }
    },
    list: {
      match: {
        enabled: true
      },
      onChooseEvent: function () {
        var elemento = $(id).getSelectedItemData();
        callback(elemento, event);
      },
      onKeyEnterEvent: function () {
        var elemento = $(id).getSelectedItemData();
        callback(elemento, event);
      }
    },
    theme: "square"
  };

  return options;
}

jQuery.fn.extend({
  autoCompletadoComprobantesVenta: function (id, event, callback) {
    if (event) {
      var options = new optionsAutoCompletadoComprobantesVenta(id, event, callback);
      $(id).easyAutocomplete(options);
    }
  }
});
