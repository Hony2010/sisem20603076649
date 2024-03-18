
VistaModeloInventarioInicial = function (data) {
    var self = this;
    ko.mapping.fromJS(data, MappingInventario, self);
    self.CheckNumeroInventarioInicial = ko.observable(true);
    self.IndicadorReseteoFormulario = true;

    ModeloInventarioInicial.call(this,self);

    self.InicializarVistaModelo = function (data,event) {
      if (event)  {
        self.InicializarModelo(event);
        var copiaSede = Knockout.CopiarObjeto(self.CopiaSedes());
        self.Sedes(copiaSede());
        AccesoKey.AgregarKeyOption("#formInventarioInicial", "#btn_Grabar", 71);
        // $("#nletras").autoDenominacionMoneda(self.Total());
        $("#FechaInventario").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
        $("#FechaVencimiento").inputmask({"mask": "99/99/9999",positionCaretOnTab : false});

        self.InicializarValidator(event);
        //self.OnChangeAlmacen(data, event);
      }
    }

    self.InicializarValidator = function(event) {
      if(event) {

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
        self.DetallesInventarioInicial([]);
        if (self.DetallesInventarioInicial().length > 0)  {
          ko.utils.arrayForEach(self.DetallesInventarioInicial(), function (el) {
              el.InicializarVistaModelo(event,self.PostBusquedaProducto,self.CrearInventarioInicial);//if (indice == 0) item = Knockout.CopiarObjeto(el);
          });
        }

        var item = self.DetallesInventarioInicial.Agregar(undefined,event);
        item.InicializarVistaModelo(event,self.PostBusquedaProducto,self.CrearInventarioInicial);
        $(item.InputOpcion()).hide();

        //self.Seleccionar(item,event);
      }
    }

    self.OnChangeFormaPago =function(data,event) {
      if(event) {
        var texto=$("#combo-formapago option:selected").text();
        data.NombreFormaPago(texto);
      }
    }

    self.OnChangeAlmacen =function(data,event) {
      if(event) {
        var texto=$("#combo-almacen option:selected").text();
        self.NombreAlmacen(texto);
        
        ko.utils.arrayForEach(data.Sedes(), function (item) {
          if (item.NombreSede() == texto) {
            data.IdSede(item.IdSede());
          }          
        });

        if(self.ParametroDocumentoSalidaZofra() == 1)
        {
          var dataAlmacen = ko.mapping.toJS(self.Sedes);
          busqueda = JSPath.apply('.{.IdAsignacionSede == $Texto}', dataAlmacen, {Texto: self.IdAsignacionSede()});
          if(busqueda.length > 0)
          {
            if(busqueda[0].IndicadorAlmacenZofra == 1)
            {
              $("#OrigenMercaderiaZofra").attr('disabled', false);
              $("#OrigenMercaderiaGeneral").attr('disabled', true);
              $("#OrigenMercaderiaDua").attr('disabled', true);
              self.IdOrigenMercaderia(ORIGEN_MERCADERIA.ZOFRA);
            }
            else {
              $("#OrigenMercaderiaZofra").attr('disabled', true);
              $("#OrigenMercaderiaGeneral").attr('disabled', false);
              $("#OrigenMercaderiaDua").attr('disabled', false);
              self.IdOrigenMercaderia(ORIGEN_MERCADERIA.GENERAL);
            }
          }
          self.OnChangeIdOrigenMercaderia(data, event);
        }

      }
    }

    self.OnChangeSerieInventarioInicial = function(data,event) {
      if(event) {
        var texto=$("#combo-seriedocumento option:selected").text();
        data.SerieInventarioInicial(texto);
      }
    }

    // self.OnChangeAlmacen = function(data,event) {
    //   if(event) {
    //     var texto=$("#combo-sede option:selected").text();
    //     data.NombreAlmacen(texto);
    //   }
    // }

    self.OnChangeMoneda =function(data,event) {
      if(event) {
        var texto =$("#combo-moneda option:selected").text();
        data.NombreMoneda(texto);
      }
    }

    self.OnChangeIdOrigenMercaderia = function(data,event) {
        if(event) {
          self.DetallesInventarioInicial([]);
          var item = self.DetallesInventarioInicial.Agregar(undefined,event);
          item.InicializarVistaModelo(event,self.PostBusquedaProducto,self.CrearInventarioInicial);
          $(item.InputOpcion()).hide();

          // data_mercaderia = self.DataMercaderiaPorOrigenMercaderia(event);
        }
    }

    self.PostBusquedaProducto = function (data,event,$callback) {
      if (event)  {
        if(data != null)  {
          setTimeout( function()  {
              self.Seleccionar(data,event);
          },250);
        }

        if($callback) $callback(data,event);
      }
    }

    self.CrearInventarioInicial = function(data,event) {
      if(event) {
        var $input = $(event.target);
        self.RefrescarBotonesDetalleInventarioInicial($input,event);
      }
    }

    self.Seleccionar = function (data,event) {
      if(event) {
        var id = "#"+ data.IdInventarioInicial();
        $(id).addClass('active').siblings().removeClass('active');
        self.SeleccionarDetalleInventarioInicial(data,event);
        self.DetallesInventarioInicial.Actualizar(undefined,event);
        // $("#nletras").autoDenominacionMoneda(self.Total());
      }
    }

    self.Deshacer = function(data,event)  {
      if(event) {
        self.Editar(self.InventarioInicialInicial,event,self.callback);
      }
    }

    self.Limpiar = function(data,event) {
      if(event) {
        $("#ParseExcel").val("");
        self.Nuevo(self.InventarioInicialInicial,event,self.callback);
      }
    }

    self.Nuevo = function (data,event,callback) {
      if(event)  {
         $('#formInventarioInicial').resetearValidaciones();
        if (callback) self.callback = callback;
        self.NuevoInventarioInicial(data,event);
        self.InicializarVistaModelo(undefined,event);
        self.InicializarVistaModeloDetalle(undefined,event);

        setTimeout( function()  {
            $('#combo-almacen').focus();
          },350);
      }
    }

    self.Editar = function(data,event,callback) {
      if(event) {
        if (self.IndicadorReseteoFormulario === true)  $('#formInventarioInicial').resetearValidaciones();
        if (callback) self.callback = callback;
        self.EditarInventarioInicial(data,event);
        self.InicializarVistaModelo(undefined,event);
        self.ConsultarDetallesInventarioInicial(data,event, function ($data,$event) {
            self.InicializarVistaModeloDetalle(undefined,event);
            setTimeout( function()  {
                $('#combo-seriedocumento').focus();
              },350);
        });
      }
    }

    self.Guardar = function(data,event) {
      if(event) {

        self.AplicarExcepcionValidaciones(data,event);

        var validar_duplicado = self.ValidarDuplicados(data, event);
        if(validar_duplicado == false)
        {
          alertify.alert("Por favor hay codigos duplicados en sus nuevos registro. Verifique y modifique.");
          return false;
        }

        if($("#formInventarioInicial").isValid() === false) {
          alertify.alert("Error en Validación","Existe aun datos inválidos , por favor de corregirlo.");
        }
        else {
          var filtrado = ko.utils.arrayFilter(self.DetallesInventarioInicial(), function(item){
            return item.IdProducto() != null;
          });
          if(filtrado.length <= 0)
          {
            alertify.alert("VALIDACIÓN!", "Debe existir un registro completo para proceder.");
            return false;
          }

          alertify.confirm(self.titulo,"¿Desea guardar los cambios?",function() {
            $("#loader").show();
            self.GuardarInventarioInicial(event,self.PostGuardar);
          },function(){

          });
        }
      }
    }

    self.PostGuardar = function (data,event) {
      if(event) {
        if(data.error) {
          $("#loader").hide();
          alertify.alert("Error en "+ self.titulo,data.error.msg,function(){
            alertify.alert().destroy();
          });
        }
        else {
          $("#loader").hide();
          alertify.alert("Inventario Inicial","Se Guardaron Correctamente los Datos.",function() {
            data_mercaderia = ObtenerJSONCodificadoDesdeURL(url_json);
            if (self.callback) self.callback(data,event);

            // data_mercaderia = self.DataMercaderiaPorOrigenMercaderia(event);
            alertify.alert().destroy();
          });

        }
      }
    }

    self.DataMercaderiaPorOrigenMercaderia = function(event)
    {
      if(event)
      {
        dataJSONMercaderia = ObtenerJSONCodificadoDesdeURL(url_json);
        filtradoMercaderia = dataJSONMercaderia;
        if (self.IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA) {
          filtradoMercaderia = JSPath.apply('.{.IdOrigenMercaderia == $Tipo}', dataJSONMercaderia, {Tipo: ORIGEN_MERCADERIA.ZOFRA});
        }
        else if (self.IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA){
          filtradoMercaderia = JSPath.apply('.{.IdOrigenMercaderia == $Tipo}', dataJSONMercaderia, {Tipo: ORIGEN_MERCADERIA.DUA});
        }
        else {
          filtradoMercaderia = JSPath.apply('.{.IdOrigenMercaderia == $Tipo}', dataJSONMercaderia, {Tipo: ORIGEN_MERCADERIA.GENERAL});
        }

        return filtradoMercaderia;
      }
    }

    self.Anular = function(data,event,callback) {
      if(event) {
        //$("#loader").show();
        if (callback != undefined) self.callback = callback;
        self.AnularInventarioInicial(data,event,self.PostAnular);
      }
    }

    self.Eliminar = function(data,event,callback) {
      if(event) {
        if (callback != undefined) self.callback = callback;
        self.EliminarInventarioInicial(data,event,self.PostEliminar);
      }
    }

    self.PostAnular = function(data,event) {
      if(event) {
        var titulo ="Anulación de Inventario Inicial";
        var mensaje = "Se anuló correctamente!";

        $("#loader").hide();

        alertify.alert(titulo,mensaje, function(){
          if (self.callback != undefined) self.callback(data,event);
         });
      }
    }

    self.PostEliminar = function(data,event) {
      if(event) {
        var resultado =  data;

        if(resultado.error === "")
        {
          alert("Se eliminó correctamente!");

          if (self.callback != undefined)
            self.callback(resultado.data,event);
        }
        else {
          alert(resultado.error);
        }
      }
    }

    self.TieneAccesoEditar =  ko.observable(self.ValidarEstadoInventarioInicial(self,window));

    self.TieneAccesoAnular =  ko.observable(self.ValidarEstadoInventarioInicial(self,window));


    self.OnChangeCheckNumeroInventarioInicial = function(data,event)  {
      if (event)  {
        if($("#CheckNumeroInventarioInicial").prop("checked"))  {
          $("#NumeroInventarioInicial").attr("readonly", false);
          $("#NumeroInventarioInicial").removeClass("no-tab");
          $("#NumeroInventarioInicial").attr("data-validation-optional","false");
          $("#NumeroInventarioInicial").focus();
        }
        else {
          self.NumeroInventarioInicial("");
          $("#NumeroInventarioInicial").attr("data-validation-optional","true");
          $("#NumeroInventarioInicial").attr("readonly", true);
          $("#NumeroInventarioInicial").addClass("no-tab");
          $("#NumeroInventarioInicial").focus();
          $("#CheckNumeroInventarioInicial").focus();
        }
      }
    }

    self.RefrescarBotonesDetalleInventarioInicial = function(data,event)  {
      if(event) {
        var tamaño =self.DetallesInventarioInicial().length;
        var indice = data.closest("tr").index();
        if(indice ===  tamaño - 1) {
          self.RemoverExcepcionValidaciones(data, event);
          var InputOpcion = self.DetallesInventarioInicial()[indice].InputOpcion();
          $(InputOpcion).show();
          self.AgregarDetalleInventarioInicial(undefined,event);
        }
      }
    }

    self.RemoverExcepcionValidaciones = function(data,event) {
      if(event) {
          //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
          var total = self.DetallesInventarioInicial().length;
          var ultimoItem = self.DetallesInventarioInicial()[total-1];
          var resultado = "false";

          $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputProducto()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputCantidadInicial()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputValorUnitario()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputFechaVencimiento()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputNumeroLote()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputNumeroDocumentoSalidaZofra()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputNumeroDua()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputNumeroItemDua()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputFechaEmisionDocumentoSalidaZofra()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputFechaEmisionDua()).attr("data-validation-optional",resultado);
      }
    }

    self.AgregarDetalleInventarioInicial = function(data,event) {
      if(event) {
        var item = self.DetallesInventarioInicial.Agregar(undefined,event);
        item.InicializarVistaModelo(event,self.PostBusquedaProducto,self.CrearInventarioInicial);
        $(item.InputOpcion()).hide();
      }
    }

    self.QuitarDetalleInventarioInicial = function (data,event) {
      if(event) {
          var tr = $(data.InputProducto()).closest("tr");

          self.DetallesInventarioInicial.Remover(data,event);
      }
    }

    self.ValidarNumeroInventarioInicial = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ValidarFechaInventario = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
           // if(valid) self.ValorTipoCambio(self.CalcularTipoCambio(data,event));
        });
      }
    }

    self.CalcularTipoCambio = function (data,event) {
      if(event) {
        var resultado = 0.00;
        if (self.IdMoneda() != ID_MONEDA_SOLES)
          self.TipoCambio.ObtenerTipoCambio(data,function($data)  {
            if($data)
              resultado = data.TipoCambioVenta;
            else
              alertify.alert("No se encontro un tipo de cambio para la fecha emision");
          });
        return resultado;
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

    self.OnFocus = function(data,event,callback) {
      if(event)  {
          $(event.target).select();
          self.DetallesInventarioInicial.Actualizar(undefined,event);
          // $("#nletras").autoDenominacionMoneda(self.Total());
          if(callback) callback(data,event);
      }
    }

    self.AplicarExcepcionValidaciones = function(data,event) {
      if(event) {
          //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
          var total = self.DetallesInventarioInicial().length;
          var ultimoItem = self.DetallesInventarioInicial()[total-1];
          var resultado = "false";
          if (ultimoItem.CodigoMercaderia() === "" && ultimoItem.NombreProducto() === ""
            && (ultimoItem.CantidadInicial() === "" || ultimoItem.CantidadInicial() === "0")
            && (ultimoItem.ValorUnitario() === "" || ultimoItem.ValorUnitario() === "0")
            && (ultimoItem.FechaVencimiento() === "")
            && (ultimoItem.NumeroLote() === "")
            && (ultimoItem.NumeroDocumentoSalidaZofra() === "")
            && (ultimoItem.NumeroDua() === "")
            && (ultimoItem.NumeroItemDua() === "" || ultimoItem.NumeroItemDua() === "0")
            && (ultimoItem.FechaEmisionDocumentoSalidaZofra() === "")
            && (ultimoItem.FechaEmisionDua() === "")
          ) {
             resultado="true";
          }

          $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputProducto()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputCantidadInicial()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputValorUnitario()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputFechaVencimiento()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputNumeroLote()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputNumeroDocumentoSalidaZofra()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputNumeroDua()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputNumeroItemDua()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputFechaEmisionDocumentoSalidaZofra()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputFechaEmisionDua()).attr("data-validation-optional",resultado);
      }
    }

    self.ValidarDuplicados = function(data, event)
    {
      if(event){
        var objeto = ko.mapping.toJS(self.DetallesInventarioInicial());
        var detalles_nuevos = objeto.filter(function(value){return value.IdProducto == "-"});

        // var codigos = [];
        // detalles_nuevos.forEach(function(entry, key){
        //   codigos.push(parseInt(entry.CodigoMercaderia));
        // });
        // var filtro_codigo = codigos.filter(function(v,i,o){if(i>=0 && v!==o[i-1]) return v;});
        var filtro_codigo = removeDuplicates(detalles_nuevos, 'CodigoMercaderia');

        if(filtro_codigo.length != detalles_nuevos.length)
        {
          return false;
        }

        return true;
      }
    }

    self.Cerrar = function(data,event) {
      if(event) {

      }
    }

    self.OnClickBtnCerrar = function(data,event) {
      if(event) {

      }
    }

    self.GenerarExcel = function(data, event)
    {
      if(event)
      {
        $('#loader').show();
        self.DetallesInventarioInicial([]);
        // var data_msg = {"title": "<strong>Cargando...</strong>", "type": "success", "clase": "success", "message": ""};
        // CargarNotificacionDetallada(data_msg);

        var rABS = true; //VALOR PARAMETRICO DE DATA IMPORT
        var files = event.target.files,file;
        if (!files || files.length == 0) return;
        file = files[0];
        var fileReader = new FileReader();
        fileReader.onload = function (e) {
          // var filename = file.name;
          // // call 'xlsx' to read the file
          // var oFile = XLSX.read(e.target.result, {type: 'binary', cellDates:true, cellStyles:true});
          /* convert data to binary string */
          var data = e.target.result;
          if(!rABS) data = new Uint8Array(data);
          // var arr = new Array();
          // for(var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
          // var bstr = arr.join("");
          /* Call XLSX */
          var workbook = XLSX.read(data, {type: rABS ? 'binary' : 'array', cellDates:true, cellStyles:true});
          /* DO SOMETHING WITH workbook HERE */
          var first_sheet_name = workbook.SheetNames[0];
          /* Get worksheet */
          var worksheet = workbook.Sheets[first_sheet_name];
          // console.log(worksheet);
          var xls_object = XLSX.utils.sheet_to_json(worksheet,{raw:true});
          console.log(XLSX.utils.sheet_to_json(worksheet,{raw:true}));

          var i = 1;
          // self.DetallesInventarioInicial([]);
          xls_object.forEach(function(entry, key){
            // var arr = Object.keys(entry).map(function (key) { return entry[key]; });
            // console.log(arr[0]);
            // if(arr.length < 5)
            // {
            //   return false;
            // }
            var origen = ko.mapping.toJS(self.NuevoDetalleInventarioInicial);
            var data_mapper = Mapper.mapeo(entry, origen);
            var data1 = Object.assign({}, data_mapper);

            if(data1.CodigoMercaderia == "")
            {
              return false;
            }
            // var data_json = Object.assign({}, data_mapper);

            // var data1 = ko.mapping.toJS(self.NuevoDetalleInventarioInicial);
            // data1.IdInventarioInicial = i;
            // data1.CodigoMercaderia = arr[0];
            // data1.NombreProducto = arr[1];
            // data1.AbreviaturaUnidadMedida = arr[2];
            // data1.CantidadInicial = arr[3];
            // data1.ValorUnitario = arr[4];

            var optionsFecha = {year: 'numeric', month: '2-digit', day: '2-digit' };

            if(self.ParametroLote() == 1)
            {
              
              if(data1.FechaVencimiento != "")
              {
                if(data1.NumeroLote == "")
                {
                  return false;
                }
                var msecs = Date.parse(data1.FechaVencimiento);
                var d = new Date(msecs);
                data1.FechaVencimiento = d.toLocaleDateString('es-ES', optionsFecha);
              }
            }

            if(self.ParametroDocumentoSalidaZofra() == 1 && self.IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA)
            {
              if(data1.NumeroDocumentoSalidaZofra == "")
              {
                return false;
              }
              if(data1.FechaEmisionDocumentoSalidaZofra != "")
              {
                var msecs = Date.parse(data1.FechaEmisionDocumentoSalidaZofra);
                var d = new Date(msecs);
                data1.FechaEmisionDocumentoSalidaZofra = d.toLocaleDateString('es-ES', optionsFecha);
              }
            }

            if(self.ParametroDua() == 1 && self.IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA)
            {
              if(data1.NumeroDua == "")
              {
                return false;
              }
              if(data1.FechaEmisionDua != "")
              {
                var msecs = Date.parse(data1.FechaEmisionDua);
                var d = new Date(msecs);
                data1.FechaEmisionDua = d.toLocaleDateString('es-ES', optionsFecha);
              }
            }

            var codigoRegimen = self.ValidarProductoPorRegimen(data1, event);
            if(!codigoRegimen)
            {
              var response = {title: "<strong>No se cargo.</strong>", type: "danger", clase: "notify-danger", message: "El producto con codigo "+data1.CodigoMercaderia+" se encuentra en otro regimen."};
              CargarNotificacionDetallada(response);
              return false;
            }

            data1.IdProducto = "-";
            data1.IdTipoExistencia = ID_TIPO_EXISTENCIA_MERCADERIA;
            data1.IdMoneda = ID_MONEDA_SOLES;
            data1.IdOrigenMercaderia = self.IdOrigenMercaderia();

            var detalle = self.DetallesInventarioInicial.Agregar(data1, event);
            // detalle.InicializarVistaModelo(event,self.PostBusquedaProducto,self.CrearInventarioInicial);
            detalle.InicializarVistaModelo(event, undefined, undefined);
            detalle.ValidarProductoPorCodigoExcel(detalle, event, function($data,$event,$valid){
                // console.log($valid);
                if($valid == false)
                {

                  $(detalle.ComboUnidadMedida()).closest("tr").addClass('has-new');

                  var nombre = String(detalle.NombreProducto()).replace(/"/g, "'");
                  detalle.NombreProducto(nombre);

                  var data_unidadmedida = ko.mapping.toJS(self.UnidadesMedida, mappingIgnore);
                  // var rpta = JSON.search(data_unidadmedida, '//*[AbreviaturaUnidadMedida="'+data1.AbreviaturaUnidadMedida+'"]');
                  var rpta = JSON.search(data_unidadmedida, '//*[AbreviaturaUnidadMedida="'+data1.CodigoUnidadMedida+'"]');

                  if (rpta.length > 0)  {
                    detalle.IdUnidadMedida(rpta[0].IdUnidadMedida);
                  }
                  else {
                    detalle.IdUnidadMedida(ID_UNIDAD_MEDIDA_UND);
                    $(detalle.ComboUnidadMedida()).closest("td").addClass('has-warning');
                  }

                  $(detalle.ComboUnidadMedida()).attr('disabled', false);

                  var $input = $(detalle.InputCodigoMercaderia());
                  $input.attr("data-validation-found","true");
                  $input.attr("data-validation-text-found", nombre);

                }
                else {
                  $(detalle.ComboUnidadMedida()).attr('disabled', true);
                }

            });
            // $("#"+i+"_input_NombreProducto").removeAttr("data-validation");
            // $("#"+i+"_input_CodigoMercaderia").removeAttr("data-validation");
            // $("#"+i+"_input_CantidadInicial").removeAttr("data-validation");
            // $("#"+i+"_input_ValorUnitario").removeAttr("data-validation");

            i++;
          });

          var item = self.DetallesInventarioInicial.Agregar(undefined,event);
          item.InicializarVistaModelo(event,self.PostBusquedaProducto,self.CrearInventarioInicial);

          $('#loader').hide();
        };

        if(rABS)fileReader.readAsBinaryString(file); else fileReader.readAsArrayBuffer(file);
        //fileReader.readAsArrayBuffer(file);

        $("#ParseExcel").val("");
      }
    }

}
