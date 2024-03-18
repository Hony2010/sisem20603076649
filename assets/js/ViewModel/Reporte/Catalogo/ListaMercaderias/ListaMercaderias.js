var Mapping_M = Object.assign(
  MappingListaMercaderias
  );


IndexReporteListaMercaderias = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_M, self);

}
