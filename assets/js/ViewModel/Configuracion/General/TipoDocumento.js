
TiposDocumentoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._CodigoTipoDocumento = ko.observable(data.CodigoTipoDocumento);
    self._NombreTipoDocumento = ko.observable(data.NombreTipoDocumento);

    self._SeleccionarTodos = ko.observable(data.SeleccionarTodos);
    self._NumeroItemsSeleccionadas = ko.observable(data.NumeroItemsSeleccionadas);
    self._NombreAbreviado = ko.observable(data.NombreAbreviado);

    self.VistaOptions = ko.pureComputed(function(){
      return self.IndicadorEstado() == "T" ? "hidden" : "visible";
    }, this);

    self.estilo_combo = ko.pureComputed(function () {
      return parseFloat(self.IdTipoDocumento())<= 10 ? 'transform-bottom': 'transform-top' ;
    }, this);

    self.Deshacer = function (data,event){
      if (event)
      {
        //console.log(self._CodigoTipoDocumento());
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreTipoDocumento());


        self.CodigoTipoDocumento.valueHasMutated();
        self.NombreTipoDocumento.valueHasMutated();
        self.NombreAbreviado.valueHasMutated();
        self.SeleccionarTodos.valueHasMutated();
        self.NumeroItemsSeleccionadas.valueHasMutated();
        self.ModulosSistema.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.CodigoTipoDocumento("");
        self.NombreTipoDocumento("");
        self.NumeroItemsSeleccionadas("");
        self.NombreAbreviado("");
        self.CodigoTipoDocumento(self._CodigoTipoDocumento());
        self.NombreTipoDocumento(self._NombreTipoDocumento());
        self.NombreAbreviado(self._NombreAbreviado());
        self.SeleccionarTodos(self._SeleccionarTodos());
        self.NumeroItemsSeleccionadas(self._NumeroItemsSeleccionadas());

        var modulos_deshacer = ko.mapping.toJS(self._ModulosSistema())
        self.ModulosSistema([]);
        ko.utils.arrayForEach(modulos_deshacer, function(item) {
          self.ModulosSistema.push(new ModulosSistemaModel(item));
        });

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._CodigoTipoDocumento.valueHasMutated();
        self._NombreTipoDocumento.valueHasMutated();
        self._NombreAbreviado.valueHasMutated();
        self._SeleccionarTodos.valueHasMutated();
        self._NumeroItemsSeleccionadas.valueHasMutated();
        self._ModulosSistema.valueHasMutated();
        self._CodigoTipoDocumento(self.CodigoTipoDocumento());
        self._NombreTipoDocumento(self.NombreTipoDocumento());
        self._NombreAbreviado(self.NombreAbreviado());
        self._SeleccionarTodos(self.SeleccionarTodos());
        self._NumeroItemsSeleccionadas(self.NumeroItemsSeleccionadas());

        var modulos_confirmar = ko.mapping.toJS(self.ModulosSistema())
        self._ModulosSistema([]);
        ko.utils.arrayForEach(modulos_confirmar, function(item) {
          self._ModulosSistema.push(new ModulosSistemaModel(item));
        });
      }
    }

    self.Contador = function(data,event){
      if(event){
        var selector_modulo_todos = '#'+self.IdTipoDocumento()+'_selector_modulo_todos';
        var numero_items_ = '#'+self.IdTipoDocumento()+'_numero_items';
        var span_numero_items_ = '#'+self.IdTipoDocumento()+'_span_numero_items';

        var NumeroItemsSeleccionadas = 0;

        for (var i = 0; i < self.ModulosSistema().length; i++) {
          var id_moduloSistema = '#' + self.IdTipoDocumento()+'_'+self.ModulosSistema()[i].IdModuloSistema() + '_moduloSistema';
            if ($(id_moduloSistema).prop("checked")) NumeroItemsSeleccionadas++;
        }
        if(self.ModulosSistema().length == NumeroItemsSeleccionadas)
        {
          $(selector_modulo_todos).prop("checked", true);
        }
        else {
          $(selector_modulo_todos).prop("checked", false);
        }
        $(numero_items_).text(NumeroItemsSeleccionadas);
        $(span_numero_items_).text(NumeroItemsSeleccionadas);
        self.NumeroItemsSeleccionadas(NumeroItemsSeleccionadas);
      }
    }

    self.SeleccionarTodasItems = function (data,event){
      if (event) {
        var selector_modulo_todos = '#'+data.IdTipoDocumento()+'_selector_modulo_todos';
        var numero_items_ = '#'+data.IdTipoDocumento()+'_numero_items';
        var sel = $(selector_modulo_todos).prop("checked");
        console.log(sel);
        console.log(self.SeleccionarTodos());
        for (var i = 0; i < self.ModulosSistema().length; i++) {
          var id_moduloSistema = '#' + data.IdTipoDocumento()+'_'+self.ModulosSistema()[i].IdModuloSistema() + '_moduloSistema';
          $(id_moduloSistema).prop("checked", sel);
          self.ModulosSistema()[i].Seleccionado($(id_moduloSistema).prop("checked"));
        }
        if (sel == true) {
          $(numero_items_).text(self.ModulosSistema().length);
          self.NumeroItemsSeleccionadas(self.ModulosSistema().length);

        }
        else {
          $(numero_items_).text('0');
          self.NumeroItemsSeleccionadas('0');

        }
        return true;
      }
    }

    // self.Contador(data,window);

    self.CambioModuloSistema = function(data, event){
      if(event)
      {
        // var id_moduloSistema = '#' + data.IdTipoDocumento()+'_'+self.ModulosSistema()[0].IdModuloSistema() + '_moduloSistema';

        self.Contador(data, event);
      }
    }
}

TipoDocumentoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
    // self._ModulosSistema = ko.observable([]);
}

ModulosSistemaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

var Mapping = {
    'TiposDocumento': {
        create: function (options) {
            if (options)
              return new TiposDocumentoModel(options.data);
            }
    },
    'IndexTipoDocumento': {
        create: function (options) {
            if (options)
              return new TipoDocumentoModel(options.data);
            }
    },
    'ModulosSistema': {
        create: function (options) {
            if (options)
              return new ModulosSistemaModel(options.data);
            }
    },
}

IndexTipoDocumento = function (data) {

    var _modo_deshacer = false;
    var _codigotipodocumento;
    var _nombretipodocumento;
    var _input_habilitado_tipodocumento = false;
    var _idtipodocumento;
    var _tipodocumento;
    var _modo_nuevo_tipodocumento = false;
    var _id_filatipodocumento_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarTiposDocumento = function() {
        console.log("ListarTiposDocumento");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cTipoDocumento/ListarTiposDocumento',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataTipoDocumento.TiposDocumento([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.dataTipoDocumento.TiposDocumento.push(new TiposDocumentoModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      if (event) {
        console.log("Seleccionar");

        if (_modo_nuevo_tipodocumento == false){
          var id = "#"+data.IdTipoDocumento()+'_tr_tipodocumento';
          $(id).addClass('active').siblings().removeClass('active');
          _tipodocumento = data;
        }
      }
    }

    self.FilaButtonsTipoDocumento = function (data, event)  {
      if (event) {
        console.log("BUTTONS");
        console.log("EVENTTARGET: " + $(event.target).attr('class'));
        console.log("THIS: " + $(this).attr('class'));
        if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
          // bla bla bla
          console.log("Button");
        }
        else
        {
          console.log("MODO NUEVO: " + _modo_nuevo_tipodocumento);
          if(_modo_nuevo_tipodocumento == true)
          return;

          _tipodocumento.Deshacer(null, event);
          _input_habilitado_tipodocumento = false;
          $("#btnAgregarTipoDocumento").prop("disabled",false);
          self.HabilitarTablaSpanTipoDocumento(null, true);

        }
        self.Seleccionar(data,event);
      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdTipoDocumento()+'_tr_tipodocumento';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_tipodocumento == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.dataTipoDocumento.TiposDocumento(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdTipoDocumento();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }

    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdTipoDocumento()+'_tr_tipodocumento';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_tipodocumento == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idtipodocumento = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.dataTipoDocumento.TiposDocumento(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idtipodocumento == item.IdTipoDocumento();
            });

          if(match)
          {
            _tipodocumento = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }

    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputTipoDocumento = function (data, option)  {
      //var id = "#"+data.IdTipoDocumento();
      var id = data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputTipoDocumento').hide();
        $(id).find('.class_SpanTipoDocumento').show();
      }
      else
      {
        $(id).find('.class_InputTipoDocumento').show();
        $(id).find('.class_SpanTipoDocumento').hide();
      }

    }

    self.HabilitarTablaSpanTipoDocumento = function (data, option)  {
      //var id = "#"+data.IdTipoDocumento();
      var id = "#DataTables_Table_0_tipodocumento";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanTipoDocumento').hide();
        $(id).find('.class_InputTipoDocumento').show();
        //$(id).find('.guardar_button_TipoDocumento').show();
        //_input_habilitado_tipodocumento = true;
      }
      else {
        $(id).find('.class_SpanTipoDocumento').show();
        $(id).find('.class_InputTipoDocumento').hide();
        $(id).find('.guardar_button_TipoDocumento').hide();
        //_input_habilitado_tipodocumento = false;
      }

    }

    self.HabilitarButtonsTipoDocumento = function(data, option){
      var id = "#DataTables_Table_0_tipodocumento";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_TipoDocumento').prop("disabled", true);
        $(id).find('.borrar_button_TipoDocumento').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_TipoDocumento').prop("disabled", false);
        $(id).find('.borrar_button_TipoDocumento').prop("disabled", false);
      }
    }

    self.AgregarTipoDocumento = function(data,event) {
      console.log("AgregarTipoDocumento");

      if ( _input_habilitado_tipodocumento == true )
      {

      }
      else
      {
        var objeto = Knockout.CopiarObjeto(self.dataTipoDocumento.TipoDocumento);
        _tipodocumento = new TiposDocumentoModel(objeto);
        self.dataTipoDocumento.TiposDocumento.push(_tipodocumento);

        //Deshabilitando buttons
        self.HabilitarButtonsTipoDocumento(null, false);
        $("#null_editar_button_TipoDocumento").prop("disabled", true);
        $("#null_borrar_button_TipoDocumento").prop("disabled", false);


        $("#btnAgregarTipoDocumento").prop("disabled",true);

        //habilitar como destino
        console.log("ID:"+objeto.IdTipoDocumento());



        var idspancodigo ="#"+objeto.IdTipoDocumento()+"_span_CodigoTipoDocumento";
        var idinputcodigo ="#"+objeto.IdTipoDocumento()+"_input_CodigoTipoDocumento";

        var idspannombre ="#"+objeto.IdTipoDocumento()+"_span_NombreTipoDocumento";
        var idinputnombre ="#"+objeto.IdTipoDocumento()+"_input_NombreTipoDocumento";

        var idspannombreabreviado ="#"+objeto.IdTipoDocumento()+"_span_NombreAbreviado";
        var idinputnombreabreviado ="#"+objeto.IdTipoDocumento()+"_input_NombreAbreviado";

        var idspanmodulosistema ="#"+objeto.IdTipoDocumento()+"_span_ModuloSistema";
        var idinputmodulosistema ="#"+objeto.IdTipoDocumento()+"_input_ModuloSistema";

        var idbutton ="#"+objeto.IdTipoDocumento()+"_button_TipoDocumento";

        $(idspancodigo).hide();
        $(idinputcodigo).show();

        $(idspannombre).hide();
        $(idinputnombre).show();

        $(idspannombreabreviado).hide();
        $(idinputnombreabreviado).show();

        $(idspanmodulosistema).hide();
        $(idinputmodulosistema).show();

        $(idbutton).show();
        $(idinputcodigo).focus();

        _modo_nuevo_tipodocumento = true;
        _input_habilitado_tipodocumento = true;

        var tabla = $('#DataTables_Table_0_tipodocumento');
        $('tr:last', tabla).addClass('active').siblings().removeClass('active');

      }
    }

    self.InsertarTipoDocumento =function(data,event){
      if(event)
      {
        console.log("InsertarTipoDocumento");
        console.log(_tipodocumento.NombreTipoDocumento());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _tipodocumento});

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cTipoDocumento/InsertarTipoDocumento',
          success: function (data) {
            if (data != null) {

            if ($.isNumeric(data.IdTipoDocumento))
            {
              _tipodocumento.IdTipoDocumento(data.IdTipoDocumento);

              _tipodocumento.ModulosSistema([]);
              ko.utils.arrayForEach(data.ModulosSistema, function(item) {
                if(item.Seleccionado == "true"){
                  item.Seleccionado = true;
                }
                else {
                  item.Seleccionado = false;
                }
                _tipodocumento.ModulosSistema.push(new ModulosSistemaModel(item));
              });

              //deshabilitar botones agregar
              $("#btnAgregarTipoDocumento").prop("disabled",false);

              var id_tipodocumento = "#"+ _tipodocumento.IdTipoDocumento()+'_tr_tipodocumento';
              self.HabilitarFilaInputTipoDocumento(id_tipodocumento, false);

              var idbutton ="#"+_tipodocumento.IdTipoDocumento()+"_button_TipoDocumento";
              $(idbutton).hide();

              _tipodocumento.Confirmar(null,event);
              self.HabilitarButtonsTipoDocumento(null, true);

              existecambio = false;
              _input_habilitado_tipodocumento = false;
              _modo_nuevo_tipodocumento = false;
            }
            else {
              alertify.alert(data);
            }
          }
          $("#loader").hide();
        },
        error : function (jqXHR, textStatus, errorThrown) {
          //console.log(jqXHR.responseText);
          $("#loader").hide();
        }
      });
      }
    }

    self.ActualizarTipoDocumento = function(data,event) {
      console.log("ActualizarTipoDocumento");
      console.log(_tipodocumento.NombreTipoDocumento());
      $("#loader").show();
      var objeto = data;
      var datajs = ko.toJS({"Data" : _tipodocumento});

      $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cTipoDocumento/ActualizarTipoDocumento',
          success: function (data) {
              if (data != null) {
                console.log(data);

                if ($.isArray(data))
                {
                  //deshabilitar campo origen
                  $("#btnAgregarTipoDocumento").prop("disabled",false);
                  console.log("ID5:"+_tipodocumento.IdTipoDocumento());

                  _tipodocumento.ModulosSistema([]);
                  ko.utils.arrayForEach(data, function(item) {
                    if(item.Seleccionado == "true"){
                      item.Seleccionado = true;
                    }
                    else {
                      item.Seleccionado = false;
                    }
                    _tipodocumento.ModulosSistema.push(new ModulosSistemaModel(item));
                  });

                  _tipodocumento.Confirmar(null,event);
                  var modulos_confirmar = ko.mapping.toJS(_tipodocumento.ModulosSistema());

                  var id_tipodocumento = "#"+ _tipodocumento.IdTipoDocumento()+'_tr_tipodocumento';
                  self.HabilitarFilaInputTipoDocumento(id_tipodocumento, false);

                  var idbutton ="#"+_tipodocumento.IdTipoDocumento()+"_button_TipoDocumento";
                  $(idbutton).hide();

                  existecambio = false;
                  _input_habilitado_tipodocumento = false;
                  _modo_nuevo_tipodocumento = false;

                }
                else {
                  alertify.alert(data);
                }
            }
            $("#loader").fadeOut("slow");
        },
        error : function (jqXHR, textStatus, errorThrown) {
          //console.log(jqXHR.responseText);
          $("#loader").hide();
        }
      });
    }

    self.EditarTipoDocumento = function(data, event) {

      if(event)
      {
        console.log("EditarTipoDocumento");
        console.log("ID.:"+data.IdTipoDocumento());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_tipodocumento);

        if( _modo_nuevo_tipodocumento == true )
        {

        }
        else {

          if (_tipodocumento.IdTipoDocumento() == data.IdTipoDocumento())
          {

            if (_input_habilitado_tipodocumento == true)
            {
              $("#btnAgregarTipoDocumento").prop("disabled",false);
              data.Deshacer(null,event);
              var id_tipodocumento = "#"+ data.IdTipoDocumento()+'_tr_tipodocumento';
              self.HabilitarFilaInputTipoDocumento(id_tipodocumento, false);

              var idbutton = "#"+_tipodocumento.IdTipoDocumento()+"_button_TipoDocumento";
              $(idbutton).hide();

              _input_habilitado_tipodocumento =false;


            }
            else {
              $("#btnAgregarTipoDocumento").prop("disabled",true);
              var id_tipodocumento = "#"+ data.IdTipoDocumento()+'_tr_tipodocumento';
              self.HabilitarFilaInputTipoDocumento(id_tipodocumento, true);

              var idbutton = "#"+data.IdTipoDocumento()+"_button_TipoDocumento";

              var idinput = "#"+data.IdTipoDocumento()+"_input_CodigoTipoDocumento";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_tipodocumento = true;
            }

          }
          else {
            $("#btnAgregarTipoDocumento").prop("disabled",true);
            if( _input_habilitado_tipodocumento == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_tipodocumento.IdTipoDocumento());

              var id_tipodocumento = "#"+ _tipodocumento.IdTipoDocumento()+'_tr_tipodocumento';
              self.HabilitarFilaInputTipoDocumento(id_tipodocumento, false);

              var idbutton = "#"+_tipodocumento.IdTipoDocumento()+"_button_TipoDocumento";

              _tipodocumento.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_tipodocumento = "#"+ data.IdTipoDocumento()+'_tr_tipodocumento';
            self.HabilitarFilaInputTipoDocumento(id_tipodocumento, true);

            var idbutton = "#"+data.IdTipoDocumento()+"_button_TipoDocumento";

            var idinput = "#"+data.IdTipoDocumento()+"_input_CodigoTipoDocumento";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_tipodocumento = true;
          }
        }
        self.Seleccionar(data,event);
      }

    }

    self.PreBorrarTipoDocumento = function (data, event) {

      if(_modo_nuevo_tipodocumento == false)
      {
        _tipodocumento.Deshacer(null, event);
        _input_habilitado_tipodocumento = false;
        $("#btnAgregarTipoDocumento").prop("disabled",false);
        self.HabilitarTablaSpanTipoDocumento(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarTipoDocumento");
          console.log(data.IdTipoDocumento());
          self.HabilitarButtonsTipoDocumento(null, true);
          if (data.IdTipoDocumento() != null){
            self.BorrarTipoDocumento(data);
          }
          else
          {
            $("#btnAgregarTipoDocumento").prop("disabled",false);
            _input_habilitado_tipodocumento = false;
            _modo_nuevo_tipodocumento = false;
            self.dataTipoDocumento.TiposDocumento.remove(data);

            var tabla = $('#DataTables_Table_0_tipodocumento');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarTiposDocumento();
          }
        });
      }, 200);

    }

    self.BorrarTipoDocumento = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cTipoDocumento/BorrarTipoDocumento',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarTipoDocumento").prop("disabled",false);
                      self.HabilitarTablaSpanTipoDocumento(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataTipoDocumento.TiposDocumento.remove(objeto);
                        //self.ListarTiposDocumento();
                    }
              }
          },
          error : function (jqXHR, textStatus, errorThrown) {
                 //$("#modalMensajeTexto").empty();
                 //$("#modalMensajeTexto").append(jqXHR.responseText);
                 //$("#modalMensaje").modal("show");
                 console.log(jqXHR.responseText);
                 /*
                 setTimeout(function () {
                     $btn.button('reset');
                 }, 1000);
                 */
             }
      });
      self.Seleccionar(data,event);

    }

    self.OnClickTipoDocumento = function(data ,event) {

      if(event)
      {

          console.log("OnClickTipoDocumento");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_tipodocumento);

          if( _modo_nuevo_tipodocumento == true )
          {

          }
          else
          {
            $("#btnAgregarTipoDocumento").prop("disabled",true);
            if(_tipodocumento.IdTipoDocumento() !=  data.IdTipoDocumento())
            {
              if (_input_habilitado_tipodocumento == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _tipodocumento.Deshacer(null, event);

                var id_tipodocumento = "#" + _tipodocumento.IdTipoDocumento()+'_tr_tipodocumento';
                self.HabilitarFilaInputTipoDocumento(id_tipodocumento, false);

                var idbutton = "#"+_tipodocumento.IdTipoDocumento()+"_button_TipoDocumento";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_tipodocumento.IdTipoDocumento());
              console.log(data.IdTipoDocumento());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_tipodocumento = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_tipodocumento, "span") || $.isSubstring(id_fila_tipodocumento, "input")){
                id_fila_tipodocumento = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filatipodocumento_anterior = $(id_fila_tipodocumento).parent()[0].id;
              var idspan ="#"+$(id_fila_tipodocumento).find('span').attr('id');
              var idinput ="#"+$(id_fila_tipodocumento).find('input').attr('id');
              self.HabilitarFilaInputTipoDocumento("#" + $(id_fila_tipodocumento).parent()[0].id, true);

              var idbutton = "#"+data.IdTipoDocumento()+"_button_TipoDocumento";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_tipodocumento = true;

              }
              else {
                if (_input_habilitado_tipodocumento == false)
                {
                  var id_fila_tipodocumento = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_tipodocumento, "span") || $.isSubstring(id_fila_tipodocumento, "input")){
                    id_fila_tipodocumento = "#" + $(event.target).parent()[0].id;
                  }

                  self.HabilitarFilaInputTipoDocumento("#" + $(id_fila_tipodocumento).parent()[0].id, true);

                  var idbutton = "#"+data.IdTipoDocumento()+"_button_TipoDocumento";
                  var idinput ="#"+$(id_fila_tipodocumento).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_tipodocumento = true;
                }
                else {
                  console.log("MISMA LNEA");
                }
              }
              self.Seleccionar(data,event);
          }
          return false;
      }

    }

    //Funcion para buscar una palabra en una cadena de texto
    jQuery.isSubstring = function(haystack, needle){
      return haystack.indexOf(needle) !== -1;
    }

    //FUNCION DE MANEJO DE TECLAS Y ATAJOS
    self.OnKeyUpTipoDocumento = function(data, event){
      if(event)
      {
       console.log("OnKeyUpTipoDocumento");

       var code = event.keyCode || event.which;

       if (code === 13) {//enter
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }

         if(alertify.confirm().isOpen() || alertify.alert().isOpen())
         {
           event.preventDefault();
           return false;
         }
        //Variable para obtener el id delinput
        var idinputcodigo = _tipodocumento.IdTipoDocumento() + '_input_CodigoTipoDocumento';
         var idinputnombre = _tipodocumento.IdTipoDocumento() + '_input_NombreTipoDocumento';


         if(event.target.id == idinputcodigo)
         {
            _tipodocumento.CodigoTipoDocumento(event.target.value);
         }
         else if(event.target.id == idinputnombre)
         {
           _tipodocumento.NombreTipoDocumento(event.target.value);
         }


         if(_modo_nuevo_tipodocumento == true)
         {
           self.InsertarTipoDocumento(_tipodocumento,event);
         }
         else
         {
           self.ActualizarTipoDocumento(_tipodocumento,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado_tipodocumento == true)
        {
          if(_modo_nuevo_tipodocumento == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_tipodocumento);
              self.dataTipoDocumento.TiposDocumento.remove(_tipodocumento);
              var tabla = $('#DataTables_Table_0_tipodocumento');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarTipoDocumento").prop("disabled",false);
              self.HabilitarButtonsTipoDocumento(null, true);

               _modo_nuevo_tipodocumento = false;
               _input_habilitado_tipodocumento = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_tipodocumento._NombreTipoDocumento());
            //revertir texto
            //data.NombreTipoDocumento(_tipodocumento.NombreTipoDocumento());

             _tipodocumento.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarTipoDocumento").prop("disabled",false);

            /*var id_fila_tipodocumento = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_tipodocumento, "span") || $.isSubstring(id_fila_tipodocumento, "input")){
              id_fila_tipodocumento = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputTipoDocumento("#" + $(id_fila_tipodocumento).parent()[0].id, false);*/
            self.HabilitarTablaSpanTipoDocumento(null, true);
            var idbutton ="#"+_tipodocumento.IdTipoDocumento()+"_button_TipoDocumento";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_tipodocumento = false;
            _input_habilitado_tipodocumento = false;
          }

        }
      }
    }

    self.GuardarTipoDocumento = function(data,event) {
      if(event)
      {
         console.log("GuardarTipoDocumento");
         console.log(_nombretipodocumento);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
         var idinputcodigo = _tipodocumento.IdTipoDocumento() + '_input_CodigoTipoDocumento';
          var idinputnombre = _tipodocumento.IdTipoDocumento() + '_input_NombreTipoDocumento';


          if(event.target.id == idinputcodigo)
          {
             _tipodocumento.CodigoTipoDocumento(_codigotipodocumento);
          }
          else if(event.target.id == idinputnombre)
          {
            _tipodocumento.NombreTipoDocumento(_nombretipodocumento);
          }
         //_tipodocumento.NombreTipoDocumento(_nombretipodocumento);

         if(_modo_nuevo_tipodocumento == true)
         {
           self.InsertarTipoDocumento(_tipodocumento,event);
         }
         else
         {
           self.ActualizarTipoDocumento(_tipodocumento,event);
         }
         self.Seleccionar(data,event);

      }
    }

}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
