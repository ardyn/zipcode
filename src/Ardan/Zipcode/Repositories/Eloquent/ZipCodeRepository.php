<?php

namespace Ardan\Zipcode\Repositories\Eloquent;

use Ardan\Zipcode\Repositories\ZipCodeInterface;
use Ardan\Zipcode\Models\ZipCodeModelInterface as ZipCode;
use Illuminate\Config\Repository as Config;
use Ardan\Zipcode\Repositories\AbstractZipCodeRepository;
use Ardan\Zipcode\Exceptions\ZipCodeNotFoundException;

class ZipCodeRepository extends AbstractZipCodeRepository implements ZipCodeInterface {

 /**
  * ZipCode Model
  *
  * @var \Ardan\Zipcode\Models\ZipCodeModelInterface
  */
  private $model;

 /**
  * Column name for the zip code
  *
  * @var string
  */
  protected $zipCode;

 /**
  * Column name for the latitude
  *
  * @var string
  */
  protected $latitude;

 /**
  * Column name for the longitude
  *
  * @var string
  */
  protected $longitude;



 /**
  * Constructor
  *
  * @access public
  * @param \Ardan\Zipcode\Models\ZipCodeModelInterface $zipCode
  * @param \Illuminate\Config\Repository $config
  * @return void
  */
  public function __construct(ZipCode $zipCode, Config $config) {

    parent::__construct($config);

    // Setup the Model
    $this->model = $zipCode;
    $this->model->setConnection($config->get('ardan/zipcode::connection'));
    $this->model->setTable($config->get('ardan/zipcode::table'));
    $this->model->setPrimarykey($config->get('ardan/zipcode::zip_code'));

    // Field Names
    $this->zipCode = $config->get('ardan/zipcode::zip_code');
    $this->latitude = $config->get('ardan/zipcode::latitude');
    $this->longitude = $config->get('ardan/zipcode::longitude');

  } /* function __construct */



 /**
  * Retrieve a zip code record by zip code
  *
  * @access public
  * @param string   $zipCode
  * @return \Ardan\Zipcode\Models\Eloquent\ZipCode
  */
  public function findByZipCode($zipCode) {

    $model = $this->model->where($this->zipCode, '=', $zipCode)->first();

    if ( is_null($model) )
      throw new ZipCodeNotFoundException($zipCode);

    return $model;

  } /* function findByZipCode */



 /**
  * Calculate distance between two zip codes.
  * We are using our MySQl server to do the calculation to ensure
  * consistent results among other distance calculation queries.
  *
  * @access public
  * @param string $zip1
  * @param string $zip2
  * @param string $unit
  * @return decimal
  */
  public function distanceBetween($zip1, $zip2, $unit=null) {

    $zip1 = $this->findByZipCode($zip1);
    $latitude = $zip1->{$this->latitude};
    $longitude = $zip1->{$this->longitude};
    $constant = $this->getUnitConstant($unit);

    $result = $this->model
      ->selectRaw("WGS84distance({$latitude}, {$longitude}, {$this->latitude}, {$this->longitude}) * $constant AS `distance`")
      ->where($this->zipCode, '=', $zip2)
      ->first();

    if ( is_null($result) )
      throw new ZipCodeNotFoundException($zip2);

    return $result->distance;

  } /* function distanceBetween */



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

    $zipcode = $this->findByZipCode($zipCode);
    $latitude = $zipcode->{$this->latitude};
    $longitude = $zipcode->{$this->longitude};
    $constant = $this->getUnitConstant($unit);

    // MySQL will cache the results of the distance function during the connection
    // After the connection closes, Laravel will rember this forever.
    return $this->model
      ->rememberForever()
      ->selectRaw("*, WGS84distance($latitude, $longitude, `{$this->latitude}`, `{$this->longitude}`) * $constant AS distance")
      ->having('distance', '<=', $outerRadius)
      ->having('distance', '>=', $innerRadius)
      ->orderBy('distance', 'asc')
      ->get();

  } /* function radiusSearch */


 /**
  * Return the nearest zip code to the WGS84 location
  *
  * @access public
  * @param string $latitude
  * @param string $longitude
  * @param string [$unit]
  * @return \Ardan\Zipcode\Models\ZipCodeModelInterface
  */
  public function nearest($latitude, $longitude, $unit=null) {

    $constant = $this->getUnitConstant($unit);

    return $this->model
      ->rememberForever()
      ->selectRaw("*, WGS84distance($latitude, $longitude, `{$this->latitude}`, `{$this->longitude}`) * $constant AS distance")
      ->orderBy('distance', 'asc')
      ->first();

  } /* function nearest */

} /* class ZipCodeRepository */

/* EOF */
