<!-- ko with : vmcMarca.dataMarca -->

<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Marcas &nbsp; <button id="btnAgregarMarca" class="btn btn-info" type="button" data-bind="click : vistaModeloCatalogo.vmcMarca.AgregarMarca"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <?php echo $view_subcontent_marca; ?>
    </div>
  </div>
</div>
<!-- /ko -->
