-- 01 --

ALTER TABLE comprobanteventa
ADD COLUMN SumCuotaPagoClienteComprobanteVenta DECIMAL(10,2) NULL DEFAULT 0.00 
COMMENT 'Suma de las cuotas por rutina almacenada de la tabla cuotapagoclientecomprobanteventa';

--

DELIMITER $$

CREATE PROCEDURE ActualizarSumCuota()
BEGIN
    -- Actualizar los valores de Sumcuotapagoclientecomprobanteventa en la tabla comprobanteventa
    UPDATE comprobanteventa AS cv
    SET cv.Sumcuotapagoclientecomprobanteventa = (
        SELECT SUM(cpcv.MontoCuota)
        FROM cuotapagoclientecomprobanteventa AS cpcv
        WHERE cpcv.idcomprobanteventa = cv.idcomprobanteventa
    )
    WHERE EXISTS (
        SELECT 1
        FROM cuotapagoclientecomprobanteventa AS cpcv
        WHERE cpcv.idcomprobanteventa = cv.idcomprobanteventa
    );
END $$

DELIMITER ;

--

DELIMITER $$

CREATE TRIGGER trg_actualizar_sumcuota_update
AFTER INSERT ON cuotapagoclientecomprobanteventa
FOR EACH ROW
BEGIN
    -- Actualizar la suma después de un INSERT
    UPDATE comprobanteventa AS cv
    SET cv.SumCuotaPagoClienteComprobanteVenta = (
        SELECT SUM(cpcv.MontoCuota)
        FROM cuotapagoclientecomprobanteventa AS cpcv
        WHERE cpcv.idcomprobanteventa = NEW.idcomprobanteventa
    )
    WHERE cv.idcomprobanteventa = NEW.idcomprobanteventa;
END $$

DELIMITER ;

-- 05 --

UPDATE parametrosistema 
SET ValorParametroSistema = 'VentaDetallado/Reporte_Venta_Detallado_2024'
WHERE NombreParametroSistema = 'nombre_archivo_jasper' AND ValorParametroSistema = 'VentaDetallado/Reporte_Venta_Detallado';
