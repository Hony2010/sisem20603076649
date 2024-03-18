var Mapping_ActivosFijos = Object.assign(
  MappingActivosFijos
  );


IndexReporteListaActivosFijos = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_ActivosFijos, self);

}
