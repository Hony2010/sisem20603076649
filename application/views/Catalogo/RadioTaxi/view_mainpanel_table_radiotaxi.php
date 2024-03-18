<div class="scrollable scrollbar-macosx">
  <!-- table-hover -->
  <fieldset>
    <table id="DataTables_Table_0" class="datalist__table table display" width="100%" data-products="brand">
      <thead>
        <tr>
          <th class="products__title">Codigo</th>
          <th class="products__title">Nombre</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <!-- ko foreach : RadiosTaxi -->
        <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdRadioTaxi }">
          <td data-bind="event : { click : $root.OnClickRadioTaxi },attr : { id : IdRadioTaxi() + '_td_IdRadioTaxi'}">
            <span class="class_SpanRadioTaxi" data-bind="text : IdRadioTaxi , visible : true , attr : { id : IdRadioTaxi() + '_span_IdRadioTaxi'}"></span>
            <span class="class_InputRadioTaxi" data-bind="text : IdRadioTaxi , visible : false , attr : { id : IdRadioTaxi() + '_input_IdRadioTaxi'}"></span>
            <!-- <input name="IdRadioTaxi" class="class_InputRadioTaxi form-control formulario" data-bind="value : IdRadioTaxi ,
                                visible : false , attr : { id : IdRadioTaxi() + '_input_IdRadioTaxi' } ,
                                event : { keyup : $root.OnKeyUpRadioTaxi }"> -->
          </td>
          <td data-bind="event : { click : $root.OnClickRadioTaxi },attr : { id : IdRadioTaxi() + '_td_NombreRadioTaxi'}">
            <span class="class_SpanRadioTaxi" data-bind="text : NombreRadioTaxi , visible : true , attr : { id : IdRadioTaxi() + '_span_NombreRadioTaxi'}"></span>
            <!-- ko if: (IdRadioTaxi() != 0)-->
            <input name="NombreRadioTaxi" class="class_InputRadioTaxi form-control formulario" data-bind="value : NombreRadioTaxi ,
                                visible : false , attr : { id : IdRadioTaxi() + '_input_NombreRadioTaxi' } ,
                                event : { keyup : $root.OnKeyUpRadioTaxi }">
            <!-- /ko -->
            <!-- ko if: (IdRadioTaxi() == 0)-->
            <span class="class_InputRadioTaxi" data-bind="text : NombreRadioTaxi , visible : false , attr : { id : IdRadioTaxi() + '_input_NombreRadioTaxi'}"></span>
            <!-- /ko -->
          </td>
          <td align="center" data-bind="click:$root.FilaButtonsRadioTaxi">
            <!-- ko if: (IdRadioTaxi() != 0)-->
            <button data-bind="visible : false, attr : { id : IdRadioTaxi() + '_button_RadioTaxi' } , click : $root.GuardarRadioTaxi" class="btn btn-sm btn-success guardar_button_RadioTaxi btn-operaciones" data-toogle="tooltip" title="Guardar">
              <span class="glyphicon glyphicon-floppy-disk"></span>
            </button>
            <button data-bind="attr : { id : IdRadioTaxi() + '_editar_button_RadioTaxi' } , click : $root.EditarRadioTaxi" class="btn btn-sm btn-warning editar_button_RadioTaxi btn-operaciones" data-toogle="tooltip" title="Editar">
              <span class="glyphicon glyphicon-pencil"></span>
            </button>
            <button data-bind="attr : { id : IdRadioTaxi() + '_borrar_button_RadioTaxi' } , click : $root.PreBorrarRadioTaxi" class="btn btn-sm btn-danger borrar_button_RadioTaxi btn-operaciones" data-toogle="tooltip" title="Borrar">
              <span class="glyphicon glyphicon-trash"></span>
            </button>
            <!-- /ko -->
          </td>
        </tr>
        <!-- /ko -->
      </tbody>
    </table>
  </fieldset>
</div>