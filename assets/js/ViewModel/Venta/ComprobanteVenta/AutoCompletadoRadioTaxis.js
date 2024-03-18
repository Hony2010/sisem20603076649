var RUTA_DATA_RADIO_TAXI = SERVER_URL + URL_JSON_RADIO_TAXI;

function optionsAutoCompletadoRadioTaxis(data, event, callback) {
  var id = data;
  var event = event;
  var callback = callback;

  var options = {
    selectedItemIndexPreview: -1,
    url: RUTA_DATA_RADIO_TAXI,
    getValue: function (element) {
      if (element == undefined) {
        return "";
      } else {
        return element.NombreRadioTaxi || element;
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
  autoCompletadoRadioTaxis: function (id, event, callback) {
    if (event) {
      var options = new optionsAutoCompletadoRadioTaxis(id, event, callback);
      $(id).easyAutocomplete(options);
    }
  }
});
