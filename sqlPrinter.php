<?php
include_once("printer.php");

class SQLPrinter extends Printer
{
  private $_printedLines = 0;
  private $_tableName = 'notas';
  private $_insertPrefix;

  public function setTableName($_tableName)
  {
    $this->_tableName = $_tableName;
    return $this;
  }

  public function printHeader()
  {
    print("/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n" .
      "/*!40101 SET NAMES utf8 */;\n" .
      "/*!50503 SET NAMES utf8mb4 */;\n" .
      "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;\n" .
      "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n" .
      "/*!40000 ALTER TABLE `{$this->_tableName}` DISABLE KEYS */;\n");
  }

  public function printRow($row)
  {
    if ($this->_printedLines == 0 ) {
      $sql = $this->_getInsertPrefix();
    } elseif ($this->_printedLines % 1000 == 0) {
      $sql = ";\n".$this->_getInsertPrefix();
    } else {
      $sql = ",\n(";
    }
    $fieldValues = explode(";", $row);
    $lastFieldKey = count($fieldValues) - 1;
    foreach ($fieldValues as $key => $value) {
      $sql .= $value . ($key == $lastFieldKey ? ")" : ",");
    }
    print($sql);
    $this->_printedLines++;
  }

  private function _getInsertPrefix()
  {

    if (!isset($this->_insertPrefix)) {
      $sql = "INSERT INTO `{$this->_tableName}` (";
      $lastFieldKey = count($this->fieldList) - 1;
      foreach ($this->fieldList as $key => $field) {
        $sql .= "`$field`" . ($key == $lastFieldKey ? ")" : ",");
      }
      $sql .= " VALUES (";
      $this->_insertPrefix = $sql;
    }
    return $this->_insertPrefix;
  }


  public function printFooter()
  {
    print(";\n" .
      "/*!40000 ALTER TABLE `{$this->_tableName}` ENABLE KEYS */;\n" .
      "/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;\n" .
      "/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;\n" .
      "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n");
  }
}
