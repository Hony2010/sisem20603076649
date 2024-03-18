var MappingEmpleado = {
  'Empleados': {
      create: function (options) {
          if (options)
            return new EmpleadosModel(options.data);
          }
  },
  'Empleado': {
      create: function (options) {
          console.log('Empleado');
          console.log(options);
          if (options)
            return new EmpleadoModel(options.data);
          }
  },
  'NuevoEmpleado': {
      create: function (options) {
          if (options)
            return new EmpleadoModel(options.data);
          }
  }
}


EmpleadosModel = function (data) {
  var self = this;

  ko.mapping.fromJS(data, {}, self);

  self.PintadoBoton = ko.pureComputed(function() {
      return self.IndicadorEstado() == "A" ? "btn-baja"  : "btn-primary";
  }, this);

  self.Icono = ko.pureComputed(function() {
      return self.IndicadorEstado() == "A" ? "glyphicon-minus" : "glyphicon-refresh";
  }, this);

  self.mensajeTitle = ko.pureComputed(function() {
      return self.IndicadorEstado() == "A" ? "Dar baja empleado" : "Reactivar Empleado";
  }, this);

  self.NombreEstado = ko.computed(function(){
    try {
      if(self.IndicadorEstado() == "A"){
        return "ACTIVO";
      }
      else if(self.IndicadorEstado() == "I"){
        return "BAJA";
      }
    }
    catch(err) {
      var error = err.message;
      console.log("ADVERTENCIA: " + error);
      if(error.indexOf("self.IndicadorEstado") >= 0){
        return false;
      }
    }

  }, this);

  self.CargarFoto = function(data, event) {
    if(event)
    {
      if(data!= "")
      {
        var src = "";
        console.log(data.IdEmpleado());

        //console.log("ID PRODUCTO: " + data.IdPersona() + "  ., FOTO NOMBRE: " + data.Foto())
        if (data.IdPersona()=="" || data.IdPersona() == null || data.Foto() == null || data.Foto() == "")
        src=BASE_URL + CARPETA_IMAGENES + "nocover.png";
        else
        src=SERVER_URL + CARPETA_IMAGENES + CARPETA_EMPLEADO+data.IdPersona()+SEPARADOR_CARPETA+data.Foto();

        return src;
      }
    }
  }

  self.Seleccionar = function (data,event)  {
    if(event) {
      try {
        console.log("Seleccionar");
        var id = "#"+ data.IdEmpleado();
        $(id).addClass('active').siblings().removeClass('active');
        //debugger;
        _objeto_empleado = new EmpleadosModel(Knockout.CopiarObjeto(data));
        var objeto = Knockout.CopiarObjeto(data);
        //objeto = new EmpleadoModel(objeto);
        ko.mapping.fromJS(objeto, {}, Models.data.Empleado);
        //var objeto = new EmpleadosModel(ko.mapping.toJS(data));
        //ko.mapping.fromJS(data, {}, Models.data.Empleado);

        $("#img_FileFoto").attr("src",self.CargarFoto(data, event));//OJO AQUI
        $("#img_FileFotoPreview").attr("src",self.CargarFoto(data, event));
      }
      catch(err) {
          var error = err.message;
          console.log("ADVERTENCIA: " + error);
          if(error.indexOf("data.IdEmpleado") >= 0){
            return false;
        }
      }
    }
  }

  self.SeleccionarAnterior = function (data, event)  {
    if(event)
    {
      var id = "#"+data.IdEmpleado();
      var anteriorObjeto = $(id).prev();

      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_empleado == false)
      {
        var match = ko.utils.arrayFirst(Models.data.Empleados(), function(item) {
          return anteriorObjeto.attr("id") == item.IdEmpleado();
        });

        $("#img_FileFoto").attr("src",self.CargarFoto(match, event));//OJO AQUI
        $("#img_FileFotoPreview").attr("src",self.CargarFoto(match, event));
      }
    }
  }

  self.SeleccionarSiguiente = function (data, event)  {
    if(event)
    {
      var id = "#"+data.IdEmpleado();
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_empleado == false) //revisar
        {
          var match = ko.utils.arrayFirst(Models.data.Empleados(), function(item) {
                return siguienteObjeto.attr("id") == item.IdEmpleado();
            });


          $("#img_FileFoto").attr("src",self.CargarFoto(match, event));//OJO AQUI
          $("#img_FileFotoPreview").attr("src",self.CargarFoto(match, event));
        }
      }
      else {
        self.SeleccionarAnterior(data, event);
      }
    }

  }

  self.Editar  = function(data, event) {
    //
    if(event) {
      $('#btn-busqueda').hide();
      _opcion_guardar_empleado = 1;
      if( _modo_nuevo_empleado == true ) {

      }
      else {
        //$("#btn_LimpiarEmpleado").val("Deshacer");
        $('#btn_LimpiarEmpleado').text("Deshacer");
        // Models.data.Empleado.ChangeBtnBusqueda(data);
        Models.data.Empleado.ChangeTipoPersona(data,event);
        Models.data.Empleado.ChangeRazonSocial(data,event);
        $("#modalEmpleado").modal("show");
        $("#IdEmpleado").focus();
      }
      _modo_nuevo_empleado = false;

      Models.data.Empleado.EstaProcesado(false);
      Models.data.Empleado.opcionProceso(opcionProceso.Edicion);
    }

  }

  self.PreEliminar = function (data, event) {
    if(event)
    {
      self.MostrarTitulo("ELIMINACION DE EMPLEADO");

      setTimeout(function(){
        alertify.confirm(self.MostrarTitulo(),"¿Desea borrar realmente?", function(){
          if (data.IdEmpleado() != null)
          self.Eliminar(data, event);
        },function() {

        });
      }, 100);

    }

  }

  self.Eliminar = function (data, event) {
    if(event)
    {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Catalogo/cEmpleado/DarBajaEmpleado',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarEmpleado");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      self.SeleccionarSiguiente(objeto, event);
                      Models.data.Empleados.remove(objeto);

                      var filas = Models.data.Empleados().length;
                      Models.data.Filtros.totalfilas(data.Filtros.totalfilas);
                      if(filas == 0)
                      {
                        $("#Paginador").paginador(data.Filtros,Models.ConsultarPorPagina);

                        var ultimo = $("#Paginador ul li:last").prev();
                        ultimo.children("a").click();
                      }
                    }
                  }
            },
            error : function (jqXHR, textStatus, errorThrown) {
                   console.log(jqXHR.responseText);
               }
      });

    }

  }

  self.PreDarBaja = function (data, event) {
    if(event)
    {
      if(data.IndicadorEstado() == "A")
      {
        var titulo = "BAJA DE EMPLEADO";
        //setTimeout(function(){
          alertify.confirm(titulo,"¿Desea dar de baja realmente?", function(){
            console.log("PreDarBajaEmpleado");

            if (data.IdEmpleado() != null)
            self.DarBaja(data, event);

          },function(){});
        //}, 100);

      } else {
        //setTimeout(function(){
        var titulo = "REACTIVACION DE EMPLEADO";
          alertify.confirm(titulo,"¿Desea reactivar realmente?", function(){
            console.log("ReactivarEmpleado");

            if (data.IdEmpleado() != null)
            self.ReactivarEmpleado(data, event);

          },function(){});
        //}, 100);
      }
    }

  }

  self.DarBaja = function (data, event) {
    if(event)
    {
      var _objeto = data;
      var datajs = ko.mapping.toJS({"Data" : data}, mappingIgnore)

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Catalogo/cEmpleado/DarBajaEmpleado',
              success: function (data) {
                  if (data != null) {
                    console.log("DarBajaEmpleado");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert("ERROR EN DAR BAJA EMPLEADO",data);
                    }
                    else {

                      console.log("Dar de baja");
                      _objeto.IndicadorEstado("I");
                      ko.mapping.fromJS(_objeto, MappingEmpleado, Models.data.Empleado);
                      // var fila_baja = Models.data.Empleado.IdEmpleado() + '_btnBaja';

                      var fila_objeto =ko.utils.arrayFirst(Models.data.Empleados(), function(item) {
                          return Models.data.Empleado.IdEmpleado() == item.IdEmpleado();
                      });

                      var objeto = Knockout.CopiarObjeto(Models.data.Empleado);
                      objeto = new EmpleadosModel(objeto);
                      Models.data.Empleados.replace(fila_objeto,objeto);

                      objeto.Seleccionar(Models.data.Empleado, event);

                      //var id_baja = '#'+ self.IdEmpleado() + '_btnBaja';
                      //$(id_baja).addClass("btn-primary");
                      //$(id_baja).removeClass("btn-secondary");

                      //$(id_baja).toggleClass("btn-secondary btn-primary");
                      //self.SeleccionarSiguiente(objeto, event);
                      //Models.data.Empleados.remove(objeto);
                    }
                  }
            },
            error : function (jqXHR, textStatus, errorThrown) {
                   console.log(jqXHR.responseText);
               }
      });

    }

  }

  self.ReactivarEmpleado = function (data, event) {
    if(event)
    {
      var _objeto = data;
      var datajs = ko.mapping.toJS({"Data" : data}, mappingIgnore)

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Catalogo/cEmpleado/ReactivarEmpleado',
              success: function (data) {
                  if (data != null) {
                    console.log("ReactivarEmpleado");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert("ERROR EN REACTIVAR EMPLEADO",data);
                    }
                    else {
                      console.log("Dar de baja");
                      _objeto.IndicadorEstado("A");
                      ko.mapping.fromJS(_objeto, MappingEmpleado, Models.data.Empleado);

                      var fila_objeto =ko.utils.arrayFirst(Models.data.Empleados(), function(item) {
                          return Models.data.Empleado.IdEmpleado() == item.IdEmpleado();
                      });

                      var objeto = Knockout.CopiarObjeto(Models.data.Empleado);
                      objeto = new EmpleadosModel(objeto);
                      Models.data.Empleados.replace(fila_objeto,objeto);

                      objeto.Seleccionar(Models.data.Empleado, event);
                      //var id_baja = '#'+ self.IdEmpleado() + '_btnBaja';
                      //$(id_baja).toggleClass("btn-primary btn-secondary");
                      //$(id_baja).addClass("btn-secondary");
                      //$(id_baja).removeClass("btn-primary");
                      //self.SeleccionarSiguiente(objeto, event);
                      //Models.data.Empleados.remove(objeto);
                    }
                  }
            },
            error : function (jqXHR, textStatus, errorThrown) {
                   console.log(jqXHR.responseText);
               }
      });

    }

  }

}



EmpleadoModel = function (data) {
  var self = this;

  ko.mapping.fromJS(data, {} , self);

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showEmpleado = ko.observable(false);

  AccesoKey.AgregarKeyOption("#formEmpleado", "#btn_GrabarEmpleado", TECLA_G);
  self.InicializarVistaModelo =function(data,event) {
    if(event) {
      setTimeout(function(){
        $('#combo-tipodocumentoIdentidad').focus();
      }, 850);

      self.InicializarValidator(event);
    }
  }

  self.InicializarValidator = function(event) {
    if(event) {
      $.formUtils.addValidator({
        name : 'validacion_numero_documento',
        validatorFunction : function(value, $el, config, language, $form) {
          return self.ValidarNumeroDocumentoIdentidad(value,event);
        }
      });
    }
  }

  self.CambiarEstadoEmpleado = ko.computed(function () {
    var estado = self.IndicadorEstadoEmpleado() == true ? '1' : '0';
    self.EstadoEmpleado(estado);
  }, this)

  self.OnFocus = function(data,event,callback) {
    if(event)  {
        $(event.target).select();
        if(callback) callback(data,event);
    }
  }

  self.OnKeyEnter = function(data,event) {
    if(event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }

  self.ValidarNumeroDocumentoIdentidad = function(value,event) {
    if(event) {
      var idtipo = self.IdTipoDocumentoIdentidad();

      if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI) { //dni
        if($.isNumeric(value) === true && value.length === MAXIMO_DIGITOS_DNI)
          return true;
        else
          return false;
      }
      else if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_RUC) {//ruc
        if ($.isNumeric(value) === true && value.length === MAXIMO_DIGITOS_RUC)
          return true;
        else
          return false;
      }
      else {
        if(value !=="") {
          return true;
        }
        else {
          return false;
        }
      }
    }
  }

  self.OnChangeInputFile = function(data,event) {
    if(event) {
      $("#FileFoto").readAsImage(event,function($data){
        if($data) {
          $('#img_FileFoto').attr('src', $data.source);
          $('#foto_previa').attr('src', $data.source);
          data.Foto($data.filename);
        }
      });
    }
  }

  self.AbrirPreview = function(data,event){
    if(event)
    {
      var img = event.target;
      var dataURL = img.src;

      if(dataURL != '')
      {
        $("#foto_previa").attr('src',dataURL);
        $("#modalPreview").modal("show");
      }
    }
  }

      /*EMPLEADO - EMPLEADO - EMPLEADO*/
  self.AgregarEventoExterno = function(data, event){
    if(event)
    {
      self.EventoExterno = data;
    }
  }

  self.CargarEmpleado = function(data, event) {
    if(event)
    {
      var datajs = ko.toJS({"Data": data.IdEmpleado});

      $.ajax({
        method: 'POST',
        data : datajs,
        dataType: 'json',
        url: SITE_URL+'/Catalogo/cEmpleado/ListarEmpleadosPorId',
        success: function(data) {
          if(data != null)
          {
            //self.EventoExterno(data[0], event);
            Models.data.Usuario.CargarEmpleado(data[0], event);
          }
          else
          {
            alertify.alert("Seleccione un Empleado");
          }
        }
      });
    }
  }

  self.SubirFoto = function(data, event) {
    if(event)
    {
      var modal = document.getElementById("formEmpleado");      
      var input = new FormData(modal);

      $.ajax({
          type: 'POST',
          data : input,
          contentType: false,       // The content type used when sending data to the server.
          cache: false,             // To unable request pages to be cached
          processData: false,        // To send DOMDocument or non processed data file it is set to false
          mimeType: "multipart/form-data",
          url: SITE_URL+'/Catalogo/cEmpleado/SubirFoto',
          success: function ($data) {
            //ACTUALIZANDO LA FILA EN MERADERIAS
            Models.data.Empleado.EstaProcesado(true);
            if(_opcion_guardar_empleado!= 0 ){
              var fila_objeto =ko.utils.arrayFirst(Models.data.Empleados(), function(item) {
                return Models.data.Empleado.IdEmpleado() == item.IdEmpleado();
              });
              var objeto = Knockout.CopiarObjeto(Models.data.Empleado);
              objeto = new EmpleadosModel(objeto);
              Models.data.Empleados.replace(fila_objeto,objeto);

              objeto.Seleccionar(Models.data.Empleado, event);

              $("#modalEmpleado").modal("hide");
            }
            else {
              alertify.confirm(self.MostrarTitulo(),"¿Desea seguir agregando nuevos registros?", function(){
                ko.mapping.fromJS(Models.data.NuevoEmpleado, {}, Models.data.Empleado);
                document.getElementById("formEmpleado").reset();
          
                $('#btn_LimpiarUsuario').text("Limpiar");
                $('#combo-tipopersona').val(2);
                $('#combo-tipopersona').prop("disabled", true);
          
                self.LimpiarImagen(event);
                setTimeout(function(){
                  $("#NumeroDocumentoIdentidad").focus();
                }, 500);

              },function(){
                _modo_nuevo_empleado == false;
                $("#modalEmpleado").modal("hide");

                // var objeto_resultado = Knockout.CopiarObjeto(Models.data.Empleado.resultado);
                //var objeto_filtro = ko.mapping.toJS(Models.data.Empleado.Filtros);
                var objeto_filtro = ko.mapping.toJS(data.Filtros);

                var filas = Models.data.Empleados().length;
                Models.data.Filtros.totalfilas(objeto_filtro.totalfilas);
                
                if(filas >= 10) {
                  $("#Paginador").paginador(objeto_filtro,Models.ConsultarPorPagina);
                  var ultimo = $("#Paginador ul li:last").prev();
                  ultimo.children("a").click();
                }
                else {
                  copia_objeto = new EmpleadosModel(Models.data.Empleado);
                  Models.data.Empleados.push(new EmpleadosModel(Models.data.Empleado));
                  copia_objeto.Seleccionar(Models.data.Empleado, event);
                }
              });
            }
          }
      });
    }
  }

  self.DatosDelCombo = function(event){
    if(event)
    {
      var nombretipodocumentoindentidad = $("#combo-tipodocumentoIdentidad option:selected").text();
      Models.data.Empleado.NombreAbreviado(nombretipodocumentoindentidad);

      var nombrerol = $("#combo-rol option:selected").text();
      Models.data.Empleado.NombreRol(nombrerol);

      var Nombresede = $("#combo-sede option:selected").text();
      Models.data.Empleado.NombreSede(Nombresede);
    }
  }

   self.Guardar =function(data,event){
     if(event)
     {
       if (parseFloatAvanzado(data.Sueldo()) < 0 ) {
        alertify.alert(self.MostrarTitulo(), 'El sueldo debe ser mayor o igual a "0"', function () { });
        return false;
       }
       $("#loader").show();
       var accion = "";
       if(_opcion_guardar_empleado != 0)
       {
         accion = "ActualizarEmpleado";
       }
       else
       {
         accion = "InsertarEmpleado";
       }
       Models.data.Empleado.RazonSocial($("#RazonSocial").val());
       var _data = data;

       var datajs = ko.mapping.toJS({"Data" : Models.data.Empleado}, mappingIgnore);
       console.log(datajs);
       console.log(data);
       $.ajax({
         type: 'POST',
         data : datajs,
         dataType: "json",
         url: SITE_URL+'/Catalogo/cEmpleado/' + accion,
         success: function (data) {
             console.log(data);
             if(_opcion_guardar_empleado != 0){
               if(data == "")
               {
                 ko.mapping.fromJS(_data, MappingEmpleado, Models.data.Empleado);
                 self.DatosDelCombo(event);

                 self.SubirFoto(data, event);
               }
               else {
                 alertify.alert("ERROR EN "+self.MostrarTitulo(),data);
               }
             }
             else {
               if(data.resultado) {
                 var objeto = Knockout.CopiarObjeto(data.resultado);
                 //objeto = new EmpleadoModel(data.resultado);
                 ko.mapping.fromJS(objeto, MappingEmpleado, Models.data.Empleado);

                 self.DatosDelCombo(event);

                 self.SubirFoto(data, event);
               }
                else
                {
                  alertify.alert("ERROR EN "+self.MostrarTitulo(),data.error.msg);
                  $("#NumeroDocumentoIdentidad").focus();
                }
             }
             $("#loader").hide();

         }
       });
     }

   }

   self.ChangeRazonSocial = function(data,event){
     if(event)
     {
       var IdTipoPersona = data.IdTipoPersona();
       if((IdTipoPersona == 2) || (IdTipoPersona == 3)) //Persona Judirica || No Domiciliado
       {
         var NombreCompleto = $("#NombreCompleto").val();
         var ApellidoCompleto = $("#ApellidoCompleto").val();
         var RazonSocial = ApellidoCompleto+' '+NombreCompleto;
         $("#RazonSocial").val(RazonSocial);
       }
     }
   }

   self.ChangeTipoPersona = function(data,event){
     if(event)
     {
       var IdTipoPersona = data.IdTipoPersona();
       if(IdTipoPersona == 1) //Persona Judirica
       {
         $("#NombreCompleto").prop("disabled",true);
         $("#NombreCompleto").prop("tabIndex","-1");
         $("#NombreCompleto").addClass("no-tab");
         $("#ApellidoCompleto").prop("disabled",true);
         $("#ApellidoCompleto").prop("tabIndex","-1");
         $("#ApellidoCompleto").addClass("no-tab");
         $("#RazonSocial").prop("disabled",false);
         $("#RazonSocial").removeAttr("tabIndex");
         $("#RazonSocial").removeClass("no-tab");

       }
       else if((IdTipoPersona == 2) || (IdTipoPersona == 3) ) //Persona Natural || No Domiciliado
       {
         $("#NombreCompleto").prop("disabled",false);
         $("#NombreCompleto").removeAttr("tabIndex");
         $("#NombreCompleto").removeClass("no-tab");
         $("#ApellidoCompleto").prop("disabled",false);
         $("#ApellidoCompleto").removeAttr("tabIndex");
         $("#ApellidoCompleto").removeClass("no-tab");
         $("#RazonSocial").prop("disabled",true);
         $("#RazonSocial").prop("tabIndex","-1");
         $("#RazonSocial").addClass("no-tab");
       }
     }
   }

   self.LimpiarImagen = function(event){
     if(event){
       var src=BASE_URL + CARPETA_IMAGENES + "nocover.png";
       $('#img_FileFoto').attr('src', src);
       $('#img_FileFotoPreview').attr('src', src);
     }
   }

   self.Deshacer = function(event) {
     if(event)
     {
       if($('#btn_LimpiarEmpleado').text() == "Deshacer")
       {
         var objeto = new EmpleadosModel(_objeto_empleado);
         objeto.Seleccionar(_objeto_empleado, event);
       }
       else if($('#btn_LimpiarEmpleado').text() == "Limpiar")
       {
         ko.mapping.fromJS(Models.data.NuevoEmpleado, {}, Models.data.Empleado);
         document.getElementById("formEmpleado").reset();
         self.LimpiarImagen(event);
         $('#combo-tipopersona').val(2);
         $('#combo-tipopersona').prop("disabled", true);
         setTimeout(function(){
           $("#NumeroDocumentoIdentidad").focus();
         }, 500);
       }

     }
   }

   self.Cerrar = function(data, event) {
     if(event)
     {
       if(_modo_nuevo_empleado == true){
         _modo_nuevo_empleado = false;
       }
       $("#modalEmpleado").modal("hide");

       var objeto = new EmpleadosModel(_objeto_empleado);
       objeto.Seleccionar(_objeto_empleado, event);

     }
   }

   self.OnChangeTipoDocumentoIdentidad = function(data, event) {
     if (event) {
       var texto = $("#combo-tipodocumentoIdentidad option:selected").text();
       data.NombreAbreviado(texto);
       var idtipo = data.IdTipoDocumentoIdentidad();

       if ( idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI || idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_RUC ) {
         $('#btn-busqueda').show();
         if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI) {
           data.IdTipoPersona(2);
           $('#btn-busqueda').empty();
           $('#btn-busqueda').append('<img width="25px" height="20px" src="'+BASE_URL+'assets/js/iconos/logoRENIEC.svg" alt="" title="RENIEC">');
         }
         else {
           data.IdTipoPersona(1);
           $('#btn-busqueda').empty();
           $('#btn-busqueda').append('<img width="22px" height="20px" src="'+BASE_URL+'assets/js/iconos/logoSUNAT.svg" alt="" title="SUNAT">');
         }
       }
       else {
         $('#btn-busqueda').hide();
       }
       self.ChangeTipoPersona(data, event);
     }
   }

   self.OnClickBtnBusqueda = function(data,event) {
     if(event) {
       self.OnBuscarPersona(data,event);
     }
   }

   self.OnBuscarPersona = function(data, event,callback) {
     if (event) {
       //if(!self.procesado) {

         $("#NumeroDocumentoIdentidad").validate(function(valid, elem) {

           if(valid === true) {
             $("#loader").show();
             self.ObtenerClientePorServicioExterno(data,event,function($data,$event) {
               if($data.success == false) {
                 $("#loader").hide();
                 alertify.alert("Error en "+ self.titulo,$data.message,function(){});
               }
               else {
                 $("#loader").hide();
               }
               if(callback) callback(data,event);
             });
           }
           else {
             if(callback) callback(data,event);
           }
         });
       }
       else {
         if(callback) callback(data,event);
       }
     //}
   }

   // self.ConsultarReniec = function(data, event,callback) {
   //   if (event) {
   //     var datajs = ko.toJS({"Data":{'NumeroDocumentoIdentidad':data.NumeroDocumentoIdentidad()}});
   //     $.ajax({
   //       type: 'POST',
   //       data : datajs,
   //       dataType: "json",
   //       url: SITE_URL+'/Catalogo/cCliente/ConsultarReniec',
   //       success: function (data) {
   //         callback(data,event);
   //       },
   //       error : function (jqXHR, textStatus, errorThrown) {
   //         var data = {error:{msg:jqXHR.responseText}};
   //         callback(data,event);
   //       }
   //     });
   //   }
   // }
   //
   // self.ConsultarSunat = function(data, event, callback) {
   //   if (event) {
   //     var datajs = ko.toJS({"Data":{'NumeroDocumentoIdentidad':data.NumeroDocumentoIdentidad()}});
   //     $.ajax({
   //       type: 'POST',
   //       data : datajs,
   //       dataType: "json",
   //       url: SITE_URL+'/Catalogo/cCliente/ConsultarSunat',
   //       success: function (data) {
   //         callback(data,event);
   //       },
   //       error : function (jqXHR, textStatus, errorThrown) {
   //         var data = {error:{msg:jqXHR.responseText}};
   //         callback(data,event);
   //       }
   //     });
   //   }
   // }

   self.ObtenerClientePorServicioExterno = function(data,event,callback) {
     if(event) {

       var idtipo= data.IdTipoDocumentoIdentidad();
       var incluye = {
         'include': ["RazonSocial","NombreCompleto","ApellidoCompleto","Direccion","NombreComercial","RepresentanteLegal","Email","Celular","TelefonoFijo"]
       };

       if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI) {
         self.ConsultarReniec(data, event,function($data,$event) {
           if($data.success == false) {
             callback($data,event);
           }
           else {
             if ($data.success == true) {
               var objetoJS =ko.mapping.toJS(self.ClienteNuevo);
               var extra = leaveJustIncludedProperties(objetoJS,incluye.include);
               ko.mapping.fromJS(extra,{}, self);
               ko.mapping.fromJS($data.result, {}, self);
               self.IdTipoPersona(2);
               callback($data.result,event);
             }
             else {
               var $datajs = {error:{msg:$data.message}};
               callback($datajs,event);
             }
           }
         });
       }
       else if(idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_RUC) {
         self.ConsultarSunat(data, event,function($data,$event) {
           if($data.success == false) {
             callback($data,event);
           }
           else {
             if ($data.success == true) {
               var objetoJS =ko.mapping.toJS(self.ClienteNuevo);
               var extra = leaveJustIncludedProperties(objetoJS,incluye.include);
               ko.mapping.fromJS(extra,{}, self);
               ko.mapping.fromJS($data.result, {}, self);
               if($data.result.TipoPersona == 1) self.IdTipoPersona(1);
               if($data.result.TipoPersona == 2) self.IdTipoPersona(2);
               callback($data.result,event);
             }
             else {
               var $datajs = {error:{msg:$data.message}};
               callback($datajs,event);
             }
           }
         });
       }
       else {
           callback(data,event);
       }
     }
   }

   self.ConsultarReniec = function(data,event) {
     if (event) {
       var numero = ko.mapping.toJS(Models.data.Empleado.NumeroDocumentoIdentidad);
       if (numero!=null) {
         if (numero.length == 8) {
           Models.data.Empleado.IdTipoPersona(2);
         }
       }

       $("#loader").show();
       var datajs = ko.toJS({"Data":{'NumeroDocumentoIdentidad':data.NumeroDocumentoIdentidad}});
       $.ajax({
         type: 'POST',
         data : datajs,
         dataType: "json",
         url: SITE_URL+'/Catalogo/cEmpleado/ConsultarReniec',
         success: function (data) {
           if (data['success'] == true) {
             ko.mapping.fromJS(data.result, {}, Models.data.Empleado);
             self.IdTipoPersona(2);
           }
           else {
             alertify.alert("ERROR EN "+self.MostrarTitulo(),data.message);
           }
           $("#loader").hide();
         },
         error : function (jqXHR, textStatus, errorThrown) {
           $("#loader").hide();
           console.log(jqXHR.responseText);
         }
       });
     }
   }

   self.ConsultarSunat = function(data,event) {
     if (event) {
       var numero = ko.mapping.toJS(Models.data.Empleado.NumeroDocumentoIdentidad);
       if (numero!=null) {
         if (numero.length == 11) {
           Models.data.Empleado.IdTipoPersona(4);
         }
       }

       $("#loader").show();
       var datajs = ko.toJS({"Data":{'NumeroDocumentoIdentidad':data.NumeroDocumentoIdentidad}});
       $.ajax({
         type: 'POST',
         data : datajs,
         dataType: "json",
         url: SITE_URL+'/Catalogo/cCliente/ConsultarSunat',
         success: function (data) {
           if (data['success'] == true) {
             ko.mapping.fromJS(data.result, {}, Models.data.Empleado);
             self.IdTipoPersona(1);
           }
           else {
             alertify.alert("ERROR EN "+self.MostrarTitulo(),data.message);
           }
           $("#loader").hide();
         },
         error : function (jqXHR, textStatus, errorThrown) {
           $("#loader").hide();
           console.log(jqXHR.responseText);
         }
       });
     }
   }

   self.OnClickBtnCerrar = function(data,event)  {
     if(event) {
       $("#modalEmpleado").modal("hide");
       if (self.callback) self.callback(self,event);
     }
   }

   self.Show = function(event) {
     if(event) {
       self.showEmpleado(true);
     }
   }

   self.Hide = function(event) {
     if(event) {
       self.showEmpleado(false);
       self.EstaProcesado(false);
       self.OnClickBtnCerrar(self,event);
     }
   }

   self.MostrarTitulo = ko.pureComputed( function () {
     if (self.opcionProceso() == opcionProceso.Nuevo)  {
       self.titulo = "REGISTRO DE EMPLEADO";
     }
     else {
       self.titulo = "EDICIÓN DE EMPLEADO";
     }

     return self.titulo;
   },this);

}
