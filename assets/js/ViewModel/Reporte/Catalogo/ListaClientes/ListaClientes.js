var Mapping_D = Object.assign(
  MappingListaClientes
  );


IndexReporteListaClientes = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_D, self);

}
