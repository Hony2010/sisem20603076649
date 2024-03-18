<!-- ko with : ImportacionMasiva -->
<form  id="formImportacionMasiva" name="formImportacionMasiva" role="form" autocomplete="off" >
  <div class="datalist__result">

    <div class="tab-pane active" id="brand" role="tabpanel">
      <div class="scrollable scrollbar-macosx">
        <div class="container-fluid">
          <div class="row">
            <fieldset>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Opciones</div>
                        <select name="combo-opciones" id="combo-opciones" class="form-control formulario" data-bind="
                          value : Opcion,
                          options : Opciones,
                          optionsValue : 'Opcion' ,
                          optionsText : 'Descripcion',
                          event : {change : OnChangeImportacion}">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div tabindex="500" style="border-radius: 4px; padding-top: 4px;" class="btn btn-primary btn-file focus-control btn-control">
                      <label>Excel</label>
                      <input class="formulario" type="file" id="ParseExcel" name="ParseExcel" data-bind="event : { change : $root.CargarExcel }">
                    </div>
                  </div>
                <div class="col-md-1">
                </div>
                <div class="col-md-3">
                  <span id="Descargar_Plantilla" class="link-plantilla" data-bind="event : {click : OnClickDescargarPlantilla}">Descargar plantilla Excel</span>
                </div>
              </div>
            </div>
          </fieldset>
          </div>
          <br>
          <div class="row">
              <div class="col-md-12">
                <div class="row detalle-comprobante">
                  <div class="col-md-12">
                    <fieldset>
                      <table class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaDetalleImportacionMasiva">
                        <thead>
                          <tr data-bind="template:{name: PlantillaCabecera()}">

                          </tr>
                        </thead>
                        <tbody data-bind="template:{name: PlantillaDetalle(), foreach: DetallesImportacionMasiva}">

                        </tbody>
                      </table>
                    </fieldset>
                  </div>
                </div>
              </div>
          </div>

          <div class="row">
            <center>
              <br>
              <button type="button" id="btn_Grabar" class="btn btn-success focus-control" data-bind="click : Guardar">Grabar</button> &nbsp;
              <br>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- /ko -->
