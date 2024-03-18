<!-- ko with : Vehiculo  -->
<form class="form products-new" enctype="multipart/form-data" id="formVehiculo" name="formVehiculo" role="form" autocomplete="off">
  <!-- action="" method="post" -->
  <div class="container-fluid">
    <input type="hidden" name="IdVehiculo" id="IdVehiculo" data-bind="value : IdVehiculo">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Radio Taxi <strong class="alert-info">(*)</strong></div>
            <select id="combo-radiotaxi" class="form-control formulario" data-bind="
                    value : IdRadioTaxiActual,
                    options : $parent.RadiosTaxi,
                    optionsValue : 'IdRadioTaxi' ,
                    optionsText : 'NombreRadioTaxi',
                    event: { focus : OnFocus, keydown : OnKeyEnter} ">
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Nº Placa <strong class="alert-info">(*)</strong></div>
            <input id="NumeroPlaca" class="form-control formulario" type="text" data-bind="value: NumeroPlaca, valueUpdate : 'keyup', event : {  focus : OnFocus }" data-validation="alphanumeric" data-validation-error-msg="Solo números y letras.">
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Ultimo Kilometraje <strong class="alert-info">(*)</strong></div>
            <input id="NumeroPlaca" class="form-control formulario" type="text" data-bind="value: UltimoKilometraje, event : {  focus : OnFocus }, numbertrim: UltimoKilometraje">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <strong class="alert-info">(*) Campos Obligatorios</strong>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <center>
      <button type="button" id="BtnGrabar" class="btn btn btn-success focus-control" data-bind="click : OnClickBtnGrabar">Grabar</button> &nbsp;
      <button type="button" id="BtnLimpiar" class="btn btn-default focus-control" data-bind="click : OnClickBtnLimpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
      <button type="button" id="BtnDeshacer" class="btn btn-default focus-control" data-bind="click : OnClickBtnDeshacer,visible : opcionProceso() == 2">Deshacer</button> &nbsp;
      <button type="button" id="BtnCerrar" class="btn btn-default focus-control" data-bind="click : OnClickBtnCerrar">Cerrar</button>
    </center>
  </div>
  <div class="col-md-12">
    <strong class="alert-info">* Grabar = ALT + G</strong>
  </div>
</form>
<!-- /ko -->