function optionsAutoCompletadoPlacaVehiculo(data, event, callback) {
  var data = data;
  var event = event;
  var callback = callback;

  var options = {
    url: SERVER_URL + URL_JSON_VEHICULOS,
    getValue: function (element) {
      debugger
      if (element == undefined) {
        return "";
      }
      else {
        return element.NumeroPlaca;
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

jQuery.fn.extend({
  autoCompletadoPlacaVehiculo: function (data, event, callback) {
    if (event) {
      var options = new optionsAutoCompletadoPlacaVehiculo(data.id, event, callback);
      $(data.id).easyAutocomplete(options);
    }
  }
});
