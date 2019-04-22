<?php

class DatabaseObject {
  protected static $db;
  protected static $table_name = "";
  protected static $db_columns = [];
  public $errors = [];

  public static function set_database($database) {
    self::$db = $database;
  }

  public static function find_by_sql($sql) {
    $result = self::$db->query($sql); // returns true/false
    if(!$result) {
      exit("Database query failed.");
    }
    // results into objects
    $object_array = [];
    while($record = $result->fetch_assoc()) {
      $object_array[] = static::istantiate($record);
    }
    $result->free();
    return $object_array;
  }

  protected static function istantiate($record){
    $object = new static;
    // automatically assign values to properties
    foreach ($record as $property => $value) {
      if(property_exists($object, $property)) {
        $object->$property = $value;
      }
    }
    return $object;
  }

  public static function find_all(){
    $sql = "SELECT * FROM " . static::$table_name;
    return static::find_by_sql($sql);
  }

  public static function count_all() {
    $sql = "SELECT COUNT(*) FROM " . static::$table_name;
    $result = self::$db->query($sql);
    $row = $result->fetch_array();
    return array_shift($row);
  }

  public static function find_by_id($id) {
    $sql  = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE id='" . self::$db->escape_string($id) . "'";
    $object_array = static::find_by_sql($sql);
    if(empty($object_array)) {
      return false;
    }
    return array_shift($object_array);
  }

  protected function validate() {
    $this->errors = [];
    // Add custom validation for each class
    return $this->errors;
  }

    // dynamically set column values (propperties) regardless their name (key)
    protected function attributes() {
      $attributes = [];
      foreach (static::$db_columns as $column) {
        if($column == 'id') { continue; }
        $attributes[$column] = $this->$column;
      }
      return $attributes;
    }
  
    protected function sanitized_attributes() {
      $sanitized = [];
      foreach ($this->attributes() as $key => $value) {
        $sanitized[$key] = self::$db->escape_string($value);
      }
      return $sanitized;
    }

  public function create() {
    $this->validate();
    if(!empty($this->errors)) { return false; }

    $attributes = $this->sanitized_attributes();
    $sql = "INSERT INTO " . static::$table_name . " (";
    $sql .= join(', ', array_keys($attributes));
    $sql .= ") VALUES ('";
    $sql .= join("', '", array_values($attributes));
    $sql .= "')";
    $result = self::$db->query($sql);
    if($result) {
      $this->id = self::$db->insert_id;
    }
    return $result;
  }

  public function update() {
    $this->validate();
    if(!empty($this->errors)) { return false; }

    $attributes = $this->sanitized_attributes();
    $attribute_pairs = [];
    foreach ($attributes as $key => $value) {
      $attribute_pairs[] = "{$key}='{$value}'";
    }
    $sql  = "UPDATE " . static::$table_name . " SET ";
    $sql .= join(', ', $attribute_pairs);
    $sql .= "WHERE id='" . self::$db->escape_string($this->id) . "' ";
    $sql .= "LIMIT 1";
    $result = self::$db->query($sql);
    return $result;
  }

  // merge form field values into class instance properties
  public function merge_attributes($args) {
    foreach ($args as $property => $value) {
      if(property_exists($this, $property)) {
        $this->$property = $value;
      }
    }
  }

  public function delete() {
    $sql  = "DELETE FROM " . static::$table_name . " ";
    $sql .= "WHERE id='" . self::$db->escape_string($this->id) . "' ";
    $sql .= "LIMIT 1";
    $result = self::$db->query($sql);
  }

}

?>
