<!-- ko with : Filtros -->
<form action="" class="datalist-filter">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-md-8">
        <input id="input-text-filtro" class="form-control formulario" type="text" placeholder="Buscar por Nro Documento"
        data-bind="value : textofiltro, event : { keyup : $root.Consultar}">
      </div>
      <div class="col-md-2">
        <div class="input-group">
          <div class="input-group-addon ">F. Inicio</div>
          <input id="FechaInicio" name="FechaInicio"
           class="form-control formulario" data-inputmask-clearmaskonlostfocus="false"
           data-bind="value: FechaInicio, event : { keyup : $root.Consultar}"/>
        </div>
      </div>
      <div class="col-md-2">
        <div class="input-group">
          <div class="input-group-addon ">F. Fin</div>
          <input id="FechaFin" name="FechaFin"
           class="form-control formulario" data-inputmask-clearmaskonlostfocus="false"
           data-bind="value: FechaFin, event : { keyup : $root.Consultar}"/>
        </div>
      </div>
    </div>

    <!-- <div class="input-group datalist-filter__search">
        <span class="input-group-btn">
          <button class="btn btn-default" type="button" role="button" data-toggle="collapse"
            data-target="#datalist-filter__detail" aria-controls="users__filter-detail" aria-expanded="false">
            <div class="fa fa-filter"></div>
          </button>
        </span>
    </div>-->
  </div>

  <div class="collapse" id="datalist-filter__detail">
    <!--
    <div class="container-fluid datalist-filter__detail">
      <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 input-daterange">

        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 input-daterange">

        </div>
      </div>
    </div>-->
  </div>
</form>
<!-- /ko -->
<br>
<br>
