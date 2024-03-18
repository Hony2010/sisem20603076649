<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Roles</h3>
              </div>
              <div id="AccesoRolContent" class="panel-body">
                <div class="datalist__result">
                  <div class="row">
                    <div class="col-md-12">
                      Rol Empresa
                      <select id="combo-rolempresa" name="rolempresa" class="form-control formulario"
                      data-bind= "options : $root.data.Roles,
                      optionsValue : 'IdRol' ,
                      optionsText : 'NombreRol', event: {change: $root.ListarAccesosRol} ">
                    </select>
                  </div>
                </div>
                <?php echo $view_subcontent_accesorol; ?>
                <button id="guardar_accesorol" class="btn btn-primary" type="button" name="button" data-bind="event:{click: $root.GuardarAccesoRol}">Guardar Datos</button>
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
