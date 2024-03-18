<!-- ko with : Cliente  -->
<form class="form products-new" enctype="multipart/form-data" id="formcliente" name="formcliente" role="form" autocomplete="off">
  <!-- action="" method="post" -->
  <div class="container-fluid">
    <input type="hidden" name="IdPersona" id="IdPersona" data-bind="value : IdPersona">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Tipo Documento <strong class="alert-info">(*)</strong></div>
            <select id="combo-tipodocumentoIdentidad" class="form-control formulario" data-bind="
                    value : IdTipoDocumentoIdentidad,
                    options : TiposDocumentoIdentidad,
                    optionsValue : 'IdTipoDocumentoIdentidad' ,
                    optionsText : 'NombreAbreviado',
                    event: { focus : OnFocus ,change : OnChangeTipoDocumentoIdentidad, keydown : OnKeyEnter} ">
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario" disabled>Estado y Condición</div>
            <input class="form-control formulario no-tab" type="text" disabled data-bind="value: EstadoContribuyente()+' - '+ CondicionContribuyente()" tabindex="-1">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Nº Documento <strong class="alert-info">(*)</strong></div>
            <input id="NumeroDocumentoIdentidad" class="form-control formulario" type="text" data-bind="value: NumeroDocumentoIdentidad, valueUpdate : 'keyup', event : {  focus : OnFocus , focusout : OnFocusOutNumeroDocumentoIdentidad , keydown : function(data,event) {return OnKeyEnterNumeroDocumentoIdentidad(data,event,OnKeyEnter);} }" data-validation="validacion_numero_documento" data-validation-error-msg="Ingrese el numero de documento correcto">
            <div class="input-group-btn">
              <button tabindex="-1" type="button" class="btn-busqueda btn focus-control no-tab" id="BtnBusqueda" data-bind="click : OnClickBtnBusqueda"></button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Tipo Persona <strong class="alert-info">(*)</strong></div>
            <select id="combo-tipopersona" class="form-control formulario" data-bind="
                    value : IdTipoPersona,
                    options : TiposPersona,
                    optionsValue : 'IdTipoPersona' ,
                    optionsText : 'NombreTipoPersona',
                    event:{ focus : OnFocus , change: OnChangeTipoPersona , keydown : OnKeyEnter} ">
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
            <input id="RazonSocial" class="form-control formulario" type="text" data-bind="value: RazonSocial, event:{focus : OnFocus , keydown : OnKeyEnter}">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Nombres <strong class="alert-info">(*)</strong></div>
            <input id="NombreCompleto" class="form-control formulario no-tab" type="text" data-bind="value: NombreCompleto, event:{focus : OnFocus ,change: OnChangeNombreCompleto, keydown : OnKeyEnter}" data-validation="required" data-validation-error-msg="Ingrese los nombres" data-validation-optional="true">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Apellidos <strong class="alert-info">(*)</strong></div>
            <input id="ApellidoCompleto" class="form-control formulario no-tab" type="text" data-bind="value: ApellidoCompleto, event:{focus : OnFocus , change: OnChangeApellidoCompleto, keydown : OnKeyEnter}" data-validation="required" data-validation-error-msg="Ingrese los apellidos" data-validation-optional="true">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- ko if: Parametros.DireccionParametroCliente() == 1 -->
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario"> Dirección</div>
            <!-- <input class="form-control formulario" type="text" data-bind="value: Direccion,  event:{focus : OnFocus , keydown : OnKeyEnter} "> -->
            <select id="combo-direcciones" class="form-control formulario" data-bind="
                    options : DireccionesCliente,
                    optionsValue : 'IdDireccionCliente' ,
                    optionsText : 'Direccion',
                    event:{ focus : OnFocus , keydown : OnKeyEnter}">
            </select>
            <div class="input-group-btn">
              <button type="button" class="btn btn-buscar focus-control no-tab" data-bind="event: { click: OnClickBtnAgregarDireccionesCliente }"> <i class="fas fa-plus"></i> </button>
            </div>
          </div>
        </div>
      </div>
      <!-- /ko -->
      <!-- ko if: Parametros.NombreComerciaParametroCliente() == 1 -->
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Nombre Comercial</div>
            <input class="form-control formulario" type="text" data-bind="value: NombreComercial,  event:{focus : OnFocus , keydown : OnKeyEnter} ">
          </div>
        </div>
      </div>
      <!-- /ko -->
      <!-- ko if: Parametros.RepresentanteLegalParametroCliente() == 1 -->
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Represent. Legal</div>
            <input class="form-control formulario" type="text" data-bind="value: RepresentanteLegal,  event:{focus : OnFocus , keydown : OnKeyEnter} ">
          </div>
        </div>
      </div>
      <!-- /ko -->
      <!-- ko if: Parametros.CorreoElectronicoParametroCliente() == 1 -->
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Correo Electronico</div>
            <input class="form-control formulario" type="text" data-bind="value: Email,  event:{focus : OnFocus , keydown : OnKeyEnter} ">
          </div>
        </div>
      </div>
      <!-- /ko -->
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Celular</div>
            <input class="form-control formulario" type="text" data-bind="value: Celular,  event:{focus : OnFocus , keydown : OnKeyEnter} ">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Telefono</div>
            <input class="form-control formulario" type="text" data-bind="value: TelefonoFijo,  event:{focus : OnFocus , keydown : OnKeyEnter} ">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Nombre Zona</div>
            <input class="form-control formulario" type="text" data-bind="value: NombreZona,  event:{focus : OnFocus , keydown : OnKeyEnter} ">
          </div>
        </div>
      </div>
      <!-- ko if: Parametros.ParametroRubroLubricante() == 1 -->
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario"> Numero Placa</div>
            <!--PARA LAS OPCIONES DATA-BIND  -->
            <select id="combo-direcciones" class="form-control formulario" data-bind="
                    options : VehiculosCliente,
                    optionsValue : 'IdVehiculoCliente' ,
                    optionsText : 'NumeroPlaca',
                    event:{ focus : OnFocus , keydown : OnKeyEnter}">
            </select>
            <div class="input-group-btn">
              <button type="button" class="btn btn-buscar focus-control no-tab" data-bind="event: { click: OnClickBtnAgregarVehiculoCliente }"> <i class="fas fa-plus"></i> </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- /ko -->
      <!-- ko if: Parametros.FechaNacimientoParametroCliente() == 1 -->
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Fecha Nacimiento</div>
            <input class="form-control formulario fecha" type="text" data-bind="value: FechaNacimiento,  event:{focus : OnFocus , keydown : OnKeyEnter} ">
          </div>
        </div>
      </div>
      <!-- /ko -->
      <!-- ko if: Parametros.ParametroAlumno() == 1 -->
      <div class="col-md-12">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Grado Alumno</div>
            <select id="combo-gradoalumno" class="form-control formulario" data-bind="
                    value : IdGradoAlumno,
                    options : GradosAlumno,
                    optionsValue : 'IdGradoAlumno' ,
                    optionsText : 'NombreGradoAlumno',
                    event:{ focus : OnFocus , keydown : OnKeyEnter, change: OnChangeGradoAlumno}">
            </select>
          </div>
        </div>
      </div>
      <!-- /ko -->
    </div>
    <!-- ko if: Parametros.ParametroRestaurante() != 0 -->
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="checkbox">
            <input id="IndicadorAfiliacionTarjeta" type="checkbox" class="form-control formulario" data-bind="checked: _IndicadorAfiliacionTarjeta , event: {focus : OnFocus , keydown : OnKeyEnter}">
            <label for="IndicadorAfiliacionTarjeta">Afiliacion de Tarjeta</label>
          </div>
        </div>
      </div>
      <div class="col-md-6" data-bind="visible: _IndicadorAfiliacionTarjeta">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Fecha Inicial </div>
            <input class="form-control formulario fecha" type="text" data-bind="value: FechaInicioAfiliacionTarjeta,  event:{focus : OnFocus , keydown : OnKeyEnter} " data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="Fecha invalida">
          </div>
        </div>
      </div>
      <div class="col-md-6" data-bind="visible: _IndicadorAfiliacionTarjeta">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Fecha Final </div>
            <input class="form-control formulario fecha" type="text" data-bind="value: FechaFinAfiliacionTarjeta,  event:{focus : OnFocus , keydown : OnKeyEnter}" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="Fecha invalida">
          </div>
        </div>
      </div>
    </div>
    <!-- /ko -->
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <textarea class="form-control formulario" rows="2" placeholder="Observaciones" data-bind="value: Observacion,  event:{focus : OnFocus} " style="height:auto !important;"></textarea>
        </div>
      </div>
    </div>
    <div class="row">
     <div class="col-md-12">
       <div class="checkbox checkbox-inline">
         <input id="IndicadorEstadoCliente" type="checkbox" class="no-tab" data-bind="checked : IndicadorEstadoCliente">
         <label for="IndicadorEstadoCliente">Estado Cliente</label>
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
              <a id="LnkFileFoto" data-toogle="modal" data-bind="click : OnClickLnkFileFoto">
                <img src="" width="110" height="110" class="img-rounded foto" id="img_FileFoto">
              </a>
              <input type="hidden" id="InputFileName" name="InputFileName" value="FileFoto">
            </div>
            <div tabindex="500" class="btn btn-default btn-file focus-control">
              <span class="hidden-xs glyphicon glyphicon-folder-open"></span> &nbsp <label> Foto</label>
              <input class="formulario" type="file" id="FileFoto" name="FileFoto" data-bind="event : { change : OnChangeFileFoto }" />
            </div>
          </div>
        </center>
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