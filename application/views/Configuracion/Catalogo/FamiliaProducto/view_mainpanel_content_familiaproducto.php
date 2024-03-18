<!-- ko with : vmcFamilia.dataFamiliaProducto-->
<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title">Familias &nbsp; <button id="btnAgregarFamiliaProducto" class="btn btn-info" type="button" data-bind="click : vistaModeloCatalogo.vmcFamilia.AgregarFamiliaProducto"><u>N</u>uevo</button></h4>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <?php echo $view_subcontent_familiaproducto; ?>
    </div>
  </div>
</div>
<!-- /ko -->
