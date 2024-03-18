<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx" id="principal">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <?php echo $view_subcontent_preview_usuario; ?>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Usuarios &nbsp; <button id="btnNuevo" class="btn btn-info" type="button" data-bind="click : $root.NuevoUsuario"><u>N</u>uevo</button></h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                    <div class="tab-pane active" id="brand" role="tabpanel">
                      <?php echo $view_subcontent_consulta_usuarios; ?>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /ko -->

<!-- ko with : $root.data.Usuario  -->
<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalUsuario">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="event:{click: Cerrar}"><span aria-hidden="true">&times;</span></button>
                <h4 class="panel-title"><span data-bind="text: $root.MostrarTitulo()">REGISTRO DE USUARIO</span></h4>
            </div>
            <div class="modal-body">
              <?php echo $view_subcontent_form_usuario; ?>
           </div>
      </div>
    </div>
</div>
<!-- /ko -->

<!-- ko with : $root.data.Empleado -->
<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalEmpleado">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="event:{click: Cerrar}"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <?php echo $view_subcontent_form_empleado; ?>
      </div>
        <!--<center>
            <img src="" width="60%" height="60%" id="foto_previa" name="foto_previa">
        </center>-->
    </div>
  </div>
</div>
<!-- /ko -->

<!-- ko with : $root.data -->
<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalAccesoRol">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <?php //echo $view_subcontent_form_accesosistema; ?>

        <?php echo $view_subcontent_form_accesorol; ?>
      </div>
        <!--<center>
            <img src="" width="60%" height="60%" id="foto_previa" name="foto_previa">
        </center>-->
    </div>
  </div>
</div>

<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalAccesoUsuario">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <?php echo $view_subcontent_form_accesousuario; ?>
      </div>
        <!--<center>
            <img src="" width="60%" height="60%" id="foto_previa" name="foto_previa">
        </center>-->
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
