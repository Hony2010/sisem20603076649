  <div class="container-fluid">
    <input type="hidden" name="IdUsuario" id="IdUsuario" data-bind="value : IdUsuario">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Nombre Usuario</i></div>
            <input id="NombreUsuario" class="form-control formulario" type="text" data-bind="value: NombreUsuario">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Clave Usuario</div>
            <input id="ClaveUsuario" class="form-control formulario" type="password" data-bind="value: ClaveUsuario">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Confirmar Clave</div>
            <input id="ConfirmarClaveUsuario" class="form-control formulario" type="password" data-bind="value: ConfirmarClaveUsuario">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Alias Usuario Venta</div>
            <input id="AliasUsuarioVenta" class="form-control formulario" type="text" data-bind="value: AliasUsuarioVenta">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon formulario">Nombre Empleado</div>
              <input id="IdPersona" class="form-control formulario" type="hidden" placeholder="Buscar Usuario..." data-bind="value: IdPersona">
              <input id="NombreEmpleado" class="form-control formulario" type="text" data-bind="">
              <div class="input-group-btn">
                <button class="btn-buscar btn btn-default" data-bind="event:{click: AgregarEmpleado}"><i class="fa fa-search"></i></button>
              </div>
            </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Pregunta Seguridad</div>
                  <select id="combo-preguntaseguridad" class="form-control formulario" data-bind= "
                          value : IdPreguntaSeguridad,
                          options : $root.data.PreguntasSeguridad,
                          optionsValue : 'IdPreguntaSeguridad' ,
                          optionsText : 'Pregunta' ">
                  </select>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Respuesta Seguridad</div>
            <input class="form-control formulario" type="password" data-bind="value: RespuestaSeguridad">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Confirmar Respuesta</div>
            <input class="form-control formulario" type="password" data-bind="value: ConfirmarRespuestaSeguridad">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <center>
        <br>
        <button id="btn_GrabarUsuario" class="btn btn-default" data-bind="click : Guardar">Grabar</button> &nbsp;
        <button id="btn_LimpiarUsuario" class="btn btn-default" data-bind="click : Deshacer">Deshacer</button> &nbsp;
        <button id="btn_CerrarUsuario" class="btn btn-default" data-bind="click : Cerrar">Cerrar</button>
      </center>
    </div>
  </div>
