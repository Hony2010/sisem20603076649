VistaModeloComprobanteVenta = function (data, options) {
  var self = this;
  ko.mapping.fromJS(data, MappingVenta, self);
  self.TotalItems = ko.observable('0');
  self.CheckNumeroDocumento = ko.observable(false);
  self.CalcularMontoTotalACuenta = ko.observable(true);
  self.TotalCantidades = ko.observable("0.00");
  //self.IdTipoPrecioEspecialCliente = ko.observable("");

  if (self.hasOwnProperty('ParametroCamposConEnvioYGestion')) {
    // do stuff
    self.NombreCampoSubTotal = ko.observable(self.ParametroCamposConEnvioYGestion() == '1' ? "Sumatoria" : "SubTotal");
  }
  else {
    self.NombreCampoSubTotal = ko.observable("");
  }

  self.Options = options;
  ModeloComprobanteVenta.call(this, self);
  var $form = $(options.IDForm);

  self.DivFooterVenta = ko.pureComputed(function () {
    if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
      if (self.ParametroCamposConEnvioYGestion() == '1' && self.ParametroCampoACuenta() == '1') {
        return "col-md-2";
      } else if (self.ParametroCamposConEnvioYGestion() == '1' && self.ParametroCampoACuenta() == '0') {
        return "col-md-4";
      } else if (self.ParametroCamposConEnvioYGestion() == '0' && self.ParametroCampoACuenta() == '1') {
        return "col-md-4";
      } else {
        return "col-md-6";
      }
    }
    else {
      if (self.ParametroCampoACuenta() == '1') {
        return "";
      } else {
        if (self.IdTipoVenta() == TIPO_VENTA.SERVICIOS) {
          return "";
        } else {
          return "";
          // return "col-md-2";
        }
      }
    }
  });

  self.IndicadorReseteoFormulario = true;

  self.InicializarVistaModelo = function (data, event, editar = true) {
    if (event) {
      self.InicializarModelo(event);
      var copiaSede = Knockout.CopiarObjeto(self.CopiaSedes());
      self.Sedes(copiaSede());
      var target = options.IDForm + " " + "#Cliente";
      if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
        $form.find("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.RUC, target);
      } else if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && self.ParametroFiltroClienteSinRuc() == 1) {
        $form.find("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, FILTRO_CLIENTE_SIN_RUC, target);
      }
      else {
        $form.find("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, target);
      }


      var target2 = options.IDForm + " " + "#RazonSocialDestinatario";
      $form.find("#RazonSocialDestinatario").autoCompletadoCliente(event, self.ValidarAutoCompletadoRazonSocialDestinatario, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.DNI, target2);

      if (self.ParametroRubroLubricante() == 1) {
        var dataNumeroPlaca = { id: options.IDForm + " " + "#NumeroPlaca" };
        $form.find("#NumeroPlaca").autoCompletadoPlacaVehiculo(dataNumeroPlaca, event, self.ValidarAutoCompletadoNumeroPlacaVehiculo);
      }

      $form.find("#NumeroDocumento").attr("readonly", true);
      $form.find("#CheckNumeroDocumento").prop("checked", false);

      $form.find("#FechaEmision").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $form.find("#FechaVencimiento").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $form.find("#FechaMovimientoAlmacen").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $form.find(".fecha").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      self.InicializarValidator(event);

      $form.find("#Cliente").on("focusout", function (event) {
        self.ValidarCliente(undefined, event);
      });

      $form.find("#RazonSocialDestinatario").on("focusout", function (event) {
        self.ValidarRazonSocialDestinatario(undefined, event);
      });

      self.OnRefrescar(data, event, true);
      self.CambiosFormulario(false);
      self.CambiarFechaVencimiento(data, event);
      self.IdTipoVenta(self.TipoVenta());

      if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
        self.Sedes.remove(function (item) { return item.IndicadorAlmacenZofra() == 1 });
      } else if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA) {
        if (self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z) {
          self.Sedes.remove(function (item) { return item.IndicadorAlmacenZofra() == 0 });
        } else {
          self.Sedes.remove(function (item) { return item.IndicadorAlmacenZofra() == 1 });
        }
      } else if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
        self.Sedes.remove(function (item) { return item.IndicadorAlmacenZofra() == 1 });
      }

      // if (self.Sedes().length > 0) {
      //   self.IdAsignacionSede(self.Sedes()[0].IdAsignacionSede())
      // }

      if (editar) {
        if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
          if (self.ParametroVistaVenta() == 1 || !(self.TipoVenta() == TIPO_VENTA.SERVICIOS)) {
            $form.find("#CheckCliente").prop("checked", false);
          } else {
            $form.find("#CheckCliente").prop("checked", true);
          }
        }
        self.CambiarClientesVarios(event);
      }

      if (self.ParametroRubroLubricante() == 1) {
        var inputRadioTaxi = options.IDForm + " " + "#NombreRadioTaxi";
        $form.find("#NombreRadioTaxi").autoCompletadoRadioTaxis(inputRadioTaxi, event, self.ValidarAutoCompletadoRadioTaxi);
      }

      self.ObtenerComprobantesVentaReferencia(data, event);
      //self.ObtenerComprobantesVentaProforma(data, event);

      //var objeto = { id: "#DocumentoVentaProforma", data: $data }
      var IdTarget = options.IDForm + " " + "#DocumentoVentaProforma";
      $form.find("#DocumentoVentaProforma").autoCompletadoComprobanteVentaProforma(IdTarget, event, self.ValidarAutoCompletadoComprobanteVentaProforma);
      //var target = `${self.Options.IdForm}  #AutocompletadoReferencia`;
      //$(target).autoCompletadoComprobantesVenta(target, event, self.ValidarAutoCompletadoDocumentoReferencia);

      self.ObtenerTipoCambioSunat(self, event);

      AccesoKey.AgregarKeyOption(options.IDForm, "#btn_Grabar", TECLA_G);
    }
  }

  self.InicializarValidator = function (event) {
    if (event) {

      $.formUtils.addValidator({
        name: 'autocompletado',
        validatorFunction: function (value, $el) {
          var texto = $el.attr("data-validation-text-found");
          var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
          return resultado;
        },
        errorMessageKey: 'autocompletado'
      });

      $.formUtils.addValidator({
        name: 'autocompletado_opcional',
        validatorFunction: function (value, $el) {
          var texto = $el.attr("data-validation-text-found");
          var resultado = (value.toUpperCase() === texto.toUpperCase()) ? true : false;
          return resultado;
        },
        errorMessageKey: 'autocompletado_opcional'
      });

      $.formUtils.addValidator({
        name: 'autocompletado_cliente',
        validatorFunction: function (value, $el, config, language, $form) {
          var texto = $el.attr("data-validation-text-found");
          var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
          if (resultado == false) {
            $form.find("#combo-alumno").empty();
          }
          return resultado;
        },
        errorMessageKey: 'badautocompletado_cliente'
      });

      $.formUtils.addValidator({
        name: 'autocompletado_RazonSocialDestinatario',
        validatorFunction: function (value, $el, config, language, $form) {
          var texto = $el.attr("data-validation-text-found");
          var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
          return resultado;
        },
        errorMessageKey: 'autocompletado_RazonSocialDestinatario'
      });

      $.formUtils.addValidator({
        name: 'fecha_vencimiento',
        validatorFunction: function (value, $el, config, language, $form) {
          if (value !== "") {
            var dateFormat = $el.valAttr('format') || config.dateFormat || 'yyyy-mm-dd';
            var addMissingLeadingZeros = $el.valAttr('require-leading-zero') === 'false';
            return $.formUtils.parseDate(value, dateFormat, addMissingLeadingZeros) !== false;
          }
          else {
            if (self.IdFormaPago() === ID_FORMA_PAGO_CREDITO)
              return false;
            else
              return true;
          }
        }
      });

      $.formUtils.addValidator({
        name: 'validacion_producto',
        validatorFunction: function (value, $el, config, language, $form) {
          var texto = $el.attr("data-validation-found");
          var resultado = ("true" === texto) ? true : false;
          return resultado;
        },
        errorMessageKey: 'badvalidacion_producto'
      });

      $.formUtils.addValidator({
        name: 'autocompletado_producto',
        validatorFunction: function (value, $el, config, language, $form) {
          var $referencia = $("#" + $el.attr("data-validation-reference"));
          var texto = $referencia.attr("data-validation-text-found").toUpperCase();
          var resultado = (value.toUpperCase() === texto && value.toUpperCase() !== "") ? true : false;
          return resultado;
        },
        errorMessageKey: 'badautocompletado_producto'
      });
      $.formUtils.addValidator({
        name: 'required_lote',
        validatorFunction: function (value, $el, config, language, $form) {
          var texto = $el.attr("data-validation-found");
          var resultado = ("true" === texto) ? true : false;
          return resultado;
        },
        errorMessageKey: 'badvalidacion_lote'
      });
      $.formUtils.addValidator({
        name: 'required_zofra',
        validatorFunction: function (value, $el, config, language, $form) {
          var texto = $el.attr("data-validation-found");
          var resultado = ("true" === texto) ? true : false;
          return resultado;
        },
        errorMessageKey: 'badvalidacion_zofra'
      });
      $.formUtils.addValidator({
        name: 'required_dua',
        validatorFunction: function (value, $el, config, language, $form) {
          var texto = $el.attr("data-validation-found");
          var resultado = ("true" === texto) ? true : false;
          return resultado;
        },
        errorMessageKey: 'badvalidacion_dua'
      });

    }
  }

  self.InicializarVistaModeloDetalle = function (data, event) {
    if (event) {
      var item;

      if (self.DetallesComprobanteVenta().length > 0) {
        ko.utils.arrayForEach(self.DetallesComprobanteVenta(), function (el) {
          el.InicializarVistaModelo(event);
        });
      }

      var item = self.DetallesComprobanteVenta.Agregar(undefined, event);
      item.TipoVenta(self.TipoVenta());

      item.InicializarVistaModelo(event);

      $(item.InputOpcion()).hide();
      $(item.InputOpcionMercaderia()).hide();

    }
  }

  self.OnChangeEstadoPendienteNota = function (data, event) {
    if (event) {

      if (data.EstadoPendienteNota() == ESTADO_PENDIENTE_NOTA.PENDIENTE) {
        $form.find("#FechaMovimientoAlmacen").removeAttr("disabled");
        $form.find("#FechaMovimientoAlmacen").removeClass("no-tab");
        $form.find("#combo-almacen").removeAttr("disabled");
        $form.find("#combo-almacen").removeClass("no-tab");
      }
      else {
        $form.find("#FechaMovimientoAlmacen").attr("disabled", "disabled");
        $form.find("#FechaMovimientoAlmacen").addClass("no-tab");
        $form.find("#GrupoAlmacen").resetearValidaciones();
        $form.find("#combo-almacen").attr("disabled", "disabled");
        $form.find("#combo-almacen").addClass("no-tab");

      }
      if (data.EstadoPendienteNota() == COMPROBANTE_CON_NOTA_SALIDA) {
        setTimeout(function () {
          $("#SeriesNotaSalida").focus()
        }, 100);
      }
    }
  }

  self.OnChangeTipoVenta = function (data, event) {
    if (event) {
      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Limpiar(data, event);
      }
      self.IdTipoVenta(self.TipoVenta());
    }
  }

  self.OnClickTipoVenta = function (data, event) {
    if (event) {
      if (self.CambiosFormulario() == true) {
        alertify.confirm(self.titulo, "Se perderá el registro actual, ¿Desea cambiar de todas formas?", function () {
          $(event.target).prop("checked", true);
          self.OnChangeTipoVenta(data, event);
        }, function () {

        });
      }
      else {
        return true;
      }
    }
  }

  self.OnChangeComboAlmacen = function (data, event, parent) {
    if (event) {
      var comboAlmacen = $form.find("#combo-almacen option:selected");
      self.NombreSedeAlmacen(comboAlmacen.text());
      self.IdAsignacionSede(comboAlmacen.val());

      ko.utils.arrayForEach(data.Sedes(), function (item) {
        if (item.NombreSede() == comboAlmacen.text()) {
          self.IdSede(item.IdSede());
        }
      });

      parent.BusquedaAvanzadaProducto.OnLimpiar(undefined, event);
      self.ActualizarValorDeStockProducto(data, event)
    }
  }

  self.ActualizarValorDeStockProducto = function (data, event) {
    if (event) {
      ko.utils.arrayForEach(self.DetallesComprobanteVenta(), function (item) {
        if (item.IdProducto() !== "" && item.IdProducto() !== null) {
          var producto = self.ObtenerFilaMercaderiaJSON(item, event);
          var stock = item.ObtenerStockProducto(producto, event);
          item.StockProducto(stock ? stock.StockMercaderia : "0.00");
        }
      })
    }
  }

  self.OnChangeFormaPago = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-formapago option:selected").text();
      data.NombreFormaPago(texto);
      self.CambiarCampoACuenta(data, event);
      self.CambiarFechaVencimiento(data, event);
      if (self.IdFormaPago() == ID_FORMA_PAGO_CREDITO) {
        self.IdCaja("null");
      } else {
        self.CargarCajas(data, event);
      }
    }
  }

  self.CambiarCampoACuenta = function (data, event) {
    if (event) {
      var id = $form.find("#combo-formapago").val();
      if (self.ParametroCampoACuenta() == 1) {
        //if (id == ID_FORMA_PAGO_CONTADO) {
        //$form.find("#ACuenta").prop('readonly', true);
        //$form.find("#ACuenta").addClass('no-tab');
        self.MontoACuenta(self.CalcularTotal());
        //}
        //else {
        // $form.find("#ACuenta").prop('readonly', false);
        // $form.find("#ACuenta").removeClass('no-tab');
        // self.MontoACuenta('0.00');
        //}
      }
    }
  }

  self.CambiarFechaVencimiento = function (data, event) {
    if (event) {
      var id = $form.find("#combo-formapago").val();
      if (id == ID_FORMA_PAGO_CONTADO) {
        $form.find("#FechaVencimiento").closest(".form-group").removeClass("has-success");
        $form.find("#FechaVencimiento").prop('disabled', true);
        $form.find("#FechaVencimiento").addClass('no-tab');
        $form.find("#FechaVencimiento").removeClass('valid');
        self.FechaVencimiento("");
      }
      else {
        $form.find("#FechaVencimiento").prop('disabled', false);
        $form.find("#FechaVencimiento").removeClass('no-tab');
        $form.find("#FechaVencimiento").focus();
        self.FechaVencimiento(self.FechaEmision());
      }
    }
  }

  self.OnChangeSerieDocumento = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-seriedocumento option:selected").text();
      data.SerieDocumento(texto);
    }
  }

  self.OnChangeMoneda = function (data, event) {
    if (event) {
      var texto = $(self.Options.IDPanelHeader).find("#combo-moneda option:selected").text();
      data.NombreMoneda(texto);

      ko.utils.arrayForEach(data.Monedas(), function (item) {
        if (item.NombreMoneda() == texto) {
          self.CodigoMoneda(item.CodigoMoneda());
        }
      });

      self.ObtenerTipoCambioSunat(data, event);
      
      self.CargarCajas(data, event);
    }
  }

  self.ObtenerTipoCambioSunat = function (data, event) {
    if (event) {
      var valorTipoCambio = parseFloatAvanzado(self.ValorTipoCambio());

      if (self.IdMoneda() == ID_MONEDA_DOLARES && valorTipoCambio <= 0) {
        $("#loader").show();
        self.TipoCambio.ObtenerTipoCambio(data, function ($data) {
          $("#loader").hide();
          if ($data) {
            resultado = $data.TipoCambioVenta;
            self.ValorTipoCambio(resultado);
          }
          else {
            alertify.alert(self.titulo, "No se encontro un tipo de cambio para la fecha emision");
          }
        });

        // self.ObtenerTipoCambioActual(data, event, function ($data, $event) {
        //   if ($data.TipoCambio != "") {
        //     self.ValorTipoCambio($data.TipoCambio);
        //   }
        // });
      }
    }
  }

  self.CrearDetalleComprobanteVenta = function (data, event) {
    if (event) {
      var $input = $(event.target);
      self.RefrescarBotonesDetalleComprobanteVenta($input, event);
    }
  }

  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }
  }

  self.OnRefrescar = function (data, event, esporeliminacion) {
    if (event) {
      if (!$form.hasClass("selector-blocked")) {//'#formComprobanteVenta'
        if (!esporeliminacion) self.CrearDetalleComprobanteVenta(data, event);
        self.CalcularTotales(event);
        self.CalcularSumatoriaICBP(data, event)

        self.CalcularTotalACuenta(event);
        self.CalcularTotalPendientePago(event);
      }
      $form.find("#nletras").autoDenominacionMoneda(self.Total());
    }
  }

  self.OnClickBtnNuevoCliente = function (data, event, dataCliente) {
    if (event) {
      dataCliente.OnNuevo(dataCliente.ClienteNuevo, event, self.PostCerrarCliente);
      dataCliente.IdTipoDocumentoIdentidad(ID_TIPO_DOCUMENTO_IDENTIDAD_RUC);
      dataCliente.Show(event);
      return true;
    }
  }

  self.OnClickBtnNuevoDestinatario = function (data, event, dataCliente) {
    if (event) {
      dataCliente.OnNuevo(dataCliente.ClienteNuevo, event, self.PostCerrarDestinatario);
      dataCliente.IdTipoDocumentoIdentidad(ID_TIPO_DOCUMENTO_IDENTIDAD_RUC);
      dataCliente.Show(event);
      return true;
    }
  }

  self.OnClickBtnNuevaMercaderia = function (data, event, dataMercaderia) {
    if (event) {
      dataMercaderia.OnNuevaMercaderia(dataMercaderia.MercaderiaNueva, event, self.PostCerrarMercaderia);
      return true;
    }
  }

  self.OnClickBtnBuscadorMercaderia = function (data, event, dataBase) {
    if (event) {
      $("#BuscadorMercaderia").modal('show');
      dataBase.Mercaderias([]);
      dataBase.BusquedaAvanzadaProducto.InicializarVistaModelo(data, event, self);
      setTimeout(function () {
        $("#BuscadorMercaderia").find('input').first().focus();
      }, 500)
    }
  }

  self.OnClickBtnBuscadorMercaderiaLista = function (data, event, dataBase) {
    if (event) {
      $("#BuscadorMercaderiaLista").modal('show');
      dataBase.Mercaderias([]);
      dataBase.BusquedaAvanzadaProducto.InicializarVistaModelo(data, event, self);
      setTimeout(function () {
        $("#BuscadorMercaderiaLista").find('input').first().focus();
      }, 500)
    }
  }

  self.OnClickBtnBuscadorMercaderiaListaSimple = function (data, event, dataBase) {
    if (event) {
      $("#BuscadorMercaderiaListaSimple").modal('show');
      $("#BuscadorMercaderiaListaSimple #spanNombreSede").text(self.NombreSedeAlmacen());
      //dataBase.Mercaderias([]);
      dataBase.BusquedaAvanzadaProducto.InicializarVistaModelo(data, event, self, self.OnClickAgregarMercaderiaImagen);

      setTimeout(function () {
        $("#BuscadorMercaderiaListaSimple").find('input').first().focus();
      }, 500)
    }
  }

  self.OnClickAgregarMercaderiaImagen = function (data, event) {
    if (event) {

      var objeto = ko.mapping.toJS(data);
      // objeto.PrecioUnitario = objeto.PrecioVentaSoles;//ojo tiene que ser precio de venta segun tipo moneda venta

      if (self.IndicadorPermisoStockNegativo() == 0 && parseFloatAvanzado(objeto.StockProducto) <= 0) {
        alertify.alert("Comprobante Venta", "Este producto no cuenta con Stock para proceder con la Venta..", function () { });
        return false;
      }

      var detalleFiltrado = ko.utils.arrayFilter(self.DetallesComprobanteVenta(), function (item) {
        return item.IdProducto() == objeto.IdProducto;
      });

      self.DetallesComprobanteVenta.remove(function (item) { return item.IdProducto() == null; })

      objeto.CostoUnitario = objeto.CostoUnitario == "" || objeto.CostoUnitario == null ? '0' : objeto.CostoUnitario
      objeto.DescuentoItem = objeto.DescuentoItem == "" || objeto.DescuentoItem == null ? '0' : objeto.DescuentoItem
      objeto.DescuentoUnitario = objeto.DescuentoUnitario == "" || objeto.DescuentoUnitario == null ? '0' : objeto.DescuentoUnitario


      if (detalleFiltrado.length > 0) {
        var producto = detalleFiltrado[0]
        var cantidadaDetalleFiltrado = parseFloatAvanzado(producto.Cantidad()) + parseFloatAvanzado(objeto.Cantidad);
        var precioUnitarioDetalleFiltrado = parseFloatAvanzado(producto.PrecioUnitario());
        producto.Cantidad(parseFloatAvanzado(cantidadaDetalleFiltrado));
        producto.SubTotal(parseFloatAvanzado(cantidadaDetalleFiltrado * precioUnitarioDetalleFiltrado));

      } else {

        var detalle = ko.mapping.toJS(self.NuevoDetalleComprobanteVenta);
        objeto = Object.assign(detalle, objeto);
        objeto.Cantidad = objeto.Cantidad == '0' ? '1' : objeto.Cantidad;
        objeto.SubTotal = objeto.Cantidad * objeto.PrecioUnitario;
        var producto = self.DetallesComprobanteVenta.Agregar(objeto, event);
        producto.InicializarVistaModelo(event);

        var dataItem = self.ObtenerProductoPorIdProducto(producto, event);

        if (dataItem) {
          if (self.TipoVenta() == TIPO_VENTA.MERCADERIAS && self.ParametroLote() == 1) {
            producto.ListaLotes(dataItem.ListaLotes);
          }
          if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && (self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z)) {
            producto.ListaZofra(dataItem.ListaZofra);
          }
          if (self.TipoVenta() == TIPO_VENTA.MERCADERIAS && self.ParametroDua() == 1) {
            producto.ListaDua(dataItem.ListaDua);
          }
        }

      }

      producto.CalculoSubTotal(data, event);
      $form.find(producto.InputCodigoMercaderia()).attr("data-validation-found", "true");
      $form.find(producto.InputCodigoMercaderia()).attr("data-validation-text-found", $form.find(producto.InputProducto()).val());
      $form.find(producto.InputNumeroLote()).attr("data-validation-found", "false");
      $form.find(producto.InputNumeroDocumentoSalidaZofra()).attr("data-validation-found", "false");
      $form.find(producto.InputNumeroDua()).attr("data-validation-found", "false");

      var item = self.DetallesComprobanteVenta.Agregar(undefined, event);
      item.TipoVenta(self.TipoVenta());
      item.InicializarVistaModelo(event);
      $(item.InputOpcion()).hide();
      $(item.InputOpcionMercaderia()).hide();
      self.CalcularTotales(event);

      var response = {
        title: "<strong></strong>",
        type: "success",
        clase: "notify-producto",
        message: "Se agregó el producto " + objeto.NombreProducto + "."
      };
      CargarNotificacionBusquedaProducto(response);
    }
  }

  self.OnClickAgregarMercaderiaImagenPuntoVenta = function (data, event) {
    if (event) {

      var objeto = ko.mapping.toJS(data);
      var detalleFiltrado = ko.utils.arrayFilter(self.DetallesComprobanteVenta(), function (item) {
        return item.IdProducto() == objeto.IdProducto;
      });

      if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && parseFloatAvanzado(self.Total()) >= MONTO_MAXIMO_BOLETA) {
        if (!$(".detail-voucher").is(":visible")) {
          self.OnHideOrShowElement(data, event)
        }
        alertify.alert(self.titulo, "El monto de la Boleta es >= S/ 700.00, se necesita colocar los datos del Cliente.", function () {
          $("#CheckCliente").prop("checked", true);
          self.CambiarClientesVarios(event);
          setTimeout(() => { $("#Cliente").focus(); }, 300);
        })
        return false;
      }

      if (detalleFiltrado.length > 0) {
        var cantidadaDetalleFiltrado = parseFloatAvanzado(detalleFiltrado[0].Cantidad()) + 1,
          precioUnitarioDetalleFiltrado = parseFloatAvanzado(detalleFiltrado[0].PrecioUnitario());
        detalleFiltrado[0].Cantidad(parseFloatAvanzado(cantidadaDetalleFiltrado));
        detalleFiltrado[0].SubTotal(parseFloatAvanzado(cantidadaDetalleFiltrado * precioUnitarioDetalleFiltrado));

        var id = "#" + detalleFiltrado[0].IdDetalleComprobanteVenta() + "_tr_detalle";
      } else {
        var detalle = ko.mapping.toJS(self.NuevoDetalleComprobanteVenta);
        objeto = Object.assign(detalle, objeto);
        objeto.Cantidad = '1';
        objeto.SubTotal = objeto.Cantidad * objeto.PrecioUnitario;
        var producto = self.DetallesComprobanteVenta.Agregar(objeto, event);
        var id = "#" + producto.IdDetalleComprobanteVenta() + "_tr_detalle";
        producto.InicializarVistaModelo(event);
      }

      $(id).addClass('active').siblings().removeClass('active');
      $(id).find("input:not(:disabled)").first().focus();
      // self.IndicadorCambioMontoRecibido(true);
      self.CalcularTotales(event);
      self.CantidadMontoRecibido(data, event);
      self.ActualizarValorDeStockProducto(data, event);
    }
  }

  self.OnClickAgregarMercaderiaImagenComanda = function (data, event) {
    if (event) {
      if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && parseFloatAvanzado(self.Total()) >= MONTO_MAXIMO_BOLETA) {
        if (!$(".detail-voucher").is(":visible")) {
          self.OnHideOrShowElement(data, event)
        }
        alertify.alert(self.titulo, "El monto de la Boleta es >= S/ 700.00, se necesita colocar los datos del Cliente.", function () {
          $("#CheckCliente").prop("checked", true);
          self.CambiarClientesVarios(event);
          setTimeout(() => { $("#Cliente").focus(); }, 300);
        })
        return false;
      }

      var objeto = ko.mapping.toJS(data.Mercaderia);
      var idanotacionplato = data.IdAnotacionPlato ? data.IdAnotacionPlato() : "";
      delete objeto.ListaAnotacionesPlato;

      var detalleFiltrado = ko.utils.arrayFilter(self.DetallesComprobanteVenta(), function (item) {
        if (item.IdProducto() == objeto.IdProducto) {
          if (item.IndicadorImpresion() != ESTADO_INDICADOR_IMPRESION.ENVIADO) {
            if (item.IdAnotacionPlato() == idanotacionplato) {
              return item;
            }
          }
        }
      });

      if (detalleFiltrado.length > 0) {
        var cantidadaDetalleFiltrado = parseFloatAvanzado(detalleFiltrado[0].Cantidad()) + 1;
        detalleFiltrado[0].Cantidad(cantidadaDetalleFiltrado);
        detalleFiltrado[0].CalculoSubTotal(data, event);
        var id = "#" + detalleFiltrado[0].IdDetalleComprobanteVenta() + "_tr_detalle";
      } else {
        var detalle = ko.mapping.toJS(self.NuevoDetalleComprobanteVenta);
        objeto = Object.assign(detalle, objeto);

        objeto.Cantidad = '1';
        objeto.IdAnotacionPlato = idanotacionplato;
        objeto.Observacion = data.NombreAnotacionPlato ? data.NombreAnotacionPlato() : "";

        var producto = self.DetallesComprobanteVenta.Agregar(objeto, event);
        var id = "#" + producto.IdDetalleComprobanteVenta() + "_tr_detalle";
        producto.InicializarVistaModelo(event);
        producto.CalculoSubTotal(data, event);

      }

      if ($(id).hasClass('pendiente')) {
        $(id).addClass('active').siblings().removeClass('active');
      }
      $(id).find("input:not(:disabled)").first().focus();
      // self.IndicadorCambioMontoRecibido(true);
      self.CalcularTotales(event);
      self.CantidadMontoRecibido(data, event);
    }
  }

  self.AgregarProductoBonificado = function (data, event) {
    if (event) {

      var detalleFiltrado = ko.utils.arrayFilter(self.DetallesComprobanteVenta(), function (item) {
        return item.IdProducto() == data.IdProducto && item.IndicadorOperacionGratuita() == 1
      });

      if (detalleFiltrado.length > 0) {
        var detalle = detalleFiltrado[0];
        detalle.Cantidad(data.Cantidad);
      } else {
        var objeto = Object.assign(ko.mapping.toJS(self.NuevoDetalleComprobanteVenta), data)
        objeto.Cantidad = data.Cantidad;
        var valorreferencial = parseFloatAvanzado(data.PrecioUnitario) / (1 + parseFloatAvanzado(self.TasaIGV()));
        var valorreferencialitem = parseFloatAvanzado(objeto.Cantidad) * valorreferencial;
        var igvreferencialitem = valorreferencialitem * parseFloatAvanzado(self.TasaIGV());
        objeto.ValorUnitario = '0.00'; //ESTO ES UNA PRUEBA
        objeto.DescuentoUnitario = '0.00';

        var detalle = self.DetallesComprobanteVenta.Agregar(objeto, event);
        detalle.ProductoBonificado(true);
        detalle.TipoVenta(self.TipoVenta());
        detalle.InicializarVistaModelo(event);
        detalle.ValidarAutocompletadoProducto(objeto, event);
        detalle.IdTipoTributo(5); //5 = 9996 - Gratuito
        detalle.IndicadorOperacionGratuita(1);
        detalle.PrecioUnitario('0.00');
        detalle.IdTipoPrecio(2);//VALOR REFERENCIAL_UNITARIO
        detalle.ValorReferencial(valorreferencial);
        detalle.ValorReferencialItem(valorreferencialitem);
        detalle.IGVReferencialItem(igvreferencialitem);
        detalle.IdTipoAfectacionIGV(5);//
        detalle.CodigoTipoAfectacionIGV('15');//Gravado – Bonificaciones
        detalle.NombreProducto(detalle.NombreProducto() + " (BONIFICACION)");
        detalle.NombreLargoProducto(detalle.NombreLargoProducto() + " (BONIFICACION)");
        $(detalle.InputSubTotal()).removeAttr("data-validation");
      }
      detalle.CalculoSubTotal(data, event);

      self.DetallesComprobanteVenta.remove(function (item) { return item.IdProducto() == null; });
      var item = self.DetallesComprobanteVenta.Agregar(undefined, event);
      item.TipoVenta(self.TipoVenta());
      item.InicializarVistaModelo(event);
      $(item.InputOpcion()).hide();
      $(item.InputOpcionMercaderia()).hide();

    }
  }

  self.PostCerrarCliente = function (dataCliente, data, event) {
    if (event) {
      $(self.Options.IDModalCliente).modal("hide");
      var IdCliente = data.resultado.IdPersona

      var cliente = self.ObtenerFilaClienteJSON({ IdCliente }, event)
      if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
        if (cliente.CodigoDocumentoIdentidad == CODIGO_TIPO_DOCUMENTO_IDENTIDAD.RUC) {
          self.ValidarAutoCompletadoCliente(cliente, event);
        } else {
          $("#combo-formapago").focus();
        }
      } else {
        if (self.ParametroFiltroClienteSinRuc() == 1 && cliente.NumeroDocumentoIdentidad.length == MAXIMO_DIGITOS_RUC) {
          $("#combo-formapago").focus();
        } else {
          $form.find("#CheckCliente").prop("checked", true);
          self.CambiarClientesVarios(event);
          self.ValidarAutoCompletadoCliente(cliente, event);
        }
      }
    }
  }

  self.PostCerrarDestinatario = function (dataCliente, event) {
    if (event) {
      $(self.Options.IDModalCliente).modal("hide");//"#modalCliente"
      if (dataCliente.EstaProcesado() === true) {
        $form.find("#RazonSocialDestinatario").focus();
      }
      else {
        $form.find("#RazonSocialDestinatario").focus();
      }
    }
  }

  self.OnClickBtnBusquedaProducto = function (data, event, dataCliente) {
    if (event) {
      dataCliente.OnNuevo(dataCliente.ClienteNuevo, event, self.PostCerrarBusquedaProducto);
      dataCliente.IdTipoDocumentoIdentidad(ID_TIPO_DOCUMENTO_IDENTIDAD_RUC);
      dataCliente.Show(event);
      return true;
    }
  }

  self.PostCerrarBusquedaProducto = function (dataCliente, event) {
    if (event) {
      $(self.Options.IDModalBusquedaProducto).modal("hide");//"#modalCliente"
      if (dataCliente.EstaProcesado() === true) {
        $form.find("#Cliente").focus();
      }
      else {
        $form.find("#Cliente").focus();
      }
    }
  }

  self.Deshacer = function (data, event) {
    if (event) {
      self.Editar(self.ComprobanteVentaInicial, event, self.callback);
    }
  }

  self.Limpiar = function (data, event) {
    if (event) {
      self.Nuevo(self.ComprobanteVentaInicial, event, self.callback);
      self.IdTipoVenta(self.TipoVenta());
    }
  }

  self.OnVer = function (data, event, callback) {
    if (event) {
      self.Editar(data, event, callback, true);
    }
  }
  self.OnEditar = function (data, event, callback) {
    if (event) {
      self.Editar(data, event, callback, false);
    }
  }

  self.LimpiarPuntoVenta = function (data, event) {
    if (event) {
      self.OnHideOrShowElement(data, event);
      self.DetallesComprobanteVenta([]);
      self.IndicadorCambioMontoRecibido(false);
      self.MontoRecibido("");
      self.VueltoRecibido("");
      $form.resetearValidaciones();//'#formComprobanteVenta'
      self.NuevoComprobanteVenta(data, event);
      self.InicializarVistaModelo(undefined, event);

      $form.find("#Cliente").attr("data-validation-text-found", "");
      $form.find("#Cliente").val("");
      $form.find("#RazonSocialDestinatario").val("");
      $form.find("#combo-alumno").empty();
    }
  }

  self.CerrarPuntoVenta = function (data, event) {
    if (event) {
      // self.showComprobanteVenta(false);
      // self.LimpiarPuntoVenta(data, event);
    }
  }

  self.Nuevo = function (data, event, callback) {
    if (event) {
      $form.resetearValidaciones();//'#formComprobanteVenta'
      if (callback) self.callback = callback;
      self.NuevoComprobanteVenta(data, event);
      self.InicializarVistaModelo(undefined, event);
      self.InicializarVistaModeloDetalle(undefined, event);

      $form.find("#Cliente").attr("data-validation-text-found", "");
      $form.find("#Cliente").val("");
      $form.find("#RazonSocialDestinatario").val("");
      $form.find("#combo-alumno").empty();

      self.IndicadorCambioMontoRecibido(false);
      self.CheckNumeroDocumento(false);
      self.OnChangeCheckNumeroDocumento(undefined, event);
      self.OnChangeCheckDestinatario(undefined, event);
      self.CargarCajas(data, event);

      setTimeout(function () {
        if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
          if (self.TipoVenta() == TIPO_VENTA.SERVICIOS) {
            if ($form.find('#Cliente').is(":disabled")) {
              $form.find('.table').find('input').first().focus();
            }
            else {
              $form.find('#Cliente').focus();
            }
          } else {
            $form.find('.table').find('input').first().focus();
          }
        }
        else {
          $form.find('#combo-seriedocumento').focus();
        }
      }, 500);

    }
  }

  self.Editar = function (data, event, callback, blocked) {
    if (event) {
      if (self.IndicadorReseteoFormulario === true) $form.resetearValidaciones();//'#formComprobanteVenta'
      if (callback) self.callback = callback;
      var copia_objeto = ko.mapping.toJS(data);
      self.EditarComprobanteVenta(data, event);
      self.CargarAlumnos(data, event);

      var editar = true;
      if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
        editar = false;
      }
      self.TipoVenta(self.IdTipoVenta());
      self.CalcularMontoTotalACuenta(false);
      self.InicializarVistaModelo(undefined, event, editar);
      self.FechaVencimiento(data.FechaVencimiento())
      self.MontoPendientePago(data.MontoPendientePago())
      self.OnChangeTipoVenta(self, event);
      self.CargarCajas(data, event);
      self.opcionProceso(opcionProceso.Edicion);
      self.CopiaIdProductosDetalle([]);
      if (self.NumeroDocumentoIdentidad() == "") {
        $form.find("#Cliente").attr("data-validation-text-found", self.RazonSocial());
      } else {
        $form.find("#Cliente").attr("data-validation-text-found", self.NumeroDocumentoIdentidad() + " - " + self.RazonSocial());
      }
      $form.find("#RazonSocialDestinatario").attr("data-validation-text-found", self.RazonSocialDestinatario());

      $form.find("#DocumentoVentaReferencia").attr("data-validation-text-found", self.DocumentoVentaReferencia());
      $form.find("#DocumentoVentaProforma").attr("data-validation-text-found", self.DocumentoVentaProforma());
      $form.find("#NumeroPlaca").attr("data-validation-text-found", self.NumeroPlaca());
      $form.find("#NombreRadioTaxi").attr("data-validation-text-found", self.NombreRadioTaxi());

      self.IdCliente(copia_objeto.IdCliente);
      setTimeout(function () {
        if (self.NumeroDocumentoIdentidad() == "") {
          $form.find("#Cliente").val(self.RazonSocial());
        } else {
          $form.find("#Cliente").val(self.NumeroDocumentoIdentidad() + " - " + self.RazonSocial());
        }

        if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
          if (self.IdCliente() == ID_CLIENTES_VARIOS) {
            $form.find("#CheckCliente").prop("checked", false);
            $form.find("#Cliente").attr("disabled", true);
          }
          else {
            $form.find("#CheckCliente").prop("checked", true);
            $form.find("#Cliente").attr("disabled", false);
          }
        }


        if (self.IdTipoVenta() == TIPO_VENTA.SERVICIOS) {
          self.CheckDestinatario(self.IdDestinatario() == null || self.IdDestinatario() == "" ? 1 : 0)
          self.OnChangeCheckDestinatario(self, event);
        }
      }, 120);
      if (blocked == false) {
        $(self.Options.IDPanelHeader).bloquearSelector(blocked);//'#panelheaderComprobanteVenta'
        $form.bloquearSelector(blocked);//'#formComprobanteVenta'
      }

      $('#loader').show();
      self.ConsultarDetallesComprobanteVenta(data, event, function ($data, $event) {
        self.InicializarVistaModeloDetalle(undefined, event);
        if (self.DetallesComprobanteVenta().length > 0) {
          ko.utils.arrayForEach(self.DetallesComprobanteVenta(), function (item) {

            if (item.IdProducto() != null) self.CopiaIdProductosDetalle.push(item.IdProducto())

            var dataItem = self.ObtenerFilaMercaderiaJSON(item, event);
            if (dataItem) {
              if (self.TipoVenta() == TIPO_VENTA.MERCADERIAS && self.ParametroLote() == 1) {
                item.ListaLotes(dataItem.ListaLotes);
              }
              if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && (self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z)) {
                item.ListaZofra(dataItem.ListaZofra);
              }
              if (self.TipoVenta() == TIPO_VENTA.MERCADERIAS && self.ParametroDua() == 1) {
                item.ListaDua(dataItem.ListaDua);
              }
              item.ListaBonificaciones(dataItem.ListaBonificaciones || []);
            }

            if (item.IdProducto() != null) {
              item.ProductoAnterior(Knockout.CopiarObjeto(item));
            }

            $form.find(item.InputCodigoMercaderia()).attr("data-validation-found", "true");
            $form.find(item.InputCodigoMercaderia()).attr("data-validation-text-found", $form.find(item.InputProducto()).val());
            $form.find(item.InputNumeroLote()).attr("data-validation-found", "true");
            $form.find(item.InputNumeroDocumentoSalidaZofra()).attr("data-validation-found", "true");
            $form.find(item.InputNumeroDua()).attr("data-validation-found", "true");

            if (item.IndicadorOperacionGratuita() == 1) {
              $form.find(item.InputSubTotal()).removeAttr("data-validation");
            }


          })
        }

        $('#loader').hide();
        setTimeout(function () {
          $form.find('#combo-seriedocumento').focus();
        }, 450);
        self.IndicadorCrearProducto(parseFloat(self.IndicadorCrearProducto()));

        if (blocked) {
          $(self.Options.IDPanelHeader).bloquearSelector(blocked);//'#panelheaderComprobanteVenta'
          $form.bloquearSelector(blocked);//'#formComprobanteVenta'
        }
        $form.find('#btn_Cerrar').removeAttr('disabled');
        $form.find('#NombreGradoAlumno').prop('disabled', true);
        self.OnChangeEstadoPendienteNota(self, event);
      });
    }
  }

  self.AbrirPreVenta = function (data, event, callback) {
    if (event) {
      self.InicializarValidator(event)
      self.NuevoComprobanteVenta(data, event);
      self.DetallesComprobanteVenta([]);
      if (callback) self.callback = callback;
      ko.utils.arrayForEach(data.DetallesComprobanteVenta, function (item) {
        item.Cantidad = parseFloatAvanzado(item.SaldoPendientePreVenta) < parseFloatAvanzado(item.Cantidad) ? item.SaldoPendientePreVenta : item.Cantidad;
        item.PrecioUnitario = parseFloatAvanzado(item.PrecioUnitario).toFixed(CANTIDAD_DECIMALES_VENTA.PRECIO_UNITARIO);
        var objeto = self.DetallesComprobanteVenta.Agregar(new VistaModeloDetalleComprobanteVenta(item, self), event);
        objeto.CalcularSubTotal(data, event);
      });
    }
  }


  self.Guardar = function (data, event) {
    if (event) {

      if ($("#loader").is(":visible")) { return false; } //peticiones multiples

      if (self.ParametroSauna() == 1) {
        if ((self.IdGenero() == "" || self.IdCasillero() == "") && self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
          alertify.alert(self.titulo, "Debe seleccionar un Genero y Casillero", function () { });
          return false;
        }
      }

      self.AplicarExcepcionValidaciones(data, event);

      if ($form.isValid() === false) {//"#formComprobanteVenta"
        alertify.alert("Error en Validación", "Existe aun datos inválidos , por favor de corregirlo.", function () {
          setTimeout(function () {
            $form.find('.has-error').find('input').first().focus();
          }, 300);
          alertify.alert().destroy();
        });
      } else {
        var filtrado = ko.utils.arrayFilter(self.DetallesComprobanteVenta(), function (item) {
          return item.IdProducto() != null;
        });

        if (filtrado.length <= 0) {
          alertify.alert("Validación", "Debe ingresar por lo menos 1 ítem.");
          return false;
        }

        if (self.OpcionComprobantesAutomaticos() == false) {
          $("#loader").show();
          self.GuardarComprobanteVenta(event, self.PostGuardar);
        } else {
          var cantidadComprobantesAutomaticos = parseFloatAvanzado(self.CantidadComprobantesAutomaticos()),
            parametroMaxComprobantesAutomaticos = parseFloatAvanzado(self.ParametroMaxComprobantesAutomaticos());

          if (cantidadComprobantesAutomaticos > parametroMaxComprobantesAutomaticos) {
            alertify.alert("Validacion!", "La cantidad maxima de comprobantes automaticos es " + self.ParametroMaxComprobantesAutomaticos(), function () {
              setTimeout(() => {
                $("#CantidadComprobantesAutomaticos").focus()
              }, 350);
            });
            return false;
          }
          if (cantidadComprobantesAutomaticos <= 0) {
            alertify.alert("Validacion!", "La cantidad minima de comprobantes automaticos es 2", function () {
              setTimeout(() => {
                $("#CantidadComprobantesAutomaticos").focus()
              }, 350);
            });
            return false;
          }
          $("#loader").show();
          for (var i = 0; i < self.CantidadComprobantesAutomaticos(); i++) {
            self.GuardarComprobanteVenta(event, self.PostGuardarComprobantesAutomaticos);
          }
        }
      }
    }
  }


  self.GuardarProforma = function (data, event) {
    if (event) {
      if ($("#loader").is(":visible")) { return false; } //peticiones multiples

      self.AplicarExcepcionValidaciones(data, event);

      if ($form.isValid() === false) {//"#formComprobanteVenta"
        alertify.alert("Error en Validación", "Existe aun datos inválidos , por favor de corregirlo.", function () {
          setTimeout(function () {
            $form.find('.has-error').find('input').first().focus();
          }, 300);
          alertify.alert().destroy();
        });
      } else {
        var filtrado = ko.utils.arrayFilter(self.DetallesComprobanteVenta(), function (item) {
          return item.IdProducto() != null;
        });

        if (filtrado.length <= 0) {
          alertify.alert("Validación", "Debe ingresar por lo menos 1 ítem.");
          return false;
        }

        $("#loader").show();
        self.GuardarProformaVenta(event, self.PostGuardar);
      }
    }
  }

  self.ValidarGuardadoPreVenta = function (data, event) {
    if (event) {
      if ($("#loader").is(":visible")) { return false; } //peticiones multiples

      self.AplicarExcepcionValidaciones(data, event);

      if ($form.isValid() === false) {//"#formComprobanteVenta"
        alertify.alert("Error en Validación", "Existe aun datos inválidos , por favor de corregirlo.", function () {
          setTimeout(function () {
            $form.find('.has-error').find('input').first().focus();
          }, 300);
          alertify.alert().destroy();
        });
      } else {

        if (self.DetallesComprobanteVenta().length <= 0 && self.IdTipoDocumento() != ID_TIPO_DOCUMENTO_COMANDA) {
          alertify.alert("Validación", "Debe ingresar por lo menos 1 ítem.", function () { });
          return false;
        }

        var alertmsg = self.ValidarSubTotales(data, event)
        if (alertmsg != "") {
          alertify.alert(self.titulo, alertmsg, function () { });
          return false;
        }

        return true;
      }
    }
  }

  self.GuardarComanda = function (data, event) {
    if (event) {
      var validarpreventa = self.ValidarGuardadoPreVenta(data, event);
      if (validarpreventa) {
        $("#loader").show();
        if (self.IdComprobanteVenta() == "" || self.IdComprobanteVenta() == null) {
          self.GuardarPreVenta(event, self.PostGuardarComanda);
        } else {
          if (self.DetallesComprobanteVenta().length <= 0) {
            self.EliminarPreVenta(event, self.PostGuardarComanda);
          } else {
            self.GuardarPreVenta(event, self.PostGuardarComanda);
          }
        }
      }
    }
  }

  self.PostGuardarComanda = function (data, event) {
    if (event) {
      if (data.error) {
        $("#loader").hide();
        alertify.alert("Error en " + self.titulo, data.error.msg, function () {
        });
      }
      else {
        $("#loader").hide();

        var itemsImprimido = ko.utils.arrayFilter(self.DetallesComprobanteVenta(), function (item) {
          return item.IndicadorImpresion() == ESTADO_INDICADOR_IMPRESION.ENVIADO;
        });

        if (itemsImprimido.length == self.DetallesComprobanteVenta().length) {
          alertify.alert(self.titulo, "El comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " se guardó correctamente.", function () {
            $(self.Options.IDModalComprobanteVenta).modal('hide');
          });
          return false;
        }

        alertify.confirm(self.titulo, "Se guardó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente. <br /> <b> ¿Desea enviar la comanda a cocina?</b><br />", function () {
          $("#loader").show();
          self.ImprimirComanda(data, event, function ($data, $event) {
            $("#loader").hide();
            if (!$data.error) {
              $(self.Options.IDModalComprobanteVenta).modal('hide');
            } else {
              alertify.alert(self.titulo, $data.error.msg, function () { });
            }
          });
        }, function () {
          $(self.Options.IDModalComprobanteVenta).modal('hide');
        });
      }
    }
  }

  self.GuardarPreCuenta = function (data, event) {
    if (event) {
      var validarpreventa = self.ValidarGuardadoPreVenta(data, event);
      if (validarpreventa) {
        $("#loader").show();
        self.GuardarPreVenta(event, self.PostGuardarPreCuenta);
      }
    }
  }

  self.OnclickBtnCancelarPreCuenta = function (data, event) {
    if (event) {
      var validarpreventa = self.ValidarGuardadoPreVenta(data, event);
      if (validarpreventa) {
        $("#loader").show();
        self.CancelarPreCuenta(event, function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            self.mensaje = "Se guardó el comprobante " + $data.SerieDocumento + " - " + $data.NumeroDocumento + " correctamente.";
            alertify.alert(self.titulo, self.mensaje, function () {
              $('.btn-preventa').eq(1).click();
            });
          } else {
            alertify.alert(self.titulo, $data.error.msg, function () {
              $('.btn-preventa').eq(1).click();
            });
          }
        });
      }
    }
  }


  self.PostGuardarPreCuenta = function (data, event) {
    if (event) {
      if (data.error) {
        $("#loader").hide();
        alertify.alert("Error en " + self.titulo, data.error.msg, function () {
        });
      } else {
        $("#loader").hide();
        self.mensaje = "Se guardó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente. <br /> ¿Desea Imprimir la Pre Cuenta?";
        alertify.confirm(self.titulo, self.mensaje, function () {
          $("#loader").show();
          data.IndicadorTipoImpresion = INDICADOR_TIPO_IMPRESION.DETALLADO;
          self.ImprimirPreVenta(data, event, function ($data, $event) {
            self.PostImprimirPreventa($data, $event, data);
          });
        }, function () {
          if (self.ParametroEnvioEmail() == 1) { self.EnviarEmailCliente(data, event); }
          $('.btn-preventa').eq(1).click();
        });
      }
    }
  }

  self.GuardarComprobantePostVenta = function (data, event) {
    if (event) {
      var validarpreventa = self.ValidarGuardadoPreVenta(data, event);
      if (validarpreventa) {
        $("#loader").show();
        self.GuardarPreVenta(event, self.PostGuardarComprobantePostVenta);
      }
    }
  }

  self.PostGuardarComprobantePostVenta = function (data, event) {
    if (event) {
      if (data.error) {
        $("#loader").hide();
        alertify.alert("Error en " + self.titulo, data.error.msg, function () {
        });
      }
      else {
        $("#loader").hide();
        self.mensaje = "Se guardó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente. <br /> ¿Desea imprimir el comprobante?";

        alertify.confirm(self.titulo, self.mensaje, function () {
          $("#loader").show();
          data.IndicadorTipoImpresion = INDICADOR_TIPO_IMPRESION.DETALLADO;
          self.ImprimirPreVenta(data, event, function ($data, $event) {
            self.PostImprimirPreventa($data, $event, data);
          })
        }, function () {
          if (self.ParametroEnvioEmail() == 1) { self.EnviarEmailCliente(data, event); }
          $('.btn-preventa').eq(1).click();
        });
      }
    }
  }

  self.PostImprimirPreventa = function (data, event, dataventa) {
    if (event) {
      $("#loader").hide();
      if (!$data.error) {
        if (self.ParametroEnvioEmail() == 1) { self.EnviarEmailCliente(data, event); }
        $('.btn-mesa').eq(0).click();
      } else {
        alertify.alert(self.titulo, $data.error.msg, function () { });
      }
    }
  }


  self.PostGuardarComprobantesAutomaticos = function (data, event) {
    if (event) {
      if (data.error) {
        $("#loader").hide();
        var valiData = {
          title: "<strong>Error!</strong>",
          type: "danger",
          clase: "notify-danger",
          message: data.error.msg
        };
        CargarNotificacionDetallada(valiData);
      } else {
        var valiData = {
          title: "<strong>Exito!</strong>",
          type: "success",
          clase: "notify-success",
          message: "el comprobante " + data.SerieDocumento + ' - ' + data.NumeroDocumento + " se registro correctamente."
        };
        CargarNotificacionDetallada(valiData);

        self.ComprobantesAutomaticos().push(Knockout.CopiarObjeto(data));
        var cantidadimpresion = 0;

        if (self.ComprobantesAutomaticos().length == self.CantidadComprobantesAutomaticos()) {
          $("#loader").hide();
          alertify.confirm(self.titulo, "¿Desea Imprimir los " + self.ComprobantesAutomaticos().length + " Comprobantes Automaticos?", function () {
            $("#loader").show();
            ko.utils.arrayFirst(self.ComprobantesAutomaticos(), function (item) {
              self.Imprimir(item, event, function ($data, $evento) {
                cantidadimpresion++;
                if ($data.error) {
                  var valiData = {
                    title: "<strong>Error!</strong>",
                    type: "danger",
                    clase: "notify-danger",
                    message: $data.error.msg
                  };
                  CargarNotificacionDetallada(valiData);
                }
                if (cantidadimpresion == self.CantidadComprobantesAutomaticos()) {
                  $("#loader").hide();
                  if (self.callback) self.callback(data, event);
                }
              });
            });
          }, function () {
            if (self.callback) self.callback(data, event);
          })
        }
      }
    }
  }
  self.TipoDocumentoSegunSerie = function (data, event) {
    if (event) {
      var indiceDocumento = data.substr(0, 1);

      if (indiceDocumento == "F") {
        return "FACTURA ELECTRONICA";
      } else if (indiceDocumento == "B") {
        return "BOLETA ELECTRONICA";
      } else {
        return "DOCUMENTO FISICO";
      }
    }
  }

  self.PostGuardar = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (data.error) {
        alertify.alert("Error en " + self.titulo, data.error.msg, function () { });
      }
      else {
        var tipo = self.TipoDocumentoSegunSerie(data.SerieDocumento, event);
        if (self.IndicadorEnvioAutomaticoSUNAT() == 1 && self.opcionProceso() == opcionProceso.Nuevo) {

          alertify.confirm(self.titulo, `Se registró el comprobante ${data.SerieDocumento}-${data.NumeroDocumento} correctamente ¿Desea Enviar a SUNAT e imprimir el documento?`, function () {
            $("#loader").show();
            if (tipo == 'FACTURA ELECTRONICA') {
              var datajs = { Data: JSON.stringify(ko.mapping.toJS(data)) };
              self.EnviarFacturaSunat(datajs, event, function ($data) {
                if (!$data.error) {
                  CargarNotificacionDetallada($data);
                  self.Imprimir(data, event, function ($dataImpresion, event) {
                    $("#loader").hide();
                    if (!$dataImpresion.error) {
                      if (self.ParametroVistaPreviaImpresion() == 1) {
                        printJS($dataImpresion.APP_RUTA);
                      }
                      self.FuncionesPostGuardado(data, event);
                    } else {
                      self.FuncionesPostGuardado(data, event);
                    }
                  });
                } else {
                  CargarNotificacionDetallada($data);
                  self.Imprimir(data, event, function ($dataImpresion, event) {
                    $("#loader").hide();
                    if (!$dataImpresion.error) {
                      if (self.ParametroVistaPreviaImpresion() == 1) {
                        printJS($dataImpresion.APP_RUTA);
                      }
                      self.FuncionesPostGuardado(data, event);
                    } else {
                      self.FuncionesPostGuardado(data, event);
                    }
                  });
                }
              })
            } else if (tipo == 'BOLETA ELECTRONICA') {
              var _mappingIgnore = ko.toJS(mappingIgnore);
              var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesComprobanteVenta" });
              //var copia_objeto = ko.mapping.toJS(self, mappingfinal);              
              //var datajs = JSON.stringify(copia_objeto) ;
              var datajs = { Data: JSON.stringify(ko.mapping.toJS({ "Data": [self] }, mappingIgnore)) };
              self.GenerarResumen(datajs, event, function ($data) {
                if (!$data.error) {
                  CargarNotificacionDetallada($data);
                  self.Imprimir(data, event, function ($dataImpresion, event) {
                    $("#loader").hide();
                    if (!$dataImpresion.error) {
                      if (self.ParametroVistaPreviaImpresion() == 1) {
                        printJS($dataImpresion.APP_RUTA);
                      }
                      self.FuncionesPostGuardado(data, event);
                    } else {
                      self.FuncionesPostGuardado(data, event);
                    }
                  });
                } else {
                  CargarNotificacionDetallada($data);
                  self.Imprimir(data, event, function ($dataImpresion, event) {
                    $("#loader").hide();
                    if (!$dataImpresion.error) {
                      if (self.ParametroVistaPreviaImpresion() == 1) {
                        printJS($dataImpresion.APP_RUTA);
                      }
                      self.FuncionesPostGuardado(data, event);
                    } else {
                      self.FuncionesPostGuardado(data, event);
                    }
                  });
                }
              })
            } else {
              self.Imprimir(data, event, function ($dataImpresion, event) {
                $("#loader").hide();
                if (!$dataImpresion.error) {
                  if (self.ParametroVistaPreviaImpresion() == 1) {
                    printJS($dataImpresion.APP_RUTA);
                  }
                  self.FuncionesPostGuardado(data, event);
                } else {
                  self.FuncionesPostGuardado(data, event);
                }
              });
            }
          }, function () {
            self.FuncionesPostGuardado(data, event);
          });
        } else {
          alertify.confirm(self.titulo, self.mensaje, function () {
            $("#loader").show();
            self.Imprimir(data, event, function ($dataImpresion, event) {
              $("#loader").hide();
              if (!$dataImpresion.error) {
                if (self.ParametroVistaPreviaImpresion() == 1) {
                  printJS($dataImpresion.APP_RUTA);
                }
                self.FuncionesPostGuardado(data, event);
              } else {
                self.FuncionesPostGuardado(data, event);
              }
            });
          }, function () {
            self.FuncionesPostGuardado(data, event);
          });
        }
      }
    }
  }

  self.FuncionesPostGuardado = function (data, event) {
    if (event) {
      if (self.ParametroEnvioEmail() == 1) {
        self.EnviarEmailCliente(data, event);
      }
      ViewModels.data.CasillerosPorGenero.Femenino([]);
      ViewModels.data.CasillerosPorGenero.Masculino([]);
      self.CasilleroSeleccionado = {};
      if (self.callback) self.callback(data, event);
    }
  }

  self.Anular = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.AnularComprobanteVenta(data, event, self.PostAnular);
    }
  }

  self.AnularProforma = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.AnularProformaVenta(data, event, self.PostAnularProforma);
    }
  }

  self.AnularComprobantePreVenta = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.AnularVentaDesdePreVenta(data, event, self.PostAnularComprobantePreVenta);
    }
  }

  self.Eliminar = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.EliminarComprobanteVenta(data, event, self.PostEliminar);
    }
  }

  self.EliminarProforma = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.EliminarProformaVenta(data, event, self.PostEliminarProforma);
    }
  }

  self.EliminarComprobantePreVenta = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.EliminarVentaDesdePreVenta(data, event, self.PostEliminarComprobantePreVenta);
    }
  }

  self.PostAnular = function (data, event) {
    if (event) {
      if (self.callback) { self.callback(data, event); }
    }
  }

  self.PostAnularComprobantePreVenta = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (!data.error) {
        alertify.alert("Anulación de Comprobante de Venta", "Se anuló correctamente!", function () {
          if (self.callback) self.callback(data, event);
        });
      }
      else {
        alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function () { });
      }
    }
  }

  self.PostAnularProforma = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (!data.error) {
        alertify.alert("Anulación de Comprobante de Venta", "Se anuló correctamente!", function () {
          if (self.callback) self.callback(data, event);
        });
      }
      else {
        alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function () { });
      }
    }
  }


  self.PostEliminar = function (data, event) {
    if (event) {
      if (self.callback) { self.callback(data, event); }
    }
  }

  self.PostEliminarComprobantePreVenta = function (data, event) {
    if (event) {
      var resultado = data;

      if (resultado.error === undefined) {
        // alertify.alert("Se eliminó correctamente!");

        if (self.callback != undefined)
          self.callback(resultado, event);
      }
      else {
        alertify.alert("HA OCURRIDO UN ERROR", resultado.error.msg, function () { });
      }
    }
  }

  self.PostEliminarProforma = function (data, event) {
    if (event) {
      var resultado = data;
      if (resultado.error === undefined) {
        if (self.callback != undefined)
          self.callback(resultado, event);
      }
      else {
        alertify.alert("HA OCURRIDO UN ERROR", resultado.error.msg, function () { });
      }
    }
  }
  self.EnviarEmailCliente = function (data, event) {
    if (event) {
      var data_objeto = ko.mapping.toJS(data);
      if ((data_objeto.SerieDocumento.search(CODIGO_SERIE_BOLETA) >= 0 && data_objeto.IdTipoDocumento == ID_TIPO_DOCUMENTO_BOLETA) || (data_objeto.SerieDocumento.search(CODIGO_SERIE_FACTURA) >= 0 && data_objeto.IdTipoDocumento == ID_TIPO_DOCUMENTO_FACTURA)) {
        var rpta = self.ObtenerFilaClienteJSON(data_objeto, event);
        if (rpta != null) {
          if (data_objeto.NombreArchivoComprobante) {
            if (rpta.Email == null || rpta.Email == "") {
              //NONE
            }
            else {
              data_notify = {};
              data_notify.title = "¿Desea Enviar el CPE por Email al Cliente?";
              data_notify.message = "Se enviará el XML y PDF respectivo.";
              LoadNotificacionEmail(data_notify, function (res) {
                if (res) {
                  if (validarEmail(rpta.Email) == false) {
                    var val_data = {
                      title: "<strong>Validación!</strong>",
                      type: "danger",
                      clase: "notify-danger",
                      message: "El email del cliente es invalido."
                    };
                    CargarNotificacionDetallada(val_data);
                    return false;
                  }
                  rpta.NombreArchivo = data_objeto.NombreArchivoComprobante;
                  rpta.IdComprobanteVenta = data_objeto.IdComprobanteVenta;
                  rpta.SerieDocumento = data_objeto.SerieDocumento;
                  rpta.NumeroDocumento = data_objeto.NumeroDocumento;
                  rpta.Total = data_objeto.Total;
                  rpta.NombreAbreviado = data_objeto.NombreAbreviado;
                  rpta.NombreTipoDocumento = data_objeto.NombreTipoDocumento;
                  rpta.IdTipoDocumento = data_objeto.IdTipoDocumento;
                  self.EnviarMail(rpta, event, function ($data, $event) {
                    if (!$data.error) {
                      CargarNotificacionDetallada($data);
                    }
                  });
                }
              });

            }
          }
        }

      }

    }
  }

  self.CalculoTotalVentaGravado = ko.computed(function () {
    var resultado = accounting.formatNumber(self.ValorVentaGravado(), NUMERO_DECIMALES_VENTA);
    return resultado;
  }, this);

  self.CalculoTotalVentaNoGravado = ko.computed(function () {
    var resultado = accounting.formatNumber(self.ValorVentaNoGravado(), NUMERO_DECIMALES_VENTA);
    return resultado;
  }, this);

  self.CalculoTotalVentaInafecto = ko.computed(function () {
    var resultado = accounting.formatNumber(self.ValorVentaInafecto(), NUMERO_DECIMALES_VENTA);
    return resultado;
  }, this);

  self.CalculoTotalDescuentoGlobal = ko.computed(function () {
    var resultado = accounting.formatNumber(self.DescuentoGlobal(), NUMERO_DECIMALES_VENTA);
    return resultado;
  }, this);

  self.CalculoTotalIGV = ko.computed(function () {
    var resultado = accounting.formatNumber(self.IGV(), NUMERO_DECIMALES_VENTA);
    return resultado;
  }, this);

  self.CalculoTotalVenta = ko.computed(function () {
    var resultado = accounting.formatNumber(self.Total(), NUMERO_DECIMALES_VENTA);
    if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && !(self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z)) {
      if (parseFloatAvanzado(self.Total()) >= MONTO_MAXIMO_BOLETA) {
        if (self.IdCliente() == ID_CLIENTES_VARIOS || self.IdCliente() == "") {
          alertify.alert("AVISO", "El monto de la Boleta es >= S/ 700.00, se necesita colocar los datos del Cliente.", function functionName() {
            $("#CheckCliente").prop("checked", true);
            self.CambiarClientesVarios(event);
            setTimeout(() => { $("#Cliente").focus(); }, 300);

          });
        }
        $("#CheckCliente").prop("disabled", true);
      }
      else {
        $("#CheckCliente").prop("disabled", false);
      }
      // else if(event.type != "click" && !(self.IdCliente() != ID_CLIENTES_VARIOS) || self.IdCliente() == "" ){
      //   $("#CheckCliente").prop("checked", false);
      //   $("#CheckCliente").prop("disabled", false);
      //   self.CambiarClientesVarios(event);
      // }
    }
    return resultado;
  }, this);

  self.DenominacionTotal = ko.computed(function () {

    var resultado = self.Total ? self.Total() : "0.00";

    if (resultado != null) {
      if (self.IdMoneda() == ID_MONEDA_SOLES)
        self.MontoLetra(Number(resultado).DenominacionMonedaSOLES());
      else
        self.MontoLetra(Number(resultado).DenominacionMonedaDOLARES());
    }
    else {
      self.MontoLetra("");
    }

    return self.MontoLetra();
  }, this);

  //self.TieneAccesoEditar =  ko.observable(self.ValidarEstadoComprobanteVenta(self,window));
  //self.TieneAccesoAnular =  ko.observable(self.ValidarEstadoComprobanteVenta2(self,window));

  /*self.AbreviaturaSituacionCPE =  ko.computed(function(){
    var resultado = "";
    ko.utils.arrayForEach(self.SituacionesCPE(), function (item) {
        if (item.CodigoSituacionComprobanteElectronico() == self.SituacionCPE()) {
          console.log("self.AbreviaturaSituacionCPE");
          console.log(self.SituacionCPE());
           resultado = item.AbreviaturaSituacionComprobanteElectronicoVentas();
        }
    });
    return resultado;
  },this);*/

  self.OnEnableBtnEditar = ko.computed(function () {
    /*
    if ((self.VerTodasVentas() == 1 && self.IdRolUsuario() == ID_ROL_GERENTE)) {

    }
    else if ((self.VerTodasVentas() != 1) || (self.VerTodasVentas() == 1 && self.IdUsuarioActivo() == self.IdUsuario())) {

    }
    else {
      return false;
    }*/
    if (self.IndicadorPermisoEditarComprobanteVenta() == 0) {
      return false;
    }

    if (self.IndicadorEstado() == ESTADO.ACTIVO) {
      if (self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO) {
        if (self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO) {
          var mensaje = "SI(Advertencia, tiene q enviar el CP como RD)";
          if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && self.CodigoEstado() != CODIGO_ESTADO.ANULADO) {
            return true;
          }
          return false;
        }
        else {

        }
        return true;
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO) {
        return true;
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO) {
        return false;
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO) {
        return false;
      }
      else {
        return true;
      }
    }
    else if (self.IndicadorEstado() == ESTADO.ANULADO) {
      if (self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO) {
        if (self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO) {
          //svar mensaje="SI(Advertencia, tiene q enviar el CP como RD)";
          return false;
        }
        else {
          if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && self.CodigoEstado() == CODIGO_ESTADO.ANULADO) {
            return false;
          }
        }
        return true;
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO) {
        return true;
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO) {
        return false;
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO) {
        return false;
      }
      else {
        return true;
      }
    }
    else {
      return false;
    }
  }, this);

  self.OnEnableBtnAnular = ko.computed(function () {

    if (self.IndicadorPermisoAnularComprobanteVenta() == 0) {
      return false;
    }

    if (self.IndicadorDocumentoReferencia() == '1') {
      return false;
    }

    /*
    if ((self.VerTodasVentas() == 1 && self.IdRolUsuario() == ID_ROL_GERENTE)) {

    }
    else if ((self.VerTodasVentas() != 1) || (self.VerTodasVentas() == 1 && self.IdUsuarioActivo() == self.IdUsuario())) {

    }
    else {
      return false;
    }
    */

    if (self.IndicadorEstado() == ESTADO.ACTIVO) {
      if (self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO) {
        if (self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO)
          var mensaje = "SI(Advertencia, tiene q enviar el CP como RD)";
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO) {
        var mensaje = "SI(Advert - Comunicación Baja y plazo)";
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO) {
        //var mensaje = "SI(Advert - Comunicación Baja y plazo)";
        return false;
      }
      return true;
    }
    else if (self.IndicadorEstado() == ESTADO.ANULADO) {
      return false;
    }
    else {
      return false;
    }
  }, this);

  self.OnEnableBtnEliminar = ko.computed(function () {
    /*
    if ((self.VerTodasVentas() == 1 && self.IdRolUsuario() == ID_ROL_GERENTE)) {

    }
    else if ((self.VerTodasVentas() != 1) || (self.VerTodasVentas() == 1 && self.IdUsuarioActivo() == self.IdUsuario())) {

    }
    else {
      return false;
    }
    */

    if (self.IndicadorPermisoEliminarComprobanteVenta() == 0) {
      return false;
    }

    if (self.IndicadorEstado() == ESTADO.ACTIVO) {
      if (self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO) {
        if (self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO) {
          return false;
        }
        else {
          if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && self.CodigoEstado() == CODIGO_ESTADO.MODIFICADO) {
            return false;
          }
          return true;
        }
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO) {
        return true;
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO) {
        return false;
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO) {
        return false;
      }
      else {
        return true;
      }
    }
    else if (self.IndicadorEstado() == ESTADO.ANULADO) {
      if (self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO) {
        if (self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO) {
          return false;
        }
        else {
          if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && self.CodigoEstado() == CODIGO_ESTADO.ANULADO) {
            return false;
          }
          return true;
        }
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO) {
        return true;
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO) {
        return false;
      }
      else if (self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO) {
        return false;
      }
      else {
        return true;
      }
    }
    else {
      return false;
    }
  }, this);

  self.OnChangeCheckNumeroDocumento = function (data, event) {
    if (event) {
      if ($form.find("#CheckNumeroDocumento").prop("checked")) {
        $form.find("#NumeroDocumento").attr("readonly", false);
        $form.find("#NumeroDocumento").removeClass("no-tab");
        $form.find("#NumeroDocumento").attr("data-validation-optional", "false");
        if (data) {
          $form.find("#NumeroDocumento").focus();
        }
      }
      else {
        $form.find("#NumeroDocumento").attr("data-validation-optional", "true");
        $form.find("#NumeroDocumento").attr("readonly", true);
        $form.find("#NumeroDocumento").addClass("no-tab");
        if (data) {
          $form.find("#CheckNumeroDocumento").focus();
        }
        self.NumeroDocumento("");
        $form.find("#NumeroDocumento").validate();
      }
    }
  }

  self.OnChangeCheckDestinatario = function (data, event) {
    if (event) {
      if (self.CheckDestinatario() == 1) {
        $form.find("#DivTransporte").resetearValidaciones();
        $form.find("#RazonSocialDestinatario").val("");
        self.IdDestinatario("");
        $form.find("#Destinatario").attr("disabled", false);
        $form.find("#Destinatario").removeClass("no-tab");
        $form.find("#RazonSocialDestinatario").attr("disabled", true);
        $form.find("#RazonSocialDestinatario").addClass("no-tab");
        self.CheckDestinatario(1)
        if (data) {
          $form.find("#Destinatario").focus();
        }
      }
      else {
        $form.find("#DivTransporte").resetearValidaciones();
        self.Destinatario("");
        $form.find("#Destinatario").attr("disabled", true);
        $form.find("#Destinatario").addClass("no-tab");
        $form.find("#RazonSocialDestinatario").attr("disabled", false);
        $form.find("#RazonSocialDestinatario").removeClass("no-tab");
        self.CheckDestinatario(0)
        if (data) {
          $form.find("#RazonSocialDestinatario").focus();
        }
      }
    }
  }

  self.OnChangeCheckCliente = function (data, event) {
    if (event) {
      self.IdCliente("");
      self.CambiarClientesVarios(event);
      // setTimeout(function(){
      //   $form.find("#Cliente").focus();
      // }, 150);
    }
  }

  self.CambiarClientesVarios = function (event) {
    if (event) {
      if ($form.find("#CheckCliente").length > 0) {
        if (self.IdCliente() == null || self.IdCliente() == "" || self.IdCliente() == ID_CLIENTES_VARIOS) {
          if ($form.find("#CheckCliente").prop("checked")) {
            $form.find("#Cliente").attr("data-validation", "autocompletado_cliente");

            $form.find("#Cliente").attr("disabled", false);
            $form.find("#Cliente").removeClass("no-tab");
            $form.find("#Cliente").val("");
            $form.find("#Cliente").attr("data-validation-optional", "false");
            self.IdCliente("");
            self.RazonSocial("");
            self.NumeroDocumentoIdentidad("");
            $("#input-teclado-virtual").val("Cliente");

          }
          else {
            $form.find("#Cliente").removeAttr("data-validation");
            $form.find("#Cliente").removeAttr("style");
            $form.find("#Cliente").closest(".form-group").removeClass("has-error");
            $form.find("#Cliente").removeClass('error');
            $form.find("#Cliente").closest(".form-group").removeClass("has-success");
            $form.find("#Cliente").removeClass('valid');
            var grupo = $form.find("#Cliente").closest(".form-group");
            $(grupo).find("span").filter(".form-error").remove();

            $form.find("#Cliente").attr("data-validation-optional", "true");
            $form.find("#Cliente").attr("disabled", true);
            $form.find("#Cliente").addClass("no-tab");
            self.IdCliente(ID_CLIENTES_VARIOS);
            self.CodigoDocumentoIdentidad("0");
            self.RazonSocial(RZ_CLIENTES_VARIOS);
            self.NumeroDocumentoIdentidad(RUC_CLIENTES_VARIOS);
            $("#eac-container-Cliente ul").hide();
            setTimeout(function () {
              $form.find("#Cliente").val(TEXTO_CLIENTES_VARIOS);
            }, 100);
            if ($form.find("#MontoRecibido").is(":visible")) {
              $form.find("#input-teclado-virtual").val("MontoRecibido");
            }
          }
        }

      }
    }
  }

  self.CambiarClientesVariosPreCuenta = function (data, event) {
    if (event) {
      $form.resetearValidaciones();
      self.IdCliente(ID_CLIENTES_VARIOS);
      self.RazonSocial(RZ_CLIENTES_VARIOS);
      self.NumeroDocumentoIdentidad(RUC_CLIENTES_VARIOS);
      $("#eac-container-Cliente ul").hide();
      $form.find("#ClientePreCuenta").val(TEXTO_CLIENTES_VARIOS);
      $form.find("#ClientePreCuenta").attr("data-validation-text-found", TEXTO_CLIENTES_VARIOS);
    }
  }

  self.RefrescarBotonesDetalleComprobanteVenta = function (data, event) {
    if (event) {
      var tamaño = self.DetallesComprobanteVenta().length;
      var indice = data.closest("tr").index();
      if (indice === tamaño - 1) {
        self.RemoverExcepcionValidaciones(data, event);
        var InputOpcion = self.DetallesComprobanteVenta()[indice].InputOpcion();
        var InputOpcionMercaderia = self.DetallesComprobanteVenta()[indice].InputOpcionMercaderia();
        $(InputOpcion).show();
        $(InputOpcionMercaderia).show();
        self.OnAgregarFila(undefined, event);
      }
    }
  }

  self.RemoverExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesComprobanteVenta().length;
      var ultimoItem = self.DetallesComprobanteVenta()[total - 1];
      var resultado = "false";

      $form.find(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputNumeroLote()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputNumeroDua()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputPrecioUnitario()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputDescuentoUnitario()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputSubTotal()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputNumeroDocumentoSalidaZofra()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputObservacion()).attr("data-validation-optional", resultado);
    }
  }

  self.OnAgregarFila = function (data, event) {
    if (event) {
      var item = self.DetallesComprobanteVenta.Agregar(undefined, event);
      item.TipoVenta(self.TipoVenta());
      item.InicializarVistaModelo(event);
      $(item.InputOpcion()).hide();
      $(item.InputOpcionMercaderia()).hide();
    }
  }

  self.OnQuitarFila = function (data, event) {
    if (event) {
      self.DetallesComprobanteVenta.Remover(data, event);
      var trfilas = $("#tablaDetalleComprobanteVenta").find("tr").find("button:visible");
      if (trfilas.length == 0) {
        setTimeout(function () {
          $form.find("#OrdenCompra").focus();
        }, 250);
      }
      self.OnRefrescar(data, event, true);
      self.CalcularVuelto(data, event);
      self.OnQuitarProductoBonificado(data, event);
    }
  }

  self.OnQuitarProductoBonificado = function (data, event) {
    if (event) {
      var bonificaciones = (data.ListaBonificaciones == undefined) ? [] : ko.mapping.toJS(data.ListaBonificaciones());

      if (self.ParametroBonificacion() == 1 && bonificaciones.length > 0 && data.IndicadorOperacionGratuita() == 0) {
        bonificaciones.forEach(function (item) {
          var producto = ko.utils.arrayFirst(self.DetallesComprobanteVenta(), function (producto) {
            return producto.IdProducto() == item.IdProductoBonificacion;
          });

          if (producto) {
            self.DetallesComprobanteVenta.Remover(producto, event);
          }

        })
      }
    }
  }

  self.OnClickBtnEliminarItemComanda = function (data, event) {
    if (event) {
      if (data.IndicadorImpresion() != ESTADO_INDICADOR_IMPRESION.ENVIADO) {
        self.OnQuitarFila(data, event)
        return false;
      }
      if (self.IdRolUsuario() != ID_ROL_CAJERO) {
        alertify.alert("ELIMINACIÓN DE ITEM DE COMANDA", "Este ítem ya fue enviado a cocina, Para anularlo deberá comunicarse con el Cajero", function functionName() { });
        return false;
      }
      alertify.confirm("ELIMINACIÓN DE ITEM DE COMANDA", 'Se eliminará "' + data.NombreProducto() + '" (' + data.Cantidad() + '). La orden se enviará a cocina.', function () {
        $("#loader").show()
        self.EliminarItemComanda(data, event, function ($data, event) {
          $("#loader").hide()
          if (!$data.error) {
            alertify.alert(self.titulo, 'Se Eliminó correctamente el producto <b>"' + data.NombreProducto() + '"</b>.', function () {
              self.OnQuitarFila(data, event)
            });
          } else {
            alertify.alert(self.titulo, $data.error.msg, function () { });
          }
        });
      }, function () { });
    }
  }

  self.OnBloquearComboAlmacen = function (event) {
    if (event) {
      if (self.TotalItems() > 0) {
        var textTitle = self.EstadoPendienteNota() == ESTADO_PENDIENTE_NOTA.PENDIENTE ? "Para habilitar el almacén deberá borrar todo los ítems de la venta" : "";
        $form.find('#combo-almacen').attr("title", textTitle);
        $form.find('#combo-almacen').prop("disabled", true);
        $form.find('#combo-almacen').addClass("no-tab");
      } else {
        if (self.EstadoPendienteNota() == ESTADO_PENDIENTE_NOTA.PENDIENTE) {
          $form.find('#combo-almacen').attr("title", "");
          $form.find('#combo-almacen').prop("disabled", false);
          $form.find('#combo-almacen').removeClass("no-tab");
        } else {
          $form.find('#combo-almacen').attr("title", '');
        }
      }
    }
  }

  self.ValidarNumeroDocumento = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
      data.NumeroDocumento($(event.target).zFill(data.NumeroDocumento(), 8));

    }
  }

  self.ValidarDescuentoGlobal = function (data, event) {
    if (event) {
      self.CalcularTotales(event);
    }
  }

  self.ValidarFechaEmision = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (valid) {
          self.RecalcularICBPERDetalle(data, event);
          self.FechaMovimientoAlmacen($(event.target).val());

          if (self.IdFormaPago() != ID_FORMA_PAGO_CONTADO) {
            self.FechaVencimiento($(event.target).val());
          }
        }
      });
    }
  }

  self.OnChangeFechaEmision = function (data, event) {
    if (event) {
      self.CalcularTipoCambio(data, event);
    }
  }

  self.ValidarFechaMovimientoAlmacen = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.ValidarDestinatario = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.CalcularTipoCambio = function (data, event) {
    if (event) {
      var resultado = 0.00;
      if (self.IdMoneda() != ID_MONEDA_SOLES)
        if (data.ValorTipoCambio() != "0.00" && data.ValorTipoCambio() != 0 && jQuery.isNumeric(data.ValorTipoCambio())) {
          resultado = data.ValorTipoCambio();
        }
        else {
          self.TipoCambio.ObtenerTipoCambio(data, function ($data) {
            if ($data) {
              resultado = $data.TipoCambioVenta;
              self.ValorTipoCambio(resultado);
            }
            else {
              alertify.alert(self.titulo, "No se encontro un tipo de cambio para la fecha emision");
            }
          });
        }
      return resultado;
    }
  }

  self.ValidarCliente = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdCliente(null);
          self.Direccion("");
          $("#eac-container-" + $form.find("#input-teclado-virtual").val() + " ul").hide();
        }
      });
    }
  }

  self.ValidarRazonSocialDestinatario = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdDestinatario(null);
          self.CelularDestinatario("");
        }
      });
    }
  }

  self.ValidarAutoCompletadoCliente = function (data, event) {
    if (event) {

      var $inputCliente = $form.find("#Cliente");
      $inputCliente[0].value = $inputCliente.val().trim();
      if ($inputCliente.val().length != 11) {
        $inputCliente.attr("data-validation-error-msg", "Ingrese el numero de documento correcto");
      } else {
        $inputCliente.attr("data-validation-error-msg", "No se han encontrado resultados para tu búsqueda de cliente");
      }

      $form.find("#combo-alumno").empty();

      if (data === -1) {
        if ($inputCliente.attr("data-validation-text-found") === $inputCliente.val()) {
          var $evento = { target: self.Options.IDForm + " " + "#Cliente" };
          self.ValidarCliente(data, $evento);
        }
        else {
          $inputCliente.attr("data-validation-text-found", "");
          var $evento = { target: self.Options.IDForm + " " + "#Cliente" };
          self.ValidarCliente(data, $evento);
        }
        self.CargarAlumnos(data, event);

        if (self.ParametroGuardarClienteVenta() == 1) {
          self.GuardarClienteVenta($inputCliente.val(), event);
        } else {
          self.FocusNextAutocompleteCliente(event);
        }

      }
      else {
        if (($inputCliente.attr("data-validation-text-found") !== $inputCliente.val()) || ($inputCliente.val() == "")) {
          if (data.NumeroDocumentoIdentidad == "") {
            $inputCliente.attr("data-validation-text-found", data.RazonSocial);
          } else {
            $inputCliente.attr("data-validation-text-found", data.NumeroDocumentoIdentidad + " - " + data.RazonSocial);
          }
        }

        var $evento = { target: self.Options.IDForm + " " + "#Cliente" };
        data.IdCliente = data.IdPersona;

        //Validar si
        self.DniPasajero("");
        self.NombrePasajero("");
        self.CodigoDocumentoIdentidad(data.CodigoDocumentoIdentidad);

        if (data.CodigoDocumentoIdentidad == CODIGO_TIPO_DOCUMENTO_IDENTIDAD.RUC) {
          $("#DniPasajero").prop('disabled', false);
          $("#NombrePasajero").prop('disabled', false);
          $("#BtnBusquedaRENIEC").prop('disabled', false);
        }
        else {
          $("#DniPasajero").attr('disabled', true);
          $("#NombrePasajero").attr('disabled', true);
          $("#BtnBusquedaRENIEC").prop('disabled', true);
        }

        ko.mapping.fromJS(data, MappingVenta, self);

        if (self.DireccionesCliente().length > 0) {
          var direccionclientedefecto = self.DireccionesCliente()[0].Direccion();
          self.Direccion(direccionclientedefecto);
        }
        else {
          self.Direccion("");
        }

        self.ValidarCliente(data, $evento);
        self.CargarAlumnos(data, event);
        self.FocusNextAutocompleteCliente(event);

        if (self.ParametroAplicaPrecioEspecial() == "1") {
          $("#loader").show();
          self.AplicarPrecioEspecialCliente(data, event, function ($data, $event) {
            $("#loader").hide();

            self.IdTipoListaPrecioEspecial($data);
          });
        }
      }
    }
  }

  self.ValidarAutoCompletadoClientePreCuenta = function (data, event) {
    if (event) {
      var $inputCliente = $form.find("#ClientePreCuenta");
      $inputCliente[0].value = $inputCliente.val().trim();
      if ($inputCliente.val().length != 11) {
        $inputCliente.attr("data-validation-error-msg", "Ingrese el numero de documento correcto");
      } else {
        $inputCliente.attr("data-validation-error-msg", "No se han encontrado resultados para tu búsqueda de cliente");
      }
      $form.find("#combo-alumno").empty();
      if (data === -1) {
        if ($inputCliente.attr("data-validation-text-found") === $inputCliente.val()) {
          var $evento = { target: self.Options.IDForm + " " + "#ClientePreCuenta" };
          self.ValidarCliente(data, $evento);
        }
        else {
          $inputCliente.attr("data-validation-text-found", "");
          var $evento = { target: self.Options.IDForm + " " + "#ClientePreCuenta" };
          self.ValidarCliente(data, $evento);
        }
        self.CargarAlumnos(data, event);

        if (self.ParametroGuardarClienteVenta() == 1) {
          self.GuardarClienteVenta($inputCliente.val(), event);
        } else {
          self.FocusNextAutocompleteCliente(event);
        }

      }
      else {
        if (($inputCliente.attr("data-validation-text-found") !== $inputCliente.val()) || ($inputCliente.val() == "")) {
          if (data.NumeroDocumentoIdentidad == "") {
            $inputCliente.attr("data-validation-text-found", data.RazonSocial);
          } else {
            $inputCliente.attr("data-validation-text-found", data.NumeroDocumentoIdentidad + " - " + data.RazonSocial);
          }
        }

        var $evento = { target: self.Options.IDForm + " " + "#ClientePreCuenta" };
        data.IdCliente = data.IdPersona;
        ko.mapping.fromJS(data, MappingVenta, self);
        self.ValidarCliente(data, $evento);
        self.AplicarDescuentoProductoPorTarjeta(data, event);
        self.CargarAlumnos(data, event);
        self.FocusNextAutocompleteCliente(event);
      }
    }
  }

  self.AplicarDescuentoProductoPorTarjeta = function (data, event) {
    if (event) {
      var idcliente = { IdCliente: self.IdCliente() };
      var hoy = new Date();
      var objetocliente = self.ObtenerFilaClienteJSON(idcliente, event);
      var checkboxdescuento = $("#AplicarDescuentoTarjeta").prop("checked");
      if (objetocliente) {
        var indicadorafiliaciontarjeta = objetocliente.IndicadorAfiliacionTarjeta,
          fechainicioafiliaciontarjeta = new Date(objetocliente.FechaInicioAfiliacionTarjeta),
          fechafinafiliaciontarjeta = new Date(objetocliente.FechaFinAfiliacionTarjeta),
          clienteafiliado = (indicadorafiliaciontarjeta == 1 && (hoy >= fechainicioafiliaciontarjeta && hoy <= fechafinafiliaciontarjeta));

        ko.utils.arrayForEach(self.DetallesComprobanteVenta(), function (item) {
          if (clienteafiliado && checkboxdescuento) {
            var objetoproducto = self.ObtenerFilaMercaderiaJSON(item, event);
            if (objetoproducto.IdProducto) {
              var valordescuento = parseFloatAvanzado(objetoproducto.ValorDescuento);

              if (objetoproducto.TipoDescuento == TIPO_DESCUENTO.MONTO) {
                item.PorcentajeDescuento(0);
                item.DescuentoUnitario(valordescuento);
              } else if (objetoproducto.TipoDescuento == TIPO_DESCUENTO.PORCENTUAL) {
                item.PorcentajeDescuento(valordescuento);
                item.CalcularDescuentoUnitarioPorPorcentaje(data, event);
              }
            }
          } else {
            item.PorcentajeDescuento(0);
            item.DescuentoUnitario(0);
          }
          var clientecontarjeta = clienteafiliado ? "Afiliado a tarjeta 7" : "No afiliado a tarjeta 7";
          $form.find("#ClienteConTarjeta").text(clientecontarjeta);
          item.CalculoSubTotal(data, event);
        });
      }
      self.CalcularTotales(event);
    }
  }

  self.OnClickAplicarDescuentoProductoPorTarjeta = function (data, event) {
    if (event) {
      self.AplicarDescuentoProductoPorTarjeta(data, event);
      var estado = $("#AplicarDescuentoTarjeta").prop("checked") ? false : true;
      $(".PorcentajeDescuento").prop('disabled', estado);
    }
  }

  self.GuardarClienteVenta = function (data, event) {
    if (event) {
      if (data.length == 11) {
        $("#loader").hide();
        alertify.confirm("Cliente No Encontrado", "Agregar Cliente Al Catálago?", function () {
          $("#loader").show();
          self.InsertarClienteEnVenta(data, event, function ($data, $evento) {
            $("#loader").hide();
            self.ValidarAutoCompletadoCliente($data, $evento);
          });
        }, function () {
          $("#loader").hide();
        });
      }
    }
  }

  self.FocusNextAutocompleteCliente = function (event) {
    if (event) {
      var $inputCliente = $form.find("#Cliente");
      var pos = $inputCliente.closest("Form").find("input, select").not(':disabled').index($inputCliente);
      $inputCliente.closest("Form").find("input, select").not(':disabled').eq(pos + 1).focus();
    }
  }

  self.ValidarAutoCompletadoRazonSocialDestinatario = function (data, event) {
    if (event) {
      $form.find("#RazonSocialDestinatario").attr("data-validation-error-msg", "No se han encontrado resultados");

      if (data === -1) {
        if ($form.find("#RazonSocialDestinatario").attr("data-validation-text-found") === $form.find("#RazonSocialDestinatario").val()) {
          var $evento = { target: self.Options.IDForm + " " + "#RazonSocialDestinatario" };
          self.ValidarRazonSocialDestinatario(data, $evento);
        }
        else {
          $form.find("#RazonSocialDestinatario").attr("data-validation-text-found", "");
          var $evento = { target: self.Options.IDForm + " " + "#RazonSocialDestinatario" };
          self.ValidarRazonSocialDestinatario(data, $evento);
        }

        $form.find("#CheckDestinatario").focus();
      }
      else {
        if ($form.find("#RazonSocialDestinatario").attr("data-validation-text-found") !== $form.find("#RazonSocialDestinatario").val()) {
          $form.find("#RazonSocialDestinatario").attr("data-validation-text-found", data.NumeroDocumentoIdentidad + " - " + data.RazonSocial);
        }

        var $evento = { target: self.Options.IDForm + " " + "#RazonSocialDestinatario" };
        self.ValidarRazonSocialDestinatario(data, $evento);
        self.CelularDestinatario(data.Celular);
        self.IdDestinatario(data.IdPersona);
        $("#CheckDestinatario").focus();
      }
    }
  }

  self.ValidarFechaVencimiento = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }

  self.AplicarExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesComprobanteVenta().length;
      var ultimoItem = self.DetallesComprobanteVenta()[total - 1];
      var resultado = "false";
      if (ultimoItem) {
        if (ultimoItem.CodigoMercaderia() === "" && ultimoItem.NombreProducto() === ""
          && (ultimoItem.Cantidad() === "" || ultimoItem.Cantidad() === "0")
          && (ultimoItem.PrecioUnitario() === "" || ultimoItem.PrecioUnitario() === "0")
          && (ultimoItem.DescuentoUnitario() === "" || ultimoItem.DescuentoUnitario() === "0")
        ) {
          resultado = "true";
        }

        $form.find(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputNumeroLote()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputNumeroDua()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputPrecioUnitario()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputPrecioUnitarioSolesDolares()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputDescuentoUnitario()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputSubTotal()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputNumeroDocumentoSalidaZofra()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputCantidadCaja()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputObservacion()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputDescuentoValorUnitario()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputValorUnitario()).attr("data-validation-optional", resultado);
        $form.find(ultimoItem.InputValorVentaItem()).attr("data-validation-optional", resultado);
      }
    }
  }

  self.Cerrar = function (data, event) {
    if (event) {

    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $(self.Options.IDModalComprobanteVenta).modal("hide");//"#modalComprobanteVenta"
      if (self.callback) self.callback(self, event);
    }
  }

  self.OnClickBtnCerrarModalComanda = function (data, event) {
    if (event) {
      alertify.confirm(self.titulo, "¿Esta seguro que desea cerrar la ventana?", function () {
        $(self.Options.IDModalComprobanteVenta).modal("hide");//"#modalComprobanteVenta"
        // if (self.callback) self.callback(self,event);
      }, function () { })
    }
  }

  self.Show = function (event) {
    if (event) {
      self.showComprobanteVenta(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showComprobanteVenta(false);
      // self.callback = undefined;
      // self.OnClickBtnCerrar(self, event);
    }
  }

  self.OnChangeFacturaVenta = function (event) {
    if (event) {
      self.CambiosFormulario(true);
    }
  }

  self.CargarAlumnos = function (data, event) {
    if (event) {
      $form.find("#combo-alumno").empty()
      var alumnos = ko.mapping.toJS(self.Alumnos());
      var data = ko.mapping.toJS(data);
      var id_alumno = data.IdAlumno;
      $.each(alumnos, function (key, entry) {
        if (data.IdCliente == entry.IdCliente) {
          var sel = "";
          if (id_alumno != "" || id_alumno != null) {
            if (id_alumno == entry.IdAlumno) {
              sel = 'selected="true"';
            }
          }
          $form.find("#combo-alumno").append($('<option ' + sel + '></option>').attr('value', entry.IdAlumno).text(entry.NombreAlumno));
        }
      })
      self.OnChangeAlumno(data, event);
    }
  }

  self.OnChangeAlumno = function (data, event) {
    if (event) {
      var $data = ko.mapping.toJS(self.Alumnos());
      var $id = $form.find("#combo-alumno").val();
      if ($id != "" || $id != null) {
        $data.forEach(function (entry, key) {
          if (entry.IdAlumno == $id) {
            self.IdAlumno(entry.IdAlumno);
            self.CodigoAlumno(entry.CodigoAlumno);
          }
        })
      }
    }
  }

  self.ValidarAlumno = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });

    }
  }

  self.CargarCajas = function (data, event) {
    if (event) {
      $form.find("#combo-caja").empty()
      var cajas = ko.mapping.toJS(self.Cajas());
      var data = ko.mapping.toJS(data);
      var id_caja = data.IdCaja;
      $.each(cajas, function (key, entry) {
        if (self.IdMoneda() == entry.IdMoneda) {
          var sel = "";
          if (id_caja != "" || id_caja != null) {
            if (id_caja == entry.IdCaja) {
              sel = 'selected="true"';
            }
          }
          $form.find("#combo-caja").append($('<option ' + sel + '></option>').attr('value', entry.IdCaja).text(entry.NombreCaja));
        }
      })
      self.OnChangeCajas(data, event);
    }
  }

  self.OnChangeCajas = function (data, event) {
    if (event) {
      if (self.IdFormaPago() == ID_FORMA_PAGO_CONTADO) {
        var id = $form.find("#combo-caja option:selected").val();
        self.IdCaja(id == undefined ? "" : id);
      }
    }
  }
  self.OnChangeMontoRecibido = function (data, event) {
    if (event) {
      //self.IndicadorCambioMontoRecibido(false);
      //self.CantidadMontoRecibido(data, event);
      self.CalcularVuelto(data, event);
    }
  }
  self.LimpiarCliente = function (data, event) {
    if (event) {
      if ($form.find("#ClientePreCuenta").is(":visible")) {
        $form.find("#ClientePreCuenta").val("");
        $form.find("#input-teclado-virtual").val("ClientePreCuenta");
        $("#eac-container-Cliente ul").hide();
        $form.find("#GrupoClientePreCuenta").resetearValidaciones(event);

      } else {
        if ($form.find("#CheckCliente").prop("checked")) {
          $form.find("#Cliente").val("");
          self.ValidarCliente(data, event);
          $form.find("#input-teclado-virtual").val("Cliente");
        }
        $("#eac-container-Cliente ul").hide();
        $form.find("#GrupoCliente").resetearValidaciones(event);
      }
    }
  }

  self.CantidadMontoRecibido = function (data, event) {
    if (event) {
      if (self.IndicadorCambioMontoRecibido() == true) {
        var resultado = parseFloatAvanzado($("#MontoRecibido").val());
      } else {
        var resultado = parseFloatAvanzado(self.Total());
      }
      if (resultado < 0) { resultado = 0 };

      self.MontoRecibido(resultado);
      self.CalcularVuelto(data, event);
    }
  }

  self.CalcularVuelto = function (data, event) {
    if (event) {
      var montoTotal = parseFloatAvanzado(self.Total()),
        montoRecibido = parseFloatAvanzado(self.MontoRecibido()),
        vueltoRecibido = montoRecibido < 0 ? 0 : parseFloatAvanzado(montoRecibido - montoTotal);

      self.VueltoRecibido(vueltoRecibido);
      $("#VueltoRecibido").validate(function (valid, elem) { });
    }
  }

  self.ObtenerserieDocumento = function (data, event) {
    if (event) {
      var tipodocumento = $(event.target).data("tipodocumento");
      var listaSeries = ko.mapping.toJS(self.SeriesDocumento);

      var nuevalista = listaSeries.filter(function (entry) {
        return entry.IdTipoDocumento == tipodocumento;
      })

      self.SeriesDocumentoFiltrado(nuevalista);
      self.IdTipoDocumento(nuevalista[0].IdTipoDocumento);
      self.SerieDocumento(nuevalista[0].SerieDocumento);
      self.IdCorrelativoDocumento(nuevalista[0].IdCorrelativoDocumento);

    }
  }

  self.OnClickBtnTipoDocumentoVenta = function (data, event) {
    if (event) {
      self.ObtenerserieDocumento(data, event);
      self.IdCliente("");

      var target = options.IDForm + " " + "#Cliente";

      if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
        $form.find("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.RUC, target);
      } else if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && self.ParametroFiltroClienteSinRuc() == 1) {
        $form.find("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, FILTRO_CLIENTE_SIN_RUC, target);
      }
      else {
        $form.find("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, target);
      }

      if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
        $form.find("#CheckCliente").closest(".form-group").show();
        $form.find("#CheckCliente").prop("checked", false);
        self.CambiarClientesVarios(event);
      }
      else {
        $form.find("#CheckCliente").closest(".form-group").hide();
        $form.find("#CheckCliente").prop("checked", true);
        self.CambiarClientesVarios(event);
      }
    }
  }

  self.ValidarCantidadSegunSaldoPendientePreVenta = function (data, event) {
    if (event) {
      var numitem = 0;
      var alertmsg = "";

      ko.utils.arrayForEach(self.DetallesComprobanteVenta(), function (item) {
        numitem++;
        if (parseFloatAvanzado(item.Cantidad()) > parseFloatAvanzado(item.SaldoPendientePreVenta())) {
          alertmsg += "El " + numitem + "° ítem tiene una cantidad mayor al saldo actual del producto. <br>";
        }
      });
      return alertmsg;
    }
  }
  self.OnClickBtnTipoDocumentoVentaPreCuenta = function (data, event) {
    if (event) {

      var alertmsg = self.ValidarCantidadSegunSaldoPendientePreVenta(data, event);

      if (alertmsg != "") {
        alertify.alert(self.titulo, alertmsg, function () { });
        return false;
      }
      self.CantidadMontoRecibido(data, event);
      self.ObtenerserieDocumento(data, event);

      var target = options.IDForm + " " + "#Cliente";
      if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
        $form.find("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.RUC, target);
      } else if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && self.ParametroFiltroClienteSinRuc() == 1) {
        $form.find("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, FILTRO_CLIENTE_SIN_RUC, target);
      }
      else {
        $form.find("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, target);
      }

      if (self.IdCliente() != "" || self.IdCliente() != null) {
        var datacliente = { IdCliente: self._IdCliente() }
        var objetocliente = self.ObtenerFilaClienteJSON(datacliente, event);
        if (objetocliente.CodigoDocumentoIdentidad == CODIGO_TIPO_DOCUMENTO_IDENTIDAD.RUC && self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
          $form.find("#CheckCliente").closest(".form-group").hide();
          $form.find("#CheckCliente").prop("checked", true);
          self.CambiarClientesVarios(event);
          self.ValidarAutoCompletadoCliente(objetocliente.event);
        } else if (objetocliente.CodigoDocumentoIdentidad != CODIGO_TIPO_DOCUMENTO_IDENTIDAD.RUC && self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
          $form.find("#CheckCliente").closest(".form-group").hide();
          $form.find("#CheckCliente").prop("checked", true);
          self.IdCliente("")
          self.CambiarClientesVarios(event);
        } else if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_TICKET) {
          $form.find("#CheckCliente").closest(".form-group").show();
          $form.find("#CheckCliente").prop("checked", true);
          self.CambiarClientesVarios(event);
          self.ValidarAutoCompletadoCliente(objetocliente, event);
        }
      }

      var nombrecomprobante = self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA ? 'FACTURA' : self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA ? 'BOLETA' : 'TICKET';
      $form.find("#TituloTipoComprobante").text(nombrecomprobante);

      if (!$('.detail-voucher').is(":visible")) {
        $form.resetearValidaciones(event)
        self.OnHideOrShowElement(data, event);
      }

      self.IndicadorEstadoPreVenta(ESTADO_INDICADOR_PREVENTA.POSTPRECUENTA);
    }
  }
  self.ValidarSubTotales = function (data, event) {
    if (event) {
      var numitem = 0;
      var alertmsg = "";

      ko.utils.arrayForEach(self.DetallesComprobanteVenta(), function (item) {
        numitem++;
        if (parseFloatAvanzado(item.SubTotal()) <= 0) {
          alertmsg += "El " + numitem + "° ítem tiene una cantidad menor a 0. <br>";
        }
      });
      return alertmsg;
    }
  }

  self.OnHideOrShowElement = function (data, event) {
    if (event) {
      var elementshide = ".head-details, .body-details, .btn-pagar",
        elementsshow = ".detail-voucher, .btn-opcions";

      if (self.DetallesComprobanteVenta().length <= 0) {
        alertify.alert(self.titulo, "Para realizar el pago tiene que seleccionar por lo menos un Producto.", function () { });
        return false;
      }

      var alertmsg = self.ValidarSubTotales(data, event)

      if (alertmsg != "") {
        alertify.alert(self.titulo, alertmsg, function () { });
        return false;
      }

      if ($(elementshide).is(":visible")) {
        $(elementshide).removeClass("show-element").addClass("hide-element");
        $(elementsshow).removeClass("hide-element").addClass("show-element");
      } else {
        $(elementshide).removeClass("hide-element").addClass("show-element");
        $(elementsshow).removeClass("show-element").addClass("hide-element");

        self.IndicadorEstadoPreVenta(ESTADO_INDICADOR_PREVENTA.PRECUENTA);
        //self.IdTipoDocumento("");
      }

      setTimeout(function () {
        if ($(elementshide).is(":visible")) {
          $(elementshide).hide();
          $(elementsshow).show();
        } else {
          $(elementshide).show();
          $(elementsshow).hide();
        }
        $(elementsshow).removeClass("show-element hide-element");
        sizeProductDetails();
      }, 500);

      self.OnFocusElementForm(data, event);
    }
  }

  self.OnFocusElementForm = function (data, event, callback) {
    if (event) {
      var idelement = $(event.target).attr("id");
      var nameelement = $(event.target).attr("name");

      if (idelement != undefined) {
        $("#input-teclado-virtual").val(idelement);
      } else if (nameelement != undefined) {
        $("#input-teclado-virtual").val(nameelement);
      } else {
        $("#input-teclado-virtual").val("MontoRecibido");
      }
      if (callback) {
        callback(data, event);
      }
    }
    $("#eac-container-Cliente ul").hide();
  }

  self.OnClickBtnCargarNotaSalida = function (data, event) {
    if (event) {
      var datajs = { "SerieNotaSalida": self.SerieNotaSalida(), "NumeroNotaSalida": self.NumeroNotaSalida() };
      $("#loader").show();
      self.ObtenerNotaSalidaVentaSinDocumento(datajs, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          self.DetallesComprobanteVenta([]);

          if ($data.length <= 0) {
            alertify.alert(self.titulo, 'No existe ninguna Nota de Salida ' + self.SerieNotaSalida() + ' - ' + self.NumeroNotaSalida() + ' con motivo "Comprobante sin Documento"', function () { })
            return false;
          }

          var ExisteAlgunSaldoPendienteComprobante = false;
          $data.forEach(function (entry, key) {
            if (entry.SaldoPendienteComprobante > 0) {
              ExisteAlgunSaldoPendienteComprobante = true;
            }
          });

          if (!ExisteAlgunSaldoPendienteComprobante) {
            alertify.alert(self.titulo, 'La Nota de Salida ' + self.SerieNotaSalida() + ' - ' + self.NumeroNotaSalida() + ' ya fue vinculada completamente con otras facturas', function () { })
            return false;
          }



          $data.forEach(function (entry, key) {
            self.IdNotaSalida(entry.IdNotaSalida);
            var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + entry.IdProducto + '.json');
            if (producto.length > 0) {
              producto[0].Cantidad = entry.SaldoPendienteComprobante; //entry.Cantidad;
              producto[0].PrecioUnitario = entry.ValorUnitario;
              producto[0].DescuentoUnitario = '0.00';
              self.AgregarMercaderiaPorNotaSalida(producto[0], event);
            }
          });
          self.ActualizarValorDeStockProducto(data, event);
        } else {

        }
      })
    }
  }

  self.AgregarMercaderiaPorNotaSalida = function (data, event) {
    if (event) {
      var detalle = ko.mapping.toJS(self.NuevoDetalleComprobanteVenta);
      var objeto = Object.assign(detalle, data);

      self.DetallesComprobanteVenta.remove(function (item) { return item.IdProducto() == null; })
      var producto = self.DetallesComprobanteVenta.Agregar(objeto, event);
      producto.InicializarVistaModelo(event);

      var dataItem = self.ObtenerFilaMercaderiaJSON(producto, event);
      if (dataItem) {
        if (self.TipoVenta() == TIPO_VENTA.MERCADERIAS && self.ParametroLote() == 1) {
          producto.ListaLotes(dataItem.ListaLotes);
        }
        if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && (self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z)) {
          producto.ListaZofra(dataItem.ListaZofra);
        }
        if (self.TipoVenta() == TIPO_VENTA.MERCADERIAS && self.ParametroDua() == 1) {
          producto.ListaDua(dataItem.ListaDua);
        }
      }

      producto.CalculoSubTotal(data, event);
      $form.find(producto.InputCodigoMercaderia()).attr("data-validation-found", "true");
      $form.find(producto.InputCodigoMercaderia()).attr("data-validation-text-found", $form.find(producto.InputProducto()).val());
      $form.find(producto.InputNumeroLote()).attr("data-validation-found", "false");
      $form.find(producto.InputNumeroDocumentoSalidaZofra()).attr("data-validation-found", "false");
      $form.find(producto.InputNumeroDua()).attr("data-validation-found", "false");

      $form.find(producto.InputFila()).find("input").attr("readonly", "readonly");
      $form.find(producto.InputCantidad()).removeAttr("readonly");

      var item = self.DetallesComprobanteVenta.Agregar(undefined, event);
      item.TipoVenta(self.TipoVenta());
      item.InicializarVistaModelo(event);
      $(item.InputOpcion()).hide();
      $(item.InputOpcionMercaderia()).hide();
      self.CalcularTotales(event);
      // $form.find(item.InputFila()).find("input").attr("disabled","disabled");

    }
  }

  self.OnEnableDescuentoGlobal = ko.computed(function () {
    var cantidaditems = 0, cantidaditemGravado = 0, cantidaditemExonerado = 0, cantidaditemInafecto = 0;

    if (self.DetallesComprobanteVenta) {
      ko.utils.arrayForEach(self.DetallesComprobanteVenta(), function (item) {
        if (item.IdProducto() != "" && item.IdProducto() != null) {
          cantidaditems++;
          switch (item.CodigoTipoAfectacionIGV()) {
            case CODIGO_AFECTACION_IGV_GRAVADO:
              cantidaditemGravado++;
              break;
            case CODIGO_AFECTACION_IGV_EXONERADO:
              cantidaditemExonerado++;
              break;
            case CODIGO_AFECTACION_IGV_INAFECTO:
              cantidaditemInafecto++;
              break;
            default:
          }
        }
      });
    }

    if ((cantidaditemGravado == cantidaditems) || (cantidaditemExonerado == cantidaditems) || (cantidaditemInafecto == cantidaditems)) {
      return true;
    } else {
      // self.DescuentoGlobal(0);
      return false;
    }

  }, this);

  self.OnFocusOutNumeroDocumentoIdentidad = function (data, event) {
    if (event) {
      //self.OnBuscarPersona(data,event);
    }
  }

  self.OnKeyEnterNumeroDocumentoIdentidad = function (data, event, callback) {
    if (event) {
      if (event.keyCode == TECLA_ENTER) {
        if (self.DniPasajero() != "") {
          self.OnClickBtnBusquedaRENIEC(data, event);
        }
      }

      return true;
    }
  }

  self.OnClickBtnBusquedaRENIEC = function (data, event) {
    if (event) {
      self.OnBuscarPersonaRENIEC(data, event);
    }
  }

  self.OnBuscarPersonaRENIEC = function (data, event) {
    if (event) {
      $("#loader").show();
      self.ConsultarNumeroDocumentoReniec(data, event, function ($data, $event) {
        $("#loader").hide();
        if ($data.success == true) {
          self.DniPasajero($data.result.DNI);
          self.NombrePasajero($data.result.RazonSocial);
        }
        else {
          self.NombrePasajero("");
          alertify.alert(self.titulo, $data.message, function () { })
        }
      })
    }
  }

  self.OnChangeOrigenDestino = function (data, event) {
    if (event) {
      if (self.IdLugarOrigen() != undefined && self.IdLugarDestino() != undefined) {
        $form.resetearValidaciones();
        var nombreOrigen = $("#combo-lugarorigen :selected").text();
        var nombreDestino = $("#combo-lugardestino :selected").text();

        var nombreProducto = `SERVICIO DE ${nombreOrigen} A ${nombreDestino} ${self.Observacion()}`.trim();
        var detalle = self.DetallesComprobanteVenta()[0];

        var rpta = self.ObtenerServicioPorNombreProducto({ NombreProducto: nombreProducto }, event);

        if (rpta.length > 0) {
          var servicio = ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json');
          detalle.Reemplazar(servicio[0]);
          detalle.ValidarAutocompletadoProducto(servicio[0], event)
        } else {
          var objservicio = ko.mapping.toJS(ViewModels.data.Servicio);
          objservicio.NombreProducto = nombreProducto;
          objservicio.NombreLargoProducto = nombreProducto;

          var datajs = { Data: objservicio };
          $("#loader").show();
          detalle.InsertarServicio(datajs, event, function ($data, $event) {
            $("#loader").hide()
            if (!$data.error) {
              var servicio = ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_RUTA_PRODUCTOS + $data.resultado.IdProducto + '.json');
              detalle.Reemplazar(servicio[0]);
              detalle.ValidarAutocompletadoProducto(servicio[0], event)
            } else {
              alertify.alert("servicio", $data.error.msg, function () { })
            }
          })
        }
      }
    }
  }

  self.ObtenerServicioPorNombreProducto = function (data, event) {
    if (event) {

      var json = ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_JSON_SERVICIOS);
      var rpta = JSON.search(json, '//*[NombreProducto="' + data.NombreProducto + '"]');

      if (rpta.length > 0) {
        var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
        return ObtenerJSONCodificadoDesdeURL(ruta_producto);
      } else {
        return [];
      }
    }
  }

  self.ObtenerComprobantesVentaReferencia = function (data, event) {
    if (event) {
      if (self.ParametroRubroClinica() != 1 || self.IdTipoDocumento() != ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) { return false }
      if (ViewModels.data.ComprobantesVentaReferenciaClinica().length > 0) { return false }

      $("#loader").show();
      self.ConsultarComprobantesVentaReferencia(data, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          ViewModels.data.ComprobantesVentaReferenciaClinica(Knockout.CopiarObjeto($data)());
          var objeto = { id: "#DocumentoVentaReferencia", data: $data }
          $form.find(objeto.id).autoCompletadoComprobanteVentaReferencia(objeto, event, self.ValidarAutoCompletadoComprobanteVentaReferencia);

        } else {
          alertify.alert(self.titulo, $data.error.msg, function () { });
        }
      })
    }
  }

  self.ValidarAutoCompletadoComprobanteVentaReferencia = function (data, event) {
    if (event) {
      var $inputComprobanteReferencia = $form.find("#DocumentoVentaReferencia");
      var $evento = { target: `${self.Options.IDForm} #DocumentoVentaReferencia` };

      if (data === -1) {
        var memsajeError = "No se han encontrado resultados para tu búsqueda";
        var NombreDocumento = "";
        var $data = { IdComprobanteVentaReferencia: '' };
      } else {
        var memsajeError = "";
        var NombreDocumento = data.Documento;
        var $data = { Documento: data.Documento, IdComprobanteVentaReferencia: data.IdComprobanteVenta };
      }

      $inputComprobanteReferencia.attr("data-validation-error-msg", memsajeError);
      $inputComprobanteReferencia.attr("data-validation-text-found", NombreDocumento);

      ko.mapping.fromJS($data, {}, self);
      self.ValidarComprobanteVentaReferencia(data, $evento);
    }
  }

  self.ValidarComprobanteVentaReferencia = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdComprobanteVentaReferencia('');
        }
      });
    }
  }

  self.ValidarAutoCompletadoRadioTaxi = function (data, event) {
    if (event) {
      var $inputComprobanteReferencia = $form.find("#NombreRadioTaxi");
      var $evento = { target: `${self.Options.IDForm} #NombreRadioTaxi` };

      if (data === -1) {
        var memsajeError = "No se han encontrado resultados para tu búsqueda";
        var NombreRadioTaxi = "";
        var $data = { IdRadioTaxi: '' };
      } else {
        var memsajeError = "";
        var NombreRadioTaxi = data.NombreRadioTaxi;
        var $data = { Documento: data.NombreRadioTaxi, IdRadioTaxi: data.IdRadioTaxi, NombreRadioTaxi };
      }

      $inputComprobanteReferencia.attr("data-validation-error-msg", memsajeError);
      $inputComprobanteReferencia.attr("data-validation-text-found", NombreRadioTaxi);

      ko.mapping.fromJS($data, {}, self);
      self.ValidarRadioTaxi(data, $evento);
    }
  }

  self.ValidarRadioTaxi = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdRadioTaxi('');
        }
      });
    }
  }

  self.OnChangeComboVendedores = function (data, event) {
    if (event) {
      if (self.ComprobanteVentaInicial) {
        self.ComprobanteVentaInicial.AliasUsuarioVenta = self.AliasUsuarioVenta();
        if (self.opcionProceso() == opcionProceso.Nuevo) {
          ViewModels.data.ComprobanteVentaNuevo.AliasUsuarioVenta(self.AliasUsuarioVenta());
        }
      }
    }
  }


  self.ObtenerComprobantesVentaProforma = function (data, event) {
    if (event) {
      if (self.ParametroProforma() != 1) { return false }
      if (ViewModels.data.ComprobantesVentaProforma().length > 0) { return false }

      $("#loader").show();
      self.ConsultarComprobantesVentaProforma(data, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          ViewModels.data.ComprobantesVentaProforma(Knockout.CopiarObjeto($data)());

          var objeto = { id: "#DocumentoVentaProforma", data: $data }
          $form.find(objeto.id).autoCompletadoComprobanteVentaProforma(objeto, event, self.ValidarAutoCompletadoComprobanteVentaProforma);

        } else {
          alertify.alert(self.titulo, $data.error.msg, function () { });
        }
      })
    }
  }

  self.ValidarAutoCompletadoComprobanteVentaProforma = function (data, event) {
    if (event) {
      var $inputComprobanteReferenciaProforma = $form.find("#DocumentoVentaProforma");
      var $evento = { target: `${self.Options.IDForm} #DocumentoVentaProforma` };

      if ($inputComprobanteReferenciaProforma.val() !== "") {
        if (data === -1) {
          var memsajeError = "No se han encontrado resultados para tu búsqueda";
          var NombreDocumento = "";
          var $data = { IdReferenciaProforma: '' };
          var comprobante = {}
        } else {
          var memsajeError = "";
          var NombreDocumento = data.Documento;
          var $data = { Documento: data.Documento, IdReferenciaProforma: data.IdComprobanteVenta };
          var comprobante = data;
        }

        $inputComprobanteReferenciaProforma.attr("data-validation-error-msg", memsajeError);
        $inputComprobanteReferenciaProforma.attr("data-validation-text-found", NombreDocumento);

        ko.mapping.fromJS($data, {}, self);

        self.ValidarComprobanteVentaProforma(data, $evento);
        self.CargarProformaEnComprobanteVenta(comprobante, event);
      }
      var pos = $inputComprobanteReferenciaProforma.closest("Form").find("input, select").not(':disabled').index($inputComprobanteReferenciaProforma);
      $inputComprobanteReferenciaProforma.closest("Form").find("input, select").not(':disabled').eq(pos + 1).focus();
    }
  }

  self.ValidarComprobanteVentaProforma = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdReferenciaProforma('');
        }
      });
    }
  }

  self.CargarProformaEnComprobanteVenta = function (data, event) {
    if (event) {
      var cliente = self.ObtenerFilaClienteJSON(data, event);

      if (cliente) {
        if (self.IdTipoDocumento() != ID_TIPO_DOCUMENTO_FACTURA) {
          $form.find("#CheckCliente").prop("checked", true)
          self.OnChangeCheckCliente(data, event);
        }

        self.ValidarAutoCompletadoCliente(cliente, event);
      }

      self.Observacion(data.Observacion);
      self.IdFormaPago(data.IdFormaPago);
      self.MontoACuenta(data.MontoACuenta);

      self.DetallesComprobanteVenta([]);

      $("#loader").show()
      self.ConsultarDetallesComprobanteVenta(data, event, function ($data, $event) {
        self.InicializarVistaModeloDetalle(undefined, event);
        if (self.DetallesComprobanteVenta().length > 0) {
          ko.utils.arrayForEach(self.DetallesComprobanteVenta(), function (item) {

            if (item.IdProducto() != null) self.CopiaIdProductosDetalle.push(item.IdProducto())

            var dataItem = self.ObtenerFilaMercaderiaJSON(item, event);
            if (dataItem) {
              if (self.TipoVenta() == TIPO_VENTA.MERCADERIAS && self.ParametroLote() == 1) {
                item.ListaLotes(dataItem.ListaLotes);
              }
              if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA && (self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || self.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z)) {
                item.ListaZofra(dataItem.ListaZofra);
              }
              if (self.TipoVenta() == TIPO_VENTA.MERCADERIAS && self.ParametroDua() == 1) {
                item.ListaDua(dataItem.ListaDua);
              }
            }
            $form.find(item.InputCodigoMercaderia()).attr("data-validation-found", "true");
            $form.find(item.InputCodigoMercaderia()).attr("data-validation-text-found", $form.find(item.InputProducto()).val());
            $form.find(item.InputNumeroLote()).attr("data-validation-found", "true");
            $form.find(item.InputNumeroDocumentoSalidaZofra()).attr("data-validation-found", "true");
            $form.find(item.InputNumeroDua()).attr("data-validation-found", "true");
          })
        }

        $('#loader').hide();
      });

    }
  }

  self.OnPorcentajeComision = function (data, event) {
    if (event) {
      var porcentajecomision = parseFloatAvanzado(self.PorcentajeComision());
      var montocomision = parseFloatAvanzado(porcentajecomision * self.Total() / 100);
      self.MontoComision(parseFloatAvanzado(montocomision.toFixed(NUMERO_DECIMALES_VENTA)));
    }
  }

  self.OnChangeDireccion = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-direcciones option:selected").text();
      var valor = $form.find("#combo-direcciones option:selected").val();
      self.Direccion(texto);
    }
  }

  self.ValidarAutoCompletadoNumeroPlacaVehiculo = function (data, event) {
    if (event) {
      var $inputNumeroPlaca = $form.find("#NumeroPlaca");
      var $evento = { target: `${self.Options.IDForm} #NumeroPlaca` };

      if (data === -1) {
        var memsajeError = "No se han encontrado resultados para tu búsqueda";
        var numeroPlaca = "";
        var $data = { IdReferenciaProforma: '' };
      } else {
        var memsajeError = "";
        var numeroPlaca = data.NumeroPlaca;
        var $data = { NumeroPlaca: data.NumeroPlaca, KilometrajeVehiculo: data.UltimoKilometraje };
      }

      $inputNumeroPlaca.attr("data-validation-error-msg", memsajeError);
      $inputNumeroPlaca.attr("data-validation-text-found", numeroPlaca);

      ko.mapping.fromJS($data, {}, self);

      self.ValidarNumeroPlaca(data, $evento);

      var RadioTaxis = ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_JSON_RADIO_TAXI);
      var RadioTaxi = JSON.search(RadioTaxis, '//*[ IdRadioTaxi ="' + data.IdRadioTaxiActual + '"]');

      if (RadioTaxi.length > 0) {
        self.ValidarAutoCompletadoRadioTaxi(RadioTaxi[0], $evento);
      }
    }
  }

  self.ValidarNumeroPlaca = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) { });
    }
  }

  self.OnClickBtnGuiasRemisionRemitente = function (data, event) {
    if (event) {
      self.ShowModalGuiasRemisionRemitente(true)
    }
  }

  self.OnHideModalGuiasRemisionRemitente = function (data, event) {
    if (event) {
      self.ShowModalGuiasRemisionRemitente(false)

    }
  }

  self.OnClickBtnCasillerosPorGenero = function (data, event) {
    if (event) {
      var casillerosPorGenero = ViewModels.data.CasillerosPorGenero

      if (casillerosPorGenero.Masculino().length > 0 && casillerosPorGenero.Femenino().length > 0) {
        self.ShowCasillerosPorGenero(true)
      } else {
        $('#loader').show();
        var datajs = { Data: { IdGenero: '%' } };
        self.ListarCasillerosGenero(datajs, event, function ($data) {
          $('#loader').hide()
          if (!$data.error) {
            var casillerosMasculinos = Knockout.CopiarObjeto($data.filter(item => item.IdGenero == ID_GENERO_MASCULINO))
            var casillerosFemeninos = Knockout.CopiarObjeto($data.filter(item => item.IdGenero == ID_GENERO_FEMENINO))

            casillerosPorGenero.Masculino(casillerosMasculinos());
            casillerosPorGenero.Femenino(casillerosFemeninos());


            if (self.opcionProceso() == opcionProceso.Edicion) {
              var casilleros = self.IdGenero() == ID_GENERO_MASCULINO ? casillerosMasculinos : casillerosFemeninos;
              var casillero = ko.utils.arrayFirst(casilleros(), item => item.IdCasillero() == self.IdCasillero());

              self.CasilleroSeleccionado = casillero;
            }

            self.ShowCasillerosPorGenero(true);

          } else {
            alertify.alert(self.titulo, $data.error.msg, function () { });
          }
        });
      }
    }
  }

  self.OnHideCasillerosPorGenero = function (event) {
    if (event) {
      var existeCasilleroSeleccionado = self.CasilleroSeleccionado.IdCasillero ? true : false

      var data = {
        IdCasillero: existeCasilleroSeleccionado ? self.CasilleroSeleccionado.IdCasillero() : "",
        NombreCasillero: existeCasilleroSeleccionado ? self.CasilleroSeleccionado.NombreCasillero() : "",
        IdGenero: existeCasilleroSeleccionado ? self.CasilleroSeleccionado.IdGenero() : "",
        NombreGenero: existeCasilleroSeleccionado ? self.CasilleroSeleccionado.NombreGenero() : "",
      }

      ko.mapping.fromJS(data, {}, self)

      self.ShowCasillerosPorGenero(false);
    }
  }

  self.OnClickBtnCasillero = function (data, event) {
    if (event) {
      var existeCasilleroSeleccionado = self.CasilleroSeleccionado.IdCasillero ? true : false
      if (data.IndicadorCasilleroDisponible() == 1) {
        if (existeCasilleroSeleccionado) {
          self.CasilleroSeleccionado.IndicadorCasilleroDisponible(1)
        }
        data.IndicadorCasilleroDisponible(0);
        self.CasilleroSeleccionado = data;
      } else {
        if (existeCasilleroSeleccionado) {
          if (self.CasilleroSeleccionado.IdCasillero() == data.IdCasillero()) {
            data.IndicadorCasilleroDisponible(1);
            self.CasilleroSeleccionado = {};
          }
        }
      }
    }
  }

  self.LiberarCasillero = function (data, event, callback) {
    if (event) {
      var datajs = { Data: ko.mapping.toJS(data, mappingIgnore) }
      $("#loader").show()
      self.LiberarCasilleroGenero(datajs, event, function ($data) {
        $("#loader").hide()
        callback($data, event)
      })
    }
  }

  self.OnEnableBtnLiberarCasillero = ko.computed(function () {
    return self.IndicadorCasilleroDisponible() == 0 ? true : false;
  }, this);

  self.OnClickBtnBuscarProformas = function (data, event, $parent) {
    if (event) {
      ViewModels.data.BusquedaProformaVenta.Inicializar(data, event, $parent)
      $("#modalBuscadorProforma").modal("show");
    }
  }

  self.CambiosEnElDetale = ko.computed(function () {
    if (self.DetallesComprobanteVenta != undefined) {
      var detalles = self.DetallesComprobanteVenta();
    }
    else {
      var detalles = [];
    }

    var totalCantidad = 0;
    ko.utils.arrayForEach(detalles, function (item, key) {
      item.NumeroItem(key + 1);

      if (item.IdProducto() !== null && item.IdProducto() !== "") {
        totalCantidad = parseFloatAvanzado(totalCantidad) + parseFloatAvanzado(item.Cantidad());
      }
    });
    self.TotalCantidades(totalCantidad.toFixed(NUMERO_DECIMALES_VENTA))
  }, this)


  self.OnChangeMontoRecibido = function (data, event) {
    if (event) {
      if (self.ParametroCampoACuenta() == 1) {
        var debe = parseFloatAvanzado(self.Total()) - parseFloatAvanzado(self.MontoACuenta())
        var vuelto = parseFloatAvanzado(self.MontoRecibido()) - parseFloatAvanzado(debe);
      } else {
        var debe = parseFloatAvanzado(self.MontoDebe())
        var vuelto = parseFloatAvanzado(self.MontoRecibido()) - parseFloatAvanzado(self.Total());
      }
      self.MontoDebe(debe.toFixed(NUMERO_DECIMALES_VENTA));
      self.VueltoRecibido(vuelto.toFixed(NUMERO_DECIMALES_VENTA));
    }
  }

  self.OnChangeMontoACuenta = function (data, event) {
    if (event) {
      self.OnRefrescar(data, event, false)
    }
  }

  self.OnClickBtnNuevoVehiculoCliente = function (data, event, cliente) {
    if (event) {
      if (self.IdCliente() == "" || self.IdCliente() == null || self.IdCliente() == ID_CLIENTES_VARIOS) {
        alertify.alert(self.titulo, "Debe seleccionar un cliente para agregar nuevas placas", function () { });
        return false;
      }

      cliente.IndicadorGuardarVehiculoCliente(true);
      cliente.showVehiculos(true);
      cliente.IdClienteVenta(self.IdCliente())
    }
  }

}