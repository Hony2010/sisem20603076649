  </br>
  <div class="container-fluid" id="ContentUsuario" autocomplete="off">
    <!-- <form class="" action="" method="post"> -->
      <input type="hidden" name="IdUsuario" id="IdUsuario" data-bind="value : IdUsuario, event : { focus : OnFocus, keydown : OnKeyEnter }">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon formulario">Nombre Usuario</i></div>
              <input id="NombreUsuario" class="form-control formulario" type="text" data-bind="value: NombreUsuario, event : { focus : OnFocus, keydown : OnKeyEnter }">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon formulario">Clave Usuario</div>
              <input id="ClaveUsuario" class="form-control formulario" type="password" data-bind="value: ClaveUsuario, event : { focus : OnFocus, keydown : OnKeyEnter }">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon formulario">Confirmar Clave</div>
              <input id="ConfirmarClaveUsuario" class="form-control formulario" type="password" data-bind="value: ConfirmarClaveUsuario, event : { focus : OnFocus, keydown : OnKeyEnter }">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon formulario">Nombre Empleado</div>
              <input id="IdPersona" class="form-control formulario no-tab" type="hidden" placeholder="Buscar Usuario..." data-bind="value: IdPersona">
              <input id="NombreEmpleado" class="form-control formulario" type="text" data-bind="event : { focus : OnFocus, keydown : OnKeyEnter }">
              <div class="input-group-btn">
                <button id="AgregarEmpleado" type="button" class="btn-buscar btn btn-default no-tab" data-bind="event:{click: AgregarEmpleado}"><i class="glyphicon glyphicon-plus"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon formulario">Serie Usuario</i></div>
              <input id="NumeroSerie" class="form-control formulario" type="text" data-bind="value: NumeroSerie, event : { focus : OnFocus, keydown : OnKeyEnter }">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <span>Accesos designados según rol: <b data-bind="text: NombreRol">Vendedor</b></span>
            <button class="btn btn-default" type="button" data-bind="event:{click: AccesoSistema}"><i class="fa fa-eye"></i></button>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon formulario">Alias Usuario Venta</div>
              <input id="AliasUsuarioVenta" class="form-control formulario" type="text" data-bind="value: AliasUsuarioVenta, event : { focus : OnFocus, keydown : OnKeyEnter }">
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <div class="input-group">
              <div class="multiselect-native-select formulario">
                <button type="button" class="multiselect dropdown-toggle btn btn-default btn-control" data-toggle="dropdown">
                  <span class="multiselect-selected-text">Almacenes </span><span id="numero_items" class="badge" data-bind="text: NumeroItemsSeleccionadas"></span>
                  <b class="caret"></b>
                </button>
                <ul class="multiselect-container dropdown-menu">
                  <li>
                    <div class="checkbox">
                      <input id="selector_almacen_todos" type="checkbox" data-bind="checked: SeleccionarTodos, event:{change: SeleccionarTodasItems}" />
                      <label class="checkbox" for="selector_almacen_todos"> Seleccionar Todos</label>
                    </div>
                  </li>
                  <!-- ko foreach: Almacenes -->
                  <li>
                    <div class="checkbox">
                      <input type="checkbox" data-bind="checked: Seleccionado, event: {change: $parent.CambioAlmacen}, attr : { id: IdSede() +'_almacen' }" />
                      <label class="checkbox" data-bind="text: NombreSede, attr:{for : IdSede() +'_almacen'}"></label>
                    </div>
                  </li>
                  <!-- /ko -->
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- ko if: (ParametroCaja() == "1") -->
        <div class="col-md-2">
          <div class="form-group">
            <div class="input-group">
              <div class="multiselect-native-select formulario">
                <button type="button" class="multiselect dropdown-toggle btn btn-default btn-control" data-toggle="dropdown">
                  <span class="multiselect-selected-text">Cajas</span><span id="numero_items_caja" class="badge" data-bind="text: NumeroCajasSeleccionadas"></span>
                  <b class="caret"></b>
                </button>
                <ul class="multiselect-container dropdown-menu">
                  <li>
                    <div class="checkbox">
                      <input id="selector_caja_todos" type="checkbox" data-bind="checked: SeleccionarTodosCajas, event: {change: SeleccionarTodasItemsCaja}" />
                      <label class="checkbox" for="selector_caja_todos"> Seleccionar Todos</label>
                    </div>
                  </li>
                  <!-- ko foreach: Cajas -->
                  <li>
                    <div class="checkbox">
                      <input type="checkbox" data-bind="checked: Seleccionado, event: {change: $parent.CambioCaja}, attr : { id: IdCaja() +'_caja' }" />
                      <label class="checkbox" data-bind="text: NombreCaja, attr:{ for : IdCaja() +'_caja'}"></label>
                    </div>
                  </li>
                  <!-- /ko -->
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <div class="input-group">
              <div class="multiselect-native-select formulario">
                <button type="button" class="multiselect dropdown-toggle btn btn-default btn-control" data-toggle="dropdown">
                  <span class="multiselect-selected-text">Turnos</span><span id="numero_items_turno" class="badge" data-bind="text: NumeroTurnosSeleccionadas"></span>
                  <b class="caret"></b>
                </button>
                <ul class="multiselect-container dropdown-menu">
                  <li>
                    <div class="checkbox">
                      <input id="selector_turno_todos" type="checkbox" data-bind="checked: SeleccionarTodosTurnos, event: {change: SeleccionarTodasItemsTurno}" />
                      <label class="checkbox" for="selector_turno_todos"> Seleccionar Todos</label>
                    </div>
                  </li>
                  <!-- ko foreach: Turnos -->
                  <li>
                    <div class="checkbox">
                      <input type="checkbox" data-bind="checked: Seleccionado, event: {change: $parent.CambioTurno}, attr : { id: IdTurno() +'_turno' }" />
                      <label class="checkbox" data-bind="text: NombreTurno()+' ('+HoraInicio()+' - '+HoraFin()+')', attr:{ for : IdTurno() +'_turno'}"></label>
                    </div>
                  </li>
                  <!-- /ko -->
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- /ko -->
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon formulario">Pregunta Seguridad</div>
              <select id="combo-preguntaseguridad" class="form-control formulario" data-bind= "
              value : IdPreguntaSeguridad,
              options : $root.data.PreguntasSeguridad,
              optionsValue : 'IdPreguntaSeguridad' ,
              optionsText : 'Pregunta',
              event : { focus : OnFocus, keydown : OnKeyEnter } ">
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Respuesta Seguridad</div>
            <input class="form-control formulario" type="password" data-bind="value: RespuestaSeguridad, event : { focus : OnFocus, keydown : OnKeyEnter }">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Confirmar Respuesta</div>
            <input class="form-control formulario" type="password" data-bind="value: ConfirmarRespuestaSeguridad, event : { focus : OnFocus, keydown : OnKeyEnter }">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon formulario">Nombre Zona</div>
            <input class="form-control formulario" type="text" data-bind="value: NombreZona, event : { focus : OnFocus, keydown : OnKeyEnter }">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="checkbox checkbox-inline">            
            <input id="IndicadorVistaPrecioMinimo" type="checkbox" class="no-tab" data-bind="checked : IndicadorVistaPrecioMinimo">
            <label for="IndicadorVistaPrecioMinimo"> Ver Precio Minimo </label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="checkbox checkbox-inline">            
            <input id="IndicadorExoneradoPrecioMinimo" type="checkbox" class="no-tab" data-bind="checked : IndicadorExoneradoPrecioMinimo">
            <label for="IndicadorExoneradoPrecioMinimo">Exonerado Precio Minimo</label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="checkbox checkbox-inline">            
            <input id="IndicadorEditarCampoPrecioUnitarioVenta" type="checkbox" class="no-tab" data-bind="checked : IndicadorEditarCampoPrecioUnitarioVenta">
            <label for="IndicadorEditarCampoPrecioUnitarioVenta">Modificar Precios de Venta</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="checkbox checkbox-inline">            
            <input id="IndicadorCrearProducto" type="checkbox" class="no-tab" data-bind="checked : IndicadorCrearProducto">
            <label for="IndicadorCrearProducto">Crear Productos</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="checkbox checkbox-inline">            
            <input id="IndicadorPermisoEditarComprobanteVenta" type="checkbox" class="no-tab" data-bind="checked : IndicadorPermisoEditarComprobanteVenta">
            <label for="IndicadorPermisoEditarComprobanteVenta">Modificar Comprobantes</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="checkbox checkbox-inline">            
            <input id="IndicadorPermisoAnularComprobanteVenta" type="checkbox" class="no-tab" data-bind="checked : IndicadorPermisoAnularComprobanteVenta">
            <label for="IndicadorPermisoAnularComprobanteVenta">Anular Comprobantes</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="checkbox checkbox-inline">            
            <input id="IndicadorPermisoEliminarComprobanteVenta" type="checkbox" class="no-tab" data-bind="checked : IndicadorPermisoEliminarComprobanteVenta">
            <label for="IndicadorPermisoEliminarComprobanteVenta">Eliminar Comprobantes</label>
        </div>
      </div>
    </div>
    <div class="row">
     <div class="col-md-12">
       <div class="checkbox checkbox-inline">
         <input id="IndicadorEstadoUsuario" type="checkbox" class="no-tab" data-bind="checked : IndicadorEstadoUsuario">
         <label for="IndicadorEstadoUsuario">Estado usuario</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="checkbox checkbox-inline">            
            <input id="IndicadorPermisoCobranzaRapida" type="checkbox" class="no-tab" data-bind="checked : IndicadorPermisoCobranzaRapida">
            <label for="IndicadorPermisoCobranzaRapida">Permiso Cobranza Rapida</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="checkbox checkbox-inline">            
            <input id="IndicadorPermisoStockNegativo" type="checkbox" class="no-tab" data-bind="checked : IndicadorPermisoStockNegativo">
            <label for="IndicadorPermisoStockNegativo">Permiso Venta sin stock</label>
        </div>
      </div>
    </div>
    <div class="row">
      <center>
        <br>
        <button id="btn_GrabarUsuario" type="button" class="btn btn-success" data-bind="click : Guardar">Grabar</button> &nbsp;
        <button id="btn_LimpiarUsuario" type="button" class="btn btn-default" data-bind="click : Deshacer">Deshacer</button> &nbsp;
        <button id="btn_CerrarUsuario" type="button" class="btn btn-default" data-bind="click : Cerrar">Cerrar</button>
      </center>
    </div>
    <!-- </form> -->
  </div>
