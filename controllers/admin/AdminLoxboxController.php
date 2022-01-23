<?php

class AdminLoxboxController extends ModuleAdminController {

    
  public function __construct(){
      parent::__construct();

      $this->bootsrap=true;
      $this->allow_export = true; // allow export in CSV, XLS..
    
  }
}
