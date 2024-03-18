<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="FormularioMercaderia" data-backdrop='static'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="panel-title">REGISTRO DE PRODUCTO</h4>
      </div>
      <div class="modal-body">
        <br>
        <div class="container-fluid">
          <!-- ko with : Mercaderia  -->
          <form class="" action="" method="post">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Código <strong class="alert-info">(*)</strong></div>
                      <input id="CodigoMercaderia" type="text" class="form-control formulario no-tab" data-bind="value:CodigoMercaderia, event : { focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="checkbox">
                        <input name="CheckNumeroDocumento" id="CheckCodigoMercaderia" type="checkbox" class="form-control formulario" data-bind="checked : CodigoAutomatico, event: { change : OnChangeCheckNumeroDocumento , focus : OnFocus , keydown : OnKeyEnter}">
                        <label for="CheckNumeroDocumento">Editar</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Unidad de Med <strong class="alert-info">(*)</strong></div>
                      <select id="combo-unidadmedida" class="form-control formulario" data-bind="
                              value : IdUnidadMedida,
                              options : UnidadesMedida,
                              optionsValue : 'IdUnidadMedida' ,
                              optionsText : 'NombreUnidadMedida',
                              event : {focus : OnFocus, keydown : OnKeyEnter}">
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="" data-bind="css: ParametroRestaurante() != 0 ? 'col-md-8' : 'col-md-12' ">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Descripción <strong class="alert-info">(*)</strong></div>
                      <input type="text" class="form-control formulario" data-bind="value : NombreProducto, event : { focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
                <!-- ko if: ParametroRestaurante() != 0 -->
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="multiselect-native-select formulario">
                        <button type="button" class="multiselect dropdown-toggle btn btn-default btn-control" data-toggle="dropdown">
                          <span class="multiselect-selected-text">Agregados</span><span id="numero_items_anotacionesplato" class="badge" data-bind="text: TotalAnotacionesPlatoSeleccionados"></span>
                          <b class="caret"></b>
                        </button>
                        <ul class="multiselect-container dropdown-menu">
                          <li>
                            <div class="checkbox">
                              <input id="selector_anotacionesplato_todos" type="checkbox" data-bind="checked: SeleccionarTodosAnotacionesPlato, event: {change: ChangeTodosAnotacionesPlato}" />
                              <label class="checkbox" for="selector_anotacionesplato_todos"> Seleccionar Todos</label>
                            </div>
                          </li>
                          <!-- ko foreach: AnotacionesPlatoProducto -->
                          <li>
                            <div class="checkbox">
                              <input type="checkbox" data-bind="checked: Seleccionado, event: {change: $parent.CambioAnotacionesPlato}, attr : { id: IdAnotacionPlato() +'_anotacionesplato' }" />
                              <label class="checkbox" data-bind="text: NombreAnotacionPlato(), attr:{ for : IdAnotacionPlato() +'_anotacionesplato'}"></label>
                            </div>
                          </li>
                          <!-- /ko -->
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /ko -->
              </div>
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Orig. Mercad. <strong class="alert-info">(*)</strong></div>
                      <select id="combo-origenmercaderia" class="form-control formulario" data-bind="
                              value : IdOrigenMercaderia,
                              options : OrigenMercaderia,
                              optionsValue : 'IdOrigenMercaderia' ,
                              optionsText : 'NombreOrigenMercaderia',
                              event : {focus : OnFocus, keydown : OnKeyEnter}">
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Precio Unit. Venta</div>
                    <input class="form-control formulario" type="text" data-bind="value: PrecioUnitario, event : {focus : OnFocus, keydown : OnKeyEnter}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <fieldset>
                      <div class="radio radio-inline">
                        <input id="radioAfectoIGV" type="radio" name="radio" class="no-tab" value="1" data-bind="checked : IdTipoAfectacionIGV, event : { change : OnChangeTipoAfectacionIGV }">
                        <label for="radioAfectoIGV">Afecto IGV</label>
                      </div>
                      <div class="radio radio-inline">
                        <input id="radioExoneradoIGV" type="radio" name="radio" class="no-tab" value="2" data-bind="checked : IdTipoAfectacionIGV, event : { change : OnChangeTipoAfectacionIGV }">
                        <label for="radioExoneradoIGV">Exonerado IGV</label>
                      </div>
                      <div class="radio radio-inline">
                        <input id="radioInafectoIGV" type="radio" name="radio" class="no-tab" value="3" data-bind="checked : IdTipoAfectacionIGV, event : { change : OnChangeTipoAfectacionIGV }">
                        <label for="radioInafectoIGV">Inafecto IGV</label>
                      </div>
                    </fieldset>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Tipo Sistema Calculo ISC</div>
                      <select id="combo-tiposistemaisc" class="form-control formulario" data-bind="
                              value : IdTipoSistemaISC,
                              options : TiposSistemaISC,
                              optionsValue : 'IdTipoSistemaISC' ,
                              optionsText : 'NombreTipoSistemaISC',
                              event : { focus : OnFocus, keydown : OnKeyEnter, change : OnChangeTipoSistemaISC }">
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="checkbox checkbox-inline">
                    <input id="indicadoricbper" type="checkbox" class="no-tab" data-bind="checked : IndicadorAfectoICBPER">
                    <label for="indicadoricbper">Afecto ICBPER</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <center>
                  <br>
                  <button type="button" id="BtnGrabar" class="btn btn btn-success focus-control" data-bind="event : {click : InsertarNuevaMercaderia}">Grabar</button> &nbsp;
                  <button type="button" id="BtnCerrar" class="btn btn-default focus-control" data-bind="event : {click : OnClickBtnCerrar}">Cerrar</button>
                </center>
              </div>
            </div>
          </form>
          <!-- /ko -->
        </div>
      </div>
    </div>
  </div>
</div>
