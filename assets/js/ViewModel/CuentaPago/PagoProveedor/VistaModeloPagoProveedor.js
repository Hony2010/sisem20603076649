VistaModeloPagoProveedor = function (data,options) {
    var self = this;
    self.Options = options;
    ko.mapping.fromJS(data, MappingCuentaPago, self);
    self.IndicadorReseteoFormulario = true;
    self.TotalSoles = ko.observable("0.00");
    self.TotalDolares = ko.observable("0.00");
    self.TotalMontoOriginal = ko.observable("0.00");
    self.TotalNuevoSaldo = ko.observable("0.00");
    self.TotalSaldo = ko.observable("0.00");

    ModeloPagoProveedor.call(this,self);

    var $form = $(self.Options.IDForm);
    var idform = self.Options.IDForm;
    self.InicializarVistaModelo =function(data,event) {
      if(event) {
        self.InicializarModelo();
        self.InicializarValidator(event);
        $(".fecha").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});

        var target = idform + " #RazonSocialProveedor";
        $(target).autoCompletadoProveedor(event,self.ValidarAutoCompletadoProveedor,CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS,target);

        AccesoKey.AgregarKeyOption(idform, "#BtnGrabar", TECLA_G);
        setTimeout(function(){
          $('#FechaComprobante').focus();
        }, 500);
      }
    }

    self.InicializarValidator = function(event) {
      if(event) {
        $.formUtils.addValidator({
          name: "formapagocheque",
          validatorFunction: function(value) {
            return (value == "" && self.IdMedioPago() == MEDIO_PAGO.CHEQUE ? false : true)
          },
          errorMessage: "",
          errorMessageKey: "Selecciona un Item"
        });

        $.formUtils.addValidator({
          name: "select",
          validatorFunction: function(value) {
            return (value == "" || value == null || value == 0 ? false : true)
          },
          errorMessage: "",
          errorMessageKey: "Selecciona un Item"
        });

        $.formUtils.addValidator({
          name : 'autocompletado_proveedor',
          validatorFunction : function(value, $el, config, language, $form) {
            var texto = $el.attr("data-validation-text-found");
            var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "")  ? true : false;
            return resultado;
          },
          errorMessageKey: 'badautocompletado_proveedor'
        });
      }
    }

    self.OnFocus = function(data,event,callback) {
      if(event)  {
          $(event.target).select();
          if(callback) callback(data,event);
      }
    }

    self.OnKeyEnter = function(data,event) {
      if(event) {
        var resultado = $(event.target).enterToTab(event);
        return resultado;
      }
    }

    self.OnNuevo = function (data,event,callback) {
      if (event) {
        $form.resetearValidaciones();
        if(callback) self.callback = callback;
        self.NuevoPagoProveedor(data,event);
        self.InicializarVistaModelo(data,event);
      }
    }

    self.OnVer = function (data,event,callback) {
      if(event) {
        $form.disabledElments($form, true);
        self.OnEditar(data, event, callback, true);
      }
    }

    self.OnEditar = function (data,event,callback, ver = false) {
      if(event) {
        if (!ver) {$form.disabledElments($form, false);}
        if (self.IndicadorReseteoFormulario === true)  $form.resetearValidaciones();
        if(callback) self.callback = callback;
        self.ConsultarDetallesPagoProveedor(data, event, ver);
        self.EditarPagoProveedor(data,event);
        self.InicializarVistaModelo(data,event);
        $form.find("#RazonSocialProveedor").attr("data-validation-text-found",self.RazonSocialProveedor());
      }
    }

    self.OnEliminar = function(data,event,callback) {
      if(event) {
          self.EliminarPagoProveedor(data,event,function($data,$event) {
            callback($data,$event);
          });
      }
    }

    self.OnAnular = function(data,event,callback) {
      if(event) {
          self.AnularPagoProveedor(data,event,function($data,$event) {
            callback($data,$event);
          });
      }
    }

    self.OnClickBtnGrabar = function(data, event)  {
      if(event) {
        if($form.isValid() === false) {
          alertify.alert("Error en Validación de "+self.titulo,"Existe aun datos inválidos , por favor de corregirlo.",function() {
            setTimeout(function(){
              $form.find('.has-error').find('input, select').first().focus();
            }, 300);
          });
        }
        else {
          self.CalcularTotalComprobante(data, event);
          if (self.DetallesPagoProveedor().length <= 0) {
            alertify.alert(self.titulo, "No hay ningun pago para el proveedor seleccionado.",function () { });
            return false;
          }
          if (self.MontoComprobante() <= 0) {
            alertify.alert(self.titulo, "El monto del comprobante debe ser mayor a 0.",function () { });
            return false;
          }
          $("#loader").show();
          self.GuardarPagoProveedor(event,function($data,$event){
            if($data.error) {
              $("#loader").hide();
              alertify.alert("Error en "+self.titulo,$data.error.msg,function() {});
            }
            else {
              $("#loader").hide();
              if (self.opcionProceso() == opcionProceso.Nuevo) {
                if (self.callback) self.callback(self,$data,event);
              }
              else {
                alertify.alert(self.titulo,self.mensaje, function() {
                  if (self.callback) self.callback(self,$data,event);
                });
              }
            }
          });
        }
      }
    }

    self.OnClickBtnDeshacer = function(data,event)  {
      if(event) {
        self.OnEditar(self.PagoProveedorInicial,event,self.callback);
      }
    }

    self.OnClickBtnLimpiar = function (data,event) {
      if(event) {
        $("#RazonSocialProveedor").val("");
        self.OnNuevo(self.PagoProveedorInicial,event,self.callback);
      }
    }

    self.OnClickBtnCerrar = function(data,event)  {
      if(event) {
        $("#modalPagoProveedor").modal("hide");
        if (self.callback) self.callback(self,event);
      }
    }

    self.Show = function(event) {
      if(event) {
        self.showPagoProveedor(true);
      }
    }

    self.Hide = function(event) {
      if(event) {
        self.showPagoProveedor(false);//$("#modalPagoProveedor").modal("hide");
        self.EstaProcesado(false);
        self.OnClickBtnCerrar(self,event);
      }
    }

    self.MostrarTitulo = ko.computed( function () {
      if (self.opcionProceso() == opcionProceso.Nuevo)  {
        self.titulo = "REGISTRO DE PAGO PROVEEDOR";
      }
      else {
        self.titulo = "EDICIÓN DE PAGO PROVEEDOR";
      }
      return self.titulo;
    },this);

    self.ValidarFechaApertura =  function (data,event) {
      if (event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }
    self.ValidarNroCheque =  function (data,event) {
      if (event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ValidarMontoComprobante =  function (data,event) {
      if (event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ValidarProveedor = function(data,event)  {
      if(event) {
        $(event.target).validate(function(valid, elem) {
            if(!valid) {
              self.IdPersona("");
            }
        });
      }
    }

    self.OnChangeCaja = function (data, event) {
      if (event) {
        var $data = ko.mapping.toJS(self.Cajas());
        var busqueda = JSPath.apply('.{.IdCaja *= $Texto}', $data, {Texto: self.IdCaja()});
        self.IdMoneda(busqueda[0].IdMoneda);
        $("#loader").show();
        self.ConsultarPagoProveedor(data, event, function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            self.MontoComprobante($data.MontoComprobante);
          } else {
            alertify.alert(self.titulo, $data.error.msg, function functionName() {
              self.MontoComprobante("");
            })
          }
        })
      }
    }

    self.ValidarAutoCompletadoProveedor = function(data, event) {
        if (event) {
          var $inputProveedor = $form.find("#RazonSocialProveedor");
          $inputProveedor[0].value = $inputProveedor.val().trim();
          if($inputProveedor.val().length != 11){
            $inputProveedor.attr("data-validation-error-msg","Ingrese el numero de documento correcto");
          } else {
            $inputProveedor.attr("data-validation-error-msg","No se han encontrado resultados para tu búsqueda de proveedor");
          }

          if(data === -1 ) {
            if($inputProveedor.attr("data-validation-text-found") === $inputProveedor.val() ) {
              var $evento = { target : idform + " "+"#RazonSocialProveedor" };
              self.ValidarProveedor(data,$evento);
            } else {
              $inputProveedor.attr("data-validation-text-found","");
              var $evento = { target : idform + " "+"#RazonSocialProveedor" };
              self.ValidarProveedor(data,$evento);
            }
          } else {
            if(($inputProveedor.attr("data-validation-text-found") !== $inputProveedor.val()) || ($inputProveedor.val() == "")) {
              if (data.NumeroDocumentoIdentidad  == "") {
                $inputProveedor.attr("data-validation-text-found",data.RazonSocial);
              }else {
                $inputProveedor.attr("data-validation-text-found",data.NumeroDocumentoIdentidad +"  -  "+ data.RazonSocial);
              }
            }

            var $evento = { target : idform + " "+"#RazonSocialProveedor"};
            ko.mapping.fromJS(data,MappingCuentaPago,self);
            self.ValidarProveedor(data,$evento);
            setTimeout(function () { $("#BtnBuscarPago").focus() }, 200);
          }
        }
    }

    self.OnClickBtnBucarPagoPorProveedor = function (data, event) {
      if (event) {
        if (self.IdPersona() == "" || self.IdPersona() == null) {
          alertify.alert("PAGO PROVEEDOR", "Debe seleccionar un proveedor para poder realizar la busqueda.", function () { });
          return false;
        }

        self.Filtro.IdProveedor(self.IdPersona());
        var objeto = ko.mapping.toJS(self.Filtro);
        objeto.TextoFiltro = objeto.TextoFiltro == "" ? "%" : objeto.TextoFiltro;
        $("#loader").show();
        self.ConsultarPendientesPagoProveedorPorIdProveedor(objeto, event, function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            self.ComprobantesPorPagar([]);

            var resultado = $data.filter(function (el) {
                if (self.DetallesPagoProveedor().length) {
                  var filterResult = self.DetallesPagoProveedor().filter(function (filter) {
                    return filter.IdComprobanteCompra() == el.IdComprobanteCompra;
                  });
                  return filterResult.length == 0;
                } else {
                  return el.IdComprobanteCompra;
                }
            });

            resultado.forEach(function (item) {
              var objetodetalle = Object.assign(ko.mapping.toJS(self.NuevoDetallePagoProveedor), item);
              self.ComprobantesPorPagar.push(new VistaModeloDetallePagoProveedor(objetodetalle, self));
            });
            $("#modalComprobantesPorPagar").modal("show")
            self.CalcularTotalesDetalle(data, event, self.ComprobantesPorPagar);
          } else {
            alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
          }
        });
      }
    }


    self.OnClickBtnCargarComprobantesPagados = function (data, event) {
      if (event) {
        var pagosValidas = ko.utils.arrayFilter(self.ComprobantesPorPagar(), function (item) {
          return item.Importe() > 0;
        });

        ko.utils.arrayForEach(pagosValidas, function (item) {
          var obj = ko.mapping.toJS(item);
          obj.Importe = parseFloatAvanzado(obj.Importe);
          obj.MontoPagado = parseFloatAvanzado(obj.MontoPagado);
          obj.MontoDolares = parseFloatAvanzado(obj.MontoDolares);
          obj.MontoOriginal = parseFloatAvanzado(obj.MontoOriginal);
          obj.MontoSoles = parseFloatAvanzado(obj.MontoSoles);
          // obj.NuevoSaldo = parseFloatAvanzado(obj.MontoSoles);

          self.DetallesPagoProveedor.push(new VistaModeloDetallePagoProveedor(obj, self));
        });

        self.ComprobantesPorPagar([]);
        $("#modalComprobantesPorPagar").modal("hide");
        self.CalcularTotalesDetalle(data, event, self.DetallesPagoProveedor);

      }
    }

    self.OnClickBtnRemoverDetalle = function (data, event) {
      if (event) {
        self.DetallesPagoProveedor.remove(data);
      }
    }

    self.RazonSocialProveedor = ko.computed( function () {
      var resultado ="";
      if(self.RazonSocial() == "") {
        resultado = "";
      } else if (self.NumeroDocumentoIdentidad() == "") {
        resultado = self.RazonSocial();
      } else {
        resultado = self.NumeroDocumentoIdentidad()+'  -  '+self.RazonSocial();
      }

      return resultado ;
    }, this);

    self.ObtenerNombreSerie = ko.computed( function () {
      var serie = self.IdCorrelativoDocumento();
      var nombreSerie = "";
      ko.utils.arrayForEach(self.SeriesDocumento(), function (item) {
        if (serie == item.IdCorrelativoDocumento()) {
          nombreSerie = item.SerieDocumento();
        }
      });
      self.SerieDocumento(nombreSerie);
    }, this);

    self.CajasFiltrado = ko.computed( function () {
      var idmoneda = self.IdMoneda()
      var seriesfiltrado = ko.utils.arrayFilter(self.Cajas(), function (item) {
        return item.IdMoneda() == idmoneda;
      })
      return seriesfiltrado;
    }, this)


    self.CalcularTotalesDetalle = function (data, event, detail) {
      if (event) {
        var totalSoles = 0, totalDolares = 0, totalMontoOriginal = 0, totalSaldo = 0, totalNuevoSaldo = 0;
        if (!detail) { return false }
        ko.utils.arrayForEach(detail(), function (item) {
          totalSoles += parseFloatAvanzado(item.MontoSoles());
          totalDolares += parseFloatAvanzado(item.MontoDolares());
          totalMontoOriginal += parseFloatAvanzado(item.MontoOriginal());
          totalSaldo += parseFloatAvanzado(item.SaldoPendiente());
          totalNuevoSaldo += parseFloatAvanzado(item.NuevoSaldo());
        });

        self.TotalSoles(accounting.formatNumber(totalSoles, NUMERO_DECIMALES_VENTA));
        self.TotalDolares(accounting.formatNumber(totalDolares, NUMERO_DECIMALES_VENTA));
        self.TotalMontoOriginal(accounting.formatNumber(totalMontoOriginal, NUMERO_DECIMALES_VENTA));
        self.TotalSaldo(accounting.formatNumber(totalSaldo, NUMERO_DECIMALES_VENTA));
        self.TotalNuevoSaldo(accounting.formatNumber(totalNuevoSaldo, NUMERO_DECIMALES_VENTA));
      }
    }

    // self.TotalSoles = ko.computed( function () {
    //   var total = 0;
    //   ko.utils.arrayForEach(self.DetallesPagoProveedor(), function (item) {
    //     var montoSoles = parseFloatAvanzado(item.MontoSoles());
    //     total += montoSoles;
    //   });
    //   return total.toFixed(2) ;
    // }, this);

    // self.TotalDolares = ko.computed( function () {
    //   var total = 0;
    //   ko.utils.arrayForEach(self.DetallesPagoProveedor(), function (item) {
    //     var montoDolares = parseFloatAvanzado(item.MontoDolares());
    //     total += montoDolares;
    //   });

    //   return total.toFixed(2) ;
    // }, this);

    self.CalcularTotalComprobante = function (data, event, detail) {
      if (event) {
        var total = 0;
        ko.utils.arrayForEach(self.DetallesPagoProveedor(), function (item) {
          var importe = parseFloatAvanzado(item.Importe());
          total += importe;
        });
        console.log(total);
        self.MontoComprobante(total.toFixed(2));
        self.CalcularTotalesDetalle(data, event, detail)

      }
    }

    self.ConsultarDetallesPagoProveedor = function (data, event, ver = false) {
      if (event) {
        var objeto = ko.mapping.toJS(data, mappingIgnore);
        self.ConsultarDetallesPagoPorPago(objeto, event, function ($data, $event) {
          if (!$data.error) {
            var detalle = {};
            self.DetallesPagoProveedor([]);
            $data.forEach(function (item) {

              item.MontoSoles = item.IdMoneda == ID_MONEDA_SOLES ? item.Importe : 0;
              item.MontoDolares = item.IdMoneda == ID_MONEDA_DOLARES ? item.Importe : 0;
              var objetodetalle = new VistaModeloDetallePagoProveedor(Object.assign(ko.mapping.toJS(self.NuevoDetallePagoProveedor), item), self);

              self.DetallesPagoProveedor.push(objetodetalle);
              objetodetalle.OnChangeMontoPago(objetodetalle, event)
            });
            $form.disabledElments($form, !ver ? false : true);
            if (!ver) { self.IdMoneda(parseInt(self.IdMoneda())); }
          } else {
            $("#loader").hide();
            alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
            return false;
          }
        })
      }
    }

}
