-- MySQL dump 10.13  Distrib 8.0.23, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: pis_cofins
-- ------------------------------------------------------
-- Server version	8.0.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `notas`
--

DROP TABLE IF EXISTS `notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notas` (
  `nota` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chave` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emissao` date DEFAULT NULL,
  `versao` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `natOp` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tpNF` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idDest` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tpEmis` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finNFe` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `indFinal` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emitente-nome` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destinatario-nome` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `indIEDest` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int DEFAULT NULL,
  `motivo-status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nItem` int NOT NULL,
  `cProd` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cEAN` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `xProd` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ncm` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nve` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cest` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `indEscala` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CNPJFab` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cBenef` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extipi` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cfop` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uCom` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qCom` decimal(15,4) DEFAULT NULL,
  `vUnCom` decimal(21,10) DEFAULT NULL,
  `vProd` decimal(15,2) DEFAULT NULL,
  `cEANTrib` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uTrib` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qTrib` decimal(15,4) DEFAULT NULL,
  `vUnTrib` decimal(21,10) DEFAULT NULL,
  `vFrete` decimal(15,2) DEFAULT NULL,
  `vSeg` decimal(15,2) DEFAULT NULL,
  `vDesc` decimal(15,2) DEFAULT NULL,
  `vOutro` decimal(15,2) DEFAULT NULL,
  `indTot` int DEFAULT NULL,
  `icms-elem` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icms-origem` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icms-CST` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icms-modBC` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icms-vBC` decimal(15,2) DEFAULT NULL,
  `icms-pRedBC` decimal(15,4) DEFAULT NULL,
  `icms-pICMS` decimal(15,4) DEFAULT NULL,
  `icms-vICMS` decimal(15,2) DEFAULT NULL,
  `icms-vBCFCP` decimal(15,2) DEFAULT NULL,
  `icms-pFCP` decimal(15,4) DEFAULT NULL,
  `icms-vFCP` decimal(15,2) DEFAULT NULL,
  `icms-modBCST` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icms-pMVAST` decimal(15,4) DEFAULT NULL,
  `icms-pRedBCST` decimal(15,4) DEFAULT NULL,
  `icms-vBCST` decimal(15,2) DEFAULT NULL,
  `icms-pICMSST` decimal(15,4) DEFAULT NULL,
  `icms-vICMSST` decimal(15,4) DEFAULT NULL,
  `icms-vBCFCPST` decimal(15,2) DEFAULT NULL,
  `icms-pFCPST` decimal(15,4) DEFAULT NULL,
  `icms-vFCPST` decimal(15,2) DEFAULT NULL,
  `icms-vICMSDeson` decimal(15,2) DEFAULT NULL,
  `icms-motDesICMS` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icms-vICMSOp` decimal(15,2) DEFAULT NULL,
  `icms-pDif` decimal(15,4) DEFAULT NULL,
  `icms-vICMSDif` decimal(15,2) DEFAULT NULL,
  `icms-pBCOp` decimal(15,4) DEFAULT NULL,
  `icms-UFST` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icms-vBCSTRet` decimal(15,2) DEFAULT NULL,
  `icms-pST` decimal(15,4) DEFAULT NULL,
  `icms-vICMSSubstituto` decimal(15,2) DEFAULT NULL,
  `icms-vICMSSTRet` decimal(15,2) DEFAULT NULL,
  `icms-vBCFCPSTRet` decimal(15,2) DEFAULT NULL,
  `icms-pFCPSTRet` decimal(15,4) DEFAULT NULL,
  `icms-vFCPSTRet` decimal(15,2) DEFAULT NULL,
  `icms-vBCSTDest` decimal(15,2) DEFAULT NULL,
  `icms-vICMSSTDest` decimal(15,2) DEFAULT NULL,
  `icms-pRedBCEfet` decimal(15,4) DEFAULT NULL,
  `icms-vBCEfet` decimal(15,2) DEFAULT NULL,
  `icms-pICMSEfet` decimal(15,4) DEFAULT NULL,
  `icms-vICMSEfet` decimal(15,2) DEFAULT NULL,
  `ipi-elem` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ipi-CST` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ipi-vBC` decimal(15,2) DEFAULT NULL,
  `ipi-pIPI` decimal(15,4) DEFAULT NULL,
  `ipi-qUnid` decimal(15,4) DEFAULT NULL,
  `ipi-vUnid` decimal(15,2) DEFAULT NULL,
  `ipi-vIPI` decimal(15,2) DEFAULT NULL,
  `pis-elem` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pis-CST` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pis-vBC` decimal(15,2) DEFAULT NULL,
  `pis-pPIS` decimal(15,4) DEFAULT NULL,
  `pis-qBCProd` decimal(15,4) DEFAULT NULL,
  `pis-vAliqProd` decimal(15,2) DEFAULT NULL,
  `pis-vPIS` decimal(15,2) DEFAULT NULL,
  `pisSt-vBC` decimal(15,2) DEFAULT NULL,
  `pisSt-pPIS` decimal(15,4) DEFAULT NULL,
  `pisSt-qBCProd` decimal(15,4) DEFAULT NULL,
  `pisSt-vAliqProd` decimal(15,2) DEFAULT NULL,
  `pisSt-vPIS` decimal(15,2) DEFAULT NULL,
  `cofins-elem` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cofins-CST` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cofins-vBC` decimal(15,2) DEFAULT NULL,
  `cofins-pCOFINS` decimal(15,4) DEFAULT NULL,
  `cofins-qBCProd` decimal(15,4) DEFAULT NULL,
  `cofins-vAliqProd` decimal(15,2) DEFAULT NULL,
  `cofins-vCOFINS` decimal(15,2) DEFAULT NULL,
  `cofinsSt-vBC` decimal(15,2) DEFAULT NULL,
  `cofinsSt-pCOFINS` decimal(15,4) DEFAULT NULL,
  `cofinsSt-qBCProd` decimal(15,4) DEFAULT NULL,
  `cofinsSt-vAliqProd` decimal(15,2) DEFAULT NULL,
  `cofinsSt-vCOFINS` decimal(15,2) DEFAULT NULL,
  `LINK` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelada` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  PRIMARY KEY (`chave`,`nItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-03-31 16:41:41
