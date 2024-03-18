<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/AutoCompletadoVendedor.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentaDetallado/ModeloVistaVentaDetallado.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentaDetallado/VentaDetallado.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentaGeneral/ModeloVistaVentaGeneral.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentaGeneral/VentaGeneral.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ProductosMasVendidos/ModeloVistaProductosMasVendidos.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ProductosMasVendidos/ProductosMasVendidos.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/FamiliasMasVendidos/ModeloVistaFamiliasMasVendidos.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/FamiliasMasVendidos/FamiliasMasVendidos.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/MarcasMasVendidos/ModeloVistaMarcasMasVendidos.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/MarcasMasVendidos/MarcasMasVendidos.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentaDiaria/ModeloVistaVentaDiaria.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentaDiaria/VentaDiaria.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentasPorVendedor/ModeloVistaVentasPorVendedor.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentasPorVendedor/VentasPorVendedor.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ProductosPorFamilia/ModeloVistaProductosPorFamilia.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ProductosPorFamilia/ProductosPorFamilia.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentasMensuales/ModeloVistaVentasMensuales.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentasMensuales/VentasMensuales.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentasPorMercaderia/ModeloVistaVentasPorMercaderia.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentasPorMercaderia/VentasPorMercaderia.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/GananciaPorProducto/ModeloVistaGananciaPorProducto.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/GananciaPorProducto/GananciaPorProducto.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/GananciaPorVendedor/ModeloVistaGananciaPorVendedor.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/GananciaPorVendedor/GananciaPorVendedor.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/GananciaPorPrecioBaseProducto/ModeloVistaGananciaPorPrecioBaseProducto.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/GananciaPorPrecioBaseProducto/GananciaPorPrecioBaseProducto.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ReporteSaldoCliente/ModeloVistaReporteSaldoCliente.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ReporteSaldoCliente/ReporteSaldoCliente.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ReporteFormato14_1Venta/ModeloVistaReporteFormato14_1Venta.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ReporteFormato14_1Venta/ReporteFormato14_1Venta.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ResumenVentas/ModeloVistaResumenVentas.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ResumenVentas/ResumenVentas.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ReporteRemuneracionEmpleadosMetaMensual/VistaModeloReporteRemuneracionEmpleadosMetaMensual.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ReporteListaPrecios/VistaModeloReporteListaPrecios.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ReporteProductosPorFamiliaConsolidado/VistaModeloReporteProductosPorFamiliaConsolidado.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/VentaGeneralLubricante/VistaModeloVentaGeneralLubricante.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script>
  var data = <?php echo json_encode($data); ?>
</script>

<script>
  var koNode = document.getElementById('maincontent');
  vistaModeloReporte = {
    vmgReporteVentaDetallado: new IndexReporteVentaDetallado(data.dataReporteVentaDetallado),
    vmgReporteVentaGeneral: new IndexReporteVentaGeneral(data.dataReporteVentaGeneral),
    vmgReporteVentaGeneralLubricante: new IndexReporteVentaGeneralLubricante(data.dataReporteVentaGeneralLubricante),
    vmgProductosMasVendidos: new IndexProductosMasVendidos(data.dataProductosMasVendidos),
    vmgFamiliasMasVendidos: new IndexFamiliasMasVendidos(data.dataFamiliasMasVendidos),
    vmgMarcasMasVendidos: new IndexMarcasMasVendidos(data.dataMarcasMasVendidos),
    vmgVentaDiaria: new IndexVentaDiaria(data.dataVentaDiaria),
    vmgReporteVentasPorVendedor: new IndexReporteVentasPorVendedor(data.dataReporteVentasPorVendedor),
    vmgProductosPorFamilia: new IndexProductosPorFamilia(data.dataProductosPorFamilia),
    vmgVentasMensuales: new IndexVentasMensuales(data.dataVentasMensuales),
    vmgReporteVentasPorMercaderia: new IndexReporteVentasPorMercaderia(data.dataReporteVentasPorMercaderia),
    vmgReporteGananciaPorProducto: new IndexReporteGananciaPorProducto(data.dataReporteGananciaPorProducto),
    vmgReporteGananciaPorPrecioBaseProducto: new IndexReporteGananciaPorPrecioBaseProducto(data.dataReporteGananciaPorPrecioBaseProducto),
    vmgReporteGananciaPorVendedor: new IndexReporteGananciaPorVendedor(data.dataReporteGananciaPorVendedor),
    vmgReporteSaldoCliente: new IndexReporteSaldoCliente(data.dataReporteSaldoCliente),
    vmgReporteFormato14_1Venta: new IndexReporteFormato14_1Venta(data.dataReporteFormato14_1Venta),
    vmgReporteResumenVentas: new IndexReporteResumenVentas(data.dataReporteResumenVentas),
    vmgReporteRemuneracionEmpleadosMetaMensual: new IndexReporteRemuneracionEmpleadosMetaMensual(data.dataReporteRemuneracionEmpleadosMetaMensual),
    vmgReporteListaPrecios: new IndexReporteListaPrecios(data.dataReporteListaPrecios),
    vmgReporteProductosPorFamiliaConsolidado: new IndexReporteProductosPorFamiliaConsolidado(data.dataReporteProductosPorFamiliaConsolidado),
    parametros: ko.observable(data.parametros)
  }
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(vistaModeloReporte, koNode);
</script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Reporte/Venta/ContenedorFooter.js"></script>

<script type="text/javascript">
  // RecorrerLista.Agregar("#tab-general");
</script>