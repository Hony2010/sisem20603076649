
VistaModeloImportacionMasiva = function (data,options) {
    var self = this;
    ko.mapping.fromJS(data, MappingMasivo, self);
    self.CheckNumeroDocumento = ko.observable(true);
    self.IndicadorReseteoFormulario = true;
    self.Options = options;
    ModeloImportacionMasiva.call(this,self);
    self.Estructura = ko.observable([]);

    self.PlantillaCabecera = ko.observable();
    self.PlantillaDetalle = ko.observable();
    self.PlantillaDescarga = ko.observable();

    var $form = $(options.IDForm);

    self.InicializarVistaModelo = function (data,event) {
      if (event)  {
        self.InicializarModelo(event);
        self.OnChangeImportacion(data, event);
        self.OnRefrescar(data,event,true);
        AccesoKey.AgregarKeyOption(options.IDForm,"#btn_Grabar",TECLA_G);

      }
    }


    self.OnChangeImportacion = function(data, event)
    {
      if(event)
      {
        var opciones = ko.mapping.toJS(self.Opciones);
        var opcion = null;

        opciones.forEach(function(entry, key){
          if(self.Opcion() == entry.Opcion)
          {
            opcion = entry;
          }
        });

        if(opcion != null)
        {
          self.Estructura(opcion.Estructura);
          self.PlantillaCabecera(opcion.NombrePlantillaCabecera);
          self.PlantillaDetalle(opcion.NombrePlantillaDetalle);
          self.PlantillaDescarga(opcion.PlantillaDescarga);
        }

        var inputImage = document.getElementById("ParseExcel");
        inputImage.value = '';
        self.DetallesImportacionMasiva([])

      }
    }
    self.OnClickDescargarPlantilla = function(data,event) {
      if (event) {
        window.location = BASE_URL+self.PlantillaDescarga();
      }
    }

    self.CrearDetalleImportacionMasiva = function(data,event) {
      if(event) {
        var $input = $(event.target);
        self.RefrescarBotonesDetalleImportacionMasiva($input,event);
      }
    }

    self.OnFocus = function(data,event) {
      if(event)  {
          $(event.target).select();
      }
    }

    self.OnRefrescar = function(data,event,esporeliminacion) {
      if(event) {
        if(!$form.hasClass("selector-blocked")) {//'#formImportacionMasiva'
          if(!esporeliminacion) self.CrearDetalleImportacionMasiva(data,event);

        }
        //$form.find("#nletras").autoDenominacionMoneda(self.Total());
      }
    }

    self.Limpiar = function(data,event) {
      if(event) {
        self.Nuevo(self.ImportacionMasivaInicial,event,self.callback);
      }
    }

    self.Nuevo = function (data,event,callback) {
      if(event)  {
        $form.resetearValidaciones();//'#formImportacionMasiva'
        if (callback) self.callback = callback;
        self.NuevoImportacionMasiva(data,event);
        self.InicializarVistaModelo(undefined,event);

        setTimeout( function()  {
            $form.find('#Proveedor').focus();
          },350);
      }
    }

    self.Guardar = function(data,event) {
      if(event) {
        var filas = self.DetallesImportacionMasiva().length;
        if(filas <= 0)
        {
          alertify.alert("VALIDACION", "No se puede proceder porque no hay datos para grabar.");
          return false;
        }
        $('#loader').show();
        var objeto = ko.mapping.toJS(self);
        self.GuardarImportacionMasiva(objeto, event, self.PostGuardar);
      }
    }

    self.PostGuardar = function (data,event) {
      if(event) {
        if(data.error) {
          $("#loader").hide();
          var inputImage = document.getElementById("ParseExcel");
          inputImage.value = '';
          alertify.alert("Error en "+ self.titulo,data.error.msg,function(){
            self.DetallesImportacionMasiva([]);
          });
        }
        else {
          $("#loader").hide();
          var inputImage = document.getElementById("ParseExcel");
          inputImage.value = '';
          self.DetallesImportacionMasiva([]);
          alertify.alert("IMPORTACION MASIVA", "Se registraron correctamente los datos.", function(){
            if (self.callback) self.callback(data,event);
          });

        }
      }
    }

    self.OnAgregarFila = function(data,event) {
      if(event) {
        var item = self.DetallesImportacionMasiva.Agregar(undefined,event);
        item.InicializarVistaModelo(event);
        $(item.InputOpcion()).hide();
      }
    }

    self.OnQuitarFila = function (data,event) {
      if(event) {
          self.DetallesImportacionMasiva.Remover(data,event);
          var trfilas = $("#tablaDetalleImportacionMasiva").find("tr").find("button:visible");
          if(trfilas.length == 0) {
            setTimeout(function()  {
              $form.find("#OrdenCompra").focus();
            },250);
          }
          self.OnRefrescar(data,event,true);
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
        $(self.Options.IDModalImportacionMasiva).modal("hide");//"#modalImportacionMasiva"
        // if (self.callback) self.callback(self,event);
      }
    }

    self.Show = function(event) {
      if(event) {
        self.showImportacionMasiva(true);
      }
    }

    self.Hide = function(event) {
      if(event) {
        self.showImportacionMasiva(false);
        self.callback = undefined;
        self.OnClickBtnCerrar(self,event);
      }
    }


}
