
FabricantesModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreFabricante = ko.observable(data.NombreFabricante);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreFabricante());

        self.NombreFabricante.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreFabricante("");
        self.NombreFabricante(self._NombreFabricante());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreFabricante.valueHasMutated();
        self._NombreFabricante(self.NombreFabricante());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreFabricante());
}

FabricanteModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'Fabricantes': {
        create: function (options) {
            if (options)
              return new FabricantesModel(options.data);
            }
    },
    'Fabricante': {
        create: function (options) {
            if (options)
              return new FabricanteModel(options.data);
            }
    }

}

IndexFabricante = function (data) {

    var _modo_deshacer = false;
    var _nombrefabricante;
    var _input_habilitado_fabricante = false;
    var _idfabricante;
    var _fabricante;
    var _modo_nuevo_fabricante = false;
    var _id_filafabricante_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarFabricantes = function() {
        console.log("ListarFabricantes");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cFabricante/ListarFabricantes',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.data.Fabricantes([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.data.Fabricantes.push(new FabricantesModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_fabricante == false)
      {
        var id = "#"+data.IdFabricante()+ '_tr_fabricante';
        $(id).addClass('active').siblings().removeClass('active');
        _fabricante = data;
      }

    }

    self.FilaButtonsFabricante = function (data, event)  {
      console.log("BUTTONS");
      console.log("EVENTTARGET: " + $(event.target).attr('class'));
      console.log("THIS: " + $(this).attr('class'));
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_fabricante);
          if(_modo_nuevo_fabricante == true)
          return;

          _fabricante.Deshacer(null, event);
          _input_habilitado_fabricante = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarFabricante").prop("disabled",false);
          self.HabilitarTablaSpanFabricante(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdFabricante()+ '_tr_fabricante';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_fabricante == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcFabricante.dataFabricante.Fabricantes(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdFabricante();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdFabricante()+ '_tr_fabricante';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_fabricante == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idfabricante = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcFabricante.dataFabricante.Fabricantes(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idfabricante == item.IdFabricante();
            });

          if(match)
          {
            _fabricante = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputFabricante = function (data, option)  {
      //var id = "#"+data.IdFabricante();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputFabricante').hide();
        $(id).find('.class_SpanFabricante').show();
      }
      else
      {
        $(id).find('.class_InputFabricante').show();
        $(id).find('.class_SpanFabricante').hide();
      }

    }

    self.HabilitarTablaSpanFabricante = function (data, option)  {
      //var id = "#"+data.IdFabricante();
      var id = "#DataTables_Table_0_fabricante";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanFabricante').hide();
        $(id).find('.class_InputFabricante').show();
        //$(id).find('.guardar_button_Fabricante').show();
        //_input_habilitado_fabricante = true;
      }
      else {
        $(id).find('.class_SpanFabricante').show();
        $(id).find('.class_InputFabricante').hide();
        $(id).find('.guardar_button_Fabricante').hide();
        //_input_habilitado_fabricante = false;
      }

    }

    self.HabilitarButtonsFabricante = function(data, option){
      var id = "#DataTables_Table_0_fabricante";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_Fabricante').prop("disabled", true);
        $(id).find('.borrar_button_Fabricante').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_Fabricante').prop("disabled", false);
        $(id).find('.borrar_button_Fabricante').prop("disabled", false);
      }
    }


    self.AgregarFabricante = function(data,event) {
          console.log("AgregarFabricante");

          if ( _input_habilitado_fabricante == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(vistaModeloCatalogo.vmcFabricante.dataFabricante.Fabricante);
            _fabricante = new FabricantesModel(objeto);
            vistaModeloCatalogo.vmcFabricante.dataFabricante.Fabricantes.push(_fabricante);

            //Deshabilitando buttons
            self.HabilitarButtonsFabricante(null, false);
            $("#null_editar_button_Fabricante").prop("disabled", true);
            $("#null_borrar_button_Fabricante").prop("disabled", false);


            $("#btnAgregarFabricante").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdFabricante());

            var idspan ="#"+objeto.IdFabricante()+"_span_NombreFabricante";
            var idinput ="#"+objeto.IdFabricante()+"_input_NombreFabricante";

            var idbutton ="#"+objeto.IdFabricante()+"_button_Fabricante";

            console.log(idbutton);
            //self.HabilitarFilaInputFabricante(_fabricante, true);
            //self.HabilitarFilaSpanFabricante(_fabricante, false);

            $(idspan).hide();
            $(idinput).show();
            $(idbutton).show();
            $(idinput).focus();

            _modo_nuevo_fabricante = true;
            _input_habilitado_fabricante = true;

            var tabla = $('#DataTables_Table_0_fabricante');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarFabricante =function(data,event){

      if(event)
      {
        $("#loader").show();
        console.log("InsertarFabricante");
        console.log(_fabricante.NombreFabricante());

        var objeto = data;
        var datajs = ko.toJS({"Data" : _fabricante});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cFabricante/InsertarFabricante',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarFabricante");
                      console.log(data);

                      if ($.isNumeric(data.IdFabricante))
                      {
                        _fabricante.IdFabricante(data.IdFabricante);
                        //deshabilitar botones agregar
                        $("#btnAgregarFabricante").prop("disabled",false);

                        var id_fabricante = "#"+ _fabricante.IdFabricante()+ '_tr_fabricante';
                        self.HabilitarFilaInputFabricante(id_fabricante, false);

                        var idbutton ="#"+_fabricante.IdFabricante()+"_button_Fabricante";
                        $(idbutton).hide();

                         _fabricante.Confirmar(null,event);
                         self.HabilitarButtonsFabricante(null, true);

                        existecambio = false;
                        _input_habilitado_fabricante = false;
                        _modo_nuevo_fabricante = false;

                      }
                      else {
                        alertify.alert(data.IdFabricante);
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

    self.ActualizarFabricante = function(data,event) {
          console.log("ActualizarFabricante");
          console.log(_fabricante.NombreFabricante());
          $("#loader").show();

          var objeto = data;
          var datajs = ko.toJS({"Data" : _fabricante});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/Catalogo/cFabricante/ActualizarFabricante',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarFabricante").prop("disabled",false);
                          console.log("ID5:"+_fabricante.IdFabricante());
                          _fabricante.Confirmar(null,event);

                          var id_fabricante = "#"+ _fabricante.IdFabricante()+ '_tr_fabricante';
                          self.HabilitarFilaInputFabricante(id_fabricante, false);

                          var idbutton ="#"+_fabricante.IdFabricante()+"_button_Fabricante";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_fabricante = false;
                          _modo_nuevo_fabricante = false;

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

    self.EditarFabricante = function(data, event) {

      if(event)
      {
        console.log("EditarFabricante");
        console.log("ID.:"+data.IdFabricante());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_fabricante);

        if( _modo_nuevo_fabricante == true )
        {

        }
        else {

          if (_fabricante.IdFabricante() == data.IdFabricante())
          {

            if (_input_habilitado_fabricante == true)
            {
              $("#btnAgregarFabricante").prop("disabled",false);
              data.Deshacer(null,event);
              var id_fabricante = "#"+ data.IdFabricante()+ '_tr_fabricante';
              self.HabilitarFilaInputFabricante(id_fabricante, false);

              var idbutton = "#"+_fabricante.IdFabricante()+"_button_Fabricante";
              $(idbutton).hide();

              _input_habilitado_fabricante =false;


            }
            else {
              $("#btnAgregarFabricante").prop("disabled",true);
              var id_fabricante = "#"+ data.IdFabricante()+ '_tr_fabricante';
              self.HabilitarFilaInputFabricante(id_fabricante, true);

              var idbutton = "#"+data.IdFabricante()+"_button_Fabricante";

              var idinput = "#"+data.IdFabricante()+"_input_NombreFabricante";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_fabricante = true;
            }

          }
          else {
            $("#btnAgregarFabricante").prop("disabled",true);
            if( _input_habilitado_fabricante == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_fabricante.IdFabricante());

              var id_fabricante = "#"+ _fabricante.IdFabricante()+ '_tr_fabricante';
              self.HabilitarFilaInputFabricante(id_fabricante, false);

              var idbutton = "#"+_fabricante.IdFabricante()+"_button_Fabricante";

              _fabricante.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_fabricante = "#"+ data.IdFabricante()+ '_tr_fabricante';
            self.HabilitarFilaInputFabricante(id_fabricante, true);

            var idbutton = "#"+data.IdFabricante()+"_button_Fabricante";

            var idinput = "#"+data.IdFabricante()+"_input_NombreFabricante";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_fabricante = true;
          }


        }

      }

    }

    self.PreBorrarFabricante = function (data) {

      if(_modo_nuevo_fabricante == false)
      {
        _fabricante.Deshacer(null, event);
        _input_habilitado_fabricante = false;
        $("#btnAgregarFabricante").prop("disabled",false);
        self.HabilitarTablaSpanFabricante(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarFabricante");
          console.log(data.IdFabricante());
          self.HabilitarButtonsFabricante(null, true);
          if (data.IdFabricante() != null)
            self.BorrarFabricante(data);
          else
          {
            $("#btnAgregarFabricante").prop("disabled",false);
            _input_habilitado_fabricante = false;
            _modo_nuevo_fabricante = false;
            vistaModeloCatalogo.vmcFabricante.dataFabricante.Fabricantes.remove(data);
            var tabla = $('#DataTables_Table_0_fabricante');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarFabricantes();
          }
        });
      }, 200);

    }

    self.BorrarFabricante = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cFabricante/BorrarFabricante',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarFabricante").prop("disabled",false);
                      self.HabilitarTablaSpanFabricante(null, true);
                      self.SeleccionarSiguiente(objeto);
                      vistaModeloCatalogo.vmcFabricante.dataFabricante.Fabricantes.remove(objeto);
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


    self.OnClickFabricante = function(data ,event) {

      if(event)
      {
          console.log("OnClickFabricante");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_fabricante);

          if( _modo_nuevo_fabricante == true )
          {

          }
          else
          {

            $("#btnAgregarFabricante").prop("disabled",true);
            if(_fabricante.IdFabricante() !=  data.IdFabricante())
            {
              if (_input_habilitado_fabricante == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _fabricante.Deshacer(null, event);

                //var id_fabricante = "#" + _id_filafabricante_anterior;
                var id_fabricante = "#" + _fabricante.IdFabricante()+ '_tr_fabricante';
                self.HabilitarFilaInputFabricante(id_fabricante, false);

                var idbutton = "#"+_fabricante.IdFabricante()+"_button_Fabricante";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_fabricante.IdFabricante());
              console.log(data.IdFabricante());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_fabricante = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_fabricante, "span") || $.isSubstring(id_fila_fabricante, "input")){
                id_fila_fabricante = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filafabricante_anterior = $(id_fila_fabricante).parent()[0].id;
              var idspan ="#"+$(id_fila_fabricante).find('span').attr('id');
              var idinput ="#"+$(id_fila_fabricante).find('input').attr('id');
              self.HabilitarFilaInputFabricante("#" + $(id_fila_fabricante).parent()[0].id, true);

              var idbutton = "#"+data.IdFabricante()+"_button_Fabricante";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_fabricante = true;

              }
              else {
                if (_input_habilitado_fabricante == false)
                {
                  var id_fila_fabricante = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_fabricante, "span") || $.isSubstring(id_fila_fabricante, "input")){
                    id_fila_fabricante = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputFabricante("#" + $(id_fila_fabricante).parent()[0].id, true);

                  var idbutton = "#"+data.IdFabricante()+"_button_Fabricante";
                  var idinput ="#"+$(id_fila_fabricante).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_fabricante = true;
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
    self.OnKeyUpFabricante = function(data, event){
      if(event)
      {
       console.log("OnKeyUpFabricante");

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
         var idinputnombre = _fabricante.IdFabricante() + '_input_NombreFabricante';

         if(event.target.id == idinputnombre)
         {
           _fabricante.NombreFabricante(event.target.value);
         }


         if(_modo_nuevo_fabricante == true)
         {
           self.InsertarFabricante(_fabricante,event);
         }
         else
         {
           self.ActualizarFabricante(_fabricante,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event)
    {
      if(event)
      {
        if(_input_habilitado_fabricante == true)
        {
          if(_modo_nuevo_fabricante == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_fabricante);
              vistaModeloCatalogo.vmcFabricante.dataFabricante.Fabricantes.remove(_fabricante);
              var tabla = $('#DataTables_Table_0_fabricante');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarFabricante").prop("disabled",false);
              self.HabilitarButtonsFabricante(null, true);
               _modo_nuevo_fabricante = false;
               _input_habilitado_fabricante = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_fabricante._NombreFabricante());
            //revertir texto
            //data.NombreFabricante(_fabricante.NombreFabricante());

             _fabricante.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarFabricante").prop("disabled",false);

            /*var id_fila_fabricante = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_fabricante, "span") || $.isSubstring(id_fila_fabricante, "input")){
              id_fila_fabricante = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputFabricante("#" + $(id_fila_fabricante).parent()[0].id, false);*/
            self.HabilitarTablaSpanFabricante(null, true);

            var idbutton ="#"+_fabricante.IdFabricante()+"_button_Fabricante";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_fabricante = false;
            _input_habilitado_fabricante = false;
          }
        }
      }
    }

    self.GuardarFabricante = function(data,event) {
      if(event)
      {
         console.log("GuardarFabricante");
         console.log(_nombrefabricante);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _fabricante.IdFabricante() + '_input_NombreFabricante';

          if(event.target.id == idinputnombre)
          {
            _fabricante.NombreFabricante(_nombrefabricante);
          }
         //_fabricante.NombreFabricante(_nombrefabricante);

         if(_modo_nuevo_fabricante == true)
         {
           self.InsertarFabricante(_fabricante,event);
         }
         else
         {
           self.ActualizarFabricante(_fabricante,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
