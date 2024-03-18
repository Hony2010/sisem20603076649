VistaModeloConsultaCuentaPago = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingCuentaPago, self);
    ModeloConsultaCuentaPago.call(this,self);

    self.Inicializar = function ()  {
      if(self.data.CuentasPago().length > 0)
      {
        var objeto = self.data.CuentasPago()[0];
        self.Seleccionar(objeto,window);
        var input = ko.toJS(self.data.Filtros);
        $("#Paginador").paginador(input,self.ConsultarPorPagina);
      }
      $("#FechaInicio").inputmask({"mask": "99/99/9999"});
      $("#FechaFin").inputmask({"mask": "99/99/9999"});
    }

    self.Seleccionar = function (data, event) {
      if (event)
      {
        if (data != undefined) {
          var id = "#"+ data.IdComprobanteCaja();
          $(id).addClass('active').siblings().removeClass('active');
        }
      }
    }

    self.OnClickBtnVer = function (data, event) {
      if(event) {
        var options = {};

        switch (data.IdTipoOperacionCaja()) {
          case ID_TIPO_OPERACION_COBRANZA_CLIENTE:
            self.data.CobranzaCliente.OnVer(data, event, self.PostGuardar);
            options = self.data.CobranzaCliente.Options;
          break;
          default:
        }
        $(options.IDModalForm).modal("show");
      }
    }

    self.OnClickBtnEditar = function (data, event) {
      if(event) {
        var options = {};
        switch (data.IdTipoOperacionCaja()) {
          case ID_TIPO_OPERACION_COBRANZA_CLIENTE:
            self.data.CobranzaCliente.OnEditar(data, event, self.PostGuardar);
            options = self.data.CobranzaCliente.Options;
            break;
          default:
        }
        $(options.IDModalForm).modal("show");
      }
    }

    self.OnClickBtnAnular = function (data, event) {
      if (event)  {
        alertify.confirm("Anulación de Comprobante de Caja", "¿Desea anular realmente el comprobante "+data.SerieDocumento()+" - " + data.NumeroDocumento()+"?", function(){
          $("#loader").show();

          switch (data.IdTipoOperacionCaja()) {
            case ID_TIPO_OPERACION_COBRANZA_CLIENTE:
              self.data.CobranzaCliente.AnularCobranzaCliente(data, event, self.PostAnular);
              break;
            default:
          }

        }, function(){ });
      }
    }

    self.OnClickBtnEliminar = function (data,event) {
      if (event) {
        alertify.confirm("Eliminación de Comprobante de Caja", "¿Desea borrar realmente el comprobante "+data.SerieDocumento()+" - " + data.NumeroDocumento()+"?", function(){
          var objeto = ko.mapping.toJS(data);
          objeto.Filtros = ko.mapping.toJS(self.data.Filtros);
          objeto.Filtros.IdCaja = (objeto.Filtros.IdCaja == undefined) ? "%" : objeto.Filtros.IdCaja;
          objeto.Filtros.IdTipoOperacionCaja = (objeto.Filtros.IdTipoOperacionCaja == undefined) ? "%" : objeto.Filtros.IdTipoOperacionCaja;

          switch (data.IdTipoOperacionCaja()) {
            case ID_TIPO_OPERACION_COBRANZA_CLIENTE:
              self.data.CobranzaCliente.EliminarCobranzaCliente(objeto, event, self.PostEliminar);
              break;
            default:
          }
        }, function(){ });
      }
    }

    self.OnClickBtnImprimir = function(data,event) {
      if (event) {
        $("#loader").show();
        self.data.ComprobanteCaja.Imprimir(data,event,function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            alertify.alert("Impresión de Comprobante de Caja","El Comprobante se imprimió correctamente.",function() {})
          } else {
            alertify.alert("HA OCURRIDO UN ERROR",$data.error.msg,function() {});
          }
        });
      }
    }

    self.PostAnular = function (data,event) {
      if(event) {
        $("#loader").hide();
        if(!data.error) {
          var objeto = ko.utils.arrayFirst(self.data.CuentasPago(),function (item) {return item.IdComprobanteCaja() == data.IdComprobanteCaja;});
          objeto.IndicadorEstado(data.IndicadorEstado);
          self.Seleccionar(objeto,event);
          $("#loader").hide();
        } else {
          alertify.alert("HA OCURRIDO UN ERROR",data.error.msg,function() {});
        }
      }
    }

    self.PostEliminar = function (data,event) {
      if(event) {
        if(!data.error) {
          var id =  "#"+data.IdComprobanteCaja;
          var siguienteObjeto = $(id).next();
          if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
          siguienteObjeto.addClass('active').siblings().removeClass('active');
          var objeto = ko.utils.arrayFirst(self.data.CuentasPago(),function (item) {return item.IdComprobanteCaja() == data.IdComprobanteCaja;});
          self.data.CuentasPago.remove(objeto);

          // var filas = self.data.CuentasPago().length;
          // self.data.Filtros.totalfilas(data.Filtros.totalfilas);
          // if (filas == 0) {
          //   $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
          //   var ultimo = $("#Paginador ul li:last").prev();
          //   ultimo.children("a").click();
          // }
        } else {
          $("#loader").hide();
          alertify.alert("CONSULTA COMPROBANTE CAJA",data.error.msg,function(){
          });
        }
      }
    }

    self.PostGuardar = function(data,event) {
      if(event) {
        if(data) {
          var objeto = ko.utils.arrayFirst(self.data.CuentasPago(),function (item) {return item.IdComprobanteCaja() == data.IdComprobanteCaja();});
          var options = {}, item = {};

          switch (data.IdTipoOperacionCaja()) {
            case ID_TIPO_OPERACION_COBRANZA_CLIENTE:
              var copia = ko.mapping.toJS(self.data.CobranzaCliente,mappingIgnore);
              options = self.data.CobranzaCliente.Options;
              self.data.CobranzaCliente.DetallesCobranzaCliente([]);
              item = new VistaModeloCobranzaCliente(copia,options)
              break;
            default:
          }

          self.data.CuentasPago.replace(item, objeto);
          self.Seleccionar(item,event);
          $(options.IDModalForm).modal("hide");
        }
      }
    }

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
          self.ListarCuentasPagoPorPagina(data,event,self.PostConsultarPorPagina);
      }
    }

    self.PostConsultarPorPagina =  function(data,event) {
      if(event) {
        var objeto = self.data.CuentasPago()[0];
        self.Seleccionar(objeto, event);
        $("#Paginador").pagination("drawPage", data.pagina);
      }
    }

    self.Consultar = function (data,event) {
      if(event) {
        var tecla = event.keyCode ? event.keyCode : event.which;
        if(tecla == TECLA_ENTER)
        {
          var inputs = $(event.target).closest('form').find(':input:visible');
          inputs.eq(inputs.index(event.target)+ 1).focus();
          self.ListarCuentasPago(data,event,self.PostConsultar);
        }
      }
    }

    self.PostConsultar = function (data,event) {
      if(event) {
        var objeto = self.data.CuentasPago()[0];
        self.Seleccionar(objeto,event);
        $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
        self.data.Filtros.totalfilas(data.Filtros.totalfilas);
      }
    }
}
