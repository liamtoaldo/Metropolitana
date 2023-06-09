-- MySQL dump 10.13  Distrib 8.0.33, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: metro
-- ------------------------------------------------------
-- Server version	8.0.33-0ubuntu0.20.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `linea`
--

DROP TABLE IF EXISTS `linea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `linea` (
  `Nome` varchar(45) NOT NULL,
  PRIMARY KEY (`Nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prenotazione`
--

DROP TABLE IF EXISTS `prenotazione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prenotazione` (
  `idPrenotazione` int NOT NULL AUTO_INCREMENT,
  `DataPrenotazione` datetime NOT NULL,
  `OraPartenza` time NOT NULL,
  `OraArrivo` time NOT NULL,
  `Costo` double NOT NULL,
  `NumCambi` int NOT NULL,
  `NumFermate` int NOT NULL,
  `Promozione` tinyint NOT NULL,
  `TempoPercorrenza` int NOT NULL,
  `Utente_idUtente` int NOT NULL,
  PRIMARY KEY (`idPrenotazione`),
  KEY `fk_Prenotazione_Utente1_idx` (`Utente_idUtente`),
  CONSTRAINT `fk_Prenotazione_Utente1` FOREIGN KEY (`Utente_idUtente`) REFERENCES `utente` (`idUtente`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prenotazione_has_transiti`
--

DROP TABLE IF EXISTS `prenotazione_has_transiti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prenotazione_has_transiti` (
  `Prenotazione_idPrenotazione` int NOT NULL,
  `Transiti_Viaggio_idViaggio` int NOT NULL,
  `Transiti_Stazione_Partenza` varchar(45) NOT NULL,
  `Transiti_Stazione_Arrivo` varchar(45) NOT NULL,
  `PosizioneInPrenotazione` int NOT NULL,
  PRIMARY KEY (`Prenotazione_idPrenotazione`,`Transiti_Viaggio_idViaggio`,`Transiti_Stazione_Partenza`,`Transiti_Stazione_Arrivo`),
  KEY `fk_Prenotazione_has_Transiti_Transiti1_idx` (`Transiti_Viaggio_idViaggio`,`Transiti_Stazione_Partenza`,`Transiti_Stazione_Arrivo`),
  KEY `fk_Prenotazione_has_Transiti_Prenotazione1_idx` (`Prenotazione_idPrenotazione`),
  CONSTRAINT `fk_Prenotazione_has_Transiti_Prenotazione1` FOREIGN KEY (`Prenotazione_idPrenotazione`) REFERENCES `prenotazione` (`idPrenotazione`),
  CONSTRAINT `fk_Prenotazione_has_Transiti_Transiti1` FOREIGN KEY (`Transiti_Viaggio_idViaggio`, `Transiti_Stazione_Partenza`, `Transiti_Stazione_Arrivo`) REFERENCES `transiti` (`Viaggio_idViaggio`, `Stazione_Partenza`, `Stazione_Arrivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stazione`
--

DROP TABLE IF EXISTS `stazione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stazione` (
  `Nome` varchar(45) NOT NULL,
  `Latitudine` double NOT NULL,
  `Longitudine` double NOT NULL,
  PRIMARY KEY (`Nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stazione_passa_linea`
--

DROP TABLE IF EXISTS `stazione_passa_linea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stazione_passa_linea` (
  `Stazione_Nome` varchar(45) NOT NULL,
  `Linea_Nome` varchar(45) NOT NULL,
  `Posizione` int NOT NULL,
  PRIMARY KEY (`Stazione_Nome`,`Linea_Nome`),
  KEY `fk_Stazione_has_Linea_Linea1_idx` (`Linea_Nome`),
  KEY `fk_Stazione_has_Linea_Stazione1_idx` (`Stazione_Nome`),
  CONSTRAINT `fk_Stazione_has_Linea_Linea1` FOREIGN KEY (`Linea_Nome`) REFERENCES `linea` (`Nome`),
  CONSTRAINT `fk_Stazione_has_Linea_Stazione1` FOREIGN KEY (`Stazione_Nome`) REFERENCES `stazione` (`Nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transiti`
--

DROP TABLE IF EXISTS `transiti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transiti` (
  `Viaggio_idViaggio` int NOT NULL,
  `Stazione_Partenza` varchar(45) NOT NULL,
  `Stazione_Arrivo` varchar(45) NOT NULL,
  `InizioViaggio` tinyint NOT NULL,
  `FineViaggio` tinyint NOT NULL,
  `OraPartenza` time DEFAULT NULL,
  `OraArrivo` time DEFAULT NULL,
  `PosizioneNelViaggio` int DEFAULT NULL,
  `CostoTransito` double NOT NULL,
  PRIMARY KEY (`Viaggio_idViaggio`,`Stazione_Partenza`,`Stazione_Arrivo`),
  KEY `fk_Stazione_has_Viaggio_Viaggio1_idx` (`Viaggio_idViaggio`),
  KEY `fk_Viaggio_transita_Stazione_Stazione1_idx` (`Stazione_Partenza`),
  KEY `fk_Stazione_has_Viaggio_Stazione1` (`Stazione_Arrivo`),
  CONSTRAINT `fk_Stazione_has_Viaggio_Stazione1` FOREIGN KEY (`Stazione_Arrivo`) REFERENCES `stazione` (`Nome`),
  CONSTRAINT `fk_Stazione_has_Viaggio_Viaggio1` FOREIGN KEY (`Viaggio_idViaggio`) REFERENCES `viaggio` (`idViaggio`),
  CONSTRAINT `fk_Viaggio_transita_Stazione_Stazione1` FOREIGN KEY (`Stazione_Partenza`) REFERENCES `stazione` (`Nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `treno`
--

DROP TABLE IF EXISTS `treno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treno` (
  `idTreno` int NOT NULL AUTO_INCREMENT,
  `Marca` varchar(45) NOT NULL,
  `Modello` varchar(45) NOT NULL,
  `Tipologia` varchar(45) NOT NULL,
  `NumCarrozze1Classe` int NOT NULL,
  `NumCarrozze2Classe` int NOT NULL,
  `NumPosti1Classe` int NOT NULL,
  `NumPosti2Classe` int NOT NULL,
  PRIMARY KEY (`idTreno`)
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `utente`
--

DROP TABLE IF EXISTS `utente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utente` (
  `idUtente` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(45) NOT NULL,
  `Cognome` varchar(45) NOT NULL,
  `CF` varchar(45) NOT NULL,
  `Eta` varchar(45) NOT NULL,
  `Professione` varchar(45) DEFAULT NULL,
  `PasswordHash` varchar(115) NOT NULL,
  `Username` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idUtente`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `viaggio`
--

DROP TABLE IF EXISTS `viaggio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `viaggio` (
  `idViaggio` int NOT NULL AUTO_INCREMENT,
  `Treno_idTreno` int NOT NULL,
  PRIMARY KEY (`idViaggio`),
  KEY `fk_Viaggio_Treno1_idx` (`Treno_idTreno`),
  CONSTRAINT `fk_Viaggio_Treno1` FOREIGN KEY (`Treno_idTreno`) REFERENCES `treno` (`idTreno`)
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-09 15:59:07
