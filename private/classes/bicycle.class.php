<?php

class Bicycle extends DatabaseObject {

protected static $table_name = 'bicycles';
protected static $db_columns = ['brand', 'model', 'year', 'category', 'color', 'gender', 'price', 'weight_kg', 'condition_id', 'description'];

  public $id;
  public $model;
  public $brand;
  public $year;
  public $category;
  public $color;
  public $description;
  public $gender;
  public $price;
  public $condition_id;
  public $weight_kg;
  public const CATEGORIES = ['Road', 'Moutain', 'Hybrid', 'Cruiser', 'City', 'BMX'];
  public const GENDERS = ['Men', 'Women', 'Unisex'];
  public const CONDITION = [1 => 'Beat Up', 2 => 'Decent', 3 => 'Good', 4 => 'Great', 5 => 'Like New'];

  public function __construct($args=[]) {
    $this->brand = $args['brand'] ?? NULL;
    $this->model = $args['model'] ?? NULL;
    $this->year = $args['year'] ?? NULL;
    $this->category = $args['category'] ?? NULL;
    $this->color = $args['color'] ?? NULL;
    $this->description = $args['description'] ?? NULL;
    $this->gender = $args['gender'] ?? NULL;
    $this->price = $args['price'] ?? NULL;
    $this->weight_kg = $args['weight_kg'] ?? NULL;
    $this->condition_id = $args['condition_id'] ?? 3;
  }

  public function name() {
    return "{$this->brand} {$this->model} {$this->year}";
  }

  public function set_weight_kg($value) {
    $this->weight_kg = floatval($value);
  }

  public function weight_kg(){
    return number_format($this->weight_kg, 2) . ' kg';
  }

  public function set_weight_lbs($value) {
    $this->weight_kg = floatval($value) / 2.20462;
  }

  public function weight_lbs(){
    return $this->weight_kg * 2.20462 . ' lbs';
  }

  public function condition(){
    return self::CONDITION[$this->condition_id];
  }

  protected function validate() {
    $this->errors = [];

    if(is_blank($this->brand)) {
      $this->errors[] = "Brand cannot be blank.";
    }

    if(is_blank($this->model)) {
      $this->errors[] = "Model cannot be blank.";
    }
    return $this->errors;
  }

}

?>
