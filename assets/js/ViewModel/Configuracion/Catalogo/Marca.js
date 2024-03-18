
MarcasModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreMarca = ko.observable(data.NombreMarca);

    self.VistaOptions = ko.pureComputed(function(){
      return self.NoEspecificado() == "S" ? "hidden" : "visible";
    }, this);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //console.log("Deshacer");
        self.NombreMarca.valueHasMutated();
        self.NombreMarca("");
        self.NombreMarca(self._NombreMarca());
        return true;
      }
    }

    self.Confirmar = function(data,event) {
        if (event) {
          //console.log("Confirmar");
          self._NombreMarca.valueHasMutated();
          self._NombreMarca(self.NombreMarca());
        }
    }
}

MarcaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

NuevaMarcaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

var Mapping1 = {
    'Marcas': {
        create: function (options) {
            if (options)
              return new MarcasModel(options.data);
            }
    }/*,
    'Modelos': {
        create: function (options) {
          if (options)
            return new ModelosModel(options.data);
            }
    }*/,
    'Marca': {
        create: function (options) {
            if (options)
              return new MarcaModel(options.data);
            }
    },
    'NuevaMarca': {
        create: function (options) {
            if (options)
              return new NuevaMarcaModel(options.data);
            }
    }/*,
    'Modelo': {
        create: function (options) {
            if (options)
              return new ModeloModel(options.data);
            }
    }*/

}

/*jQuery.isSubstring = function (haystack, needle) {
  return haystack.indexOf(needle) !== -1;

};*/

IndexMarca = function (data) {
    var _input_habilitado_marca = false;
    var _modo_nuevo_marca = false;
    var _codigo_evento_previo = 0;
    var self = this;
    var _marca=null;
    var ModelsModelo = new IndexModelo(data);

    ko.mapping.fromJS(data, Mapping1, self);
    //self.Errores = ko.validation.group(self, { deep: true });
    self.EstaHabilitadaVistaMarca = function () {
      console.log("EstaHabilitadaVistaMarca");
      if ( $("#brand_marca").find(".opt_marca").prop("disabled") == true)
        return false;
      else
        return true;
    }




    self.HabilitarFilaMarca =function (data, option) {

      var idspan ="#"+data.IdMarca()+"_span_NombreMarca";
      var idinput ="#"+data.IdMarca()+"_input_NombreMarca";
      var idbuttonguardar ="#"+data.IdMarca()+"_button_Marca";

      if (option == false)
      {
        $(idspan).show();
        $(idinput).hide();
        $(idbuttonguardar).hide();
        $("#brand_marca").find(".btn_modelo").prop("disabled",false);
        $("#btnAgregarMarca").prop("disabled",false);
        $("#btnAgregarModelo").prop("disabled",false);
      }
      else {
        $(idspan).hide();
        $(idinput).show();
        $(idbuttonguardar).show();
        $(idinput).focus();
        $("#brand_marca").find(".btn_modelo").prop("disabled",true);
        $("#btnAgregarMarca").prop("disabled",true);
        $("#btnAgregarModelo").prop("disabled",true);
      }

    }

    self.HabilitarOpcionesVistaMarca = function(option) {
      //console.log("btn_modelo");
      if (option == true)
        $("#brand_marca").find('.opt_marca').prop('disabled',true);
      else
        $("#brand_marca").find('.opt_marca').prop("disabled",false);
    }

    self.ListarMarcas = function() {
        //console.log("ListarMarcas");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cMarca/ListarMarcas',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        vistaModeloCatalogo.vmcMarca.dataMarca.Marcas([]);
                        ko.utils.arrayForEach(data, function (item) {
                            vistaModeloCatalogo.vmcMarca.dataMarca.Marcas.push(new MarcasModel(item));
                    });
                }
            }
        });
    }

    self.HabilitarTablaSpanMarca = function (data, option)  {
      //var id = "#"+data.IdTipoExistencia();
      var id = ".DataTables_Table_marca";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanMarca').hide();
        $(id).find('.class_InputMarca').show();
      }
      else {
        $(id).find('.class_SpanMarca').show();
        $(id).find('.class_InputMarca').hide();
        $(id).find('.guardar_button_Marca').hide();
        //_input_habilitado_marca = false;
      }

    }

    self.FilaButtonsMarca = function (data, event)  {
      console.log("FilaButtonsMarca");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else
      {
          console.log("MODO NUEVO: " + _modo_nuevo_marca);
          if(_modo_nuevo_marca == true)
          return;

          self.RefrescarMarcas(data, event);
      }

    }

    self.RefrescarMarcas = function(data, event){
      _marca.Deshacer(null, event);
      _input_habilitado_marca = false;
      $("#btnAgregarMarca").prop("disabled",false);
      $("#btnAgregarModelo").prop("disabled",false);
      // $('#opcion-modelo').removeClass('disabledTab');
      $("#brand_marca").find(".btn_modelo").prop("disabled",false);
      self.HabilitarTablaSpanMarca(null,  true);
    }

    self.SeleccionarNormal = function(data){
      if(data.IdMarca() == 0 && _modo_nuevo_marca == false){
        self.Seleccionar(data);
      }
      else {
        self.Seleccionar(data);
      }
    }

    self.SeleccionarAnterior = function (data)  {

      var id = "#"+data.IdMarca()+'_tr_marca';
      var anteriorObjeto = $(id).prev();

      anteriorObjeto.addClass('active').siblings().removeClass('active');

      //if (_modo_nuevo_marca == false)
      //{
        var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcMarca.dataMarca.Marcas(), function(item) {
              return anteriorObjeto.attr("name") == item.IdMarca();
          });

          vistaModeloCatalogo.vmcMarca.dataMarca.Marca= match;

        if(match)
        {
          //$("#TituloNombreMarca").text(data.NombreMarca());
          vistaModeloCatalogo.vmcMarca.dataMarca.Marca= match;
          self.PrimerModelo(vistaModeloCatalogo.vmcMarca.dataMarca.Marca);
          $("#TituloNombreMarca").text(vistaModeloCatalogo.vmcMarca.dataMarca.Marca.NombreMarca());
        }
      //}
    }

    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdMarca()+'_tr_marca';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_marca == false) //revisar
        {
          var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcMarca.dataMarca.Marcas(), function(item) {
                return siguienteObjeto.attr("name") == item.IdMarca();
            });

          if(match)
          {
            vistaModeloCatalogo.vmcMarca.dataMarca.Marca= match;
            self.PrimerModelo(vistaModeloCatalogo.vmcMarca.dataMarca.Marca);
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }

    self.HabilitarButtonAgregarMarca = function(data, event){
      if(_modo_nuevo_marca == true || _input_habilitado_marca == true)
      return;
      $("#btnAgregarMarca").prop("disabled",false);
    }

    self.DeshabilitarButtonAgregarMarca = function(data, event){
      if(_modo_nuevo_marca == true || _input_habilitado_marca == true)
      return;
      $("#btnAgregarMarca").prop("disabled",true);
    }

    self.Seleccionar = function (data,event)  {
      //console.log("Seleccionar");
      if (self.EstaHabilitadaVistaMarca() == false)
        return false;


      if (_modo_nuevo_marca == false)
      {
        //SETEANDO FILA SELECCIONADA
        // vistaModeloCatalogo.vmcMarca.dataMarca = data;
        _marca = data;
        vistaModeloCatalogo.vmcMarca.dataMarca.Marca = data;

        var id = "#"+data.IdMarca()+'_tr_marca';
        $(id).addClass('active').siblings().removeClass('active');

        $("#TituloNombreMarca").text(data.NombreMarca());

        //if (vistaModeloCatalogo.vmcMarca.dataMarca.Marca.IdMarca() != data.IdMarca())
        // self.PrimerModelo(data);

        /*if (_codigo_evento_previo > 0 )
        {
          vistaModeloCatalogo.vmcMarca.dataMarca.Marca = data;
          console.log("PASO POR AQUI: " +  vistaModeloCatalogo.vmcMarca.dataMarca.Marca.IdMarca());
          _codigo_evento_previo = 0;
        }*/
        console.log("FUERA DE CONSOLA: " + _marca.IdMarca());
      }

    }

    self.AgregarMarca = function(data,event) {
          //console.log("AgregarMarca");

          if ( _input_habilitado_marca == true )
          {
          }
          else
          {
            var objeto = Knockout.CopiarObjeto(vistaModeloCatalogo.vmcMarca.dataMarca.NuevaMarca);
            vistaModeloCatalogo.vmcMarca.dataMarca.Marca  = new MarcasModel(objeto);
            vistaModeloCatalogo.vmcMarca.dataMarca.Marcas.push(vistaModeloCatalogo.vmcMarca.dataMarca.Marca);

            /*DESHABILITAMOS EL ACCESO AL TAB DE MODELO*/
            // $('#opcion-modelo').removeClass('active').addClass("disabledTab");
            _marca= vistaModeloCatalogo.vmcMarca.dataMarca.Marca;
            /*HABILITAMOS LO INPUT DE LA FILA NUEVA PARA AGREGAR EL REGISTRO*/
            self.HabilitarFilaMarca(objeto , true);

            /*OCULTAMOS EL TEXTO DEL ID EN LA FILA NUEVA*/
            $("#-1_tr_marca td:first").text("");

            /*DESHABILITANDO BOTONES DE CONTROL Y HABILITANDO EL EDITAR DEL NUEVO*/
            self.HabilitarOpcionesVistaMarca(true);
            var id_borrar_marca = "#-1_borrar_button_Marca";
            $(id_borrar_marca).prop("disabled", false);

            /*SELECCIONANDO ULTIMA FILA*/
            var tabla = $('.DataTables_Table_marca');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');



            _modo_nuevo_marca = true;
            _input_habilitado_marca = true;
          }
    }

    self.InsertarMarca =function(data,event){

      if(event)
      {

        //data.IdMarca("");
        $("#loader").show();
        console.log("InsertarMarca");
        var datajs = ko.toJS({"Data" : data});
        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cMarca/InsertarMarca',
                success: function (data) {
                    if (data != null) {
                      if ($.isNumeric(data.IdMarca))
                      {
                        vistaModeloCatalogo.vmcMarca.dataMarca.Marca.IdMarca(data.IdMarca);
                        vistaModeloCatalogo.vmcMarca.dataMarca.Marca.Confirmar(null,event);
                        self.HabilitarFilaMarca(vistaModeloCatalogo.vmcMarca.dataMarca.Marca, false);
                        existecambio = false;
                        _input_habilitado_marca = false;
                        _modo_nuevo_marca = false;

                        self.HabilitarOpcionesVistaMarca(false);

                        //vistaModeloCatalogo.vmcMarca.dataMarca.Marca = data;
                        self.PrimerModelo(vistaModeloCatalogo.vmcMarca.dataMarca.Marca);
                        //HABILITANDO EL TAB MODELO
                        // $('#opcion-modelo').removeClass('disabledTab');

                        $("#TituloNombreMarca").text(vistaModeloCatalogo.vmcMarca.dataMarca.Marca.NombreMarca());
                      }
                      else {
                        alertify.alert(data.IdMarca);
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

    self.ActualizarMarca = function(data,event) {
          console.log("ActualizarMarca");
          var datajs = ko.toJS({"Data" : data});
          $("#loader").show();

          console.log("InsertarMarca");
          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/Catalogo/cMarca/ActualizarMarca',
                  success: function (data) {
                      if (data != null) {

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          _marca.Confirmar(null,event);
                          self.HabilitarFilaMarca(_marca,false);
                          $("#TituloNombreMarca").text(vistaModeloCatalogo.vmcMarca.dataMarca.Marca.NombreMarca());
                          // $('#opcion-modelo').removeClass('disabledTab');
                          existecambio = false;
                          _input_habilitado_marca = false;
                          _modo_nuevo_marca = false;
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

    self.EditarMarca = function(data, event) {

      if(event)
      {
          console.log("EditarMarca");
          _codigo_evento_previo=2;

          if( _modo_nuevo_marca == true )
          {

          }
          else
          {
            // $('#opcion-modelo').removeClass('active').addClass("disabledTab");

            if (self.EstaHabilitadaVistaMarca() == false)
              return false;

            if (vistaModeloCatalogo.vmcMarca.dataMarca.Marca.IdMarca() == data.IdMarca())
            {
              if (_input_habilitado_marca == true)
              {
                //deshabilitar origen
                // $('#opcion-modelo').removeClass('disabledTab');
                _marca.Deshacer(null,event);
                self.HabilitarFilaMarca(data,false);
                _input_habilitado_marca =false;
              }
              else
              {
                //habilitar origen
               self.HabilitarFilaMarca(data,true);
               _input_habilitado_marca = true;
              }
           }
           else
           {

              if (_input_habilitado_marca == true)
              {
                //deshabilitar origen
                _marca.Deshacer(null,event);
                self.HabilitarFilaMarca(_marca,false);
              }

              //habilitar destino
              self.HabilitarFilaMarca(data,true);
              _input_habilitado_marca = true;
           }

          }
      }
    }


    self.PreBorrarMarca = function (data) {

        if(_modo_nuevo_marca == false)
        {
          self.RefrescarMarcas(data, event);
        }

        setTimeout(function(){
          alertify.confirm("¿Desea borrar el registro?", function(){
            console.log("PreBorrarMarca");

            if (data.IdMarca() != null && _modo_nuevo_marca == false)
            {
              self.BorrarMarca(data);
            }
            else
            {
              self.HabilitarOpcionesVistaMarca(false);

              //self.HabilitarFilaMarca(data,false);
              self.SeleccionarAnterior(data);
              vistaModeloCatalogo.vmcMarca.dataMarca.Marcas.remove(data);

              //HABILITANDO EL TAB MODELO
              // $('#opcion-modelo').removeClass('disabledTab');
              /*HABILITANDO CARPETAS Y SUBCARPETAS*/
              $("#brand_marca").find(".btn_modelo").prop("disabled",false);
              $("#btnAgregarMarca").prop("disabled",false);
              $("#btnAgregarModelo").prop("disabled",false);

              _input_habilitado_marca = false;
              _modo_nuevo_marca = false;
              /*var tabla = $('.DataTables_Table_marca');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');*/
            }
          });
        }, 200);
    }

    self.BorrarMarca = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cMarca/BorrarMarca',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarMarca");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      self.SeleccionarSiguiente(objeto);
                      vistaModeloCatalogo.vmcMarca.dataMarca.Marcas.remove(objeto);

                      // $('#opcion-modelo').removeClass('disabledTab');

                      self.HabilitarTablaSpanMarca(null,  true);

                      $("#brand_marca").find(".btn_modelo").prop("disabled",false);

                      $("#btnAgregarMarca").prop("disabled",false);
                      $("#btnAgregarModelo").prop("disabled",false);

                      //self.HabilitarOpcionesVistaMarca(false);
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

    self.OnClickNombreMarca = function(data ,event) {

      if(event)
      {
        if(data.NoEspecificado() == "S" && _modo_nuevo_marca == false)
        {
          if(_input_habilitado_marca==false)
            return;

          _marca.Deshacer(null, event);
          _input_habilitado_marca = false;
          $("#btnAgregarMarca").prop("disabled",false);
          $("#btnAgregarModelo").prop("disabled",false);
          $(".btn_modelo").prop("disabled",false);

          self.HabilitarTablaSpanMarca(null, true);
          // $('#opcion-modelo').removeClass('disabledTab');


        }
        else {
          _codigo_evento_previo=1;
          //console.log("OnClickMarca");
          if( _modo_nuevo_marca == true )
          {

          }
          else
          {
            // $('#opcion-modelo').removeClass('active').addClass('disabledTab');
            if (self.EstaHabilitadaVistaMarca() == false)
              return false;

            if(vistaModeloCatalogo.vmcMarca.dataMarca.Marca.IdMarca() !=  data.IdMarca())
            {
              if (_input_habilitado_marca == true)
              {
                //deshabilitar campo origen
                _marca.Deshacer(null,event);
                self.HabilitarFilaMarca(_marca,false);
              }

              //habilitar como destino
              self.HabilitarFilaMarca(data,true);
              _input_habilitado_marca = true;
            }
            else
            {
              if (_input_habilitado_marca == false)
              {
                self.HabilitarFilaMarca(data,true);
                _input_habilitado_marca = true;
              }
              else
              {
                //
              }
            }

          }

          return false;
        }
      }
    }

    self.OnKeyUpNombreMarca = function(data, event){

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

         if(_modo_nuevo_marca == true)
         {
           self.InsertarMarca(data,event);
         }
         else
         {
           self.ActualizarMarca(data,event);
         }
       }

       return true;
      }
    }

    self.EscaparGlobal = function(event)
    {
      if(event)
      {
        if(_input_habilitado_marca == true)
        {
          if(_modo_nuevo_marca == true)
          {
            var nuevo_objeto = ko.mapping.fromJS(ko.toJS(_marca));
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.HabilitarOpcionesVistaMarca(false);
              self.HabilitarFilaMarca(nuevo_objeto,false);
              _modo_nuevo_marca = false;
              _input_habilitado_marca = false;
              self.SeleccionarAnterior(nuevo_objeto);
              vistaModeloCatalogo.vmcMarca.dataMarca.Marcas.remove(_marca);
            });
          }
          else
          {
            console.log("escape - modo edicion");
            //deshabilitar botones
            _marca.Deshacer(null,event);
            self.HabilitarFilaMarca(_marca,false);
            existecambio=false;
            _modo_nuevo_marca = false;
            _input_habilitado_marca = false;
          }
          // $('#opcion-modelo').removeClass('disabledTab');

        }
      }
    }

    self.GuardarMarca = function(data,event) {
      if(event)
      {
         console.log("GuardarMarca");

         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }

         if(_modo_nuevo_marca == true)
         {
           self.InsertarMarca(data,event);
         }
         else
         {
           self.ActualizarMarca(data,event);
         }
      }
    }

    self.PrimerModelo = function(data) {
        console.log("PrimerModelo");
        if(_modo_nuevo_marca)
        {

        }
        else {

          if(data.IdMarca() != null)
          {

            var datajs = ko.toJS({"Data":data});

            $.ajax({
                    type: 'POST',
                    data : datajs,
                    dataType: "json",
                    url: SITE_URL+'/Configuracion/Catalogo/cModelo/ConsultarModelo',
                    success: function (data) {
                        if (data != null) {
                            console.log(data);
                            vistaModeloCatalogo.vmcMarca.dataMarca.Modelos([]);
                            ko.utils.arrayForEach(data, function (item) {
                            vistaModeloCatalogo.vmcMarca.dataMarca.Modelos.push(new ModelosModel(item));
                        });
                        $('#tabla-modelo tbody tr:first').addClass('active');
                    }
                }
            });
          }

        }
    }

    self.ConsultarModelo = function(data) {
        //console.log("ConsultarModelo");

        if(_modo_nuevo_marca)
        {

        }
        else {
          if(data.IdMarca() != null)
          {
            var datajs = ko.toJS({"Data":data});

            $.ajax({
                    type: 'POST',
                    data : datajs,
                    dataType: "json",
                    url: SITE_URL+'/Configuracion/Catalogo/cModelo/ConsultarModelo',
                    success: function (data) {
                        if (data != null) {
                            console.log(data);
                            vistaModeloCatalogo.vmcMarca.dataMarca.Modelos([]);
                            ko.utils.arrayForEach(data, function (item) {
                            vistaModeloCatalogo.vmcMarca.dataMarca.Modelos.push(new ModelosModel(item));
                        });

                        $('#tabla-modelo tbody tr:first').addClass('active');
                        $("#modalModelo").modal("show");
                        $("#btnAgregarModelo").prop("disabled",false);

                        // $("#opcion-modelo").addClass('active').siblings().removeClass('active');
                        // $("#modelo").addClass('active').siblings().removeClass('active');
                        // $("#btnAgregarMarca").prop("disabled",true);

                    }
                }
            });
          }
        }

    }

    //Funcionalidad para invocar a Modelo
    self.SeleccionarNormalModelo = function (data,event) {
      ModelsModelo.SeleccionarNormalModelo(data,event);
    }

    self.AgregarModelo = function(data,event) {
      ModelsModelo.AgregarModelo(data,event);
    }

    self.EscaparGlobalModelo = function(event) {
      ModelsModelo.EscaparGlobalModelo(event);
    }

    self.OnClickNombreModelo = function (data,event) {
      ModelsModelo.OnClickNombreModelo(data,event);
    }

    self.EditarModelo = function (data,event) {
      ModelsModelo.EditarModelo(data,event);
    }

    self.OnKeyUpNombreModelo = function (data,event) {
      ModelsModelo.OnKeyUpNombreModelo(data,event);
    }

    self.GuardarModelo = function (data,event) {
      ModelsModelo.GuardarModelo(data,event);
    }

    self.PreBorrarModelo = function (data,event) {
      ModelsModelo.PreBorrarModelo(data,event);
    }

    self.FilaButtonsModelo = function (data,event) {
      ModelsModelo.FilaButtonsModelo(data,event);
    }

    self.OutModal = function (data,event) {
      ModelsModelo.OutModal(data,event);
    }

}
