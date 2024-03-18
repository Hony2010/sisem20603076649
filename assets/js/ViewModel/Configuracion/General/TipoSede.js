self.dataTipoSede
TiposSedeModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.VistaOptions = ko.pureComputed(function(){
      return self.IndicadorEstado() == "T" ? "hidden" : "visible";
    }, this);

    self._NombreTipoSede = ko.observable(data.NombreTipoSede);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreTipoSede());

        self.NombreTipoSede.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreTipoSede("");
        self.NombreTipoSede(self._NombreTipoSede());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreTipoSede.valueHasMutated();
        self._NombreTipoSede(self.NombreTipoSede());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreTipoSede());
}

TipoSedeModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'TiposSede': {
        create: function (options) {
            if (options)
              return new TiposSedeModel(options.data);
            }
    },
    'TipoSede': {
        create: function (options) {
            if (options)
              return new TipoSedeModel(options.data);
            }
    }

}

IndexTipoSede = function (data) {

    var _modo_deshacer = false;
    var _nombretiposede;
    var _input_habilitado_tiposede = false;
    var _idtiposede;
    var _tiposede;
    var _modo_nuevo_tiposede = false;
    var _id_filatiposede_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarTiposSede = function() {
        console.log("ListarTiposSede");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cTipoSede/ListarTiposSede',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataTipoSede.TiposSede([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.dataTipoSede.TiposSede.push(new TiposSedeModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_tiposede == false)
      {
        var id = "#"+data.IdTipoSede()+'_tr_tiposede';
        $(id).addClass('active').siblings().removeClass('active');
        _tiposede = data;
      }

    }

    self.FilaButtonsTipoSede = function (data, event)  {
      console.log("BUTTONS");
      console.log("EVENTTARGET: " + $(event.target).attr('class'));
      console.log("THIS: " + $(this).attr('class'));
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_tiposede);
          if(_modo_nuevo_tiposede == true)
          return;

          _tiposede.Deshacer(null, event);
          _input_habilitado_tiposede = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarTipoSede").prop("disabled",false);
          self.HabilitarTablaSpanTipoSede(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdTipoSede()+'_tr_tiposede';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_tiposede == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.dataTipoSede.TiposSede(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdTipoSede();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdTipoSede()+'_tr_tiposede';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_tiposede == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idtiposede = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.dataTipoSede.TiposSede(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idtiposede == item.IdTipoSede();
            });

          if(match)
          {
            _tiposede = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputTipoSede = function (data, option)  {
      //var id = "#"+data.IdTipoSede();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputTipoSede').hide();
        $(id).find('.class_SpanTipoSede').show();
      }
      else
      {
        $(id).find('.class_InputTipoSede').show();
        $(id).find('.class_SpanTipoSede').hide();
      }

    }

    self.HabilitarTablaSpanTipoSede = function (data, option)  {
      //var id = "#"+data.IdTipoSede();
      var id = "#DataTables_Table_0_tiposede";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanTipoSede').hide();
        $(id).find('.class_InputTipoSede').show();
        //$(id).find('.guardar_button_TipoSede').show();
        //_input_habilitado_tiposede = true;
      }
      else {
        $(id).find('.class_SpanTipoSede').show();
        $(id).find('.class_InputTipoSede').hide();
        $(id).find('.guardar_button_TipoSede').hide();
        //_input_habilitado_tiposede = false;
      }

    }

    self.HabilitarButtonsTipoSede = function(data, option){
      var id = "#DataTables_Table_0_tiposede";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_TipoSede').prop("disabled", true);
        $(id).find('.borrar_button_TipoSede').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_TipoSede').prop("disabled", false);
        $(id).find('.borrar_button_TipoSede').prop("disabled", false);
      }
    }


    self.AgregarTipoSede = function(data,event) {
          console.log("AgregarTipoSede");

          if ( _input_habilitado_tiposede == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.dataTipoSede.TipoSede);
            _tiposede = new TiposSedeModel(objeto);
            self.dataTipoSede.TiposSede.push(_tiposede);

            //Deshabilitando buttons
            self.HabilitarButtonsTipoSede(null, false);
            $("#null_editar_button_TipoSede").prop("disabled", true);
            $("#null_borrar_button_TipoSede").prop("disabled", false);


            $("#btnAgregarTipoSede").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdTipoSede());

            var idspan ="#"+objeto.IdTipoSede()+"_span_NombreTipoSede";
            var idinput ="#"+objeto.IdTipoSede()+"_input_NombreTipoSede";

            var idbutton ="#"+objeto.IdTipoSede()+"_button_TipoSede";

            console.log(idbutton);
            //self.HabilitarFilaInputTipoSede(_tiposede, true);
            //self.HabilitarFilaSpanTipoSede(_tiposede, false);

            $(idspan).hide();
            $(idinput).show();
            $(idbutton).show();
            $(idinput).focus();

            _modo_nuevo_tiposede = true;
            _input_habilitado_tiposede = true;

            var tabla = $('#DataTables_Table_0_tiposede');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarTipoSede =function(data,event){

      if(event)
      {
        console.log("InsertarTipoSede");
        console.log(_tiposede.NombreTipoSede());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _tiposede});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cTipoSede/InsertarTipoSede',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarTipoSede");
                      console.log(data);

                      if ($.isNumeric(data.IdTipoSede))
                      {
                        _tiposede.IdTipoSede(data.IdTipoSede);
                        //deshabilitar botones agregar
                        $("#btnAgregarTipoSede").prop("disabled",false);

                        var id_tiposede = "#"+ _tiposede.IdTipoSede()+'_tr_tiposede';
                        self.HabilitarFilaInputTipoSede(id_tiposede, false);

                        var idbutton ="#"+_tiposede.IdTipoSede()+"_button_TipoSede";
                        $(idbutton).hide();

                         _tiposede.Confirmar(null,event);
                         self.HabilitarButtonsTipoSede(null, true);

                        existecambio = false;
                        _input_habilitado_tiposede = false;
                        _modo_nuevo_tiposede = false;

                      }
                      else {
                        alertify.alert(data.IdTipoSede);
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

    self.ActualizarTipoSede = function(data,event) {
          console.log("ActualizarTipoSede");
          console.log(_tiposede.NombreTipoSede());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _tiposede});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cTipoSede/ActualizarTipoSede',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarTipoSede").prop("disabled",false);
                          console.log("ID5:"+_tiposede.IdTipoSede());
                          _tiposede.Confirmar(null,event);

                          var id_tiposede = "#"+ _tiposede.IdTipoSede()+'_tr_tiposede';
                          self.HabilitarFilaInputTipoSede(id_tiposede, false);

                          var idbutton ="#"+_tiposede.IdTipoSede()+"_button_TipoSede";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_tiposede = false;
                          _modo_nuevo_tiposede = false;

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

    self.EditarTipoSede = function(data, event) {

      if(event)
      {
        console.log("EditarTipoSede");
        console.log("ID.:"+data.IdTipoSede());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_tiposede);

        if( _modo_nuevo_tiposede == true )
        {

        }
        else {

          if (_tiposede.IdTipoSede() == data.IdTipoSede())
          {

            if (_input_habilitado_tiposede == true)
            {
              $("#btnAgregarTipoSede").prop("disabled",false);
              data.Deshacer(null,event);
              var id_tiposede = "#"+ data.IdTipoSede()+'_tr_tiposede';
              self.HabilitarFilaInputTipoSede(id_tiposede, false);

              var idbutton = "#"+_tiposede.IdTipoSede()+"_button_TipoSede";
              $(idbutton).hide();

              _input_habilitado_tiposede =false;


            }
            else {
              $("#btnAgregarTipoSede").prop("disabled",true);
              var id_tiposede = "#"+ data.IdTipoSede()+'_tr_tiposede';
              self.HabilitarFilaInputTipoSede(id_tiposede, true);

              var idbutton = "#"+data.IdTipoSede()+"_button_TipoSede";

              var idinput = "#"+data.IdTipoSede()+"_input_NombreTipoSede";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_tiposede = true;
            }

          }
          else {
            $("#btnAgregarTipoSede").prop("disabled",true);
            if( _input_habilitado_tiposede == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_tiposede.IdTipoSede());

              var id_tiposede = "#"+ _tiposede.IdTipoSede()+'_tr_tiposede';
              self.HabilitarFilaInputTipoSede(id_tiposede, false);

              var idbutton = "#"+_tiposede.IdTipoSede()+"_button_TipoSede";

              _tiposede.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_tiposede = "#"+ data.IdTipoSede()+'_tr_tiposede';
            self.HabilitarFilaInputTipoSede(id_tiposede, true);

            var idbutton = "#"+data.IdTipoSede()+"_button_TipoSede";

            var idinput = "#"+data.IdTipoSede()+"_input_NombreTipoSede";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_tiposede = true;
          }


        }

      }

    }

    self.PreBorrarTipoSede = function (data) {

      if(_modo_nuevo_tiposede == false)
      {
        _tiposede.Deshacer(null, event);
        _input_habilitado_tiposede = false;
        $("#btnAgregarTipoSede").prop("disabled",false);
        self.HabilitarTablaSpanTipoSede(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarTipoSede");
          console.log(data.IdTipoSede());
          self.HabilitarButtonsTipoSede(null, true);
          if (data.IdTipoSede() != null)
          {
            self.BorrarTipoSede(data);
          }
          else
          {
            $("#btnAgregarTipoSede").prop("disabled",false);
            _input_habilitado_tiposede = false;
            _modo_nuevo_tiposede = false;
            self.dataTipoSede.TiposSede.remove(data);
            var tabla = $('#DataTables_Table_0_tiposede');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarTiposSede();
          }
        });
      }, 200);

    }

    self.BorrarTipoSede = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cTipoSede/BorrarTipoSede',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarTipoSede").prop("disabled",false);
                      self.HabilitarTablaSpanTipoSede(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataTipoSede.TiposSede.remove(objeto);
                        //self.ListarTiposSede();
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


    self.OnClickTipoSede = function(data ,event) {

      if(event)
      {
          console.log("OnClickTipoSede");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_tiposede);
          if(data.IndicadorEstado() == "T" && _modo_nuevo_tiposede == false)
          {
            if(_input_habilitado_tiposede==false)
              return;

            _tiposede.Deshacer(null, event);
            _input_habilitado_tiposede = false;
            self.HabilitarTablaSpanFamiliaProducto(null, true);
          }
          else {
            if( _modo_nuevo_tiposede == true )
            {

            }
            else
            {

              $("#btnAgregarTipoSede").prop("disabled",true);
              if(_tiposede.IdTipoSede() !=  data.IdTipoSede())
              {
                if (_input_habilitado_tiposede == true)
                {
                  console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                  _tiposede.Deshacer(null, event);

                  //var id_tiposede = "#" + _id_filatiposede_anterior;
                  var id_tiposede = "#" + _tiposede.IdTipoSede()+'_tr_tiposede';
                  self.HabilitarFilaInputTipoSede(id_tiposede, false);

                  var idbutton = "#"+_tiposede.IdTipoSede()+"_button_TipoSede";
                  $(idbutton).hide();
                }

                console.log("INPUT ESTA HABILITADO Y PASO 2");
                console.log(_tiposede.IdTipoSede());
                console.log(data.IdTipoSede());
                //habilitar campo destino
                //Obteniendo ID de la fila para usarlo con los span e inputs
                var id_fila_tiposede = "#" + $(event.target).attr('id');
                //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                if($.isSubstring(id_fila_tiposede, "span") || $.isSubstring(id_fila_tiposede, "input")){
                  id_fila_tiposede = "#" + $(event.target).parent()[0].id;
                }
                //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
                //_id_filatiposede_anterior = $(id_fila_tiposede).parent()[0].id;
                var idspan ="#"+$(id_fila_tiposede).find('span').attr('id');
                var idinput ="#"+$(id_fila_tiposede).find('input').attr('id');
                self.HabilitarFilaInputTipoSede("#" + $(id_fila_tiposede).parent()[0].id, true);

                var idbutton = "#"+data.IdTipoSede()+"_button_TipoSede";

                $(idinput).focus();
                $(idbutton).show();

                _input_habilitado_tiposede = true;

              }
              else {
                if (_input_habilitado_tiposede == false)
                {
                  var id_fila_tiposede = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_tiposede, "span") || $.isSubstring(id_fila_tiposede, "input")){
                    id_fila_tiposede = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputTipoSede("#" + $(id_fila_tiposede).parent()[0].id, true);

                  var idbutton = "#"+data.IdTipoSede()+"_button_TipoSede";
                  var idinput ="#"+$(id_fila_tiposede).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_tiposede = true;
                }
                else {
                  console.log("MISMA LNEA");
                }
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
    self.OnKeyUpTipoSede = function(data, event){
      if(event)
      {
       console.log("OnKeyUpTipoSede");

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
         var idinputnombre = _tiposede.IdTipoSede() + '_input_NombreTipoSede';

         if(event.target.id == idinputnombre)
         {
           _tiposede.NombreTipoSede(event.target.value);
         }


         if(_modo_nuevo_tiposede == true)
         {
           self.InsertarTipoSede(_tiposede,event);
         }
         else
         {
           self.ActualizarTipoSede(_tiposede,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event)
    {
      if(event)
      {
        if(_input_habilitado_tiposede == true)
        {
          if(_modo_nuevo_tiposede == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_tiposede);
              self.dataTipoSede.TiposSede.remove(_tiposede);
              var tabla = $('#self.dataTipoSede');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarTipoSede").prop("disabled",false);
              self.HabilitarButtonsTipoSede(null, true);
               _modo_nuevo_tiposede = false;
               _input_habilitado_tiposede = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_tiposede._NombreTipoSede());
            //revertir texto
            //data.NombreTipoSede(_tiposede.NombreTipoSede());

             _tiposede.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarTipoSede").prop("disabled",false);

            /*var id_fila_tiposede = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_tiposede, "span") || $.isSubstring(id_fila_tiposede, "input")){
              id_fila_tiposede = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputTipoSede("#" + $(id_fila_tiposede).parent()[0].id, false);*/
            self.HabilitarTablaSpanTipoSede(null, true);

            var idbutton ="#"+_tiposede.IdTipoSede()+"_button_TipoSede";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_tiposede = false;
            _input_habilitado_tiposede = false;
          }
        }
      }
    }

    self.GuardarTipoSede = function(data,event) {
      if(event)
      {
         console.log("GuardarTipoSede");
         console.log(_nombretiposede);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _tiposede.IdTipoSede() + '_input_NombreTipoSede';

          if(event.target.id == idinputnombre)
          {
            _tiposede.NombreTipoSede(_nombretiposede);
          }
         //_tiposede.NombreTipoSede(_nombretiposede);

         if(_modo_nuevo_tiposede == true)
         {
           self.InsertarTipoSede(_tiposede,event);
         }
         else
         {
           self.ActualizarTipoSede(_tiposede,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
