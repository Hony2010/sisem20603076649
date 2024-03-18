var Mapping_MovimientoMercaderia = Object.assign(
  MappingMovimientoMercaderia
  );


IndexReporteMovimientoMercaderia = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingMovimientoMercaderia, self);

}
