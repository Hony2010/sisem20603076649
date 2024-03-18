ModeloNotaDebito = function (data) {
  var self = this;
  var base = data;

  self.showNotaDebito = ko.observable(false);

  self.NuevoNotaDebito = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesNotaDebito" });
      self.ComprobanteVentaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulonotadebito ="Emision de Nota de Debito";
    }
  }

  self.EditarNotaDebito = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      // var copia = Knockout.CopiarObjeto(data);
      var copia = ko.mapping.toJS(data, {ignore:['DetallesNotaDebito']});
      ko.mapping.fromJS(copia,{},self);
      copia.Direccion(self.Direccion);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesNotaDebito" });
      self.ComprobanteVentaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo ="Edición de Nota de Debito";
    }
  }

  self.GuardarNotaDebito = function (event,callback) {
    if (event)  {

      var _mappingIgnore  = ko.toJS(ignore_array_data);//ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesNotaDebito" });
      var datajs =ko.mapping.toJS(base, mappingfinal);

      datajs.MotivoNotaDebito = window.Motivo.Reglas;

      if(window.Motivo.Reglas.BorrarDetalles == 1)
      {
        var nuevo_detalle = ko.mapping.toJS(ViewModels.data.NotaDebito.NuevoDetalleNotaDebito, mappingIgnore);
        nuevo_detalle.IdProducto = datajs.Concepto;
        nuevo_detalle.CodigoTipoAfectacionIGV = "20";
        nuevo_detalle.CodigoTipoSistemaISC = "01";
        nuevo_detalle.CodigoUnidadMedida = "NIU";
        nuevo_detalle.Cantidad = "1.0";
        nuevo_detalle.IGVItem = "0";
        nuevo_detalle.CodigoTipoPrecio = "01";
        nuevo_detalle.NombreProducto = $("#combo-conceptonotadebito :selected").text();
        nuevo_detalle.SubTotal = datajs.Total;
        nuevo_detalle.ValorUnitario = 0;
        nuevo_detalle.PrecioUnitario = 0;

        datajs.DetallesNotaDebito[0] = nuevo_detalle;
      }

      datajs.CodigoMotivoNotaDebito = window.Motivo.Data.CodigoMotivoNotaDebito;
      datajs.NombreMotivoNotaDebito = window.Motivo.Data.NombreMotivoNotaDebito;
      datajs = {Data: JSON.stringify(datajs)};

      if (self.opcionProceso() == opcionProceso.Nuevo)  {
        self.Insertar(datajs,event,function($data,$event) {
          //debugger;
          if($data.error)
            callback($data,$event);
          else {
            delete $data.DetallesComprobanteVenta;
            ko.mapping.fromJS($data, MappingVenta, self);
            self.mensaje ="Se registró el comprobante  " +self.SerieDocumento() + " - " + self.NumeroDocumento()+ " correctamente.\n";
            //AGREGAMOS LOS MOTIVOS

            callback($data,$event);
          }
        });
      }
      else {
        // datajs.Data.CodigoMotivoNotaDebito = window.Motivo.Data.CodigoMotivoNotaDebito;
        // datajs.Data.NombreMotivoNotaDebito = window.Motivo.Data.NombreMotivoNotaDebito;
        self.Actualizar(datajs,event,function($data,$event) {
          //var objeto = Knockout.CopiarObjeto($data);
          if($data.error)
            callback($data,$event);
          else {
            delete $data.DetallesComprobanteVenta;
            self.mensaje = "Se actualizó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento()+" correctamente.\n";
            callback($data,$event);
          }
        });
      }

    }
  }

  self.Insertar = function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType : "json",
        url: SITE_URL+'/Venta/NotaDebito/cNotaDebito/InsertarNotaDebito',
        success: function (data) {
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.Actualizar =function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Venta/NotaDebito/cNotaDebito/ActualizarNotaDebito',
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

  if (self.DetallesNotaDebito !=undefined)
  {
    self.DetallesNotaDebito.RemoverDetalle = function(data,event)  {
      if(event) {
        this.remove(data);
      }
    }

    self.DetallesNotaDebito.Obtener = function(data,event) {
        if(event) {
          var objeto = ko.utils.arrayFirst(this(), function(item) {
              return data == item.IdDetalleComprobanteVenta();
              // return data == item.IdReferenciaDCV();
          });

          //if(objeto != null)
            objeto.__ko_mapping__ = undefined;
          return objeto;
        }
    }

  }

  self.GenerarXML = function(data,event,callback) {

    if(event)
    {
      var datajs = ko.mapping.toJS({"Data": data });

      $.ajax({
        type: 'POST',
        data : datajs,
        dataType : "json",
        url: SITE_URL+'/Venta/NotaDebito/cNotaDebito/GenerarXMLNotaDebito',
        success: function (data) {
          if (data.error)
          {
            $("#loader").hide();
            alertify.alert("Error Generacion XML de Comprobante de Venta",data.error.msg,function(){
              callback(undefined,event);
            });
          }
          else
            callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert("Error Fatal",jqXHR.responseText,function(){
            callback(undefined,event);
          });
        }
      });
    }
  }

  self.DetallesNotaDebito.AgregarDetalle = function(data,event) {
    if(event) {
      var objeto = null;
      if(data == undefined) {
        objeto = Knockout.CopiarObjeto(base.NuevoDetalleNotaDebito);
      }
      else {
        objeto = Knockout.CopiarObjeto(data);
      }

      var resultado = new VistaModeloDetalleNotaDebito(objeto);

      var idMaximo = 0;

      if (this().length > 0) idMaximo = Math.max.apply(null,ko.utils.arrayMap(this(),function(e){ return e.IdDetalleComprobanteVenta(); }));

      resultado.IdDetalleComprobanteVenta(idMaximo+1);
      this.push(resultado);

      if (self.TipoVenta() == TIPO_VENTA.SERVICIOS) {
        $('#'+resultado.IdDetalleComprobanteVenta()+'_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.AbreviaturaUnidadMedida()).addClass('no-tab');
        $(resultado.AbreviaturaUnidadMedida()).attr('tabIndex','-1');
      }

      if (self.TipoVenta() == TIPO_VENTA.ACTIVOS) {
        $('#'+resultado.IdDetalleComprobanteVenta()+'_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.AbreviaturaUnidadMedida()).addClass('no-tab');
        $(resultado.AbreviaturaUnidadMedida()).attr('tabIndex','-1');
      }

      if (self.TipoVenta() == TIPO_VENTA.OTRASVENTAS) {
        $('#'+resultado.IdDetalleComprobanteVenta()+'_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.InputCodigoMercaderia()).closest('td').addClass('ocultar');
        $(resultado.InputCodigoMercaderia()).addClass('no-tab');
        $(resultado.InputCodigoMercaderia()).attr('tabIndex','-1');
      }

      if (!(self.TipoVenta() == TIPO_VENTA.MERCADERIAS)) {
        // $(resultado.OpcionMercaderia()).closest('td').addClass('ocultar');
        // $(resultado.OpcionMercaderia()).addClass('no-tab');
        // $(resultado.OpcionMercaderia()).attr('tabIndex','-1');
      }

      self.CalcularTotales(event);
      return resultado;
    }
  }

  self.DetallesNotaDebito.AgregarDetalleNotaDebito = function(data,event) {
    if(event) {
      var objeto = ko.mapping.toJS(data);

      var resultado = new VistaModeloDetalleNotaDebito(objeto);

      this.push(resultado);

      if (self.TipoVenta() == TIPO_VENTA.SERVICIOS) {
        $('#'+resultado.IdDetalleComprobanteVenta()+'_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.AbreviaturaUnidadMedida()).addClass('no-tab');
        $(resultado.AbreviaturaUnidadMedida()).attr('tabIndex','-1');
      }

      if (self.TipoVenta() == TIPO_VENTA.ACTIVOS) {
        $('#'+resultado.IdDetalleComprobanteVenta()+'_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.AbreviaturaUnidadMedida()).addClass('no-tab');
        $(resultado.AbreviaturaUnidadMedida()).attr('tabIndex','-1');
      }

      if (self.TipoVenta() == TIPO_VENTA.OTRASVENTAS) {
        $('#'+resultado.IdDetalleComprobanteVenta()+'_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.InputCodigoMercaderia()).closest('td').addClass('ocultar');
        $(resultado.InputCodigoMercaderia()).addClass('no-tab');
        $(resultado.InputCodigoMercaderia()).attr('tabIndex','-1');
      }

      if (!(self.TipoVenta() == TIPO_VENTA.MERCADERIAS)) {
        // $(resultado.OpcionMercaderia()).closest('td').addClass('ocultar');
        // $(resultado.OpcionMercaderia()).addClass('no-tab');
        // $(resultado.OpcionMercaderia()).attr('tabIndex','-1');
      }

      return resultado;
    }
  }

  self.ActualizarConceptos = function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : {"Data": data},
        dataType : "json",
        url: SITE_URL+'/Venta/NotaDebito/cNotaDebito/ObtenerConceptosPorMotivo',
        success: function (data) {
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.ConsultarDetallesComprobanteVenta = function(data,event,callback) {
    if(event)
    {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({"Data": { "IdComprobanteVenta" : $data.IdComprobanteVenta(), "IdTipoVenta": $data.IdTipoVenta(), "IdAsignacionSede": $data.IdAsignacionSede()}});

      $.ajax({
        type: 'GET',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Venta/ComprobanteVenta/cDetalleComprobanteVenta/ConsultarDetallesComprobanteVenta',
        success: function (data) {
            self.DetallesNotaDebito([]);
            ko.utils.arrayForEach(data, function (item) {
              self.DetallesNotaDebito.AgregarDetalleNotaDebito(new VistaModeloDetalleNotaCredito(item),event);
            });

            callback(self,event);
          }
      });
    }
  }

  self.ConsultarDocumentosReferencia = function(data,event,callback) {
    if(event)
    {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({"Data": { "IdComprobanteVenta" : $data.IdComprobanteVenta()}});

      $.ajax({
        type: 'GET',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Venta/NotaDebito/cNotaDebito/ConsultarDocumentosReferencia',
        success: function (data) {
            self.MiniComprobantesVentaND([]);
            ko.utils.arrayForEach(data, function (item) {
              self.MiniComprobantesVentaND.push(new MiniComprobantesVentaNDModel(item));
            });

            callback(self,event);
          }
      });
    }
  }

  self.EnviarMail = function(data,event,callback) {
    if(event) {
      var datajs = ko.mapping.toJS({"Data":data});

      $.ajax({
        type: 'POST',
        data : datajs,
        dataType : "json",
        url: SITE_URL+'/Venta/NotaDebito/cNotaDebito/EnviarEmail',
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

  self.ObtenerFilaClienteJSON = function (data, event) {
    if (event) {
      codigo = data.IdCliente;//data.IdPersona;
      url_json = SERVER_URL + URL_JSON_CLIENTES;
      _busqueda = "IdPersona";

      var json = ObtenerJSONCodificadoDesdeURL(url_json);
      var rpta = JSON.search(json, '//*['+_busqueda+'="'+codigo+'"]');
      if (rpta.length > 0) {
        return rpta[0];
      }
      else {
        return null;
      }
    }
  }


}
