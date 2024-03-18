
RolesModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreRol = ko.observable(data.NombreRol);
    self._IdTipoRol = ko.observable(data.IdTipoRol);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreRol());

        self.NombreRol.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreRol("");
        self.NombreRol(self._NombreRol());
        self.IdTipoRol.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.IdTipoRol("");
        self.IdTipoRol(self._IdTipoRol());

        var id_tiporol = '#' + self.IdRol() +  '_input_IdTipoRol';
        // $(id_tiporol).selectpicker("refresh");

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreRol.valueHasMutated();
        self._NombreRol(self.NombreRol());
        self._IdTipoRol.valueHasMutated();
        self._IdTipoRol(self.IdTipoRol());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreRol());
}

RolModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'Roles': {
        create: function (options) {
            if (options)
              return new RolesModel(options.data);
            }
    },
    'Rol': {
        create: function (options) {
            if (options)
              return new RolModel(options.data);
            }
    }

}

Index = function (data) {

    var _modo_deshacer = false;
    var _nombreRol;
    var _input_habilitado = false;
    var _idRol;
    var _rol;
    var _modo_nuevo = false;
    var _id_filaRol_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarRoles = function() {
        console.log("ListarRoles");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cRol/ListarRoles',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.data.Roles([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.data.Roles.push(new RolesModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo == false)
      {
        var id = "#"+data.IdRol();
        $(id).addClass('active').siblings().removeClass('active');
        _rol = data;
      }

    }

    self.FilaButtonsRol = function (data, event)  {
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

          _rol.Deshacer(null, event);
          _input_habilitado = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarRol").prop("disabled",false);
          self.HabilitarTablaSpanRol(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdRol();
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.data.Roles(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdRol();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdRol();
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idRol = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.data.Roles(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idRol == item.IdRol();
            });

          if(match)
          {
            _rol = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputRol = function (data, option)  {
      //var id = "#"+data.IdRol();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputRol').hide();
        $(id).find('.class_SpanRol').show();
        $(id).find('.guardar_button_Rol').hide();
      }
      else
      {
        $(id).find('.class_InputRol').show();
        $(id).find('.class_SpanRol').hide();
        $(id).find('.guardar_button_Rol').show();
      }

    }

    self.HabilitarTablaSpanRol = function (data, option)  {
      //var id = "#"+data.IdRol();
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanRol').hide();
        $(id).find('.class_InputRol').show();
        $(id).find('.guardar_button_Rol').show();
        //_input_habilitado = true;
      }
      else {
        $(id).find('.class_SpanRol').show();
        $(id).find('.class_InputRol').hide();
        $(id).find('.guardar_button_Rol').hide();
        //_input_habilitado = false;
      }

    }

    self.HabilitarButtonsRol = function(data, option){
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_rol').prop("disabled", true);
        $(id).find('.borrar_button_rol').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_rol').prop("disabled", false);
        $(id).find('.borrar_button_rol').prop("disabled", false);
      }
    }


    self.AgregarRol = function(data,event) {
          console.log("AgregarRol");

          if ( _input_habilitado == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.data.Rol);
            _rol = new RolesModel(objeto);
            self.data.Roles.push(_rol);

            //Deshabilitando buttons
            self.HabilitarButtonsRol(null, false);
            $("#null_editar_button_rol").prop("disabled", true);
            $("#null_borrar_button_rol").prop("disabled", false);


            $("#btnAgregarRol").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdRol());

            var idspan ="#"+objeto.IdRol()+"_span_NombreRol";
            var idinput ="#"+objeto.IdRol()+"_input_NombreRol";

            var idbutton ="#"+objeto.IdRol()+"_button_rol";

            var id_span_idtiporol ="#"+objeto.IdRol()+"_span_IdTipoRol";
            var id_input_idtiporol ="#"+objeto.IdRol()+"_input_IdTipoRol";
            var id_combo_idtiporol ="#"+objeto.IdRol()+"_combo_IdTipoRol";
            var button_Rol ="#"+objeto.IdRol()+"_button_Rol";

            console.log(idbutton);
            //self.HabilitarFilaInputRol(_rol, true);
            //self.HabilitarFilaSpanRol(_rol, false);
            // $(id_input_idtiporol).selectpicker("refresh");

            $(idspan).hide();
            $(idinput).show();

            $(id_span_idtiporol).hide();
            $(id_combo_idtiporol).show();

            $(idbutton).show();
            $(idinput).focus();

            $(button_Rol).show();

            _modo_nuevo = true;
            _input_habilitado = true;

            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarRol =function(data,event){

      if(event)
      {
        $("#loader").show();
        console.log("InsertarRol");
        console.log(_rol.NombreRol());

        var objeto = data;
        var datajs = ko.toJS({"Data" : _rol});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cRol/InsertarRol',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarRol");
                      console.log(data);

                      if ($.isNumeric(data.IdRol))
                      {
                        _rol.IdRol(data.IdRol);
                        //deshabilitar botones agregar
                        $("#btnAgregarRol").prop("disabled",false);

                        var id_rol = "#"+ _rol.IdRol();
                        self.HabilitarFilaInputRol(id_rol, false);

                        var idbutton ="#"+_rol.IdRol()+"_button_rol";
                        $(idbutton).hide();

                         _rol.Confirmar(null,event);
                         self.HabilitarButtonsRol(null, true);

                         //ACTUALIZANDO DATA Nombre
                         var idnombretiporol = '#' + _rol.IdRol() + '_input_IdTipoRol option:selected';
                         var nombretiporol = $(idnombretiporol).html();

                         _rol.NombreTipoRol(nombretiporol);

                        existecambio = false;
                        _input_habilitado = false;
                        _modo_nuevo = false;

                      }
                      else {
                        alertify.alert(data.IdRol);
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

    self.ActualizarRol = function(data,event) {
          console.log("ActualizarRol");
          console.log(_rol.NombreRol());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _rol});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cRol/ActualizarRol',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarRol").prop("disabled",false);
                          console.log("ID5:"+_rol.IdRol());
                          _rol.Confirmar(null,event);

                          var id_rol = "#"+ _rol.IdRol();
                          self.HabilitarFilaInputRol(id_rol, false);

                          var idbutton ="#"+_rol.IdRol()+"_button_rol";
                          $(idbutton).hide();

                          //ACTUALIZANDO DATA Nombre
                          var idnombretiporol = '#' + _rol.IdRol() + '_input_IdTipoRol option:selected';
                          var nombretiporol = $(idnombretiporol).html();

                          _rol.NombreTipoRol(nombretiporol);

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

    self.EditarRol = function(data, event) {

      if(event)
      {
        console.log("EditarRol");
        console.log("ID.:"+data.IdRol());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_rol);

        if( _modo_nuevo == true )
        {

        }
        else {

          if (_rol.IdRol() == data.IdRol())
          {

            if (_input_habilitado == true)
            {
              $("#btnAgregarRol").prop("disabled",false);
              data.Deshacer(null,event);
              var id_rol = "#"+ data.IdRol();
              self.HabilitarFilaInputRol(id_rol, false);

              var idbutton = "#"+_rol.IdRol()+"_button_rol";
              $(idbutton).hide();

              _input_habilitado =false;


            }
            else {
              $("#btnAgregarRol").prop("disabled",true);
              var id_rol = "#"+ data.IdRol();
              self.HabilitarFilaInputRol(id_rol, true);

              var idbutton = "#"+data.IdRol()+"_button_rol";

              var idinput = "#"+data.IdRol()+"_input_NombreRol";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;
            }

          }
          else {
            $("#btnAgregarRol").prop("disabled",true);
            if( _input_habilitado == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_rol.IdRol());

              var id_rol = "#"+ _rol.IdRol();
              self.HabilitarFilaInputRol(id_rol, false);

              var idbutton = "#"+_rol.IdRol()+"_button_rol";

              _rol.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_rol = "#"+ data.IdRol();
            self.HabilitarFilaInputRol(id_rol, true);

            var idbutton = "#"+data.IdRol()+"_button_rol";

            var idinput = "#"+data.IdRol()+"_input_NombreRol";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado = true;
          }


        }

      }

    }

    self.PreBorrarRol = function (data) {

      if(_modo_nuevo == false)
      {
        _rol.Deshacer(null, event);
        _input_habilitado = false;
        $("#btnAgregarRol").prop("disabled",false);
        self.HabilitarTablaSpanRol(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarRol");
          console.log(data.IdRol());
          self.HabilitarButtonsRol(null, true);
          if (data.IdRol() != null)
            self.BorrarRol(data);
          else
          {
            $("#btnAgregarRol").prop("disabled",false);
            _input_habilitado = false;
            _modo_nuevo = false;
            self.data.Roles.remove(data);
            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarRoles();
          }
        });
      }, 200);

    }

    self.BorrarRol = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cRol/BorrarRol',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarRol").prop("disabled",false);
                      self.HabilitarTablaSpanRol(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.data.Roles.remove(objeto);
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


    self.OnClickRol = function(data ,event) {

      if(event)
      {
          console.log("OnClickRol");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_rol);

          if( _modo_nuevo == true )
          {

          }
          else
          {

            $("#btnAgregarRol").prop("disabled",true);
            if(_rol.IdRol() !=  data.IdRol())
            {
              if (_input_habilitado == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _rol.Deshacer(null, event);

                //var id_rol = "#" + _id_filaRol_anterior;
                var id_rol = "#" + _rol.IdRol();
                self.HabilitarFilaInputRol(id_rol, false);

                var idbutton = "#"+_rol.IdRol()+"_button_rol";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_rol.IdRol());
              console.log(data.IdRol());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_rol = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_rol, "span") || $.isSubstring(id_fila_rol, "input")){
                id_fila_rol = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filaRol_anterior = $(id_fila_rol).parent()[0].id;
              var idspan ="#"+$(id_fila_rol).find('span').attr('id');
              var idinput ="#"+$(id_fila_rol).find('input').attr('id');
              self.HabilitarFilaInputRol("#" + $(id_fila_rol).parent()[0].id, true);

              var idbutton = "#"+data.IdRol()+"_button_rol";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;

              }
              else {
                if (_input_habilitado == false)
                {
                  var id_fila_rol = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_rol, "span") || $.isSubstring(id_fila_rol, "input")){
                    id_fila_rol = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputRol("#" + $(id_fila_rol).parent()[0].id, true);

                  var idbutton = "#"+data.IdRol()+"_button_rol";
                  var idinput ="#"+$(id_fila_rol).find('input').attr('id');
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
    self.OnKeyUpRol = function(data, event){
      if(event)
      {
       console.log("OnKeyUpRol");

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

         var idinputnombre = '#' + _rol.IdRol() + '_input_NombreRol';
         var idinputtipo ='#' +  _rol.IdRol() + '_input_IdTipoRol';

         _rol.NombreRol($(idinputnombre).val());
         _rol.IdTipoRol($(idinputtipo).val());


         if(_modo_nuevo == true)
         {
           self.InsertarRol(_rol,event);
         }
         else
         {
           self.ActualizarRol(_rol,event);
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
              self.SeleccionarAnterior(_rol);
              self.data.Roles.remove(_rol);
              var tabla = $('#DataTables_Table_0');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarRol").prop("disabled",false);
              self.HabilitarButtonsRol(null, true);
               _modo_nuevo = false;
               _input_habilitado = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_rol._NombreRol());
            //revertir texto
            //data.NombreRol(_rol.NombreRol());

             _rol.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarRol").prop("disabled",false);

            /*var id_fila_rol = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_rol, "span") || $.isSubstring(id_fila_rol, "input")){
              id_fila_rol = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputRol("#" + $(id_fila_rol).parent()[0].id, false);*/
            self.HabilitarTablaSpanRol(null, true);

            var idbutton ="#"+_rol.IdRol()+"_button_rol";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo = false;
            _input_habilitado = false;
          }

        }
      }
    }

    self.GuardarRol = function(data,event) {
      if(event)
      {
         console.log("GuardarRol");
         console.log(_nombreRol);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
         var idinputnombre = '#' + _rol.IdRol() + '_input_NombreRol';
         var idinputtipo ='#' +  _rol.IdRol() + '_input_IdTipoRol';

         _rol.NombreRol($(idinputnombre).val());
         _rol.IdTipoRol($(idinputtipo).val());
         //_rol.NombreRol(_nombreRol);

         if(_modo_nuevo == true)
         {
           self.InsertarRol(_rol,event);
         }
         else
         {
           self.ActualizarRol(_rol,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
