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



 /**
  * Return distance constant given string name
  *
  * @access protected
  * @param string $unit
  * @return decimal
  */
  protected function getUnitConstant($unit) {

    if ( ! $unit )
      $unit = $this->config->get('ardyn/zipcode::default_unit');

    switch ( strtolower($unit) ) {

      case "miles":
        return self::MILES;

      case "feet":
        return self::FEET;

      case "km":
      case "kilometers":
        return self::KILOMETERS;

      case "m":
      case "meters":
        return self::METERS;

      case "deg":
      case "degrees":
        return self::DEGREES;

      case "rad":
      case "radians":
        return self::RADIANS;

      default:
        throw new UnitNotFoundException($unit);

    }

  } /* function getUnitConstant */

} /* class AbstractZipCodeRepository */

/* EOF */
