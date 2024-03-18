<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Tipo Tarjeta</h3>
              </div>
              <div class="panel-body">
                <!-- <p>Tienes 62 clientes.</p> -->
                <form class="datalist-filter">
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

                <div class="datalist__result">
                  <ul class="nav nav-tabs" role="tablist">
                    <li id="opcion-tipotarjeta" class="active" role="presentation">
                      <a href="#brand" aria-controls="brand" role="tab" data-toggle="tab">
                      TIPO DE TARJETA &nbsp; <button id="btnAgregarTipoTarjeta" class="btn btn-info" type="button" data-bind="click : $root.AgregarTipoTarjeta"><u>N</u>uevo</button><!-- <i class="badge badge-default">62</i> -->
                      </a>
                    </li>

                    <!-- <li role="presentation"><a href="#affilate" aria-controls="affilate" role="tab" data-toggle="tab">Affilate&nbsp;</a></li> -->

                  </ul>

                  <div class="tab-content">

                    <div class="tab-pane active" id="brand" role="tabpanel"  >
                      <div class="scrollable scrollbar-macosx">
                        <div class="container-fluid">
                          <!-- table-hover -->
                          <table id="DataTables_Table_0" class="datalist__table table display" width="100%" data-products="brand" >
                            <thead>
                              <tr>
                                <th class="products__id">Codigo</th>
                                <th class="products__title">Nombre</th>
                                <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!-- ko foreach : TiposTarjeta -->
                              <tr class="clickable-row" data-bind="click : $root.Seleccionar, attr : { id: IdTipoTarjeta }">

                                <td data-bind="text : IdTipoTarjeta, click:$root.FilaButtonsTipoTarjeta"></td>

                                <td data-bind="event : { click : $root.OnClickTipoTarjeta },attr : { id : IdTipoTarjeta() + '_td_NombreTarjeta'}">
                                  <span class="class_SpanTipoTarjeta" data-bind="text : NombreTarjeta , visible : true , attr : { id : IdTipoTarjeta() + '_span_NombreTarjeta'}"></span>
                                  <input name="NombreTarjeta" class="class_InputTipoTarjeta text-uppercase"
                                  data-bind="value : NombreTarjeta ,
                                  visible : false , attr : { id : IdTipoTarjeta() + '_input_NombreTarjeta' } ,
                                  event : { keyup : $root.OnKeyUpTipoTarjeta }"
                                  type="text" style="width : 100%" >

                                </td>

                                <td align="center" data-bind="click:$root.FilaButtonsTipoTarjeta">
                                    <button class="btn btn-sm btn-success guardar_button_TipoTarjeta" data-bind="visible : false, attr : { id : IdTipoTarjeta() + '_button_TipoTarjeta' } , click : $root.GuardarTipoTarjeta" data-toogle="tooltip" title="Guardar" >
                                      <span class="glyphicon glyphicon-floppy-disk"></span>
                                    </button>
                                    <button data-bind="attr : { id : IdTipoTarjeta() + '_editar_button_TipoTarjeta' } , click : $root.EditarTipoTarjeta" class="btn btn-sm btn-warning editar_button_TipoTarjeta" data-toogle="tooltip" title="Editar">
                                      <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                    <button data-bind="attr : { id : IdTipoTarjeta() + '_borrar_button_TipoTarjeta' } , click : $root.PreBorrarTipoTarjeta" class="btn btn-sm btn-danger borrar_button_TipoTarjeta" data-toogle="tooltip" title="Borrar">
                                      <span class="glyphicon glyphicon-trash"></span>
                                    </button>
                                </td>
                              </tr>
                              <!-- /ko -->
                            </tbody>
                          </table>
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
    </div>
  </div>
</div>
<!-- /ko -->
