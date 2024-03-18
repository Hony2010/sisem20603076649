<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaClientes/ModeloVistaListaClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaClientes/ListaClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ClientesPorZona/ModeloVistaClientesPorZona.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ClientesPorZona/ClientesPorZona.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaMercaderias/ModeloVistaListaMercaderias.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaMercaderias/ListaMercaderias.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaActivosFijos/ModeloVistaListaActivosFijos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaActivosFijos/ListaActivosFijos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaGastos/ModeloVistaListaGastos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaGastos/ListaGastos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaEmpleados/ModeloVistaListaEmpleados.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaEmpleados/ListaEmpleados.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaProveedores/ModeloVistaListaProveedores.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaProveedores/ListaProveedores.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListacostosAgregados/ModeloVistaListacostosAgregados.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListacostosAgregados/ListacostosAgregados.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaOtrasVentas/ModeloVistaListaOtrasVentas.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaOtrasVentas/ListaOtrasVentas.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaFamiliasSubFamilias/ModeloVistaListaFamiliasSubFamilias.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaFamiliasSubFamilias/ListaFamiliasSubFamilias.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaMarcasModelos/ModeloVistaListaMarcasModelos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ListaMarcasModelos/ListaMarcasModelos.js"></script>

<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var koNode = document.getElementById('maincontent');
  vistaModeloReporte = {
    vmgReporteListaClientes: new IndexReporteListaClientes(data.dataReporteListaClientes),
    vmgReporteListaMercaderias: new IndexReporteListaMercaderias(data.dataReporteListaMercaderias),
    vmgReporteListaActivosFijos: new IndexReporteListaActivosFijos(data.dataReporteListaActivosFijos),
    vmgReporteListaGastos: new IndexReporteListaGastos(data.dataReporteListaGastos),
    vmgReporteListaEmpleados: new IndexReporteListaEmpleados(data.dataReporteListaEmpleados),
    vmgReporteListaProveedores: new IndexReporteListaProveedores(data.dataReporteListaProveedores),
    vmgReporteListaCostosAgregados: new IndexReporteListaCostosAgregados(data.dataReporteListaCostosAgregados),
    vmgReporteListaOtrasVentas: new IndexReporteListaOtrasVentas(data.dataReporteListaOtrasVentas),
    vmgReporteListaFamiliasSubFamilias: new IndexReporteListaFamiliasSubFamilias(data.dataReporteListaFamiliasSubFamilias),
    vmgReporteListaMarcasModelos: new IndexReporteListaMarcasModelos(data.dataReporteListaMarcasModelos),
    vmgReporteClientesPorZona: new IndexReporteClientesPorZona(data.dataReporteClientesPorZona)
  }
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(vistaModeloReporte, koNode);
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Catalogo/ContenedorFooter.js"></script>
<script type="text/javascript">
  // RecorrerLista.Agregar("#tab-general");
</script>
