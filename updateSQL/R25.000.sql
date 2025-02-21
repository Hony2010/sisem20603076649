-- R25.008

INSERT INTO `parametrosistema` (`IdParametroSistema`, `NombreParametroSistema`, `ValorParametroSistema`, `IdEntidadSistema`, `IndicadorEstado`, `UsuarioRegistro`, `FechaRegistro`, `FechaModificacion`) VALUES ('397', 'ParametroHoraConsultaVenta', '0', '7', 'A', 'SISEM', '2023-05-20 11:47:00', '2023-05-20 11:47:00');

-- R25.009
UPDATE motivonotacredito SET IndicadorVenta = '1', AfectacionVenta = '1', IndicadorEstado = 'A' WHERE CodigoMotivoNotaCredito = '02' and CodigoMotivoNotaCredito = '03';

-- R25.011
ALTER TABLE `comprobantecompra` MODIFY COLUMN `Observacion` VARCHAR(900);

-- R25.014
-- Actualización de la base de datos para detracciones

-- 1. Nuevo campo para medio de pago: (001 = Depósito en cuenta).
ALTER TABLE `comprobanteventa` ADD COLUMN `CodigoMedioPagoDetraccion` CHAR(5) NULL DEFAULT '' AFTER `NumeroDetraccionBancoNacion`;

-- 2. Activar detracciones: (0 = Desactivado, 1 = Activado).
UPDATE `parametrosistema` SET `ValorParametroSistema` = '1' WHERE `IdParametroSistema` = 395;

-- 3. Añadir cuenta bancaria de detracciones
UPDATE `parametrosistema` SET `ValorParametroSistema` = '01452369232320' WHERE `IdParametroSistema` = 390;

-- 4. Código de tipo de detracción: (Tablas Sunat).
UPDATE `parametrosistema` SET `ValorParametroSistema` = '027' WHERE `IdParametroSistema` = 391;

-- 5. Configurar el porcentaje de detracción
UPDATE `parametrosistema` SET `ValorParametroSistema` = '10' WHERE `IdParametroSistema` = 392;

-- 6. configurar Tipo de operacion para detracción
INSERT INTO `tipooperacion` (`IdTipoOperacion`, `CodigoTipoOperacion`, `CodigoSUNAT`, `NombreTipoOperacion`, `IndicadorEstado`) VALUES ('5', '05', '1001', 'DETRACCION', 'T');

-- crear tabla detracción
CREATE TABLE IF NOT EXISTS `tipodetraccion` (
  `IdTipoDetraccion` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DescripcionTipoDetraccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `IndicadorEstado` varchar(1) NOT NULL,
  `PorcentajeTipoDetraccion` decimal(14,2) NOT NULL,
  KEY `tipodetraccion_IdTipoDetraccion_index` (`IdTipoDetraccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- insertando datos para tabla detracción 
INSERT INTO `tipodetraccion` (`IdTipoDetraccion`, `DescripcionTipoDetraccion`, `IndicadorEstado`, `PorcentajeTipoDetraccion`) VALUES
	('001', 'Azúcar', 'A', 10.00),
	('003', 'Alcohol etílico', 'A', 10.00),
	('004', 'Recursos hidrobiológicos', 'A', 4.00),
	('005', 'Maíz amarillo duro', 'A', 4.00),
	('007', 'Caña de azúcar', 'A', 10.00),
	('008', 'Madera', 'A', 4.00),
	('009', 'Arena y piedra.', 'A', 10.00),
	('010', 'Residuos, subproductos, desechos, recortes y desperdicios', 'A', 15.00),
	('011', 'Bienes del inciso A) del Apéndice I de la Ley del IGV', 'A', 10.00),
	('012', 'Intermediación laboral y tercerización', 'A', 12.00),
	('014', 'Carnes y despojos comestibles', 'A', 4.00),
	('016', 'Aceite de pescado', 'A', 10.00),
	('017', 'Harina, polvo y “pellets” de pescado, crustáceos, moluscos y demás invertebrados acuáticos', 'A', 4.00),
	('019', 'Arrendamiento de bienes muebles', 'A', 10.00),
	('020', 'Mantenimiento y reparación de bienes muebles', 'A', 12.00),
	('021', 'Movimiento de carga', 'A', 10.00),
	('022', 'Otros servicios empresariales', 'A', 12.00),
	('023', 'Leche', 'A', 4.00),
	('024', 'Comisión mercantil', 'A', 10.00),
	('025', 'Fabricación de bienes por encargo', 'A', 10.00),
	('026', 'Servicio de transporte de personas', 'A', 10.00),
	('027', 'Servicio de transporte de bienes', 'A', 4.00),
	('030', 'Contratos de construcción', 'A', 4.00),
	('031', 'Oro gravado con el IGV', 'A', 10.00),
	('032', 'Páprika y otros frutos de los géneros capsicum o pimienta', 'A', 10.00),
	('034', 'Minerales metálicos no auríferos', 'A', 10.00),
	('035', 'Bienes exonerados del IGV', 'A', 1.50),
	('036', 'Oro y demás minerales metálicos exonerados del IGV', 'A', 1.50),
	('037', 'Demás servicios gravados con el IGV', 'A', 12.00),
	('039', 'Minerales no metálicos', 'A', 10.00),
	('040', 'Bien inmueble gravado con IGV', 'A', 4.00),
	('041', 'Plomo', 'A', 15.00);
