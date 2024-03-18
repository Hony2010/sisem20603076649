var RUTA_DATA_GASTOS = SERVER_URL + URL_JSON_GASTOS;

function optionsAutoCompletadoGasto(data,event,callback) {
  var data = data;
  var event = event;
  var callback = callback;

  var options = {
     selectedItemIndexPreview : -1,
     url: RUTA_DATA_GASTOS,
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

//extend jquery autoCompletadoGasto

jQuery.fn.extend({
  autoCompletadoGasto: function(data,event,callback) {
    if(event) {
      var options =new optionsAutoCompletadoGasto(data,event,callback);
      $(data.id).easyAutocomplete(options);
    }
  }
});
