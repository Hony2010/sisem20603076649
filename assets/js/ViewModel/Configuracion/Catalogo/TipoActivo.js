
TiposActivoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreTipoActivo = ko.observable(data.NombreTipoActivo);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreTipoActivo());

        self.NombreTipoActivo.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreTipoActivo("");
        self.NombreTipoActivo(self._NombreTipoActivo());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreTipoActivo.valueHasMutated();
        self._NombreTipoActivo(self.NombreTipoActivo());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreTipoActivo());
}

TipoActivoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'TiposActivo': {
        create: function (options) {
            if (options)
              return new TiposActivoModel(options.data);
            }
    },
    'TipoActivo': {
        create: function (options) {
            if (options)
              return new TipoActivoModel(options.data);
            }
    }

}

Index = function (data) {

    var _modo_deshacer = false;
    var _nombretipoactivo;
    var _input_habilitado = false;
    var _idtipoactivo;
    var _tipoactivo;
    var _modo_nuevo = false;
    var _id_filatipoactivo_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarTiposActivo = function() {
        console.log("ListarTiposActivo");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cTipoActivo/ListarTiposActivo',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.data.TiposActivo([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.data.TiposActivo.push(new TiposActivoModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo == false)
      {
        var id = "#"+data.IdTipoActivo();
        $(id).addClass('active').siblings().removeClass('active');
        _tipoactivo = data;
      }

    }

    self.FilaButtonsTipoActivo = function (data, event)  {
      console.log("BUTTONS");
      console.log("EVENTTARGET: " + $(event.target).attr('class'));
      console.log("THIS: " + $(this).attr('class'));
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo);
          if(_modo_nuevo == true)
          return;

          _tipoactivo.Deshacer(null, event);
          _input_habilitado = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarTipoActivo").prop("disabled",false);
          self.HabilitarTablaSpanTipoActivo(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdTipoActivo();
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.data.TiposActivo(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdTipoActivo();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdTipoActivo();
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idtipoactivo = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.data.TiposActivo(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idtipoactivo == item.IdTipoActivo();
            });

          if(match)
          {
            _tipoactivo = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputTipoActivo = function (data, option)  {
      //var id = "#"+data.IdTipoActivo();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputTipoActivo').hide();
        $(id).find('.class_SpanTipoActivo').show();
      }
      else
      {
        $(id).find('.class_InputTipoActivo').show();
        $(id).find('.class_SpanTipoActivo').hide();
      }

    }

    self.HabilitarTablaSpanTipoActivo = function (data, option)  {
      //var id = "#"+data.IdTipoActivo();
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanTipoActivo').hide();
        $(id).find('.class_InputTipoActivo').show();
        //$(id).find('.guardar_button_TipoActivo').show();
        //_input_habilitado = true;
      }
      else {
        $(id).find('.class_SpanTipoActivo').show();
        $(id).find('.class_InputTipoActivo').hide();
        $(id).find('.guardar_button_TipoActivo').hide();
        //_input_habilitado = false;
      }

    }

    self.HabilitarButtonsTipoActivo = function(data, option){
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_TipoActivo').prop("disabled", true);
        $(id).find('.borrar_button_TipoActivo').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_TipoActivo').prop("disabled", false);
        $(id).find('.borrar_button_TipoActivo').prop("disabled", false);
      }
    }


    self.AgregarTipoActivo = function(data,event) {
          console.log("AgregarTipoActivo");

          if ( _input_habilitado == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.data.TipoActivo);
            _tipoactivo = new TiposActivoModel(objeto);
            self.data.TiposActivo.push(_tipoactivo);

            //Deshabilitando buttons
            self.HabilitarButtonsTipoActivo(null, false);
            $("#null_editar_button_TipoActivo").prop("disabled", true);
            $("#null_borrar_button_TipoActivo").prop("disabled", false);


            $("#btnAgregarTipoActivo").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdTipoActivo());

            var idspan ="#"+objeto.IdTipoActivo()+"_span_NombreTipoActivo";
            var idinput ="#"+objeto.IdTipoActivo()+"_input_NombreTipoActivo";

            var idbutton ="#"+objeto.IdTipoActivo()+"_button_TipoActivo";

            console.log(idbutton);
            //self.HabilitarFilaInputTipoActivo(_tipoactivo, true);
            //self.HabilitarFilaSpanTipoActivo(_tipoactivo, false);

            $(idspan).hide();
            $(idinput).show();
            $(idbutton).show();
            $(idinput).focus();

            _modo_nuevo = true;
            _input_habilitado = true;

            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarTipoActivo =function(data,event){

      if(event)
      {
        $("#loader").show();
        console.log("InsertarTipoActivo");
        console.log(_tipoactivo.NombreTipoActivo());

        var objeto = data;
        var datajs = ko.toJS({"Data" : _tipoactivo});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cTipoActivo/InsertarTipoActivo',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarTipoActivo");
                      console.log(data);

                      if ($.isNumeric(data.IdTipoActivo))
                      {
                        _tipoactivo.IdTipoActivo(data.IdTipoActivo);
                        //deshabilitar botones agregar
                        $("#btnAgregarTipoActivo").prop("disabled",false);

                        var id_tipoactivo = "#"+ _tipoactivo.IdTipoActivo();
                        self.HabilitarFilaInputTipoActivo(id_tipoactivo, false);

                        var idbutton ="#"+_tipoactivo.IdTipoActivo()+"_button_TipoActivo";
                        $(idbutton).hide();

                         _tipoactivo.Confirmar(null,event);
                         self.HabilitarButtonsTipoActivo(null, true);

                        existecambio = false;
                        _input_habilitado = false;
                        _modo_nuevo = false;

                      }
                      else {
                        alertify.alert(data.IdTipoActivo);
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

    self.ActualizarTipoActivo = function(data,event) {
          console.log("ActualizarTipoActivo");
          console.log(_tipoactivo.NombreTipoActivo());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _tipoactivo});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/Catalogo/cTipoActivo/ActualizarTipoActivo',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarTipoActivo").prop("disabled",false);
                          console.log("ID5:"+_tipoactivo.IdTipoActivo());
                          _tipoactivo.Confirmar(null,event);

                          var id_tipoactivo = "#"+ _tipoactivo.IdTipoActivo();
                          self.HabilitarFilaInputTipoActivo(id_tipoactivo, false);

                          var idbutton ="#"+_tipoactivo.IdTipoActivo()+"_button_TipoActivo";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado = false;
                          _modo_nuevo = false;

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

    self.EditarTipoActivo = function(data, event) {

      if(event)
      {
        console.log("EditarTipoActivo");
        console.log("ID.:"+data.IdTipoActivo());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_tipoactivo);

        if( _modo_nuevo == true )
        {

        }
        else {

          if (_tipoactivo.IdTipoActivo() == data.IdTipoActivo())
          {

            if (_input_habilitado == true)
            {
              $("#btnAgregarTipoActivo").prop("disabled",false);
              data.Deshacer(null,event);
              var id_tipoactivo = "#"+ data.IdTipoActivo();
              self.HabilitarFilaInputTipoActivo(id_tipoactivo, false);

              var idbutton = "#"+_tipoactivo.IdTipoActivo()+"_button_TipoActivo";
              $(idbutton).hide();

              _input_habilitado =false;


            }
            else {
              $("#btnAgregarTipoActivo").prop("disabled",true);
              var id_tipoactivo = "#"+ data.IdTipoActivo();
              self.HabilitarFilaInputTipoActivo(id_tipoactivo, true);

              var idbutton = "#"+data.IdTipoActivo()+"_button_TipoActivo";

              var idinput = "#"+data.IdTipoActivo()+"_input_NombreTipoActivo";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;
            }

          }
          else {
            $("#btnAgregarTipoActivo").prop("disabled",true);
            if( _input_habilitado == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_tipoactivo.IdTipoActivo());

              var id_tipoactivo = "#"+ _tipoactivo.IdTipoActivo();
              self.HabilitarFilaInputTipoActivo(id_tipoactivo, false);

              var idbutton = "#"+_tipoactivo.IdTipoActivo()+"_button_TipoActivo";

              _tipoactivo.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_tipoactivo = "#"+ data.IdTipoActivo();
            self.HabilitarFilaInputTipoActivo(id_tipoactivo, true);

            var idbutton = "#"+data.IdTipoActivo()+"_button_TipoActivo";

            var idinput = "#"+data.IdTipoActivo()+"_input_NombreTipoActivo";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado = true;
          }


        }

      }

    }

    self.PreBorrarTipoActivo = function (data) {

      if(_modo_nuevo == false)
      {
        _tipoactivo.Deshacer(null, event);
        _input_habilitado = false;
        $("#btnAgregarTipoActivo").prop("disabled",false);
        self.HabilitarTablaSpanTipoActivo(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarTipoActivo");
          console.log(data.IdTipoActivo());
          self.HabilitarButtonsTipoActivo(null, true);
          if (data.IdTipoActivo() != null)
          {
            self.BorrarTipoActivo(data);
          }
          else
          {
            $("#btnAgregarTipoActivo").prop("disabled",false);
            _input_habilitado = false;
            _modo_nuevo = false;
            self.data.TiposActivo.remove(data);
            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarTiposActivo();
          }
        });
      }, 200);

    }

    self.BorrarTipoActivo = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cTipoActivo/BorrarTipoActivo',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarTipoActivo").prop("disabled",false);
                      self.HabilitarTablaSpanTipoActivo(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.data.TiposActivo.remove(objeto);
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


    self.OnClickTipoActivo = function(data ,event) {

      if(event)
      {
          console.log("OnClickTipoActivo");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_tipoactivo);

          if( _modo_nuevo == true )
          {

          }
          else
          {

            $("#btnAgregarTipoActivo").prop("disabled",true);
            if(_tipoactivo.IdTipoActivo() !=  data.IdTipoActivo())
            {
              if (_input_habilitado == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _tipoactivo.Deshacer(null, event);

                //var id_tipoactivo = "#" + _id_filatipoactivo_anterior;
                var id_tipoactivo = "#" + _tipoactivo.IdTipoActivo();
                self.HabilitarFilaInputTipoActivo(id_tipoactivo, false);

                var idbutton = "#"+_tipoactivo.IdTipoActivo()+"_button_TipoActivo";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_tipoactivo.IdTipoActivo());
              console.log(data.IdTipoActivo());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_tipoactivo = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_tipoactivo, "span") || $.isSubstring(id_fila_tipoactivo, "input")){
                id_fila_tipoactivo = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filatipoactivo_anterior = $(id_fila_tipoactivo).parent()[0].id;
              var idspan ="#"+$(id_fila_tipoactivo).find('span').attr('id');
              var idinput ="#"+$(id_fila_tipoactivo).find('input').attr('id');
              self.HabilitarFilaInputTipoActivo("#" + $(id_fila_tipoactivo).parent()[0].id, true);

              var idbutton = "#"+data.IdTipoActivo()+"_button_TipoActivo";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;

              }
              else {
                if (_input_habilitado == false)
                {
                  var id_fila_tipoactivo = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_tipoactivo, "span") || $.isSubstring(id_fila_tipoactivo, "input")){
                    id_fila_tipoactivo = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputTipoActivo("#" + $(id_fila_tipoactivo).parent()[0].id, true);

                  var idbutton = "#"+data.IdTipoActivo()+"_button_TipoActivo";
                  var idinput ="#"+$(id_fila_tipoactivo).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado = true;
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
    self.OnKeyUpTipoActivo = function(data, event){
      if(event)
      {
       console.log("OnKeyUpTipoActivo");

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
         var idinputnombre = _tipoactivo.IdTipoActivo() + '_input_NombreTipoActivo';

         if(event.target.id == idinputnombre)
         {
           _tipoactivo.NombreTipoActivo(event.target.value);
         }


         if(_modo_nuevo == true)
         {
           self.InsertarTipoActivo(_tipoactivo,event);
         }
         else
         {
           self.ActualizarTipoActivo(_tipoactivo,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado == true)
        {
          if(_modo_nuevo == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_tipoactivo);
              self.data.TiposActivo.remove(_tipoactivo);
              var tabla = $('#DataTables_Table_0');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarTipoActivo").prop("disabled",false);
              self.HabilitarButtonsTipoActivo(null, true);
               _modo_nuevo = false;
               _input_habilitado = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_tipoactivo._NombreTipoActivo());
            //revertir texto
            //data.NombreTipoActivo(_tipoactivo.NombreTipoActivo());

             _tipoactivo.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarTipoActivo").prop("disabled",false);

            /*var id_fila_tipoactivo = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_tipoactivo, "span") || $.isSubstring(id_fila_tipoactivo, "input")){
              id_fila_tipoactivo = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputTipoActivo("#" + $(id_fila_tipoactivo).parent()[0].id, false);*/
            self.HabilitarTablaSpanTipoActivo(null, true);

            var idbutton ="#"+_tipoactivo.IdTipoActivo()+"_button_TipoActivo";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo = false;
            _input_habilitado = false;
          }

        }
      }
    }

    self.GuardarTipoActivo = function(data,event) {
      if(event)
      {
         console.log("GuardarTipoActivo");
         console.log(_nombretipoactivo);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _tipoactivo.IdTipoActivo() + '_input_NombreTipoActivo';

          if(event.target.id == idinputnombre)
          {
            _tipoactivo.NombreTipoActivo(_nombretipoactivo);
          }
         //_tipoactivo.NombreTipoActivo(_nombretipoactivo);

         if(_modo_nuevo == true)
         {
           self.InsertarTipoActivo(_tipoactivo,event);
         }
         else
         {
           self.ActualizarTipoActivo(_tipoactivo,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
