RegimenesTributarioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreRegimenTributario = ko.observable(data.NombreRegimenTributario);
    self._NombreAbreviado = ko.observable(data.NombreAbreviado);

    self.VistaOptions = ko.pureComputed(function(){
      return self.IndicadorEstado() == "T" ? "hidden" : "visible";
    }, this);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreRegimenTributario());

        self.NombreRegimenTributario.valueHasMutated();
        self.NombreAbreviado.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreRegimenTributario("");
        self.NombreAbreviado("");
        self.NombreRegimenTributario(self._NombreRegimenTributario());
        self.NombreAbreviado(self._NombreAbreviado());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreRegimenTributario.valueHasMutated();
        self._NombreRegimenTributario(self.NombreRegimenTributario());
        self._NombreAbreviado.valueHasMutated();
        self._NombreAbreviado(self.NombreAbreviado());

      }
    }

}

RegimenTributarioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'RegimenesTributario': {
        create: function (options) {
            if (options)
              return new RegimenesTributarioModel(options.data);
            }
    },
    'RegimenTributario': {
        create: function (options) {
            if (options)
              return new RegimenTributarioModel(options.data);
            }
    }

}

IndexRegimenTributario = function (data) {

    var _modo_deshacer = false;
    var _nombreabreviado;
    var _nombreregimentributario;
    var _input_habilitado_regimentributario = false;
    var _idregimentributario;
    var _regimentributario;
    var _modo_nuevo_regimentributario = false;
    var _id_filaregimentributario_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarRegimenesTributario = function() {
        console.log("ListarRegimenesTributario");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cRegimenTributario/ListarRegimenesTributario',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataRegimenTributario.RegimenesTributario([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.dataRegimenTributario.RegimenesTributario.push(new RegimenesTributarioModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_regimentributario == false)
      {
        var id = "#"+data.IdRegimenTributario()+'_tr_regimentributario';
        $(id).addClass('active').siblings().removeClass('active');
        _regimentributario = data;
      }

    }

    self.FilaButtonsRegimenTributario = function (data, event)  {
      console.log("FILASBUTONES");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_regimentributario);
          if(_modo_nuevo_regimentributario == true)
          return;

          _regimentributario.Deshacer(null, event);
          _input_habilitado_regimentributario = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarRegimenTributario").prop("disabled",false);
          self.HabilitarTablaSpanRegimenTributario(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdRegimenTributario()+'_tr_regimentributario';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_regimentributario == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.dataRegimenTributario.RegimenesTributario(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdRegimenTributario();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdRegimenTributario()+'_tr_regimentributario';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_regimentributario == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idregimentributario = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.dataRegimenTributario.RegimenesTributario(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idregimentributario == item.IdRegimenTributario();
            });

          if(match)
          {
            _regimentributario = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputRegimenTributario = function (data, option)  {
      //var id = "#"+data.IdRegimenTributario();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputRegimenTributario').hide();
        $(id).find('.class_SpanRegimenTributario').show();
      }
      else
      {
        $(id).find('.class_InputRegimenTributario').show();
        $(id).find('.class_SpanRegimenTributario').hide();
      }

    }

    self.HabilitarTablaSpanRegimenTributario = function (data, option)  {
      //var id = "#"+data.IdRegimenTributario();
      var id = "#DataTables_Table_0_regimentributario";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanRegimenTributario').hide();
        $(id).find('.class_InputRegimenTributario').show();
        //$(id).find('.guardar_button_RegimenTributario').show();
        //_input_habilitado_regimentributario = true;
      }
      else {
        $(id).find('.class_SpanRegimenTributario').show();
        $(id).find('.class_InputRegimenTributario').hide();
        $(id).find('.guardar_button_RegimenTributario').hide();
        //_input_habilitado_regimentributario = false;
      }

    }

    self.HabilitarButtonsRegimenTributario = function(data, option){
      var id = "#DataTables_Table_0_regimentributario";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_RegimenTributario').prop("disabled", true);
        $(id).find('.borrar_button_RegimenTributario').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_RegimenTributario').prop("disabled", false);
        $(id).find('.borrar_button_RegimenTributario').prop("disabled", false);
      }
    }


    self.AgregarRegimenTributario = function(data,event) {
          console.log("AgregarRegimenTributario");

          if ( _input_habilitado_regimentributario == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.dataRegimenTributario.RegimenTributario);
            _regimentributario = new RegimenesTributarioModel(objeto);
            self.dataRegimenTributario.RegimenesTributario.push(_regimentributario);

            //Deshabilitando buttons
            self.HabilitarButtonsRegimenTributario(null, false);
            $("#null_editar_button_RegimenTributario").prop("disabled", true);
            $("#null_borrar_button_RegimenTributario").prop("disabled", false);


            $("#btnAgregarRegimenTributario").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdRegimenTributario());

            var id_span_nombreregimentributario ="#"+objeto.IdRegimenTributario()+"_span_NombreRegimenTributario";
            var id_input_nombreregimentributario ="#"+objeto.IdRegimenTributario()+"_input_NombreRegimenTributario";

            var id_span_nombreabreviado ="#"+objeto.IdRegimenTributario()+"_span_NombreAbreviado";
            var id_input_nombreabreviado ="#"+objeto.IdRegimenTributario()+"_input_NombreAbreviado";

            var idbutton ="#"+objeto.IdRegimenTributario()+"_button_RegimenTributario";

            console.log(idbutton);

            $(id_span_nombreabreviado).hide();
            $(id_input_nombreabreviado).show();

            $(id_span_nombreregimentributario).hide();
            $(id_input_nombreregimentributario).show();

            $(idbutton).show();
            $(id_input_nombreregimentributario).focus();

            _modo_nuevo_regimentributario = true;
            _input_habilitado_regimentributario = true;

            var tabla = $('#DataTables_Table_0_regimentributario');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarRegimenTributario =function(data,event){

      if(event)
      {
        console.log("InsertarRegimenTributario");
        console.log(_regimentributario.NombreRegimenTributario());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _regimentributario});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cRegimenTributario/InsertarRegimenTributario',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarRegimenTributario");
                      console.log(data);

                      if ($.isNumeric(data.IdRegimenTributario))
                      {
                        _regimentributario.IdRegimenTributario(data.IdRegimenTributario);
                        //deshabilitar botones agregar
                        $("#btnAgregarRegimenTributario").prop("disabled",false);

                        var id_regimentributario = "#"+ _regimentributario.IdRegimenTributario()+'_tr_regimentributario';
                        self.HabilitarFilaInputRegimenTributario(id_regimentributario, false);

                        var idbutton ="#"+_regimentributario.IdRegimenTributario()+"_button_RegimenTributario";
                        $(idbutton).hide();

                         _regimentributario.Confirmar(null,event);
                         self.HabilitarButtonsRegimenTributario(null, true);

                        existecambio = false;
                        _input_habilitado_regimentributario = false;
                        _modo_nuevo_regimentributario = false;

                      }
                      else {
                        alertify.alert(data.IdRegimenTributario);
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

    self.ActualizarRegimenTributario = function(data,event) {
          console.log("ActualizarRegimenTributario");
          console.log(_regimentributario.NombreRegimenTributario());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _regimentributario});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cRegimenTributario/ActualizarRegimenTributario',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarRegimenTributario").prop("disabled",false);
                          console.log("ID5:"+_regimentributario.IdRegimenTributario());
                          _regimentributario.Confirmar(null,event);

                          var id_regimentributario = "#"+ _regimentributario.IdRegimenTributario()+'_tr_regimentributario';
                          self.HabilitarFilaInputRegimenTributario(id_regimentributario, false);

                          var idbutton ="#"+_regimentributario.IdRegimenTributario()+"_button_RegimenTributario";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_regimentributario = false;
                          _modo_nuevo_regimentributario = false;

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

    self.EditarRegimenTributario = function(data, event) {

      if(event)
      {
        console.log("EditarRegimenTributario");
        console.log("ID.:"+data.IdRegimenTributario());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_regimentributario);

        if( _modo_nuevo_regimentributario == true )
        {

        }
        else {

          if (_regimentributario.IdRegimenTributario() == data.IdRegimenTributario())
          {

            if (_input_habilitado_regimentributario == true)
            {
              $("#btnAgregarRegimenTributario").prop("disabled",false);
              data.Deshacer(null,event);
              var id_regimentributario = "#"+ data.IdRegimenTributario()+'_tr_regimentributario';
              self.HabilitarFilaInputRegimenTributario(id_regimentributario, false);

              var idbutton = "#"+_regimentributario.IdRegimenTributario()+"_button_RegimenTributario";
              $(idbutton).hide();

              _input_habilitado_regimentributario =false;

            }
            else {
              $("#btnAgregarRegimenTributario").prop("disabled",true);
              var id_regimentributario = "#"+ data.IdRegimenTributario()+'_tr_regimentributario';
              self.HabilitarFilaInputRegimenTributario(id_regimentributario, true);

              var idbutton = "#"+data.IdRegimenTributario()+"_button_RegimenTributario";

              var idinput = "#"+data.IdRegimenTributario()+"_input_NombreRegimenTributario";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_regimentributario = true;
            }

          }
          else {
            $("#btnAgregarRegimenTributario").prop("disabled",true);
            if( _input_habilitado_regimentributario == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_regimentributario.IdRegimenTributario());

              var id_regimentributario = "#"+ _regimentributario.IdRegimenTributario()+'_tr_regimentributario';
              self.HabilitarFilaInputRegimenTributario(id_regimentributario, false);

              var idbutton = "#"+_regimentributario.IdRegimenTributario()+"_button_RegimenTributario";

              _regimentributario.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_regimentributario = "#"+ data.IdRegimenTributario()+'_tr_regimentributario';
            self.HabilitarFilaInputRegimenTributario(id_regimentributario, true);

            var idbutton = "#"+data.IdRegimenTributario()+"_button_RegimenTributario";

            var idinput = "#"+data.IdRegimenTributario()+"_input_NombreRegimenTributario";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_regimentributario = true;
          }


        }

      }

    }

    self.PreBorrarRegimenTributario = function (data) {

      if(_modo_nuevo_regimentributario == false)
      {
      _regimentributario.Deshacer(null, event);
      _input_habilitado_regimentributario = false;
      $("#btnAgregarRegimenTributario").prop("disabled",false);
      self.HabilitarTablaSpanRegimenTributario(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarRegimenTributario");
          console.log(data.IdRegimenTributario());
          self.HabilitarButtonsRegimenTributario(null, true);
          if (data.IdRegimenTributario() != null)
            self.BorrarRegimenTributario(data);
          else
          {
            $("#btnAgregarRegimenTributario").prop("disabled",false);
            _input_habilitado_regimentributario = false;
            _modo_nuevo_regimentributario = false;
            self.dataRegimenTributario.RegimenesTributario.remove(data);
            var tabla = $('#DataTables_Table_0_regimentributario');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarRegimenesTributario();
          }
        });

      }, 200);

    }

    self.BorrarRegimenTributario = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cRegimenTributario/BorrarRegimenTributario',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarRegimenTributario").prop("disabled",false);
                      self.HabilitarTablaSpanRegimenTributario(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataRegimenTributario.RegimenesTributario.remove(objeto);
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


    self.OnClickRegimenTributario = function(data ,event) {

      if(event)
      {
          console.log("OnClickRegimenTributario");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_regimentributario);

          if( _modo_nuevo_regimentributario == true )
          {

          }
          else
          {

            $("#btnAgregarRegimenTributario").prop("disabled",true);
            if(_regimentributario.IdRegimenTributario() !=  data.IdRegimenTributario())
            {
              if (_input_habilitado_regimentributario == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _regimentributario.Deshacer(null, event);

                //var id_regimentributario = "#" + _id_filaregimentributario_anterior;
                var id_regimentributario = "#" + _regimentributario.IdRegimenTributario()+'_tr_regimentributario';
                self.HabilitarFilaInputRegimenTributario(id_regimentributario, false);

                var idbutton = "#"+_regimentributario.IdRegimenTributario()+"_button_RegimenTributario";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_regimentributario.IdRegimenTributario());
              console.log(data.IdRegimenTributario());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_regimentributario = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_regimentributario, "span") || $.isSubstring(id_fila_regimentributario, "input")){
                id_fila_regimentributario = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              var idinput ="#"+$(id_fila_regimentributario).find('input').attr('id');
              self.HabilitarFilaInputRegimenTributario("#" + $(id_fila_regimentributario).parent()[0].id, true);

              var idbutton = "#"+data.IdRegimenTributario()+"_button_RegimenTributario";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_regimentributario = true;

              }
              else {
                if (_input_habilitado_regimentributario == false)
                {
                  var id_fila_regimentributario = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_regimentributario, "span") || $.isSubstring(id_fila_regimentributario, "input")){
                    id_fila_regimentributario = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputRegimenTributario("#" + $(id_fila_regimentributario).parent()[0].id, true);

                  var idbutton = "#"+data.IdRegimenTributario()+"_button_RegimenTributario";
                  var idinput ="#"+$(id_fila_regimentributario).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_regimentributario = true;
                }
                else {
                  console.log("MISMA LNEA");
                }
              }

          }

          return false;
      }

    }

    //Funcion para buscar una palabra en una cadena de texto
    jQuery.isSubstring = function(haystack, needle){
      return haystack.indexOf(needle) !== -1;
    }

    //FUNCION DE MANEJO DE TECLAS Y ATAJOS
    self.OnKeyUpRegimenTributario = function(data, event){
      if(event)
      {
       console.log("OnKeyUpRegimenTributario");

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
         var idinputnombre = _regimentributario.IdRegimenTributario() + '_input_NombreRegimenTributario';
         var idinputnombreabreviado = _regimentributario.IdRegimenTributario() + '_input_NombreAbreviado';

         if(event.target.id == idinputnombre)
         {
           _regimentributario.NombreRegimenTributario(event.target.value);
         }
         else if(event.target.id == idinputnombreabreviado)
         {
            _regimentributario.NombreAbreviado(event.target.value);
         }


         if(_modo_nuevo_regimentributario == true)
         {
           self.InsertarRegimenTributario(_regimentributario,event);
         }
         else
         {
           self.ActualizarRegimenTributario(_regimentributario,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado_regimentributario == true)
        {
          if(_modo_nuevo_regimentributario == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_regimentributario);
              self.dataRegimenTributario.RegimenesTributario.remove(_regimentributario);
              var tabla = $('#DataTables_Table_0');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarRegimenTributario").prop("disabled",false);
              self.HabilitarButtonsRegimenTributario(null, true);
               _modo_nuevo_regimentributario = false;
               _input_habilitado_regimentributario = false;
            });
          }
          else
          {
            console.log("Escape - false");
            //revertir texto

             _regimentributario.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarRegimenTributario").prop("disabled",false);

            /*var id_fila_regimentributario = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_regimentributario, "span") || $.isSubstring(id_fila_regimentributario, "input")){
              id_fila_regimentributario = "#" + $(event.target).parent()[0].id;
            }

            self.HabilitarFilaInputRegimenTributario("#" + $(id_fila_regimentributario).parent()[0].id, false);*/
            self.HabilitarTablaSpanRegimenTributario(null, true);

            var idbutton ="#"+_regimentributario.IdRegimenTributario()+"_button_RegimenTributario";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_regimentributario = false;
            _input_habilitado_regimentributario = false;
          }
        }
      }
    }

    self.GuardarRegimenTributario = function(data,event) {
      if(event)
      {
         console.log("GuardarRegimenTributario");
         console.log(_nombreregimentributario);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _regimentributario.IdRegimenTributario() + '_input_NombreRegimenTributario';
          var idinputnombreabreviado = _regimentributario.IdRegimenTributario() + '_input_NombreAbreviado';

          if(event.target.id == idinputnombre)
          {
            _regimentributario.NombreRegimenTributario(_nombreregimentributario);
          }
          else if(event.target.id == idinputnombreabreviado)
          {
             _regimentributario.NombreAbreviado(_nombreabreviado);
          }
         //_regimentributario.NombreRegimenTributario(_nombreregimentributario);

         if(_modo_nuevo_regimentributario == true)
         {
           self.InsertarRegimenTributario(_regimentributario,event);
         }
         else
         {
           self.ActualizarRegimenTributario(_regimentributario,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
