VistaModeloCobranzaCliente = function (data,options) {
    var self = this;
    self.Options = options;
    ko.mapping.fromJS(data, MappingCuentaCobranza, self);
    self.IndicadorReseteoFormulario = true;
    self.TotalSoles = ko.observable("0.00");
    self.TotalDolares = ko.observable("0.00");
    self.TotalMontoOriginal = ko.observable("0.00");
    self.TotalNuevoSaldo = ko.observable("0.00");
    self.TotalSaldo = ko.observable("0.00");
    ModeloCobranzaCliente.call(this,self);

    var $form = $(self.Options.IDForm);
    var idform = self.Options.IDForm;
    self.InicializarVistaModelo =function(data,event) {
      if(event) {
        self.InicializarModelo();
        self.InicializarValidator(event);
        $(".fecha").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});

        var target = idform + " #RazonSocialCliente";
        $(target).autoCompletadoCliente(event,self.ValidarAutoCompletadoCliente,CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS,target);

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
          validatorFunction: function(value, $el) {
            return (value == "" || value == null || value == 0 ? false : true)
          },
          errorMessage: "",
          errorMessageKey: "Selecciona un Item"
        });

        $.formUtils.addValidator({
          name : 'autocompletado_cliente',
          validatorFunction : function(value, $el, config, language, $form) {
            var texto = $el.attr("data-validation-text-found");
            var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "")  ? true : false;
            return resultado;
          },
          errorMessageKey: 'badautocompletado_cliente'
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
        self.NuevoCobranzaCliente(data,event);
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
        self.ConsultarDetallesCobranzaCliente(data, event, ver);
        self.EditarCobranzaCliente(data,event);
        self.InicializarVistaModelo(data,event);
        $form.find("#RazonSocialCliente").attr("data-validation-text-found",self.RazonSocialCliente());
      }
    }

    self.OnEliminar = function(data,event,callback) {
      if(event) {
          self.EliminarCobranzaCliente(data,event,function($data,$event) {
            callback($data,$event);
          });
      }
    }

    self.OnAnular = function(data,event,callback) {
      if(event) {
          self.AnularCobranzaCliente(data,event,function($data,$event) {
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
          if (self.DetallesCobranzaCliente().length <= 0) {
            alertify.alert(self.titulo, "No hay ninguna cobranza para el cliente seleccionado.",function () { });
            return false;
          }
          if (self.MontoComprobante() <= 0) {
            alertify.alert(self.titulo, "El monto del comprobante debe ser mayor a 0.",function () { });
            return false;
          }
          $("#loader").show();
          self.GuardarCobranzaCliente(event,function($data,$event){
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
        self.OnEditar(self.CobranzaClienteInicial,event,self.callback);
      }
    }

    self.OnClickBtnLimpiar = function (data,event) {
      if(event) {
        $("#RazonSocialCliente").val("");
        self.OnNuevo(self.CobranzaClienteInicial,event,self.callback);
      }
    }

    self.OnClickBtnCerrar = function(data,event)  {
      if(event) {
        $("#modalCobranzaCliente").modal("hide");
        if (self.callback) self.callback(self,event);
      }
    }

    self.Show = function(event) {
      if(event) {
        self.showCobranzaCliente(true);
      }
    }

    self.Hide = function(event) {
      if(event) {
        self.showCobranzaCliente(false);//$("#modalCobranzaCliente").modal("hide");
        self.EstaProcesado(false);
        self.OnClickBtnCerrar(self,event);
      }
    }

    self.MostrarTitulo = ko.computed( function () {
      if (self.opcionProceso() == opcionProceso.Nuevo)  {
        self.titulo = "REGISTRO DE COBRANZA CLIENTE";
      }
      else {
        self.titulo = "EDICIÓN DE COBRANZA CLIENTE";
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

    self.ValidarCliente = function(data,event)  {
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
        self.ConsultarCobranzaCliente(data, event, function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            self.MontoComprobante($data.MontoComprobante);
          } else {
            alertify.alert(self.titulo, $data.error.msg, function() {
              self.MontoComprobante("");
            })
          }
        })
      }
    }

    self.ValidarAutoCompletadoCliente = function(data, event) {
        if (event) {
          var $inputCliente = $form.find("#RazonSocialCliente");
          $inputCliente[0].value = $inputCliente.val().trim();
          if($inputCliente.val().length != 11){
            $inputCliente.attr("data-validation-error-msg","Ingrese el numero de documento correcto");
          } else {
            $inputCliente.attr("data-validation-error-msg","No se han encontrado resultados para tu búsqueda de cliente");
          }

          if(data === -1 ) {
            if($inputCliente.attr("data-validation-text-found") === $inputCliente.val() ) {
              var $evento = { target : idform + " "+"#RazonSocialCliente" };
              self.ValidarCliente(data,$evento);
            } else {
              $inputCliente.attr("data-validation-text-found","");
              var $evento = { target : idform + " "+"#RazonSocialCliente" };
              self.ValidarCliente(data,$evento);
            }
          } else {
            if(($inputCliente.attr("data-validation-text-found") !== $inputCliente.val()) || ($inputCliente.val() == "")) {
              if (data.NumeroDocumentoIdentidad  == "") {
                $inputCliente.attr("data-validation-text-found",data.RazonSocial);
              }else {
                $inputCliente.attr("data-validation-text-found",data.NumeroDocumentoIdentidad +"  -  "+ data.RazonSocial);
              }
            }

            var $evento = { target : idform + " "+"#RazonSocialCliente"};
            ko.mapping.fromJS(data,MappingCuentaCobranza,self);
            self.ValidarCliente(data,$evento);
            setTimeout(function () { $("#BtnBuscarCobranza").focus() }, 200);
          }
        }
    }

    self.OnClickBtnBucarCobranzaPorCliente = function (data, event) {
      if (event) {
        if (self.IdPersona() == "" || self.IdPersona() == null) {
          alertify.alert("COBRANZA CLIENTE", "Debe seleccionar un cliente para poder realizar la busqueda.", function () { });
          return false;
        }

        self.Filtro.IdCliente(self.IdPersona());
        var objeto = ko.mapping.toJS(self.Filtro);
        objeto.TextoFiltro = objeto.TextoFiltro == "" ? "%" : objeto.TextoFiltro;
        $("#loader").show();
        self.ConsultarPendientesCobranzaClientePorIdCliente(objeto, event, function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            self.ComprobantesPorCobrar([]);

            var resultado = $data.filter(function (el) {
                if (self.DetallesCobranzaCliente().length) {
                  var filterResult = self.DetallesCobranzaCliente().filter(function (filter) {
                    return filter.IdPendienteCobranzaCliente() == el.IdPendienteCobranzaCliente;
                  });
                  return filterResult.length == 0;
                } else {
                  return el.IdPendienteCobranzaCliente;
                }
            });

            resultado.forEach(function (item) {
              var objetodetalle = Object.assign(ko.mapping.toJS(self.NuevoDetalleCobranzaCliente), item);
              self.ComprobantesPorCobrar.push(new VistaModeloDetalleCobranzaCliente(objetodetalle, self));
            });
            $("#modalComprobantesPorCobrar").modal("show");
            self.CalcularTotalesDetalle(data, event, self.ComprobantesPorCobrar);

          } else {
            alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
          }
        });
      }
    }


    self.OnClickBtnCargarComprobantesCobrados = function (data, event) {
      if (event) {
        var cobranzasValidas = ko.utils.arrayFilter(self.ComprobantesPorCobrar(), function (item) {
          return parseFloatAvanzado(item.Importe()) > 0;
        });

        ko.utils.arrayForEach(cobranzasValidas, function (item) {
          var obj = ko.mapping.toJS(item);
          obj.Importe = parseFloatAvanzado(obj.Importe);
          obj.MontoCobrado = parseFloatAvanzado(obj.MontoCobrado);
          obj.MontoDolares = parseFloatAvanzado(obj.MontoDolares);
          obj.MontoOriginal = parseFloatAvanzado(obj.MontoOriginal);
          obj.MontoSoles = parseFloatAvanzado(obj.MontoSoles);
          // obj.NuevoSaldo = parseFloatAvanzado(obj.MontoSoles);

          self.DetallesCobranzaCliente.push(new VistaModeloDetalleCobranzaCliente(obj, self));
        });

        self.ComprobantesPorCobrar([]);
        $("#modalComprobantesPorCobrar").modal("hide");

        self.CalcularTotalesDetalle(data, event, self.DetallesCobranzaCliente);

      }
    }

    self.OnClickBtnRemoverDetalle = function (data, event) {
      if (event) {
        self.DetallesCobranzaCliente.remove(data);
        self.CalcularTotalesDetalle(data, event, self.DetallesCobranzaCliente);
      }
    }

    self.RazonSocialCliente = ko.computed( function () {
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
    //   ko.utils.arrayForEach(self.DetallesCobranzaCliente(), function (item) {
    //     total += parseFloatAvanzado(item.MontoSoles());;
    //   });
    //   return total.toFixed(2);
    // }, this);

    // self.TotalDolares = ko.computed( function () {
    //   var total = 0;
    //   ko.utils.arrayForEach(self.DetallesCobranzaCliente(), function (item) {
    //     total += parseFloatAvanzado(item.MontoDolares());
    //   });
    //   return total.toFixed(2);
    // }, this);



    self.ObtenerDataDetalle = function (event) {
      if (event) {
        var array = [];
        var comprobantesPorCobrar = self.ComprobantesPorCobrar();
        var detallesCobranzaCliente = self.DetallesCobranzaCliente();

        if (detallesCobranzaCliente.length == 0) {
          array = comprobantesPorCobrar;
        }
        if (comprobantesPorCobrar.length == 0) {
          array = detallesCobranzaCliente;
        }
        return array;
      }
    }

    self.CalcularTotalComprobante = function (data, event, detail) {
      if (event) {
        var total = 0;
        ko.utils.arrayForEach(self.DetallesCobranzaCliente(), function (item) {
          var importe = parseFloatAvanzado(item.Importe());
          total += importe;
        });
        console.log(total);
        self.MontoComprobante(total.toFixed(2));
        self.CalcularTotalesDetalle(data, event, detail)
      }
    }

    self.ConsultarDetallesCobranzaCliente = function (data, event, ver = false) {
      if (event) {
        var objeto = ko.mapping.toJS(data, mappingIgnore);
        self.ConsultarDetallesCobranzaPorCobranza(objeto, event, function ($data, $event) {
          if (!$data.error) {
            var detalle = {};
            self.DetallesCobranzaCliente([]);
            $data.forEach(function (item) {

              item.MontoSoles = item.IdMoneda == ID_MONEDA_SOLES ? item.Importe : 0;
              item.MontoDolares = item.IdMoneda == ID_MONEDA_DOLARES ? item.Importe : 0;
              var objetodetalle = new VistaModeloDetalleCobranzaCliente(Object.assign(ko.mapping.toJS(self.NuevoDetalleCobranzaCliente), item), self);

              self.DetallesCobranzaCliente.push(objetodetalle);
              objetodetalle.OnChangeMontoCobranza(objetodetalle, event)
            });
            $form.disabledElments($form, !ver ? false : true);
            if (!ver) { self.IdMoneda(parseInt(self.IdMoneda())); }
            self.CalcularTotalComprobante(data, event, self.DetallesCobranzaCliente);

          } else {
            $("#loader").hide();
            alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
            return false;
          }
        })
      }
    }

}
