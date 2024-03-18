<!-- ko with : Turno  -->
<form class="form products-new" enctype="multipart/form-data" id="formturno" name="formturno" role="form" autocomplete="off"><!-- action="" method="post" -->
  <div class="container-fluid">
    <input type="hidden" name="IdTurno" id="IdTurno" data-bind="value : IdTurno">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Nombre Turno <strong class="alert-info">(*)</strong></div>
            <input id="NombreTurno" class="form-control formulario" type="text" data-bind="value: NombreTurno, event : {  focus : OnFocus, keydown : OnKeyEnter, change : ValidarNombreTurno}"
            data-validation="required" data-validation-error-msg="Campo requerido">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <fieldset>
          <legend>Horario</legend>
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Hora Inicio  <strong class="alert-info">(*)</strong></div>
                    <input id="HoraInicio" class="form-control formulario hora" type="text" data-bind="value: HoraInicio, event:{focus : OnFocus, keydown : OnKeyEnter, change : ValidarHoraInicio}"
                    data-validation="time" data-validation-error-msg="Campo requerido">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Hora Fin  <strong class="alert-info">(*)</strong></div>
                    <input id="HoraFin" class="form-control formulario hora" type="text" data-bind="value: HoraFin, event:{focus : OnFocus, keydown : OnKeyEnter, change : ValidarHoraFinal}"
                    data-validation="time" data-validation-error-msg="Formato incorrecto">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Horas de Holgura  <strong class="alert-info">(*)</strong></div>
                    <input id="HorasHolgura" class="form-control formulario" type="text" data-bind="value: HorasHolgura, event:{ focus : OnFocus, keydown : OnKeyEnter }"
                    data-validation="required" data-validation-error-msg="Formato incorrecto">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
    <br>
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
