<script type="text/javascript">

</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/AutoCompletadoProveedores.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/AutoCompletadoVendedor.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/CompraDetallado/ModeloVistaCompraDetallado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/CompraDetallado/CompraDetallado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/CompraGeneral/ModeloVistaCompraGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/CompraGeneral/CompraGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ProductosMasComprados/ModeloVistaProductosMasComprados.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ProductosMasComprados/ProductosMasComprados.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ComprasMensuales/ModeloVistaComprasMensuales.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ComprasMensuales/ComprasMensuales.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ComprasPorMercaderia/ModeloVistaComprasPorMercaderia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ComprasPorMercaderia/ComprasPorMercaderia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ProductoProveedor/ModeloVistaProductoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ProductoProveedor/ProductoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ReporteSaldoProveedor/ModeloVistaReporteSaldoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ReporteSaldoProveedor/ReporteSaldoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ReporteFormato8_1Compra/ModeloVistaReporteFormato8_1Compra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ReporteFormato8_1Compra/ReporteFormato8_1Compra.js"></script>
<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
vistaModeloReporte = {
  vmrReporteCompraDetallado: new IndexReporteCompraDetallado(data.dataReporteCompraDetallado),
  vmrReporteCompraGeneral: new IndexReporteCompraGeneral(data.dataReporteCompraGeneral),
  vmrReporteProductoMasComprado: new IndexReporteProductoMasComprado(data.dataReporteProductoMasComprado),
  vmrReporteComprasMensuales: new IndexReporteComprasMensuales(data.dataReporteComprasMensuales),
  vmgReporteComprasPorMercaderia: new IndexReporteComprasPorMercaderia(data.dataReporteComprasPorMercaderia),
  vmgReporteProductoPorProveedor: new IndexReporteProductoProveedor(data.dataReporteProductoPorProveedor),
  vmgReporteFormato8_1Compra: new IndexReporteFormato8_1Compra(data.dataReporteFormato8_1Compra),
  vmgReporteSaldoProveedor: new IndexReporteSaldoProveedor(data.dataReporteSaldoProveedor),
  parametros: ko.observable(data.parametros)
}
</script>
<script>
  ko.applyBindingsWithValidation(vistaModeloReporte);
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Compra/ContenedorFooter.js"></script>
<script type="text/javascript">
  // RecorrerLista.Agregar("#tab-general");
</script>
