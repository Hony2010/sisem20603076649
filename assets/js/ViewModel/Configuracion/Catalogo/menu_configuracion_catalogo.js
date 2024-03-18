$(document).ready(function() {

  $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
    var nombre_catalgo = $(this).prop('rel');
    e.preventDefault();
    $(this).siblings('a.active').removeClass("active");
    $(this).addClass("active");
    var index = $(this).index();

    $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
    $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");

    if (nombre_catalgo == "linea") {
      if (vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/Catalogo/cVistaModeloCatalogo/LineaProducto',
          success: function (response) {
            if (response != null) {
              vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto([]);
              ko.utils.arrayForEach(response.dataLineaProducto.LineasProducto, function(item) {
                vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto.push(new LineasProductoModel(item));
              });
              var primera_fila = vistaModeloCatalogo.vmcLineaProducto.dataLineaProducto.LineasProducto()[0];
              vistaModeloCatalogo.vmcLineaProducto.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }
    if (nombre_catalgo == "marca") {
      if (vistaModeloCatalogo.vmcMarca.dataMarca.Marcas()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/Catalogo/cVistaModeloCatalogo/Marca',
          success: function (response) {
            if (response != null) {
              vistaModeloCatalogo.vmcMarca.dataMarca.Marcas([]);
              ko.utils.arrayForEach(response.dataMarca.Marcas, function(item) {
                vistaModeloCatalogo.vmcMarca.dataMarca.Marcas.push(new MarcasModel(item));
              });
              var primera_fila = vistaModeloCatalogo.vmcMarca.dataMarca.Marcas()[0];
              vistaModeloCatalogo.vmcMarca.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }
    if (nombre_catalgo == "tipo_de_existencia") {
      if (vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/Catalogo/cVistaModeloCatalogo/TipoExistencia',
          success: function (response) {
            if (response != null) {
              vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia([]);
              ko.utils.arrayForEach(response.dataTipoExistencia.TiposExistencia, function(item) {
                vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia.push(new TiposExistenciaModel(item));
              });
              var primera_fila = vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia()[0];
              vistaModeloCatalogo.vmcTipoExistencia.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }
    if (nombre_catalgo == "fabricante") {
      if (vistaModeloCatalogo.vmcFabricante.dataFabricante.Fabricantes()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/Catalogo/cVistaModeloCatalogo/Fabricante',
          success: function (response) {
            if (response != null) {
              vistaModeloCatalogo.vmcFabricante.dataFabricante.Fabricantes([]);
              ko.utils.arrayForEach(response.dataFabricante.Fabricantes, function(item) {
                vistaModeloCatalogo.vmcFabricante.dataFabricante.Fabricantes.push(new FabricantesModel(item));
              });
              var primera_fila = vistaModeloCatalogo.vmcFabricante.dataFabricante.Fabricantes()[0];
              vistaModeloCatalogo.vmcFabricante.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }
    if (nombre_catalgo == "tipo_de_servicio") {
      if (vistaModeloCatalogo.vmcTipoServicio.dataTipoServicio.TiposServicio()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/Catalogo/cVistaModeloCatalogo/TipoServicio',
          success: function (response) {
            if (response != null) {
              vistaModeloCatalogo.vmcTipoServicio.dataTipoServicio.TiposServicio([]);
              ko.utils.arrayForEach(response.dataTipoServicio.TiposServicio, function(item) {
                vistaModeloCatalogo.vmcTipoServicio.dataTipoServicio.TiposServicio.push(new TiposServicioModel(item));
              });
              var primera_fila = vistaModeloCatalogo.vmcTipoServicio.dataTipoServicio.TiposServicio()[0];
              vistaModeloCatalogo.vmcTipoServicio.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }
    if (nombre_catalgo == "tipo_doc_identidad") {
      if (vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/Catalogo/cVistaModeloCatalogo/TipoDocumentoIdentidad',
          success: function (response) {
            if (response != null) {
              vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad([]);
              ko.utils.arrayForEach(response.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad, function(item) {
                vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad.push(new TiposDocumentoIdentidadModel(item));
              });
              var primera_fila = vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad()[0];
              vistaModeloCatalogo.vmcTipoDocumentoIdentidad.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }
  });
});
