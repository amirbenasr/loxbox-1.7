<?php

class AdminLoxboxController extends ModuleAdminController {

    
  public function __construct(){
      parent::__construct();

      $this->bootstrap = true;
      $this->table="carrier";
      $this->identifier = 'id_carrier'; // SQL column to be used as primary key
      $this->_defaultOrderBy = 'a.name'; // the table alias is always `a`
      $this->_defaultOrderWay = 'ASC';
      $this->fields_list = [
        'id_carrier' => ['title' => 'ID','class' => 'fixed-width-xs'],  
        'name' => ['title' => 'Nom du transporteur'],  
      ];
      $this->addRowAction('details');
  }
}
