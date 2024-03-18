var RUTA_DATA_DOCUMENTO_INGRESO = SERVER_URL + 'assets/data/compra/documentosingresos.json';

function optionsAutoCompletadoDocumentoIngreso(data,event) {
  var data = data;
  var evento = evento;

  this.options = {
     url: RUTA_DATA_DOCUMENTO_INGRESO,
       getValue: function(element) {
        if (element == undefined)
          return "";
        else
         return element.CodigoTipoDocumento + '  ' + element.DocumentoIngreso ;
     },
     list: {
       match: {
           enabled: true
         },
       onChooseEvent: function() {
           var elemento =$(data).getSelectedItemData();
           if ($('#contenedor_reporte_de_documento_de_ingreso').hasClass('active')) {
             $('#Item_DocumentoIngreso').val(elemento.CodigoTipoDocumento +'  '+elemento.DocumentoIngreso);
             $('#IdDI_DocumentoIngreso').val(elemento.IdDocumentoIngresoZofra);
           }
         },
     },
     theme: "square"
   };

   return this.options;
}
