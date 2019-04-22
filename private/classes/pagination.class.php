<?php

class Pagination extends DatabaseObject {

  private $curr_page;
  private $records_per_page;
  private $total_records;
  // private $offset = $records_per_page * ($curr_page - 1);

  public function __construct($page=1, $per_page=5, $total_records=0) {
    $this->curr_page = (int) $page;
    $this->records_per_page = (int) $per_page;
    $this->total_records = (int) $total_records;
  }

  public function offset() {
    return $this->records_per_page * ($this->curr_page - 1);
  }

  public function page_count() {
    return ceil($this->total_records / $this->records_per_page);
  }

  public function next_page() {
    $next = $this->curr_page + 1;
    return $next <= $this->page_count() ? $next : false;
  }

  public function prev_page() {
    return $this->curr_page - 1; // returns false if current page is 1
  }

  public function find_counted_records($table_name, $class_name) {
    $sql  = "SELECT * FROM " . $table_name . " ";
    $sql .= "LIMIT " . $this->records_per_page . " ";
    $sql .= "OFFSET " . $this->offset();
    $object_array = $class_name::find_by_sql($sql);
    if(empty($object_array)) {
      return false;
    }
    return $object_array;
  }
}

?>
