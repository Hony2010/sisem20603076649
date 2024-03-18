<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <!-- ko with : ControlMesa -->
    <div class="container-fluid half-padding">
      <div class="row mesas">
        <!-- ko foreach : Mesas -->
        <div class="col-md-2 form-group">
          <button type="button" class="btn btn-info btn-control btn-mesa" data-bind="
          text : NumeroMesa,
          event:{ click: function (data, event) { return $root.OnClickBtnMesaVendedor(data, event, $parent);}},
          css : SituacionMesa() == SITUACION_MESA.OCUPADO ? 'mesa-ocupado' : 'mesa-disponible'
          "></button>
        </div>
        <!-- /ko -->
      </div>
    </div>
    <!-- /ko -->
  </div>
</div>


<?php echo $view_modal_comanda; ?>

<?php //echo $view_modal_cliente; ?>
<?php //echo $view_modal_preview_cliente; ?>

<!-- /ko -->
<style media="screen">
  .mesas .btn-mesa {
    height: 150px !important;
    font-size: 40px;
  }
  .mesas .form-group{
    margin-bottom : 15px;
  }
</style>
