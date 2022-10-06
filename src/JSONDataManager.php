<?php

namespace App;

class JSONDataManager implements DataManager
{
  public function __construct(private string $file){}

  public function get() : array
  {
    if (file_exists($this->file)) {
      if(str_ends_with($this->file, '.json')){
        $contents = file_get_contents('users.json');
        $array = json_decode($contents, true);

        return $array['users'];
      } else {
        throw new Exception('The file type is not supported');
      }
    } else {
      throw new Exception('The file does not exist.');
    }
  }

  public function set($data) : void
  {
    $array['users'] = $data;
    file_put_contents('users.json', json_encode($array, JSON_PRETTY_PRINT));
  }
}