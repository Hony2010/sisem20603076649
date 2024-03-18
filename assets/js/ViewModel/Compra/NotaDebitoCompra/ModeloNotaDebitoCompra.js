ModeloNotaDebitoCompra = function (data) {
  var self = this;
  var base = data;

  self.TipoCompra = ko.observable(base.IdTipoCompra());

  self.showNotaDebitoCompra = ko.observable(false);
  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);

  self.InicializarModelo = function (event) {
    if(event) {
      self.CalcularTotales(event);
    }
  }

  self.RUCDNICliente = ko.computed(function(){
      var resultado ="";
      if(self.NumeroDocumentoIdentidad()=="" || self.RazonSocial()=="")
      {
          resultado = "";
      }
      else {
        resultado = self.NumeroDocumentoIdentidad()+' - '+self.RazonSocial();
      }
      return resultado;
  }, this);

  self.NuevoComprobanteCompra = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetalleNotaDebitoCompra" });
      self.ComprobanteCompraInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo ="Registro de Nota Débito de Compra";
    }
  }

  self.NuevoNotaDebitoCompra = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesNotaDebitoCompra" });
      self.ComprobanteCompraInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulonotadebitocompra ="Emisión de Nota Débito de Compra";
    }
  }

  self.EditarNotaDebitoCompra = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      // var copia = Knockout.CopiarObjeto(data);
      var copia = ko.mapping.toJS(data, {ignore:['DetallesNotaDebitoCompra']});
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesNotaDebitoCompra" });
      self.ComprobanteCompraInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo ="Edición de Nota Débito de Compra";
    }
  }

  self.GuardarNotaDebitoCompra = function (event,callback) {
    if (event)  {

      var _mappingIgnore  = ko.toJS(ignore_array_data);//ko.toJS(mappingIgnore);//ignore_array_data
      var mappingfinal = Object.assign(_mappingIgnore, { include : "DetallesNotaDebitoCompra" });
      console.log(mappingfinal);
      var datajs =ko.mapping.toJS(base,mappingfinal);
      datajs.MotivoNotaDebitoCompra = window.Motivo;
      if(window.Motivo.Reglas.BorrarDetalles == 1)
      {
        var nuevo_detalle = ko.mapping.toJS(self.NuevoDetalleComprobanteCompra, mappingIgnore);
        nuevo_detalle.IdProducto = datajs.Concepto;
        nuevo_detalle.CodigoTipoAfectacionIGV = "40";
        nuevo_detalle.CodigoTipoSistemaISC = "01";
        nuevo_detalle.CodigoUnidadMedida = "NIU";
        nuevo_detalle.Cantidad = "1.0";
        nuevo_detalle.IGVItem = "0";
        nuevo_detalle.CodigoTipoPrecio = "01";
        nuevo_detalle.NombreProducto = $("#combo-conceptonotadebitocompra :selected").text();
        nuevo_detalle.CostoItem = datajs.Total;
        nuevo_detalle.ValorUnitario = "0";
        datajs.DetallesNotaDebitoCompra[0] = nuevo_detalle;
      }

      datajs ={Data: JSON.stringify(datajs)};

      if (self.opcionProceso() == opcionProceso.Nuevo)  {
        self.Insertar(datajs,event,function($data,$event) {
          //debugger;
          if($data.error)
            callback($data,$event);
          else {
            ko.mapping.fromJS($data, MappingCompra, self);
            self.mensaje ="Se registró el comprobante  " +self.SerieDocumento() + " - " + self.NumeroDocumento()+ " correctamente.\n";
            //AGREGAMOS LOS MOTIVOS
            $data.CodigoMotivoNotaDebitoCompra = window.Motivo.Data.CodigoMotivoNotaDebitoCompra;
            $data.NombreMotivoNotaDebitoCompra = window.Motivo.Data.NombreMotivoNotaDebitoCompra;
            callback($data,$event);
          }
        });
      }
      else {
        self.Actualizar(datajs,event,function($data,$event) {
          if($data.error)
            callback($data,$event);
          else {
            self.mensaje = "Se actualizó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento()+" correctamente.\n";
            self.mensaje = self.mensaje + " ¿Desea imprimir el documento?\n";
            self.mensaje = self.mensaje.replace(/\n/g, "<br />");
            $data.CodigoMotivoNotaDebitoCompra = window.Motivo.Data.CodigoMotivoNotaDebitoCompra;
            $data.NombreMotivoNotaDebitoCompra = window.Motivo.Data.NombreMotivoNotaDebitoCompra;
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
        url: SITE_URL+'/Compra/NotaDebitoCompra/cNotaDebitoCompra/InsertarNotaDebitoCompra',
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
        url: SITE_URL+'/Compra/NotaDebitoCompra/cNotaDebitoCompra/ActualizarNotaDebitoCompra',
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

  self.CalcularTotalOperacionGravada = function() {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaDebitoCompra(), function(item) {
        if(item.AfectoIGV() == '1')
        {
          var costoItem = parseFloatAvanzado(item.CostoItem());
          if(!isNaN(costoItem)){
            costoItem = (costoItem == "") ? 0.00 : costoItem;
            total += parseFloatAvanzado(costoItem);
          }
        }
    });
    return parseFloatAvanzado(total.toFixed(NUMERO_DECIMALES_COMPRA));
  }

  self.CalcularTotalOperacionNoGravada = function() {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaDebitoCompra(), function(item) {
        if(item.AfectoIGV() == '0')
        {
          var costoItem = parseFloatAvanzado(item.CostoItem());
          if(!isNaN(costoItem)){
            costoItem = (costoItem == "") ? 0.00 : costoItem;
            total += parseFloatAvanzado(costoItem);
          }
        }
    });
    return parseFloatAvanzado(total.toFixed(NUMERO_DECIMALES_COMPRA));
  }

  self.CalcularDescuentoGlobal = function() {
        return ;
  }

  self.CalcularMontoIGV = function() {
        var ValorCompraGravado = parseFloatAvanzado(base.ValorCompraGravado());
        var monto=ValorCompraGravado * parseFloatAvanzado(base.TasaIGV());
        return parseFloatAvanzado(monto.toFixed(2));
  }

  self.CalcularMontoISC = function() {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaDebitoCompra(), function(item) {
      if(item.AfectoISC() == '1')
      {
        total += parseFloatAvanzado(item.ISCItem());
      }
    });

    return parseFloatAvanzado(total.toFixed(NUMERO_DECIMALES_COMPRA));
  }

  self.CalcularTotal = function () {
      var total = 0;
      ko.utils.arrayForEach(base.DetallesNotaDebitoCompra(), function(item) {
          var CostoItem = parseFloatAvanzado(item.CostoItem());
          total += parseFloatAvanzado(CostoItem);
      });

      var total = total + parseFloatAvanzado(base.IGV());
      return parseFloatAvanzado(total.toFixed(NUMERO_DECIMALES_COMPRA));
  }

  self.CalcularTotales = function(event)  {
    if (event)  {
      var total =self.CalcularTotal();
      base.Total(total);
      var valorcompranogravado = self.CalcularTotalOperacionNoGravada();
      base.ValorCompraNoGravado(valorcompranogravado);
      var valorcompragravado = self.CalcularTotalOperacionGravada();
      base.ValorCompraGravado(valorcompragravado);
      var igv = self.CalcularMontoIGV();
      base.IGV(igv);
    }
  }


  if (self.DetallesNotaDebitoCompra !=undefined)
  {
    self.DetallesNotaDebitoCompra.RemoverDetalle = function(data,event)  {
      if(event) {
        this.remove(data);
      }
    }

    self.DetallesNotaDebitoCompra.Obtener = function(data,event) {
        if(event) {
          var objeto = ko.utils.arrayFirst(this(), function(item) {
              return data == item.IdDetalleComprobanteCompra();
              // return data == item.IdReferenciaDCV();
          });

          //if(objeto != null)
            objeto.__ko_mapping__ = undefined;
          return objeto;
        }
    }
  }

  self.ValidarEstadoComprobanteCompra = function(data,event) {
    if(event) {
      if (data.IndicadorEstado != undefined)  {
        if(data.IndicadorEstado() == "E" ) return false;
        if(data.IndicadorEstado() == "A" ) return false;
      }

      return true;
    }
  }

  self.DetallesNotaDebitoCompra.AgregarDetalle = function(data,event) {
    if(event) {
      var objeto = null;
      if(data == undefined) {
        objeto = Knockout.CopiarObjeto(base.NuevoDetalleComprobanteCompra);
      } else {
        objeto = Knockout.CopiarObjeto(data);
      }
      objeto.Cantidad(objeto.Cantidad() === "" ? '1.00' : objeto.Cantidad());

      var resultado = new VistaModeloDetalleNotaDebitoCompra(objeto, self);

      var idMaximo = 0;

      if (this().length > 0) idMaximo = Math.max.apply(null,ko.utils.arrayMap(this(),function(e){ return e.IdDetalleComprobanteCompra(); }));

      resultado.IdDetalleComprobanteCompra(idMaximo+1);
      this.push(resultado);

      resultado.OcultarCamposTipoCompra(self, event);

      self.CalcularTotales(event);
      return resultado;
    }
  }

  self.ActualizarConceptos = function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : {"Data": data},
        dataType : "json",
        url: SITE_URL+'/Compra/NotaDebitoCompra/cNotaDebitoCompra/ObtenerConceptosPorMotivo',
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

  self.ConsultarDetalleNotaDebitoCompra = function(data,event,callback) {
    if(event)
    {
      var $data = ko.mapping.toJS(data);
      var datajs = ko.mapping.toJS({"Data": { "IdComprobanteCompra" : $data.IdComprobanteCompra, "IdProveedor": $data.IdProveedor,"IdAsignacionSede": $data.IdAsignacionSede,"IdTipoCompra": $data.IdTipoCompra,"IdSede" : $data.IdSede }});

      $.ajax({
        type: 'GET',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Compra/ComprobanteCompra/cDetalleComprobanteCompra/ConsultarDetallesComprobanteCompra',
        success: function (data) {
            self.DetallesNotaDebitoCompra([]);
            ko.utils.arrayForEach(data, function (item) {
              self.DetallesNotaDebitoCompra.AgregarDetalle(new VistaModeloDetalleNotaDebitoCompra(item, self),event);
            });

            callback(self,event);
          }
      });
    }
  }

  self.ConsultarDocumentosReferencia = function(data,event,callback) {
    if(event)
    {
      var $data = ko.mapping.toJS(data);
      var datajs = ko.mapping.toJS({"Data": { "IdComprobanteCompra" : $data.IdComprobanteCompra}});

      $.ajax({
        type: 'GET',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Compra/NotaDebitoCompra/cNotaDebitoCompra/ConsultarDocumentosReferencia',
        success: function (data) {
            self.MiniComprobantesCompraND([]);
            ko.utils.arrayForEach(data, function (item) {
              self.MiniComprobantesCompraND.push(new MiniComprobantesCompraNDModel(item));
            });

            callback(self,event);
          }
      });
    }
  }

  self.ObtenerDataFiltro = function (data, event) {
    if (event) {
      var item = data;
      var data_json = window.DataMotivosNotaDebitoCompra;
      var rpta = JSPath.apply('.{.Data{.IdMotivoNotaDebito == $Texto}}', data_json, {Texto: item});
      if (rpta.length > 0)  {
        return rpta[0];
      } else {
        return null;
      }
    }
  }

}
