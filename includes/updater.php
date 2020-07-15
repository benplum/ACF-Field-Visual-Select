<?php

require_once 'vendor/pw-updater.php';

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class ACF_Plugin_Visual_Select_Field_Updater extends PW_GitHub_Updater {

  public $username = 'benplum';
  public $repository = 'ACF-Field-Visual-Select';
  public $requires = '5.0';
  public $tested = '5.0.2';

  public function __construct() {
    $this->parent = ACF_Plugin_Visual_Select_Field::get_instance();

    parent::__construct();
  }

}


// Instance

ACF_Plugin_Visual_Select_Field_Updater::get_instance();
