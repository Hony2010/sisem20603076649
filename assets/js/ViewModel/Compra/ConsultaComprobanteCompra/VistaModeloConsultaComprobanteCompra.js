VistaModeloConsultaComprobanteCompra = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingCompra, self);
    ModeloConsultaComprobanteCompra.call(this,self);

    self.Inicializar = function ()  {
      if(self.data.ComprobantesCompra().length > 0)
      {
        var objeto = self.data.ComprobantesCompra()[0];
        self.Seleccionar(objeto,window);
        var input = ko.toJS(self.data.Filtros);
        $("#Paginador").paginador(input,self.ConsultarPorPagina);
      }
      $("#FechaInicio").inputmask({"mask": "99/99/9999"});
      $("#FechaFin").inputmask({"mask": "99/99/9999"});
    }

    self.Seleccionar = function (data,event)  {
      if (event)
      {
        if (data != undefined) {
          var id = "#"+ data.IdComprobanteCompra();
          $(id).addClass('active').siblings().removeClass('active');
        }
      }
    }

    self.Anular = function(data,event) {
      if (event)  {
        var titulo = "Anulación de Comprobante de Compra";
        var mensaje ="¿Desea anular realmente el comprobante "+data.SerieDocumento()+" - " + data.NumeroDocumento()+"?";

        setTimeout(function() {
          alertify.confirm(titulo,mensaje,
            function(){
              $("#loader").show();
              self.data.ComprobanteCompra.Anular(data,event,self.PostAnular);
            },
            function(){
              $("#loader").hide();
            }
          );
        }, 100);
      }
    }

    self.OnClickBtnVer = function(data,event) {
      if(event) {

        switch(data.IdTipoDocumento()) {

          case ID_TIPO_DOCUMENTO_NOTA_CREDITO:
            self.data.NotaCreditoCompra.OnVer(data,event,self.PostGuardar);
            var options = self.data.NotaCreditoCompra.Options;
            break;
          case ID_TIPO_DOCUMENTO_NOTA_DEBITO:
            self.data.NotaDebitoCompra.OnVer(data,event,self.PostGuardar);
            var options = self.data.NotaDebitoCompra.Options;
            break;
          default:
            if (data.IdTipoCompra() == TIPO_COMPRA.MERCADERIAS ) {
              self.data.ComprobanteCompra.OnVer(data,event,self.PostGuardar);
              var options = self.data.ComprobanteCompra.Options;
            }
            else if (data.IdTipoCompra() == TIPO_COMPRA.GASTOS) {
              self.data.CompraGasto.OnVer(data,event,self.PostGuardar);
              var options = self.data.CompraGasto.Options;
            }
            else if (data.IdTipoCompra() == TIPO_COMPRA.COSTOSAGREGADO) {
              self.data.CompraCostoAgregado.OnVer(data,event,self.PostGuardar);
              var options = self.data.CompraCostoAgregado.Options;
            }
            break;
        }

        setTimeout( function()  {
          $(options.IDModalComprobanteCompra).modal("show");
        },250);
      }
    }

    self.OnClickBtnEditar = function(data,event) {
      if(event) {

        switch(data.IdTipoDocumento()) {
          case ID_TIPO_DOCUMENTO_NOTA_CREDITO:
            self.data.NotaCreditoCompra.Editar(data,event,self.PostGuardar);
            var options = self.data.NotaCreditoCompra.Options;
            break;
          case ID_TIPO_DOCUMENTO_NOTA_DEBITO:
            self.data.NotaDebitoCompra.Editar(data,event,self.PostGuardar);
            var options = self.data.NotaDebitoCompra.Options;
            break;
          default:
            if (data.IdTipoCompra() == TIPO_COMPRA.MERCADERIAS ) {
              self.data.ComprobanteCompra.Editar(data,event,self.PostGuardar);
              var options = self.data.ComprobanteCompra.Options;
            }
            else if (data.IdTipoCompra() == TIPO_COMPRA.GASTOS) {
              self.data.CompraGasto.Editar(data,event,self.PostGuardar);
              var options = self.data.CompraGasto.Options;
            }
            else if (data.IdTipoCompra() == TIPO_COMPRA.COSTOSAGREGADO) {
              self.data.CompraCostoAgregado.Editar(data,event,self.PostGuardar);
              var options = self.data.CompraCostoAgregado.Options;
              self.data.FiltrosCostoAgregado.InicializarVistaModelo(undefined,event,self,self.AgregarComprobantesCompraReferencia);
            }
            break;
        }

        setTimeout( function()  {
          $(options.IDModalComprobanteCompra).modal("show");//"#modalComprobanteCompra"
        },250);
      }
    }

    self.OnClickBtnEditarAlternativo = function(data,event) {
      if(event) {

        switch(data.IdTipoDocumento()) {
          case ID_TIPO_DOCUMENTO_NOTA_CREDITO:
            return false;
            break;
          case ID_TIPO_DOCUMENTO_NOTA_DEBITO:
            return false;
            break;
          default:
            if (data.IdTipoCompra() == TIPO_COMPRA.MERCADERIAS ) {
              if(data.IdTipoDocumento() == ID_TIPO_DOCUMENTO_INGRESO || data.IdTipoDocumento() == ID_TIPO_DOCUMENTO_CONTROL)
              {
                self.data.ComprobanteCompra.EditarAlternativo(data,event,self.PostGuardarAlternativo);
                var options = self.data.ComprobanteCompra.Options;
              }
              else
              {
                return false;
              }
            }
            else if (data.IdTipoCompra() == TIPO_COMPRA.GASTOS) {
              return false;
            }
            else if (data.IdTipoCompra() == TIPO_COMPRA.COSTOSAGREGADO) {
              return false;
            }
            break;
        }

        setTimeout( function()  {
          $(options.IDModalComprobanteCompraAlternativo).modal("show");
        },250);
      }
    }

    self.PostGuardarAlternativo = function(data,event) {
      if(event) {
        if(data) {
          var objeto = ko.utils.arrayFirst(self.data.ComprobantesCompra(),function (item) {return item.IdComprobanteCompra() == data.IdComprobanteCompra;});
          var idtipo = data.IdTipoDocumento;

          switch(idtipo) {
            case ID_TIPO_DOCUMENTO_NOTA_CREDITO:
              break;
            case ID_TIPO_DOCUMENTO_NOTA_DEBITO:
              break;
            default:
              if (data.IdTipoCompra == TIPO_COMPRA.MERCADERIAS) {
                if(data.IdTipoDocumento == ID_TIPO_DOCUMENTO_INGRESO || data.IdTipoDocumento == ID_TIPO_DOCUMENTO_CONTROL)
                {
                  var copiaObjeto = ko.mapping.toJS(self.data.ComprobanteCompra, mappingIgnore);
                  var copia = Object.assign(copiaObjeto, data);
                  var options = self.data.ComprobanteCompra.Options;
                }
              }
              break;
          }

          var resultado = new VistaModeloComprobanteCompra(copia,options);
          self.data.ComprobantesCompra.replace(objeto ,resultado);
          self.Seleccionar(resultado,event);
          $(options.IDModalComprobanteCompraAlternativo).modal("hide");
          $("#loader").hide();
        }
      }
    }

    self.AgregarComprobantesCompraReferencia = function(event, callback)
    {
      if(event)
      {
        ko.utils.arrayForEach(self.data.DocumentoCompra(), function (entry) {
          var cabecera = ko.mapping.toJS(entry, {ignore:["DetallesComprobanteCompra", "__ko_mapping__"]});
          ko.utils.arrayForEach(entry.DetallesComprobanteCompra(), function (entry2) {
            var detalle = ko.mapping.toJS(self.data.CompraCostoAgregado.NuevoDocumentoReferencia, mappingIgnore);
            var entryJS = ko.mapping.toJS(entry2, mappingIgnore);

            var fila = Object.assign(detalle, cabecera, entryJS);
            var objeto = new ModeloDocumentoReferencia(fila, self.data.CompraCostoAgregado);
            self.data.CompraCostoAgregado.DetallesDocumentoReferencia.push(objeto);
          });
        });

        callback(event);
      }
    }

    self.Eliminar = function (data,event) {
      if(event)
      {
        alertify.confirm("Eliminar Comprobante de Compra","¿Desea borrar realmente?",function () {
          var objeto = ko.mapping.toJS(data);
          objeto.Filtros = ko.mapping.toJS(self.data.Filtros);
          self.data.ComprobanteCompra.Eliminar(objeto,event,self.PostEliminar);
        },function () {

        });
      }
    }

    self.PostAnular = function (data,event) {
      if(event)
      {
        if(data)
        {
          debugger;
          var objeto = ko.utils.arrayFirst(self.data.ComprobantesCompra(),function (item) {return item.IdComprobanteCompra() == data.IdComprobanteCompra;});
          //var copia = ko.mapping.toJS(data,mappingIgnore);//self.data.ComprobanteCompra
          //var resultado = new VistaModeloComprobanteCompra(copia);
          //self.data.ComprobantesCompra.replace(objeto ,resultado);
          //ko.mapping.fromJS(resultado,{}, objeto);
          objeto.IndicadorEstado(data.IndicadorEstado);
          self.Seleccionar(objeto,event);
          $("#loader").hide();
        }
      }
    }

    self.PostEliminar = function (data,event) {
      if(event)
      {
        if(data.error) {
          $("#loader").hide();
          alertify.alert("Error en "+ self.titulo,data.error.msg,function(){
          });
        }
        else {
          // var id =  "#"+data.IdComprobanteCompra();
          var id =  "#"+data.IdComprobanteCompra;
          var siguienteObjeto = $(id).next();
          if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
          siguienteObjeto.addClass('active').siblings().removeClass('active');
          var objeto = ko.utils.arrayFirst(self.data.ComprobantesCompra(),function (item) {return item.IdComprobanteCompra() == data.IdComprobanteCompra;});
          self.data.ComprobantesCompra.remove(objeto);

          var filas = self.data.ComprobantesCompra().length;
          self.data.Filtros.totalfilas(data.Filtros.totalfilas);
          if(filas == 0)
          {
            $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
            var ultimo = $("#Paginador ul li:last").prev();
            ultimo.children("a").click();
          }
        }
      }
    }

    self.PostGuardar = function(data,event) {
      if(event) {
        //if (data.EstaProcesado()== true) {
          if(data) {
            var objeto = ko.utils.arrayFirst(self.data.ComprobantesCompra(),function (item) {return item.IdComprobanteCompra() == data.IdComprobanteCompra;});
            var idtipo = data.IdTipoDocumento;

            switch(idtipo) {
              // case ID_TIPO_DOCUMENTO_FACTURA: case ID_TIPO_DOCUMENTO_BOLETA:
              //   if (data.IdTipoCompra == TIPO_COMPRA.MERCADERIAS) {
              //     var copia = ko.mapping.toJS(self.data.ComprobanteCompra,mappingIgnore);
              //     var options = self.data.ComprobanteCompra.Options;
              //   }
              //   else{
              //     var copia = ko.mapping.toJS(self.data.CompraGasto,mappingIgnore);
              //     var options = self.data.CompraGasto.Options;
              //   }
              //   break;
              // case ID_TIPO_DOCUMENTO_BOLETA:
                // var copia = ko.mapping.toJS(self.data.BoletaCompra,mappingIgnore);
                // var options = self.data.BoletaCompra.Options;
                // break;
              case ID_TIPO_DOCUMENTO_NOTA_CREDITO:
                var copia = ko.mapping.toJS(self.data.NotaCreditoCompra,mappingIgnore);
                var options = self.data.NotaCreditoCompra.Options;
                break;
              case ID_TIPO_DOCUMENTO_NOTA_DEBITO:
              var copia = ko.mapping.toJS(self.data.NotaDebitoCompra,mappingIgnore);
              var options = self.data.NotaDebitoCompra.Options;
                break;
              // default :
              //   alertify.alert(self.titulo,"No se encontro pantalla para este tipo, consulte al administrador",function(){});
              //   break;
              default:
                if (data.IdTipoCompra == TIPO_COMPRA.MERCADERIAS) {
                  var copia = ko.mapping.toJS(self.data.ComprobanteCompra,mappingIgnore);
                  var options = self.data.ComprobanteCompra.Options;
                }
                else if(data.IdTipoCompra == TIPO_COMPRA.GASTOS){
                  var copia = ko.mapping.toJS(self.data.CompraGasto,mappingIgnore);
                  var options = self.data.CompraGasto.Options;
                }
                else if (data.IdTipoCompra == TIPO_COMPRA.COSTOSAGREGADO) {
                  var copia = ko.mapping.toJS(self.data.CompraCostoAgregado,mappingIgnore);
                  var options = self.data.CompraCostoAgregado.Options;
                }
                break;
            }

            var resultado = new VistaModeloComprobanteCompra(copia,options);
            self.data.ComprobantesCompra.replace(objeto ,resultado);
            self.Seleccionar(resultado,event);
            $(options.IDModalComprobanteCompra).modal("hide");
            $("#loader").hide();
          }
        }
      //}
    }

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        self.ListarComprobantesCompraPorPagina(data,event,self.PostConsultarPorPagina);
      }
    }

    self.PostConsultarPorPagina =  function(data,event) {
      if(event) {
        var objeto = self.data.ComprobantesCompra()[0];
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
          self.ListarComprobantesCompra(data,event,self.PostConsultar);
        }
      }
    }

    self.PostConsultar = function (data,event) {
      if(event) {
        var objeto = self.data.ComprobantesCompra()[0];
        self.Seleccionar(objeto,event);
        $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
        self.data.Filtros.totalfilas(data.Filtros.totalfilas);
      }
    }
}
