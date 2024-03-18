
TiposTarjetaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreTarjeta = ko.observable(data.NombreTarjeta);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreTarjeta());

        self.NombreTarjeta.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreTarjeta("");
        self.NombreTarjeta(self._NombreTarjeta());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreTarjeta.valueHasMutated();
        self._NombreTarjeta(self.NombreTarjeta());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreTarjeta());
}

TipoTarjetaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'TiposTarjeta': {
        create: function (options) {
            if (options)
              return new TiposTarjetaModel(options.data);
            }
    },
    'TipoTarjeta': {
        create: function (options) {
            if (options)
              return new TipoTarjetaModel(options.data);
            }
    }

}

Index = function (data) {

    var _modo_deshacer = false;
    var _nombretipotarjeta;
    var _input_habilitado = false;
    var _idtipotarjeta;
    var _tipotarjeta;
    var _modo_nuevo = false;
    var _id_filatipotarjeta_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarTiposTarjeta = function() {
        console.log("ListarTiposTarjeta");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Venta/cTipoTarjeta/ListarTiposTarjeta',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.data.TiposTarjeta([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.data.TiposTarjeta.push(new TiposTarjetaModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo == false)
      {
        var id = "#"+data.IdTipoTarjeta();
        $(id).addClass('active').siblings().removeClass('active');
        _tipotarjeta = data;
      }

    }

    self.FilaButtonsTipoTarjeta = function (data, event)  {
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

          _tipotarjeta.Deshacer(null, event);
          _input_habilitado = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarTipoTarjeta").prop("disabled",false);
          self.HabilitarTablaSpanTipoTarjeta(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdTipoTarjeta();
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.data.TiposTarjeta(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdTipoTarjeta();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdTipoTarjeta();
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idtipotarjeta = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.data.TiposTarjeta(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idtipotarjeta == item.IdTipoTarjeta();
            });

          if(match)
          {
            _tipotarjeta = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputTipoTarjeta = function (data, option)  {
      //var id = "#"+data.IdTipoTarjeta();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputTipoTarjeta').hide();
        $(id).find('.class_SpanTipoTarjeta').show();
      }
      else
      {
        $(id).find('.class_InputTipoTarjeta').show();
        $(id).find('.class_SpanTipoTarjeta').hide();
      }

    }

    self.HabilitarTablaSpanTipoTarjeta = function (data, option)  {
      //var id = "#"+data.IdTipoTarjeta();
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanTipoTarjeta').hide();
        $(id).find('.class_InputTipoTarjeta').show();
        //$(id).find('.guardar_button_TipoTarjeta').show();
        //_input_habilitado = true;
      }
      else {
        $(id).find('.class_SpanTipoTarjeta').show();
        $(id).find('.class_InputTipoTarjeta').hide();
        $(id).find('.guardar_button_TipoTarjeta').hide();
        //_input_habilitado = false;
      }

    }

    self.HabilitarButtonsTipoTarjeta = function(data, option){
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_TipoTarjeta').prop("disabled", true);
        $(id).find('.borrar_button_TipoTarjeta').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_TipoTarjeta').prop("disabled", false);
        $(id).find('.borrar_button_TipoTarjeta').prop("disabled", false);
      }
    }


    self.AgregarTipoTarjeta = function(data,event) {
          console.log("AgregarTipoTarjeta");

          if ( _input_habilitado == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.data.TipoTarjeta);
            _tipotarjeta = new TiposTarjetaModel(objeto);
            self.data.TiposTarjeta.push(_tipotarjeta);

            //Deshabilitando buttons
            self.HabilitarButtonsTipoTarjeta(null, false);
            $("#null_editar_button_TipoTarjeta").prop("disabled", true);
            $("#null_borrar_button_TipoTarjeta").prop("disabled", false);


            $("#btnAgregarTipoTarjeta").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdTipoTarjeta());

            var idspan ="#"+objeto.IdTipoTarjeta()+"_span_NombreTarjeta";
            var idinput ="#"+objeto.IdTipoTarjeta()+"_input_NombreTarjeta";

            var idbutton ="#"+objeto.IdTipoTarjeta()+"_button_TipoTarjeta";

            console.log(idbutton);
            //self.HabilitarFilaInputTipoTarjeta(_tipotarjeta, true);
            //self.HabilitarFilaSpanTipoTarjeta(_tipotarjeta, false);

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

    self.InsertarTipoTarjeta =function(data,event){

      if(event)
      {
        console.log("InsertarTipoTarjeta");
        console.log(_tipotarjeta.NombreTarjeta());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _tipotarjeta});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/Venta/cTipoTarjeta/InsertarTipoTarjeta',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarTipoTarjeta");
                      console.log(data);

                      if ($.isNumeric(data.IdTipoTarjeta))
                      {
                        _tipotarjeta.IdTipoTarjeta(data.IdTipoTarjeta);
                        //deshabilitar botones agregar
                        $("#btnAgregarTipoTarjeta").prop("disabled",false);

                        var id_tipotarjeta = "#"+ _tipotarjeta.IdTipoTarjeta();
                        self.HabilitarFilaInputTipoTarjeta(id_tipotarjeta, false);

                        var idbutton ="#"+_tipotarjeta.IdTipoTarjeta()+"_button_TipoTarjeta";
                        $(idbutton).hide();

                         _tipotarjeta.Confirmar(null,event);
                         self.HabilitarButtonsTipoTarjeta(null, true);

                        existecambio = false;
                        _input_habilitado = false;
                        _modo_nuevo = false;

                      }
                      else {
                        alertify.alert(data.IdTipoTarjeta);
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

    self.ActualizarTipoTarjeta = function(data,event) {
          console.log("ActualizarTipoTarjeta");
          console.log(_tipotarjeta.NombreTarjeta());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _tipotarjeta});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/Venta/cTipoTarjeta/ActualizarTipoTarjeta',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarTipoTarjeta").prop("disabled",false);
                          console.log("ID5:"+_tipotarjeta.IdTipoTarjeta());
                          _tipotarjeta.Confirmar(null,event);

                          var id_tipotarjeta = "#"+ _tipotarjeta.IdTipoTarjeta();
                          self.HabilitarFilaInputTipoTarjeta(id_tipotarjeta, false);

                          var idbutton ="#"+_tipotarjeta.IdTipoTarjeta()+"_button_TipoTarjeta";
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

    self.EditarTipoTarjeta = function(data, event) {

      if(event)
      {
        console.log("EditarTipoTarjeta");
        console.log("ID.:"+data.IdTipoTarjeta());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_tipotarjeta);

        if( _modo_nuevo == true )
        {

        }
        else {

          if (_tipotarjeta.IdTipoTarjeta() == data.IdTipoTarjeta())
          {

            if (_input_habilitado == true)
            {
              $("#btnAgregarTipoTarjeta").prop("disabled",false);
              data.Deshacer(null,event);
              var id_tipotarjeta = "#"+ data.IdTipoTarjeta();
              self.HabilitarFilaInputTipoTarjeta(id_tipotarjeta, false);

              var idbutton = "#"+_tipotarjeta.IdTipoTarjeta()+"_button_TipoTarjeta";
              $(idbutton).hide();

              _input_habilitado =false;


            }
            else {
              $("#btnAgregarTipoTarjeta").prop("disabled",true);
              var id_tipotarjeta = "#"+ data.IdTipoTarjeta();
              self.HabilitarFilaInputTipoTarjeta(id_tipotarjeta, true);

              var idbutton = "#"+data.IdTipoTarjeta()+"_button_TipoTarjeta";

              var idinput = "#"+data.IdTipoTarjeta()+"_input_NombreTarjeta";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;
            }

          }
          else {
            $("#btnAgregarTipoTarjeta").prop("disabled",true);
            if( _input_habilitado == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_tipotarjeta.IdTipoTarjeta());

              var id_tipotarjeta = "#"+ _tipotarjeta.IdTipoTarjeta();
              self.HabilitarFilaInputTipoTarjeta(id_tipotarjeta, false);

              var idbutton = "#"+_tipotarjeta.IdTipoTarjeta()+"_button_TipoTarjeta";

              _tipotarjeta.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_tipotarjeta = "#"+ data.IdTipoTarjeta();
            self.HabilitarFilaInputTipoTarjeta(id_tipotarjeta, true);

            var idbutton = "#"+data.IdTipoTarjeta()+"_button_TipoTarjeta";

            var idinput = "#"+data.IdTipoTarjeta()+"_input_NombreTarjeta";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado = true;
          }


        }

      }

    }

    self.PreBorrarTipoTarjeta = function (data) {

      if(_modo_nuevo == false)
      {
        _tipotarjeta.Deshacer(null, event);
        _input_habilitado = false;
        $("#btnAgregarTipoTarjeta").prop("disabled",false);
        self.HabilitarTablaSpanTipoTarjeta(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarTipoTarjeta");
          console.log(data.IdTipoTarjeta());
          self.HabilitarButtonsTipoTarjeta(null, true);
          if (data.IdTipoTarjeta() != null)
            self.BorrarTipoTarjeta(data);
          else
          {
            $("#btnAgregarTipoTarjeta").prop("disabled",false);
            _input_habilitado = false;
            _modo_nuevo = false;
            self.data.TiposTarjeta.remove(data);
            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarTiposTarjeta();
          }
        });
      }, 200);

    }

    self.BorrarTipoTarjeta = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Venta/cTipoTarjeta/BorrarTipoTarjeta',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarTipoTarjeta").prop("disabled",false);
                      self.HabilitarTablaSpanTipoTarjeta(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.data.TiposTarjeta.remove(objeto);
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


    self.OnClickTipoTarjeta = function(data ,event) {

      if(event)
      {
          console.log("OnClickTipoTarjeta");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_tipotarjeta);

          if( _modo_nuevo == true )
          {

          }
          else
          {

            $("#btnAgregarTipoTarjeta").prop("disabled",true);
            if(_tipotarjeta.IdTipoTarjeta() !=  data.IdTipoTarjeta())
            {
              if (_input_habilitado == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _tipotarjeta.Deshacer(null, event);

                //var id_tipotarjeta = "#" + _id_filatipotarjeta_anterior;
                var id_tipotarjeta = "#" + _tipotarjeta.IdTipoTarjeta();
                self.HabilitarFilaInputTipoTarjeta(id_tipotarjeta, false);

                var idbutton = "#"+_tipotarjeta.IdTipoTarjeta()+"_button_TipoTarjeta";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_tipotarjeta.IdTipoTarjeta());
              console.log(data.IdTipoTarjeta());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_tipotarjeta = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_tipotarjeta, "span") || $.isSubstring(id_fila_tipotarjeta, "input")){
                id_fila_tipotarjeta = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filatipotarjeta_anterior = $(id_fila_tipotarjeta).parent()[0].id;
              var idspan ="#"+$(id_fila_tipotarjeta).find('span').attr('id');
              var idinput ="#"+$(id_fila_tipotarjeta).find('input').attr('id');
              self.HabilitarFilaInputTipoTarjeta("#" + $(id_fila_tipotarjeta).parent()[0].id, true);

              var idbutton = "#"+data.IdTipoTarjeta()+"_button_TipoTarjeta";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;

              }
              else {
                if (_input_habilitado == false)
                {
                  var id_fila_tipotarjeta = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_tipotarjeta, "span") || $.isSubstring(id_fila_tipotarjeta, "input")){
                    id_fila_tipotarjeta = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputTipoTarjeta("#" + $(id_fila_tipotarjeta).parent()[0].id, true);

                  var idbutton = "#"+data.IdTipoTarjeta()+"_button_TipoTarjeta";
                  var idinput ="#"+$(id_fila_tipotarjeta).find('input').attr('id');
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
    self.OnKeyUpTipoTarjeta = function(data, event){
      if(event)
      {
       console.log("OnKeyUpTipoTarjeta");

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
         var idinputnombre = _tipotarjeta.IdTipoTarjeta() + '_input_NombreTarjeta';

         if(event.target.id == idinputnombre)
         {
           _tipotarjeta.NombreTarjeta(event.target.value);
         }


         if(_modo_nuevo == true)
         {
           self.InsertarTipoTarjeta(_tipotarjeta,event);
         }
         else
         {
           self.ActualizarTipoTarjeta(_tipotarjeta,event);
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
            alertify.confirm("¿Desea borrar el registro?", function(){
              self.SeleccionarAnterior(_tipotarjeta);
              self.data.TiposTarjeta.remove(_tipotarjeta);
              var tabla = $('#DataTables_Table_0');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarTipoTarjeta").prop("disabled",false);
              self.HabilitarButtonsTipoTarjeta(null, true);
               _modo_nuevo = false;
               _input_habilitado = false;
            });
          }
          else
          {
            console.log("Escape - false");
            //revertir texto
            //data.NombreTarjeta(_tipotarjeta.NombreTarjeta());

             _tipotarjeta.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarTipoTarjeta").prop("disabled",false);

            /*var id_fila_tipotarjeta = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_tipotarjeta, "span") || $.isSubstring(id_fila_tipotarjeta, "input")){
              id_fila_tipotarjeta = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputTipoTarjeta("#" + $(id_fila_tipotarjeta).parent()[0].id, false);*/
            self.HabilitarTablaSpanTipoTarjeta(null, true);

            var idbutton ="#"+_tipotarjeta.IdTipoTarjeta()+"_button_TipoTarjeta";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo = false;
            _input_habilitado = false;
          }

        }
      }
    }

    self.GuardarTipoTarjeta = function(data,event) {
      if(event)
      {
         console.log("GuardarTipoTarjeta");
         console.log(_nombretipotarjeta);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _tipotarjeta.IdTipoTarjeta() + '_input_NombreTarjeta';

          if(event.target.id == idinputnombre)
          {
            _tipotarjeta.NombreTarjeta(_nombretipotarjeta);
          }
         //_tipotarjeta.NombreTarjeta(_nombretipotarjeta);

         if(_modo_nuevo == true)
         {
           self.InsertarTipoTarjeta(_tipotarjeta,event);
         }
         else
         {
           self.ActualizarTipoTarjeta(_tipotarjeta,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
