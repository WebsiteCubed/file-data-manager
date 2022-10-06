<?php

namespace App;

class Api
{
  private array $dataArray;

  public function __construct(private DataManager $data){
    $this->dataArray = $data->get();
  }

  public function create($attributes) : void
  {
    $lastInArray = end($this->dataArray);
    $lastKey = $lastInArray['id'];

    array_push($this->dataArray, [
      'id' => $lastKey + 1,
      'username' => $attributes['username'],
      'email' => $attributes['email']
    ]);

    try {
      $this->data->set(array_values($this->dataArray));
    } catch (Exception $e) {
      echo $e->getMessage();
    } 
  }

  public function single($id) : array
  {
    $key = $this->getKeyInArray($id);

    return $user = $this->dataArray[$key];
  }

  public function all() : array
  {
    return $this->dataArray;
  }

  public function delete($id) : void
  {
    $key = $this->getKeyInArray($id);
    unset($this->dataArray[$key]);
    
    $this->data->set(array_values($this->dataArray));
  }

  private function getKeyInArray($id) : int
  {
    if (in_array((int) $id, array_column($this->dataArray, 'id'))){
      $key = array_search($id, array_column($this->dataArray, 'id'));
      return $key;
    } else {
      die('There is no data with this id.');
    }
  }
}