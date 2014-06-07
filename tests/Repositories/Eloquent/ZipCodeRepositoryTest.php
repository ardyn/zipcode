<?php

namespace Repositories\Eloquent;
use \TestCase;
use Mockery as m;
use Artisan;
use Config;

class ZipCodeRepositoryTest extends TestCase {

 /**
  * Setup our class
  *
  * @access public
  * @param void
  * @return void
  */
  public function setUp() {

    parent::setUp();

    // Mock Config
    $mockedConfig = m::mock('\Illuminate\Config\Repository');
    $mockedConfig->shouldReceive('get')
       ->with('ardan/zipcode::connection')
       ->andReturn('local');
    $mockedConfig->shouldReceive('get')
       ->with('ardan/zipcode::table')
       ->andReturn('zip_codes');
    $mockedConfig->shouldReceive('get')
       ->with('ardan/zipcode::primary_key')
       ->andReturn('ID');
    $mockedConfig->shouldReceive('get')
       ->with('ardan/zipcode::zip_code')
       ->andReturn('ZipCode');
    $mockedConfig->shouldReceive('get')
       ->with('ardan/zipcode::latitude')
       ->andReturn('Latitude');
    $mockedConfig->shouldReceive('get')
       ->with('ardan/zipcode::longitude')
       ->andReturn('Longitude');
    $mockedConfig->shouldReceive('get')
       ->with('ardan/zipcode::default_unit')
       ->andReturn('miles');

    $this->repository = new \Ardan\Zipcode\Repositories\Eloquent\ZipCodeRepository(
      new \Ardan\Zipcode\Models\Eloquent\ZipCode, // We need to touch the database.
      $mockedConfig
    );

  } /* function setUp */



 /**
  * Test findByZipCode
  *
  * @test
  */
  public function testFindByZipCode() {

    $result = $this->repository->findByZipCode('34050');

    $this->assertInstanceOf('Ardan\Zipcode\Models\Eloquent\ZipCode', $result);

  } /* function testFindByZipCode */



 /**
  * Test findByZipCode exception thrown
  *
  * @test
  * @expectedException Ardan\Zipcode\Exceptions\ZipCodeNotFoundException
  */
  public function testFindByZipCodeFails() {

    $result = $this->repository->findByZipCode('invalid-zip-code');

  } /* function testFindByZipCodeFails */



 /**
  * Test distanceBetween exception thrown
  *
  * @test
  * @expectedException Ardan\Zipcode\Exceptions\ZipCodeNotFoundException
  */
  public function testDistanceBetweenFailsOnZipCode() {

    $result = $this->repository->distanceBetween('34050', 'invalid-zip-code', 'miles');

  } /* function testDistanceBetweenFailsOnZipCode */



 /**
  * Test distanceBetween exception thrown
  *
  * @test
  * @expectedException Ardan\Zipcode\Exceptions\UnitNotFoundException
  */
  public function testDistanceBetweenFailsOnUnits() {

    $result = $this->repository->distanceBetween('34050', '34034', 'invalid-unit');

  } /* function testDistanceBetweenFailsOnUnits */




 /**
  * Close the tests
  *
  * @access public
  * @param void
  * @return void
  */
  public function tearDown() {

    m::close();

  } /* function tearDown */

} /* class ZipCodeRepositoryTest */

/* EOF */
