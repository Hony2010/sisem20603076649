var Mapping_Proveedores = Object.assign(
  MappingProveedores
  );


IndexReporteListaProveedores = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_Proveedores, self);

}
