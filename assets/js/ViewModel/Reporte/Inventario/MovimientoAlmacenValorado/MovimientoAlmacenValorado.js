var Mapping_MovimientoAlmacenValorado = Object.assign(
  MappingMovimientoAlmacenValorado
  );


IndexReporteMovimientoAlmacenValorado = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingMovimientoAlmacenValorado, self);

}
