<form class="form products-new" enctype="multipart/form-data" id="formEmpleado" name="formEmpleado" action="" method="post">
  <div class="container-fluid">
    <input type="hidden" name="IdEmpleado" id="IdEmpleado" data-bind="value : IdEmpleado">
    <input type="hidden" name="IdPersona" id="IdPersona" data-bind="value : IdPersona">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Tipo Documento <strong class="alert-info">(*)</strong></div>
            <select id="combo-tipodocumentoIdentidad" class="form-control formulario" data-bind="
                      value : IdTipoDocumentoIdentidad,
                      options : $root.data.TiposDocumentoIdentidad,
                      optionsValue : 'IdTipoDocumentoIdentidad' ,
                      optionsText : 'NombreAbreviado',
                      event:{focus: OnFocus, change: OnChangeTipoDocumentoIdentidad, keydown: OnKeyEnter }">
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <form class="form products-new" action="" method="post">
              <div class="input-group-addon formulario">Nº Documento <strong class="alert-info">(*)</strong></div>
              <input id="NumeroDocumentoIdentidad" class="form-control formulario" type="text" data-bind="value: NumeroDocumentoIdentidad, valueUpdate : 'keyup', event: {focus: OnFocus, keydown: OnKeyEnter }" data-validation="validacion_numero_documento" data-validation-error-msg="Ingrese el numero de documento correcto">
              <div class="input-group-btn">
                <button tabindex="-1" type="button" class="btn-busqueda btn focus-control no-tab" id="btn-busqueda" data-bind="click : OnClickBtnBusqueda">
              </div>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Tipo Persona <strong class="alert-info">(*)</strong></div>
            <select id="combo-tipopersona" class="form-control formulario" data-bind="
                      value : IdTipoPersona,
                      options : $root.data.TiposPersona,
                      optionsValue : 'IdTipoPersona' ,
                      optionsText : 'NombreTipoPersona',
                      event:{change:ChangeTipoPersona, focus: OnFocus, keydown: OnKeyEnter}">
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Rol en la empresa <strong class="alert-info">(*)</strong></div>
            <select id="combo-rol" class="form-control formulario" data-bind="
                      value : IdRol,
                      options : $root.data.Roles,
                      optionsValue : 'IdRol' ,
                      optionsText : 'NombreRol',
                      event:{focus: OnFocus, keydown: OnKeyEnter} ">
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Sede <strong class="alert-info">(*)</strong></div>
            <select id="combo-sede" class="form-control formulario" data-bind="
                      value : IdSede,
                      options : $root.data.Sedes,
                      optionsValue : 'IdSede' ,
                      optionsText : 'NombreSede',
                      event:{focus: OnFocus, keydown: OnKeyEnter} ">
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Razón Social <strong class="alert-info">(*)</strong></div>
            <input id="RazonSocial" class="form-control formulario" type="text" data-bind="value: RazonSocial, event:{focus: OnFocus, keydown: OnKeyEnter}">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Nombres <strong class="alert-info">(*)</strong></div>
            <input id="NombreCompleto" class="form-control formulario" type="text" data-bind="value: NombreCompleto, event:{change:ChangeRazonSocial, focus: OnFocus, keydown: OnKeyEnter }">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Apellidos <strong class="alert-info">(*)</strong></div>
            <input id="ApellidoCompleto" class="form-control formulario" type="text" data-bind="value: ApellidoCompleto, event:{change:ChangeRazonSocial, focus: OnFocus, keydown: OnKeyEnter}">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario"> Direccion</div>
            <input class="form-control formulario" type="text" data-bind="value: Direccion, event:{focus: OnFocus, keydown: OnKeyEnter}">
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Nombre Comercial</div>
            <input class="form-control formulario" type="text" data-bind="value: NombreComercial, event:{focus: OnFocus, keydown: OnKeyEnter}">
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Represent. Legal</div>
            <input class="form-control formulario" type="text" data-bind="value: RepresentanteLegal, event:{focus: OnFocus, keydown: OnKeyEnter}">
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Correo Electronico</div>
            <input class="form-control formulario" type="text" data-bind="value: Email, event:{focus: OnFocus, keydown: OnKeyEnter}">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Celular</div>
            <input class="form-control formulario" type="text" data-bind="value: Celular, event:{focus: OnFocus, keydown: OnKeyEnter}">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Telefono</div>
            <input class="form-control formulario" type="text" data-bind="value: TelefonoFijo, event:{focus: OnFocus, keydown: OnKeyEnter}">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Fecha Ingreso</div>
            <input class="form-control formulario fecha" type="text" data-bind="value: FechaIngreso, event:{focus: OnFocus, keydown: OnKeyEnter}">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Sueldo</div>
            <input class="form-control formulario" type="text" data-bind="value: Sueldo, event:{focus: OnFocus, keydown: OnKeyEnter}">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <textarea class="form-control formulario" rows="2" placeholder="Observaciones" data-bind="value: Observacion, event:{focus: OnFocus, keydown: OnKeyEnter}"></textarea>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="checkbox checkbox-inline">
          <input id="IndicadorEstadoEmpleado" type="checkbox" class="no-tab" data-bind="checked : IndicadorEstadoEmpleado">
          <label for="IndicadorEstadoEmpleado">Estado Empleado</label>
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
    <div class="row">
      <div class="col-md-12">
        <center>
          <br>
          <div class="form-group">
            <div class="">
              <a data-toogle="modal" data-bind="click : AbrirPreview">
                <!--  data-bind="click : AbrirPreview"  -->
                <img src="" width="110" height="110" class="img-rounded foto" id="img_FileFoto">
              </a>
              <input type="hidden" id="InputFileName" name="InputFileName" value="FileFoto">
            </div>
            <div tabindex="500" class="btn btn-default btn-file">
              <span class="hidden-xs glyphicon glyphicon-folder-open"></span> &nbsp <label> Foto</label>
              <input class="formulario" type="file" id="FileFoto" name="FileFoto" data-bind="event : { change : OnChangeInputFile }" /><!-- data-bind="event : { change : OnChangeInputFile }" -->
            </div>
          </div>
        </center>
      </div>
    </div>

    <div class="row">
      <center>
        <button id="btn_GrabarEmpleado" type="button" class="btn btn-success focus-control" data-bind="click : Guardar">Grabar</button> &nbsp;
        <button id="btn_LimpiarEmpleado" type="button" class="btn btn-default focus-control" data-bind="click : Deshacer">Deshacer</button> &nbsp;
        <button id="btn_CerrarEmpleado" type="button" class="btn btn-default focus-control" data-bind="event:{click: Cerrar}">Cerrar</button>
      </center>
    </div>
    <div class="col-md-12">
      <strong class="alert-info">* Grabar = ALT + G</strong>
    </div>
  </div>
</form>