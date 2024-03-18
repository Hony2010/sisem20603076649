var Mapping_Empleados = Object.assign(
  MappingEmpleados
  );


IndexReporteListaEmpleados = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_Empleados, self);

}
