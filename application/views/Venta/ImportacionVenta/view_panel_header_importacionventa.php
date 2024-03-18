  <div class="col-md-12">
    <fieldset>
      <div class="col-md-9">
        <div tabindex="500" style="border-radius: 4px; padding-top: 4px;" class="btn btn-primary btn-file focus-control btn-control">
          <label>Cargar Archivo</label>
          <input class="formulario" type="file" id="CargarJSON" name="CargarJSON" data-bind="event : { change : $root.CargarJSON }">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <b>
            <label for="" style="font-size:13px; color: green; font-weight: bolder;" data-bind="visible: $root.dataJSON() != undefined"> <span class="fa fa-check"></span> Se cargo correctamente.</label>
          </b>
        </div>
      </div>
    </fieldset>
  </div>
