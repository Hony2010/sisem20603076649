<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="FormularioMercaderia">
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
          <form id="formMercaderiaInventarioInicial" class="" action="" method="post">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Código</div>
                      <input id="CodigoMercaderia" type="text" class="form-control formulario no-tab" data-bind="value:CodigoMercaderia, event : { focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
                <!-- <div class="col-md-2">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="checkbox">
                        <input name="CheckNumeroDocumento" id="CheckCodigoMercaderia" type="checkbox" class="form-control formulario" data-bind="checked : CodigoAutomatico, event: { change : OnChangeCheckNumeroDocumento , focus : OnFocus , keydown : OnKeyEnter}">
                        <label for="CheckNumeroDocumento">Editar</label>
                      </div>
                    </div>
                  </div>
                </div> -->
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Unidad de Med  </div>
                      <select id="combo-unidadmedida" class="form-control formulario" data-bind="
                              value : IdUnidadMedida,
                              options : UnidadesMedida,
                              optionsValue : 'IdUnidadMedida' ,
                              optionsText : 'NombreUnidadMedida',
                              event : {focus : OnFocus, keydown : OnKeyEnter}" >
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Descripción</div>
                      <input type="text" class="form-control formulario"  data-bind="value : NombreProducto, event : { focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
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
                        <input id="radioExoneradoIGV" type="radio" name="radio"  class="no-tab" value="2" data-bind="checked : IdTipoAfectacionIGV, event : { change : OnChangeTipoAfectacionIGV }">
                        <label for="radioExoneradoIGV">Exonerado IGV</label>
                      </div>
                      <div class="radio radio-inline">
                        <input id="radioInafectoIGV" type="radio" name="radio"  class="no-tab" value="3" data-bind="checked : IdTipoAfectacionIGV, event : { change : OnChangeTipoAfectacionIGV }">
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
                              event : { focus : OnFocus, keydown : OnKeyEnter, change : OnChangeTipoSistemaISC }" >
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Almacen</div>
                      <input disabled class="form-control formulario no-tab" type="text" data-bind="value: NombreSede, event : {focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Fecha Inventario</div>
                      <input disabled id="FechaInventario" class="form-control formulario no-tab" type="text" data-bind="value: FechaInicial, event : {focus : OnFocus, keydown : OnKeyEnter}"
                      data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">Motivo Inv.</div>
                      <select id="combo-motivoinventario" class="form-control formulario" data-bind="
                              value : IdMotivoInventarioInicial,
                              options : $root.data.MotivosInventario,
                              optionsValue : 'IdMotivoInventarioInicial' ,
                              optionsText : 'NombreMotivoInventarioInicial',
                              event : {focus : OnFocus, keydown : OnKeyEnter}">
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">Cantidad</div>
                      <input id="CantidadInicial" class="form-control formulario" type="text" data-bind="value: CantidadInicial, event : {focus : OnFocus, keydown : OnKeyEnter}, numbertrim : CantidadInicial"
                      data-validation="number_desc" data-validation-error-msg="Debe ser Numerico">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">Valor Unitario</div>
                      <input id="Descripcion" class="form-control formulario" type="text" data-bind="value: ValorUnitario, event : {focus : OnFocus, keydown : OnKeyEnter}, numbertrim : ValorUnitario"
                      data-validation="number_desc" data-validation-error-msg="Debe ser Numerico">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div id="GrupoDocumentos" class="col-md-12">
                  <div class="radio radio-inline">
                    <input id="rad1" type="radio" name="radio3" class="no-tab" value="1" data-bind="checked : IdOrigenMercaderia, event : { change : $root.OnChangeIdOrigenMercaderia }">
                    <label for="rad1">General</label>
                  </div>
                  <!-- ko if: $root.data.InventarioInicial.ParametroDua() != 0 -->
                  <div class="radio radio-inline">
                    <input id="rad2" type="radio" name="radio3" class="no-tab" value="2" data-bind="checked : IdOrigenMercaderia, event : { change : $root.OnChangeIdOrigenMercaderia }">
                    <label for="rad2">DUA</label>
                  </div>
                  <!-- /ko -->
                  <!-- ko if: $root.data.InventarioInicial.ParametroDocumentoSalidaZofra() != 0 -->
                  <div class="radio radio-inline">
                    <input id="rad3" type="radio" name="radio3" class="no-tab" value="3" data-bind="checked : IdOrigenMercaderia, event : { change : $root.OnChangeIdOrigenMercaderia }">
                    <label for="rad3">Documento Salida Zofra</label>
                  </div>
                  <!-- /ko -->
                </div>
              </div>
              <div class="row">
                <!-- ko if: $root.data.InventarioInicial.ParametroLote() != 0 -->
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Numero Lote</div>
                      <input id="NumeroLote" class="form-control formulario" type="text" data-bind="value: NumeroLote, event : {focus : OnFocus, keydown : OnKeyEnter}"
                      data-validation="required" data-validation-error-msg="Este campo es requerido">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Fecha Vencimiento</div>
                      <input id="FechaVencimiento" class="form-control formulario" type="text" data-bind="value: FechaVencimiento, event : {focus : OnFocus, keydown : OnKeyEnter}"
                      data-inputmask-clearmaskonlostfocus="false" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida">
                    </div>
                  </div>
                </div>
                <!-- /ko -->
                <!-- ko if: $root.data.InventarioInicial.ParametroDocumentoSalidaZofra() == 1 && IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA -->
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Documento Zofra</div>
                      <input id="NumeroDocumentoSalidaZofra" class="form-control formulario" type="text" data-bind="value: NumeroDocumentoSalidaZofra, event : {focus : OnFocus, keydown : OnKeyEnter}"
                      data-validation="required" data-validation-error-msg="Este campo es requerido">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Fecha Documento Zofra</div>
                      <input id="FechaEmisionDocumentoSalidaZofra" class="form-control formulario" type="text" data-bind="value: FechaEmisionDocumentoSalidaZofra, event : {focus : OnFocus, keydown : OnKeyEnter}"
                      data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida">
                    </div>
                  </div>
                </div>
                <!-- /ko -->
                <!-- ko if: $root.data.InventarioInicial.ParametroDua() == 1 && IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA -->
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Numero Dua</div>
                      <input id="NumeroDua" class="form-control formulario" type="text" data-bind="value: NumeroDua, event : {focus : OnFocus, keydown : OnKeyEnter}"
                      data-validation="required" data-validation-error-msg="Este campo es requerido">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Fecha Dua</div>
                      <input id="FechaEmisionDua" class="form-control formulario" type="text" data-bind="value: FechaEmisionDua, event : {focus : OnFocus, keydown : OnKeyEnter}"
                      data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Numero Item</div>
                      <input id="NumeroItemDua" class="form-control formulario" type="text" data-bind="value: NumeroItemDua, event : {focus : OnFocus, keydown : OnKeyEnter}"
                      data-validation="number" data-validation-error-msg="Este campo es numerico">
                    </div>
                  </div>
                </div>
                <!-- /ko -->
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Observacion</div>
                      <input id="Observacion" class="form-control formulario" type="text" data-bind="value: Observacion, event : {focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <center>
                  <br>
                  <button type="button" id="BtnGrabar" class="btn btn btn-success focus-control" data-bind="event : {click : $root.NuevaMercaderia}">Grabar</button> &nbsp;
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
