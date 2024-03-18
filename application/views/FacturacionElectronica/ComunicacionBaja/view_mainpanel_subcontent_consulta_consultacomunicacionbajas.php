<div class="scrollable scrollbar-macosx">
  <div class="container-fluid">
    <!-- ko with : $root.data.BuscadorConsulta  -->
    <?php echo $view_subcontent_buscador_consultacomunicacionbaja; ?>
    <!-- /ko -->
    <br class="espacio_ocultar" /><br class="espacio_ocultar" />
    <fieldset>
      <table class="datalist__table table" width="100%" data-products="brand">
        <thead>
          <tr>
            <th class="col-md-auto"><center>Nombre Comunicacion</center></th>
            <th class="col-md-1">&nbsp;</th>
            <th class="col-md-auto"><center>Fecha de Emisión</center></th>
            <th class="col-md-1">&nbsp;</th>
            <th class="col-md-auto">Estado</th>
            <th class="col-md-auto">&nbsp;</th>
            <th class="col-md-1">&nbsp;</th>
            <th class="col-md-auto">Ver Detalle</th>
            <th class="col-md-4">&nbsp;</th>
            <th  class="products__title">XML</th>
            <th  class="products__title">CDR</th>
          </tr>
        </thead>
        <tbody>
          <!-- ko foreach : ComunicacionesBajaConsulta -->
          <tr class="clickable-row" data-bind="attr : { id: IdComunicacionBaja }, event:{click: Seleccionar}" style="text-transform: UpperCase;">
            <td align="center" class="col-md-auto col-md-auto-height" data-bind="text: NombreComunicacionBaja"></td>
            <td align="center" class="col-md-1 col-md-auto-height">
              &nbsp;
            </td>
            <td align="center" class="col-md-auto col-md-auto-height" data-bind="text: FechaFormateada"></td>
            <td align="center" class="col-md-1 col-md-auto-height">
              &nbsp;
            </td>
            <td class="col-md-auto col-md-auto-height"><span data-bind="text: EstadoCE"></span></td>
            <td class="col-md-auto col-md-auto-height">
              <span class="fa fa-fw" style="font-size: 2em" data-bind="css: Icono, style:{color: Color}"></span>
            </td>
            <td align="center" class="col-md-1 col-md-auto-height">
              &nbsp;
            </td>
            <td align="center" class="col-md-auto col-md-auto-height">
              <button data-bind="attr : { id : IdComunicacionBaja() + '_btnEditar' }, event:{click: VerFacturas},visible: OnVisibleBtnVer() "
              class="btn btn-default btn-operaciones" data-toogle="tooltip" title="Ver Facturas">
              <span class="fa fa-fw fa-eye"></span>
            </button>
          </td>
          <td align="center" class="col-md-4  col-md-auto-height">
            &nbsp;
          </td>
          <td align="center" >
            <button data-bind="attr : { id : IdComunicacionBaja() + '_btnDescargarXML', name : '<?php echo site_url();?>/FacturacionElectronica/cComunicacionBaja/DescargarXML?nombre=' +  NombreComunicacionBaja(), value: NombreComunicacionBaja()} "
              class="btn btn-warning DescargaXML btn-operaciones" data-toogle="tooltip" title="Descargar XML">
              <span class="far fa-file-code fa-lg"></span>
            </button>
          </td>
          <td align="center" >
            <div data-bind="css: VistaCDR">
              <button data-bind="attr : { id : IdComunicacionBaja() + '_btnDescargarCDR', name : '<?php echo site_url();?>/FacturacionElectronica/cComunicacionBaja/DescargarCDR?nombre=' +  NombreComunicacionBaja(), value: NombreComunicacionBaja()} "
                class="btn btn-info DescargaCDR btn-operaciones" data-toogle="tooltip" title="Descargar XML">
                <span class="far fa-file-code fa-lg"></span>
              </button>
            </div>
          </td>
        </tr>
        <!-- /ko -->
      </tbody>
    </table>
    </fieldset>
    <p>
  </div>
</div>
