<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/menu_configuracion_general.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/Empresa.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/FormaPago.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/GiroNegocio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/GrupoParametro.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MedioPago.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/Moneda.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/RegimenTributario.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/Sede.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoDocumento.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoSede.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/UnidadMedida.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/UnidadMedida.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/CorrelativoDocumento.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/CorrelativoDocumento.js"></script>

<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
  
</script>

<script>
vistaModeloGeneral = {
  vmgEmpresa: new IndexEmpresa(data.dataEmpresa),
  vmgFormaPago: new IndexFormaPago(data.dataFormaPago),
  vmgGiroNegocio: new IndexGiroNegocio(data.dataGiroNegocio),
  vmgGrupoParametro: new IndexGrupoParametro(data.dataGrupoParametro),
  vmgMedioPago: new IndexMedioPago(data.dataMedioPago),
  vmgMoneda: new IndexMoneda(data.dataMoneda),
  vmgRegimenTributario: new IndexRegimenTributario(data.dataRegimenTributario),
  vmgSede: new IndexSede(data.dataSede),
  vmgTipoCambio: new IndexTipoCambio(data.dataTipoCambio),
  vmgTipoDocumento: new IndexTipoDocumento(data.dataTipoDocumento),
  vmgTipoSede: new IndexTipoSede(data.dataTipoSede),
  vmgUnidadMedida: new IndexUnidadMedida(data.dataUnidadMedida),
  vmgCorrelativoDocumento: new IndexCorrelativoDocumento(data.dataCorrelativoDocumento)
}
</script>
<script>
  ko.applyBindingsWithValidation(vistaModeloGeneral);
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/ContenedorFooter.js"></script>

<script type="text/javascript">
  RecorrerLista.Agregar("#tab-general");
</script>
