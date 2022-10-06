<?php

namespace App;

class XMLDataManager implements DataManager
{
  public function __construct(private string $file){}

  public function get() : array
  {
    if (file_exists($this->file)) {
      if(str_ends_with($this->file, '.xml')){
        $contents = json_encode(simplexml_load_file($this->file), JSON_PRETTY_PRINT);
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

    $simplexml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><users></users>');

    foreach($array['users'] as $item){
      $child = $simplexml->addChild('users');
      foreach($item as $key => $value){
        $child->addChild($key, $value);
      }
      
    }

    $dom = new \DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;

    $dom->loadXML($simplexml->asXML());
    $dom->save($this->file);
  }
}