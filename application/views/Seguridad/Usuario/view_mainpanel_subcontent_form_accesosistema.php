<div class="datalist__result">
  <!-- ko foreach : AccesosUsuario -->
  <div class="row">
    <div class="col-md-12">
      <ul style="list-style:none;">
        <li>
            <h3>
              <input id="mercaderia" name="mercaderia" type="checkbox" data-bind="checked: EstadoGeneral, event:{change: CambiarEstadoCheck}" />
              <span data-bind="text: NombreModuloSistema">MERCADERIA</span>
            </h3>
            <ul dir=""style="list-style:none;">
            <!-- ko foreach : OpcionesSistema -->
              <li>
                <input id="accesorol" name="accesorol" type="checkbox" data-bind="checked: Estado"/>
                <span data-bind="text: NombreOpcionSistema"></span>
              </li>
            <!-- /ko -->
           </ul>
        </li>
      </ul>

    </div>
  </div>
  <!-- /ko -->
</div>
