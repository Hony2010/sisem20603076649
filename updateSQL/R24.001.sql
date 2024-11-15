-- 1 --

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
AFTER UPDATE ON cuotapagoclientecomprobanteventa
FOR EACH ROW
BEGIN
    -- Actualizar la suma después de un UPDATE
    UPDATE comprobanteventa AS cv
    SET cv.SumCuotaPagoClienteComprobanteVenta = (
        SELECT SUM(cpcv.MontoCuota)
        FROM cuotapagoclientecomprobanteventa AS cpcv
        WHERE cpcv.idcomprobanteventa = NEW.idcomprobanteventa
    )
    WHERE cv.idcomprobanteventa = NEW.idcomprobanteventa;
END $$

DELIMITER ;