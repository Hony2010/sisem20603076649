SedesModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._CodigoSede = ko.observable(data.CodigoSede);
    self._NombreSede = ko.observable(data.NombreSede);
    self._Direccion = ko.observable(data.Direccion);

    self._SeleccionarTodos = ko.observable(data.SeleccionarTodos);
    self._NumeroItemsSeleccionadas = ko.observable(data.NumeroItemsSeleccionadas);
    self._NombreAbreviado = ko.observable(data.NombreAbreviado);

    self.estilo_combo = ko.pureComputed(function () {
      return parseFloat(self.IdSede())<= 10 ? 'transform-bottom': 'transform-top' ;
    }, this);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreSede());

        self.CodigoSede.valueHasMutated();
        self.Direccion.valueHasMutated();
        self.NombreSede.valueHasMutated();
        self.NumeroItemsSeleccionadas.valueHasMutated();
        self.SeleccionarTodos.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.CodigoSede("");
        self.Direccion("");
        self.NombreSede("");
        self.CodigoSede(self._CodigoSede());
        self.Direccion(self._Direccion());
        self.NombreSede(self._NombreSede());
        self.SeleccionarTodos(self._SeleccionarTodos());
        self.NumeroItemsSeleccionadas(self._NumeroItemsSeleccionadas());

        var tipos_deshacer = ko.mapping.toJS(self._TiposSede())
        self.TiposSede([]);
        ko.utils.arrayForEach(tipos_deshacer, function(item) {
          self.TiposSede.push(new TiposSedeModel(item));
        });

        return true;
      }
    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._CodigoSede.valueHasMutated();
        self._Direccion.valueHasMutated();
        self._NombreSede.valueHasMutated();
        self._SeleccionarTodos.valueHasMutated();
        self._NumeroItemsSeleccionadas.valueHasMutated();
        self._CodigoSede(self.CodigoSede());
        self._Direccion(self.Direccion());
        self._NombreSede(self.NombreSede());
        self._SeleccionarTodos(self.SeleccionarTodos());
        self._NumeroItemsSeleccionadas(self.NumeroItemsSeleccionadas());

        var tipos_confirmar = ko.mapping.toJS(self.TiposSede())
        self._TiposSede([]);
        ko.utils.arrayForEach(tipos_confirmar, function(item) {
          self._TiposSede.push(new TiposSedeModel(item));
        });
      }
    }

    self.Contador = function(data,event){
      if(event){
        var selector_tipo_todos = '#'+self.IdSede()+'_selector_tipo_todos';
        var numero_items_ = '#'+self.IdSede()+'_numero_items';
        var span_numero_items_ = '#'+self.IdSede()+'_span_numero_items';

        var NumeroItemsSeleccionadas = 0;

        for (var i = 0; i < self.TiposSede().length; i++) {
          var id_tipoSede = '#' + self.IdSede()+'_'+self.TiposSede()[i].IdTipoSede() + '_tipoSede';
            if ($(id_tipoSede).prop("checked")) NumeroItemsSeleccionadas++;
        }
        if(self.TiposSede().length == NumeroItemsSeleccionadas)
        {
          $(selector_tipo_todos).prop("checked", true);
        }
        else {
          $(selector_tipo_todos).prop("checked", false);
        }
        $(numero_items_).text(NumeroItemsSeleccionadas);
        $(span_numero_items_).text(NumeroItemsSeleccionadas);
        self.NumeroItemsSeleccionadas(NumeroItemsSeleccionadas);
      }
    }

    self.SeleccionarTodasItems = function (data,event){
      if (event) {
        var selector_tipo_todos = '#'+data.IdSede()+'_selector_tipo_todos';
        var numero_items_ = '#'+data.IdSede()+'_numero_items';
        var sel = $(selector_tipo_todos).prop("checked");
        console.log(sel);
        console.log(self.SeleccionarTodos());
        for (var i = 0; i < self.TiposSede().length; i++) {
          var id_tipoSede = '#' + data.IdSede()+'_'+self.TiposSede()[i].IdTipoSede() + '_tipoSede';
          $(id_tipoSede).prop("checked", sel);
          self.TiposSede()[i].Seleccionado($(id_tipoSede).prop("checked"));
        }
        if (sel == true) {
          $(numero_items_).text(self.TiposSede().length);
          self.NumeroItemsSeleccionadas(self.TiposSede().length);

        }
        else {
          $(numero_items_).text('0');
          self.NumeroItemsSeleccionadas('0');

        }
        return true;
      }
    }

    // self.Contador(data,window);

    self.CambioTipoSede = function(data, event){
      if(event)
      {
        // var id_tipoSede = '#' + data.IdSede()+'_'+self.TiposSede()[0].IdTiposSede() + '_tipoSede';
        self.Contador(data, event);
      }
    }
}

SedeModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

TiposSedeModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

var Mapping = {
    'Sedes': {
        create: function (options) {
            if (options)
              return new SedesModel(options.data);
            }
    },
    'Sede': {
        create: function (options) {
            if (options)
              return new SedeModel(options.data);
            }
    },
    'TiposSede': {
        create: function (options) {
            if (options)
              return new TipoSedeModel(options.data);
            }
    }

}

IndexSede = function (data) {

    var _modo_deshacer = false;
    var _codesede;
    var _codigosede;
    var _nombresede;
    var _abreviaturasede;
    var _input_habilitado_sede = false;
    var _idsede;
    var _sede;
    var _modo_nuevo_sede = false;
    var _id_filasede_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarSedes = function() {
        console.log("ListarSedes");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cSede/ListarSedes',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataSede.Sedes([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.dataSede.Sedes.push(new SedesModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");
      if (_modo_nuevo_sede == false)
      {
        var id = "#"+data.IdSede()+'_tr_sede';
        $(id).addClass('active').siblings().removeClass('active');
        _sede = data;

      }

    }

    self.FilaButtonsSede = function (data, event)  {
      console.log("FILASBUTONES");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_sede);
          if(_modo_nuevo_sede == true)
          return;

          console.log("OTRA FILA AFECTADA");
          _sede.Deshacer(null, event);
          _input_habilitado_sede = false;
          $("#btnAgregarSede").prop("disabled",false);
          self.HabilitarTablaSpanSede(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdSede()+'_tr_sede';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_sede == false) //revisar
      {
        //console.log(item.IdSede());
        var _idsede = anteriorObjeto.attr("id");
        //console.log(_idsede);
        var match = ko.utils.arrayFirst(self.dataSede.Sedes(), function(item) {
              //console.log(item.IdSede());
              return _idsede == item.IdSede();
          });

        if(match)
        {
          _idsede = match;
        }
      }
    }

    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdSede()+'_tr_sede';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_sede == false) //revisar
        {
          //console.log(item.IdSede());
          var _idsede = siguienteObjeto.attr("id");
          //console.log(_idsede);
          var match = ko.utils.arrayFirst(self.dataSede.Sedes(), function(item) {
                //console.log(item.IdSede());
                return _idsede == item.IdSede();
            });

          if(match)
          {
            _sede = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }

    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputSede = function (data, option)  {
      //var id = "#"+data.IdSede();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputSede').hide();
        $(id).find('.class_SpanSede').show();
      }
      else
      {
        $(id).find('.class_InputSede').show();
        $(id).find('.class_SpanSede').hide();
      }

    }

    self.HabilitarTablaSpanSede = function (data, option)  {
      //var id = "#"+data.IdSede();
      var id = "#DataTables_Table_0_sede";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanSede').hide();
        $(id).find('.class_InputSede').show();
        //$(id).find('.guardar_button_Sede').show();
        //_input_habilitado_sede = true;
      }
      else {
        $(id).find('.class_SpanSede').show();
        $(id).find('.class_InputSede').hide();
        $(id).find('.guardar_button_Sede').hide();
        //_input_habilitado_sede = false;
      }

    }

    self.HabilitarButtonsSede = function(data, option){
      var id = "#DataTables_Table_0_sede";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_Sede').prop("disabled", true);
        $(id).find('.borrar_button_Sede').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_Sede').prop("disabled", false);
        $(id).find('.borrar_button_Sede').prop("disabled", false);
      }
    }

    self.AgregarSede = function(data,event) {
      console.log("AgregarSede");

      if ( _input_habilitado_sede != true )
      {
        var objeto = Knockout.CopiarObjeto(self.dataSede.Sede);
        objeto.NombreTipoSede = "AGENCIA";

        console.log(objeto);
        _sede = new SedesModel(objeto);
        self.dataSede.Sedes.push(_sede);

        //Deshabilitando buttons
        self.HabilitarButtonsSede(null, false);
        $("#null_editar_button_Sede").prop("disabled", true);
        $("#null_borrar_button_Sede").prop("disabled", false);


        $("#btnAgregarSede").prop("disabled",true);

        //habilitar como destino
        console.log("ID:"+objeto.IdSede());
        var id_span_codigosede ="#"+objeto.IdSede()+"_span_CodigoSede";
        var id_input_codigosede ="#"+objeto.IdSede()+"_input_CodigoSede";

        var id_span_nombresede ="#"+objeto.IdSede()+"_span_NombreSede";
        var id_input_nombresede ="#"+objeto.IdSede()+"_input_NombreSede";

        var id_span_direccion ="#"+objeto.IdSede()+"_span_Direccion";
        var id_input_direccion ="#"+objeto.IdSede()+"_input_Direccion";

        var id_span_tiposede ="#"+objeto.IdSede()+"_span_TipoSede";
        var id_input_tiposede ="#"+objeto.IdSede()+"_input_TipoSede";


        var idbutton ="#"+objeto.IdSede()+"_button_Sede";

        console.log(idbutton);
        //var id_tiposede = '#' + self.IdSede() +  '_input_IdTipoSede';
        // $(id_input_idtiposede).selectpicker("refresh");
        $(id_span_codigosede).hide();
        $(id_input_codigosede).show();

        $(id_span_nombresede).hide();
        $(id_input_nombresede).show();

        $(id_span_direccion).hide();
        $(id_input_direccion).show();

        $(id_span_tiposede).hide();
        $(id_input_tiposede).show();

        $(idbutton).show();
        $(id_input_codigosede).focus();

        _modo_nuevo_sede = true;
        _input_habilitado_sede = true;

        var tabla = $('#DataTables_Table_0_sede');
        $('tr:last', tabla).addClass('active').siblings().removeClass('active');
      }
    }

    self.InsertarSede =function(data,event){

      if(event)
      {
        console.log("InsertarSede");
        console.log(_sede.NombreSede());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _sede});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cSede/InsertarSede',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarSede");
                      console.log(data);

                      if ($.isNumeric(data.IdSede))
                      {
                        _sede.IdSede(data.IdSede);

                        _sede.TiposSede([]);
                        ko.utils.arrayForEach(data.TiposSede, function(item) {
                          if(item.Seleccionado == "true"){
                            item.Seleccionado = true;
                          }
                          else {
                            item.Seleccionado = false;
                          }
                          _sede.TiposSede.push(new TiposSedeModel(item));
                        });

                        //deshabilitar botones agregar
                        $("#btnAgregarSede").prop("disabled",false);

                        var id_sede = "#"+ _sede.IdSede()+'_tr_sede';
                        self.HabilitarFilaInputSede(id_sede, false);

                        var idbutton ="#"+_sede.IdSede()+"_button_Sede";
                        $(idbutton).hide();

                         _sede.Confirmar(null,event);
                         self.HabilitarButtonsSede(null, true);

                         //ACTUALIZANDO DATA Nombre
                         var idnombretiposede = '#' + _sede.IdSede() + '_input_IdTipoSede option:selected';
                         var nombretiposede = $(idnombretiposede).html();

                         _sede.NombreTipoSede(nombretiposede);

                        existecambio = false;
                        _input_habilitado_sede = false;
                        _modo_nuevo_sede = false;

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

    self.ActualizarSede = function(data,event) {
          console.log("ActualizarSede");
          console.log(_sede.NombreSede());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _sede});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cSede/ActualizarSede',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if ($.isArray(data))
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarSede").prop("disabled",false);
                          console.log("ID5:"+_sede.IdSede());

                          _sede.TiposSede([]);
                          ko.utils.arrayForEach(data, function(item) {
                            if(item.Seleccionado == "true"){
                              item.Seleccionado = true;
                            }
                            else {
                              item.Seleccionado = false;
                            }
                            _sede.TiposSede.push(new TiposSedeModel(item));
                          });

                          _sede.Confirmar(null,event);

                          var id_sede = "#"+ _sede.IdSede()+'_tr_sede';
                          self.HabilitarFilaInputSede(id_sede, false);

                          var idbutton ="#"+_sede.IdSede()+"_button_Sede";
                          $(idbutton).hide();

                          //ACTUALIZANDO DATA Nombre

                          existecambio = false;
                          _input_habilitado_sede = false;
                          _modo_nuevo_sede = false;
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

    self.EditarSede = function(data, event) {

      if(event)
      {
        console.log("EditarSede");
        console.log("ID.:"+data.IdSede());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_sede);

        if( _modo_nuevo_sede == true )
        {

        }
        else {

          if (_sede.IdSede() == data.IdSede())
          {

            if (_input_habilitado_sede == true)
            {
              $("#btnAgregarSede").prop("disabled",false);
              data.Deshacer(null,event);
              var id_sede = "#"+ data.IdSede()+'_tr_sede';
              self.HabilitarFilaInputSede(id_sede, false);

              var idbutton = "#"+_sede.IdSede()+"_button_Sede";
              $(idbutton).hide();

              _input_habilitado_sede =false;

            }
            else {
              $("#btnAgregarSede").prop("disabled",true);
              var id_sede = "#"+ data.IdSede()+'_tr_sede';
              self.HabilitarFilaInputSede(id_sede, true);

              var idbutton = "#"+data.IdSede()+"_button_Sede";

              var idinput = "#"+data.IdSede()+"_input_Direccion";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_sede = true;
            }

          }
          else {
            $("#btnAgregarSede").prop("disabled",true);
            if( _input_habilitado_sede == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_sede.IdSede());

              var id_sede = "#"+ _sede.IdSede()+'_tr_sede';
              self.HabilitarFilaInputSede(id_sede, false);

              var idbutton = "#"+_sede.IdSede()+"_button_Sede";

              _sede.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_sede = "#"+ data.IdSede()+'_tr_sede';
            self.HabilitarFilaInputSede(id_sede, true);

            var idbutton = "#"+data.IdSede()+"_button_Sede";

            var idinput = "#"+data.IdSede()+"_input_Direccion";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_sede = true;
          }
        }
        self.Seleccionar(data,event);
      }

    }

    self.PreBorrarSede = function (data) {

      if(_modo_nuevo_sede == false)
      {
        _sede.Deshacer(null, event);
        _input_habilitado_sede = false;
        $("#btnAgregarSede").prop("disabled",false);
        self.HabilitarTablaSpanSede(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarSede");
          console.log(data.IdSede());
          self.HabilitarButtonsSede(null, true);
          if (data.IdSede() != null){
            self.BorrarSede(data);
          }
          else
          {
            $("#btnAgregarSede").prop("disabled",false);
            _input_habilitado_sede = false;
            _modo_nuevo_sede = false;
            self.dataSede.Sedes.remove(data);
            var tabla = $('#DataTables_Table_0_sede');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarSedes();
          }
        });
      }, 200);
      self.Seleccionar(data,event);

    }

    self.BorrarSede = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cSede/BorrarSede',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarSede");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarSede").prop("disabled",false);
                      self.HabilitarTablaSpanSede(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataSede.Sedes.remove(objeto);
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

    }

    self.OnClickSede = function(data ,event) {

      if(event)
      {
          console.log("OnClickSede");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_sede);
          if( _modo_nuevo_sede == true )
          {

          }
          else
          {

            $("#btnAgregarSede").prop("disabled",true);
            if(_sede.IdSede() !=  data.IdSede())
            {
              if (_input_habilitado_sede == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _sede.Deshacer(null, event);

                //var id_sede = "#" + _id_filasede_anterior;
                var id_sede = "#" + _sede.IdSede()+'_tr_sede';
                self.HabilitarFilaInputSede(id_sede, false);

                var idbutton = "#"+_sede.IdSede()+"_button_Sede";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_sede.IdSede());
              console.log(data.IdSede());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_sede = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_sede, "span") || $.isSubstring(id_fila_sede, "input")){
                id_fila_sede = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              var idinput ="#"+$(id_fila_sede).find('input').attr('id');
              self.HabilitarFilaInputSede("#" + $(id_fila_sede).parent()[0].id, true);

              var idbutton = "#"+data.IdSede()+"_button_Sede";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_sede = true;

              }
              else {
                if (_input_habilitado_sede == false)
                {
                  var id_fila_sede = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_sede, "span") || $.isSubstring(id_fila_sede, "input")){
                    id_fila_sede = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputSede("#" + $(id_fila_sede).parent()[0].id, true);

                  var idbutton = "#"+data.IdSede()+"_button_Sede";
                  var idinput ="#"+$(id_fila_sede).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_sede = true;
                }
                else {
                  console.log("MISMA LNEA");
                }
              }
              self.Seleccionar(data,event)
          }

          return false;
      }

    }

    //Funcion para buscar una palabra en una cadena de texto
    jQuery.isSubstring = function(haystack, needle){
      return haystack.indexOf(needle) !== -1;
    }

    //FUNCION DE MANEJO DE TECLAS Y ATAJOS
    self.OnKeyUpSede = function(data, event){
      if(event)
      {
       console.log("OnKeyUpSede");

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
         var idinputcode = '#' + _sede.IdSede() + '_input_CodigoSede';
          var idinputnombre = '#' + _sede.IdSede() + '_input_NombreSede';
          var idinputdireccion = '#' + _sede.IdSede() + '_input_Direccion';
          var idinputtipo ='#' +  _sede.IdSede() + '_input_IdTipoSede';

          _sede.CodigoSede($(idinputcode).val());
          _sede.NombreSede($(idinputnombre).val());
          _sede.Direccion($(idinputdireccion).val());
          _sede.IdTipoSede($(idinputtipo).val());

         if(_modo_nuevo_sede == true)
         {
           self.InsertarSede(_sede,event);
         }
         else
         {
           self.ActualizarSede(_sede,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado_sede == true)
        {
          if(_modo_nuevo_sede == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_sede);
              self.dataSede.Sedes.remove(_sede);
              var tabla = $('#DataTables_Table_0_sede');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarSede").prop("disabled",false);
              self.HabilitarButtonsSede(null, true);
               _modo_nuevo_sede = false;
               _input_habilitado_sede = false;
            });

          }
          else
          {
            console.log("Escape - false");
             _sede.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarSede").prop("disabled",false);

            /*var id_fila_sede = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_sede, "span") || $.isSubstring(id_fila_sede, "input")){
              id_fila_sede = "#" + $(event.target).parent()[0].id;
            }*/
            self.HabilitarTablaSpanSede(null, true);
            //self.HabilitarFilaInputSede("#" + $(id_fila_sede).parent()[0].id, false);

            var idbutton ="#"+_sede.IdSede()+"_button_Sede";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_sede = false;
            _input_habilitado_sede = false;
          }
        }
      }
    }

    self.GuardarSede = function(data,event) {
      if(event)
      {
         console.log("GuardarSede");
         console.log(_nombresede);

         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
         var idinputcode = '#' + _sede.IdSede() + '_input_CodigoSede';
          var idinputnombre = '#' + _sede.IdSede() + '_input_NombreSede';
          var idinputdireccion = '#' + _sede.IdSede() + '_input_Direccion';

          _sede.CodigoSede($(idinputcode).val());
          _sede.NombreSede($(idinputnombre).val());
          _sede.Direccion($(idinputdireccion).val());

         if(_modo_nuevo_sede == true)
         {
           self.InsertarSede(_sede,event);
         }
         else
         {
           self.ActualizarSede(_sede,event);
         }
      }
      self.Seleccionar(data,event);

    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
