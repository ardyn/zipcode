<?php

namespace Ardan\Zipcode;

use Ardan\Zipcode\Repositories\ZipCodeInterface;
use Ardan\Zipcode\Exceptions\AttributeNotFoundException;

class ZipCodeEngine {

 /**
  * The ZipCode Repository
  *
  * @var \Ardan\Zipcode\Repositories\ZipCodeInterface
  */
  private $zipCode;

 /**
  * ZipCode record fields
  *
  * @var array
  */
  private $fields = array();



 /**
  * Constructor
  *
  * @access public
  * @param \Ardan\Zipcode\Repositories\ZipCodeInterface
  * @return void
  */
  public function __construct(ZipCodeInterface $zipCode) {

    $this->zipCode = $zipCode;

  } /* function __construct */



 /**
  * Find a zip code by zip code and return the model as an object.
  *
  * @access public
  * @param sting $zipCode
  * @return stdClass
  */
  public function find($zipCode) {

    $model = $this->zipCode->findByZipCode($zipCode);
    $this->fields = $model->toArray();

    return $model;

  } /* function find */



 /**
  * Calculate the distance between two zip codes
  *
  * @access private
  * @param string $zip1
  * @param string $zip2
  * @param string [$units]
  * @return decimal
  */
  public function distance($zip1, $zip2, $unit=null) {

    return $this->zipCode->distanceBetween($zip1, $zip2, $unit);

  } /* function distance */



 /**
  * Return all zip codes within an inner radius and an outer radius (a donut)
  *
  * @access public
  * @param string $zipCode
  * @param decimal $outerRadius
  * @param decimal [$innerRadius]
  * @param string [$unit]
  * @return \Illuminate\Database\Eloquent\Collection
  */
  public function radiusSearch($zipCode, $outerRadius, $innerRadius=0, $unit=null) {

    return $this->zipCode->radiusSearch($zipCode, $outerRadius, $innerRadius, $unit);

  } /* function radiusSearch */


 /**
  * Return the nearest zip code to the WGS84 location
  *
  * @access public
  * @param string $latitude
  * @param string $longitude
  * @return \Ardan\Zipcode\Models\ZipCodeModelInterface
  */
  public function nearest($latitude, $longitude) {

    return $this->zipCode->nearest($latitude, $longitude);

  } /* function nearest */



 /**
  * Magic methods to return Model properties
  *
  * @access public
  * @param string $attribute
  * @param array $param
  * @return string
  */
  public function __call($attribute, $param) {

    return $this->$attribute;

  } /* function __call */



 /**
  * Magic methods to return Model properties
  *
  * @access public
  * @param string $attribute
  * @return string
  */
  public function __get($attribute) {

    if ( ! array_key_exists($attribute, $this->fields) )
      throw new AttributeNotFoundException($attribute);

    return $this->fields[$attribute];

  } /* function __get */

} /* class ZipCodeEngine */

/* EOF */
