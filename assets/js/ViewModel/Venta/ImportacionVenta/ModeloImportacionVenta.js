ModeloImportacionVenta = function (data) {
  var self = this;
  var base = data;

  self.InicializarModelo = function (event) {
    if(event) {
      
    }
  }


  self.Actualizar =function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Venta/cVenta/ActualizarVenta',
        success: function (data) {
          callback(data,event);
        },
        error : function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.ValidarListaClienteJSON =function(data,event,callback) {
    if(event) {
      $.ajax({
        async: false,
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Venta/cImportacionVenta/ValidarListadoCliente',
        success: function (data) {
          callback(data,event);
        },
        error : function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.ValidarListaProductoJSON =function(data,event,callback) {
    if(event) {
      $.ajax({
        async: false,
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Venta/cImportacionVenta/ValidarListadoProducto',
        success: function (data) {
          callback(data,event);
        },
        error : function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.InsertarComprobanteVentaJSON =function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Venta/cImportacionVenta/InsertarComprobanteVentaJSON',
        success: function (data) {
          callback(data,event);
        },
        error : function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

}
