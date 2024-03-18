var Mapping_OtrasVentas = Object.assign(
  MappingOtrasVentas
  );


IndexReporteListaOtrasVentas = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_OtrasVentas, self);

}
