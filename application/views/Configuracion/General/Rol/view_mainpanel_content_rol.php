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
                <h3 class="panel-title">Roles</h3>
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
                    <li id="opcion-rol" class="active" role="presentation">
                      <a href="#brand" aria-controls="brand" role="tab" data-toggle="tab">
                      Roles &nbsp; <button id="btnAgregarRol" class="btn btn-info" type="button" data-bind="click : $root.AgregarRol"><u>N</u>uevo</button><!-- <i class="badge badge-default">62</i> -->
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
                                <th class="products__title">Tipo Rol</th>
                                <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!-- ko foreach : Roles -->
                              <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdRol }">

                                <td data-bind="text : IdRol, click:$root.FilaButtonsRol"></td>

                                <td data-bind="event : { click : $root.OnClickRol },attr : { id : IdRol() + '_td_NombreRol'}">
                                  <span class="class_SpanRol" data-bind="text : NombreRol , visible : true , attr : { id : IdRol() + '_span_NombreRol'}"></span>
                                  <input name="NombreRol" class="class_InputRol form-control formulario"
                                  data-bind="value : NombreRol ,
                                  visible : false , attr : { id : IdRol() + '_input_NombreRol' } ,
                                  event : { keyup : $root.OnKeyUpRol }"
                                  type="text" style="width : 100%" >

                                </td>

                                <td data-bind="event : { click : $root.OnClickRol },attr : { id : IdRol() + '_td_IdTipoRol'}">
                                  <span class="class_SpanRol" data-bind="text : NombreTipoRol , visible : true , attr : { id : IdRol() + '_span_IdTipoRol'}"></span>
                                  <div class="class_InputRol" data-bind="visible: false, attr : { id : IdRol() + '_combo_IdTipoRol' }" >
                                    <select name="IdTipoRol"  class="form-control formulario" data-bind="
                                            value : IdTipoRol,
                                            options : $root.data.TiposRol,
                                            optionsValue : 'IdTipoRol',
                                            optionsText : 'NombreTipoRol',
                                            attr : { id : IdRol() + '_input_IdTipoRol' } ">
                                    </select>
                                  </div>
                                </td>
                                <td align="center" data-bind="click:$root.FilaButtonsRol">
                                    <button data-bind="visible : false, attr : { id : IdRol() + '_button_Rol' } , click : $root.GuardarRol" class="btn btn-sm btn-success guardar_button_Rol" data-toogle="tooltip" title="Guardar" >
                                      <span class="glyphicon glyphicon-floppy-disk"></span>
                                    </button>
                                    <button data-bind="attr : { id : IdRol() + '_editar_button_Rol' } , click : $root.EditarRol" class="btn btn-sm btn-warning editar_button_Rol" data-toogle="tooltip" title="Editar">
                                      <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                    <button data-bind="attr : { id : IdRol() + '_borrar_button_Rol' } , click : $root.PreBorrarRol" class="btn btn-sm btn-danger borrar_button_Rol" data-toogle="tooltip" title="Borrar">
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
