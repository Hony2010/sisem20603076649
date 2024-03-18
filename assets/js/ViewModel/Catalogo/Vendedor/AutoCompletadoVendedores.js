var RUTA_DATA_VENDEDORES = SERVER_URL + URL_JSON_EMPLEADOS;

function optionsAutoCompletadoVendedor(data,event,callback) {
  var data = data;
  var event = event;
  var callback = callback;

  var options = {
     url: RUTA_DATA_VENDEDORES,
     getValue: function(element) {
      if (element == undefined) {
        return "";
      }
      else {
        if (element.EstadoEmpleado == ESTADO_PROVEEDOR.VISIBLE) {
          return element.NumeroDocumentoIdentidad + " - " + element.NombrePersona;
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
  autoCompletadoVendedor: function(event,callback,target) {
    if(event) {
      var options =new optionsAutoCompletadoVendedor(target,event,callback);
      $(target).easyAutocomplete(options);

    }

  }
});
