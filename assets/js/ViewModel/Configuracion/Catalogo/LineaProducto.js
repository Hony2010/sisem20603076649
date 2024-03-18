
LineasProductoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreLineaProducto = ko.observable(data.NombreLineaProducto);

    self.VistaOptions = ko.pureComputed(function(){
      return self.NoEspecificado() == "S" ? "hidden" : "visible";
    }, this);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreLineaProducto());

        self.NombreLineaProducto.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreLineaProducto("");
        self.NombreLineaProducto(self._NombreLineaProducto());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreLineaProducto.valueHasMutated();
        self._NombreLineaProducto(self.NombreLineaProducto());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreLineaProducto());
}

LineaProductoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'LineasProducto': {
        create: function (options) {
            if (options)
              return new LineasProductoModel(options.data);
            }
    },
    'LineaProducto': {
        create: function (options) {
            if (options)
              return new LineaProductoModel(options.data);
            }
    }

}

IndexLineaProducto = function (data) {

    var _modo_deshacer = false;
    var _nombrelineaproducto;
    var _input_habilitado_lineaproducto = false;
    var _idlineaproducto;
    var _lineaproducto;
    var _modo_nuevo_lineaproducto = false;
    var _id_filalineaproducto_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarLineasProducto = function() {
        console.log("ListarLineasProducto");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cLineaProducto/ListarLineasProducto',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto([]);
                        ko.utils.arrayForEach(data, function (item) {
                            vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto.push(new LineasProductoModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {

      if (_modo_nuevo_lineaproducto == false)
      {
        var id = "#"+data.IdLineaProducto()+'_tr_LineaProducto';
        $(id).addClass('active').siblings().removeClass('active');
        _lineaproducto = data;
      }

    }

    self.FilaButtonsLineaProducto = function (data, event)  {
      console.log("BUTTONS");
      console.log("EVENTTARGET: " + $(event.target).attr('class'));
      console.log("THIS: " + $(this).attr('class'));
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_lineaproducto);
          if(_modo_nuevo_lineaproducto == true)
          return;

          _lineaproducto.Deshacer(null, event);
          _input_habilitado_lineaproducto = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarLineaProducto").prop("disabled",false);
          self.HabilitarTablaSpanLineaProducto(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdLineaProducto()+'_tr_LineaProducto';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_lineaproducto == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdLineaProducto();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdLineaProducto()+'_tr_LineaProducto';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_lineaproducto == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idlineaproducto = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idlineaproducto == item.IdLineaProducto();
            });

          if(match)
          {
            _lineaproducto = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputLineaProducto = function (data, option)  {
      //var id = "#"+data.IdLineaProducto();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputLineaProducto').hide();
        $(id).find('.class_SpanLineaProducto').show();
      }
      else
      {
        $(id).find('.class_InputLineaProducto').show();
        $(id).find('.class_SpanLineaProducto').hide();
      }

    }

    self.HabilitarTablaSpanLineaProducto = function (data, option)  {
      //var id = "#"+data.IdLineaProducto();
      var id = "#DataTables_Table_0_lineaProducto";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanLineaProducto').hide();
        $(id).find('.class_InputLineaProducto').show();
        //$(id).find('.guardar_button_LineaProducto').show();
        //_input_habilitado_lineaproducto = true;
      }
      else {
        $(id).find('.class_SpanLineaProducto').show();
        $(id).find('.class_InputLineaProducto').hide();
        $(id).find('.guardar_button_LineaProducto').hide();
        //_input_habilitado_lineaproducto = false;
      }

    }

    self.HabilitarButtonsLineaProducto = function(data, option){
      var id = "#DataTables_Table_0_lineaProducto";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_LineaProducto').prop("disabled", true);
        $(id).find('.borrar_button_LineaProducto').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_LineaProducto').prop("disabled", false);
        $(id).find('.borrar_button_LineaProducto').prop("disabled", false);
      }
    }


    self.AgregarLineaProducto = function(data,event) {
          console.log("AgregarLineaProducto");

          if ( _input_habilitado_lineaproducto == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineaProducto);
            _lineaproducto = new LineasProductoModel(objeto);
            vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto.push(_lineaproducto);

            //Deshabilitando buttons
            self.HabilitarButtonsLineaProducto(null, false);
            $("#null_editar_button_LineaProducto").prop("disabled", true);
            $("#null_borrar_button_LineaProducto").prop("disabled", false);


            $("#btnAgregarLineaProducto").prop("disabled",true);

            //habilitar como destino
            var idspan ="#"+objeto.IdLineaProducto()+"_span_NombreLineaProducto";
            var idinput ="#"+objeto.IdLineaProducto()+"_input_NombreLineaProducto";

            var idbutton ="#"+objeto.IdLineaProducto()+"_button_LineaProducto";

            console.log(idbutton);
            //self.HabilitarFilaInputLineaProducto(_lineaproducto, true);
            //self.HabilitarFilaSpanLineaProducto(_lineaproducto, false);

            $(idspan).hide();
            $(idinput).show();
            $(idbutton).show();
            $(idinput).focus();

            _modo_nuevo_lineaproducto = true;
            _input_habilitado_lineaproducto = true;

            var tabla = $('#DataTables_Table_0_lineaProducto');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarLineaProducto =function(data,event){

      if(event)
      {
        $("#loader").show();
        console.log("InsertarLineaProducto");
        console.log(_lineaproducto.NombreLineaProducto());

        var objeto = data;
        var datajs = ko.toJS({"Data" : _lineaproducto});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cLineaProducto/InsertarLineaProducto',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarLineaProducto");
                      console.log(data);

                      if ($.isNumeric(data.IdLineaProducto))
                      {
                        _lineaproducto.IdLineaProducto(data.IdLineaProducto);
                        //deshabilitar botones agregar
                        $("#btnAgregarLineaProducto").prop("disabled",false);

                        var id_lineaproducto = "#"+ _lineaproducto.IdLineaProducto()+'_tr_LineaProducto';
                        self.HabilitarFilaInputLineaProducto(id_lineaproducto, false);

                        var idbutton ="#"+_lineaproducto.IdLineaProducto()+"_button_LineaProducto";
                        $(idbutton).hide();

                         _lineaproducto.Confirmar(null,event);
                         self.HabilitarButtonsLineaProducto(null, true);

                        existecambio = false;
                        _input_habilitado_lineaproducto = false;
                        _modo_nuevo_lineaproducto = false;

                      }
                      else {
                        alertify.alert(data.IdLineaProducto);
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

    self.ActualizarLineaProducto = function(data,event) {
          console.log("ActualizarLineaProducto");
          console.log(_lineaproducto.NombreLineaProducto());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _lineaproducto});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/Catalogo/cLineaProducto/ActualizarLineaProducto',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarLineaProducto").prop("disabled",false);
                          _lineaproducto.Confirmar(null,event);

                          var id_lineaproducto = "#"+ _lineaproducto.IdLineaProducto()+'_tr_LineaProducto';
                          self.HabilitarFilaInputLineaProducto(id_lineaproducto, false);

                          var idbutton ="#"+_lineaproducto.IdLineaProducto()+"_button_LineaProducto";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_lineaproducto = false;
                          _modo_nuevo_lineaproducto = false;

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

    self.EditarLineaProducto = function(data, event) {

      if(event)
      {
        console.log("EditarLineaProducto");
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_lineaproducto);

        if( _modo_nuevo_lineaproducto == true )
        {

        }
        else {

          if (_lineaproducto.IdLineaProducto() == data.IdLineaProducto())
          {

            if (_input_habilitado_lineaproducto == true)
            {
              $("#btnAgregarLineaProducto").prop("disabled",false);
              data.Deshacer(null,event);
              var id_lineaproducto = "#"+ data.IdLineaProducto()+'_tr_LineaProducto';
              self.HabilitarFilaInputLineaProducto(id_lineaproducto, false);

              var idbutton = "#"+_lineaproducto.IdLineaProducto()+"_button_LineaProducto";
              $(idbutton).hide();

              _input_habilitado_lineaproducto =false;


            }
            else {
              $("#btnAgregarLineaProducto").prop("disabled",true);
              var id_lineaproducto = "#"+ data.IdLineaProducto()+'_tr_LineaProducto';
              self.HabilitarFilaInputLineaProducto(id_lineaproducto, true);

              var idbutton = "#"+data.IdLineaProducto()+"_button_LineaProducto";

              var idinput = "#"+data.IdLineaProducto()+"_input_NombreLineaProducto";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_lineaproducto = true;
            }

          }
          else {
            $("#btnAgregarLineaProducto").prop("disabled",true);
            if( _input_habilitado_lineaproducto == true)
            {
              //deshabilitar campo origen

              var id_lineaproducto = "#"+ _lineaproducto.IdLineaProducto()+'_tr_LineaProducto';
              self.HabilitarFilaInputLineaProducto(id_lineaproducto, false);

              var idbutton = "#"+_lineaproducto.IdLineaProducto()+"_button_LineaProducto";

              _lineaproducto.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_lineaproducto = "#"+ data.IdLineaProducto()+'_tr_LineaProducto';
            self.HabilitarFilaInputLineaProducto(id_lineaproducto, true);

            var idbutton = "#"+data.IdLineaProducto()+"_button_LineaProducto";

            var idinput = "#"+data.IdLineaProducto()+"_input_NombreLineaProducto";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_lineaproducto = true;
          }


        }

      }

    }

    self.PreBorrarLineaProducto = function (data) {

      if(_modo_nuevo_lineaproducto == false)
      {
        _lineaproducto.Deshacer(null, event);
        _input_habilitado_lineaproducto = false;
        $("#btnAgregarLineaProducto").prop("disabled",false);
        self.HabilitarTablaSpanLineaProducto(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarLineaProducto");
          self.HabilitarButtonsLineaProducto(null, true);
          if (data.IdLineaProducto() != null)
          {
            self.BorrarLineaProducto(data);
          }
          else
          {
            $("#btnAgregarLineaProducto").prop("disabled",false);
            _input_habilitado_lineaproducto = false;
            _modo_nuevo_lineaproducto = false;
            vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto.remove(data);
            var tabla = $('#DataTables_Table_0_lineaProducto');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarLineasProducto();
          }
        });
      }, 200);

    }

    self.BorrarLineaProducto = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cLineaProducto/BorrarLineaProducto',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarLineaProducto").prop("disabled",false);
                      self.HabilitarTablaSpanLineaProducto(null, true);
                      self.SeleccionarSiguiente(objeto);
                      vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto.remove(objeto);
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


    self.OnClickLineaProducto = function(data ,event) {

      if(event)
      {
          console.log("OnClickLineaProducto");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_lineaproducto);

          if(data.NoEspecificado() == "S"){
            _lineaproducto.Deshacer(null, event);
            self.HabilitarTablaSpanLineaProducto(null, true);
            $("#btnAgregarLineaProducto").prop("disabled",false)
            event.preventDefault;
            return false;
          }

          if( _modo_nuevo_lineaproducto == true )
          {

          }
          else
          {

            $("#btnAgregarLineaProducto").prop("disabled",true);
            if(_lineaproducto.IdLineaProducto() !=  data.IdLineaProducto())
            {
              if (_input_habilitado_lineaproducto == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _lineaproducto.Deshacer(null, event);

                //var id_lineaproducto = "#" + _id_filalineaproducto_anterior;
                var id_lineaproducto = "#" + _lineaproducto.IdLineaProducto()+'_tr_LineaProducto';
                self.HabilitarFilaInputLineaProducto(id_lineaproducto, false);

                var idbutton = "#"+_lineaproducto.IdLineaProducto()+"_button_LineaProducto";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_lineaproducto = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_lineaproducto, "span") || $.isSubstring(id_fila_lineaproducto, "input")){
                id_fila_lineaproducto = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filalineaproducto_anterior = $(id_fila_lineaproducto).parent()[0].id;
              var idspan ="#"+$(id_fila_lineaproducto).find('span').attr('id');
              var idinput ="#"+$(id_fila_lineaproducto).find('input').attr('id');
              self.HabilitarFilaInputLineaProducto("#" + $(id_fila_lineaproducto).parent()[0].id, true);

              var idbutton = "#"+data.IdLineaProducto()+"_button_LineaProducto";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_lineaproducto = true;

              }
              else {
                if (_input_habilitado_lineaproducto == false)
                {
                  var id_fila_lineaproducto = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_lineaproducto, "span") || $.isSubstring(id_fila_lineaproducto, "input")){
                    id_fila_lineaproducto = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputLineaProducto("#" + $(id_fila_lineaproducto).parent()[0].id, true);

                  var idbutton = "#"+data.IdLineaProducto()+"_button_LineaProducto";
                  var idinput ="#"+$(id_fila_lineaproducto).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_lineaproducto = true;
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
    self.OnKeyUpLineaProducto = function(data, event){
      if(event)
      {
       console.log("OnKeyUpLineaProducto");

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
         var idinputnombre = _lineaproducto.IdLineaProducto() + '_input_NombreLineaProducto';

         if(event.target.id == idinputnombre)
         {
           _lineaproducto.NombreLineaProducto(event.target.value);
         }


         if(_modo_nuevo_lineaproducto == true)
         {
           self.InsertarLineaProducto(_lineaproducto,event);
         }
         else
         {
           self.ActualizarLineaProducto(_lineaproducto,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event)
    {
      if(event)
      {
        if(_input_habilitado_lineaproducto == true)
        {
          if(_modo_nuevo_lineaproducto == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_lineaproducto);
              vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto.remove(_lineaproducto);
              var tabla = $('#DataTables_Table_0_lineaProducto');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarLineaProducto").prop("disabled",false);
              self.HabilitarButtonsLineaProducto(null, true);
               _modo_nuevo_lineaproducto = false;
               _input_habilitado_lineaproducto = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_lineaproducto._NombreLineaProducto());
            //revertir texto
            //data.NombreLineaProducto(_lineaproducto.NombreLineaProducto());

             _lineaproducto.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarLineaProducto").prop("disabled",false);

            /*var id_fila_lineaproducto = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_lineaproducto, "span") || $.isSubstring(id_fila_lineaproducto, "input")){
              id_fila_lineaproducto = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputLineaProducto("#" + $(id_fila_lineaproducto).parent()[0].id, false);*/
            self.HabilitarTablaSpanLineaProducto(null, true);

            var idbutton ="#"+_lineaproducto.IdLineaProducto()+"_button_LineaProducto";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_lineaproducto = false;
            _input_habilitado_lineaproducto = false;
          }

        }
      }
    }

    self.GuardarLineaProducto = function(data,event) {
      if(event)
      {
         console.log("GuardarLineaProducto");
         console.log(_nombrelineaproducto);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _lineaproducto.IdLineaProducto() + '_input_NombreLineaProducto';

          if(event.target.id == idinputnombre)
          {
            _lineaproducto.NombreLineaProducto(_nombrelineaproducto);
          }
         //_lineaproducto.NombreLineaProducto(_nombrelineaproducto);

         if(_modo_nuevo_lineaproducto == true)
         {
           self.InsertarLineaProducto(_lineaproducto,event);
         }
         else
         {
           self.ActualizarLineaProducto(_lineaproducto,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
