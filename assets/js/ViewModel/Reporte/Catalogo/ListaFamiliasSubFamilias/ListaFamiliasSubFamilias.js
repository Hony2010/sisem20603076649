var Mapping_FamiliasSubFamilias = Object.assign(
  MappingFamiliasSubFamilias
  );


IndexReporteListaFamiliasSubFamilias = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_FamiliasSubFamilias, self);

}
