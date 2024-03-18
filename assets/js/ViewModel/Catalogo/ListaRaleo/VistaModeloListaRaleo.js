VistaModeloListaRaleo = function (data) {
    var self = this;
    ko.mapping.fromJS(data, MappingCatalogo, self);

    ModeloListaRaleo.call(this,self);

    self.InicializarVistaModelo = function (data,event) {
      if (event)  {
        self.InicializarModelo(event);

        AccesoKey.AgregarKeyOption("#formListaRaleoMercaderia", "#btn_Grabar", TECLA_G);
        self.InicializarValidator(event);

        self.CargarSubFamilia(self, event);
        self.CargarModelo(self, event);
        self.OnChangeSubFamilia(self, event);
        self.OnChangeModelo(self, event);
      }
    }

    self.InicializarValidator = function(event) {
      if(event) {
        $.formUtils.addValidator({
          name : 'validacion_producto',
          validatorFunction : function(value, $el, config, language, $form) {
            var texto = $el.attr("data-validation-found");
            var resultado = ("true" === texto) ? true : false;
            return resultado;
          },
          errorMessageKey: 'badvalidacion_producto'
        });
      }
    }

    self.InicializarVistaModeloDetalle = function (data,event) {
      if (event)  {
        var item;
        self.DetallesListaRaleo([]);

      }
    }

    self.OnChangeSubFamilia = function(data, event){

      if(event)
      {
        self.IdSubFamiliaProducto($("#combo-subfamiliaproducto").val());
        //self.CargarModelo(data, event);
      }
    }

    self.OnChangeFamilia = function(data, event){
      if(event)
      {
        self.CargarSubFamilia(data, event);

        if(data.IdSubFamiliaProducto() == null)
        {
          $('#combo-subfamiliaproducto').prop('selectedIndex', 0);
        }

        // if(data.IdFamiliaProducto() == undefined)
        // {
        //   $('#combo-subfamiliaproducto').append($('<option selected="true"></option>').attr('value', '').text("TODOS"));
        // }

        setTimeout(function(){
          self.OnChangeSubFamilia(data, event);
        }, 500);

      }
    }

    self.CargarSubFamilia = function(data, event){
      if(event)
      {

        $('#combo-subfamiliaproducto').empty();
        var id_familia = data.IdFamiliaProducto();
        var id_subfamilia = data.IdSubFamiliaProducto();
        $('#combo-subfamiliaproducto').append($('<option selected="true"></option>').attr('value', '').text("TODOS"));
        url_subfamilia = ko.mapping.toJS(self.SubFamiliasProducto());
          $.each(url_subfamilia, function (key, entry) {
            if(id_familia == entry.IdFamiliaProducto)
            {
              var sel = "";
              // if(id_subfamilia != "")
              // {
              //   if(id_subfamilia == entry.IdSubFamiliaProducto)
              //   {
              //     sel = 'selected="true"';
              //   }
              // }

              $('#combo-subfamiliaproducto').append($('<option '+sel+'></option>').attr('value', entry.IdSubFamiliaProducto).text(entry.NombreSubFamiliaProducto));

            }
          })
      }
    }

    self.OnChangeModelo = function(data, event){

      if(event)
      {
        self.IdModelo($("#combo-modelo").val());
        //self.CargarModelo(data, event);
      }
    }

    self.OnChangeMarca = function(data, event){
      if(event)
      {
        self.CargarModelo(data, event);

        if(data.IdModelo() == null)
        {
          $('#combo-modelo').prop('selectedIndex', 0);
        }

        // if(data.IdMarca() == undefined)
        // {
        //   $('#combo-modelo').append($('<option selected="true"></option>').attr('value', '').text("TODOS"));
        // }

        setTimeout(function(){
          self.OnChangeModelo(data, event);
        }, 500);

      }
    }

    self.CargarModelo= function(data, event){
      if(event)
      {

        $('#combo-modelo').empty();
        var id_marca = data.IdMarca();
        var id_modelo = data.IdModelo();
        $('#combo-modelo').append($('<option selected="true"></option>').attr('value', '').text("TODOS"));
        url_modelo = ko.mapping.toJS(self.Modelos());
          $.each(url_modelo, function (key, entry) {
            if(id_marca == entry.IdMarca)
            {
              var sel = "";
              // if(id_modelo != "")
              // {
              //   if(id_modelo == entry.IdModelo)
              //   {
              //     sel = 'selected="true"';
              //   }
              // }

              $('#combo-modelo').append($('<option '+sel+'></option>').attr('value', entry.IdModelo).text(entry.NombreModelo));

            }
          })
      }
    }

    self.OnClickConsultarMercaderias = function(data, event)
    {
      if(event)
      {
        $('#loader').show();
        self.CopiaIdProductosDetalle([]);
        self.ConsultarMercaderias(data, event, function($data, $event){
          $('#loader').hide();
        });
      }
    }


    self.CrearListaRaleo = function(data,event) {
      if(event) {
        var $input = $(event.target);
        self.RefrescarBotonesDetalleListaRaleo($input,event);
      }
    }

    self.Seleccionar = function (data,event) {
      if(event) {
        var id = "#"+ data.IdListaRaleo();
        $(id).addClass('active').siblings().removeClass('active');
        self.SeleccionarDetalleListaRaleo(data,event);
        self.DetallesListaRaleo.Actualizar(undefined,event);
        // $("#nletras").autoDenominacionMoneda(self.Total());
      }
    }

    self.Deshacer = function(data,event)  {
      if(event) {
        self.Editar(self.ListaRaleoInicial,event,self.callback);
      }
    }

    self.Limpiar = function(data,event) {
      if(event) {
        self.Nuevo(self.ListaRaleoInicial,event,self.callback);
      }
    }

    self.Nuevo = function (data,event,callback) {
      if(event)  {
         $('#formListaRaleo').resetearValidaciones();
        if (callback) self.callback = callback;
        // self.NuevoListaRaleo(data,event);
        self.InicializarVistaModelo(undefined,event);
        self.InicializarVistaModeloDetalle(undefined,event);

        setTimeout( function()  {
            $('#combo-almacen').focus();
          },350);
      }
    }

    self.Editar = function(data,event,callback) {
      if(event) {
        if (self.IndicadorReseteoFormulario === true)  $('#formListaRaleo').resetearValidaciones();
        if (callback) self.callback = callback;
        self.EditarListaRaleo(data,event);
        self.InicializarVistaModelo(undefined,event);
        self.ConsultarDetallesListaRaleo(data,event, function ($data,$event) {
            self.InicializarVistaModeloDetalle(undefined,event);
            setTimeout( function()  {
                $('#combo-seriedocumento').focus();
              },350);
        });
      }
    }

    self.Guardar = function(data,event) {
      if(event) {
          alertify.confirm(self.titulo,"¿Desea guardar los cambios?",function() {
            $("#loader").show();
            self.GuardarListaRaleo(event,self.PostGuardar);
          },function(){

          });
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
          alertify.alert("Lista de Precios","Se Guardaron Correctamente los Datos.",function() {
            if (self.callback) self.callback(data,event);
            if(self.IdMarca() == undefined)
            {
              $('#combo-modelo').append($('<option selected="true"></option>').attr('value', '').text("TODOS"));
              self.IdModelo($("#combo-modelo").val());
            }

            if(self.IdFamiliaProducto() == undefined)
            {
              $('#combo-subfamiliaproducto').append($('<option selected="true"></option>').attr('value', '').text("TODOS"));
              self.IdSubFamiliaProducto($("#combo-subfamiliaproducto").val());
            }
            self.CopiaIdProductosDetalle([]);
          });
        }
      }
    }


    self.AgregarDetalleListaRaleo = function(data,event) {
      if(event) {
        var item = self.DetallesListaRaleo.Agregar(undefined,event);
        item.InicializarVistaModelo(event,self.PostBusquedaProducto,self.CrearListaRaleo);
        $(item.InputOpcion()).hide();
      }
    }

    self.QuitarDetalleListaRaleo = function (data,event) {
      if(event) {
          var tr = $(data.InputProducto()).closest("tr");

          self.DetallesListaRaleo.Remover(data,event);
      }
    }

    self.OnKeyEnter = function(data,event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }

    self.OnFocus = function(data,event,callback) {
      if(event)  {
          $(event.target).select();
          self.DetallesListaRaleo.Actualizar(undefined,event);
          // $("#nletras").autoDenominacionMoneda(self.Total());
          if(callback) callback(data,event);
      }
    }

    self.AplicarExcepcionValidaciones = function(data,event) {
      if(event) {
          //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
          var total = self.DetallesListaRaleo().length;
          var ultimoItem = self.DetallesListaRaleo()[total-1];
          var resultado = false;
          if (ultimoItem.CodigoMercaderia() === "" && ultimoItem.NombreProducto() === ""
            && (ultimoItem.CantidadInicial() === "" || ultimoItem.CantidadInicial() === "0")
            && (ultimoItem.ValorUnitario() === "" || ultimoItem.ValorUnitario() === "0")
          ) {
             resultado=true;
          }

          $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputProducto()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputCantidadInicial()).attr("data-validation-optional",resultado);
          $(ultimoItem.InputValorUnitario()).attr("data-validation-optional",resultado);
      }
    }

    self.ValidarDuplicados = function(data, event)
    {
      if(event){
        var objeto = ko.mapping.toJS(self.DetallesListaRaleo());
        var detalles_nuevos = objeto.filter(function(value){return value.IdProducto == "-"});

        var codigos = [];
        detalles_nuevos.forEach(function(entry, key){
          codigos.push(parseInt(entry.CodigoMercaderia));
        });
        var filtro_codigo = codigos.filter(function(v,i,o){if(i>=0 && v!==o[i-1]) return v;});

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

}
