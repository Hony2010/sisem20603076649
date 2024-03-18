ModeloDocumentoCompra = function (data, base) {
  var self = this;
  var base = base;

  ko.mapping.fromJS(data, {}, self);

  self.EstadoSelector = ko.observable(false);
  self.Documento = ko.observable(data.SerieDocumento + "-" + data.NumeroDocumento);

  self.InicializarModelo = function (event, data) {
    if(event) {

    }
  }

  self.CambiarEstadoCheck = function (data, event) {
    if(event)
    {
      var id = "#"+data.IdComprobanteCompra()+'_tr_comprobantecompraproveedor';
      var objeto = Knockout.CopiarObjeto(data);

      if (data.EstadoSelector() == true)
      {
        $(id).addClass('active');
        base.DocumentoCompra.push(new ModeloDocumentoCompra(objeto, base));
      }
      else
      {
        $(id).removeClass('active');
        base.DocumentoCompra.remove( function (item) { return item.IdComprobanteCompra() == objeto.IdComprobanteCompra(); } )
      }
      self.ActualizarBotonAgregar(event);

    }

  }

  self.ActualizarBotonAgregar = function(event)
  {
    if(event)
    {
      var length = base.DocumentoCompra().length;
      if(length > 0)
      {
        $("#BusquedaDocumentosCompra").find("#btn_AgregarComprobanteCompra").prop("disabled", false);
      }
      else {
        $("#BusquedaDocumentosCompra").find("#btn_AgregarComprobanteCompra").prop("disabled", true);
      }

    }
  }

}
