
GirosNegocioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreGiroNegocio = ko.observable(data.NombreGiroNegocio);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreGiroNegocio());

        self.NombreGiroNegocio.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreGiroNegocio("");
        self.NombreGiroNegocio(self._NombreGiroNegocio());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreGiroNegocio.valueHasMutated();
        self._NombreGiroNegocio(self.NombreGiroNegocio());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreGiroNegocio());
}

GiroNegocioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'GirosNegocio': {
        create: function (options) {
            if (options)
              return new GirosNegocioModel(options.data);
            }
    },
    'GiroNegocio': {
        create: function (options) {
            if (options)
              return new GiroNegocioModel(options.data);
            }
    }

}

IndexGiroNegocio = function (data) {

    var _modo_deshacer = false;
    var _nombregironegocio;
    var _input_habilitado_gironegocio = false;
    var _idgironegocio;
    var _gironegocio;
    var _modo_nuevo_gironegocio = false;
    var _id_filagironegocio_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarGirosNegocio = function() {
        console.log("ListarGirosNegocio");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cGiroNegocio/ListarGirosNegocio',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataGiroNegocio.GirosNegocio([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.dataGiroNegocio.GirosNegocio.push(new GirosNegocioModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_gironegocio == false)
      {
        var id = "#"+data.IdGiroNegocio()+'_tr_gironegocio';
        $(id).addClass('active').siblings().removeClass('active');
        _gironegocio = data;
      }

    }

    self.FilaButtonsGiroNegocio = function (data, event)  {
      console.log("BUTTONS");
      console.log("EVENTTARGET: " + $(event.target).attr('class'));
      console.log("THIS: " + $(this).attr('class'));
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_gironegocio);
          if(_modo_nuevo_gironegocio == true)
          return;

          _gironegocio.Deshacer(null, event);
          _input_habilitado_gironegocio = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarGiroNegocio").prop("disabled",false);
          self.HabilitarTablaSpanGiroNegocio(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdGiroNegocio()+'_tr_gironegocio';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_gironegocio == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.dataGiroNegocio.GirosNegocio(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdGiroNegocio();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdGiroNegocio()+'_tr_gironegocio';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_gironegocio == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idgironegocio = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.dataGiroNegocio.GirosNegocio(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idgironegocio == item.IdGiroNegocio();
            });

          if(match)
          {
            _gironegocio = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputGiroNegocio = function (data, option)  {
      //var id = "#"+data.IdGiroNegocio();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputGiroNegocio').hide();
        $(id).find('.class_SpanGiroNegocio').show();
      }
      else
      {
        $(id).find('.class_InputGiroNegocio').show();
        $(id).find('.class_SpanGiroNegocio').hide();
      }

    }

    self.HabilitarTablaSpanGiroNegocio = function (data, option)  {
      //var id = "#"+data.IdGiroNegocio();
      var id = "#DataTables_Table_0_gironegocio";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanGiroNegocio').hide();
        $(id).find('.class_InputGiroNegocio').show();
        //$(id).find('.guardar_button_GiroNegocio').show();
        //_input_habilitado_gironegocio = true;
      }
      else {
        $(id).find('.class_SpanGiroNegocio').show();
        $(id).find('.class_InputGiroNegocio').hide();
        $(id).find('.guardar_button_GiroNegocio').hide();
        //_input_habilitado_gironegocio = false;
      }

    }

    self.HabilitarButtonsGiroNegocio = function(data, option){
      var id = "#DataTables_Table_0_gironegocio";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_GiroNegocio').prop("disabled", true);
        $(id).find('.borrar_button_GiroNegocio').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_GiroNegocio').prop("disabled", false);
        $(id).find('.borrar_button_GiroNegocio').prop("disabled", false);
      }
    }


    self.AgregarGiroNegocio = function(data,event) {
          console.log("AgregarGiroNegocio");

          if ( _input_habilitado_gironegocio == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.dataGiroNegocio.GiroNegocio);
            _gironegocio = new GirosNegocioModel(objeto);
            self.dataGiroNegocio.GirosNegocio.push(_gironegocio);

            //Deshabilitando buttons
            self.HabilitarButtonsGiroNegocio(null, false);
            $("#null_editar_button_GiroNegocio").prop("disabled", true);
            $("#null_borrar_button_GiroNegocio").prop("disabled", false);


            $("#btnAgregarGiroNegocio").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdGiroNegocio());

            var idspan ="#"+objeto.IdGiroNegocio()+"_span_NombreGiroNegocio";
            var idinput ="#"+objeto.IdGiroNegocio()+"_input_NombreGiroNegocio";

            var idbutton ="#"+objeto.IdGiroNegocio()+"_button_GiroNegocio";

            console.log(idbutton);
            //self.HabilitarFilaInputGiroNegocio(_gironegocio, true);
            //self.HabilitarFilaSpanGiroNegocio(_gironegocio, false);

            $(idspan).hide();
            $(idinput).show();
            $(idbutton).show();
            $(idinput).focus();

            _modo_nuevo_gironegocio = true;
            _input_habilitado_gironegocio = true;

            var tabla = $('#DataTables_Table_0_gironegocio');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarGiroNegocio =function(data,event){

      if(event)
      {
        console.log("InsertarGiroNegocio");
        console.log(_gironegocio.NombreGiroNegocio());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _gironegocio});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cGiroNegocio/InsertarGiroNegocio',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarGiroNegocio");
                      console.log(data);

                      if ($.isNumeric(data.IdGiroNegocio))
                      {
                        _gironegocio.IdGiroNegocio(data.IdGiroNegocio);
                        //deshabilitar botones agregar
                        $("#btnAgregarGiroNegocio").prop("disabled",false);

                        var id_gironegocio = "#"+ _gironegocio.IdGiroNegocio()+'_tr_gironegocio';
                        self.HabilitarFilaInputGiroNegocio(id_gironegocio, false);

                        var idbutton ="#"+_gironegocio.IdGiroNegocio()+"_button_GiroNegocio";
                        $(idbutton).hide();

                         _gironegocio.Confirmar(null,event);
                         self.HabilitarButtonsGiroNegocio(null, true);

                        existecambio = false;
                        _input_habilitado_gironegocio = false;
                        _modo_nuevo_gironegocio = false;

                      }
                      else {
                        alertify.alert(data.IdGiroNegocio);
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

    self.ActualizarGiroNegocio = function(data,event) {
          console.log("ActualizarGiroNegocio");
          console.log(_gironegocio.NombreGiroNegocio());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _gironegocio});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cGiroNegocio/ActualizarGiroNegocio',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarGiroNegocio").prop("disabled",false);
                          console.log("ID5:"+_gironegocio.IdGiroNegocio());
                          _gironegocio.Confirmar(null,event);

                          var id_gironegocio = "#"+ _gironegocio.IdGiroNegocio()+'_tr_gironegocio';
                          self.HabilitarFilaInputGiroNegocio(id_gironegocio, false);

                          var idbutton ="#"+_gironegocio.IdGiroNegocio()+"_button_GiroNegocio";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_gironegocio = false;
                          _modo_nuevo_gironegocio = false;

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

    self.EditarGiroNegocio = function(data, event) {

      if(event)
      {
        console.log("EditarGiroNegocio");
        console.log("ID.:"+data.IdGiroNegocio());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_gironegocio);

        if( _modo_nuevo_gironegocio == true )
        {

        }
        else {

          if (_gironegocio.IdGiroNegocio() == data.IdGiroNegocio())
          {

            if (_input_habilitado_gironegocio == true)
            {
              $("#btnAgregarGiroNegocio").prop("disabled",false);
              data.Deshacer(null,event);
              var id_gironegocio = "#"+ data.IdGiroNegocio()+'_tr_gironegocio';
              self.HabilitarFilaInputGiroNegocio(id_gironegocio, false);

              var idbutton = "#"+_gironegocio.IdGiroNegocio()+"_button_GiroNegocio";
              $(idbutton).hide();

              _input_habilitado_gironegocio =false;


            }
            else {
              $("#btnAgregarGiroNegocio").prop("disabled",true);
              var id_gironegocio = "#"+ data.IdGiroNegocio()+'_tr_gironegocio';
              self.HabilitarFilaInputGiroNegocio(id_gironegocio, true);

              var idbutton = "#"+data.IdGiroNegocio()+"_button_GiroNegocio";

              var idinput = "#"+data.IdGiroNegocio()+"_input_NombreGiroNegocio";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_gironegocio = true;
            }

          }
          else {
            $("#btnAgregarGiroNegocio").prop("disabled",true);
            if( _input_habilitado_gironegocio == true)
            {
              //deshabilitar campo origen

              var id_gironegocio = "#"+ _gironegocio.IdGiroNegocio()+'_tr_gironegocio';
              self.HabilitarFilaInputGiroNegocio(id_gironegocio, false);

              var idbutton = "#"+_gironegocio.IdGiroNegocio()+"_button_GiroNegocio";

              _gironegocio.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_gironegocio = "#"+ data.IdGiroNegocio()+'_tr_gironegocio';
            self.HabilitarFilaInputGiroNegocio(id_gironegocio, true);

            var idbutton = "#"+data.IdGiroNegocio()+"_button_GiroNegocio";

            var idinput = "#"+data.IdGiroNegocio()+"_input_NombreGiroNegocio";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_gironegocio = true;
          }


        }

      }

    }

    self.PreBorrarGiroNegocio = function (data) {

      if(_modo_nuevo_gironegocio == false)
      {
        _gironegocio.Deshacer(null, event);
        _input_habilitado_gironegocio = false;
        $("#btnAgregarGiroNegocio").prop("disabled",false);
        self.HabilitarTablaSpanGiroNegocio(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarGiroNegocio");
          console.log(data.IdGiroNegocio());
          self.HabilitarButtonsGiroNegocio(null, true);
          if (data.IdGiroNegocio() != null)
            self.BorrarGiroNegocio(data);
          else
          {
            $("#btnAgregarGiroNegocio").prop("disabled",false);
            _input_habilitado_gironegocio = false;
            _modo_nuevo_gironegocio = false;
            self.dataGiroNegocio.GirosNegocio.remove(data);
            var tabla = $('#DataTables_Table_0_gironegocio');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarGirosNegocio();
          }
        });
      }, 200);

    }

    self.BorrarGiroNegocio = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cGiroNegocio/BorrarGiroNegocio',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarGiroNegocio").prop("disabled",false);
                      self.HabilitarTablaSpanGiroNegocio(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataGiroNegocio.GirosNegocio.remove(objeto);
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


    self.OnClickGiroNegocio = function(data ,event) {

      if(event)
      {
          console.log("OnClickGiroNegocio");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_gironegocio);

          if( _modo_nuevo_gironegocio == true )
          {

          }
          else
          {

            $("#btnAgregarGiroNegocio").prop("disabled",true);
            if(_gironegocio.IdGiroNegocio() !=  data.IdGiroNegocio())
            {
              if (_input_habilitado_gironegocio == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _gironegocio.Deshacer(null, event);

                //var id_gironegocio = "#" + _id_filagironegocio_anterior;
                var id_gironegocio = "#" + _gironegocio.IdGiroNegocio()+'_tr_gironegocio';
                self.HabilitarFilaInputGiroNegocio(id_gironegocio, false);

                var idbutton = "#"+_gironegocio.IdGiroNegocio()+"_button_GiroNegocio";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_gironegocio.IdGiroNegocio());
              console.log(data.IdGiroNegocio());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_gironegocio = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_gironegocio, "span") || $.isSubstring(id_fila_gironegocio, "input")){
                id_fila_gironegocio = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filagironegocio_anterior = $(id_fila_gironegocio).parent()[0].id;
              var idspan ="#"+$(id_fila_gironegocio).find('span').attr('id');
              var idinput ="#"+$(id_fila_gironegocio).find('input').attr('id');
              self.HabilitarFilaInputGiroNegocio("#" + $(id_fila_gironegocio).parent()[0].id, true);

              var idbutton = "#"+data.IdGiroNegocio()+"_button_GiroNegocio";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_gironegocio = true;

              }
              else {
                if (_input_habilitado_gironegocio == false)
                {
                  var id_fila_gironegocio = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_gironegocio, "span") || $.isSubstring(id_fila_gironegocio, "input")){
                    id_fila_gironegocio = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputGiroNegocio("#" + $(id_fila_gironegocio).parent()[0].id, true);

                  var idbutton = "#"+data.IdGiroNegocio()+"_button_GiroNegocio";
                  var idinput ="#"+$(id_fila_gironegocio).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_gironegocio = true;
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
    self.OnKeyUpGiroNegocio = function(data, event){
      if(event)
      {
       console.log("OnKeyUpGiroNegocio");

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
         var idinputnombre = _gironegocio.IdGiroNegocio() + '_input_NombreGiroNegocio';

         if(event.target.id == idinputnombre)
         {
           _gironegocio.NombreGiroNegocio(event.target.value);
         }


         if(_modo_nuevo_gironegocio == true)
         {
           self.InsertarGiroNegocio(_gironegocio,event);
         }
         else
         {
           self.ActualizarGiroNegocio(_gironegocio,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado_gironegocio == true)
        {
          if(_modo_nuevo_gironegocio == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_gironegocio);
              self.dataGiroNegocio.GirosNegocio.remove(_gironegocio);
              var tabla = $('#DataTables_Table_0_gironegocio');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarGiroNegocio").prop("disabled",false);
              self.HabilitarButtonsGiroNegocio(null, true);
               _modo_nuevo_gironegocio = false;
               _input_habilitado_gironegocio = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_gironegocio._NombreGiroNegocio());
            //revertir texto
            //data.NombreGiroNegocio(_gironegocio.NombreGiroNegocio());

             _gironegocio.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarGiroNegocio").prop("disabled",false);

            /*var id_fila_gironegocio = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_gironegocio, "span") || $.isSubstring(id_fila_gironegocio, "input")){
              id_fila_gironegocio = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputGiroNegocio("#" + $(id_fila_gironegocio).parent()[0].id, false);*/
            self.HabilitarTablaSpanGiroNegocio(null, true);

            var idbutton ="#"+_gironegocio.IdGiroNegocio()+"_button_GiroNegocio";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_gironegocio = false;
            _input_habilitado_gironegocio = false;
          }
        }
      }
    }


    self.GuardarGiroNegocio = function(data,event) {
      if(event)
      {
         console.log("GuardarGiroNegocio");
         console.log(_nombregironegocio);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _gironegocio.IdGiroNegocio() + '_input_NombreGiroNegocio';

          if(event.target.id == idinputnombre)
          {
            _gironegocio.NombreGiroNegocio(_nombregironegocio);
          }
         //_gironegocio.NombreGiroNegocio(_nombregironegocio);

         if(_modo_nuevo_gironegocio == true)
         {
           self.InsertarGiroNegocio(_gironegocio,event);
         }
         else
         {
           self.ActualizarGiroNegocio(_gironegocio,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
