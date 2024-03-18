
VistaModeloBoletaMasiva = function (data,options) {
    var self = this;
    ko.mapping.fromJS(data, MappingMasivo, self);
    self.CheckNumeroDocumento = ko.observable(true);
    self.IndicadorReseteoFormulario = true;
    self.Options = options;
    ModeloBoletaMasiva.call(this,self);

    var $form = $(options.IDForm);

    self.VistaOptions = function (event) {
      if (event) {
        ko.utils.arrayForEach(self.MostrarCampos(), function (item) {
          var campo = item.NombreParametroSistema();
          if (item.ValorParametroSistema() == 0) {
            $form.find("#"+campo).parent().addClass('ocultar no-tab');
            $form.find("#"+campo).addClass('no-tab');
            $form.find("#"+campo).prop("tabIndex","-1");
          }
        });
      }
    }

    self.InicializarVistaModelo = function (data,event) {
      if (event)  {
        self.InicializarModelo(event);
        // self.VistaOptions(event);

        var target = options.IDForm+" "+"#Cliente";

        if(self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
          $form.find("#Cliente").autoCompletadoCliente(event,self.ValidarAutoCompletadoCliente,CODIGO_TIPO_DOCUMENTO_IDENTIDAD.RUC,target);
        }
        else {
          $form.find("#Cliente").autoCompletadoCliente(event,self.ValidarAutoCompletadoCliente,CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS,target);
        }

        $form.find("#FechaEmision").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
        $form.find("#FechaVencimiento").inputmask({"mask": "99/99/9999",positionCaretOnTab : false});
        $form.find("#FechaNotaSalida").inputmask({"mask": "99/99/9999",positionCaretOnTab : false});
        self.InicializarValidator(event);

        $form.find("#Cliente").on("focusout",function(event){
          self.ValidarCliente(undefined,event);
        });

        self.OnRefrescar(data,event,true);
        self.CambiosFormulario(false);

        if(self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA)
        {
          self.CambiarClientesVarios(event);
        }
        AccesoKey.AgregarKeyOption(options.IDForm,"#btn_Grabar",TECLA_G);
      }
    }

    self.InicializarValidator = function(event) {
      if(event) {

        $.formUtils.addValidator({
          name : 'autocompletado_cliente',
          validatorFunction : function(value, $el, config, language, $form) {
            var texto = $el.attr("data-validation-text-found");
            var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "")  ? true : false;
            return resultado;
          },
          errorMessageKey: 'badautocompletado_cliente'
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

    self.InicializarVistaModeloDetalle = function (data,event) {
      if (event)  {
        var item;

        if (self.DetallesBoletaMasiva().length > 0)  {
          ko.utils.arrayForEach(self.DetallesBoletaMasiva(), function (el) {
              el.InicializarVistaModelo(event);
          });
        }

        var item = self.DetallesBoletaMasiva.Agregar(undefined,event);
        item.TipoVenta(self.TipoVenta());

        item.InicializarVistaModelo(event);

        $(item.InputOpcion()).hide();
        $(item.OpcionMercaderia()).hide();

      }
    }


    self.OnChangeComboAlmacen =function(data,event) {
      if(event) {
        var texto=$form.find("#combo-almacen option:selected").text();
        data.NombreSedeAlmacen(texto);
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

    self.OnChangeMoneda =function(data,event) {
      if(event) {
        var texto =$(self.Options.IDPanelHeader).find("#combo-moneda option:selected").text();
        data.NombreMoneda(texto);
      }
    }

    self.CrearDetalleBoletaMasiva = function(data,event) {
      if(event) {
        var $input = $(event.target);
        self.RefrescarBotonesDetalleBoletaMasiva($input,event);
      }
    }

    self.OnFocus = function(data,event) {
      if(event)  {
          $(event.target).select();
      }
    }

    self.OnRefrescar = function(data,event,esporeliminacion) {
      if(event) {
        if(!$form.hasClass("selector-blocked")) {//'#formBoletaMasiva'
          if(!esporeliminacion) self.CrearDetalleBoletaMasiva(data,event);
        }
        $form.find("#nletras").autoDenominacionMoneda(self.Total());
      }
    }

    self.OnClickBtnNuevoCliente = function(data,event,dataCliente)  {
      if (event) {
        dataCliente.OnNuevo(dataCliente.ClienteNuevo,event,self.PostCerrarCliente);
        dataCliente.IdTipoDocumentoIdentidad(ID_TIPO_DOCUMENTO_IDENTIDAD_RUC);
        dataCliente.Show(event);
        return true;
      }
    }


    self.PostCerrarCliente = function(dataCliente,event) {
      if(event) {
        $(self.Options.IDModalCliente).modal("hide");//"#modalCliente"
        if (dataCliente.EstaProcesado() === true) {
          $form.find("#Cliente").focus();
        }
        else {
          $form.find("#combo-formapago").focus();
        }
      }
    }

    self.Deshacer = function(data,event)  {
      if(event) {
        self.Editar(self.BoletaMasivaInicial,event,self.callback);
      }
    }

    self.Limpiar = function(data,event) {
      if(event) {
        self.Nuevo(self.BoletaMasivaInicial,event,self.callback);
        self.IdTipoVenta(self.TipoVenta());
      }
    }

    self.OnVer = function(data,event,callback) {
      if(event) {
        self.Editar(data,event,callback,true);
      }
    }

    self.Nuevo = function (data,event,callback) {
      if(event)  {
        $form.resetearValidaciones();//'#formBoletaMasiva'
        if (callback) self.callback = callback;
        self.NuevoBoletaMasiva(data,event);
        self.InicializarVistaModelo(undefined,event);

        $form.find("#Cliente").attr("data-validation-text-found","");
        $form.find("#Cliente").val("");

        self.CheckNumeroDocumento(false);

        setTimeout( function()  {
            $form.find('#combo-seriedocumento').focus();
          },350);
      }
    }


    self.Guardar = function(data,event) {
      if(event) {
        if(ViewModels.data.ComprobantesVenta().length <= 0)
        {
          alertify.alert("VALIDACION!", "Debe tener datos para continuar.", function(){
            alertify.alert().destroy();
          });
          return false;
        }

        $('#loader').show();
        var objeto = ko.mapping.toJS(ViewModels.data.ComprobantesVenta());
        self.GuardarBoletaMasiva(objeto, event, self.PostGuardar);
      }
    }

    self.PostGuardar = function (data,event) {
      if(event) {
        $("#loader").hide();
        if(data.error) {
          $("#loader").hide();
          alertify.alert("Error en registro de comprobantes", data.error.msg,function(){
          });
        }
        else {
          $("#loader").hide();
          alertify.alert(self.titulo,data, function(){
            if (self.callback) self.callback(data,event);
          });
        }
      }
    }

    self.OnChangeCheckCliente = function(data,event)  {
      if (event)  {
        self.CambiarClientesVarios(event);
        setTimeout(function(){
          $form.find("#Cliente").focus();
        }, 150);
      }
    }

    self.CambiarClientesVarios = function(event)
    {
      if(event)
      {
        if($form.find("#CheckCliente").length > 0)
        {
          if($form.find("#CheckCliente").prop("checked"))  {
            $form.find("#Cliente").attr("data-validation","autocompletado_cliente");

            $form.find("#Cliente").attr("disabled", false);
            $form.find("#Cliente").removeClass("no-tab");
            $form.find("#Cliente").val("");
            $form.find("#Cliente").attr("data-validation-optional","false");
            self.IdCliente("");
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

            $form.find("#Cliente").attr("data-validation-optional","true");
            $form.find("#Cliente").attr("disabled", true);
            $form.find("#Cliente").addClass("no-tab");
            self.IdCliente(ID_CLIENTES_VARIOS);
            setTimeout(function(){
              $form.find("#Cliente").val(TEXTO_CLIENTES_VARIOS);
            }, 100);
          }
        }
      }
    }

    self.RefrescarBotonesDetalleBoletaMasiva = function(data,event)  {
      if(event) {
        var tamaño =self.DetallesBoletaMasiva().length;
        var indice = data.closest("tr").index();
        if(indice ===  tamaño - 1) {
          var InputOpcion = self.DetallesBoletaMasiva()[indice].InputOpcion();
          $(InputOpcion).show();
          var OpcionMercaderia = self.DetallesBoletaMasiva()[indice].OpcionMercaderia();
          $(OpcionMercaderia).show();
          self.OnAgregarFila(undefined,event);
        }
      }
    }

    self.OnAgregarFila = function(data,event) {
      if(event) {
        var item = self.DetallesBoletaMasiva.Agregar(undefined,event);
        item.TipoVenta(self.TipoVenta());
        item.InicializarVistaModelo(event);
        $(item.InputOpcion()).hide();
        $(item.OpcionMercaderia()).hide();
      }
    }

    self.OnQuitarFila = function (data,event) {
      if(event) {
          self.DetallesBoletaMasiva.Remover(data,event);
          var trfilas = $("#tablaDetalleBoletaMasiva").find("tr").find("button:visible");
          if(trfilas.length == 0) {
            setTimeout(function()  {
              $form.find("#OrdenCompra").focus();
            },250);
          }
          self.OnRefrescar(data,event,true);
      }
    }

    self.ValidarNumeroDocumento = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
        data.NumeroDocumento($(event.target).zFill(data.NumeroDocumento(),8));
      }
    }

    self.ValidarCliente = function(data,event)  {
      if(event) {
        $(event.target).validate(function(valid, elem) {
            if(!valid) {
              self.IdCliente(null);
              self.Direccion("");
            }
        });
      }
    }

    self.ValidarAutoCompletadoCliente = function(data,event) {
        if(event) {
          $form.find("#Cliente").attr("data-validation-error-msg","No se han encontrado resultados para tu búsqueda de cliente");

          if(data === -1 ) {
            if($form.find("#Cliente").attr("data-validation-text-found") === $form.find("#Cliente").val() ) {
              var $evento = { target : self.Options.IDForm + " "+"#Cliente" };
              self.ValidarCliente(data,$evento);
            }
            else {
              $form.find("#Cliente").attr("data-validation-text-found","");
              var $evento = { target : self.Options.IDForm + " "+"#Cliente" };
              self.ValidarCliente(data,$evento);
            }

            $form.find("#combo-formapago").focus();
          }
          else {
            if($form.find("#Cliente").attr("data-validation-text-found") !== $form.find("#Cliente").val() ) {
              $form.find("#Cliente").attr("data-validation-text-found",data.NumeroDocumentoIdentidad +"  -  "+ data.RazonSocial);
            }

            var $evento = { target : self.Options.IDForm + " "+"#Cliente"};
            self.ValidarCliente(data,$evento);
            data.IdCliente = data.IdPersona;
            ko.mapping.fromJS(data,MappingMasivo,self);
            $("#combo-formapago").focus();

          }
        }
    }

    self.OnKeyEnter = function(data,event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }

    self.AplicarExcepcionValidaciones = function(data,event) {
      if(event) {
          //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
          var total = self.DetallesBoletaMasiva().length;
          var ultimoItem = self.DetallesBoletaMasiva()[total-1];
          var resultado = "false";
          if (ultimoItem.CodigoMercaderia() === "" && ultimoItem.NombreProducto() === ""
            && (ultimoItem.Cantidad() === "" || ultimoItem.Cantidad() === "0")
            && (ultimoItem.PrecioUnitario() === "" || ultimoItem.PrecioUnitario() === "0")
            && (ultimoItem.DescuentoItem() === "" || ultimoItem.DescuentoItem() === "0")
          ) {
             resultado="true";
          }

          $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputProducto()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputCantidad()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputPrecioUnitario()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputDescuentoItem()).attr("data-validation-optional",resultado);
      }
    }

    self.Cerrar = function(data,event) {
      if(event) {

      }
    }

    self.OnClickBtnCerrar = function(data,event) {
      if(event) {
        $(self.Options.IDModalBoletaMasiva).modal("hide");//"#modalBoletaMasiva"
        if (self.callback) self.callback(self,event);
      }
    }

    self.Show = function(event) {
      if(event) {
        self.showBoletaMasiva(true);
      }
    }

    self.Hide = function(event) {
      if(event) {
        self.showBoletaMasiva(false);
        self.callback = undefined;
        self.OnClickBtnCerrar(self,event);
      }
    }

    self.OnChangeFacturaVenta = function(event) {
      if(event) {
        self.CambiosFormulario(true);
      }
    }
}
