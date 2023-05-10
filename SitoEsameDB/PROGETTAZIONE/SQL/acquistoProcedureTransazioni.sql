--Creazione della procedura che inserisce un ordine nella tabella ordini 
DELIMITER $$

CREATE PROCEDURE Ordine(IDProdotto INT, IDCliente INT, QntDesired INT, Tot FLOAT, OUT EseguitoO INT)

ProcOrd: BEGIN

	DECLARE QntDisp INT;
    
    SELECT Quantita INTO QntDisp
    FROM libro
    WHERE IDLibro = IDProdotto;
    
    IF QntDisp < QntDesired THEN
        SET EseguitoO = -1;
        LEAVE ProcOrd;
    END IF;
    
    IF QntDisp >= QntDesired THEN
    	INSERT INTO ordine(IDCli, IDL, Quantita, Totale) VALUES(IDCliente, IDProdotto, QntDesired, Tot);
        SET EseguitoO = 1;
    ELSE
    	SET EseguitoO = -1;
    END IF;
    
END$$

DELIMITER ;

--Creazione della procedura che aggiorna la quantità disponibile di un prodotto (libro)
DELIMITER $$

CREATE PROCEDURE Decrementa(IDProdotto integer, QntBought integer, OUT EseguitoD INT)

ProcDecr: BEGIN

	DECLARE QntDisp INT;

	SELECT Quantita INTO QntDisp
    FROM libro
    WHERE IDLibro = IDProdotto;
    
    IF QntDisp < QntBought THEN
        SET EseguitoD = -1;
        LEAVE ProcDecr;
    END IF;
    
    IF QntDisp >= QntBought THEN
    	UPDATE libro SET Quantita = Quantita - QntBought WHERE IDLibro = IDProdotto;
        SET EseguitoD = 1;
    ELSE
    	SET EseguitoD = -1;
    END IF;
    
END$$

DELIMITER ;

--Creazione della procedura che si occupa dell'acquisto. Viene inserito l'ordine e decrementata la quantità di prodotto.
DELIMITER $$

CREATE PROCEDURE Acquisto(IDProdotto INT, IDCliente INT, QntDesired INT, Tot FLOAT, OUT Msg VARCHAR(255))

ProcAcquisto: BEGIN

	START TRANSACTION;
    
    CALL Ordine(IDProdotto, IDCliente, QntDesired, Tot, @EseguitoO);
	
	CASE @EseguitoO
		WHEN -1 THEN SET Msg='Errore nella conferma dell ordine.';
		ELSE /* Risultato=1 */ BEGIN END;
	END CASE;
	
	IF @EseguitoO < 0 THEN
		ROLLBACK; 
		LEAVE ProcAcquisto;
	END IF;
	
	CALL Decrementa(IDProdotto, QntDesired, @EseguitoD);
	
	CASE @EseguitoD
		WHEN -1 THEN SET Msg='Errore nel decrementare quantità disponibile nel database.';
		ELSE /* Risultato=1 */ BEGIN END;
	END CASE;
	
	IF @EseguitoD < 0 THEN
		ROLLBACK; 
		LEAVE ProcAcquisto;
	ELSE	
		COMMIT;
		SET Msg='Acquisto completato.';
	END IF;
	
END$$

DELIMITER ;