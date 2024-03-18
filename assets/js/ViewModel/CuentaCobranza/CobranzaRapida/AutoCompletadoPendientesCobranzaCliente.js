var RUTA_DATA_PENDIENTE_COBRANZA_CLIENTE = SERVER_URL + URL_JSON_PENDIENTE_COBRANZA_CLIENTE;

function optionsAutoCompletadoPendientesCobranzaCliente(data,event,callback) {
  var data = data;
  var event = event;
  var callback = callback;

  var options = {
     selectedItemIndexPreview : -1,
     url: RUTA_DATA_PENDIENTE_COBRANZA_CLIENTE,
       getValue: function(element) {
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

jQuery.fn.extend({
  autoCompletadoPendientesCobranzaCliente: function(data,event,callback) {
    if(event) {
      var options =new optionsAutoCompletadoPendientesCobranzaCliente(data,event,callback);
      $(data.id).easyAutocomplete(options);
    }
  }
});
