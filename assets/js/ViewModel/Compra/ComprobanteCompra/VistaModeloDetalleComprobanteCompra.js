
VistaModeloDetalleComprobanteCompra = function (data, base) {
  var self = this;
  var cabecera = base;
  ko.mapping.fromJS(data, MappingCatalogo, self);
  self.DecimalCostoUnitario = ko.observable(CANTIDAD_DECIMALES_COMPRA.COSTO_UNITARIO);
  self.DecimalPrecioUnitario = ko.observable(CANTIDAD_DECIMALES_COMPRA.PRECIO_UNITARIO);
  self.DecimalDescuentoUnitario = ko.observable(CANTIDAD_DECIMALES_COMPRA.DESCUENTO_UNITARIO);
  self.DecimalCantidad = ko.observable(CANTIDAD_DECIMALES_COMPRA.CANTIDAD);
  self.CopiaDetalle = ko.observable([]);

  self.NumeroItem = ko.observable(1);

  ModeloDetalleComprobanteCompra.call(this, self);
  self.InicializarVistaModelo = function (event) {
    if (event) {
      self.Producto.InicializarVistaModelo(data, event);
      // var input = {id : self.InputProducto() };
      var input = { id: self.InputProducto(), TipoVenta: TIPO_COMPRA.MERCADERIAS, NombreLargoProducto: cabecera.ParametroRubroRepuesto() };
      if (base.IdTipoDocumento() == base.ParametroTipoDocumentoSalidaZofra()) {
        $(self.InputProducto()).autoCompletadoProducto(input, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.ZOFRA);
      }
      else if (base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_INGRESO || base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_CONTROL) {
        $(self.InputProducto()).autoCompletadoProducto(input, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.DUAZOFRA);
      }
      else if (base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_DUA || base.IdTipoDocumento() == base.ParametroTipoDocumentoDuaAlternativo()) {
        $(self.InputProducto()).autoCompletadoProducto(input, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.DUA);
      }
      else {
        $(self.InputProducto()).autoCompletadoProducto(input, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.GENERAL);
      }
      // $(self.InputProducto()).autoCompletadoProducto(input,event,self.ValidarAutocompletadoProducto);

      $(self.InputFechaVencimiento()).inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });

      self.InicializarModelo(event, cabecera);

      $(self.InputProducto()).on("focusout", function (event) {
        self.ValidarNombreProducto(undefined, event);
      });
    }
  }

  // self.CalculoSubTotal = function( function () {
  //   var resultado =self.CalcularSubTotal();
  //   if(resultado !=="")
  //     return accounting.formatNumber(resultado, CANTIDAD_DECIMALES_COMPRA.SUB_TOTAL);
  //   else
  //     return resultado;
  // },this);

  self.CalculoSubTotal = function (data, event, callback) {
    if (event) {
      self.CalcularSubTotal(data, event);
      self.CalcularDescuentoItem(data, event);
      if (callback) {
        callback(data, event);
      }
        
    }
  }

  self.CalculoPrecioCosto = function (data, event, callback) {
    if (event) {
      self.CalcularPrecioCosto(data, event);
      callback(data, event);
    }
  }

  self.InputCodigoMercaderia = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_CodigoMercaderia";
    }
    else {
      return "";
    }
  }, this);

  self.InputProducto = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_NombreProducto";
    }
    else {
      return "";
    }
  }, this);

  self.InputFechaVencimiento = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_FechaVencimientoLote";
    }
    else {
      return "";
    }
  }, this);

  self.InputNumeroLote = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_NumeroLote";
    }
    else {
      return "";
    }
  }, this);

  self.InputCantidad = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_Cantidad";
    }
    else {
      return "";
    }
  }, this);

  self.InputCodigoProductoProveedor = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_CodigoProductoProveedor";
    }
    else {
      return "";
    }
  }, this);

  self.InputNumeroItemDua = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_ItemDua";
    }
    else {
      return "";
    }
  }, this);

  self.InputCostoUnitario = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_CostoUnitario";
    }
    else {
      return "";
    }
  }, this);

  self.InputPrecioUnitario = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_PrecioUnitario";
    }
    else {
      return "";
    }
  }, this);

  self.InputDescuentoUnitario = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_DescuentoUnitario";
    }
    else {
      return "";
    }
  }, this);

  self.InputPrecioCosto = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_PrecioCosto";
    }
    else {
      return "";
    }
  }, this);

  self.InputTasaDescuentoUnitario = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_TasaDescuentoUnitario";
    }
    else {
      return "";
    }
  }, this);

  self.InputOpcion = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_a_opcion";
    }
    else {
      return "";
    }
  }, this);

  self.OpcionMercaderia = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_opcion_mercaderia";
    }
    else {
      return "";
    }
  }, this);

  self.OnClickFila = function (data, event, callback) {
    if (event) {
      self.Seleccionar(data, event);
      callback(data, event, false);
    }
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      var id = "#" + data.IdDetalleComprobanteCompra();
      $(id).addClass('active').siblings().removeClass('active');
    }
  }

  self.OnFocus = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      self.Seleccionar(data, event);
      callback(data, event, false);
    }

    return true;
  }

  self.OnFocusPrecioUnitario = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      self.Seleccionar(data, event);
      callback(data, event, false);
    }

    return true;
  }

  self.OnKeyEnter = function (data, event, $callbackParent) {
    if (event) {
      $callbackParent(data, event);
    }
    return true;
  }

  self.OnClickBtnOpcion = function (data, event, callback) {
    if (event) {
      var tr = $(data.InputProducto()).closest("tr");
      var $trnext = tr.next();
      if ($trnext.length > 0) {
        var btnOpcion = $trnext.find("button:visible");
        if (btnOpcion.length > 0) {//visible
          var $input = $trnext.find("input[id*=CodigoMercaderia]");
          setTimeout(function () {
            $input.focus();
          }, 250);
        }
        else {
          var $trprev = tr.prev();
          if ($trprev.length > 0) {
            var $input = $trprev.find("input[id*=CodigoMercaderia]");
            setTimeout(function () {
              $input.focus();
            }, 250);
          }
        }
      }

      callback(data, event);
    }
  }

  self.procesado = false;

  self.OnKeyEnterCodigoMercaderia = function (data, event, $callbackParent) {
    if (event) {
      if (event.keyCode === TECLA_ENTER) {

        data.CodigoMercaderia($(event.target).val());
        self.ValidarProductoPorCodigo(data, event, function ($data, $event, $valid) {
          var $evento = { target: self.InputProducto() };
          self.procesado = true;
          self.ValidarNombreProducto(data, $evento);
          $callbackParent($data, $event);
          self.procesado = false;
        });
      }
      return true;
    }
  }

  self.ValidarCodigoMercaderia = function (data, event) {
    if (event) {//focusout
      if (!self.procesado) {

        self.ValidarProductoPorCodigo(data, event, function ($data, $event, $valid) {
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
          $(event.relatedTarget).select();
        });
      }
    }
  }

  self.ValidarProductoPorCodigo = function (data, event, $callback) {
    if (event) {
      if (cabecera.CheckDocumentoSalidaZofra() == true && cabecera.IdTipoDocumento() == cabecera.ParametroTipoDocumentoSalidaZofra() && cabecera.ParametroDocumentoSalidaZofra() == 1) {
        return false;
      }
      if (cabecera.CheckDocumentoSalidaZofra() == true && (cabecera.IdTipoDocumento() == ID_TIPO_DOCUMENTO_DUA || cabecera.IdTipoDocumento() == cabecera.ParametroTipoDocumentoDuaAlternativo())) {
        return false;
      }
      var objetoData = ko.mapping.toJS(data, mappingIgnore);
      var $input = $(self.InputCodigoMercaderia());
      var datajs = { CodigoMercaderia: objetoData.CodigoMercaderia, IdGrupoProducto: TIPO_VENTA.MERCADERIAS };
      var opcionExtra = "";
      if (base.IdTipoDocumento() == base.ParametroTipoDocumentoSalidaZofra()) {
        opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.ZOFRA + '"';
      }
      else if (base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_INGRESO || base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_CONTROL) {
        opcionExtra = ' and (IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.ZOFRA + '" or IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.DUA + '")';
      }
      else if (base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_DUA || base.IdTipoDocumento() == base.ParametroTipoDocumentoDuaAlternativo()) {
        opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.DUA + '"';
      }
      else {
        opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.GENERAL + '"';
      }

      var codigo = String(objetoData.CodigoMercaderia);
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      var json = ObtenerJSONCodificadoDesdeURL(url_json);
      codigo = codigo.toUpperCase();
      var queryBusqueda = '//*[CodigoMercaderia="' + codigo + '" ' + opcionExtra + ']';
      var rpta = JSON.search(json, queryBusqueda);

      if (rpta.length > 0 && rpta[0].EstadoProducto == 0) {
        rpta = [];
      }

      if (rpta.length > 0) {
        var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
        var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);

        cabecera.CopiaIdProductosDetalle.push(producto[0].IdProducto);
        // return false;
        if (data.IdProducto() != producto[0].IdProducto) {
          if (self.ValidarProductoDuplicado(producto[0], event)) {
            var copia = ko.mapping.toJS(self.CopiaDetalle(), mappingIgnore);
            alertify.alert("VALIDACION", "No se permite ingresar el mismo produto varias veces", function () {
              self.CodigoMercaderia(copia.CodigoMercaderia);
              $(event.target).focus();
            });
            return false;
          }
        }
        var descripcionProducto = cabecera.ParametroRubroRepuesto() == 1 ? producto[0].NombreLargoProducto : producto[0].NombreProducto;

        var rptaProductoProveedor = JSON.search(producto[0].ListaProveedores, '//*[IdProveedor="' + cabecera.IdProveedor() + '"]');
        if (rptaProductoProveedor.length > 0) {
          $input.attr("data-validation-found", "true");
          $input.attr("data-validation-text-found", descripcionProducto);
          producto[0].IdProductoProveedor = rptaProductoProveedor[0].IdProductoProveedor;
          producto[0].CodigoProductoProveedor = rptaProductoProveedor[0].CodigoProductoProveedor;
          producto[0].CodigoProductoProveedorSave = 1;
          var item = self.Reemplazar(producto[0]);
        }
        else {
          $input.attr("data-validation-found", "true");
          $input.attr("data-validation-text-found", descripcionProducto);
          producto[0].IdProductoProveedor = "";
          producto[0].CodigoProductoProveedor = "";
          producto[0].CodigoProductoProveedorSave = 1;
          var item = self.Reemplazar(producto[0]);
        }

        self.CopiaDetalle(ko.mapping.toJS(self, mappingIgnore));
      }
      else {
        var item = null;
        $input.attr("data-validation-found", "false");
        $input.attr("data-validation-text-found", "");
      }

      $(event.target).validate(function (valid, elem) {
        if ($callback) $callback(rpta[0], event, valid);
      });

    }
  }

  self.ValidarNombreProducto = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarCodigoPorProvedor = function (data, event) {
    if (event) {
      if (data.CodigoProductoProveedor() != "") {
        var objeto = ko.mapping.toJS(data);
        var detalles = ko.mapping.toJS(cabecera.DetallesComprobanteCompra(), mappingIgnore);
        var rpta = JSON.search(detalles, '//*[CodigoProductoProveedor="' + objeto.CodigoProductoProveedor + '"]');
        if (rpta.length > 1) {
          alertify.alert("VALIDACION", 'El Código: "' + data.CodigoProductoProveedor() + '" ya fue utilizado con el mismo Proveedor', function () {
            data.CodigoProductoProveedor("");
            $(self.InputCodigoProductoProveedor()).focus();
            alertify.alert().destroy();
          });
        }
      }
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarAutocompletadoProducto = function (data, event) {
    if (event) {
      if (cabecera.CheckDocumentoSalidaZofra() == true && cabecera.IdTipoDocumento() == cabecera.ParametroTipoDocumentoSalidaZofra() && cabecera.ParametroDocumentoSalidaZofra() == 1) {
        return false;
      }
      if (cabecera.CheckDocumentoSalidaZofra() == true && (cabecera.IdTipoDocumento() == ID_TIPO_DOCUMENTO_DUA || cabecera.IdTipoDocumento() == cabecera.ParametroTipoDocumentoDuaAlternativo())) {
        return false;
      }
      if (data === -1) {

        if ($(self.InputCodigoMercaderia()).attr("data-validation-text-found") === $(self.InputProducto()).val()) {
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
        }
        else {
          $(self.InputCodigoMercaderia()).attr("data-validation-text-found", "");
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "false");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
        }

        var $inputProducto = $(self.InputProducto());
        var pos = $inputProducto.closest("tr").find("input").index($inputProducto);
        $inputProducto.closest("tr").find("input").eq(pos + 1).focus();
      }
      else {

        if ($(self.InputCodigoMercaderia()).attr("data-validation-text-found") === $(self.InputProducto()).val() && $(self.InputProducto()).val() != "") {
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          var $inputProducto = $(self.InputProducto());
          var pos = $inputProducto.closest("tr").find("input").index($inputProducto);
          $inputProducto.closest("tr").find("input").eq(pos + 1).focus();
        }
        else {
          if (self.IdProducto() != data.IdProducto) {
            if (self.ValidarProductoDuplicado(data, event)) {
              var copia = ko.mapping.toJS(self.CopiaDetalle(), mappingIgnore);
              alertify.alert("VALIDACION", "No se permite ingresar el mismo produto varias veces", function () {
                self.NombreProducto(copia.NombreProducto);
                $(event.target).focus();
              });
              return false;
            }
          }
          // return false;
          $(self.InputCodigoMercaderia()).attr("data-validation-text-found", data.NombreProducto);
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          var $evento2 = { target: self.InputCodigoMercaderia() };
          var $data = Knockout.CopiarObjeto(data);

          self.ValidarProductoPorCodigo($data, $evento2, function ($data3, $evento3) {
            var $inputProducto = $(self.InputProducto());
            var pos = $inputProducto.closest("tr").find("input").index($inputProducto);
            $inputProducto.closest("tr").find("input").eq(pos + 1).focus();
          });
          self.ValidarNombreProducto(data, $evento);
        }
      }
    }
  }

  self.ValidarProductoDuplicado = function (data, event) {
    if (event) {
      if (self.ParametroProductoDuplicado() == 0) {
        var objeto = ko.mapping.toJS(data);
        var detalles = ko.mapping.toJS(cabecera.DetallesComprobanteCompra(), mappingIgnore);
        var rpta = JSON.search(detalles, '//*[IdProducto="' + objeto.IdProducto + '"]');
        if (rpta.length > 0) {
          return true;
        }
        else {
          return false;
        }
      } else {
        return false;
      }
    }
  }

  self.ValidarCantidad = function (data, event) {
    if (event) {
      if (cabecera.CheckDocumentoSalidaZofra() == true && cabecera.IdTipoDocumento() == cabecera.ParametroTipoDocumentoSalidaZofra() && cabecera.ParametroDocumentoSalidaZofra() == 1) {
        var cantidad = parseFloatAvanzado(self.Cantidad());
        var saldoingreso = parseFloatAvanzado(self.SaldoDocumentoIngreso());
        if (cantidad > saldoingreso) {
          self.Cantidad(self.SaldoDocumentoIngreso());
          return false;
        }
      }

      if (data.Cantidad() === "") data.Cantidad("0.00");

      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarCostoUnitario = function (data, event) {
    if (event) {
      if (data.CostoUnitario() === "") data.CostoUnitario("0.0000");

      var descuentounitario = parseFloatAvanzado(data.DescuentoUnitario());
      var costounitario = parseFloatAvanzado(data.CostoUnitario());

      if (descuentounitario > costounitario) {
        alertify.alert('AVISO', 'El Costo Unitario no puede ser mayor al Descuento Unitario', function () {
          data.CostoUnitario(descuentounitario);
          $(event.target).focus();
        });
      }

      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.ValidarPrecioUnitario = function (data, event) {
    if (event) {
      if (data.PrecioUnitario() === "") data.PrecioUnitario("0.00");
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.ValidarFechaVencimiento = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.ValidarLote = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {

      });
      if (self.hasOwnProperty("ListaLotes")) {
        var objeto_lote = ko.mapping.toJS(self.ListaLotes);
        var opcion_lote = false;
        objeto_lote.forEach(function (entry, key) {
          if (entry.NumeroLote == data.NumeroLote()) {
            opcion_lote = true;
          }
        });

        if (opcion_lote) {
          $(self.InputNumeroLote()).closest('div').removeClass('has-new');
        }
        else {
          $(self.InputNumeroLote()).closest('div').addClass('has-new');
        }
      }

    }
  }

  self.ValidarDescuentoUnitario = function (data, event) {
    if (event) {
      if (data.DescuentoUnitario() === "") data.DescuentoUnitario("0.0000");

      var descuentounitario = parseFloatAvanzado(data.DescuentoUnitario());
      var costounitario = parseFloatAvanzado(data.CostoUnitario());

      if (descuentounitario > costounitario) {
        alertify.alert('AVISO', 'El Descuento Unitario no puede ser mayor al Costo Unitario', function () {
          data.DescuentoUnitario(costounitario);
          $(event.target).focus();
        });
      }

      $(event.target).validate(function (valid, elem) {
      });

    }
  }

  self.ValidarPrecioCosto = function (data, event) {
    if (event) {
      if ($(self.InputPrecioUnitario()).length == 1) {
        if (data.CostoItem() === "") data.PrecioUnitario("0.00");
        $(event.target).validate(function (valid, elem) {
        });
      }
      else {
        if (data.PrecioItem() === "") data.CostoUnitario("0.00");
        $(event.target).validate(function (valid, elem) {
        });
      }

    }
  }

  self.OnKeyEnterOpcion = function (data, event, $callbackParent) {
    if (event) {
      if (event.keyCode === TECLA_ENTER) {
        $callbackParent(data, event);
      }
      else if (event.keyCode === TECLA_TAB) {

        var ultimafila = $("tr:last").index();
        var fila = $(event.target).closest("tr").index();

        if (fila === ultimafila - 1) {
          $("#DescuentoGlobal").focus();
          event.preventDefault();
          event.stopPropagation();
        }
      }

      return true;
    }
  }

  self.ValidarAfectoIGV = function (data, event) {
    if (event) {
      if (data.AfectoIGV() === "") data.AfectoIGV("0.00");
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.OnClickBtnNuevaMercaderia = function (data, event, dataMercaderia) {
    if (event) {
      dataMercaderia.OnNuevaMercaderia(dataMercaderia.MercaderiaNueva, event, self.PostCerrarMercaderia);
      return true;
    }
  }

  self.PostCerrarMercaderia = function (data, event) {
    if (event) {
      var producto = cabecera.ObtenerProductoPorIdProducto(Knockout.CopiarObjeto(data), event)
      self.ValidarAutocompletadoProducto(producto, event)
      $(self.InputProducto()).focus()
    }
  }

  self.ValorAfectoIGV = ko.computed(function () {
    var valorAfectoIGV = self.AfectoIGV();
    var value = self.AfectoBonificacion() == '1' && self.ParametroBonificacion() == '1' ? '0' : valorAfectoIGV;
    self.AfectoIGV(value);
    if (cabecera) {
      cabecera.OnEnableDescuentoGlobal();
    }
  }, this)


  self.ValidarTasaDescuentoUnitario = function (data, event) {
    if (event) {
      if (data.TasaDescuentoUnitario() === "") data.TasaDescuentoUnitario("0");
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.OnFocusTasaDescuentoUnitario = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      self.Seleccionar(data, event);
      callback(data, event, false);
    }

    return true;
  }
}
