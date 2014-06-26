<?php

namespace Ardyn\Zipcode\Repositories;

interface ZipCodeInterface {

 /**
  * Retrieve a zip code record by zip code
  *
  * @access public
  * @param string $zipCode
  * @return \Ardyn\Zipcode\Models\ZipCodeModelInterface
  */
  public function findByZipCode($zipCode);

 /**
  * Calculate distance between two zip codes
  *
  * @access public
  * @param string $zip1
  * @param string $zip2
  * @param string $unit
  * @return decimal
  */
  public function distanceBetween($zip1, $zip2, $unit=null);

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
  public function radiusSearch($zipCode, $outerRadius, $innerRadius=0, $unit=null);

 /**
  * Return the nearest zip code to the WGS84 location or zip code
  *
  * @access public
  * @param string $latitude
  * @param string $longitude
  * @param string [$unit]
  * @return \Ardyn\Zipcode\Models\ZipCodeModelInterface
  */
  public function nearest($latitude, $longitude, $unit=null);

} /* interface ZipCodeInterface */

/* EOF */
