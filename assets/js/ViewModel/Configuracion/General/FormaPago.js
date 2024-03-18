
FormasPagoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreFormaPago = ko.observable(data.NombreFormaPago);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreFormaPago());

        self.NombreFormaPago.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreFormaPago("");
        self.NombreFormaPago(self._NombreFormaPago());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreFormaPago.valueHasMutated();
        self._NombreFormaPago(self.NombreFormaPago());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreFormaPago());
}

FormaPagoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'FormasPago': {
        create: function (options) {
            if (options)
              return new FormasPagoModel(options.data);
            }
    },
    'FormaPago': {
        create: function (options) {
            if (options)
              return new FormaPagoModel(options.data);
            }
    }

}

IndexFormaPago = function (data) {

    var _modo_deshacer = false;
    var _nombreformapago;
    var _input_habilitado_formapago = false;
    var _idformapago;
    var _formapago;
    var _modo_nuevo_formapago = false;
    var _id_filaformapago_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarFormasPago = function() {
        console.log("ListarFormasPago");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cFormaPago/ListarFormasPago',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataFormaPago.FormasPago([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.dataFormaPago.FormasPago.push(new FormasPagoModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_formapago == false)
      {
        var id = "#"+data.IdFormaPago()+'_tr_formapago';
        $(id).addClass('active').siblings().removeClass('active');
        _formapago = data;
      }

    }

    self.FilaButtonsFormaPago = function (data, event)  {
      console.log("BUTTONS");
      console.log("EVENTTARGET: " + $(event.target).attr('class'));
      console.log("THIS: " + $(this).attr('class'));
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_formapago);
          if(_modo_nuevo_formapago == true)
          return;

          _formapago.Deshacer(null, event);
          _input_habilitado_formapago = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarFormaPago").prop("disabled",false);
          self.HabilitarTablaSpanFormaPago(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdFormaPago()+'_tr_formapago';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_formapago == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idformapago = anteriorObjeto.attr("id");
        //console.log(_idformapago);
        var match = ko.utils.arrayFirst(self.dataFormaPago.FormasPago(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idformapago == item.IdFormaPago();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdFormaPago()+'_tr_formapago';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_formapago == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idformapago = siguienteObjeto.attr("id");
          //console.log(_idformapago);
          var match = ko.utils.arrayFirst(self.dataFormaPago.FormasPago(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idformapago == item.IdFormaPago();
            });

          if(match)
          {
            _formapago = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputFormaPago = function (data, option)  {
      //var id = "#"+data.IdFormaPago();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputFormaPago').hide();
        $(id).find('.class_SpanFormaPago').show();
      }
      else
      {
        $(id).find('.class_InputFormaPago').show();
        $(id).find('.class_SpanFormaPago').hide();
      }

    }

    self.HabilitarTablaSpanFormaPago = function (data, option)  {
      //var id = "#"+data.IdFormaPago();
      var id = "#DataTables_Table_0_formapago";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanFormaPago').hide();
        $(id).find('.class_InputFormaPago').show();
        //$(id).find('.guardar_button_FormaPago').show();
        //_input_habilitado_formapago = true;
      }
      else {
        $(id).find('.class_SpanFormaPago').show();
        $(id).find('.class_InputFormaPago').hide();
        $(id).find('.guardar_button_FormaPago').hide();
        //_input_habilitado_formapago = false;
      }

    }

    self.HabilitarButtonsFormaPago = function(data, option){
      var id = "#DataTables_Table_0_formapago";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_FormaPago').prop("disabled", true);
        $(id).find('.borrar_button_FormaPago').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_FormaPago').prop("disabled", false);
        $(id).find('.borrar_button_FormaPago').prop("disabled", false);
      }
    }


    self.AgregarFormaPago = function(data,event) {
          console.log("AgregarFormaPago");

          if ( _input_habilitado_formapago == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.dataFormaPago.FormaPago);
            _formapago = new FormasPagoModel(objeto);
            self.dataFormaPago.FormasPago.push(_formapago);

            //Deshabilitando buttons
            self.HabilitarButtonsFormaPago(null, false);
            $("#null_editar_button_FormaPago").prop("disabled", true);
            $("#null_borrar_button_FormaPago").prop("disabled", false);


            $("#btnAgregarFormaPago").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdFormaPago());

            var idspan ="#"+objeto.IdFormaPago()+"_span_NombreFormaPago";
            var idinput ="#"+objeto.IdFormaPago()+"_input_NombreFormaPago";

            var idbutton ="#"+objeto.IdFormaPago()+"_button_FormaPago";

            console.log(idbutton);
            //self.HabilitarFilaInputFormaPago(_formapago, true);
            //self.HabilitarFilaSpanFormaPago(_formapago, false);

            $(idspan).hide();
            $(idinput).show();
            $(idbutton).show();
            $(idinput).focus();

            _modo_nuevo_formapago = true;
            _input_habilitado_formapago = true;

            var tabla = $('#DataTables_Table_0_formapago');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarFormaPago =function(data,event){

      if(event)
      {
        console.log("InsertarFormaPago");
        console.log(_formapago.NombreFormaPago());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _formapago});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cFormaPago/InsertarFormaPago',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarFormaPago");
                      console.log(data);

                      if ($.isNumeric(data.IdFormaPago))
                      {
                        _formapago.IdFormaPago(data.IdFormaPago);
                        //deshabilitar botones agregar
                        $("#btnAgregarFormaPago").prop("disabled",false);

                        var id_formapago = "#"+ _formapago.IdFormaPago()+'_tr_formapago';
                        self.HabilitarFilaInputFormaPago(id_formapago, false);

                        var idbutton ="#"+_formapago.IdFormaPago()+"_button_FormaPago";
                        $(idbutton).hide();

                         _formapago.Confirmar(null,event);
                         self.HabilitarButtonsFormaPago(null, true);

                        existecambio = false;
                        _input_habilitado_formapago = false;
                        _modo_nuevo_formapago = false;

                      }
                      else {
                        alertify.alert(data.IdFormaPago);
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

    self.ActualizarFormaPago = function(data,event) {
          console.log("ActualizarFormaPago");
          console.log(_formapago.NombreFormaPago());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _formapago});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cFormaPago/ActualizarFormaPago',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarFormaPago").prop("disabled",false);
                          console.log("ID5:"+_formapago.IdFormaPago());
                          _formapago.Confirmar(null,event);

                          var id_formapago = "#"+ _formapago.IdFormaPago()+'_tr_formapago';
                          self.HabilitarFilaInputFormaPago(id_formapago, false);

                          var idbutton ="#"+_formapago.IdFormaPago()+"_button_FormaPago";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_formapago = false;
                          _modo_nuevo_formapago = false;

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

    self.EditarFormaPago = function(data, event) {

      if(event)
      {
        console.log("EditarFormaPago");
        console.log("ID.:"+data.IdFormaPago());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_formapago);

        if( _modo_nuevo_formapago == true )
        {

        }
        else {

          if (_formapago.IdFormaPago() == data.IdFormaPago())
          {

            if (_input_habilitado_formapago == true)
            {
              $("#btnAgregarFormaPago").prop("disabled",false);
              data.Deshacer(null,event);
              var id_formapago = "#"+ data.IdFormaPago()+'_tr_formapago';
              self.HabilitarFilaInputFormaPago(id_formapago, false);

              var idbutton = "#"+_formapago.IdFormaPago()+"_button_FormaPago";
              $(idbutton).hide();

              _input_habilitado_formapago =false;


            }
            else {
              $("#btnAgregarFormaPago").prop("disabled",true);
              var id_formapago = "#"+ data.IdFormaPago()+'_tr_formapago';
              self.HabilitarFilaInputFormaPago(id_formapago, true);

              var idbutton = "#"+data.IdFormaPago()+"_button_FormaPago";

              var idinput = "#"+data.IdFormaPago()+"_input_NombreFormaPago";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_formapago = true;
            }

          }
          else {
            $("#btnAgregarFormaPago").prop("disabled",true);
            if( _input_habilitado_formapago == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_formapago.IdFormaPago());

              var id_formapago = "#"+ _formapago.IdFormaPago()+'_tr_formapago';
              self.HabilitarFilaInputFormaPago(id_formapago, false);

              var idbutton = "#"+_formapago.IdFormaPago()+"_button_FormaPago";

              _formapago.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_formapago = "#"+ data.IdFormaPago()+'_tr_formapago';
            self.HabilitarFilaInputFormaPago(id_formapago, true);

            var idbutton = "#"+data.IdFormaPago()+"_button_FormaPago";

            var idinput = "#"+data.IdFormaPago()+"_input_NombreFormaPago";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_formapago = true;
          }


        }

      }

    }

    self.PreBorrarFormaPago = function (data) {

      if(_modo_nuevo_formapago == false)
      {
        _formapago.Deshacer(null, event);
        _input_habilitado_formapago = false;
        $("#btnAgregarFormaPago").prop("disabled",false);
        self.HabilitarTablaSpanFormaPago(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarFormaPago");
          console.log(data.IdFormaPago());
          self.HabilitarButtonsFormaPago(null, true);
          if (data.IdFormaPago() != null)
            self.BorrarFormaPago(data);
          else
          {
            $("#btnAgregarFormaPago").prop("disabled",false);
            _input_habilitado_formapago = false;
            _modo_nuevo_formapago = false;
            self.dataFormaPago.FormasPago.remove(data);
            var tabla = $('#DataTables_Table_0_formapago');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarFormasPago();
          }
        });
      }, 200);

    }

    self.BorrarFormaPago = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cFormaPago/BorrarFormaPago',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarFormaPago").prop("disabled",false);
                      self.HabilitarTablaSpanFormaPago(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataFormaPago.FormasPago.remove(objeto);
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


    self.OnClickFormaPago = function(data ,event) {

      if(event)
      {
          console.log("OnClickFormaPago");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_formapago);

          if( _modo_nuevo_formapago == true )
          {

          }
          else
          {

            $("#btnAgregarFormaPago").prop("disabled",true);
            if(_formapago.IdFormaPago() !=  data.IdFormaPago())
            {
              if (_input_habilitado_formapago == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _formapago.Deshacer(null, event);

                //var id_formapago = "#" + _id_filaformapago_anterior;
                var id_formapago = "#" + _formapago.IdFormaPago()+'_tr_formapago';
                self.HabilitarFilaInputFormaPago(id_formapago, false);

                var idbutton = "#"+_formapago.IdFormaPago()+"_button_FormaPago";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_formapago.IdFormaPago());
              console.log(data.IdFormaPago());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_formapago = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_formapago, "span") || $.isSubstring(id_fila_formapago, "input")){
                id_fila_formapago = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filaformapago_anterior = $(id_fila_formapago).parent()[0].id;
              var idspan ="#"+$(id_fila_formapago).find('span').attr('id');
              var idinput ="#"+$(id_fila_formapago).find('input').attr('id');
              self.HabilitarFilaInputFormaPago("#" + $(id_fila_formapago).parent()[0].id, true);

              var idbutton = "#"+data.IdFormaPago()+"_button_FormaPago";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_formapago = true;

              }
              else {
                if (_input_habilitado_formapago == false)
                {
                  var id_fila_formapago = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_formapago, "span") || $.isSubstring(id_fila_formapago, "input")){
                    id_fila_formapago = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputFormaPago("#" + $(id_fila_formapago).parent()[0].id, true);

                  var idbutton = "#"+data.IdFormaPago()+"_button_FormaPago";
                  var idinput ="#"+$(id_fila_formapago).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_formapago = true;
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
    self.OnKeyUpFormaPago = function(data, event){
      if(event)
      {
       console.log("OnKeyUpFormaPago");

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
         var idinputnombre = _formapago.IdFormaPago() + '_input_NombreFormaPago';

         if(event.target.id == idinputnombre)
         {
           _formapago.NombreFormaPago(event.target.value);
         }


         if(_modo_nuevo_formapago == true)
         {
           self.InsertarFormaPago(_formapago,event);
         }
         else
         {
           self.ActualizarFormaPago(_formapago,event);
         }

       }


       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado_formapago == true)
        {
          if(_modo_nuevo_formapago == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_formapago);
              self.dataFormaPago.FormasPago.remove(_formapago);
              var tabla = $('#DataTables_Table_0_formapago');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarFormaPago").prop("disabled",false);
              self.HabilitarButtonsFormaPago(null, true);
               _modo_nuevo_formapago = false;
               _input_habilitado_formapago = false;
            });
          }
          else
          {
            console.log("Escape - false");
            //revertir texto
            //data.NombreFormaPago(_formapago.NombreFormaPago());

             _formapago.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarFormaPago").prop("disabled",false);

            /*var id_fila_formapago = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_formapago, "span") || $.isSubstring(id_fila_formapago, "input")){
              id_fila_formapago = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputFormaPago("#" + $(id_fila_formapago).parent()[0].id, false);*/
            self.HabilitarTablaSpanFormaPago(null, true);

            var idbutton ="#"+_formapago.IdFormaPago()+"_button_FormaPago";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_formapago = false;
            _input_habilitado_formapago = false;
          }

        }
      }
    }

    self.GuardarFormaPago = function(data,event) {
      if(event)
      {
         console.log("GuardarFormaPago");
         console.log(_nombreformapago);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _formapago.IdFormaPago() + '_input_NombreFormaPago';

          if(event.target.id == idinputnombre)
          {
            _formapago.NombreFormaPago(_nombreformapago);
          }
         //_formapago.NombreFormaPago(_nombreformapago);

         if(_modo_nuevo_formapago == true)
         {
           self.InsertarFormaPago(_formapago,event);
         }
         else
         {
           self.ActualizarFormaPago(_formapago,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
