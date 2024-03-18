var Mapping_General = Object.assign(
  MappingCompraGeneral
  );


IndexReporteCompraGeneral = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_General, self);

}
