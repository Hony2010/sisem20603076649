var RUTA_DATA_PROVEEDORES = SERVER_URL + URL_JSON_PROVEEDORES;

function optionsAutoCompletadoProveedor(data,event,callback) {
  var data = data;
  var event = event;
  var callback = callback;

  var options = {
     url: RUTA_DATA_PROVEEDORES,
     getValue: function(element) {
      if (element == undefined) {
        return "";
      }
      else {
        if (element.EstadoProveedor == ESTADO_PROVEEDOR.VISIBLE) {
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
       onChooseEvent: function() {
           var elemento =$(data).getSelectedItemData();
           callback(elemento,event);
         },
       onKeyEnterEvent : function() {
         var elemento =$(data).getSelectedItemData();
         callback(elemento,event);
       }
     },
     theme: "square"
   };

   return options;
}

//extend jquery autoCompletadoCliente

jQuery.fn.extend({
  autoCompletadoProveedor: function(event,callback,target) {
    if(event) {
      if(target)
        var id =target;
      else
        var id = "#"+this.attr("id");
      var options =new optionsAutoCompletadoProveedor(id,event,callback);
      $(id).easyAutocomplete(options);

    }

  }
});
