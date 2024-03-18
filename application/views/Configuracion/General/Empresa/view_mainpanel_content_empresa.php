<!-- ko with : vmgEmpresa.dataEmpresa -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Empresa</h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
        <!-- ko with : Empresa -->
        <form class="form products-new" enctype="multipart/form-data" id="form" name="form" action="" method="post">
            <div class="col-md-9">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Código Empresa</div>
                      <input type="hidden" name="IdEmpresa" id="IdEmpresa"  data-bind="value: IdEmpresa">
                      <input id="CodigoEmpresa" class="form-control formulario" type="text" data-bind="value: CodigoEmpresa, event{focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Ubigeo</div>
                      <input id="Ubigeo" class="form-control formulario" type="text" data-bind="value: Ubigeo, event{focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Razón Social</div>
                      <input id="RazonSocial" class="form-control formulario" type="text" data-bind="value: RazonSocial, event{focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Nombre Comercial</div>
                      <input id="NombreComercial" class="form-control formulario" type="text" data-bind="value: NombreComercial, event{focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Representante Legal</div>
                      <input id="RepresentanteLegal" class="form-control formulario" type="text" data-bind="value: RepresentanteLegal, event{focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Domicilio Fiscal</div>
                      <input id="DomicilioFiscal" class="form-control formulario" type="text" data-bind="value: DomicilioFiscal, event{focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Giro Negocio</div>
                      <select id="combo-gironegocio" class="form-control formulario" data-bind="
                              value : IdGiroNegocio,
                              options : vistaModeloGeneral.vmgEmpresa.dataEmpresa.GirosNegocio,
                              optionsValue : 'IdGiroNegocio',
                              optionsText : 'NombreGiroNegocio',
                              event{focus : OnFocus, keydown : OnKeyEnter} ">
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Régimen Tributario</div>
                      <select id="combo-regimentributario" class="form-control formulario" data-bind="
                              value : IdRegimenTributario,
                              options : vistaModeloGeneral.vmgEmpresa.dataEmpresa.RegimenesTributario,
                              optionsValue : 'IdRegimenTributario' ,
                              optionsText : 'NombreRegimenTributario',
                              event{focus : OnFocus, keydown : OnKeyEnter}">
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Actividad Principal</div>
                      <input id="ActividadPrincipal" class="form-control formulario" type="text" data-bind="value: ActividadPrincipal, event{focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Actividad Secundaria</div>
                      <input id="ActividadSecundaria" class="form-control formulario" type="text" data-bind="value: ActividadSecundaria, event{focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Teléfono</div>
                      <input id="Telefono" class="form-control formulario" type="text" data-bind="value: Telefono, event{focus : OnFocus, keydown : OnKeyEnter}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Observación</div>
                      <input id="Observacion" class="form-control formulario" type="text" placeholder="Observacion" data-bind="value: Observacion, event{focus : OnFocus, keydown : OnKeyEnter}" style="height:auto;">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <fieldset>
                    <legend>Certificado Digital</legend>
                    <div class="col-md-9">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario">Ruta Certificado</div>
                          <div class="radiotxt"><span data-bind="text : RutaCertificado"></span> </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <input id = "Certificado" type="text" style="text-transform:none;" class="form-control formulario" placeholder="Nombre del Archivo" data-bind="value: NombreCertificado, event{focus : OnFocus, keydown : OnKeyEnter}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario">Contraseña</div>
                          <input type="password" style="text-transform:none;" class="form-control formulario" data-bind="value: ClavePrivadaCertificado, event{focus : OnFocus, keydown : OnKeyEnter}">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario">Fecha Inicio Certificado</div>
                          <!-- <div class="form-group radiotxt">20/12/2019</div> -->
                          <input class="form-control formulario fecha-reporte" type="text" data-bind="value: FechaInicioCertificadoDigital, event{focus : OnFocus, keydown : OnKeyEnter}">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario">Fecha Fin Certificado</div>
                          <!-- <div class="form-group radiotxt">20/12/2019</div> -->
                          <input class="form-control formulario fecha-reporte" type="text" data-bind="value: FechaFinCertificadoDigital, event{focus : OnFocus, keydown : OnKeyEnter}">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario">Usuario Secundario</div>
                          <input style="text-transform:none;" class="form-control formulario" type="text" data-bind="value: UsuarioSOL, event{focus : OnFocus, keydown : OnKeyEnter}">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario">Contraseña</div>
                          <input type="password" style="text-transform:none;" class="form-control formulario" data-bind="value: ClaveSOL, event{focus : OnFocus, keydown : OnKeyEnter}">
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="row">
                <div class="col-md-12">
                  <center>
                    <div class="form-group">
                      <div class="">
                        <a href="#" data-toogle="modal" data-bind="click : AbrirPreview"><!--  data-bind="click : AbrirPreview"  -->
                          <img src="" width="110" height="110" class="img-rounded foto" id="img_FileFoto">
                        </a>
                        <input type="hidden" id="InputFileName" name="InputFileName" value="FileFoto">
                      </div>
                      <div tabindex="500" class="btn btn-default btn-file">
                          <span class="hidden-xs glyphicon glyphicon-folder-open"></span> &nbsp <label> Foto</label>
                          <input type="file" id="FileFoto" name="FileFoto" data-bind="event : { change : OnChangeInputFile }"
                           /><!-- data-bind="event : { change : OnChangeInputFile }" -->
                      </div>
                    </div>
                  </center>
                </div>
              </div>
            </div>
            <div class="row">
              <center>
                <button type="button" class="btn btn-success focus-control no-tab" data-bind="click : vistaModeloGeneral.vmgEmpresa.Guardar">Grabar</button> &nbsp;
                <button type="button" class="btn btn-default focus-control no-tab" data-bind="click : vistaModeloGeneral.vmgEmpresa.Deshacer">Deshacer</button> &nbsp;
              </center>
            </div>
        </form>
        <!-- /ko -->
    </div>
  </div>
</div>
<!-- /ko -->

<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalPreview">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <center>
                    <img src="" width="60%" height="60%" id="foto_previa" name="foto_previa">
                </center>
            </div>
        </div>
    </div>
</div>
