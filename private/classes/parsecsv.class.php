<?php
class ParseCSV {
  private $filename;
  private $header;
  private $data=[];
  private $row_count;

  public function __construct($filename='') {
    if($filename != '') {
      $this->file($filename);
    }
  }

  public function file($filename){
    if(!file_exists($filename)) {
      echo "File does not exist.";
      return false;
    } elseif(!is_readable($filename)) {
      echo "File is not readable.";
      return false;
    }
    $this->filename = $filename;
    return true;
  }

  public function parse() {
    // clears any previous parses;
    $this->reset();

    if(!isset($this->filename)) {
      echo "File not set.";
      return false;
    }

    $file = fopen($this->filename, 'r');
    while(!feof($file)) {
      $row = fgetcsv($file, 0, ',');
      if($row == [NULL] || $row === FALSE) { continue; }
      if (!$this->header) {
        $this->header = $row;
      } else {
        $this->data[] = array_combine($this->header, $row) ;
        $this->row_count++;
      }
    }
    fclose($file);
    return $this->data;
  }

  public function reset() {
    $this->header = NULL;
    $this->data = [];
    $this->row_count = 0;
  }
}

?>
