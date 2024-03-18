ModeloBusquedaDocumentoCompra = function (data) {
  var self = this;
  var base = data;

  self.InicializarModelo = function (event) {
    if(event) {

    }
  }

  self.BusquedaDocumentoCompra = function (event,callback, callback2) {
    if (event)  {

      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesBusquedaDocumentoCompra" });
      var datajs =ko.mapping.toJS({"Data" : base },mappingfinal);

      self.BuscarComprobanteCompra(datajs,event,function($data,$event) {
        if($data.error)
          callback($data,$event,callback2);
        else {
          callback($data,$event,callback2);
        }
      });

    }
  }

  self.BuscarComprobanteCompra = function(data,event,callback) {
    if(event) {
      $("#loader").show();
      $.ajax({
        type: 'POST',
        data : data,
        dataType : "json",
        url: SITE_URL+'/Compra/cBusquedaDocumentoCompra/BuscarComprobanteCompra',
        success: function (data) {
          $("#loader").hide();
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

}
