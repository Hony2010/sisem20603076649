
GruposParametroModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreGrupoParametro = ko.observable(data.NombreGrupoParametro);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreGrupoParametro());

        self.NombreGrupoParametro.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreGrupoParametro("");
        self.NombreGrupoParametro(self._NombreGrupoParametro());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreGrupoParametro.valueHasMutated();
        self._NombreGrupoParametro(self.NombreGrupoParametro());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreGrupoParametro());
}

GrupoParametroModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'GruposParametro': {
        create: function (options) {
            if (options)
              return new GruposParametroModel(options.data);
            }
    },
    'GrupoParametro': {
        create: function (options) {
            if (options)
              return new GrupoParametroModel(options.data);
            }
    }

}

IndexGrupoParametro = function (data) {

    var _modo_deshacer = false;
    var _nombregrupoparametro;
    var _input_habilitado_grupoparametro = false;
    var _idgrupoparametro;
    var _grupoparametro;
    var _modo_nuevo_grupoparametro = false;
    var _id_filagrupoparametro_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarGruposParametro = function() {
        console.log("ListarGruposParametro");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cGrupoParametro/ListarGruposParametro',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataGrupoParametro.GruposParametro([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.dataGrupoParametro.GruposParametro.push(new GruposParametroModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_grupoparametro == false)
      {
        var id = "#"+data.IdGrupoParametro()+'_tr_grupoparametro';
        $(id).addClass('active').siblings().removeClass('active');
        _grupoparametro = data;
      }

    }

    self.FilaButtonsGrupoParametro = function (data, event)  {
      console.log("BUTTONS");
      console.log("EVENTTARGET: " + $(event.target).attr('class'));
      console.log("THIS: " + $(this).attr('class'));
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_grupoparametro);
          if(_modo_nuevo_grupoparametro == true)
          return;

          _grupoparametro.Deshacer(null, event);
          _input_habilitado_grupoparametro = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarGrupoParametro").prop("disabled",false);
          self.HabilitarTablaSpanGrupoParametro(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdGrupoParametro()+'_tr_grupoparametro';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_grupoparametro == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.dataGrupoParametro.GruposParametro(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdGrupoParametro();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdGrupoParametro()+'_tr_grupoparametro';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_grupoparametro == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idgrupoparametro = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.dataGrupoParametro.GruposParametro(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idgrupoparametro == item.IdGrupoParametro();
            });

          if(match)
          {
            _grupoparametro = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputGrupoParametro = function (data, option)  {
      //var id = "#"+data.IdGrupoParametro();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputGrupoParametro').hide();
        $(id).find('.class_SpanGrupoParametro').show();
      }
      else
      {
        $(id).find('.class_InputGrupoParametro').show();
        $(id).find('.class_SpanGrupoParametro').hide();
      }

    }

    self.HabilitarTablaSpanGrupoParametro = function (data, option)  {
      //var id = "#"+data.IdGrupoParametro();
      var id = "#DataTables_Table_0_grupoparametro";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanGrupoParametro').hide();
        $(id).find('.class_InputGrupoParametro').show();
        //$(id).find('.guardar_button_GrupoParametro').show();
        //_input_habilitado_grupoparametro = true;
      }
      else {
        $(id).find('.class_SpanGrupoParametro').show();
        $(id).find('.class_InputGrupoParametro').hide();
        $(id).find('.guardar_button_GrupoParametro').hide();
        //_input_habilitado_grupoparametro = false;
      }

    }

    self.HabilitarButtonsGrupoParametro = function(data, option){
      var id = "#DataTables_Table_0_grupoparametro";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_GrupoParametro').prop("disabled", true);
        $(id).find('.borrar_button_GrupoParametro').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_GrupoParametro').prop("disabled", false);
        $(id).find('.borrar_button_GrupoParametro').prop("disabled", false);
      }
    }


    self.AgregarGrupoParametro = function(data,event) {
          console.log("AgregarGrupoParametro");

          if ( _input_habilitado_grupoparametro == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.dataGrupoParametro.GrupoParametro);
            _grupoparametro = new GruposParametroModel(objeto);
            self.dataGrupoParametro.GruposParametro.push(_grupoparametro);

            //Deshabilitando buttons
            self.HabilitarButtonsGrupoParametro(null, false);
            $("#null_editar_button_GrupoParametro").prop("disabled", true);
            $("#null_borrar_button_GrupoParametro").prop("disabled", false);


            $("#btnAgregarGrupoParametro").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdGrupoParametro());

            var idspan ="#"+objeto.IdGrupoParametro()+"_span_NombreGrupoParametro";
            var idinput ="#"+objeto.IdGrupoParametro()+"_input_NombreGrupoParametro";

            var idbutton ="#"+objeto.IdGrupoParametro()+"_button_GrupoParametro";

            console.log(idbutton);
            //self.HabilitarFilaInputGrupoParametro(_grupoparametro, true);
            //self.HabilitarFilaSpanGrupoParametro(_grupoparametro, false);

            $(idspan).hide();
            $(idinput).show();
            $(idbutton).show();
            $(idinput).focus();

            _modo_nuevo_grupoparametro = true;
            _input_habilitado_grupoparametro = true;

            var tabla = $('#DataTables_Table_0_grupoparametro');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarGrupoParametro =function(data,event){

      if(event)
      {
        console.log("InsertarGrupoParametro");
        console.log(_grupoparametro.NombreGrupoParametro());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _grupoparametro});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cGrupoParametro/InsertarGrupoParametro',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarGrupoParametro");
                      console.log(data);

                      if ($.isNumeric(data.IdGrupoParametro))
                      {
                        _grupoparametro.IdGrupoParametro(data.IdGrupoParametro);
                        //deshabilitar botones agregar
                        $("#btnAgregarGrupoParametro").prop("disabled",false);

                        var id_grupoparametro = "#"+ _grupoparametro.IdGrupoParametro()+'_tr_grupoparametro';
                        self.HabilitarFilaInputGrupoParametro(id_grupoparametro, false);

                        var idbutton ="#"+_grupoparametro.IdGrupoParametro()+"_button_GrupoParametro";
                        $(idbutton).hide();

                         _grupoparametro.Confirmar(null,event);
                         self.HabilitarButtonsGrupoParametro(null, true);

                        existecambio = false;
                        _input_habilitado_grupoparametro = false;
                        _modo_nuevo_grupoparametro = false;

                      }
                      else {
                        alertify.alert(data.IdGrupoParametro);
                      }

                  }
                  $("#loader").hide();
                  //$("#loader").fadeOut("slow");
                },
                error : function (jqXHR, textStatus, errorThrown) {
                  //console.log(jqXHR.responseText);
                  $("#loader").hide();
                }
          });
        }
    }

    self.ActualizarGrupoParametro = function(data,event) {
          console.log("ActualizarGrupoParametro");
          console.log(_grupoparametro.NombreGrupoParametro());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _grupoparametro});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cGrupoParametro/ActualizarGrupoParametro',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarGrupoParametro").prop("disabled",false);
                          console.log("ID5:"+_grupoparametro.IdGrupoParametro());
                          _grupoparametro.Confirmar(null,event);

                          var id_grupoparametro = "#"+ _grupoparametro.IdGrupoParametro()+'_tr_grupoparametro';
                          self.HabilitarFilaInputGrupoParametro(id_grupoparametro, false);

                          var idbutton ="#"+_grupoparametro.IdGrupoParametro()+"_button_GrupoParametro";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_grupoparametro = false;
                          _modo_nuevo_grupoparametro = false;

                        }
                        else {
                          alertify.alert(data);
                        }
                    }

                    $("#loader").hide();
                    //$("#loader").fadeOut("slow");
                },
                error : function (jqXHR, textStatus, errorThrown) {
                  //console.log(jqXHR.responseText);
                  $("#loader").hide();
                }
          });
    }

    self.EditarGrupoParametro = function(data, event) {

      if(event)
      {
        console.log("EditarGrupoParametro");
        console.log("ID.:"+data.IdGrupoParametro());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_grupoparametro);

        if( _modo_nuevo_grupoparametro == true )
        {

        }
        else {

          if (_grupoparametro.IdGrupoParametro() == data.IdGrupoParametro())
          {

            if (_input_habilitado_grupoparametro == true)
            {
              $("#btnAgregarGrupoParametro").prop("disabled",false);
              data.Deshacer(null,event);
              var id_grupoparametro = "#"+ data.IdGrupoParametro()+'_tr_grupoparametro';
              self.HabilitarFilaInputGrupoParametro(id_grupoparametro, false);

              var idbutton = "#"+_grupoparametro.IdGrupoParametro()+"_button_GrupoParametro";
              $(idbutton).hide();

              _input_habilitado_grupoparametro =false;


            }
            else {
              $("#btnAgregarGrupoParametro").prop("disabled",true);
              var id_grupoparametro = "#"+ data.IdGrupoParametro()+'_tr_grupoparametro';
              self.HabilitarFilaInputGrupoParametro(id_grupoparametro, true);

              var idbutton = "#"+data.IdGrupoParametro()+"_button_GrupoParametro";

              var idinput = "#"+data.IdGrupoParametro()+"_input_NombreGrupoParametro";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_grupoparametro = true;
            }

          }
          else {
            $("#btnAgregarGrupoParametro").prop("disabled",true);
            if( _input_habilitado_grupoparametro == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_grupoparametro.IdGrupoParametro());

              var id_grupoparametro = "#"+ _grupoparametro.IdGrupoParametro()+'_tr_grupoparametro';
              self.HabilitarFilaInputGrupoParametro(id_grupoparametro, false);

              var idbutton = "#"+_grupoparametro.IdGrupoParametro()+"_button_GrupoParametro";

              _grupoparametro.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_grupoparametro = "#"+ data.IdGrupoParametro()+'_tr_grupoparametro';
            self.HabilitarFilaInputGrupoParametro(id_grupoparametro, true);

            var idbutton = "#"+data.IdGrupoParametro()+"_button_GrupoParametro";

            var idinput = "#"+data.IdGrupoParametro()+"_input_NombreGrupoParametro";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_grupoparametro = true;
          }


        }

      }

    }

    self.PreBorrarGrupoParametro = function (data) {

      if(_modo_nuevo_grupoparametro == false)
      {
        _grupoparametro.Deshacer(null, event);
        _input_habilitado_grupoparametro = false;
        $("#btnAgregarGrupoParametro").prop("disabled",false);
        self.HabilitarTablaSpanGrupoParametro(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarGrupoParametro");
          console.log(data.IdGrupoParametro());
          self.HabilitarButtonsGrupoParametro(null, true);
          if (data.IdGrupoParametro() != null)
            self.BorrarGrupoParametro(data);
          else
          {
            $("#btnAgregarGrupoParametro").prop("disabled",false);
            _input_habilitado_grupoparametro = false;
            _modo_nuevo_grupoparametro = false;
            self.dataGrupoParametro.GruposParametro.remove(data);
            var tabla = $('#DataTables_Table_0_grupoparametro');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarGruposParametro();
          }
        });
      }, 200);

    }

    self.BorrarGrupoParametro = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cGrupoParametro/BorrarGrupoParametro',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarGrupoParametro").prop("disabled",false);
                      self.HabilitarTablaSpanGrupoParametro(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataGrupoParametro.GruposParametro.remove(objeto);
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


    self.OnClickGrupoParametro = function(data ,event) {

      if(event)
      {
          console.log("OnClickGrupoParametro");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_grupoparametro);

          if( _modo_nuevo_grupoparametro == true )
          {

          }
          else
          {

            $("#btnAgregarGrupoParametro").prop("disabled",true);
            if(_grupoparametro.IdGrupoParametro() !=  data.IdGrupoParametro())
            {
              if (_input_habilitado_grupoparametro == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _grupoparametro.Deshacer(null, event);

                //var id_grupoparametro = "#" + _id_filagrupoparametro_anterior;
                var id_grupoparametro = "#" + _grupoparametro.IdGrupoParametro()+'_tr_grupoparametro';
                self.HabilitarFilaInputGrupoParametro(id_grupoparametro, false);

                var idbutton = "#"+_grupoparametro.IdGrupoParametro()+"_button_GrupoParametro";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_grupoparametro.IdGrupoParametro());
              console.log(data.IdGrupoParametro());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_grupoparametro = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_grupoparametro, "span") || $.isSubstring(id_fila_grupoparametro, "input")){
                id_fila_grupoparametro = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filagrupoparametro_anterior = $(id_fila_grupoparametro).parent()[0].id;
              var idspan ="#"+$(id_fila_grupoparametro).find('span').attr('id');
              var idinput ="#"+$(id_fila_grupoparametro).find('input').attr('id');
              self.HabilitarFilaInputGrupoParametro("#" + $(id_fila_grupoparametro).parent()[0].id, true);

              var idbutton = "#"+data.IdGrupoParametro()+"_button_GrupoParametro";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_grupoparametro = true;

              }
              else {
                if (_input_habilitado_grupoparametro == false)
                {
                  var id_fila_grupoparametro = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_grupoparametro, "span") || $.isSubstring(id_fila_grupoparametro, "input")){
                    id_fila_grupoparametro = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputGrupoParametro("#" + $(id_fila_grupoparametro).parent()[0].id, true);

                  var idbutton = "#"+data.IdGrupoParametro()+"_button_GrupoParametro";
                  var idinput ="#"+$(id_fila_grupoparametro).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_grupoparametro = true;
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
    self.OnKeyUpGrupoParametro = function(data, event){
      if(event)
      {
       console.log("OnKeyUpGrupoParametro");

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
         var idinputnombre = _grupoparametro.IdGrupoParametro() + '_input_NombreGrupoParametro';

         if(event.target.id == idinputnombre)
         {
           _grupoparametro.NombreGrupoParametro(event.target.value);
         }


         if(_modo_nuevo_grupoparametro == true)
         {
           self.InsertarGrupoParametro(_grupoparametro,event);
         }
         else
         {
           self.ActualizarGrupoParametro(_grupoparametro,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado_grupoparametro == true)
        {
          if(_modo_nuevo_grupoparametro == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_grupoparametro);
              self.dataGrupoParametro.GruposParametro.remove(_grupoparametro);
              var tabla = $('#DataTables_Table_0_grupoparametro');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarGrupoParametro").prop("disabled",false);
              self.HabilitarButtonsGrupoParametro(null, true);
               _modo_nuevo_grupoparametro = false;
               _input_habilitado_grupoparametro = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_grupoparametro._NombreGrupoParametro());
            //revertir texto
            //data.NombreGrupoParametro(_grupoparametro.NombreGrupoParametro());

             _grupoparametro.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarGrupoParametro").prop("disabled",false);

            /*var id_fila_grupoparametro = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_grupoparametro, "span") || $.isSubstring(id_fila_grupoparametro, "input")){
              id_fila_grupoparametro = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputGrupoParametro("#" + $(id_fila_grupoparametro).parent()[0].id, false);*/
            self.HabilitarTablaSpanGrupoParametro(null, true);

            var idbutton ="#"+_grupoparametro.IdGrupoParametro()+"_button_GrupoParametro";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_grupoparametro = false;
            _input_habilitado_grupoparametro = false;
          }

        }
      }
    }

    self.GuardarGrupoParametro = function(data,event) {
      if(event)
      {
         console.log("GuardarGrupoParametro");
         console.log(_nombregrupoparametro);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _grupoparametro.IdGrupoParametro() + '_input_NombreGrupoParametro';

          if(event.target.id == idinputnombre)
          {
            _grupoparametro.NombreGrupoParametro(_nombregrupoparametro);
          }
         //_grupoparametro.NombreGrupoParametro(_nombregrupoparametro);

         if(_modo_nuevo_grupoparametro == true)
         {
           self.InsertarGrupoParametro(_grupoparametro,event);
         }
         else
         {
           self.ActualizarGrupoParametro(_grupoparametro,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
