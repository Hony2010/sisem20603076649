function optionsAutoCompletadoComprobanteVentaReferencia(data,event,callback) {
  var data = data;
  var event = event;
  var callback = callback;
  var dataComprobantesVentaReferencia = data.data;

  var options = {
     selectedItemIndexPreview : -1,
     data: dataComprobantesVentaReferencia,
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
  autoCompletadoComprobanteVentaReferencia: function(data,event,callback) {
    if(event) {
      var options =new optionsAutoCompletadoComprobanteVentaReferencia(data,event,callback);
      $(data.id).easyAutocomplete(options);
    }
  }
});
