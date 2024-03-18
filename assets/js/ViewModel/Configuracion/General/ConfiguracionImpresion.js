
var Mapping = Object.assign(
  MappingConfiguracionImpresion
);


Index = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);

    self.OnClickConfiguracion = function(data, event)
    {
      if(event)
      {
        $('#preview_canvas').hide();
      }
    }
}
