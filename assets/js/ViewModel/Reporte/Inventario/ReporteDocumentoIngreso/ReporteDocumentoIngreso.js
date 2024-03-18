var Mapping_DocumentoIngreso = Object.assign(
  MappingDocumentoIngreso
  );


IndexReporteDocumentoIngreso = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingDocumentoIngreso, self);

}
