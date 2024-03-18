
CostosAgregadoModel = function (data) {
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

CostoAgregadoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    //self.NombreProducto = ko.observable("");
}

var Mapping = {
    'CostosAgregado': {
        create: function (options) {
            if (options)
              return new CostosAgregadoModel(options.data);
            }
    },
    'CostoAgregado': {
        create: function (options) {
            if (options)
              return new CostoAgregadoModel(options.data);
            }
    }

}

Index = function (data) {

    var _modo_deshacer = false;
    var _nombrecostoagregado;
    var _input_habilitado = false;
    var _idcostoagregado;
    var _costoagregado;
    var _modo_nuevo = false;
    var _id_filacostoagregado_anterior;

    var self = this;
    self.MostrarTitulo = ko.observable("");
    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarCostosAgregado = function() {
        console.log("ListarCostosAgregado");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Catalogo/cCostoAgregado/ListarCostosAgregado',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.data.CostosAgregado([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.data.CostosAgregado.push(new CostosAgregadoModel(item));
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
        _costoagregado = data;
      }

    }

    self.FilaButtonsCostoAgregado = function (data, event)  {
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

          _costoagregado.Deshacer(null, event);
          _input_habilitado = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarCostoAgregado").prop("disabled",false);
          self.HabilitarTablaSpanCostoAgregado(null, true);

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
        var match = ko.utils.arrayFirst(self.data.CostosAgregado(), function(item) {
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
          var match = ko.utils.arrayFirst(self.data.CostosAgregado(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idcostoagregado == item.IdProducto();
            });

          if(match)
          {
            _costoagregado = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputCostoAgregado = function (data, option)  {
      //var id = "#"+data.IdProducto();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputCostoAgregado').hide();
        $(id).find('.class_SpanCostoAgregado').show();
      }
      else
      {
        $(id).find('.class_InputCostoAgregado').show();
        $(id).find('.class_SpanCostoAgregado').hide();
      }

    }

    self.HabilitarTablaSpanCostoAgregado = function (data, option)  {
      //var id = "#"+data.IdProducto();
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanCostoAgregado').hide();
        $(id).find('.class_InputCostoAgregado').show();
        //$(id).find('.guardar_button_CostoAgregado').show();
        //_input_habilitado = true;
      }
      else {
        $(id).find('.class_SpanCostoAgregado').show();
        $(id).find('.class_InputCostoAgregado').hide();
        $(id).find('.guardar_button_CostoAgregado').hide();
        //_input_habilitado = false;
      }

    }

    self.HabilitarButtonsCostoAgregado = function(data, option){
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_CostoAgregado').prop("disabled", true);
        $(id).find('.borrar_button_CostoAgregado').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_CostoAgregado').prop("disabled", false);
        $(id).find('.borrar_button_CostoAgregado').prop("disabled", false);
      }
    }


    self.AgregarCostoAgregado = function(data,event) {
          console.log("AgregarCostoAgregado");

          if ( _input_habilitado == true )
          {

          }
          else
          {
            self.MostrarTitulo("REGISTRO DE COSTO AGREGADO");
            var objeto = Knockout.CopiarObjeto(self.data.CostoAgregado);
            _costoagregado = new CostosAgregadoModel(objeto);
            self.data.CostosAgregado.push(_costoagregado);

            //Deshabilitando buttons
            self.HabilitarButtonsCostoAgregado(null, false);
            $("#null_editar_button_CostoAgregado").prop("disabled", true);
            $("#null_borrar_button_CostoAgregado").prop("disabled", false);


            $("#btnAgregarCostoAgregado").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdProducto());

            var idspan ="#"+objeto.IdProducto()+"_span_NombreProducto";
            var idinput ="#"+objeto.IdProducto()+"_input_NombreProducto";

            var idbutton ="#"+objeto.IdProducto()+"_button_CostoAgregado";

            console.log(idbutton);
            //self.HabilitarFilaInputCostoAgregado(_costoagregado, true);
            //self.HabilitarFilaSpanCostoAgregado(_costoagregado, false);

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

    self.InsertarCostoAgregado =function(data,event){

      if(event)
      {
        console.log("InsertarCostoAgregado");
        console.log(_costoagregado.NombreProducto());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _costoagregado});

        $.ajax({
            type: 'POST',
            data : datajs,
            dataType: "json",
            url: SITE_URL+'/Catalogo/cCostoAgregado/InsertarCostoAgregado',
            success: function (data) {
                  if (data != null) {
                  console.log("resultado -  InsertarCostoAgregado");
                  console.log(data);

                  if (!data.error)
                  {
                    _costoagregado.IdProducto(data.resultado.IdProducto);
                    //deshabilitar botones agregar
                    $("#btnAgregarCostoAgregado").prop("disabled",false);

                    var id_costoagregado = "#"+ _costoagregado.IdProducto();
                    self.HabilitarFilaInputCostoAgregado(id_costoagregado, false);

                    var idbutton ="#"+_costoagregado.IdProducto()+"_button_CostoAgregado";
                    $(idbutton).hide();

                     _costoagregado.Confirmar(null,event);
                     self.HabilitarButtonsCostoAgregado(null, true);

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

    self.ActualizarCostoAgregado = function(data,event) {
        console.log("ActualizarCostoAgregado");
        console.log(_costoagregado.NombreProducto());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _costoagregado});

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Catalogo/cCostoAgregado/ActualizarCostoAgregado',
          success: function (data) {
              if (data != null) {
                console.log(data);

                if (data == "")
                {
                  //deshabilitar campo origen
                  $("#btnAgregarCostoAgregado").prop("disabled",false);
                  console.log("ID5:"+_costoagregado.IdProducto());
                  _costoagregado.Confirmar(null,event);

                  var id_costoagregado = "#"+ _costoagregado.IdProducto();
                  self.HabilitarFilaInputCostoAgregado(id_costoagregado, false);

                  var idbutton ="#"+_costoagregado.IdProducto()+"_button_CostoAgregado";
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

    self.EditarCostoAgregado = function(data, event) {

      if(event)
      {
        console.log("EditarCostoAgregado");
        console.log("ID.:"+data.IdProducto());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_costoagregado);

        if( _modo_nuevo == true )
        {

        }
        else {

          if (_costoagregado.IdProducto() == data.IdProducto())
          {

            if (_input_habilitado == true)
            {
              $("#btnAgregarCostoAgregado").prop("disabled",false);
              data.Deshacer(null,event);
              var id_costoagregado = "#"+ data.IdProducto();
              self.HabilitarFilaInputCostoAgregado(id_costoagregado, false);

              var idbutton = "#"+_costoagregado.IdProducto()+"_button_CostoAgregado";
              $(idbutton).hide();

              _input_habilitado =false;


            }
            else {
              $("#btnAgregarCostoAgregado").prop("disabled",true);
              var id_costoagregado = "#"+ data.IdProducto();
              self.HabilitarFilaInputCostoAgregado(id_costoagregado, true);

              var idbutton = "#"+data.IdProducto()+"_button_CostoAgregado";

              var idinput = "#"+data.IdProducto()+"_input_NombreProducto";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;
            }

          }
          else {
            $("#btnAgregarCostoAgregado").prop("disabled",true);
            if( _input_habilitado == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_costoagregado.IdProducto());

              var id_costoagregado = "#"+ _costoagregado.IdProducto();
              self.HabilitarFilaInputCostoAgregado(id_costoagregado, false);

              var idbutton = "#"+_costoagregado.IdProducto()+"_button_CostoAgregado";

              _costoagregado.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_costoagregado = "#"+ data.IdProducto();
            self.HabilitarFilaInputCostoAgregado(id_costoagregado, true);

            var idbutton = "#"+data.IdProducto()+"_button_CostoAgregado";

            var idinput = "#"+data.IdProducto()+"_input_NombreProducto";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado = true;
          }


        }

      }

    }

    self.PreBorrarCostoAgregado = function (data) {

      if(_modo_nuevo == false)
      {
        _costoagregado.Deshacer(null, event);
        _input_habilitado = false;
        $("#btnAgregarCostoAgregado").prop("disabled",false);
        self.HabilitarTablaSpanCostoAgregado(null, true);
      }

      setTimeout(function(){
        self.MostrarTitulo("ELIMINACION DE COSTO AGREGADO");
        alertify.confirm(self.MostrarTitulo(),"¿Desea borrar el registro?", function(){
          console.log("BorrarCostoAgregado");
          console.log(data.IdProducto());
          self.HabilitarButtonsCostoAgregado(null, true);
          if (data.IdProducto() != null)
            self.BorrarCostoAgregado(data);
          else
          {
            $("#btnAgregarCostoAgregado").prop("disabled",false);
            _input_habilitado = false;
            _modo_nuevo = false;
            self.data.CostosAgregado.remove(data);
            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarCostosAgregado();
          }
        },function () {

        });
      }, 200);

    }

    self.BorrarCostoAgregado = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Catalogo/cCostoAgregado/BorrarCostoAgregado',
          success: function (data) {
            if (data != null) {
              console.log("BorrarFamiliaProducto");
              //console.log(data);

              if(data.msg != "")
              {
                alertify.alert("ERROR EN "+self.MostrarTitulo(),data.error.msg);
              }
              else {
                $("#btnAgregarCostoAgregado").prop("disabled",false);
                self.HabilitarTablaSpanCostoAgregado(null, true);
                self.SeleccionarSiguiente(objeto);
                self.data.CostosAgregado.remove(objeto);
              }
            }
          },
          error : function (jqXHR, textStatus, errorThrown) {
            var $data = {error:{msg:jqXHR.responseText}};
            alertify.alert("HA OCURRIDO UN ERROR",$data.error.msg);
          }
      });

    }


    self.OnClickCostoAgregado = function(data ,event) {

      if(event)
      {
          console.log("OnClickCostoAgregado");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_costoagregado);

          if( _modo_nuevo == true )
          {

          }
          else
          {

            $("#btnAgregarCostoAgregado").prop("disabled",true);
            if(_costoagregado.IdProducto() !=  data.IdProducto())
            {
              if (_input_habilitado == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _costoagregado.Deshacer(null, event);

                //var id_costoagregado = "#" + _id_filacostoagregado_anterior;
                var id_costoagregado = "#" + _costoagregado.IdProducto();
                self.HabilitarFilaInputCostoAgregado(id_costoagregado, false);

                var idbutton = "#"+_costoagregado.IdProducto()+"_button_CostoAgregado";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_costoagregado.IdProducto());
              console.log(data.IdProducto());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_costoagregado = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_costoagregado, "span") || $.isSubstring(id_fila_costoagregado, "input")){
                id_fila_costoagregado = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filacostoagregado_anterior = $(id_fila_costoagregado).parent()[0].id;
              var idspan ="#"+$(id_fila_costoagregado).find('span').attr('id');
              var idinput ="#"+$(id_fila_costoagregado).find('input').attr('id');
              self.HabilitarFilaInputCostoAgregado("#" + $(id_fila_costoagregado).parent()[0].id, true);

              var idbutton = "#"+data.IdProducto()+"_button_CostoAgregado";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;

              }
              else {
                if (_input_habilitado == false)
                {
                  var id_fila_costoagregado = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_costoagregado, "span") || $.isSubstring(id_fila_costoagregado, "input")){
                    id_fila_costoagregado = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputCostoAgregado("#" + $(id_fila_costoagregado).parent()[0].id, true);

                  var idbutton = "#"+data.IdProducto()+"_button_CostoAgregado";
                  var idinput ="#"+$(id_fila_costoagregado).find('input').attr('id');
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
    self.OnKeyUpCostoAgregado = function(data, event){
      if(event)
      {
       console.log("OnKeyUpCostoAgregado");

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
         var idinputnombre = _costoagregado.IdProducto() + '_input_NombreProducto';

         if(event.target.id == idinputnombre)
         {
           _costoagregado.NombreProducto(event.target.value);
         }


         if(_modo_nuevo == true)
         {
           self.InsertarCostoAgregado(_costoagregado,event);
         }
         else
         {
           self.ActualizarCostoAgregado(_costoagregado,event);
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
            alertify.confirm("REGISTRO DE COSTO AGREGADO","¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_costoagregado);
              self.data.CostosAgregado.remove(_costoagregado);
              var tabla = $('#DataTables_Table_0');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarCostoAgregado").prop("disabled",false);
              self.HabilitarButtonsCostoAgregado(null, true);
               _modo_nuevo = false;
               _input_habilitado = false;
            },function () {

            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_costoagregado._NombreProducto());
            //revertir texto
            //data.NombreProducto(_costoagregado.NombreProducto());

             _costoagregado.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarCostoAgregado").prop("disabled",false);

            /*var id_fila_costoagregado = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_costoagregado, "span") || $.isSubstring(id_fila_costoagregado, "input")){
              id_fila_costoagregado = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputCostoAgregado("#" + $(id_fila_costoagregado).parent()[0].id, false);*/
            self.HabilitarTablaSpanCostoAgregado(null, true);

            var idbutton ="#"+_costoagregado.IdProducto()+"_button_CostoAgregado";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo = false;
            _input_habilitado = false;
          }

        }
      }
    }

    self.GuardarCostoAgregado = function(data,event) {
      if(event)
      {
         console.log("GuardarCostoAgregado");
         console.log(_nombrecostoagregado);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _costoagregado.IdProducto() + '_input_NombreProducto';

          if(event.target.id == idinputnombre)
          {
            _costoagregado.NombreProducto(_nombrecostoagregado);
          }
         //_costoagregado.NombreProducto(_nombrecostoagregado);

         if(_modo_nuevo == true)
         {
           self.InsertarCostoAgregado(_costoagregado,event);
         }
         else
         {
           self.ActualizarCostoAgregado(_costoagregado,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
