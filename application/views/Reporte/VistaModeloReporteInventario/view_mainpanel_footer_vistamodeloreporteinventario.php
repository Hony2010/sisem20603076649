<script src="<?php echo base_url() ?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/AutoCompletadoDuaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/AutoCompletadoDocumentoZofra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/AutoCompletadoDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockProducto/ModeloVistaStockProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockProducto/StockProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockProductoLote/ModeloVistaStockProductoLote.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockProductoLote/StockProductoLote.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockProductoMarca/ModeloVistaStockProductoMarca.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockProductoMarca/StockProductoMarca.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockProductoDua/ModeloVistaStockProductoDua.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockProductoDua/StockProductoDua.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/MovimientoDocumentoDua/ModeloVistaMovimientoDocumentoDua.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/MovimientoDocumentoDua/MovimientoDocumentoDua.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/MovimientoDocumentoZofra/ModeloVistaMovimientoDocumentoZofra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/MovimientoDocumentoZofra/MovimientoDocumentoZofra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockProductoDocumentoZofra/ModeloVistaStockProductoDocumentoZofra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockProductoDocumentoZofra/StockProductoDocumentoZofra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockNegativo/ModeloVistaStockNegativo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/StockNegativo/StockNegativo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/MovimientoAlmacenValorado/ModeloVistaMovimientoAlmacenValorado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/MovimientoAlmacenValorado/MovimientoAlmacenValorado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/MovimientoMercaderia/ModeloVistaMovimientoMercaderia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/MovimientoMercaderia/MovimientoMercaderia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/MovimientoAlmacenDocumentoIngreso/ModeloVistaMovimientoAlmacenDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/MovimientoAlmacenDocumentoIngreso/MovimientoAlmacenDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/ReporteDocumentoIngreso/ModeloVistaReporteDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/ReporteDocumentoIngreso/ReporteDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/ReporteInventario/ModeloVistaReporteInventario.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/ReporteInventario/ReporteInventario.js"></script>
<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>

VistaModeloReporteInventario = {
  vmrReporteStockProducto: new IndexReporteStockProducto(data.dataReporteStockProducto),
  vmrReporteStockProductoLote: new IndexReporteStockProductoLote(data.dataReporteStockProductoLote),
  vmrReporteStockProductoMarca: new IndexReporteStockProductoMarca(data.dataReporteStockProductoMarca),
  vmrReporteStockProductoDua: new IndexReporteStockProductoDua(data.dataReporteStockProductoDua),
  vmrReporteMovimientoDocumentoDua: new IndexReporteMovimientoDocumentoDua(data.dataReporteMovimientoDocumentoDua),
  vmrReporteStockProductoDocumentoZofra: new IndexReporteStockProductoDocumentoZofra(data.dataReporteStockProductoDocumentoZofra),
  vmrReporteMovimientoDocumentoZofra: new IndexReporteMovimientoDocumentoZofra(data.dataReporteMovimientoDocumentoZofra),
  vmrReporteStockNegativo: new IndexReporteStockNegativo(data.dataReporteStockNegativo),
  vmrReporteMovimientoAlmacenValorado: new IndexReporteMovimientoAlmacenValorado(data.dataReporteMovimientoAlmacenValorado),
  vmrReporteMovimientoMercaderia: new IndexReporteMovimientoMercaderia(data.dataReporteMovimientoMercaderia),
  vmrReporteMovimientoAlmacenDocumentoIngreso: new IndexReporteMovimientoAlmacenDocumentoIngreso(data.dataReporteMovimientoAlmacenDocumentoIngreso),
  vmrReporteDocumentoIngreso: new IndexReporteDocumentoIngreso(data.dataReporteDocumentoIngreso),
  vmrReporteInventario: new IndexReporteInventario(data.dataReporteInventario),
  parametros: ko.observable(data.parametros)
}
</script>
<script>
  ko.applyBindingsWithValidation(VistaModeloReporteInventario);
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Reporte/Inventario/ContenedorFooter.js"></script>
<script type="text/javascript">
  // RecorrerLista.Agregar("#tab-general");
</script>
