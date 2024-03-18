CorrelativosDocumentoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
    self.inicio=0;
    self._IdTipoDocumento = ko.observable(data.IdTipoDocumento);
    self._SerieDocumento = ko.observable(data.SerieDocumento);
    self._UltimoDocumento = ko.observable(data.UltimoDocumento);
    self._IdSede = ko.observable(data.IdSede);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER");

        self.IdTipoDocumento.valueHasMutated();
        //self.NombreAbreviado.valueHasMutated();
        self.SerieDocumento.valueHasMutated();
        self.UltimoDocumento.valueHasMutated();
        self.IdSede.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.IdTipoDocumento("");
        //self.NombreAbreviado("");
        self.SerieDocumento("");
        self.UltimoDocumento("");
        self.IdSede("");
        self.IdTipoDocumento(self._IdTipoDocumento());
        self.SerieDocumento(self._SerieDocumento());
        self.UltimoDocumento(self._UltimoDocumento());
        self.IdSede(self._IdSede());

        var id_tipodocumento = '#' + self.IdCorrelativoDocumento() +  '_input_IdTipoDocumento';
        var id_sede = '#' + self.IdCorrelativoDocumento() +  '_input_IdSede';

        // $(id_tipodocumento).selectpicker("refresh");
        // $(id_sede).selectpicker("refresh");

        return true;
      }
    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._IdTipoDocumento.valueHasMutated();
        self._IdTipoDocumento(self.IdTipoDocumento());
        self._SerieDocumento.valueHasMutated();
        self._SerieDocumento(self.SerieDocumento());
        self._UltimoDocumento.valueHasMutated();
        self._UltimoDocumento(self.UltimoDocumento());
        self._IdSede.valueHasMutated();
        self._IdSede(self.IdSede());
      }
    }
}

CorrelativoDocumentoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

var Mapping = {
    'CorrelativosDocumento': {
        create: function (options) {
            if (options)
              return new CorrelativosDocumentoModel(options.data);
            }
    },
    'CorrelativoDocumento': {
        create: function (options) {

            if (options)
              return new CorrelativoDocumentoModel(options.data);
            }
    }

}

IndexCorrelativoDocumento = function (data) {
    var _modo_deshacer = false;
    var _input_habilitado_correlativodocumento = false;
    var _modo_nuevo_correlativodocumento = false;
    var _objeto = null;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");
      if (_modo_nuevo_correlativodocumento == false)
      {
        if (data.inicio == 0) {
          //data.IdTipoDocumento(data._IdTipoDocumento());
          //data.IdSede(data._IdSede());
          data.inicio = 1;
        }

        console.log(data.IdTipoDocumento());
        console.log("-------");
        var id = "#"+data.IdCorrelativoDocumento()+'_tr_correlativodocumento';
        $(id).addClass('active').siblings().removeClass('active');
        _objeto = data;
      }
    }

    self.FilaButtonsCorrelativoDocumento = function (data, event)  {
      console.log("FILASBUTONES");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_correlativodocumento);
          if(_modo_nuevo_correlativodocumento == true)
          return;
          console.log("OTRA FILA AFECTADA");
          //self.Deshacer(data, event);
          _objeto.Deshacer(null, event);
          _input_habilitado_correlativodocumento = false;
          $("#btnAgregarCorrelativoDocumento").prop("disabled",false);
          self.HabilitarTablaSpanCorrelativoDocumento(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdCorrelativoDocumento()+'_tr_correlativodocumento';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_correlativodocumento == false) //revisar
      {
        //console.log(item.IdCorrelativoDocumento());
        var _idcorrelativodocumento = anteriorObjeto.attr("id");
        //console.log(_idcorrelativodocumento);
        var match = ko.utils.arrayFirst(self.dataCorrelativoDocumento.CorrelativosDocumento(), function(item) {
              //console.log(item.IdCorrelativoDocumento());
              return _idcorrelativodocumento == item.IdCorrelativoDocumento();
          });

        if(match)
        {
          _idcorrelativodocumento = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdCorrelativoDocumento()+'_tr_correlativodocumento';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {

        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_correlativodocumento == false) //revisar
        {
          //console.log(item.IdCorrelativoDocumento());
          var _idcorrelativodocumento = siguienteObjeto.attr("id");
          //console.log(_idcorrelativodocumento);
          var match = ko.utils.arrayFirst(self.dataCorrelativoDocumento.CorrelativosDocumento(), function(item) {
                //console.log(item.IdCorrelativoDocumento());
                return _idcorrelativodocumento == item.IdCorrelativoDocumento();
            });

          if(match)
          {
            _objeto = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputCorrelativoDocumento = function (data, option)  {
      //var id = "#"+data.IdCorrelativoDocumento();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');
      if(option == false)
      {
        $(id).find('.class_InputCorrelativoDocumento').hide();
        $(id).find('.class_SpanCorrelativoDocumento').show();
      }
      else
      {
        $(id).find('.class_InputCorrelativoDocumento').show();
        $(id).find('.class_SpanCorrelativoDocumento').hide();
      }

    }

    self.HabilitarTablaSpanCorrelativoDocumento = function (data, option)  {
      //var id = "#"+data.IdCorrelativoDocumento();
      var id = "#DataTables_Table_0_correlativodocumento";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanCorrelativoDocumento').hide();
        $(id).find('.class_InputCorrelativoDocumento').show();
        //$(id).find('.guardar_button_CorrelativoDocumento').show();
        //_input_habilitado_correlativodocumento = true;
      }
      else {
        $(id).find('.class_SpanCorrelativoDocumento').show();
        $(id).find('.class_InputCorrelativoDocumento').hide();
        $(id).find('.guardar_button_CorrelativoDocumento').hide();
        //_input_habilitado_correlativodocumento = false;
      }

    }

    self.HabilitarButtonsCorrelativoDocumento = function(data, option){
      var id = "#DataTables_Table_0_correlativodocumento";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_CorrelativoDocumento').prop("disabled", true);
        $(id).find('.borrar_button_CorrelativoDocumento').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_CorrelativoDocumento').prop("disabled", false);
        $(id).find('.borrar_button_CorrelativoDocumento').prop("disabled", false);
      }
    }


    self.AgregarCorrelativoDocumento = function(data,event) {
        console.log("AgregarCorrelativoDocumento");

        if ( _input_habilitado_correlativodocumento != true )
        {
          var objeto = Knockout.CopiarObjeto(self.dataCorrelativoDocumento.CorrelativoDocumento);
          //objeto.NombreTipoCorrelativoDocumento = "AGENCIA";
          //objeto.IdTipoCorrelativoDocumento = 1;
          console.log(objeto);
          _objeto = new CorrelativosDocumentoModel(objeto);
          self.dataCorrelativoDocumento.CorrelativosDocumento.push(_objeto);


          //Deshabilitando buttons
          self.HabilitarButtonsCorrelativoDocumento(null, false);
          $("#null_editar_button_CorrelativoDocumento").prop("disabled", true);
          $("#null_borrar_button_CorrelativoDocumento").prop("disabled", false);


          $("#btnAgregarCorrelativoDocumento").prop("disabled",true);

          //habilitar como destino
          console.log("ID:"+objeto.IdCorrelativoDocumento());
          var id_span_tipodocumento ="#"+objeto.IdCorrelativoDocumento()+"_span_IdTipoDocumento";
          var id_input_tipodocumento ="#"+objeto.IdCorrelativoDocumento()+"_input_IdTipoDocumento";
          var id_combo_tipodocumento ="#"+objeto.IdCorrelativoDocumento()+"_combo_IdTipoDocumento";

          var id_span_seriedocumento ="#"+objeto.IdCorrelativoDocumento()+"_span_SerieDocumento";
          var id_input_seriedocumento ="#"+objeto.IdCorrelativoDocumento()+"_input_SerieDocumento";

          var id_span_ultimodocumento ="#"+objeto.IdCorrelativoDocumento()+"_span_UltimoDocumento";
          var id_input_ultimodocumento ="#"+objeto.IdCorrelativoDocumento()+"_input_UltimoDocumento";

          var id_span_sede ="#"+objeto.IdCorrelativoDocumento()+"_span_IdSede";
          var id_input_sede ="#"+objeto.IdCorrelativoDocumento()+"_input_IdSede";
          var id_combo_sede ="#"+objeto.IdCorrelativoDocumento()+"_combo_IdSede";

          var idbutton ="#"+objeto.IdCorrelativoDocumento()+"_button_CorrelativoDocumento";

          console.log(idbutton);
          //var id_tipocorrelativodocumento = '#' + self.IdCorrelativoDocumento() +  '_input_IdTipoCorrelativoDocumento';
          // $(id_input_tipodocumento).selectpicker("refresh");
          // $(id_input_sede).selectpicker("refresh");

          $(id_span_tipodocumento).hide();
          $(id_combo_tipodocumento).show();

          $(id_span_seriedocumento).hide();
          $(id_input_seriedocumento).show();

          $(id_span_ultimodocumento).hide();
          $(id_input_ultimodocumento).show();

          $(id_span_sede).hide();
          $(id_combo_sede).show();

          $(idbutton).show();
          $(id_input_seriedocumento).focus();

          _modo_nuevo_correlativodocumento = true;
          _input_habilitado_correlativodocumento = true;

          var tabla = $('#DataTables_Table_0_correlativodocumento');
          $('tr:last', tabla).addClass('active').siblings().removeClass('active');
        }
    }

    self.InsertarCorrelativoDocumento =function(data,event){

      if(event)
      {
        console.log("InsertarCorrelativoDocumento");
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _objeto});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cCorrelativoDocumento/InsertarCorrelativoDocumento',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarCorrelativoDocumento");
                      console.log(data);

                      if ($.isNumeric(data.IdCorrelativoDocumento))
                      {
                        _objeto.IdCorrelativoDocumento(data.IdCorrelativoDocumento);
                        //deshabilitar botones agregar
                        $("#btnAgregarCorrelativoDocumento").prop("disabled",false);

                        var id_correlativodocumento = "#"+ _objeto.IdCorrelativoDocumento()+'_tr_correlativodocumento';
                        self.HabilitarFilaInputCorrelativoDocumento(id_correlativodocumento, false);

                        var idbutton ="#"+_objeto.IdCorrelativoDocumento()+"_button_CorrelativoDocumento";
                        $(idbutton).hide();

                         _objeto.Confirmar(null,event);
                         //self.Confirmar(data, event);
                         self.HabilitarButtonsCorrelativoDocumento(null, true);

                         //ACTUALIZANDO DATA Nombre
                         var idnombretipodocumento = '#' + _objeto.IdCorrelativoDocumento() + '_input_IdTipoDocumento option:selected';
                         var nombretipodocumento = $(idnombretipodocumento).html();
                         var idnombresede = '#' + _objeto.IdCorrelativoDocumento() + '_input_IdSede option:selected';
                         var nombresede = $(idnombresede).html();
                         _objeto.NombreAbreviado(nombretipodocumento);
                         _objeto.NombreSede(nombresede);

                        existecambio = false;
                        _input_habilitado_correlativodocumento = false;
                        _modo_nuevo_correlativodocumento = false;

                      }
                      else {
                        alertify.alert(data.IdCorrelativoDocumento);
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

    self.ActualizarCorrelativoDocumento = function(data,event) {
          console.log("ActualizarCorrelativoDocumento");
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _objeto});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cCorrelativoDocumento/ActualizarCorrelativoDocumento',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarCorrelativoDocumento").prop("disabled",false);
                          console.log("ID5:"+_objeto.IdCorrelativoDocumento());
                          //self.Confirmar(data, event);
                          _objeto.Confirmar(null,event);

                          var id_correlativodocumento = "#"+ _objeto.IdCorrelativoDocumento()+'_tr_correlativodocumento';
                          self.HabilitarFilaInputCorrelativoDocumento(id_correlativodocumento, false);

                          var idbutton ="#"+_objeto.IdCorrelativoDocumento()+"_button_CorrelativoDocumento";
                          $(idbutton).hide();

                          //ACTUALIZANDO DATA Nombre
                          var idnombretipodocumento = '#' + _objeto.IdCorrelativoDocumento() + '_input_IdTipoDocumento option:selected';
                          var nombretipodocumento = $(idnombretipodocumento).html();
                          var idnombresede = '#' + _objeto.IdCorrelativoDocumento() + '_input_IdSede option:selected';
                          var nombresede = $(idnombresede).html();
                          _objeto.NombreAbreviado(nombretipodocumento);
                          _objeto.NombreSede(nombresede);


                          existecambio = false;
                          _input_habilitado_correlativodocumento = false;
                          _modo_nuevo_correlativodocumento = false;
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

    self.EditarCorrelativoDocumento = function(data, event) {

      if(event)
      {
        console.log("EditarCorrelativoDocumento");
        console.log("ID.:"+data.IdCorrelativoDocumento());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_objeto);

        if(_modo_nuevo_correlativodocumento != true )
        {
          if (_objeto.IdCorrelativoDocumento() == data.IdCorrelativoDocumento())
          {

            if (_input_habilitado_correlativodocumento == true)
            {
              $("#btnAgregarCorrelativoDocumento").prop("disabled",false);
              //self.Deshacer(data, event);
              data.Deshacer(null,event);
              //HABILITANDO MEDIANTE ID
              var id_correlativodocumento = "#"+ data.IdCorrelativoDocumento()+'_tr_correlativodocumento';
              self.HabilitarFilaInputCorrelativoDocumento(id_correlativodocumento, false);

              var idbutton = "#"+_objeto.IdCorrelativoDocumento()+"_button_CorrelativoDocumento";
              $(idbutton).hide();

              _input_habilitado_correlativodocumento =false;
            }
            else {
              $("#btnAgregarCorrelativoDocumento").prop("disabled",true);
              var id_correlativodocumento = "#"+ data.IdCorrelativoDocumento()+'_tr_correlativodocumento';
              self.HabilitarFilaInputCorrelativoDocumento(id_correlativodocumento, true);

              var idbutton = "#"+data.IdCorrelativoDocumento()+"_button_CorrelativoDocumento";
              var idinput = "#"+data.IdCorrelativoDocumento()+"_input_SerieDocumento";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_correlativodocumento = true;
            }

          }
          else {
            $("#btnAgregarCorrelativoDocumento").prop("disabled",true);
            if( _input_habilitado_correlativodocumento == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_objeto.IdCorrelativoDocumento());
              var id_correlativodocumento = "#"+ _objeto.IdCorrelativoDocumento()+'_tr_correlativodocumento';
              self.HabilitarFilaInputCorrelativoDocumento(id_correlativodocumento, false);

              var idbutton = "#"+_objeto.IdCorrelativoDocumento()+"_button_CorrelativoDocumento";

              //self.Deshacer(data, event);
              _objeto.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_correlativodocumento = "#"+ data.IdCorrelativoDocumento()+'_tr_correlativodocumento';
            self.HabilitarFilaInputCorrelativoDocumento(id_correlativodocumento, true);

            var idbutton = "#"+data.IdCorrelativoDocumento()+"_button_CorrelativoDocumento";

            var idinput = "#"+data.IdCorrelativoDocumento()+"_input_SerieDocumento";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_correlativodocumento = true;
          }


        }

      }
    }

    self.PreBorrarCorrelativoDocumento = function (data, event) {

      if(_modo_nuevo_correlativodocumento == false)
      {
        _objeto.Deshacer(null, event);
        //self.Deshacer(data, event);
        _input_habilitado_correlativodocumento = false;
        $("#btnAgregarCorrelativoDocumento").prop("disabled",false);
        self.HabilitarTablaSpanCorrelativoDocumento(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarCorrelativoDocumento");
          console.log(data.IdCorrelativoDocumento());
          self.HabilitarButtonsCorrelativoDocumento(null, true);
          if (data.IdCorrelativoDocumento() != null){
            self.BorrarCorrelativoDocumento(data);
          }
          else
          {
            $("#btnAgregarCorrelativoDocumento").prop("disabled",false);
            _input_habilitado_correlativodocumento = false;
            _modo_nuevo_correlativodocumento = false;
            self.dataCorrelativoDocumento.CorrelativosDocumento.remove(data);
            var tabla = $('#DataTables_Table_0_correlativodocumento');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarCorrelativosDocumento();
          }
        });
      }, 200);
    }

    self.BorrarCorrelativoDocumento = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cCorrelativoDocumento/BorrarCorrelativoDocumento',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarCorrelativoDocumento");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarCorrelativoDocumento").prop("disabled",false);
                      self.HabilitarTablaSpanCorrelativoDocumento(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataCorrelativoDocumento.CorrelativosDocumento.remove(objeto);
                    }
              }
          },
          error : function (jqXHR, textStatus, errorThrown) {
                 console.log(jqXHR.responseText);
             }
      });

    }


    self.OnClickCorrelativoDocumento = function(data ,event) {

      if(event)
      {
          console.log("OnClickCorrelativoDocumento");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_objeto);

          if( _modo_nuevo_correlativodocumento != true )
          {
            $("#btnAgregarCorrelativoDocumento").prop("disabled",true);
            if(_objeto.IdCorrelativoDocumento() !=  data.IdCorrelativoDocumento())
            {
              if (_input_habilitado_correlativodocumento == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                //self.Deshacer(data, event);
                _objeto.Deshacer(null, event);

                //var id_correlativodocumento = "#" + _id_filacorrelativodocumento_anterior;
                var id_correlativodocumento = "#" + _objeto.IdCorrelativoDocumento()+'_tr_correlativodocumento';
                self.HabilitarFilaInputCorrelativoDocumento(id_correlativodocumento, false);

                var idbutton = "#"+_objeto.IdCorrelativoDocumento()+"_button_CorrelativoDocumento";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_correlativodocumento = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_correlativodocumento, "span") || $.isSubstring(id_fila_correlativodocumento, "input")){
                id_fila_correlativodocumento = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              var idinput ="#"+$(id_fila_correlativodocumento).find('input').attr('id');
              self.HabilitarFilaInputCorrelativoDocumento("#" + $(id_fila_correlativodocumento).parent()[0].id, true);

              var idbutton = "#"+data.IdCorrelativoDocumento()+"_button_CorrelativoDocumento";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_correlativodocumento = true;

            }
            else {
              if (_input_habilitado_correlativodocumento == false)
              {
                var id_fila_correlativodocumento = "#" + $(event.target).attr('id');

                //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                if($.isSubstring(id_fila_correlativodocumento, "span") || $.isSubstring(id_fila_correlativodocumento, "input")){
                  id_fila_correlativodocumento = "#" + $(event.target).parent()[0].id;
                }


                self.HabilitarFilaInputCorrelativoDocumento("#" + $(id_fila_correlativodocumento).parent()[0].id, true);

                var idbutton = "#"+data.IdCorrelativoDocumento()+"_button_CorrelativoDocumento";
                var idinput ="#"+$(id_fila_correlativodocumento).find('input').attr('id');
                $(idbutton).show()
                $(idinput).focus();

                _input_habilitado_correlativodocumento = true;
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

    self.ActualizacionDatos = function(event){
      if(event)
      {
        var input_idtipodocumento = '#' + _objeto.IdCorrelativoDocumento() + '_input_IdTipoDocumento';
         var input_seriedocumento = '#' + _objeto.IdCorrelativoDocumento() + '_input_SerieDocumento';
         var input_ultimodocumento = '#' + _objeto.IdCorrelativoDocumento() + '_input_UltimoDocumento';
         var input_idsede ='#' +  _objeto.IdCorrelativoDocumento() + '_input_IdSede';

         _objeto.IdTipoDocumento($(input_idtipodocumento).val());
         _objeto.SerieDocumento($(input_seriedocumento).val());

         var string = numeral($(input_ultimodocumento).val()).format('00000000');
         console.log(string);
         _objeto.UltimoDocumento(string);

         _objeto.IdSede($(input_idsede).val());
      }
    }

    //FUNCION DE MANEJO DE TECLAS Y ATAJOS
    self.OnKeyUpCorrelativoDocumento = function(data, event){
      if(event)
      {
       console.log("OnKeyUpCorrelativoDocumento");

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
         self.ActualizacionDatos(event);


         if(_modo_nuevo_correlativodocumento == true)
         {
           self.InsertarCorrelativoDocumento(_objeto,event);
         }
         else
         {
           self.ActualizarCorrelativoDocumento(_objeto,event);
         }

       }
       else if(code === TECLA_TAB){
        var input_ultimodocumento = '#' + _objeto.IdCorrelativoDocumento() + '_input_UltimoDocumento';
         var string = numeral($(input_ultimodocumento).val()).format('00000000');

         $(input_ultimodocumento).val(string);
         _objeto.UltimoDocumento(string);
       }


       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado_correlativodocumento == true)
        {
          if(_modo_nuevo_correlativodocumento == true)
          {
            alertify.confirm("¿Desea borrar el registro?", function(){
              self.SeleccionarAnterior(_objeto);
              self.dataCorrelativoDocumento.CorrelativosDocumento.remove(_objeto);
              var tabla = $('#DataTables_Table_0_correlativodocumento');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarCorrelativoDocumento").prop("disabled",false);
              self.HabilitarButtonsCorrelativoDocumento(null, true);
               _modo_nuevo_correlativodocumento = false;
               _input_habilitado_correlativodocumento = false;
            });
          }
          else
          {
            console.log("Escape - false");
            //revertir texto

            //self.Deshacer(data, event);
             _objeto.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarCorrelativoDocumento").prop("disabled",false);

            /*var id_fila_correlativodocumento = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_correlativodocumento, "span") || $.isSubstring(id_fila_correlativodocumento, "input")){
              id_fila_correlativodocumento = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputCorrelativoDocumento("#" + $(id_fila_correlativodocumento).parent()[0].id, false);*/
            self.HabilitarTablaSpanCorrelativoDocumento(null, true);
            var idbutton ="#"+_objeto.IdCorrelativoDocumento()+"_button_CorrelativoDocumento";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_correlativodocumento = false;
            _input_habilitado_correlativodocumento = false;
          }

        }
      }
    }

    self.GuardarCorrelativoDocumento = function(data,event) {
      if(event)
      {
         console.log("GuardarCorrelativoDocumento");

         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
         self.ActualizacionDatos(event);

         if(_modo_nuevo_correlativodocumento == true)
         {
           self.InsertarCorrelativoDocumento(_objeto,event);
         }
         else
         {
           self.ActualizarCorrelativoDocumento(_objeto,event);
         }
      }
    }

}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
