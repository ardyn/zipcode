<?php

namespace Ardyn\Zipcode;

use Ardyn\Zipcode\Repositories\ZipCodeInterface;

class ZipCodeEngine {

 /**
  * The ZipCode Repository
  *
  * @var \Ardyn\Zipcode\Repositories\ZipCodeInterface
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
  * @param \Ardyn\Zipcode\Repositories\ZipCodeInterface
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
  * @return \Ardyn\Zipcode\ZipCodeEngine
  */
  public function find($zipCode) {

    $model = $this->zipCode->findByZipCode($zipCode);
    $this->fields = $model->toArray();

    return $this;

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
  * @return \Ardyn\Zipcode\Models\ZipCodeModelInterface
  */
  public function nearest($latitude, $longitude) {

    return $this->zipCode->nearest($latitude, $longitude);

  } /* function nearest */



  /**
   * Return latitude
   *
   * @access public
   * @param void
   * @return decimal
   */
  public function latitude() {

    return $this->getValueByAlias('latitude');

  } /* function latitude */



  /**
   * Return longitude
   *
   * @access public
   * @param void
   * @return decimal
   */
  public function longitude() {

    return $this->getValueByAlias('longitude');

  } /* function longitude */



  /**
   * Return zip code
   *
   * @access public
   * @param void
   * @return string
   */
  public function zipCode() {

    return $this->getValueByAlias('zip_code');

  } /* function zipCode */



  /**
   * We are using aliases to reference actual fields in the model. Grab the
   * actual name of the field from the config file and return the value
   * of the column we are trying to access via the alias.
   *
   * @access private
   * @param string $column
   * @return string
   */
  private function getValueByAlias($column) {

    return $this->{$this->zipCode->getConfigValue($column)};

  } /* function getValue */



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

    switch ( $attribute ) {
      case 'latitude':
        return $this->latitude();
      case 'longitude':
        return $this->longitude();
      case 'zipCode':
        return $this->zipCode();
      default:
        if ( array_key_exists($attribute, $this->fields) )
          return $this->fields[$attribute];
        return null;
    }

  } /* function __get */

} /* class ZipCodeEngine */

/* EOF */
