<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalAlumno"
  data-bind="" >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
            <div class="modal-header">
                <h5 style="margin: 0px; width: 100%;">
                  <center>
                    <b>Padre: <span  class="text-uppercase" id="TituloNombrePadreAlumno"></b></span>
                    <button id="btnAgregarAlumno" class="btn btn-info" type="button" data-bind="click : $root.AgregarAlumno"><u>N</u>uevo</button>
                  </center>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="event:{click: $root.Cerrar}"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <div class="scrollable scrollbar-macosx">
                  <table id="DataTables_Table_0_alumno" class="datalist__table table display" width="100%" data-products="brand" >
                    <thead>
                      <tr>
                        <th class="products__title">Nombres</th>
                        <th class="products__title">Apellidos</th>
                        <th class="products__title">Código</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- ko foreach : Alumnos -->
                      <tr class="clickable-row text-uppercase" data-bind="event : {click : SeleccionarAlumno}, attr : { id: IdAlumno() + '_tr_alumno' }">
                        <td data-bind="event : { click : OnClickAlumno },attr : { id : IdAlumno() + '_td_NombreCompleto'}">
                          <span class="class_SpanAlumno" data-bind="text : NombreCompleto , visible : true , attr : { id : IdAlumno() + '_span_NombreCompleto'}"></span>
                          <input name="NombreCompleto" class="class_InputAlumno form-control formulario"
                          data-bind="value : NombreCompleto ,
                          visible : false , attr : { id : IdAlumno() + '_input_NombreCompleto' } ,
                          event : { keyup : OnKeyUpAlumno }">
                        </td>
                        <td data-bind="event : { click : OnClickAlumno },attr : { id : IdAlumno() + '_td_ApellidoCompleto'}">
                          <span class="class_SpanAlumno" data-bind="text : ApellidoCompleto , visible : true , attr : { id : IdAlumno() + '_span_ApellidoCompleto'}"></span>
                          <input name="ApellidoCompleto" class="class_InputAlumno form-control formulario"
                          data-bind="value : ApellidoCompleto ,
                          visible : false , attr : { id : IdAlumno() + '_input_ApellidoCompleto' } ,
                          event : { keyup : OnKeyUpAlumno }">
                        </td>
                        <td data-bind="event : { click : OnClickAlumno },attr : { id : IdAlumno() + '_td_CodigoAlumno'}">
                          <span class="class_SpanAlumno" data-bind="text : CodigoAlumno , visible : true , attr : { id : IdAlumno() + '_span_CodigoAlumno'}"></span>
                          <input name="CodigoAlumno" class="class_InputAlumno form-control formulario"
                          data-bind="value : CodigoAlumno ,
                          visible : false , attr : { id : IdAlumno() + '_input_CodigoAlumno' } ,
                          event : { keyup : OnKeyUpAlumno }">
                        </td>
                        <td align="center" data-bind="event : {click:FilaButtonsAlumno}">
                          <button data-bind="visible : false, attr : { id : IdAlumno() + '_button_Alumno' } , click : GuardarAlumno"class="btn btn-sm btn-success guardar_button_Alumno btn-operaciones"  data-toogle="tooltip" title="Guardar" >
                            <span class="glyphicon glyphicon-floppy-disk"></span>
                          </button>
                          <button data-bind="attr : { id : IdAlumno() + '_editar_button_Alumno' } , event : {click : EditarAlumno}" class="btn btn-sm btn-warning editar_button_Alumno btn-operaciones" data-toogle="tooltip" title="Editar">
                            <span class="glyphicon glyphicon-pencil"></span>
                          </button>
                          <button data-bind="attr : { id : IdAlumno() + '_borrar_button_Alumno' } , event : {click : PreBorrarAlumno}" class="btn btn-sm btn-danger borrar_button_Alumno btn-operaciones" data-toogle="tooltip" title="Borrar">
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
