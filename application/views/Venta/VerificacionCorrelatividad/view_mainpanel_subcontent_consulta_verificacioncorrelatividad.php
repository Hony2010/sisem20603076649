<!-- ko with : $root.data.Buscador  -->
  <?php echo $view_subcontent_buscador_verificacioncorrelatividad; ?>
<!-- /ko -->
<br>
<fieldset>
    <!-- ko with : VerificacionCorrelatividad -->
      <table class="datalist__table table" width="100%" data-products="brand">
        <thead>
          <tr>
            <th style="text-align: left;">
              <div >
                <input id="SelectorTodo" class="input-checkbox" type="checkbox" data-bind="checked: SelectorTodo , event : {change : SeleccionarTodos }">
                <label class="label-checkbox">Todo</label>
              </div>
            </th>
            <th>Tipo Documento</th>
            <th>Serie Documento</th>
          </tr>
        </thead>
        <tbody>
          <!-- ko foreach : DetallesVerificacionCorrelatividad -->
          <tr class="clickable-row" data-bind="attr : { id: IdCorrelativoDocumento()+'_tr' }" style="text-transform: UpperCase;">
            <td>
              <input type="checkbox" class="input-checkbox" data-bind="checked: IndicadorEstadoCheck, event : {change : RadioBtnSeleccionar}">
            </td>
            <td data-bind="text: NombreAbreviado"></td>
            <td data-bind="text: SerieDocumento"></td>
          </tr>
          <!-- /ko -->
        </tbody>
      </table>
    <br />
    <button id="btnVerificar" disabled class="btn btn-primary" data-bind="event:{click : OnClickBtnVerificacionCorrelatividad}">Verificar Correlatividad</button>
  <!-- /ko -->
</fieldset>
<br>
