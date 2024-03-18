<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/menu_configuracion_catalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/Marca.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/Modelo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/FamiliaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/SubFamiliaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/LineaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/TipoExistencia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/Fabricante.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/TipoServicio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/VistaModeloTipoDocumentoIdentidad.js"></script>
<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
vistaModeloCatalogo = {
  vmcFamilia: new IndexFamilia(data.dataFamiliaProducto),
  vmcLineaProducto: new IndexLineaProducto(data.dataLineaProducto),
  vmcMarca: new IndexMarca(data.dataMarca),
  vmcTipoExistencia: new IndexTipoExistencia(data.dataTipoExistencia),
  vmcFabricante: new IndexFabricante(data.dataFabricante),
  vmcTipoServicio: new IndexTipoServicio(data.dataTipoServicio),
  vmcTipoDocumentoIdentidad: new IndexTipoDocumentoIdentidad(data.dataTipoDocumentoIdentidad)
}
</script>

<script>
ko.applyBindingsWithValidation(vistaModeloCatalogo);
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/ContenedorFooter.js"></script>

<script type="text/javascript">
  RecorrerLista.Agregar("#tab-configuracion");

  //Recorrer Tabla
  RecorrerTabla.Agregar("#DataTables_Table_0_familiaProducto tbody");
  RecorrerTabla.Agregar("#tabla-subfamiliaproducto tbody");
  RecorrerTabla.Agregar("#DataTables_Table_0_lineaProducto tbody");
  RecorrerTabla.Agregar("#DataTables_Table_0 tbody");
  RecorrerTabla.Agregar("#tabla-modelo tbody");
  RecorrerTabla.Agregar("#DataTables_Table_0_tipoExistencia tbody");
  RecorrerTabla.Agregar("#DataTables_Table_0_fabricante tbody");
  RecorrerTabla.Agregar("#DataTables_Table_0_tipoServicio tbody");
  RecorrerTabla.Agregar("#DataTables_Table_0_tipodocumentoidentidad tbody");
</script>
