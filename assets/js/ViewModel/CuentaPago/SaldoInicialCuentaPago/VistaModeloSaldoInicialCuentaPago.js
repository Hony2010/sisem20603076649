VistaModeloSaldoInicialCuentaPago = function (data, base) {
    var self = this;
    ko.mapping.fromJS(data, MappingCuentaPago, self);
    ModeloSaldoInicialCuentaPago.call(this,self);

    self.VisibleSpan = ko.observable(true);
    self.VisibleInput = ko.observable(false);
    self.OpcionProceso = ko.observable("");
    self.base = base;
    self.CopiaData = [];

    self.Inicializar = function ()  {
      self.InicializarValidator(event);
      $(".fecha").inputmask({"mask": "99/99/9999"});
      var target = self.InputRazonSocialProveedor();
      $(target).autoCompletadoProveedor(event,self.ValidarAutoCompletadoProveedor,CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS,target);
    }

    self.InicializarValidator = function(event) {
      if(event) {
        $.formUtils.addValidator({
          name : 'autocompletado_proveedor',
          validatorFunction : function(value, $el, config, language, $form) {
            var texto = $el.attr("data-validation-text-found");
            var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "")  ? true : false;
            return resultado;
          },
          errorMessageKey: 'badautocompletado_proveedor'
        });

        $.formUtils.addValidator({
          name : 'validacion_producto',
          validatorFunction : function(value, $el, config, language, $form) {
            var texto = $el.attr("data-validation-found");
            var resultado = ("true" === texto) ? true : false;
            return resultado;
          },
          errorMessageKey: 'badvalidacion_producto'
        });

        $.formUtils.addValidator({
          name : 'autocompletado_producto',
          validatorFunction : function(value, $el, config, language, $form) {
            var $referencia = $("#"+$el.attr("data-validation-reference"));
            var texto = $referencia.attr("data-validation-text-found").toUpperCase();
            var resultado = (value.toUpperCase() === texto && value.toUpperCase() !== "") ? true : false;
            return resultado;
          },
          errorMessageKey: 'badautocompletado_producto'
        });
      }
    }

    self.InputRazonSocialProveedor = ko.computed( function() {
      if(self.IdSaldoInicialCuentaPago) {
        return "#inputRazonSocialProveedor_" + self.IdSaldoInicialCuentaPago();
      } else {
        return "";
      }
    },this);

    self.OcultarInput = function (data, event) {
      if (event) {
        self.VisibleSpan(true);
        self.VisibleInput(false);
      }
    }

    self.MostrarInput = function (data, event) {
      if (event) {
        self.VisibleSpan(false);
        self.VisibleInput(true);
      }
    }

    self.OnClickBtnDetalle = function (data, event) {
      if(event) {
        self.base.showDetalleComprobante(true);
        self.ParseDataDetalleSaldoInicial(data, event);
      }
    }

    self.ParseDataDetalleSaldoInicial = function (data, event) {
      if (event) {
        self.base.IdSaldoInicialSeleccionado(self.IdSaldoInicialCuentaPago());

        var detalle = ko.mapping.toJS(self.DetallesSaldoInicialCuentaPago());
        ko.utils.arrayForEach(detalle, function (item) {
          var response = self.AgregarDetalles(item, event);
        });
        self.NuevoDetalle(event);
      }
    }

    self.ValidarGuardar = function (data, event) {
      if (event) {
        var subTotales = 0;
        var mensaje = "";
        ko.utils.arrayForEach(self.DetallesSaldoInicialCuentaPago(), function (item) {
          subTotales += parseFloatAvanzado(item.SubTotal());
        });

        if (self.DetallesSaldoInicialCuentaPago().length > 0) {
          if ( parseFloatAvanzado(self.MontoOriginal()) != subTotales) {
            mensaje = "El monto original del comprobante no es igual al monto montal de los detalles.";
          }
        }
        return mensaje;
      }
    }

    self.OnClickBtnGuardar = function (data, event) {
      if(event) {
        var validation = self.ValidarGuardar(data, event);
        if (validation != "") {
          alertify.alert(self.base.TituloAlerta(), validation, function () { });
          return false;
        }

        $("#loader").show();
        self.GuardarSaldoInicialCuentaPago(data, event, function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            alertify.alert(self.base.TituloAlerta(), $data.mensaje, function () {
              self.CopiaData = ko.mapping.toJS(self);
              self.OcultarInput(data, event);
              self.OpcionProceso(opcionProceso.Edicion);
              self.base.OnFilaNueva(false);
              self.base.UltimoItemSeleccionado = [];
            })
          } else {
            alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg,function () { });
          }
        });
      }
    }

    self.NuevoDetalle = function(event)  {
      if(event) {
        ko.utils.arrayForEach(self.base.data.DetallesSaldoInicialCuentaPago(),function (item) { item.UltimoItem(false); });
        var nuevo = ko.mapping.toJS(self.base.data.NuevoDetallesSaldoInicialCuentaPago);
        var response = self.AgregarDetalles(nuevo, event);
        response.UltimoItem(true);
      }
    }

    self.AgregarDetalles = function(data, event) {
      if(event) {
        var resultado = new VistaModeloDetalleSaldoInicialCuentaPago(data,self);
        var idMaximo = Math.max.apply(null,ko.utils.arrayMap(self.base.data.DetallesSaldoInicialCuentaPago(),function(e){return e.IdDetalleSaldoInicialCuentaPago(); }));
        self.base.data.DetallesSaldoInicialCuentaPago.push(resultado);
        resultado.IdDetalleSaldoInicialCuentaPago(idMaximo == '-Infinity' ? 1 : idMaximo + 1);
        resultado.InicializarVistaModelo(event);
        return resultado;
      }
    }

    self.OnClickBtnEditar = function (data, event) {
      if(event) {
        self.base.OnClickFilaSaldoInicial(data, event);
      }
    }

    self.OnClickBtnEliminar = function (data,event) {
      if (event) {
        if (data.OpcionProceso() == opcionProceso.Nuevo) {
          self.base.RemoverFilaNuevaSaldoInicial(data, event);
        } else {
          $("#loader").show();
          self.EliminarSaldoInicialCuentaPago(data, event, function ($data, $event) {
            $("#loader").hide();
            self.PostEliminar($data, $event);
          });
        }
      }
    }

    self.PostEliminar = function (data,event) {
      if(event) {
        if (!data.error) {
          ko.mapping.fromJS(data, {}, self);
          alertify.alert(self.base.TituloAlerta(),"Se Eliminó el comprobante  " +self.SerieDocumento() + " - " + self.NumeroDocumento()+ " correctamente.",function () {
            self.base.SeleccionarAnterior(self, event);
            self.base.data.SaldosInicialesCuentaPago.remove(self);
          });
        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $ata.error.msg,function () { });
        }
      }
    }

    self.RazonSocialProveedor = ko.computed( function () {
      var resultado = "";
      if(self.RazonSocial() == "") {
        resultado = "";
      } else if (self.NumeroDocumentoIdentidad() == "") {
        resultado = self.RazonSocial();
      } else {
        resultado = self.NumeroDocumentoIdentidad()+'  -  '+self.RazonSocial();
      }

      return resultado ;
    }, this);

    self.ObtenerNombreMoneda = ko.computed( function () {
      var moneda = ko.utils.arrayFilter(self.Monedas(), function (item) {
        return item.IdMoneda() == self.IdMoneda();
      })
      self.NombreMoneda(moneda.length > 0 ? moneda[0].NombreMoneda() : "");
    }, this);

    self.ObtenerNombreTipoDocumento = ko.computed( function () {
      var tipoDocumento = ko.utils.arrayFilter(self.TiposDocumento(), function (item) {
        return item.IdTipoDocumento() == self.IdTipoDocumento();
      })
      self.TipoDocumento(tipoDocumento.length > 0 ? tipoDocumento[0].NombreAbreviado() : "");
    }, this);

    self.OnEnableBtnDetalle = ko.computed( function () {
      if (self.base.OnFilaNueva() == true) {
        if (self.VisibleInput() == true) {
          return true;
        } else {
          return false;
        }
      } else {
        return self.VisibleInput() ? true : false;
      }
    }, this);

    self.OnEnableBtnGuardar = ko.computed( function () {
      if (self.base.OnFilaNueva() == true) {
        if (self.VisibleInput() == true) {
          return true;
        } else {
          return false;
        }
      } else {
        return self.VisibleInput() ? true : false;
      }
    }, this);

    self.OnEnableBtnEditar = ko.computed( function () {
      if (self.base.OnFilaNueva() == true) {
          return false;
      } else {
        return self.VisibleInput() ? false : true;
      }
    }, this);

    self.OnEnableBtnEliminar = ko.computed( function () {
      if (self.base.OnFilaNueva() == true) {
        if (self.VisibleInput() == true) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    }, this);

    self.ValidarAutoCompletadoProveedor = function(data, event) {
      if (event) {
        var $inputProveedor = $(self.InputRazonSocialProveedor());
        $inputProveedor[0].value = $inputProveedor.val().trim();
        if($inputProveedor.val().length != 11){
          $inputProveedor.attr("data-validation-error-msg","Ingrese el numero de documento correcto");
        } else {
          $inputProveedor.attr("data-validation-error-msg","No se han encontrado resultados para tu búsqueda de proveedor");
        }
        var $evento = { target : self.InputRazonSocialProveedor() };

        if(data === -1 ) {
          if($inputProveedor.attr("data-validation-text-found") === $inputProveedor.val() ) {
            self.ValidarProveedor(data, $evento);
          } else {
            $inputProveedor.attr("data-validation-text-found","");
            self.ValidarProveedor(data, $evento);
          }
        } else {
          data.IdProveedor = data.IdPersona;
          ko.mapping.fromJS(data, {}, self);
          self.ValidarProveedor(data, $evento);
          self.FocusNextAutocompleteProveedor (event)
        }
      }
    }

    self.FocusNextAutocompleteProveedor = function(event) {
      if (event) {
        var $inputProveedor =  $(self.InputRazonSocialProveedor());
        var pos = $inputProveedor.closest("tr").find("input").not(':disabled').index($inputProveedor);
        $inputProveedor.closest("tr").find("input, select").not(':disabled').eq(pos+1).focus();
      }
    }

    self.ValidarProveedor = function(data,event)  {
      if(event) {
        $(event.target).validate(function(valid, elem) {
            if(!valid) { self.IdProveedor(""); }
        });
      }
    }

    self.OnQuitarFila = function (data,event) {
      if(event) {
          self.base.data.DetallesSaldoInicialCuentaPago.remove(data,event);
          var trfilas = $("#tablaDetalleComprobanteVenta").find("tr").find("button:visible");
      }
    }

    self.OnFocusOutNumeroDocumento = function (data, event) {
      if (event) {
        data.NumeroDocumento($(event.target).zFill(data.NumeroDocumento(),8));
      }
    }

    self.OnFocusOutSerieDocumento = function (data, event) {
      if (event) {
        data.SerieDocumento($(event.target).zFill(data.SerieDocumento(),4));
      }
    }


}
