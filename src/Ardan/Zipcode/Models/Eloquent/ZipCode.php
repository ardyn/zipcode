<?php

namespace Ardan\Zipcode\Models\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Config\Repository as Config;
use Ardan\Zipcode\Models\ZipCodeModelInterface;

class ZipCode extends Eloquent implements ZipCodeModelInterface {

 /**
  * Timestamps
  *
  * @var bool
  */
  public $timestamps = false;

 /**
  * Primary key column is set by the config file
  *
  * @access public
  * @param string $key
  * @return void
  */
  public function setPrimarykey($key) {

    $this->primaryKey = $key;

  } /* function setPrimarykey */

} /* class ZipCode */

/* EOF */
