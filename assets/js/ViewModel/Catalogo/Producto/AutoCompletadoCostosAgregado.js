var RUTA_DATA_COSTOSAGREGADO = SERVER_URL + URL_JSON_COSTOSAGREGADOS;

function optionsAutoCompletadoCostoAgregado(data,event,callback) {
  var data = data;
  var event = event;
  var callback = callback;

  var options = {
     selectedItemIndexPreview : -1,
     url: RUTA_DATA_COSTOSAGREGADO,
       getValue: function(element) {
        if (element == undefined)
          return "";
        else
         return element.NombreProducto || element;
     },
     list: {
       match: {
           enabled: true
         },
       onChooseEvent: function() {
           var elemento =$(data.id).getSelectedItemData();
           callback(elemento,event);
         },
       onKeyEnterEvent : function() {
         var elemento =$(data.id).getSelectedItemData();
         callback(elemento,event);
       }
     },
     theme: "square"
   };

   return options;
}

//extend jquery autoCompletadoCostoAgregado

jQuery.fn.extend({
  autoCompletadoCostoAgregado: function(data,event,callback) {
    if(event) {
      var options =new optionsAutoCompletadoCostoAgregado(data,event,callback);
      $(data.id).easyAutocomplete(options);
    }
  }
});
