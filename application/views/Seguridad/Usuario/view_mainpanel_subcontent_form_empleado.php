      <form class="form products-new" enctype="multipart/form-data" id="form" name="form" action="" method="post">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Tipo Documento</div>
                    <select id="combo-tipodocumentoIdentidad" class="form-control formulario" data-bind="
                            value : IdTipoDocumentoIdentidad,
                            options : $root.data.TiposDocumentoIdentidad,
                            optionsValue : 'IdTipoDocumentoIdentidad' ,
                            optionsText : 'NombreAbreviado'">
                    </select>
                    <input type="hidden" name="IdEmpleado" id="IdEmpleado" data-bind="value : IdEmpleado">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Nº Documento</div>
                    <input class="form-control formulario" type="text" data-bind="value: NumeroDocumentoIdentidad">
                    <div class="input-group-btn">
                      <button class="btn-buscar btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Tipo Persona</div>
                    <select id="combo-tipopersona" class="form-control formulario" data-bind="
                            value : IdTipoPersona,
                            options : $root.data.TiposPersona,
                            optionsValue : 'IdTipoPersona' ,
                            optionsText : 'NombreTipoPersona', event:{change:ChangeTipoPersona}">
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Cargo</div>
                    <select id="combo-rol" class="form-control formulario" data-bind="
                            value : IdRol,
                            options : $root.data.Roles,
                            optionsValue : 'IdRol' ,
                            optionsText : 'NombreRol' ">
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Sede</div>
                    <select id="combo-sede" class="form-control formulario" data-bind="
                            value : IdSede,
                            options : $root.data.Sedes,
                            optionsValue : 'IdSede' ,
                            optionsText : 'NombreSede' ">
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Razón Social</div>
                    <input id="RazonSocial" class="form-control formulario" type="text" data-bind="value: RazonSocial">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Nombres</div>
                    <input id="NombreCompleto" class="form-control formulario" type="text" data-bind="value: NombreCompleto, event:{change:ChangeRazonSocial}">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Apellidos</div>
                    <input id="ApellidoCompleto" class="form-control formulario" type="text" data-bind="value: ApellidoCompleto, event:{change:ChangeRazonSocial}">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario"> Direccion</div>
                    <input class="form-control formulario" type="text" data-bind="value: Direccion">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Nombre Comercial</div>
                    <input class="form-control formulario" type="text" data-bind="value: NombreComercial">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Representante Legal</div>
                    <input class="form-control formulario" type="text" data-bind="value: RepresentanteLegal">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Correo Electronico</div>
                    <input class="form-control formulario" type="text" data-bind="value: Email">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Celular</div>
                    <input class="form-control formulario" type="text" data-bind="value: Celular">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Telefono</div>
                    <input class="form-control formulario" type="text" data-bind="value: TelefonoFijo">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <textarea class="form-control formulario" rows="2" placeholder="Observaciones" data-bind="value: Observacion"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <center>
                <br>
                <div class="form-group">
                  <div class="">
                    <a data-toogle="modal" data-bind="click : AbrirPreview"><!--  data-bind="click : AbrirPreview"  -->
                      <img src="" width="110" height="110" class="img-rounded foto" id="img_FileFoto">
                    </a>
                    <input type="hidden" id="InputFileName" name="InputFileName" value="FileFoto">
                  </div>
                  <div tabindex="500" class="btn btn-default btn-file">
                    <span class="hidden-xs glyphicon glyphicon-folder-open"></span> &nbsp <label> Foto</label>
                    <input class="formulario" type="file" id="FileFoto" name="FileFoto" data-bind="event : { change : OnChangeInputFile }"
                    /><!-- data-bind="event : { change : OnChangeInputFile }" -->
                  </div>
                </div>
                </center>
              </div>
            </div>
          </div>
        <div class="row">
          <center>
            <button id="btn_GrabarEmpleado" class="btn btn-default" data-bind="click : Guardar">Grabar</button> &nbsp;
            <button id="btn_LimpiarEmpleado" class="btn btn-default" data-bind="click : Deshacer">Limpiar</button> &nbsp;
            <button id="btn_CerrarEmpleado" class="btn btn-default" data-bind="event:{click: Cerrar}">Cerrar</button>
          </center>
        </div>
      </form>
