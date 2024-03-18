var RUTA_DATA_DUA = SERVER_URL + 'assets/data/mercaderia/duaproducto.json';

function optionsAutoCompletadoDuaProducto(data,event) {
  var data = data;
  var evento = evento;

  this.options = {
     url: RUTA_DATA_DUA,
       getValue: function(element) {
        if (element == undefined)
          return "";
        else
         return element.NumeroItemDua+' - '+element.NumeroDua+' - '+element.FechaEmisionDua;
     },
     list: {
       match: {
           enabled: true
         },
       onChooseEvent: function() {
           var elemento =$(data).getSelectedItemData();
           if ($('#contenedor_reporte_de_stock_producto_por_dua').hasClass('active')) {
             $('#Item_StockProductoDua').val(elemento.NumeroItemDua+' - '+elemento.NumeroDua+' - '+elemento.FechaEmisionDua);
             $('#IdDua_StockProductoDua').val(elemento.IdDua);
           }
           else if ($('#contenedor_reporte_de_movimiento_documento_dua').hasClass('active')) {
             $('#Item_MovimientoDocumentoDua').val(elemento.NumeroItemDua+' - '+elemento.NumeroDua+' - '+elemento.FechaEmisionDua);
             $('#IdDua_MovimientoDocumentoDua').val(elemento.IdDua);
           }

         },
     },
     theme: "square"
   };

   return this.options;
}
