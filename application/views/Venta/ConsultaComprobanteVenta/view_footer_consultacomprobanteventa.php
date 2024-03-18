<script>
    var data=<?php echo json_encode($data); ?>;
    window.DataMotivosNotaCredito = data.data.MotivosNotaCredito;
    window.CamposNotaCredito = data.data.CamposNotaCredito;
    window.DataMotivosNotaDebito = data.data.MotivosNotaDebito;
    window.CamposNotaDebito = data.data.CamposNotaDebito;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/VistaModeloTipoDocumentoIdentidad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/MappingConfiguracionCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/ModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/VistaModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MappingConfiguracionGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Vehiculo/AutoCompletadoPlacaVehiculos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/ModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/VistaModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Mercaderia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/MappingVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/ModeloComprobanteVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/ModeloDetalleComprobanteVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/VistaModeloComprobanteVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/VistaModeloDetalleComprobanteVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/FacturaVenta/VistaModeloFacturaVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/BoletaVenta/VistaModeloBoletaVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/OrdenPedido/VistaModeloOrdenPedido.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/BoletaViajeVenta/VistaModeloBoletaViajeVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/Proforma/VistaModeloProforma.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaCredito/Motivos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaCredito/ModeloDetalleNotaCredito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaCredito/VistaModeloDetalleNotaCredito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaCredito/ModeloNotaCredito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaCredito/VistaModeloNotaCredito.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaCredito/OtrosModeloNotaCredito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaDebito/OtrosModeloNotaDebito.js"></script>
<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Venta/DocumentoReferencia/ModeloMiniComprobantesVenta.js"></script> -->

<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaDebito/Motivos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaDebito/ModeloDetalleNotaDebito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaDebito/VistaModeloDetalleNotaDebito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaDebito/ModeloNotaDebito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaDebito/VistaModeloNotaDebito.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ConsultaComprobanteVenta/ModeloConsultaComprobanteVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ConsultaComprobanteVenta/VistaModeloConsultaComprobanteVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantesventa.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloMercaderiaJSON.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloBusquedaAvanzadaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloBusquedaAvanzadaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/AutoCompletadoRadioTaxis.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/AutoCompletadoComprobanteVentaProforma.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/Proforma/ModeloBusquedaProforma.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/Proforma/VistaModeloBusquedaProforma.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/VistaModeloDireccionCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/VistaModeloCuotaPagoClienteComprobanteVenta.js"></script>
<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloConsultaComprobanteVenta(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);

    $("#BusquedaComprobantesVentaModel").on("hidden.bs.modal", function(e){
      if(ViewModels.data.NotaCredito.MiniComprobantesVenta().length <= 0)
      {
        $("#btn_buscardocumentoreferencia").focus();
        $("#BusquedaComprobantesVentaModel").resetearValidaciones();
      }
    });

</script>

<script type="text/javascript">
  ViewModels.Inicializar();
  RecorrerTabla.Agregar("#TablaInventarioMercaderias tbody");
  RecorrerPaginador.Agregar("#PaginadorJSONParaListaSimple");
</script>
