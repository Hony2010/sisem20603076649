<form id="formComanda" class="" action="" method="post">
  <div class="row">
    <div class="col-md-12">
      <div class="container-products col-md-8">
        <?php echo $view_form_product_comanda; ?>
      </div>
      <div class="container-voucher col-md-4">
        <!-- ko with : Comanda -->
        <?php echo $view_form_detail_comanda; ?>
        <!-- /ko -->

        <!-- ko with : TecladoVirtual -->
        <?php echo $view_form_keyboard_comanda; ?>
        <!-- /ko -->
      </div>
    </div>
  </div>
</form>

<style media="screen">
  .list-products,
  .body-details,
  .filter-products-vertical{
    overflow: auto;
    overflow-x: hidden;
  }
  .list-products{
    /* padding: 5px; */
  }
  .products-preview__info span{
    height: 32px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    font-weight: bold;
  }
  .table > tbody > tr > td,
  .table > tfoot > tr > td{
    vertical-align: middle;
  }

  .btn-familias,
  .btn-tipodocumento {
    text-overflow: ellipsis;
    overflow: hidden;
    padding: 10px 5px;
  }

  .btn-familias{
    margin-bottom: 2px;
  }

  .numpad > .col-md-12,
  .numpad > .col-md-4,
  .numpad > .col-xs-4,
  .numpad > .col-lg-4{
    padding: 1px;
  }
  .input-button {
    font-weight: bold;
    height: 35px !important;
    font-size: 13px !important;
  }
  .foot-details {
    margin-top: 3px;
    padding: 5px 4px 5px 4px;
    border-radius: 2px;
    font-weight: bold;
    font-size: 13px !important;
  }

  .hide-element {
    animation: fadeOut .5s;
    animation-fill-mode: forwards;
  }
  .show-element {
    animation: fadeIn .5s;
    animation-fill-mode: forwards;
  }

  @keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  .margin-bottom {
    margin-bottom: 5px;
  }

  .detalle-punto-venta .enviado {
    background-color: #15a049;
    color: #ffff;
  }
  .detalle-punto-venta .enviado input{
    color: #ffff;
  }

</style>
