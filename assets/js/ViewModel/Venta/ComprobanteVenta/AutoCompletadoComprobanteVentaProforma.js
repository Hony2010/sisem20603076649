var RUTA_DATA_PROFORMA = SERVER_URL +  URL_JSON_PROFORMA;

function optionsAutoCompletadoComprobanteVentaProforma(data,event,callback) {
  var id = data;
  var event = event;
  var callback = callback;
  //var dataComprobantesVentaProforma = data.data;

  var options = {
     selectedItemIndexPreview : -1,
     url:  RUTA_DATA_PROFORMA,
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
           var elemento =$(id).getSelectedItemData();
           callback(elemento,event);
         },
       onKeyEnterEvent : function() {
         var elemento =$(id).getSelectedItemData();
         callback(elemento,event);
       }
     },
     theme: "square"
   };

   return options;
}

jQuery.fn.extend({
  autoCompletadoComprobanteVentaProforma: function(id,event,callback) {
    if(event) {
      var options =new optionsAutoCompletadoComprobanteVentaProforma(id,event,callback);
      $(id).easyAutocomplete(options);
    }
  }
});
