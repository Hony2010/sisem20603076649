
VistaModeloCompraMasiva = function (data,options) {
    var self = this;
    ko.mapping.fromJS(data, MappingMasivo, self);
    self.CheckNumeroDocumento = ko.observable(true);
    self.IndicadorReseteoFormulario = true;
    self.Options = options;
    ModeloCompraMasiva.call(this,self);

    var $form = $(options.IDForm);

    self.InicializarVistaModelo = function (data,event) {
      if (event)  {
        self.InicializarModelo(event);

        var target = options.IDForm+" "+"#Proveedor";
        $form.find("#Proveedor").autoCompletadoProveedor(event,self.ValidarAutoCompletadoProveedor,target);
        $form.find("#FechaEmision").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
        $form.find("#FechaVencimiento").inputmask({"mask": "99/99/9999",positionCaretOnTab : false});
        $form.find("#FechaVencimiento").inputmask({"mask": "99/99/9999",positionCaretOnTab : false});
        $form.find("#FechaDetraccion").inputmask({"mask": "99/99/9999",positionCaretOnTab : false});

        self.InicializarValidator(event);

        $form.find("#Proveedor").on("focusout",function(event){
          self.ValidarProveedor(undefined,event);
        });

        $("body")
          //.off("keydown")
          .on("keydown",function(event){
          return true;
        });

        self.OnRefrescar(data,event,true);
        AccesoKey.AgregarKeyOption(options.IDForm,"#btn_Grabar",TECLA_G);

      }
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
          name : 'fecha_vencimiento',
          validatorFunction : function(value, $el, config, language, $form) {
              if(value !=="")  {
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

    self.OnChangeFormaPago =function(data,event) {
      if(event) {
        var texto=$form.find("#combo-formapago option:selected").text();
        data.NombreFormaPago(texto);
      }
    }

    self.OnChangeSerieDocumento = function(data,event) {
      if(event) {
        var texto=$form.find("#combo-seriedocumento option:selected").text();
        data.SerieDocumento(texto);
      }
    }

    self.OnChangeTipoDocumento = function(data,event) {
      if(event) {
        var texto=$form.find("#combo-tipodocumento option:selected").text();
        //data.TipoDocumento(texto);
      }
    }

    self.OnChangePeriodo = function(data,event) {
      if(event) {
        var texto=$form.find("#combo-periodo option:selected").text();
        //data.NombrePeriodo(texto);
      }
    }

    self.OnChangeMoneda =function(data,event) {
      if(event) {
        var texto =$form.find("#combo-moneda option:selected").text();
        data.NombreMoneda(texto);
      }
    }

    self.CrearDetalleCompraMasiva = function(data,event) {
      if(event) {
        var $input = $(event.target);
        self.RefrescarBotonesDetalleCompraMasiva($input,event);
      }
    }

    self.OnFocus = function(data,event) {
      if(event)  {
          $(event.target).select();
      }
    }

    self.OnRefrescar = function(data,event,esporeliminacion) {
      if(event) {
        if(!$form.hasClass("selector-blocked")) {//'#formCompraMasiva'
          if(!esporeliminacion) self.CrearDetalleCompraMasiva(data,event);

        }
        //$form.find("#nletras").autoDenominacionMoneda(self.Total());
      }
    }

    self.OnClickBtnNuevoProveedor = function(data,event,dataProveedor)  {
      if (event) {
        dataProveedor.OnNuevo(dataProveedor.ProveedorNuevo,event,self.PostCerrarProveedor);
        dataProveedor.Show(event);
        return true;
      }
    }

    self.PostCerrarProveedor = function(dataProveedor,event) {
      if(event) {
        $(self.Options.IDModalProveedor).modal("hide");//"#modalProveedor"
        if (dataProveedor.EstaProcesado() === true) {
          $form.find("#Proveedor").focus();
        }
        else {
          $form.find("#FechaEmision").focus();
        }
      }
    }

    self.Deshacer = function(data,event)  {
      if(event) {
        self.Editar(self.CompraMasivaInicial,event,self.callback);
      }
    }

    self.Limpiar = function(data,event) {
      if(event) {
        self.Nuevo(self.CompraMasivaInicial,event,self.callback);
      }
    }

    self.OnVer = function(data,event,callback) {
      if(event) {
        self.Editar(data,event,callback,true);
      }
    }

    self.Nuevo = function (data,event,callback) {
      if(event)  {
        $form.resetearValidaciones();//'#formCompraMasiva'
        if (callback) self.callback = callback;
        self.NuevoCompraMasiva(data,event);
        self.InicializarVistaModelo(undefined,event);

        setTimeout( function()  {
            $form.find('#Proveedor').focus();
          },350);
      }
    }

    self.Guardar = function(data,event) {
      if(event) {
        $('#loader').show();
        var objeto = ko.mapping.toJS(ViewModels.data.ComprobantesCompra());
        self.GuardarCompraMasiva(objeto, event, self.PostGuardar);
      }
    }

    self.PostGuardar = function (data,event) {
      if(event) {
        if(data.error) {
          $("#loader").hide();
          alertify.alert("Error en "+ self.titulo,data.error.msg,function(){
          });
        }
        else {
          $("#loader").hide();
          alertify.alert(self.titulo, self.mensaje, function(){
            if (self.callback) self.callback(data,event);
          });

        }
      }
    }

    self.RefrescarBotonesDetalleCompraMasiva = function(data,event)  {
      if(event) {
        var tamaño =self.DetallesCompraMasiva().length;
        var indice = data.closest("tr").index();
        if(indice ===  tamaño - 1) {
          var InputOpcion = self.DetallesCompraMasiva()[indice].InputOpcion();
          $(InputOpcion).show();
          self.OnAgregarFila(undefined,event);
        }
      }
    }

    self.OnAgregarFila = function(data,event) {
      if(event) {
        var item = self.DetallesCompraMasiva.Agregar(undefined,event);
        item.InicializarVistaModelo(event);
        $(item.InputOpcion()).hide();
      }
    }

    self.OnQuitarFila = function (data,event) {
      if(event) {
          self.DetallesCompraMasiva.Remover(data,event);
          var trfilas = $("#tablaDetalleCompraMasiva").find("tr").find("button:visible");
          if(trfilas.length == 0) {
            setTimeout(function()  {
              $form.find("#OrdenCompra").focus();
            },250);
          }
          self.OnRefrescar(data,event,true);
      }
    }

    self.ValidarSerieDocumento = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
        data.SerieDocumento($(event.target).zFill(data.SerieDocumento(),4));
      }
    }

    self.ValidarNumeroDocumento = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
        data.NumeroDocumento($(event.target).zFill(data.NumeroDocumento(),8));

      }
    }

    self.ValidarDescuentoGlobal  = function(data,event) {

    }

    self.ValidarFechaEmision = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
           if(valid) self.ValorTipoCambio(self.CalcularTipoCambio(data,event));
        });
      }
    }

    self.ValidarFechaNotaSalida = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {

        });
      }
    }

    self.CalcularTipoCambio = function (data,event) {
      if(event) {
        var resultado = 0.00;
        if (self.IdMoneda() != ID_MONEDA_SOLES)
          if (data.ValorTipoCambio() != "0.00" && data.ValorTipoCambio() != 0 && jQuery.isNumeric(data.ValorTipoCambio())) {
            resultado = data.ValorTipoCambio();
          }
          else {
            self.TipoCambio.ObtenerTipoCambio(data,function($data)  {
              if($data){
                resultado = data.TipoCambioVenta;
              }
              else {
                alertify.alert(self.titulo,"No se encontro un tipo de cambio para la fecha emision");
              }
            });
          }
        return resultado;
      }
    }

    self.ValidarProveedor = function(data,event)  {
      if(event) {
        $(event.target).validate(function(valid, elem) {
            if(!valid) {
              self.IdProveedor(null);
              self.Direccion("");
            }
        });
      }
    }

    self.ValidarAutoCompletadoProveedor = function(data,event) {
        if(event) {

          if(data === -1 ) {
            if($form.find("#Proveedor").attr("data-validation-text-found") === $form.find("#Proveedor").val() ) {
              var $evento = { target : self.Options.IDForm + " "+"#Proveedor" };
              self.ValidarProveedor(data,$evento);
            }
            else {
              $form.find("#Proveedor").attr("data-validation-text-found","");
              var $evento = { target : self.Options.IDForm + " "+"#Proveedor" };
              self.ValidarProveedor(data,$evento);
            }

            $form.find("#FechaEmision").focus();
          }
          else {
            if($form.find("#Proveedor").attr("data-validation-text-found") !== $form.find("#Proveedor").val() ) {
              $form.find("#Proveedor").attr("data-validation-text-found",data.NumeroDocumentoIdentidad +"  -  "+ data.RazonSocial);
            }

            var $evento = { target : self.Options.IDForm + " "+"#Proveedor"};
            self.ValidarProveedor(data,$evento);
            //var $data = { IdPersona : }
            data.IdProveedor = data.IdPersona;
            ko.mapping.fromJS(data,MappingMasivo,self);
            $form.find("#combo-almacen").focus();

          }

          return false;
        }
    }

    self.ValidarFechaVencimiento = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.OnKeyEnter = function(data,event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }

    self.Cerrar = function(data,event) {
      if(event) {

      }
    }

    self.OnClickBtnCerrar = function(data,event) {
      if(event) {
        $(self.Options.IDModalCompraMasiva).modal("hide");//"#modalCompraMasiva"
        // if (self.callback) self.callback(self,event);
      }
    }

    self.Show = function(event) {
      if(event) {
        self.showCompraMasiva(true);
      }
    }

    self.Hide = function(event) {
      if(event) {
        self.showCompraMasiva(false);
        self.callback = undefined;
        self.OnClickBtnCerrar(self,event);
      }
    }

    self.OnChangeComboAlmacen =function(data,event) {
      if(event) {
        var texto=$form.find("#combo-almacen option:selected").text();
        data.NombreSedeAlmacen(texto);
      }
    }

    self.ValidarFechaNotaEntrada = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {

        });
      }
    }


}
