
GastosModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreProducto = ko.observable(data.NombreProducto);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreProducto());

        self.NombreProducto.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreProducto("");
        self.NombreProducto(self._NombreProducto());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreProducto.valueHasMutated();
        self._NombreProducto(self.NombreProducto());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreProducto());
}

GastoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    //self.NombreProducto = ko.observable("");
}

var Mapping = {
    'Gastos': {
        create: function (options) {
            if (options)
              return new GastosModel(options.data);
            }
    },
    'Gasto': {
        create: function (options) {
            if (options)
              return new GastoModel(options.data);
            }
    }

}

Index = function (data) {

    var _modo_deshacer = false;
    var _nombrecostoagregado;
    var _input_habilitado = false;
    var _idcostoagregado;
    var _gasto;
    var _modo_nuevo = false;
    var _id_filacostoagregado_anterior;

    var self = this;

    self.MostrarTitulo = ko.observable("");

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarGastos = function() {
        console.log("ListarGastos");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Catalogo/cGasto/ListarGastos',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.data.Gastos([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.data.Gastos.push(new GastosModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo == false)
      {
        var id = "#"+data.IdProducto();
        $(id).addClass('active').siblings().removeClass('active');
        _gasto = data;
      }

    }

    self.FilaButtonsGasto = function (data, event)  {
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

          _gasto.Deshacer(null, event);
          _input_habilitado = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarGasto").prop("disabled",false);
          self.HabilitarTablaSpanGasto(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdProducto();
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.data.Gastos(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdProducto();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdProducto();
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idcostoagregado = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.data.Gastos(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idcostoagregado == item.IdProducto();
            });

          if(match)
          {
            _gasto = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputGasto = function (data, option)  {
      //var id = "#"+data.IdProducto();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputGasto').hide();
        $(id).find('.class_SpanGasto').show();
      }
      else
      {
        $(id).find('.class_InputGasto').show();
        $(id).find('.class_SpanGasto').hide();
      }

    }

    self.HabilitarTablaSpanGasto = function (data, option)  {
      //var id = "#"+data.IdProducto();
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanGasto').hide();
        $(id).find('.class_InputGasto').show();
        //$(id).find('.guardar_button_Gasto').show();
        //_input_habilitado = true;
      }
      else {
        $(id).find('.class_SpanGasto').show();
        $(id).find('.class_InputGasto').hide();
        $(id).find('.guardar_button_Gasto').hide();
        //_input_habilitado = false;
      }

    }

    self.HabilitarButtonsGasto = function(data, option){
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_Gasto').prop("disabled", true);
        $(id).find('.borrar_button_Gasto').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_Gasto').prop("disabled", false);
        $(id).find('.borrar_button_Gasto').prop("disabled", false);
      }
    }


    self.AgregarGasto = function(data,event) {
          console.log("AgregarGasto");

          if ( _input_habilitado == true )
          {

          }
          else
          {
            self.MostrarTitulo("REGISTRO DE GASTO")
            var objeto = Knockout.CopiarObjeto(self.data.Gasto);
            _gasto = new GastosModel(objeto);
            self.data.Gastos.push(_gasto);

            //Deshabilitando buttons
            self.HabilitarButtonsGasto(null, false);
            $("#null_editar_button_Gasto").prop("disabled", true);
            $("#null_borrar_button_Gasto").prop("disabled", false);


            $("#btnAgregarGasto").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdProducto());

            var idspan ="#"+objeto.IdProducto()+"_span_NombreProducto";
            var idinput ="#"+objeto.IdProducto()+"_input_NombreProducto";

            var idbutton ="#"+objeto.IdProducto()+"_button_Gasto";

            console.log(idbutton);
            //self.HabilitarFilaInputGasto(_gasto, true);
            //self.HabilitarFilaSpanGasto(_gasto, false);

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

    self.InsertarGasto =function(data,event){

      if(event)
      {
        console.log("InsertarGasto");
        console.log(_gasto.NombreProducto());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _gasto});

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Catalogo/cGasto/InsertarGasto',
          success: function (data) {
                if (data != null) {
                console.log("resultado -  InsertarGasto");
                console.log(data);

                if (!data.error)
                {
                  _gasto.IdProducto(data.resultado.IdProducto);
                  //deshabilitar botones agregar
                  $("#btnAgregarGasto").prop("disabled",false);

                  var id_gasto = "#"+ _gasto.IdProducto();
                  self.HabilitarFilaInputGasto(id_gasto, false);

                  var idbutton ="#"+_gasto.IdProducto()+"_button_Gasto";
                  $(idbutton).hide();

                   _gasto.Confirmar(null,event);
                   self.HabilitarButtonsGasto(null, true);

                  existecambio = false;
                  _input_habilitado = false;
                  _modo_nuevo = false;

                }
                else {
                  alertify.alert("ERROR EN "+self.MostrarTitulo(),data.error.msg);
                }

            }
            $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            var $data = {error:{msg:jqXHR.responseText}};
            $("#loader").hide();
            alertify.alert("HA OCURRIDO UN ERROR",$data.error.msg);
          }
        });
      }
    }

    self.ActualizarGasto = function(data,event) {
          console.log("ActualizarGasto");
          console.log(_gasto.NombreProducto());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _gasto});

          $.ajax({
            type: 'POST',
            data : datajs,
            dataType: "json",
            url: SITE_URL+'/Catalogo/cGasto/ActualizarGasto',
            success: function (data) {
                if (data != null) {
                  console.log(data);

                  if (data == "")
                  {
                    //deshabilitar campo origen
                    $("#btnAgregarGasto").prop("disabled",false);
                    console.log("ID5:"+_gasto.IdProducto());
                    _gasto.Confirmar(null,event);

                    var id_gasto = "#"+ _gasto.IdProducto();
                    self.HabilitarFilaInputGasto(id_gasto, false);

                    var idbutton ="#"+_gasto.IdProducto()+"_button_Gasto";
                    $(idbutton).hide();

                    existecambio = false;
                    _input_habilitado = false;
                    _modo_nuevo = false;

                  }
                  else {
                    alertify.alert("ERROR EN "+self.MostrarTitulo(),data.error.msg);
                  }
              }
              $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            var $data = {error:{msg:jqXHR.responseText}};
            $("#loader").hide();
            alertify.alert("HA OCURRIDO UN ERROR",$data.error.msg);
          }
      });
    }

    self.EditarGasto = function(data, event) {

      if(event)
      {
        console.log("EditarGasto");
        console.log("ID.:"+data.IdProducto());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_gasto);

        if( _modo_nuevo == true )
        {

        }
        else {
          self.MostrarTitulo("EDICIÓN DE GASTO");
          if (_gasto.IdProducto() == data.IdProducto())
          {

            if (_input_habilitado == true)
            {
              $("#btnAgregarGasto").prop("disabled",false);
              data.Deshacer(null,event);
              var id_gasto = "#"+ data.IdProducto();
              self.HabilitarFilaInputGasto(id_gasto, false);

              var idbutton = "#"+_gasto.IdProducto()+"_button_Gasto";
              $(idbutton).hide();

              _input_habilitado =false;


            }
            else {
              $("#btnAgregarGasto").prop("disabled",true);
              var id_gasto = "#"+ data.IdProducto();
              self.HabilitarFilaInputGasto(id_gasto, true);

              var idbutton = "#"+data.IdProducto()+"_button_Gasto";

              var idinput = "#"+data.IdProducto()+"_input_NombreProducto";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;
            }

          }
          else {
            $("#btnAgregarGasto").prop("disabled",true);
            if( _input_habilitado == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_gasto.IdProducto());

              var id_gasto = "#"+ _gasto.IdProducto();
              self.HabilitarFilaInputGasto(id_gasto, false);

              var idbutton = "#"+_gasto.IdProducto()+"_button_Gasto";

              _gasto.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_gasto = "#"+ data.IdProducto();
            self.HabilitarFilaInputGasto(id_gasto, true);

            var idbutton = "#"+data.IdProducto()+"_button_Gasto";

            var idinput = "#"+data.IdProducto()+"_input_NombreProducto";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado = true;
          }


        }

      }

    }

    self.PreBorrarGasto = function (data) {

      if(_modo_nuevo == false)
      {
        _gasto.Deshacer(null, event);
        _input_habilitado = false;
        $("#btnAgregarGasto").prop("disabled",false);
        self.HabilitarTablaSpanGasto(null, true);
      }

      setTimeout(function(){
        self.MostrarTitulo("ELIMINACION DE GASTO");
        alertify.confirm(self.MostrarTitulo(),"¿Desea borrar el registro?", function(){
          console.log("BorrarGasto");
          console.log(data.IdProducto());
          self.HabilitarButtonsGasto(null, true);
          if (data.IdProducto() != null)
            self.BorrarGasto(data);
          else
          {
            $("#btnAgregarGasto").prop("disabled",false);
            _input_habilitado = false;
            _modo_nuevo = false;
            self.data.Gastos.remove(data);
            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarGastos();
          }
        },function () {

        });

      }, 200);

    }

    self.BorrarGasto = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Catalogo/cGasto/BorrarGasto',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data.msg != "")
                    {
                      alertify.alert("ERROR EN "+self.MostrarTitulo(),data.error.msg);
                    }
                    else {
                      $("#btnAgregarGasto").prop("disabled",false);
                      self.HabilitarTablaSpanGasto(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.data.Gastos.remove(objeto);
                    }
              }
          },
      });

    }


    self.OnClickGasto = function(data ,event) {

      if(event)
      {
          console.log("OnClickGasto");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_gasto);

          if( _modo_nuevo == true )
          {

          }
          else
          {

            $("#btnAgregarGasto").prop("disabled",true);
            if(_gasto.IdProducto() !=  data.IdProducto())
            {
              if (_input_habilitado == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _gasto.Deshacer(null, event);

                //var id_gasto = "#" + _id_filacostoagregado_anterior;
                var id_gasto = "#" + _gasto.IdProducto();
                self.HabilitarFilaInputGasto(id_gasto, false);

                var idbutton = "#"+_gasto.IdProducto()+"_button_Gasto";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_gasto.IdProducto());
              console.log(data.IdProducto());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_gasto = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_gasto, "span") || $.isSubstring(id_fila_gasto, "input")){
                id_fila_gasto = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filacostoagregado_anterior = $(id_fila_gasto).parent()[0].id;
              var idspan ="#"+$(id_fila_gasto).find('span').attr('id');
              var idinput ="#"+$(id_fila_gasto).find('input').attr('id');
              self.HabilitarFilaInputGasto("#" + $(id_fila_gasto).parent()[0].id, true);

              var idbutton = "#"+data.IdProducto()+"_button_Gasto";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;

              }
              else {
                if (_input_habilitado == false)
                {
                  var id_fila_gasto = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_gasto, "span") || $.isSubstring(id_fila_gasto, "input")){
                    id_fila_gasto = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputGasto("#" + $(id_fila_gasto).parent()[0].id, true);

                  var idbutton = "#"+data.IdProducto()+"_button_Gasto";
                  var idinput ="#"+$(id_fila_gasto).find('input').attr('id');
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
    self.OnKeyUpGasto = function(data, event){
      if(event)
      {
       console.log("OnKeyUpGasto");

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
         var idinputnombre = _gasto.IdProducto() + '_input_NombreProducto';

         if(event.target.id == idinputnombre)
         {
           _gasto.NombreProducto(event.target.value);
         }


         if(_modo_nuevo == true)
         {
           self.InsertarGasto(_gasto,event);
         }
         else
         {
           self.ActualizarGasto(_gasto,event);
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
            alertify.confirm("REGISTRO DE GASTO","¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_gasto);
              self.data.Gastos.remove(_gasto);
              var tabla = $('#DataTables_Table_0');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarGasto").prop("disabled",false);
              self.HabilitarButtonsGasto(null, true);
               _modo_nuevo = false;
               _input_habilitado = false;
            },function() {

            });
          }
          else
          {
            console.log("Escape - false");
            //revertir texto
            //data.NombreProducto(_gasto.NombreProducto());

             _gasto.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarGasto").prop("disabled",false);

            /*var id_fila_gasto = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_gasto, "span") || $.isSubstring(id_fila_gasto, "input")){
              id_fila_gasto = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputGasto("#" + $(id_fila_gasto).parent()[0].id, false);*/
            self.HabilitarTablaSpanGasto(null, true);

            var idbutton ="#"+_gasto.IdProducto()+"_button_Gasto";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo = false;
            _input_habilitado = false;
          }

        }
      }
    }

    self.GuardarGasto = function(data,event) {
      if(event)
      {
         console.log("GuardarGasto");
         console.log(_nombrecostoagregado);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _gasto.IdProducto() + '_input_NombreProducto';

          if(event.target.id == idinputnombre)
          {
            _gasto.NombreProducto(_nombrecostoagregado);
          }
         //_gasto.NombreProducto(_nombrecostoagregado);

         if(_modo_nuevo == true)
         {
           self.InsertarGasto(_gasto,event);
         }
         else
         {
           self.ActualizarGasto(_gasto,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
