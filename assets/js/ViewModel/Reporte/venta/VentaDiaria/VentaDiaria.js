var Mapping_Diario = Object.assign(
  MappingVentaDiaria
  );


IndexVentaDiaria = function (data) {

    var self = this;

    ko.mapping.fromJS(data, MappingVentaDiaria, self);

}
