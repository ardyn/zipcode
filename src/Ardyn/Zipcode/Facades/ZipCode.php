<?php

namespace Ardyn\Zipcode\Facades;

use Illuminate\Support\Facades\Facade;

class ZipCode extends Facade {

 /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() {

      return 'zipcode';

  } /* function getFacadeAccessor */

} /* class ZipCode */

/* EOF */
