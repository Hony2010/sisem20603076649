var Mapping_Inventario = Object.assign(
  MappingInventario
  );


IndexReporteInventario = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingInventario, self);

}
