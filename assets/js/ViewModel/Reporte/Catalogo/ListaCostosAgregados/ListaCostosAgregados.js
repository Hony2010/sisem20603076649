var Mapping_CostosAgregados = Object.assign(
  MappingCostosAgregados
  );


IndexReporteListaCostosAgregados = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_CostosAgregados, self);

}
