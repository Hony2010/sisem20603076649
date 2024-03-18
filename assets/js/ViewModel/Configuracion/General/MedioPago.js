
MediosPagoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._CodigoMedioPago = ko.observable(data.CodigoMedioPago);
    self._NombreMedioPago = ko.observable(data.NombreMedioPago);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //console.log(self._CodigoMedioPago());
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreMedioPago());

        self.CodigoMedioPago.valueHasMutated();
        self.NombreMedioPago.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.CodigoMedioPago("");
        self.NombreMedioPago("");
        self.CodigoMedioPago(self._CodigoMedioPago());
        self.NombreMedioPago(self._NombreMedioPago());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._CodigoMedioPago.valueHasMutated();
        self._CodigoMedioPago(self.CodigoMedioPago());
        self._NombreMedioPago.valueHasMutated();
        self._NombreMedioPago(self.NombreMedioPago());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreMedioPago());
}

MedioPagoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'MediosPago': {
        create: function (options) {
            if (options)
              return new MediosPagoModel(options.data);
            }
    },
    'MedioPago': {
        create: function (options) {
            if (options)
              return new MedioPagoModel(options.data);
            }
    }

}

IndexMedioPago = function (data) {

    var _modo_deshacer = false;
    var _codigomediopago;
    var _descripcionmediopago;
    var _input_habilitado_mediopago = false;
    var _idmediopago;
    var _mediopago;
    var _modo_nuevo_mediopago = false;
    var _id_filamediopago_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarMediosPago = function() {
        console.log("ListarMediosPago");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cMedioPago/ListarMediosPago',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataMedioPago.MediosPago([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.dataMedioPago.MediosPago.push(new MediosPagoModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_mediopago == false){
        var id = "#"+data.IdMedioPago()+'_tr_mediopago';
        $(id).addClass('active').siblings().removeClass('active');
        _mediopago = data;
      }

    }

    self.FilaButtonsMedioPago = function (data, event)  {
      console.log("BUTTONS");
      console.log("EVENTTARGET: " + $(event.target).attr('class'));
      console.log("THIS: " + $(this).attr('class'));
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else
      {
          console.log("MODO NUEVO: " + _modo_nuevo_mediopago);
          if(_modo_nuevo_mediopago == true)
          return;

          _mediopago.Deshacer(null, event);
          _input_habilitado_mediopago = false;
          $("#btnAgregarMedioPago").prop("disabled",false);
          self.HabilitarTablaSpanMedioPago(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdMedioPago()+'_tr_mediopago';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_mediopago == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idmediopago = anteriorObjeto.attr("id");
        //console.log(_idmediopago);
        var match = ko.utils.arrayFirst(self.dataMedioPago.MediosPago(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idmediopago == item.IdMedioPago();
          });

        if(match)
        {
          _mediopago = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdMedioPago()+'_tr_mediopago';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_mediopago == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idmediopago = siguienteObjeto.attr("id");
          //console.log(_idmediopago);
          var match = ko.utils.arrayFirst(self.dataMedioPago.MediosPago(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idmediopago == item.IdMedioPago();
            });

          if(match)
          {
            _mediopago = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputMedioPago = function (data, option)  {
      //var id = "#"+data.IdMedioPago();
      var id = data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputMedioPago').hide();
        $(id).find('.class_SpanMedioPago').show();
      }
      else
      {
        $(id).find('.class_InputMedioPago').show();
        $(id).find('.class_SpanMedioPago').hide();
      }

    }

    self.HabilitarTablaSpanMedioPago = function (data, option)  {
      //var id = "#"+data.IdMedioPago();
      var id = "#DataTables_Table_0_mediopago";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanMedioPago').hide();
        $(id).find('.class_InputMedioPago').show();
        //$(id).find('.guardar_button_MedioPago').show();
        //_input_habilitado_mediopago = true;
      }
      else {
        $(id).find('.class_SpanMedioPago').show();
        $(id).find('.class_InputMedioPago').hide();
        $(id).find('.guardar_button_MedioPago').hide();
        //_input_habilitado_mediopago = false;
      }

    }

    self.HabilitarButtonsMedioPago = function(data, option){
      var id = "#DataTables_Table_0_mediopago";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_MedioPago').prop("disabled", true);
        $(id).find('.borrar_button_MedioPago').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_MedioPago').prop("disabled", false);
        $(id).find('.borrar_button_MedioPago').prop("disabled", false);
      }
    }


    self.AgregarMedioPago = function(data,event) {
          console.log("AgregarMedioPago");

          if ( _input_habilitado_mediopago == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.dataMedioPago.MedioPago);
            _mediopago = new MediosPagoModel(objeto);
            self.dataMedioPago.MediosPago.push(_mediopago);

            //Deshabilitando buttons
            self.HabilitarButtonsMedioPago(null, false);
            $("#null_editar_button_MedioPago").prop("disabled", true);
            $("#null_borrar_button_MedioPago").prop("disabled", false);


            $("#btnAgregarMedioPago").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdMedioPago());

            var idspancodigo ="#"+objeto.IdMedioPago()+"_span_CodigoMedioPago";
            var idinputcodigo ="#"+objeto.IdMedioPago()+"_input_CodigoMedioPago";

            var idspannombre ="#"+objeto.IdMedioPago()+"_span_NombreMedioPago";
            var idinputnombre ="#"+objeto.IdMedioPago()+"_input_NombreMedioPago";

            var idbutton ="#"+objeto.IdMedioPago()+"_button_MedioPago";

            console.log(idbutton);
            //self.HabilitarFilaInputMedioPago(_mediopago, true);
            //self.HabilitarFilaSpanMedioPago(_mediopago, false);

            $(idspancodigo).hide();
            $(idinputcodigo).show();
            $(idspannombre).hide();
            $(idinputnombre).show();
            $(idbutton).show();
            $(idinputcodigo).focus();

            _modo_nuevo_mediopago = true;
            _input_habilitado_mediopago = true;

            var tabla = $('#DataTables_Table_0_mediopago');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');

          }
    }

    self.InsertarMedioPago =function(data,event){

      if(event)
      {
        console.log("InsertarMedioPago");
        console.log(_mediopago.NombreMedioPago());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _mediopago});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cMedioPago/InsertarMedioPago',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarMedioPago");
                      console.log(data);

                      if ($.isNumeric(data.IdMedioPago))
                      {
                        _mediopago.IdMedioPago(data.IdMedioPago);
                        //deshabilitar botones agregar
                        $("#btnAgregarMedioPago").prop("disabled",false);

                        var id_mediopago = "#"+ _mediopago.IdMedioPago()+'_tr_mediopago';
                        self.HabilitarFilaInputMedioPago(id_mediopago, false);

                        var idbutton ="#"+_mediopago.IdMedioPago()+"_button_MedioPago";
                        $(idbutton).hide();

                         _mediopago.Confirmar(null,event);
                         self.HabilitarButtonsMedioPago(null, true);

                        existecambio = false;
                        _input_habilitado_mediopago = false;
                        _modo_nuevo_mediopago = false;

                      }
                      else {
                        alertify.alert(data.IdMedioPago);
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

    self.ActualizarMedioPago = function(data,event) {
          console.log("ActualizarMedioPago");
          console.log(_mediopago.NombreMedioPago());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _mediopago});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cMedioPago/ActualizarMedioPago',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarMedioPago").prop("disabled",false);
                          console.log("ID5:"+_mediopago.IdMedioPago());
                          _mediopago.Confirmar(null,event);

                          var id_mediopago = "#"+ _mediopago.IdMedioPago()+'_tr_mediopago';
                          self.HabilitarFilaInputMedioPago(id_mediopago, false);

                          var idbutton ="#"+_mediopago.IdMedioPago()+"_button_MedioPago";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_mediopago = false;
                          _modo_nuevo_mediopago = false;

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

    self.EditarMedioPago = function(data, event) {

      if(event)
      {
        console.log("EditarMedioPago");
        console.log("ID.:"+data.IdMedioPago());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_mediopago);

        if( _modo_nuevo_mediopago == true )
        {

        }
        else {

          if (_mediopago.IdMedioPago() == data.IdMedioPago())
          {

            if (_input_habilitado_mediopago == true)
            {
              $("#btnAgregarMedioPago").prop("disabled",false);
              data.Deshacer(null,event);
              var id_mediopago = "#"+ data.IdMedioPago()+'_tr_mediopago';
              self.HabilitarFilaInputMedioPago(id_mediopago, false);

              var idbutton = "#"+_mediopago.IdMedioPago()+"_button_MedioPago";
              $(idbutton).hide();

              _input_habilitado_mediopago =false;


            }
            else {
              $("#btnAgregarMedioPago").prop("disabled",true);
              var id_mediopago = "#"+ data.IdMedioPago()+'_tr_mediopago';
              self.HabilitarFilaInputMedioPago(id_mediopago, true);

              var idbutton = "#"+data.IdMedioPago()+"_button_MedioPago";

              var idinput = "#"+data.IdMedioPago()+"_input_CodigoMedioPago";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_mediopago = true;
            }

          }
          else {
            $("#btnAgregarMedioPago").prop("disabled",true);
            if( _input_habilitado_mediopago == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_mediopago.IdMedioPago());

              var id_mediopago = "#"+ _mediopago.IdMedioPago()+'_tr_mediopago';
              self.HabilitarFilaInputMedioPago(id_mediopago, false);

              var idbutton = "#"+_mediopago.IdMedioPago()+"_button_MedioPago";

              _mediopago.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_mediopago = "#"+ data.IdMedioPago()+'_tr_mediopago';
            self.HabilitarFilaInputMedioPago(id_mediopago, true);

            var idbutton = "#"+data.IdMedioPago()+"_button_MedioPago";

            var idinput = "#"+data.IdMedioPago()+"_input_CodigoMedioPago";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_mediopago = true;
          }


        }

      }

    }

    self.PreBorrarMedioPago = function (data, event) {

      if(_modo_nuevo_mediopago == false)
      {
        _mediopago.Deshacer(null, event);
        _input_habilitado_mediopago = false;
        $("#btnAgregarMedioPago").prop("disabled",false);
        self.HabilitarTablaSpanMedioPago(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarMedioPago");
          console.log(data.IdMedioPago());
          self.HabilitarButtonsMedioPago(null, true);
          if (data.IdMedioPago() != null){
            self.BorrarMedioPago(data);
          }
          else
          {
            $("#btnAgregarMedioPago").prop("disabled",false);
            _input_habilitado_mediopago = false;
            _modo_nuevo_mediopago = false;
            self.dataMedioPago.MediosPago.remove(data);

            var tabla = $('#DataTables_Table_0_mediopago');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarMediosPago();
          }
        });
      }, 200);

    }

    self.BorrarMedioPago = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cMedioPago/BorrarMedioPago',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarMedioPago").prop("disabled",false);
                      self.HabilitarTablaSpanMedioPago(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataMedioPago.MediosPago.remove(objeto);
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


    self.OnClickMedioPago = function(data ,event) {

      if(event)
      {
          console.log("OnClickMedioPago");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_mediopago);

          if( _modo_nuevo_mediopago == true )
          {

          }
          else
          {

            $("#btnAgregarMedioPago").prop("disabled",true);
            if(_mediopago.IdMedioPago() !=  data.IdMedioPago())
            {
              if (_input_habilitado_mediopago == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _mediopago.Deshacer(null, event);

                //var id_mediopago = "#" + _id_filamediopago_anterior;
                var id_mediopago = "#" + _mediopago.IdMedioPago()+'_tr_mediopago';
                self.HabilitarFilaInputMedioPago(id_mediopago, false);

                var idbutton = "#"+_mediopago.IdMedioPago()+"_button_MedioPago";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_mediopago.IdMedioPago());
              console.log(data.IdMedioPago());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_mediopago = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_mediopago, "span") || $.isSubstring(id_fila_mediopago, "input")){
                id_fila_mediopago = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filamediopago_anterior = $(id_fila_mediopago).parent()[0].id;
              var idspan ="#"+$(id_fila_mediopago).find('span').attr('id');
              var idinput ="#"+$(id_fila_mediopago).find('input').attr('id');
              self.HabilitarFilaInputMedioPago("#" + $(id_fila_mediopago).parent()[0].id, true);

              var idbutton = "#"+data.IdMedioPago()+"_button_MedioPago";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_mediopago = true;

              }
              else {
                if (_input_habilitado_mediopago == false)
                {
                  var id_fila_mediopago = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_mediopago, "span") || $.isSubstring(id_fila_mediopago, "input")){
                    id_fila_mediopago = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputMedioPago("#" + $(id_fila_mediopago).parent()[0].id, true);

                  var idbutton = "#"+data.IdMedioPago()+"_button_MedioPago";
                  var idinput ="#"+$(id_fila_mediopago).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_mediopago = true;
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
    self.OnKeyUpMedioPago = function(data, event){
      if(event)
      {
       console.log("OnKeyUpMedioPago");

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
         var idinputdescripcion = _mediopago.IdMedioPago() + '_input_NombreMedioPago';
         var idinputcodigo = _mediopago.IdMedioPago() + '_input_CodigoMedioPago';

         if(event.target.id == idinputdescripcion)
         {
           _mediopago.NombreMedioPago(event.target.value);
         }
         else if(event.target.id == idinputcodigo)
         {
            _mediopago.CodigoMedioPago(event.target.value);
         }


         if(_modo_nuevo_mediopago == true)
         {
           self.InsertarMedioPago(_mediopago,event);
         }
         else
         {
           self.ActualizarMedioPago(_mediopago,event);
         }

       }

       return true;
      }
    }


    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado_mediopago == true)
        {
          if(_modo_nuevo_mediopago == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_mediopago);
              self.dataMedioPago.MediosPago.remove(_mediopago);
              var tabla = $('#DataTables_Table_0_mediopago');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarMedioPago").prop("disabled",false);
              self.HabilitarButtonsMedioPago(null, true);

               _modo_nuevo_mediopago = false;
               _input_habilitado_mediopago = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_mediopago._NombreMedioPago());
            //revertir texto
            //data.NombreMedioPago(_mediopago.NombreMedioPago());

             _mediopago.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarMedioPago").prop("disabled",false);

            /*var id_fila_mediopago = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_mediopago, "span") || $.isSubstring(id_fila_mediopago, "input")){
              id_fila_mediopago = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputMedioPago("#" + $(id_fila_mediopago).parent()[0].id, false);*/
            self.HabilitarTablaSpanMedioPago(null, true);

            var idbutton ="#"+_mediopago.IdMedioPago()+"_button_MedioPago";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_mediopago = false;
            _input_habilitado_mediopago = false;
          }

        }
      }
    }

    self.GuardarMedioPago = function(data,event) {
      if(event)
      {
         console.log("GuardarMedioPago");
         console.log(_descripcionmediopago);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputdescripcion = _mediopago.IdMedioPago() + '_input_NombreMedioPago';
          var idinputcodigo = _mediopago.IdMedioPago() + '_input_CodigoMedioPago';

          if(event.target.id == idinputdescripcion)
          {
            _mediopago.NombreMedioPago(_descripcionmediopago);
          }
          else if(event.target.id == idinputcodigo)
          {
             _mediopago.CodigoMedioPago(_codigomediopago);
          }
         //_mediopago.NombreMedioPago(_descripcionmediopago);

         if(_modo_nuevo_mediopago == true)
         {
           self.InsertarMedioPago(_mediopago,event);
         }
         else
         {
           self.ActualizarMedioPago(_mediopago,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
