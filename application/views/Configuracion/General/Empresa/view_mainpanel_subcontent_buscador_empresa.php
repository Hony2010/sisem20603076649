<br>
<form class="datalist-filter">
  <div class="col-sm-9">
    <div class="input-group datalist-filter__search">
        <input id="input-text-filtro" class="form-control" type="text" placeholder="Encuentre a la mercaderia">
        <span class="input-group-btn">
          <button class="btn btn-default" type="button" role="button" data-toggle="collapse" data-target="#datalist-filter__detail" aria-controls="users__filter-detail" aria-expanded="false" data-bind="click : $root.Consultar">
            <div class="fa fa-filter"></div>
          </button>
        </span>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="input-group">
      <button data-bind="" class="btn btn-sm btn-default" data-toogle="tooltip" title="Modo Tabla">
          <span class="glyphicon glyphicon-list-alt"></span>
      </button>
      <button data-bind="" class="btn btn-sm btn-default" data-toogle="tooltip" title="Modo Lista">
        <span class="glyphicon glyphicon-list"></span>
      </button>
      <button data-bind="" class="btn btn-sm btn-default" data-toogle="tooltip" title="Modo Galeria">
        <span class="glyphicon glyphicon-th"></span>
      </button>
    </div>
  </div>
  <div class="collapse" id="datalist-filter__detail">
    <div class="container-fluid datalist-filter__detail">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-daterange">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div>
              <input class="form-control datalist-filter__from" type="text" value="">
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-calendar-plus-o"></i></div>
              <input class="form-control datalist-filter__to" type="text" value="">
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
          <div class="form-group">
            <input class="slider" id="datalist-filter__salary" type="text" name="" value="">
          </div>
          <div class="form-group">
            <div class="checkbox checkbox-danger">
              <input id="datalist-filter__actives" type="checkbox">
              <label for="datalist-filter__actives">Actives only</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<br>
<br>
