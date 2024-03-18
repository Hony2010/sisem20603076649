
ClientesModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.CargarFoto = function(data, event){
      if (event) {
        var src = "";
        if (data != null) {
          console.log(data.IdPersona());
          if (data.IdPersona()=="" || data.IdPersona() == null || data.Foto() == null || data.Foto() == ""){
            src=BASE_URL + CARPETA_IMAGENES + "nocover.png";
          }
          else{
            src=SERVER_URL + CARPETA_IMAGENES + CARPETA_CLIENTE+data.IdPersona()+SEPARADOR_CARPETA+data.Foto();
          }
          return src;
        }
      }
    }

    self.SeleccionarAnterior = function (data,event){
      if (event) {
        var id = "#"+data.IdPersona();
        var anteriorObjeto = $(id).prev();

        anteriorObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo == false)
        {
          var match = ko.utils.arrayFirst(Models.data.Clientes(), function(item) {
            return anteriorObjeto.attr("id") == item.IdPersona();
          });

          $("#img_FileFoto").attr("src",self.CargarFoto(match,event));//OJO AQUI
          $("#img_FileFotoPreview").attr("src",self.CargarFoto(match,event));
        }
      }
    }

    self.SeleccionarSiguiente = function (data, event){
      if (event) {
        var id = "#"+data.IdPersona();
        var siguienteObjeto = $(id).next();

        if (siguienteObjeto.length > 0)
        {
          siguienteObjeto.addClass('active').siblings().removeClass('active');

          if (_modo_nuevo == false) //revisar
          {
            var match = ko.utils.arrayFirst(Models.data.Clientes(), function(item) {
              return siguienteObjeto.attr("id") == item.IdPersona();
            });

            $("#img_FileFoto").attr("src",self.CargarFoto(match,event));//OJO AQUI
            $("#img_FileFotoPreview").attr("src",self.CargarFoto(match,event));
          }
        }
        else
        {
          self.SeleccionarAnterior(data,event);
        }
      }
    }

    self.Seleccionar = function (data,event){
      if (event) {
        console.log("Seleccionar");

        var id = "#"+ data.IdPersona();
        $(id).addClass('active').siblings().removeClass('active');
        //debugger;

        var objeto = Knockout.CopiarObjeto(data);
        ko.mapping.fromJS(objeto, Mapping, Models.data.Cliente);

        _objeto = Knockout.CopiarObjeto(Models.data.Cliente);

        $("#img_FileFoto").attr("src",self.CargarFoto(objeto,event));//OJO AQUI
        $("#img_FileFotoPreview").attr("src",self.CargarFoto(objeto,event));
      }
    }

    self.Editar  = function(data, event) {
      if(event)
      {
        _opcion_guardar = 1;
        if( _modo_nuevo == true )
        {

        }
        else
        {
          Models.data.Cliente.ChangeTipoPersona(data,event);
          Models.data.Cliente.RazonSocial(data,event);
          $("#modalCliente").modal("show");
          $("#CodigoCliente").focus();
        }
        _modo_nuevo = false;
        $('#btn-busqueda').hide();
      }
    }

    self.PreEliminar = function (data, event) {
      if (event) {
        setTimeout(function(){
          alertify.confirm("¿Desea borrar realmente?", function(){
            console.log("PreEliminarCliente");

            if (data.IdPersona() != null)
            self.Eliminar(data,event);
          });
        }, 100);
      }
    }

    self.Eliminar = function (data,event) {
      if (event) {
        var objeto = data;
        var datajs = ko.toJS({"Data":data});

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Catalogo/cCliente/BorrarCliente',
          success: function (data) {
            if (data != null) {
              console.log("BorrarCliente");
              if(data.msg != "")
              {
                alertify.alert(data.msg);
              }
              else {

                self.SeleccionarSiguiente(objeto, event);
                Models.data.Clientes.remove(objeto);
                var filas = Models.data.Clientes().length;

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
}

ClienteModel = function (data) {
    var self = this;

    ko.mapping.fromJS(data, {}, self);
    self.EstadoCondicion = ko.observable(self.EstadoContribuyente() + " - " +self.CondicionContribuyente());

    self.OnChangeInputFile = function(data,event){

      if(event)
      {
        var file =event.target.files[0];
        var filename = file.name;
        var id = event.target.attributes.id.value;
        var img = $("#img_"+id);
        readImageAsDataURL(file,img);

        var nombre_foto = filename;
        var _filename = "";
        if(nombre_foto != null || nombre_foto != ""){
         _filename = nombre_foto.split(" ").join("_");
        }
        data.Foto(_filename);
      }
    }

    self.AbrirPreview = function(data,event){
      if(event)
      {
        var img = event.target;
        var dataURL = img.src;

        if(  dataURL != '')
        {
          $("#foto_previa").attr('src',dataURL);
          $("#modalPreview").modal("show");
        }
      }
    }

    self.SubirFoto = function(data, event) {
      if (event)
      {
        var modal = document.getElementById("form");
        console.log($("#IdPersona").val());
        var data = new FormData(modal);

        $.ajax({
          type: 'POST',
          data : data,
          contentType: false,       // The content type used when sending data to the server.
          cache: false,             // To unable request pages to be cached
          processData: false,        // To send DOMDocument or non processed data file it is set to false
          mimeType: "multipart/form-data",
          url: SITE_URL+'/Catalogo/cCliente/SubirFoto',
          success: function (data) {

            if(_opcion_guardar!= 0 ){
              var fila_objeto =ko.utils.arrayFirst(Models.data.Clientes(), function(item) {
                return Models.data.Cliente.IdPersona() == item.IdPersona();

              });
              var objeto = Knockout.CopiarObjeto(Models.data.Cliente);
              var _objeto = ko.mapping.toJS(objeto);
              objeto = new ClientesModel(_objeto);
              Models.data.Clientes.replace(fila_objeto,objeto);

              objeto.Seleccionar(Models.data.Cliente, event);

              $("#modalCliente").modal("hide");
            }
            else {
              //debugger;
              var copia_objeto = Knockout.CopiarObjeto(Models.data.Cliente);
              copia_objeto = new ClientesModel(copia_objeto);
              Models.data.Clientes.push(new ClientesModel(copia_objeto));

              copia_objeto.Seleccionar(Models.data.Cliente, event);
              alertify.confirm("Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function(){
                ko.mapping.fromJS(Models.data.NuevoCliente, {}, Models.data.Cliente);
                document.getElementById("form").reset();

                //LIMPIADOR DE IMAGENES A BLANCO
                  self.LimpiarImagen(event);

                setTimeout(function(){
                  $("#TipoDocumentoIdentidad").focus();
                }, 200);
              }, function(){
                _modo_nuevo == false;
                $("#modalCliente").modal("hide");
              });

            }
          }
        });
      }
    }

    self.Guardar =function(data,event){
      if(event)
      {
        $("#loader").show();
        var accion = "";
        if(_opcion_guardar != 0)
        {
          accion = "ActualizarCliente";
        }
        else
        {
          accion = "InsertarCliente";
        }
        Models.data.Cliente.RazonSocial($("#RazonSocial").val());

        var datajs = ko.toJS({"Data": Models.data.Cliente});
        console.log(datajs);
        console.log(data);
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Catalogo/cCliente/' + accion,
          success: function (data) {
              console.log(data);
              if(_opcion_guardar != 0){
                if(data == "")
                {
                  ko.mapping.fromJS(data, Mapping, Models.data.Cliente);

                  var nombretipopersona = $("#combo-tipopersona option:selected").text();
                  Models.data.Cliente.NombreTipoPersona(nombretipopersona);

                  var nombretipodocumentoindentidad = $("#combo-tipodocumentoIdentidad option:selected").text();
                  Models.data.Cliente.NombreAbreviado(nombretipodocumentoindentidad);

                  self.SubirFoto(data, event);
                }
                else {
                  alertify.alert(data);
                }
              }
              else {
                if(!data.error)
                {
                  ko.mapping.fromJS(data.resultado, Mapping, Models.data.Cliente);

                  var nombretipopersona = $("#combo-tipopersona option:selected").text();
                  Models.data.Cliente.NombreTipoPersona(nombretipopersona);

                  var nombretipodocumentoindentidad = $("#combo-tipodocumentoIdentidad option:selected").text();
                  Models.data.Cliente.NombreAbreviado(nombretipodocumentoindentidad);
                  //console.log("ID PRODUCTO" + data.IdPersona);
                  self.SubirFoto(data, event);
                }
                else {
                  alertify.alert(data.error.msg);
                  $("#TipoDocumentoIdentidad").focus();
                }
              }
              $("#loader").hide();
          }
        });
      }
    }

    self.ChangeRazonSocial = function(data,event) {
        if(event)
        {
          var IdTipoPersona = data.IdTipoPersona();
          if((IdTipoPersona == 2) || (IdTipoPersona == 3)) //Persona Judirica || No Domiciliado
          {
            var NombreCompleto = $("#NombreCompleto").val();
            var ApellidoCompleto = $("#ApellidoCompleto").val();
            $("#RazonSocial").val(ApellidoCompleto+' '+NombreCompleto);
          }
        }
      }

    self.ChangeTipoPersona = function(data,event) {
        if(event)
        {
          var IdTipoPersona = data.IdTipoPersona();
          if(IdTipoPersona == 1) //Persona Judirica
          {
            // $("#NombreCompleto").val("");
            $("#NombreCompleto").prop("disabled",true);
            // $("#ApellidoCompleto").val("");
            $("#ApellidoCompleto").prop("disabled",true);
            $("#RazonSocial").prop("disabled",false);
            //$("#RazonSocial").val("");

          }
          else if((IdTipoPersona == 2) || (IdTipoPersona == 3) ) //Persona Natural || No Domiciliado
          {
            $("#NombreCompleto").prop("disabled",false);
            $("#ApellidoCompleto").prop("disabled",false);
            $("#RazonSocial").prop("disabled",true);
            // $("#RazonSocial").val("");
          }
        }
      }

    self.LimpiarImagen = function(event){
      if (event) {
        var src= BASE_URL + CARPETA_IMAGENES + "nocover.png";
        $('#img_FileFoto').attr('src', src);
        $('#img_FileFotoPreview').attr('src', src);
      }
    }

    self.Deshacer = function(event) {
      if(event)
      {
        if($('#btn_Limpiar').text() == "Deshacer")
        {
          _seleccionar_objeto = new ClientesModel(_objeto);
          _seleccionar_objeto.Seleccionar(_objeto, null);
          ko.mapping.fromJS(_objeto, Mapping, Models.data.Cliente);

        }
        else  if($('#btn_Limpiar').text() == "Limpiar")
        {
          ko.mapping.fromJS(Models.data.NuevoCliente, {}, Models.data.Cliente);
          document.getElementById("form").reset();
          $('#combo-tipopersona').val(2);
          $('#combo-tipopersona').prop("disabled", true);
          self.LimpiarImagen(event);

          setTimeout(function(){
            $("#CodigoCliente").focus();
          }, 500);
        }

      }
    }

    self.Cerrar = function(event)  {
      if(event)
      {
        $("#modalCliente").modal("hide");

        if(_modo_nuevo == true){
          _modo_nuevo = false;
        }
        var objeto = new ClientesModel(_objeto);
        objeto.Seleccionar(_objeto, event);
      }
    }

    self.ChangeBtnBusqueda = function(data, event ) {

        if (event) {
          var logoTipoDocumento = ko.mapping.toJS(Models.data.Cliente.IdTipoDocumentoIdentidad);
          if ( logoTipoDocumento == 2 || logoTipoDocumento == 4 ) {
            $('#btn-busqueda').show();
            if (logoTipoDocumento == 2) {
              $('#btn-busqueda').empty();
              $('#btn-busqueda').append('<img width="25px" height="22px" src="'+BASE_URL+'assets/js/iconos/logoRENIEC.svg" alt="" title="RENIEC">');
            }
            else {
              $('#btn-busqueda').empty();
              $('#btn-busqueda').append('<img width="22px" height="20px" src="'+BASE_URL+'assets/js/iconos/logoSUNAT.svg" alt="" title="SUNAT">');
            }
          }
          else {
            $('#btn-busqueda').hide();
          }
        }
      }

    self.ConsultaPorNumeroDocumento=function(data, event){
      if (event) {
        var numero = ko.mapping.toJS(Models.data.Cliente.NumeroDocumentoIdentidad);
        var logoTipoDocumento = ko.mapping.toJS(Models.data.Cliente.IdTipoDocumentoIdentidad);
        if (numero !=null) {
          if (numero.length == 8) {
            Models.data.Cliente.IdTipoPersona(2);
          }
          else if(numero.length > 8){
            if (numero.substring(0, 2) == 20){
              Models.data.Cliente.IdTipoPersona(1);
            }
            else if (numero.substring(0, 1) == 1) {
              Models.data.Cliente.IdTipoPersona(2);
            }
          }
        }

        if (logoTipoDocumento == 2) {
          self.ConsultarReniec(data, event);
        }
        else if(logoTipoDocumento == 4) {
          self.ConsultarSunat(data, event);
        }
      }
    }

    self.ConsultarSunat = function(data, event) {
        if (event) {
          $("#loader").show();
          var datajs = ko.toJS({"Data":{'NumeroDocumentoIdentidad':data.NumeroDocumentoIdentidad}});
          $.ajax({
            type: 'POST',
            data : datajs,
            dataType: "json",
            url: SITE_URL+'/Catalogo/cCliente/ConsultarSunat',
            success: function (data) {
              if (data['success'] == true) {
                var objeto = new ClienteModel(data.result);
                ko.mapping.fromJS(objeto, Mapping, Models.data.Cliente);

              }
              else {
                alertify.alert(data.msg);
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

    self.ConsultarReniec = function(data, event) {

        if (event) {
          $("#loader").show();
          var datajs = ko.toJS({"Data":{'NumeroDocumentoIdentidad':data.NumeroDocumentoIdentidad}});
          $.ajax({
            type: 'POST',
            data : datajs,
            dataType: "json",
            url: SITE_URL+'/Catalogo/cCliente/ConsultarReniec',
            success: function (data) {
              if (data['success'] == true) {
                ko.mapping.fromJS(data.result, {}, Models.data.Cliente);
              }
              else {
                alertify.alert(data.message);
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
}

NuevoClienteModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    //this.NombreProducto = ko.observable();
}

var Mapping = {
    'Clientes': {
        create: function (options) {
            if (options)
              return new ClientesModel(options.data);
            }
    },
    'Cliente': {
        create: function (options) {
            console.log('Cliente');
            console.log(options);
            if (options)
              return new ClienteModel(options.data);
            }
    },
    'NuevoCliente': {
        create: function (options) {
            if (options)
              return new ClienteModel(options.data);
            }
    }
}

jQuery.isSubstring = function (haystack, needle) {
  return haystack.indexOf(needle) !== -1;

};

ImageURL = data.data.ImageURL;
var _opcion_guardar = 1;
var ImageURL;
var _modo_nuevo = false;
var _objeto = null;

Index = function (data) {
    var self = this;


    ko.mapping.fromJS(data, Mapping, self);

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        self.ConsultarClientesPorPagina(data,event,self.PostConsultarPorPagina);
        $("#Paginador").pagination("drawPage", data.pagina);
      }
    }

    self.PostConsultarPorPagina =  function(data,event) {
      if(event) {
        self.data.Clientes([]);
        ko.utils.arrayForEach(data, function (item) {
          self.data.Clientes.push(new ClientesModel(item));
        });

        var objeto = Models.data.Clientes()[0];
        var _seleccionar_objeto = new ClientesModel(_objeto);
        _seleccionar_objeto.Seleccionar(objeto, event);
      }
    }

    self.ConsultarClientesPorPagina = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Catalogo/cCliente/ConsultarClientesPorPagina',
          success: function (data) {
              $("#loader").hide();
              callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert(jqXHR.responseText);
          }
        });
      }
    }

    self.Consultar = function (data,event) {
      if(event) {
        var tecla = event.keyCode ? event.keyCode : event.which;
        if(tecla == TECLA_ENTER)
        {
          var inputs = $(event.target).closest('form').find(':input:visible');
          inputs.eq(inputs.index(event.target)+ 1).focus();

          self.ConsultarClientes(data,event,self.PostConsultar);
        }
      }
    }

    self.ConsultarClientes = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Catalogo/cCliente/ConsultarClientes',
          success: function (data) {
              $("#loader").hide();
              callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert(jqXHR.responseText);
          }
        });
      }
    }

    self.PostConsultar = function (data,event) {
      if(event) {
        self.data.Clientes([]);
        ko.utils.arrayForEach(data.resultado, function (item) {
          self.data.Clientes.push(new ClientesModel(item));
        });

        var objeto = Models.data.Clientes()[0];
        objeto.Seleccionar(objeto, event);
        //ko.mapping.fromJS(data.Filtros,{},self.data.Filtros);
        $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
        self.data.Filtros.totalfilas(data.Filtros.totalfilas);
      }
    }

    self.NuevoCliente = function(data,event) {
          //console.log("AgregarFamiliaProducto");
        if(event)
        {
          _objeto = Knockout.CopiarObjeto(Models.data.Cliente);
          ko.mapping.fromJS(Models.data.NuevoCliente, Mapping, Models.data.Cliente);

          //LIMPIADOR DE IMAGENES A BLANCO
          Models.data.Cliente.LimpiarImagen(event);

          $('#btn_Limpiar').text("Limpiar");

          $("#modalCliente").modal("show");

          setTimeout(function(){
            $("#combo-tipodocumentoIdentidad").focus();
          }, 1000);

          _opcion_guardar = 0;
          _modo_nuevo = true;

          Models.data.Cliente.ChangeBtnBusqueda(data);
        }
    }
}
