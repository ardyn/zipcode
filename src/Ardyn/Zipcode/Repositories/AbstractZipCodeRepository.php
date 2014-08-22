<?php

namespace Ardyn\Zipcode\Repositories;

use Illuminate\Config\Repository as Config;
use Ardyn\Zipcode\Exceptions\UnitNotFoundException;

abstract class AbstractZipCodeRepository {

 /**
  * Distance Constants
  * This is a rough estimation. We may want a more accurate method of calculating distances.
  *
  * @const float
  */
  const MILES = 3958.756;
  const FEET = 20902253;
  const METERS = 6371000;
  const KILOMETERS = 6371;
  const DEGREES = 57.2957795;
  const RADIANS = 1;



 /**
  * Config Repository
  *
  * @var \Illuminate\Config\Repository
  */
  protected $config;



 /**
  * Constructor
  *
  * @access public
  * @param \Illuminate\Config\Repository $config
  * @return void
  */
  public function __construct(Config $config) {

    $this->config = $config;

  } /* function __construct */

} /* class AbstractZipCodeRepository */

/* EOF */
