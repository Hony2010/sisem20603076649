var MappingTurno = {
  'Turnos': {
    create: function (options) {
      if (options)
      return new VistaModeloTurno(options.data);
    }
  },
  'Turno': {
    create: function (options) {
      if (options)
      return new VistaModeloTurno(options.data);
    }
  }
}
