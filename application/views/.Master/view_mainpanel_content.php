<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="products-preview datalist-preview">
              <div class="products-preview__cont">
                <div class="products-preview__name" title="Name">Name</div>
                <div class="products-preview__data">
                  <div class="products-preview__photo">
                    <div></div>
                  </div>
                  <div class="products-preview__info">
                    <div class="products-preview__type">Position</div>
                    <div class="products-preview__stat sparkline"></div>
                    <div class="products-preview__edit">
                      <div class="btn-group btn-group-sm">
                        <button class="btn btn-danger" type="button">Edit</button>
                        <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <li><a href="#">Details</a></li>
                          <li><a href="#">Disable</a></li>
                          <li><a href="#">Delete</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="products-preview__props">
                  <div class="products-preview__prop" title="salary"><i class="fa fa-money"></i><span class="products-preview__salary">Salary</span></div>
                  <div class="products-preview__prop"><i class="fa fa-calendar"></i><span class="products-preview__date">Date</span></div>
                  <div class="products-preview__prop"><i class="fa fa-heartbeat"></i><span class="products-preview__status">Status</span></div>
                </div>
                <div class="products-preview__note">Aliquam quis turpis eget elit sodales scelerisque. Mauris sit amet eros. Suspendisse accumsan tortor quis turpis. Sed ante.</div>
              </div>
            </div>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Clientes</h3>
              </div>
              <div class="panel-body">
                <!-- <p>Tienes 62 clientes.</p> -->
                <form class="datalist-filter">
                  <div class="input-group datalist-filter__search">
                    <input id="input-text-filtro" class="form-control" type="text" placeholder="Encuentre al cliente">
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" role="button" data-toggle="collapse" data-target="#datalist-filter__detail" aria-controls="users__filter-detail" aria-expanded="false">
                        <div class="fa fa-filter"></div>
                        </button>
                      </span>
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
                <div class="datalist__result">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="active" role="presentation"><a href="#brand" aria-controls="brand" role="tab" data-toggle="tab">Resultado <i class="badge badge-default">62</i></a></li>
                    <!-- <li role="presentation"><a href="#digital" aria-controls="digital" role="tab" data-toggle="tab">Digital&nbsp;</a></li>
                    <li role="presentation"><a href="#affilate" aria-controls="affilate" role="tab" data-toggle="tab">Affilate&nbsp;</a></li> -->
                    <li role="presentation"><a href="#new" aria-controls="new" role="tab" data-toggle="tab"><i class="fa fa-plus"></i>&nbsp;Nuevo</a></li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane" id="new" role="tabpanel">
                      <form class="form products-new">
                        <div class="container-fluid">

                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-folder"></i></div>
                                  <select class="selectpicker" placeholder="Tipo Documento">
                                    <option value="1">DNI</option>
                                    <option value="6">RUC</option>
                                    <option value="4">Carnet Extranjeria</option>
                                    <option value="7">Pasaporte</option>
                                    <option value="A">Cedula Diplomatica de Identidad</option>
                                    <option value="0">otros</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-cube"></i></div>
                                  <input class="form-control" type="text" placeholder="Nº Documento">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="products-preview__props">
                                <div class="products-preview__prop" title="Estado y Condicion">
                                  <i class="fa fa-clone"></i><span class="products-preview__salary">ACTIVO Y HABIDO</span>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-12">
                              <label class="control-label"> Tipo Personeria</label>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-9">
                              <div class="form-group">
                                <!-- <label class="col-sm-2 control-label"> Tipo Persona</label> -->
                                  <div class="radio radio-inline">
                                    <input id="r1" type="radio" name="controls_radio" checked="checked">
                                    <label for="r1">P. Juridica</label>
                                  </div>
                                  <div class="radio radio-inline">
                                    <input id="r2" type="radio" name="controls_radio" >
                                    <label for="r2">P. Natural</label>
                                  </div>
                                  <div class="radio radio-inline">
                                    <input id="r3" type="radio" name="controls_radio" >
                                    <label for="r3">No Domiciliado</label>
                                  </div>

                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-cube"></i></div>
                                  <input class="form-control" type="text" placeholder="Codigo">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-bolt"></i></div>
                                  <input class="form-control" type="text" placeholder="Razon Social">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-bars"></i></div>
                                  <input class="form-control" type="text" placeholder="Primer Nombre">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-feed"></i></div>
                                  <input class="form-control" type="text" placeholder="Primer Apellido">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-bars"></i></div>
                                  <input class="form-control" type="text" placeholder="Segundo Nombre">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-feed"></i></div>
                                  <input class="form-control" type="text" placeholder="Segundo Apellido">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-home"></i></div>
                                  <input class="form-control" type="text" placeholder="Direccion">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-home"></i></div>
                                  <input class="form-control" type="text" placeholder="Nombre Comercial">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-home"></i></div>
                                  <input class="form-control" type="text" placeholder="Representante Legal">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-5">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-home"></i></div>
                                  <input class="form-control" type="text" placeholder="Correo Electronico">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-feed"></i></div>
                                  <input class="form-control" type="text" placeholder="Celular">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon"><i class="fa fa-feed"></i></div>
                                  <input class="form-control" type="text" placeholder="Telefono">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <textarea class="form-control" rows="2" placeholder="Observaciones"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="roW">
                            <center>
                              <button class="btn btn-default">Grabar</button> &nbsp;
                              <button class="btn btn-default">Limpiar</button> &nbsp;
                              <button class="btn btn-default">Cerrar</button>
                            </center>
                          </div>
                        </div>

                      </form>
                    </div>
                    <div class="tab-pane active" id="brand" role="tabpanel">
                      <div class="scrollable scrollbar-macosx">
                        <div class="container-fluid">
                          <table class="datalist__table table datatable display table-hover" width="100%" data-products="brand">
                            <thead>
                              <tr>
                                <th class="products__id">#</th>
                                <th class="products__pic">Tipo</th>
                                <th class="products__title">Numero Doc</th>
                                <th class="products__date">Nombre Razon Social</th>
                                <th class="products__salary">Celular</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <!--<th class="products__status">Status</th>
                                <th class="products__chart">Stat</th>
                                <th class="products__type">Type</th>-->
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>1</td>
                                <td>RUC</td>
                                <td>20533320130</td>
                                <td>Inversiones y Servicios S.A.</td>
                                <td>952241144</td>
                                <td align="center"> <div class="fa fa-fw fa-pencil" data-toogle="tooltip" title="Modificar"> </div></td>
                                <td align="center"> <div class="fa fa-fw fa-trash-o"> </div></td>
                              </tr>
                              <tr>
                                <td>2</td>
                                <td>RUC</td>
                                <td>20463320130</td>
                                <td>Inversiones y Servicios S.A.</td>
                                <td>952241144</td>
                                <td align="center"> <div class="fa fa-fw fa-pencil"> </div></td>
                                <td align="center"> <div class="fa fa-fw fa-trash-o"> </div></td>
                              </tr>
                              <tr>
                                <td>3</td>
                                <td>DNI</td>
                                <td>43206060</td>
                                <td>Nicky Salomon Enriquez Torres</td>
                                <td>944591291</td>
                                <td align="center"> <div class="fa fa-fw fa-pencil"> </div></td>
                                <td align="center"> <div class="fa fa-fw fa-trash-o"> </div></td>
                              </tr>
                              <tr>
                                <td>4</td>
                                <td>RUC</td>
                                <td>2099310110</td>
                                <td>Infinity Constructora e Inmobiliaria SAC</td>
                                <td>952241144</td>
                                <td align="center"> <div class="fa fa-fw fa-pencil"> </div></td>
                                <td align="center"> <div class="fa fa-fw fa-trash-o"> </div></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="digital" role="tabpanel">
                      <div class="scrollable scrollbar-macosx">
                        <div class="container-fluid">
                          <table class="datalist__table table datatable display table-hover" width="100%" data-products="digital">
                            <thead>
                              <tr>
                                <th class="products__id">#ID</th>
                                <th class="products__pic">Pic</th>
                                <th class="products__title">Title</th>
                                <th class="products__date">Date</th>
                                <th class="products__salary">Salary</th>
                                <th class="products__status">Status</th>
                                <th class="products__chart">Stat</th>
                                <th class="products__type">Type</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="affilate" role="tabpanel">
                      <div class="scrollable scrollbar-macosx">
                        <div class="container-fluid">
                          <table class="datalist__table table datatable display table-hover" width="100%" data-products="affilate">
                            <thead>
                              <tr>
                                <th class="products__id">#ID</th>
                                <th class="products__pic">Pic</th>
                                <th class="products__title">Title</th>
                                <th class="products__date">Date</th>
                                <th class="products__salary">Salary</th>
                                <th class="products__status">Status</th>
                                <th class="products__chart">Stat</th>
                                <th class="products__type">Type</th>
                              </tr>
                            </thead>
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
