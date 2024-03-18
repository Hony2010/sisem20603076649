<!-- ko with : data -->
<!-- ko with : GeneracionJSON -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Restaurar Base</h3>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-4">
              <fieldset>
                <!-- <legend>Restaurar Base</legend> -->
                <div class="col-md-6">
                  <div tabindex="500" style="border-radius: 4px; padding-top: 4px;" class="btn btn-primary btn-file focus-control btn-control">
                    <label>Importar Archivo</label>
                    <input class="formulario" type="file" id="ParseExcel" name="FileFoto" data-bind="event : {change  : $root.OnClickBtnCargarArchivo}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" readonly class="form-control formulario" data-bind="value:NombreArchivo">
                  </div>
                </div>
                <div class="col-md-12">
                  <button type="button" class="btn btn-default btn-control" name="button" data-bind="event : {click : $root.OnClickBtnRestaurarBase}">Restaurar Base</button>
                </div>
              </fieldset>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /ko -->
<!-- /ko -->
