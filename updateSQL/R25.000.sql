-- R25.008

INSERT INTO `parametrosistema` (`IdParametroSistema`, `NombreParametroSistema`, `ValorParametroSistema`, `IdEntidadSistema`, `IndicadorEstado`, `UsuarioRegistro`, `FechaRegistro`, `FechaModificacion`) VALUES ('397', 'ParametroHoraConsultaVenta', '0', '7', 'A', 'SISEM', '2023-05-20 11:47:00', '2023-05-20 11:47:00');

-- R25.009
UPDATE motivonotacredito SET IndicadorVenta = '1', AfectacionVenta = '1', IndicadorEstado = 'A' WHERE CodigoMotivoNotaCredito = '02' and CodigoMotivoNotaCredito = '03';