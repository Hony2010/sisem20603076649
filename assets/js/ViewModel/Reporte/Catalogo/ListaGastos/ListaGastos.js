var Mapping_Gastos = Object.assign(
  MappingGastos
  );


IndexReporteListaGastos = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_Gastos, self);

}
