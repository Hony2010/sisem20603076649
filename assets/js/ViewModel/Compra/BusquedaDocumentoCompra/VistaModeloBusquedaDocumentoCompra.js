VistaModeloBusquedaDocumentoCompra = function (data) {
    var self = this;
    var base = null;
    var $callback = null;
    var $callbackbusqueda = null;
    ko.mapping.fromJS(data, MappingCompra, self);

    ModeloBusquedaDocumentoCompra.call(this,self);

    // var $form = $(options.IDForm);

    self.InicializarVistaModelo = function (data,event, basemodels, callback) {
      if (event)  {
        base = basemodels;
        $callback = callback;
        self.InicializarModelo(event);

        var target = ".ProveedorDocumentoCompra";
        $(".ProveedorDocumentoCompra").autoCompletadoProveedor(event,self.ValidarAutoCompletadoProveedor,target);

        $("#BusquedaDocumentosCompra").find("#btn_AgregarComprobanteCompra").prop("disabled", true);
        $("#BusquedaDocumentosCompra").find("#fecha-inicio").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
        $("#BusquedaDocumentosCompra").find("#fecha-fin").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      }
    }

    self.ValidarProveedor = function(data,event)  {
      if(event) {
        $(event.target).validate(function(valid, elem) {
            if(!valid) {
              self.IdPersona(0);
            }
        });
      }
    }

    self.ValidarAutoCompletadoProveedor = function(data,event) {
        if(event) {

          if(data === -1 ) {
            var $evento = { target : ".ProveedorDocumentoCompra" };
            self.ValidarProveedor(data,$evento);

            $(".ProveedorDocumentoCompra").filter(":visible").closest(".row").find("select")[0].focus();
            // $form.find("#FechaEmision").focus();
          }
          else {
            var $evento = { target : ".ProveedorDocumentoCompra"};
            self.ValidarProveedor(data,$evento);
            //var $data = { IdPersona : }
            data.IdProveedor = data.IdPersona;
            ko.mapping.fromJS(data,MappingCompra,self);

            $("#combo-tipodocumentoreferencia").focus();

          }
        }
    }

    self.OnChangeTipoDocumento = function(data, event){
      if(event){

      }
    }

    self.Buscar = function(data, event, callback){
      if(event)
      {
        if(self.IdPersona() == 0)
        {
          alertify.alert("Busqueda Proveedor", "Por favor, busque un proveedor.", function(){
            setTimeout(function(){
              $("#ProveedorDocumentoCompra").focus();
            }, 300);
          });
          return false;
        }

        $callbackbusqueda = callback;
        $("#BusquedaDocumentosCompra").modal('show');
        self.BusquedaDocumentoCompra(event, self.PostBuscar, callback);
      }
    }

    self.BuscarDocumento = function(data,event) {
      if(event) {
        // self.BusquedaDocumentoCompra(event,self.PostBuscar);
        self.BusquedaDocumentoCompra(event, self.PostBuscar, $callbackbusqueda);
      }
    }

    self.LimpiarBusquedaCompra = function(event)
    {
      if(event)
      {
        base.data.DocumentosCompra([]);
        base.data.DocumentoCompra([]);
        $("#BusquedaDocumentosCompra").find("#btn_AgregarComprobanteCompra").prop("disabled", true);
      }
    }

    self.PostBuscar = function(data, event, callback)
    {
      if(event)
      {
        // console.log(data);
        // console.log(base);
        $("#BusquedaDocumentosCompra").find("#btn_AgregarComprobanteCompra").prop("disabled", true);
        base.data.DocumentosCompra([]);
        ko.utils.arrayForEach(data, function (entry) {
            base.data.DocumentosCompra.push(new ModeloDocumentoCompra(entry, base.data));
        });

        //ELIMINAMOS LAS FACTURAS QUE YA ESTAN EN LA DATA
        var documentos = callback(data, event);
        var filas = documentos().length;
        if(filas > 0)
        {
          ko.utils.arrayForEach(documentos(), function (objeto) {
            base.data.DocumentosCompra.remove( function (item) { return item.IdComprobanteCompra() == objeto.IdComprobanteCompra(); } )
          });
        }

      }
    }

    self.AgregarComprobantesCompra = function(data, event)
    {
      if(event)
      {
        $callback(event, self.PostAgregarComprobantesCompra);
      }
    }

    self.PostAgregarComprobantesCompra = function(event)
    {
      if(event)
      {
        self.LimpiarBusquedaCompra(event);
        $("#BusquedaDocumentosCompra").modal('hide');
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

    self.PostGuardar = function (data,event) {
      if(event) {
        if(data.error) {
          $("#loader").hide();
          alertify.alert("Error en "+ self.titulo,data.error.msg,function(){
          });
        }
        else {
          $("#loader").hide();
          alertify.alert(self.titulo,self.mensaje,function() {
            if (self.callback) self.callback(data,event);
          });
        }
      }
    }

    self.Show = function(event) {
      if(event) {

      }
    }

    self.Hide = function(event) {
      if(event) {
        $("#combo-formaprorrateo").focus();
      }
    }

}
