var Mapping_MarcasModelos = Object.assign(
  MappingMarcasModelos
  );


IndexReporteListaMarcasModelos = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_MarcasModelos, self);

}
