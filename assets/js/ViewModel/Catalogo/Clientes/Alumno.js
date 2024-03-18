var _modo_deshacer = false;
var _nombrecostoagregado;
var _input_habilitado = false;
var _idcostoagregado;
var _alumno;
var _modo_nuevo = false;
var _id_filacostoagregado_anterior;

AlumnosModel = function (data) {
    var self = this;

    self.MostrarTituloAlumno = ko.observable("");

    ko.mapping.fromJS(data, {}, self);

    self._NombreCompleto = ko.observable(data.NombreCompleto);
    self._ApellidoCompleto = ko.observable(data.ApellidoCompleto);
    self._CodigoAlumno = ko.observable(data.CodigoAlumno);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreCompleto());

        self.NombreCompleto.valueHasMutated();
        self.NombreCompleto("");
        self.NombreCompleto(self._NombreCompleto());

        self.ApellidoCompleto.valueHasMutated();
        self.ApellidoCompleto("");
        self.ApellidoCompleto(self._ApellidoCompleto());

        self.CodigoAlumno.valueHasMutated();
        self.CodigoAlumno("");
        self.CodigoAlumno(self._CodigoAlumno());

        return true;
      }

    }

    self.ConfirmarAlumno = function(data,event){
      if (event) {
        console.log("ConfirmarAlumno");
        self._NombreCompleto.valueHasMutated();
        self._NombreCompleto(self.NombreCompleto());

        self._ApellidoCompleto.valueHasMutated();
        self._ApellidoCompleto(self.ApellidoCompleto());

        self._CodigoAlumno.valueHasMutated();
        self._CodigoAlumno(self.CodigoAlumno());

      }
    }

    self.ListarAlumnos = function(data,event) {
      if (event) {
        console.log("ListarAlumnos");
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Catalogo/cAlumno/ListarAlumnos',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.data.Alumnos([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.data.Alumnos.push(new AlumnosModel(item));
                    });
                }
            }
        });
      }
    }

    self.SeleccionarAlumno = function (data,event)  {
      if (event) {
        if (data != undefined) {
          if (_modo_nuevo == false)
          {
            var id = "#"+data.IdAlumno()+ '_tr_alumno';
            $(id).addClass('active').siblings().removeClass('active');
            _alumno = data;
          }

        }
      }

    }

    self.FilaButtonsAlumno = function (data, event)  {
      if (event) {
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

          _alumno.Deshacer(null, event);
          _input_habilitado = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarAlumno").prop("disabled",false);
          self.HabilitarTablaSpanAlumno(null, true);

        }
      }
    }

    self.SeleccionarAlumnoAnterior = function (data, event)  {
      if (event) {
        var id = "#"+data.IdAlumno()+ '_tr_alumno';
        var anteriorObjeto = $(id).prev();

        anteriorObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo == false) //revisar
        {

          var _idfamiliaproducto = anteriorObjeto.attr("id");
          var match = ko.utils.arrayFirst(ViewModels.data.Alumnos(), function(item) {
            return _idfamiliaproducto == item.IdAlumno();
          });

          if(match)
          {
            _familiaproducto = match;
          }
        }
      }
    }


    self.SeleccionarAlumnoSiguiente = function (data, event)  {
      if (event) {
        var id = "#"+data.IdAlumno()+ '_tr_alumno';
        var siguienteObjeto = $(id).next();

        if (siguienteObjeto.length > 0)
        {
          siguienteObjeto.addClass('active').siblings().removeClass('active');

          if (_modo_nuevo == false) //revisar
          {
            //console.log(item.IdFamiliaProducto());
            var _idcostoagregado = siguienteObjeto.attr("id");
            //console.log(_idfamiliaproducto);
            var match = ko.utils.arrayFirst(self.data.Alumnos(), function(item) {
                  //console.log(item.IdFamiliaProducto());
                  return _idcostoagregado == item.IdAlumno();
              });

            if(match)
            {
              _alumno = match;
            }
          }
        }
        else {
          self.SeleccionarAlumnoAnterior(data,event);
        }
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputAlumno = function (data, event, option)  {
      if (event) {
        var id =data;
        if(option == false)
        {
          $(id).find('.class_InputAlumno').hide();
          $(id).find('.class_SpanAlumno').show();
        }
        else
        {
          $(id).find('.class_InputAlumno').show();
          $(id).find('.class_SpanAlumno').hide();
        }
      }
    }

    self.HabilitarTablaSpanAlumno = function (data, option)  {
      //var id = "#"+data.IdAlumno()+ '_tr_alumno';
      var id = "#DataTables_Table_0_alumno";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanAlumno').hide();
        $(id).find('.class_InputAlumno').show();
        //$(id).find('.guardar_button_Alumno').show();
        //_input_habilitado = true;
      }
      else {
        $(id).find('.class_SpanAlumno').show();
        $(id).find('.class_InputAlumno').hide();
        $(id).find('.guardar_button_Alumno').hide();
        //_input_habilitado = false;
      }

    }

    self.HabilitarButtonsAlumno = function(data, option){
      var id = "#DataTables_Table_0_alumno";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_Alumno').prop("disabled", true);
        $(id).find('.borrar_button_Alumno').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_Alumno').prop("disabled", false);
        $(id).find('.borrar_button_Alumno').prop("disabled", false);
      }
    }

    self.InsertarAlumno =function(data,event){
      if(event)
      {
        console.log("InsertarAlumno");
        console.log(_alumno.NombreCompleto());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.mapping.toJS({"Data" : _alumno});
        datajs.Data.IdCliente = ViewModels.data.Cliente.IdPersona();

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Catalogo/cAlumno/InsertarAlumno',
          success: function (data) {
            if (data != null) {
              console.log("resultado -  InsertarAlumno");
              console.log(data);

              if (data.IdAlumno)
              {
                _alumno.IdAlumno(data.IdAlumno);
                _alumno.IdCliente(data.IdCliente);
                //deshabilitar botones agregar
                $("#btnAgregarAlumno").prop("disabled",false);

                var id_alumno = "#"+ _alumno.IdAlumno() + '_tr_alumno';
                self.HabilitarFilaInputAlumno(id_alumno, event, false);

                var idbutton ="#"+_alumno.IdAlumno()+"_button_Alumno";
                $(idbutton).hide();

                _alumno.ConfirmarAlumno(null,event);
                self.HabilitarButtonsAlumno(null, true);

                existecambio = false;
                _input_habilitado = false;
                _modo_nuevo = false;

              }
              else {
                alertify.alert("ERROR EN "+self.MostrarTituloAlumno(),data.error.msg);
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

    self.ActualizarAlumno = function(data,event) {
      if (event) {
        console.log("ActualizarAlumno");
        console.log(_alumno.NombreCompleto());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _alumno});

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Catalogo/cAlumno/ActualizarAlumno',
          success: function (data) {
            if (data != null) {
              console.log(data);

              if (data == "")
              {
                //deshabilitar campo origen
                $("#btnAgregarAlumno").prop("disabled",false);
                console.log("ID5:"+_alumno.IdAlumno());
                _alumno.ConfirmarAlumno(null,event);

                var id_alumno = "#"+ _alumno.IdAlumno() + '_tr_alumno';
                self.HabilitarFilaInputAlumno(id_alumno, event, false);

                var idbutton ="#"+_alumno.IdAlumno()+"_button_Alumno";
                $(idbutton).hide();

                existecambio = false;
                _input_habilitado = false;
                _modo_nuevo = false;

              }
              else {
                alertify.alert("ERROR EN "+self.MostrarTituloAlumno(),data.error.msg);
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

    self.EditarAlumno = function(data, event) {
      if(event)
      {
        console.log("EditarAlumno");
        console.log("ID.:"+data.IdAlumno());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_alumno);

        if( _modo_nuevo == true )
        {

        }
        else {
          self.MostrarTituloAlumno("EDICIÓN DE ALUMNO");
          if (_alumno.IdAlumno() == data.IdAlumno())
          {

            if (_input_habilitado == true)
            {
              $("#btnAgregarAlumno").prop("disabled",false);
              data.Deshacer(null,event);
              var id_alumno = "#"+ data.IdAlumno()+ '_tr_alumno';
              self.HabilitarFilaInputAlumno(id_alumno, event, false);

              var idbutton = "#"+_alumno.IdAlumno()+"_button_Alumno";
              $(idbutton).hide();

              _input_habilitado =false;


            }
            else {
              $("#btnAgregarAlumno").prop("disabled",true);
              var id_alumno = "#"+ data.IdAlumno()+ '_tr_alumno';
              self.HabilitarFilaInputAlumno(id_alumno, event, true);

              var idbutton = "#"+data.IdAlumno()+"_button_Alumno";

              var idinput = "#"+data.IdAlumno()+"_input_NombreCompleto";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;
            }

          }
          else {
            $("#btnAgregarAlumno").prop("disabled",true);
            if( _input_habilitado == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_alumno.IdAlumno());

              var id_alumno = "#"+ _alumno.IdAlumno() + '_tr_alumno';
              self.HabilitarFilaInputAlumno(id_alumno, event, false);

              var idbutton = "#"+_alumno.IdAlumno()+"_button_Alumno";

              _alumno.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_alumno = "#"+ data.IdAlumno()+ '_tr_alumno';
            self.HabilitarFilaInputAlumno(id_alumno, event, true);

            var idbutton = "#"+data.IdAlumno()+"_button_Alumno";

            var idinput = "#"+data.IdAlumno()+"_input_NombreCompleto";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado = true;
          }


        }

      }

    }

    self.PreBorrarAlumno = function (data,event) {
      if (event) {
        if(_modo_nuevo == false)
        {
          _alumno.Deshacer(null, event);
          _input_habilitado = false;
          $("#btnAgregarAlumno").prop("disabled",false);
          self.HabilitarTablaSpanAlumno(null, true);
        }

        setTimeout(function(){
          self.MostrarTituloAlumno("ELIMINACION DE ALUMNO");
          alertify.confirm(self.MostrarTituloAlumno(),"¿Desea borrar el registro?", function(){
            console.log("BorrarAlumno");
            console.log(data.IdAlumno());
            self.HabilitarButtonsAlumno(null, true);
            if (data.IdAlumno() != null)
            self.BorrarAlumno(data,event);
            else
            {
              $("#btnAgregarAlumno").prop("disabled",false);
              _input_habilitado = false;
              _modo_nuevo = false;
              ViewModels.data.Alumnos.remove(data);
              var tabla = $('#DataTables_Table_0_alumno');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');
              //self.ListarAlumnos();
            }
          },function () {

          });

        }, 200);
      }

    }

    self.BorrarAlumno = function (data, event) {
      if (event) {
        var objeto = data;
        var datajs = ko.toJS({"Data":_alumno});

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Catalogo/cAlumno/BorrarAlumno',
          success: function (data) {
            if (data != null) {
              if(data == "")
              {
                $("#btnAgregarAlumno").prop("disabled",false);
                self.HabilitarTablaSpanAlumno(null, true);
                self.SeleccionarAlumnoSiguiente(objeto,event);
                ViewModels.data.Alumnos.remove(objeto);
              }
              else {
                alertify.alert("ERROR EN "+self.MostrarTituloAlumno(),data);
              }
            }
          },
        });
      }
    }


    self.OnClickAlumno = function(data ,event) {
      if(event)
      {
          console.log("OnClickAlumno");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_alumno);

          if( _modo_nuevo == true )
          {

          }
          else
          {

            $("#btnAgregarAlumno").prop("disabled",true);
            if(_alumno.IdAlumno() !=  data.IdAlumno())
            {
              if (_input_habilitado == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _alumno.Deshacer(null, event);

                //var id_alumno = "#" + _id_filacostoagregado_anterior;
                var id_alumno = "#" + _alumno.IdAlumno() + '_tr_alumno';
                self.HabilitarFilaInputAlumno(id_alumno, event, false);

                var idbutton = "#"+_alumno.IdAlumno()+"_button_Alumno";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_alumno.IdAlumno());
              console.log(data.IdAlumno());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_alumno = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_alumno, "span") || $.isSubstring(id_fila_alumno, "input")){
                id_fila_alumno = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filacostoagregado_anterior = $(id_fila_alumno).parent()[0].id;
              var idspan ="#"+$(id_fila_alumno).find('span').attr('id');
              var idinput ="#"+$(id_fila_alumno).find('input').attr('id');
              self.HabilitarFilaInputAlumno("#" + $(id_fila_alumno).parent()[0].id, event, true);

              var idbutton = "#"+data.IdAlumno()+"_button_Alumno";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;

              }
              else {
                if (_input_habilitado == false)
                {
                  var id_fila_alumno = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_alumno, "span") || $.isSubstring(id_fila_alumno, "input")){
                    id_fila_alumno = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputAlumno("#" + $(id_fila_alumno).parent()[0].id, event, true);

                  var idbutton = "#"+data.IdAlumno()+"_button_Alumno";
                  var idinput ="#"+$(id_fila_alumno).find('input').attr('id');
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
    self.OnKeyUpAlumno = function(data, event){
      if(event)
      {
       console.log("OnKeyUpAlumno");

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
         var idinputnombre = _alumno.IdAlumno() + '_input_NombreCompleto';

         if(event.target.id == idinputnombre)
         {
           _alumno.NombreCompleto(event.target.value);
         }


         if(_modo_nuevo == true)
         {
           self.InsertarAlumno(_alumno,event);
         }
         else
         {
           self.ActualizarAlumno(_alumno,event);
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
            alertify.confirm("REGISTRO DE ALUMNO","¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAlumnoAnterior(_alumno,event);
              self.data.Alumnos.remove(_alumno);
              var tabla = $('#DataTables_Table_0_alumno');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarAlumno").prop("disabled",false);
              self.HabilitarButtonsAlumno(null, true);
               _modo_nuevo = false;
               _input_habilitado = false;
            },function() {

            });
          }
          else
          {
            console.log("Escape - false");
            //revertir texto
            //data.NombreCompleto(_alumno.NombreCompleto());

             _alumno.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarAlumno").prop("disabled",false);

            self.HabilitarTablaSpanAlumno(null, true);

            var idbutton ="#"+_alumno.IdAlumno()+"_button_Alumno";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo = false;
            _input_habilitado = false;
          }

        }
      }
    }

    self.GuardarAlumno = function(data,event) {
      if(event)
      {
         console.log("GuardarAlumno");
         console.log(_nombrecostoagregado);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _alumno.IdAlumno() + '_input_NombreCompleto';

          if(event.target.id == idinputnombre)
          {
            _alumno.NombreCompleto(_nombrecostoagregado);
          }
         //_alumno.NombreCompleto(_nombrecostoagregado);

         if(_modo_nuevo == true)
         {
           self.InsertarAlumno(_alumno,event);
         }
         else
         {
           self.ActualizarAlumno(_alumno,event);
         }
      }
    }

}

AlumnoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    AlumnosModel.call(this,self);

    self.AgregarAlumno = function(data,event) {
      console.log("AgregarAlumno");

      if ( _input_habilitado == true )
      {

      }
      else
      {
        self.MostrarTituloAlumno("REGISTRO DE ALUMNO")
        var objeto = Knockout.CopiarObjeto(ViewModels.data.Alumno);
        _alumno = new AlumnosModel(objeto);
        ViewModels.data.Alumnos.push(_alumno);

        //Deshabilitando buttons
        self.HabilitarButtonsAlumno(null, false);
        $("#null_editar_button_Alumno").prop("disabled", true);
        $("#null_borrar_button_Alumno").prop("disabled", false);


        $("#btnAgregarAlumno").prop("disabled",true);

        //habilitar como destino
        console.log("ID:"+objeto.IdAlumno());

        var idspanNombre ="#"+objeto.IdAlumno()+"_span_NombreCompleto";
        var idinputNombre ="#"+objeto.IdAlumno()+"_input_NombreCompleto";

        var idspanApellido ="#"+objeto.IdAlumno()+"_span_ApellidoCompleto";
        var idinputApellido ="#"+objeto.IdAlumno()+"_input_ApellidoCompleto";

        var idspanCodigo ="#"+objeto.IdAlumno()+"_span_CodigoAlumno";
        var idinputCodigo ="#"+objeto.IdAlumno()+"_input_CodigoAlumno";

        var idbutton ="#"+objeto.IdAlumno()+"_button_Alumno";

        console.log(idbutton);

        $(idspanNombre).hide();
        $(idinputNombre).show();
        $(idinputNombre).focus();

        $(idspanApellido).hide();
        $(idinputApellido).show();

        $(idspanCodigo).hide();
        $(idinputCodigo).show();


        $(idbutton).show();

        _modo_nuevo = true;
        _input_habilitado = true;

        var tabla = $('#DataTables_Table_0_alumno');
        $('tr:last', tabla).addClass('active').siblings().removeClass('active');
      }
    }
}
