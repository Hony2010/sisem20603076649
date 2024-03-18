
ModelosModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreModelo = ko.observable(data.NombreModelo);

    self.VistaOptions = ko.pureComputed(function(){
      return self.NoEspecificado() == "S" ? "hidden" : "visible";
    }, this);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //console.log("Deshacer");
        self.NombreModelo.valueHasMutated();
        self.NombreModelo("");
        self.NombreModelo(self._NombreModelo());
        return true;
      }
    }

    self.Confirmar = function(data,event) {
        if (event) {
          //console.log("Confirmar");
          self._NombreModelo.valueHasMutated();
          self._NombreModelo(self.NombreModelo());
        }
    }
}

ModeloModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

NuevoModeloModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

var Mapping2 = {
    'Modelos': {
        create: function (options) {
          if (options)
            return new ModelosModel(options.data);
            }
    },
    'Modelo': {
        create: function (options) {
            if (options)
              return new ModeloModel(options.data);
            }
    },
    'NuevoModelo': {
        create: function (options) {
            if (options)
              return new NuevoModeloModel(options.data);
            }
    }
}

IndexModelo = function (data) {

    var _input_habilitado_modelo = false;
    var _modo_nuevo_modelo = false;
    var _codigo_evento_previo = 0;
    var _modelo = null;
    var self = this;

    ko.mapping.fromJS(data, Mapping2, self);

    self.EstaHabilitadaVistaModelo = function () {
      console.log("EstaHabilitadoOpcionesVistaModelo");
      if ( $("#modelo").find('.opt_modelo').prop("disabled") == true)
        return false;
      else
        return true;
    }

    self.HabilitarTablaSpanModelo = function (data, option)  {
      //var id = "#"+data.IdTipoExistencia();
      var id = ".DataTables_Table_modelo";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanModelo').hide();
        $(id).find('.class_InputModelo').show();
      }
      else {
        $(id).find('.class_SpanModelo').show();
        $(id).find('.class_InputModelo').hide();
        $(id).find('.guardar_button_Modelo').hide();
        //_input_habilitado_modelo = false;
      }

    }

    self.FilaButtonsModelo = function (data, event)  {
      console.log("FilaButtonsMarca");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else
      {
          console.log("MODO NUEVO: " + _modo_nuevo_modelo);
          if(_modo_nuevo_modelo == true)
          return;

          if(_input_habilitado_modelo == false)
          return;

          self.RefrescarModelos(null, event);
      }

    }

    self.RefrescarModelos = function(data, event){
      if (event) {
        data.Deshacer(null, event);
        _input_habilitado_modelo = false;
        $("#btnAgregarModelo").prop("disabled",false);
        // $('#opcion-marca').removeClass('disabledTab');
        self.HabilitarTablaSpanModelo(null,  true);
      }
    }

    self.HabilitarFilaModelo = function (data, option) {

      var idspan ="#"+data.IdModelo()+"_span_NombreModelo";
      var idinput ="#"+data.IdModelo()+"_input_NombreModelo";
      var idbuttonguardar ="#"+data.IdModelo()+"_button_Modelo";

      if (option == false)
      {
        $(idspan).show();
        $(idinput).hide();
        $(idbuttonguardar).hide();
        //$("#brand").find("button").prop("disabled",false);
        //$("#btnAgregarMarca").prop("disabled",false);
        $("#btnAgregarModelo").prop("disabled",false);
      }
      else {
        $(idspan).hide();
        $(idinput).show();
        $(idbuttonguardar).show();
        $(idinput).focus();
        //$("#brand").find("button").prop("disabled",true);
        //$("#btnAgregarMarca").prop("disabled",true);
        $("#btnAgregarModelo").prop("disabled",true);
      }
    }

    self.SeleccionarAnterior = function (data)  {
        var id ="#"+data.IdModelo()+"_modelo";
        var anteriorObjeto = $(id).prev();

        anteriorObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_modelo == false)
        {
          var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcMarca.dataMarca.Modelos(), function(item) {
                return anteriorObjeto.attr("id") == item.IdModelo();
            });

          if(match)
          {
            vistaModeloCatalogo.vmcMarca.dataMarca.Modelo= match;
          }
        }
    }

    self.SeleccionarSiguiente = function (data)  {
      var id ="#"+data.IdModelo()+"_modelo";
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_modelo == false) //revisar
        {
          var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcMarca.dataMarca.Modelos(), function(item) {
                return siguienteObjeto.attr("id") == item.IdModelo();
            });

          if(match)
          {
            vistaModeloCatalogo.vmcMarca.dataMarca.Modelo= match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }

    self.SeleccionarNormalModelo = function(data){
      if(data.NoEspecificado() == "S" && _modo_nuevo_modelo == false){
        self.SeleccionarModelo(data);
      }
      else {
        self.SeleccionarModelo(data);
      }
    }

    self.SeleccionarModelo = function (data,event) {

      if (_modo_nuevo_modelo == false)
      {
        var id = "#"+data.IdModelo()+"_modelo";
        $(id).addClass('active').siblings().removeClass('active');
        //$("#TituloNombreMarca").text(data.NombreMarca());
        //if (vistaModeloCatalogo.vmcMarca.dataMarca.Modelo.IdModelo() != data.IdModelo())
          //self.PrimerModelo(data);
        _modelo = data;

        if (_codigo_evento_previo > 0 )
        {
          vistaModeloCatalogo.vmcMarca.dataMarca.Modelo = data;
          _codigo_evento_previo = 0;
        }
      }
    }

    //self.ListarModelos = function() {
    //  self.ConsultarModelo(vistaModeloCatalogo.vmcMarca.dataMarca.Marca);// self._marca
    //}
    self.HabilitarOpcionesVistaModelo = function(option) {
      //console.log("btn_modelo");
      if (option == true)
        $("#modelo").find('.opt_modelo').prop('disabled',true);
      else
        $("#modelo").find('.opt_modelo').prop("disabled",false);
    }


    self.AgregarModelo = function(data,event) {
      console.log("AgregarModelo");

      if ( _input_habilitado_modelo == true )
      {

      }
      else
      {
        var objeto = Knockout.CopiarObjeto(vistaModeloCatalogo.vmcMarca.dataMarca.NuevoModelo);
        vistaModeloCatalogo.vmcMarca.dataMarca.Modelo  = new ModelosModel(objeto);
        vistaModeloCatalogo.vmcMarca.dataMarca.Modelos.push(vistaModeloCatalogo.vmcMarca.dataMarca.Modelo);
        vistaModeloCatalogo.vmcMarca.dataMarca.Modelo.IdMarca(vistaModeloCatalogo.vmcMarca.dataMarca.Marca.IdMarca());
        //console.log("ID Marca en Modelo: " + vistaModeloCatalogo.vmcMarca.dataMarca.Modelo.IdMarca());
        //console.log("ID Marca: " + vistaModeloCatalogo.vmcMarca.dataMarca.Marca.IdMarca());
        self.HabilitarFilaModelo(objeto , true);

        // $('#opcion-marca').removeClass('active').addClass("disabledTab");

        /*OCULTAMOS EL TEXTO DEL ID EN LA FILA NUEVA*/
        $("#-1_modelo td:first").text("");

        /*DESHABILITANDO BOTONES DE CONTROL Y HABILITANDO EL EDITAR DEL NUEVO*/
        self.HabilitarOpcionesVistaModelo(true);
        var id_borrar_modelo = "#-1_borrar_button_Modelo";
        $(id_borrar_modelo).prop("disabled", false);

        /*DESHABILITAMOS EL BOTON MARCA*/
        // $("#btnAgregarMarca").prop("disabled",true);
        /*SELECCIONANDO ULTIMA FILA*/
        var tabla = $('.DataTables_Table_modelo');
        $('tr:last', tabla).addClass('active').siblings().removeClass('active');

        //self.HabilitarSubFilaMarca(objeto , true);
        /*SELECCIONADO ELEMENTO*/
        //var input_marca = document.getElementById('-1_input_NombreModelo');
        //input_marca.focus();
        //input_marca.scrollIntoView();
        //$("#-1_input_NombreModelo").val("PRUEBA");
        //$("#-1_input_NombreModelo").select();
        setTimeout(function(){
          $("#-1_input_NombreModelo").focus();
        }, 150);

        //$('input:last', tabla).focus()
        _modo_nuevo_modelo = true;
        _input_habilitado_modelo = true;
      }

    }

    self.InsertarModelo = function(data,event){

      if(event)
      {
        //console.log("InsertarModelo");
        $("#loader").show();
        var datajs = ko.toJS({"Data" : data});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cModelo/InsertarModelo',
                success: function (data) {
                    if (data != null) {

                      if ($.isNumeric(data.IdModelo))
                      {
                        vistaModeloCatalogo.vmcMarca.dataMarca.Modelo.IdModelo(data.IdModelo);
                        vistaModeloCatalogo.vmcMarca.dataMarca.Modelo.Confirmar(null,event);
                        self.HabilitarFilaModelo(vistaModeloCatalogo.vmcMarca.dataMarca.Modelo, false);

                        self.HabilitarOpcionesVistaModelo(false);

                        //HABILITANDO EL TAB MODELO
                        // $('#opcion-marca').removeClass('disabledTab');
                        existecambio = false;
                        _input_habilitado_modelo = false;
                        _modo_nuevo_modelo = false;
                      }
                      else {
                        alertify.alert(data.IdModelo);
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

    self.ActualizarModelo = function(data,event){
      $("#loader").show();
      console.log("ActualizarModelo");
      var datajs = ko.toJS({"Data" : data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cModelo/ActualizarModelo',
              success: function (data) {
                  if (data != null) {

                    if (data == "")
                    {
                      //deshabilitar campo origen
                      vistaModeloCatalogo.vmcMarca.dataMarca.Modelo.Confirmar(null,event);
                      self.HabilitarFilaModelo(vistaModeloCatalogo.vmcMarca.dataMarca.Modelo,false);
                      // $('#opcion-marca').removeClass('disabledTab');

                      existecambio = false;
                      _input_habilitado_modelo = false;
                      _modo_nuevo_modelo = false;
                    }
                    else
                    {
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

    self.OnClickNombreModelo = function(data,event) {

      if(event)
      {
        if(data.NoEspecificado() == "S" && _modo_nuevo_modelo == false)
        {
          if(_input_habilitado_modelo==false)
            return;

          _modelo.Deshacer(null, event);
          _input_habilitado_modelo = false;
          //$("#btnAgregarMarca").prop("disabled",false);
          $("#btnAgregarModelo").prop("disabled",false);
          self.HabilitarTablaSpanModelo(null, true);
          // $('#opcion-marca').removeClass('disabledTab');


        }
        else {

          _codigo_evento_previo=1;
          //console.log("OnClickMarca");
          if( _modo_nuevo_modelo == true )
          {

          }
          else
          {
            // $('#opcion-marca').removeClass('active').addClass('disabledTab');
            if(self.EstaHabilitadaVistaModelo() == false)
            {
              return false;
            }

            if(vistaModeloCatalogo.vmcMarca.dataMarca.Modelo.IdModelo() !=  data.IdModelo())
            {
              if (_input_habilitado_modelo == true)
              {
                //deshabilitar campo origen
                _modelo.Deshacer(null,event);
                self.HabilitarFilaModelo(vistaModeloCatalogo.vmcMarca.dataMarca.Modelo,false);
              }

              //habilitar como destino
              self.HabilitarFilaModelo(data,true);
              _input_habilitado_modelo = true;
            }
            else
            {
              if (_input_habilitado_modelo == false)
              {
                self.HabilitarFilaModelo(data,true);
                _input_habilitado_modelo = true;
              }
              else
              {
                //
              }
            }

          }
        }
          return false;
      }

    }

    self.EditarModelo = function(data,event) {
        if(event)
        {
            console.log("EditarModelo");
            _codigo_evento_previo=2;

            if( _modo_nuevo_modelo == true )
            {

            }
            else
            {
              // $('#opcion-marca').removeClass('active').addClass("disabledTab");
              if(self.EstaHabilitadaVistaModelo() == false)
              {
                return false;
              }

              if (vistaModeloCatalogo.vmcMarca.dataMarca.Modelo.IdModelo() == data.IdModelo())
              {
                if (_input_habilitado_modelo == true)
                {
                  //deshabilitar origen
                  // $('#opcion-marca').removeClass('disabledTab');
                  _modelo.Deshacer(null,event);
                  self.HabilitarFilaModelo(data,false);
                  _input_habilitado_modelo =false;
                }
                else
                {
                  //habilitar origen
                 self.HabilitarFilaModelo(data,true);
                 _input_habilitado_modelo = true;
                }
             }
             else
             {
                if (_input_habilitado_modelo == true)
                {
                  //deshabilitar origen
                  _modelo.Deshacer(null,event);
                  self.HabilitarFilaModelo(vistaModeloCatalogo.vmcMarca.dataMarca.Modelo,false);
                }

                //habilitar destino
                self.HabilitarFilaModelo(data,true);
                _input_habilitado_modelo = true;
             }
            }
        }


    }

    self.OnKeyUpNombreModelo = function (data,event){

        if(event)
        {
         //console.log("OnKeyUpNombreMarca");

         var code = event.keyCode || event.which;

         existecambio = true;

         if (code === 13)
         {//enter
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

           if(_modo_nuevo_modelo == true)
           {
             self.InsertarModelo(data,event);
           }
           else
           {
             self.ActualizarModelo(data,event);
           }
         }

         return true;
        }
    }

    self.EscaparGlobalModelo = function(event)
    {
      if(event)
      {
        if(_input_habilitado_modelo == true)
        {
          if(_modo_nuevo_modelo == true)
          {
            //alertify.confirm("¿Desea perder el nuevo registro?", function(){
              // self.HabilitarFilaModelo(_modelo,false);
              // self.SeleccionarAnterior(_modelo);
              // vistaModeloCatalogo.vmcMarca.dataMarca.Modelos.remove(_modelo);
              // self.HabilitarOpcionesVistaModelo(false);
              //HABILITANDO EL TAB MODELO
              // $('#opcion-marca').removeClass('disabledTab');
              _modo_nuevo_modelo = false;
              _input_habilitado_modelo = false;
            //});
          }
          else
          {
            // $('#opcion-marca').removeClass('disabledTab');
            //self.HabilitarOpcionesVistaModelo(false);
            console.log("escape - modo edicion");
            //deshabilitar botones
            _modelo.Deshacer(null,event);
            self.HabilitarFilaModelo(_modelo,false);
            existecambio=false;
            _modo_nuevo_modelo = false;
            _input_habilitado_modelo = false;
          }

        }
      }
    }

    self.GuardarModelo = function (data,event) {
      if(event)
      {
         console.log("GuardarModelo");
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }

         if(_modo_nuevo_modelo == true)
         {
           self.InsertarModelo(data,event);
         }
         else
         {
           self.ActualizarModelo(data,event);
         }
      }

    }



    self.PreBorrarModelo = function(data,event){

        if(_modo_nuevo_modelo == false)
        {
          self.RefrescarModelos(data,  event);
        }

        setTimeout(function(){
          alertify.confirm("¿Desea borrar el registro?", function(){
            console.log("BorrarModelo");

            if (data.IdModelo() != null && _modo_nuevo_modelo == false)
            {
              self.BorrarModelo(data);
            }
            else
            {
              self.HabilitarOpcionesVistaModelo(false);
              //HABILITANDO EL TAB MODELO
              // $('#opcion-marca').removeClass('disabledTab');

              self.HabilitarFilaModelo(data,false);
              _input_habilitado_modelo = false;
              _modo_nuevo_modelo = false;
              self.SeleccionarAnterior(data);
              vistaModeloCatalogo.vmcMarca.dataMarca.Modelos.remove(data);
            }
          });
        }, 200);


    }

    self.BorrarModelo = function(data,event) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});
      console.log(datajs);
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cModelo/BorrarModelo',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarModelo");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      self.SeleccionarSiguiente(objeto);
                      vistaModeloCatalogo.vmcMarca.dataMarca.Modelos.remove(objeto);
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

    self.OutModal = function(event){
      if(event){
        _input_habilitado_modelo = false;
        _modo_nuevo_modelo = false;
      }
    }


}
