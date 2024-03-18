var RUTA_DATA_ZOFRA = SERVER_URL + 'assets/data/mercaderia/documentosalidazofra.json';

function optionsAutoCompletadoDocumentoZofra(data,event) {
  var data = data;
  var evento = evento;

  this.options = {
     url: RUTA_DATA_ZOFRA,
       getValue: function(element) {
        if (element == undefined)
          return "";
        else
         return element.NumeroDocumentoSalidaZofra;
     },
     list: {
       match: {
           enabled: true
         },
       onChooseEvent: function() {
           var elemento =$(data).getSelectedItemData();
           if ($('#contenedor_reporte_de_stock_producto_por_documento_zofra').hasClass('active')) {
             $('#Item_StockProductoDocumentoZofra').val(elemento.NumeroDocumentoSalidaZofra);
             $('#IdDocumentoSalidaZofra_StockProductoDocumentoZofra').val(elemento.IdDocumentoSalidaZofra);
           }
           else if ($('#contenedor_reporte_de_movimiento_documento_zofra').hasClass('active')) {
             $('#Item_MovimientoDocumentoZofra').val(elemento.NumeroDocumentoSalidaZofra);
             $('#IdDocumentoSalidaZofra_MovimientoDocumentoZofra').val(elemento.IdDocumentoSalidaZofra);
           }

         },
     },
     theme: "square"
   };

   return this.options;
}
