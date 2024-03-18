ModeloNotaCredito = function (data) {
  var self = this;
  var base = data;
  
  self.showNotaCredito = ko.observable(false);

  self.NuevoNotaCredito = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaCredito" });
      self.ComprobanteVentaInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);  
      self.titulo = (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_NOTADEVOLUCION) ? "Emisión de Nota Devolución de Venta" : "Emisión de Nota Crédito de Venta";
    }
  }

  self.EditarNotaCredito = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      // var copia = Knockout.CopiarObjeto(data);
      var copia = ko.mapping.toJS(data, { ignore: ['DetallesNotaCredito'] });
      ko.mapping.fromJS(copia, {}, self);
      self.Direccion(copia.Direccion);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaCredito" });
      self.ComprobanteVentaInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo = "Edición de Nota de Credito";
      self.titulo =  (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_NOTADEVOLUCION)  ? "Edición de Nota Devolución de Venta" : "Edición de Nota Crédito de Venta";
    }
  }

  self.CalcularTotalOperacionInafecto = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaCredito(), function (item) {
      if (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_INAFECTO) total += parseFloatAvanzado(item.SubTotal());
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularTotalOperacionNoGravada = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaCredito(), function (item) {
      if (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_EXONERADO) total += parseFloatAvanzado(item.SubTotal());
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularTotal = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaCredito(), function (item) {
      total += parseFloatAvanzado(item.SubTotal() == null ? 0 : item.SubTotal());
    });
    return parseFloatAvanzado(total.toFixed(2));
  }
  

  self.GuardarNotaCredito = function (event, callback) {
    if (event) {

      var _mappingIgnore = ko.toJS(ignore_array_data);//ko.toJS(mappingIgnore);//ignore_array_data
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaCredito" });
      console.log(mappingfinal);
      var datajs = ko.mapping.toJS(base, mappingfinal);

      datajs.MotivoNotaCredito = window.Motivo;
      console.log("GuardarNotaCredito");
      
      if(window.Motivo.Data.CodigoMotivoNotaCredito !="13") {
        if (window.Motivo.Reglas.BorrarDetalles == 1) {
          // ViewModels.data.NotaCredito.DetallesNotaCredito([]);
          var nuevo_detalle = ko.mapping.toJS(ViewModels.data.NotaCredito.NuevoDetalleComprobanteVenta, mappingIgnore);
          nuevo_detalle.IdProducto = datajs.Concepto;
          nuevo_detalle.IdTipoTributo= "1";//20
          nuevo_detalle.CodigoTipoAfectacionIGV = "10";//20
          nuevo_detalle.CodigoTipoSistemaISC = "";// "01";
          nuevo_detalle.CodigoUnidadMedida = "NIU";
          nuevo_detalle.Cantidad = "1.0";
          nuevo_detalle.IGVItem = datajs.IGV;// "0";
          nuevo_detalle.CodigoTipoPrecio = "01";
          nuevo_detalle.NombreProducto = $("#combo-conceptonotacredito :selected").text();
          nuevo_detalle.NumeroItem = 1;
          nuevo_detalle.SubTotal = datajs.Total;
          nuevo_detalle.ValorUnitario = (datajs.Total/1.18);
          nuevo_detalle.ValorVentaItem = datajs.ValorVentaGravado;
          nuevo_detalle.PrecioUnitario = datajs.Total;        
          nuevo_detalle.DescuentoUnitario = 0;
          datajs.DetallesNotaCredito[0] = nuevo_detalle;
        }
      }
      else {
        var nuevo_detalle = ko.mapping.toJS(ViewModels.data.NotaCredito.NuevoDetalleComprobanteVenta, mappingIgnore);
        var totalcuotapago = base.TotalMontoCuotasPago();
        nuevo_detalle.IdProducto = datajs.Concepto;
        nuevo_detalle.IdTipoTributo= "1";//20
        nuevo_detalle.CodigoTipoAfectacionIGV = "10";//20
        nuevo_detalle.CodigoTipoSistemaISC = "";// "01";
        nuevo_detalle.CodigoUnidadMedida = "NIU";
        nuevo_detalle.Cantidad = "1.0";
        nuevo_detalle.IGVItem = 0;//totalcuotapago - (totalcuotapago/1.18);// datajs.IGV;// "0";
        nuevo_detalle.CodigoTipoPrecio = "01";
        nuevo_detalle.NombreProducto = window.Motivo.Data.NombreMotivoNotaCredito;
        nuevo_detalle.NumeroItem = 1;
        nuevo_detalle.SubTotal = 0;// totalcuotapago;
        nuevo_detalle.ValorUnitario = 0;//(totalcuotapago/1.18);
        nuevo_detalle.ValorVentaItem =0;
        nuevo_detalle.PrecioUnitario = 0;//totalcuotapago;        
        nuevo_detalle.DescuentoUnitario = 0;
        var IdDetalleReferencia = null;
        if (datajs.DetallesNotaCredito.length == 0) {
          IdDetalleReferencia = datajs.MiniComprobantesVentaNC[0].DetallesComprobanteVenta[0].IdDetalleReferencia;
        } 
        else {
          IdDetalleReferencia = datajs.DetallesNotaCredito[0].IdDetalleReferencia;
        } 
        nuevo_detalle.IdDetalleReferencia = IdDetalleReferencia;
        datajs.DetallesNotaCredito=[];
        datajs.DetallesNotaCredito[0] = nuevo_detalle;
        //debugger;
      }

      datajs.CodigoMotivoNotaCredito = window.Motivo.Data.CodigoMotivoNotaCredito;
      datajs.NombreMotivoNotaCredito = window.Motivo.Data.NombreMotivoNotaCredito;
      datajs = { Data: JSON.stringify(datajs) };

      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Insertar(datajs, event, function ($data, $event) {
          //debugger;
          if ($data.error)
            callback($data, $event);
          else {
            delete $data.DetallesComprobanteVenta;
            ko.mapping.fromJS($data, MappingVenta, self);
            self.mensaje = "Se registró el comprobante  " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.\n";
            //AGREGAMOS LOS MOTIVOS
            callback($data, $event);

          }
        });
      }
      else {
        self.Actualizar(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            delete $data.DetallesComprobanteVenta;
            self.mensaje = "Se actualizó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.\n";
            callback($data, $event);
          }
        });
      }

    }
  }

  self.Insertar = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Venta/NotaCredito/cNotaCredito/InsertarNotaCredito',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.Actualizar = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Venta/NotaCredito/cNotaCredito/ActualizarNotaCredito',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  if (self.DetallesNotaCredito != undefined) {
    self.DetallesNotaCredito.RemoverDetalle = function (data, event) {
      if (event) {
        this.remove(data);
      }
    }

    self.DetallesNotaCredito.Obtener = function (data, event) {
      if (event) {
        var objeto = ko.utils.arrayFirst(this(), function (item) {
          return data == item.IdDetalleComprobanteVenta();
          // return data == item.IdReferenciaDCV();
        });

        //if(objeto != null)
        objeto.__ko_mapping__ = undefined;
        return objeto;
      }
    }
  }

  self.GenerarXML = function (data, event, callback) {

    if (event) {
      var datajs = ko.mapping.toJS({ "Data": data });

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/NotaCredito/cNotaCredito/GenerarXMLNotaCredito',
        success: function (data) {
          if (data.error) {
            $("#loader").hide();
            alertify.alert("Error Generacion XML de Comprobante de Venta", data.error.msg, function () {
              callback(undefined, event);
            });
          }
          else
            callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert("Error Fatal", jqXHR.responseText, function () {
            callback(undefined, event);
          });
        }
      });
    }
  }

  self.DetallesNotaCredito.AgregarDetalle = function (data, event) {
    if (event) {
      var objeto = null;
      if (data == undefined) {
        objeto = Knockout.CopiarObjeto(base.NuevoDetalleComprobanteVenta);
      }
      else {
        objeto = Knockout.CopiarObjeto(data);
      }

      var resultado = new VistaModeloDetalleNotaCredito(objeto);

      var idMaximo = 0;

      if (this().length > 0) idMaximo = Math.max.apply(null, ko.utils.arrayMap(this(), function (e) { return e.IdDetalleComprobanteVenta(); }));

      resultado.IdDetalleComprobanteVenta(idMaximo + 1);
      this.push(resultado);

      if (self.TipoVenta() == TIPO_VENTA.SERVICIOS) {
        $('#' + resultado.IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.AbreviaturaUnidadMedida()).addClass('no-tab');
        $(resultado.AbreviaturaUnidadMedida()).attr('tabIndex', '-1');
      }

      if (self.TipoVenta() == TIPO_VENTA.ACTIVOS) {
        $('#' + resultado.IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.AbreviaturaUnidadMedida()).addClass('no-tab');
        $(resultado.AbreviaturaUnidadMedida()).attr('tabIndex', '-1');
      }

      if (self.TipoVenta() == TIPO_VENTA.OTRASVENTAS) {
        $('#' + resultado.IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.InputCodigoMercaderia()).closest('td').addClass('ocultar');
        $(resultado.InputCodigoMercaderia()).addClass('no-tab');
        $(resultado.InputCodigoMercaderia()).attr('tabIndex', '-1');
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

  self.DetallesNotaCredito.AgregarDetalleNotaCredito = function (data, event) {
    if (event) {
      var objeto = ko.mapping.toJS(data);

      var resultado = new VistaModeloDetalleNotaCredito(objeto);

      this.push(resultado);

      if (self.TipoVenta() == TIPO_VENTA.SERVICIOS) {
        $('#' + resultado.IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.AbreviaturaUnidadMedida()).addClass('no-tab');
        $(resultado.AbreviaturaUnidadMedida()).attr('tabIndex', '-1');
      }

      if (self.TipoVenta() == TIPO_VENTA.ACTIVOS) {
        $('#' + resultado.IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.AbreviaturaUnidadMedida()).addClass('no-tab');
        $(resultado.AbreviaturaUnidadMedida()).attr('tabIndex', '-1');
      }

      if (self.TipoVenta() == TIPO_VENTA.OTRASVENTAS) {
        $('#' + resultado.IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida').closest('td').addClass('ocultar');
        $(resultado.InputCodigoMercaderia()).closest('td').addClass('ocultar');
        $(resultado.InputCodigoMercaderia()).addClass('no-tab');
        $(resultado.InputCodigoMercaderia()).attr('tabIndex', '-1');
      }

      if (!(self.TipoVenta() == TIPO_VENTA.MERCADERIAS)) {
        // $(resultado.OpcionMercaderia()).closest('td').addClass('ocultar');
        // $(resultado.OpcionMercaderia()).addClass('no-tab');
        // $(resultado.OpcionMercaderia()).attr('tabIndex','-1');
      }

      return resultado;
    }
  }

  self.ActualizarConceptos = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: { "Data": data },
        dataType: "json",
        url: SITE_URL + '/Venta/NotaCredito/cNotaCredito/ObtenerConceptosPorMotivo',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.ConsultarDetallesComprobanteVenta = function (data, event, callback) {
    if (event) {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdComprobanteVenta": $data.IdComprobanteVenta(), "IdTipoVenta": $data.IdTipoVenta(), "IdAsignacionSede": $data.IdAsignacionSede(), "IdMotivoNotaCredito" : $data.IdMotivoNotaCredito() } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/ComprobanteVenta/cDetalleComprobanteVenta/ConsultarDetallesComprobanteVenta',
        success: function (data) {
          self.DetallesNotaCredito([]);
          ko.utils.arrayForEach(data, function (item) {
            // self.DetallesNotaCredito.AgregarDetalle(new VistaModeloDetalleNotaCredito(item),event);
            self.DetallesNotaCredito.AgregarDetalleNotaCredito(new VistaModeloDetalleNotaCredito(item), event);
          });

          callback(self, event);
        }
      });
    }
  }

  self.ConsultarDocumentosReferencia = function (data, event, callback) {
    if (event) {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdComprobanteVenta": $data.IdComprobanteVenta() } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/NotaCredito/cNotaCredito/ConsultarDocumentosReferencia',
        success: function (data) {
          self.MiniComprobantesVentaNC([]);
          ko.utils.arrayForEach(data, function (item) {
            self.MiniComprobantesVentaNC.push(new MiniComprobantesVentaNCModel(item));
          });

          callback(self, event);
        }
      });
    }
  }

  self.EnviarMail = function (data, event, callback) {
    if (event) {
      var datajs = ko.mapping.toJS({ "Data": data });

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/NotaCredito/cNotaCredito/EnviarEmail',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
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
      var rpta = JSON.search(json, '//*[' + _busqueda + '="' + codigo + '"]');
      if (rpta.length > 0) {
        return rpta[0];
      }
      else {
        return null;
      }
    }
  }


}
