<?php
abstract class Printer
{
  protected $fieldList;

  public function __construct($fieldList)
  {
    $this->fieldList = $fieldList;
  }

  abstract function printHeader();
  abstract function printRow($row);
  abstract function printFooter();
}
