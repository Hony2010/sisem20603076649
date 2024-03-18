<div class="scrollable scrollbar-macosx">

  <div class="container-fluid">

    <!-- ko with : $root.data.BuscadorConsulta  -->
    <?php echo $view_subcontent_buscador_consultaresumendiario; ?>
    <!-- /ko -->
    <fieldset>
      <table class="datalist__table table" width="100%" data-products="brand">
        <thead>
          <tr>
            <th class="products__id">Nombre Resumen</th>
            <th  class="products__title">Fecha de Emisión</th>
            <th  class="products__title">Estado</th>
            <th  class="products__title">Cod. Error</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th  class="products__title">XML</th>
            <th  class="products__title">CDR</th>
          </tr>
        </thead>
        <tbody>
          <!-- ko foreach : ResumenesDiarioConsulta -->
          <tr class="clickable-row" data-bind="attr : { id: IdResumenDiario }, event:{click: Seleccionar}" style="text-transform: UpperCase;">
            <td data-bind="text: NombreResumenDiario"></td>
            <td data-bind="text: FechaFormateada"></td>
            <td><span data-bind="text: EstadoCE"></span></td>
            <td data-bind="text: IndicadorEstadoResumenDiario() != ESTADO_CPE.ACEPTADO ? CodigoError : '', attr: { 'title': CodigoError() != 0 ? DescripcionError() : '' }"></td>
            <td>
              <span class="fa fa-fw" style="font-size: 2em" data-bind="css: Icono, style:{color: Color}"></span>
            </td>
            <td align="center" >
              <button data-bind="attr : { id : IdResumenDiario() + '_btnEditar' }, event:{click: VerFacturas} "
              class="btn btn-default btn-operaciones" data-toogle="tooltip" title="Ver Documentos">
              <span class="fa fa-fw fa-eye"></span>
              </button>
            </td>
            <td align="center" >
              <button data-bind="attr : { id : IdResumenDiario() + '_btnDescargarXML', name : '<?php echo site_url();?>/FacturacionElectronica/cResumenDiario/DescargarXML?nombre=' +  NombreResumenDiario(), value: NombreResumenDiario()} "
                class="btn btn-warning DescargaXML btn-operaciones" data-toogle="tooltip" title="Descargar XML">
                <span class="far fa-file-code fa-lg"></span>
              </button>
            </td>
            <td align="center" >
              <div data-bind="css: VistaCDR">
                <button data-bind="attr : { id : IdResumenDiario() + '_btnDescargarCDR', name : '<?php echo site_url();?>/FacturacionElectronica/cResumenDiario/DescargarCDR?nombre=' +  NombreResumenDiario(), value: NombreResumenDiario()} "
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
