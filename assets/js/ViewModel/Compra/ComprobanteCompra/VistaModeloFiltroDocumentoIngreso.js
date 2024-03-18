VistaModeloFiltroDocumentoIngreso = function (data) {
    var self = this;
    var base = undefined;
    // self.parent = ko.observable().syncWith("ComprobanteCompra");
    ko.mapping.fromJS(data, MappingCompra, self);

    self.InicializarVistaModelo = function (data,event, parent) {
      if (event)  {
        base = parent;

        $("#modalBusquedaDocumentoIngreso").find("#fecha-inicio").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
        $("#modalBusquedaDocumentoIngreso").find("#fecha-fin").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      }
    }

    self.OnKeyEnter = function(data,event) {
      if(event)
      {
        // var code = event.keyCode;
        // if(code == TECLA_ENTER)
        // {
        //   var $input = $(event.target);
        //   var lista = $(event.target).closest('fieldset').find('input, button');
        //   var index = lista.index($input);
        //   lista.eq(index+1).focus();
        // }
      }
    }

    self.ValidarFechaInicio = function(data, event){
      if(event) {
        $(event.target).validate(function(valid, elem) {
           //console.log('Element '+elem.name+' is '+( valid ? 'valid' : 'invalid'));
        });
      }
    }

    self.ValidarFechaFin = function(data, event){
      if(event) {
        $(event.target).validate(function(valid, elem) {
           //console.log('Element '+elem.name+' is '+( valid ? 'valid' : 'invalid'));
        });
      }
    }

    self.BuscarDocumento = function(data,event) {
      if(event) {
        base.DocumentosIngreso([]);
        // var _mappingIgnore  =ko.toJS(mappingIgnore);
        // var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesBusquedaDocumentoCompra" });
        var datajs =ko.mapping.toJS({"Data" : data },mappingIgnore);

        self.BuscarDocumentosIngreso(datajs, event, function($data, $event){
          if($data.length > 0)
          {
            $data.forEach(function(entry, key){
              var total = 0;
              entry.DetallesComprobanteCompra.forEach(function(entry, key){
                total += parseFloat(entry.SaldoDocumentoIngreso);
              });

              if(total > 0)
              {
                var objeto = new ModeloDocumentoIngreso(entry);
                base.DocumentosIngreso.push(objeto);
              }
            });
          }
        });
      }
    }

    self.BuscarDocumentosIngreso = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : data,
          dataType : "json",
          url: SITE_URL+'/Compra/ComprobanteCompra/cComprobanteCompra/BuscarDocumentosIngreso',
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
