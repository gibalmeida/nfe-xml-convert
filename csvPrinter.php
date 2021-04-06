<?php 
include_once("printer.php");

class CSVPrinter extends Printer {

  public function printHeader()
  {
    $lastFieldKey = count($this->fieldList) - 1;
    foreach ($this->fieldList as $key => $field) {
      print($field . ($key !== $lastFieldKey ? ";" : "\n"));
    }
  }

  public function printRow($row)
  {
    print ($row."\n");
  }

  public function printFooter()
  {
    return;
  }

}