var RUTA_DATA_EMPLEADOS = SERVER_URL + URL_JSON_EMPLEADOS;

function optionsAutoCompletadoEmpleado(data,evento) {
  var data = data;
  var evento = evento;

  this.options = {
     url: RUTA_DATA_EMPLEADOS,
       getValue: function(element) {
        if (element == undefined) {
          return "";
        }
        else {
          if (element.EstadoEmpleado == ESTADO_EMPLEADO.VISIBLE) {
            return element.NombrePersona;
          } else {
            return "";
          }
        }
     },
     list: {
       sort:{
         enabled: true
       },
       match: {
           enabled: true
         },
       onChooseEvent: function() {
           var elemento =$(data).getSelectedItemData();
           evento(elemento, window);
         }
     },
     theme: "square"
   };

   return this.options;
}
