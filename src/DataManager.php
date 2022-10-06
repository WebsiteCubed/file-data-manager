<?php

namespace App;

interface DataManager
{
  public function __construct(string $file);
  public function get();
  public function set(array $data);
}