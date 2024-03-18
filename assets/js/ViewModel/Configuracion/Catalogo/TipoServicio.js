
TiposServicioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreTipoServicio = ko.observable(data.NombreTipoServicio);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreTipoServicio());

        self.NombreTipoServicio.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreTipoServicio("");
        self.NombreTipoServicio(self._NombreTipoServicio());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreTipoServicio.valueHasMutated();
        self._NombreTipoServicio(self.NombreTipoServicio());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreTipoServicio());
}

TipoServicioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'TiposServicio': {
        create: function (options) {
            if (options)
              return new TiposServicioModel(options.data);
            }
    },
    'TipoServicio': {
        create: function (options) {
            if (options)
              return new TipoServicioModel(options.data);
            }
    }

}

IndexTipoServicio = function (data) {

    var _modo_deshacer = false;
    var _nombretiposervicio;
    var _input_habilitado_tiposervicio = false;
    var _idtiposervicio;
    var _tiposervicio;
    var _modo_nuevo_tiposervicio = false;
    var _id_filatiposervicio_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarTiposServicio = function() {
        console.log("ListarTiposServicio");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cTipoServicio/ListarTiposServicio',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.data.TiposServicio([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.data.TiposServicio.push(new TiposServicioModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_tiposervicio == false)
      {
        var id = "#"+data.IdTipoServicio()+'_tr_tipoServicio' ;
        $(id).addClass('active').siblings().removeClass('active');
        _tiposervicio = data;
      }

    }

    self.FilaButtonsTipoServicio = function (data, event)  {
      console.log("BUTTONS");
      console.log("EVENTTARGET: " + $(event.target).attr('class'));
      console.log("THIS: " + $(this).attr('class'));
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_tiposervicio);
          if(_modo_nuevo_tiposervicio == true)
          return;

          _tiposervicio.Deshacer(null, event);
          _input_habilitado_tiposervicio = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarTipoServicio").prop("disabled",false);
          self.HabilitarTablaSpanTipoServicio(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdTipoServicio()+'_tr_tipoServicio';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_tiposervicio == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcTipoServicio.dataTipoServicio.TiposServicio(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdTipoServicio();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdTipoServicio()+'_tr_tipoServicio';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_tiposervicio == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idtiposervicio = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcTipoServicio.dataTipoServicio.TiposServicio(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idtiposervicio == item.IdTipoServicio();
            });

          if(match)
          {
            _tiposervicio = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputTipoServicio = function (data, option)  {
      //var id = "#"+data.IdTipoServicio();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputTipoServicio').hide();
        $(id).find('.class_SpanTipoServicio').show();
      }
      else
      {
        $(id).find('.class_InputTipoServicio').show();
        $(id).find('.class_SpanTipoServicio').hide();
      }

    }

    self.HabilitarTablaSpanTipoServicio = function (data, option)  {
      //var id = "#"+data.IdTipoServicio();
      var id = "#DataTables_Table_0_tipoServicio";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanTipoServicio').hide();
        $(id).find('.class_InputTipoServicio').show();
        //$(id).find('.guardar_button_TipoServicio').show();
        //_input_habilitado_tiposervicio = true;
      }
      else {
        $(id).find('.class_SpanTipoServicio').show();
        $(id).find('.class_InputTipoServicio').hide();
        $(id).find('.guardar_button_TipoServicio').hide();
        //_input_habilitado_tiposervicio = false;
      }

    }

    self.HabilitarButtonsTipoServicio = function(data, option){
      var id = "#DataTables_Table_0_tipoServicio";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_TipoServicio').prop("disabled", true);
        $(id).find('.borrar_button_TipoServicio').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_TipoServicio').prop("disabled", false);
        $(id).find('.borrar_button_TipoServicio').prop("disabled", false);
      }
    }


    self.AgregarTipoServicio = function(data,event) {
          console.log("AgregarTipoServicio");

          if ( _input_habilitado_tiposervicio == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(vistaModeloCatalogo.vmcTipoServicio.dataTipoServicio.TipoServicio);
            _tiposervicio = new TiposServicioModel(objeto);
            vistaModeloCatalogo.vmcTipoServicio.dataTipoServicio.TiposServicio.push(_tiposervicio);

            //Deshabilitando buttons
            self.HabilitarButtonsTipoServicio(null, false);
            $("#null_editar_button_TipoServicio").prop("disabled", true);
            $("#null_borrar_button_TipoServicio").prop("disabled", false);


            $("#btnAgregarTipoServicio").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdTipoServicio());

            var idspan ="#"+objeto.IdTipoServicio()+"_span_NombreTipoServicio";
            var idinput ="#"+objeto.IdTipoServicio()+"_input_NombreTipoServicio";

            var idbutton ="#"+objeto.IdTipoServicio()+"_button_TipoServicio";

            console.log(idbutton);
            //self.HabilitarFilaInputTipoServicio(_tiposervicio, true);
            //self.HabilitarFilaSpanTipoServicio(_tiposervicio, false);

            $(idspan).hide();
            $(idinput).show();
            $(idbutton).show();
            $(idinput).focus();

            _modo_nuevo_tiposervicio = true;
            _input_habilitado_tiposervicio = true;

            var tabla = $('#DataTables_Table_0_tipoServicio');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarTipoServicio =function(data,event){

      if(event)
      {
        $("#loader").show();
        console.log("InsertarTipoServicio");
        console.log(_tiposervicio.NombreTipoServicio());

        var objeto = data;
        var datajs = ko.toJS({"Data" : _tiposervicio});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cTipoServicio/InsertarTipoServicio',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarTipoServicio");
                      console.log(data);

                      if ($.isNumeric(data.IdTipoServicio))
                      {
                        _tiposervicio.IdTipoServicio(data.IdTipoServicio);
                        //deshabilitar botones agregar
                        $("#btnAgregarTipoServicio").prop("disabled",false);

                        var id_tiposervicio = "#"+ _tiposervicio.IdTipoServicio()+'_tr_tipoServicio';
                        self.HabilitarFilaInputTipoServicio(id_tiposervicio, false);

                        var idbutton ="#"+_tiposervicio.IdTipoServicio()+"_button_TipoServicio";
                        $(idbutton).hide();

                         _tiposervicio.Confirmar(null,event);
                         self.HabilitarButtonsTipoServicio(null, true);

                        existecambio = false;
                        _input_habilitado_tiposervicio = false;
                        _modo_nuevo_tiposervicio = false;

                      }
                      else {
                        alertify.alert(data.IdTipoServicio);
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

    self.ActualizarTipoServicio = function(data,event) {
          console.log("ActualizarTipoServicio");
          console.log(_tiposervicio.NombreTipoServicio());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _tiposervicio});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/Catalogo/cTipoServicio/ActualizarTipoServicio',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarTipoServicio").prop("disabled",false);
                          console.log("ID5:"+_tiposervicio.IdTipoServicio());
                          _tiposervicio.Confirmar(null,event);

                          var id_tiposervicio = "#"+ _tiposervicio.IdTipoServicio()+'_tr_tipoServicio';
                          self.HabilitarFilaInputTipoServicio(id_tiposervicio, false);

                          var idbutton ="#"+_tiposervicio.IdTipoServicio()+"_button_TipoServicio";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_tiposervicio = false;
                          _modo_nuevo_tiposervicio = false;

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

    self.EditarTipoServicio = function(data, event) {

      if(event)
      {
        console.log("EditarTipoServicio");
        console.log("ID.:"+data.IdTipoServicio());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_tiposervicio);

        if( _modo_nuevo_tiposervicio == true )
        {

        }
        else {

          if (_tiposervicio.IdTipoServicio() == data.IdTipoServicio())
          {

            if (_input_habilitado_tiposervicio == true)
            {
              $("#btnAgregarTipoServicio").prop("disabled",false);
              data.Deshacer(null,event);
              var id_tiposervicio = "#"+ data.IdTipoServicio()+'_tr_tipoServicio';
              self.HabilitarFilaInputTipoServicio(id_tiposervicio, false);

              var idbutton = "#"+_tiposervicio.IdTipoServicio()+"_button_TipoServicio";
              $(idbutton).hide();

              _input_habilitado_tiposervicio =false;


            }
            else {
              $("#btnAgregarTipoServicio").prop("disabled",true);
              var id_tiposervicio = "#"+ data.IdTipoServicio()+'_tr_tipoServicio';
              self.HabilitarFilaInputTipoServicio(id_tiposervicio, true);

              var idbutton = "#"+data.IdTipoServicio()+"_button_TipoServicio";

              var idinput = "#"+data.IdTipoServicio()+"_input_NombreTipoServicio";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_tiposervicio = true;
            }

          }
          else {
            $("#btnAgregarTipoServicio").prop("disabled",true);
            if( _input_habilitado_tiposervicio == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_tiposervicio.IdTipoServicio());

              var id_tiposervicio = "#"+ _tiposervicio.IdTipoServicio()+'_tr_tipoServicio';
              self.HabilitarFilaInputTipoServicio(id_tiposervicio, false);

              var idbutton = "#"+_tiposervicio.IdTipoServicio()+"_button_TipoServicio";

              _tiposervicio.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_tiposervicio = "#"+ data.IdTipoServicio()+'_tr_tipoServicio';
            self.HabilitarFilaInputTipoServicio(id_tiposervicio, true);

            var idbutton = "#"+data.IdTipoServicio()+"_button_TipoServicio";

            var idinput = "#"+data.IdTipoServicio()+"_input_NombreTipoServicio";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_tiposervicio = true;
          }


        }

      }

    }

    self.PreBorrarTipoServicio = function (data) {

      if(_modo_nuevo_tiposervicio == false)
      {
        _tiposervicio.Deshacer(null, event);
        _input_habilitado_tiposervicio = false;
        $("#btnAgregarTipoServicio").prop("disabled",false);
        self.HabilitarTablaSpanTipoServicio(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarTipoServicio");
          console.log(data.IdTipoServicio());
          self.HabilitarButtonsTipoServicio(null, true);
          if (data.IdTipoServicio() != null)
          {
            self.BorrarTipoServicio(data);
          }
          else
          {
            $("#btnAgregarTipoServicio").prop("disabled",false);
            _input_habilitado_tiposervicio = false;
            _modo_nuevo_tiposervicio = false;
            vistaModeloCatalogo.vmcTipoServicio.dataTipoServicio.TiposServicio.remove(data);
            var tabla = $('#DataTables_Table_0_tipoServicio');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarTiposServicio();
          }
        });
      }, 200);

    }

    self.BorrarTipoServicio = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cTipoServicio/BorrarTipoServicio',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarTipoServicio").prop("disabled",false);
                      self.HabilitarTablaSpanTipoServicio(null, true);
                      self.SeleccionarSiguiente(objeto);
                      vistaModeloCatalogo.vmcTipoServicio.dataTipoServicio.TiposServicio.remove(objeto);
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


    self.OnClickTipoServicio = function(data ,event) {

      if(event)
      {
          console.log("OnClickTipoServicio");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_tiposervicio);

          if( _modo_nuevo_tiposervicio == true )
          {

          }
          else
          {

            $("#btnAgregarTipoServicio").prop("disabled",true);
            if(_tiposervicio.IdTipoServicio() !=  data.IdTipoServicio())
            {
              if (_input_habilitado_tiposervicio == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _tiposervicio.Deshacer(null, event);

                //var id_tiposervicio = "#" + _id_filatiposervicio_anterior;
                var id_tiposervicio = "#" + _tiposervicio.IdTipoServicio()+'_tr_tipoServicio';
                self.HabilitarFilaInputTipoServicio(id_tiposervicio, false);

                var idbutton = "#"+_tiposervicio.IdTipoServicio()+"_button_TipoServicio";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_tiposervicio.IdTipoServicio());
              console.log(data.IdTipoServicio());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_tiposervicio = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_tiposervicio, "span") || $.isSubstring(id_fila_tiposervicio, "input")){
                id_fila_tiposervicio = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filatiposervicio_anterior = $(id_fila_tiposervicio).parent()[0].id;
              var idspan ="#"+$(id_fila_tiposervicio).find('span').attr('id');
              var idinput ="#"+$(id_fila_tiposervicio).find('input').attr('id');
              self.HabilitarFilaInputTipoServicio("#" + $(id_fila_tiposervicio).parent()[0].id, true);

              var idbutton = "#"+data.IdTipoServicio()+"_button_TipoServicio";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_tiposervicio = true;

              }
              else {
                if (_input_habilitado_tiposervicio == false)
                {
                  var id_fila_tiposervicio = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_tiposervicio, "span") || $.isSubstring(id_fila_tiposervicio, "input")){
                    id_fila_tiposervicio = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputTipoServicio("#" + $(id_fila_tiposervicio).parent()[0].id, true);

                  var idbutton = "#"+data.IdTipoServicio()+"_button_TipoServicio";
                  var idinput ="#"+$(id_fila_tiposervicio).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_tiposervicio = true;
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
    self.OnKeyUpTipoServicio = function(data, event){
      if(event)
      {
       console.log("OnKeyUpTipoServicio");

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
         var idinputnombre = _tiposervicio.IdTipoServicio() + '_input_NombreTipoServicio';

         if(event.target.id == idinputnombre)
         {
           _tiposervicio.NombreTipoServicio(event.target.value);
         }


         if(_modo_nuevo_tiposervicio == true)
         {
           self.InsertarTipoServicio(_tiposervicio,event);
         }
         else
         {
           self.ActualizarTipoServicio(_tiposervicio,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event)
    {
      if(event)
      {
        if(_input_habilitado_tiposervicio == true)
        {
          if(_modo_nuevo_tiposervicio == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_tiposervicio);
              vistaModeloCatalogo.vmcTipoServicio.dataTipoServicio.TiposServicio.remove(_tiposervicio);
              var tabla = $('#DataTables_Table_0_tipoServicio');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarTipoServicio").prop("disabled",false);
              self.HabilitarButtonsTipoServicio(null, true);
               _modo_nuevo_tiposervicio = false;
               _input_habilitado_tiposervicio = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_tiposervicio._NombreTipoServicio());
            //revertir texto
            //data.NombreTipoServicio(_tiposervicio.NombreTipoServicio());

             _tiposervicio.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarTipoServicio").prop("disabled",false);

            /*var id_fila_tiposervicio = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_tiposervicio, "span") || $.isSubstring(id_fila_tiposervicio, "input")){
              id_fila_tiposervicio = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputTipoServicio("#" + $(id_fila_tiposervicio).parent()[0].id, false);*/
            self.HabilitarTablaSpanTipoServicio(null, true);

            var idbutton ="#"+_tiposervicio.IdTipoServicio()+"_button_TipoServicio";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_tiposervicio = false;
            _input_habilitado_tiposervicio = false;
          }
        }
      }
    }

    self.GuardarTipoServicio = function(data,event) {
      if(event)
      {
         console.log("GuardarTipoServicio");
         console.log(_nombretiposervicio);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _tiposervicio.IdTipoServicio() + '_input_NombreTipoServicio';

          if(event.target.id == idinputnombre)
          {
            _tiposervicio.NombreTipoServicio(_nombretiposervicio);
          }
         //_tiposervicio.NombreTipoServicio(_nombretiposervicio);

         if(_modo_nuevo_tiposervicio == true)
         {
           self.InsertarTipoServicio(_tiposervicio,event);
         }
         else
         {
           self.ActualizarTipoServicio(_tiposervicio,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
