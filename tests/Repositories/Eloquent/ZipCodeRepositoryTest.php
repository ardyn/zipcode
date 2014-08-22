<?php

namespace Repositories\Eloquent;

use Config;
use Artisan;
use \TestCase;
use Mockery as m;
use Ardyn\Zipcode\Repositories\AbstractZipCodeRepository as Zip;

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
       ->with('ardyn/zipcode::connection')
       ->andReturn('local');
    $mockedConfig->shouldReceive('get')
       ->with('ardyn/zipcode::table')
       ->andReturn('zip_codes');
    $mockedConfig->shouldReceive('get')
       ->with('ardyn/zipcode::primary_key')
       ->andReturn('ID');
    $mockedConfig->shouldReceive('get')
       ->with('ardyn/zipcode::zip_code')
       ->andReturn('ZipCode');
    $mockedConfig->shouldReceive('get')
       ->with('ardyn/zipcode::latitude')
       ->andReturn('Latitude');
    $mockedConfig->shouldReceive('get')
       ->with('ardyn/zipcode::longitude')
       ->andReturn('Longitude');
    $mockedConfig->shouldReceive('get')
       ->with('ardyn/zipcode::default_unit')
       ->andReturn(Zip::MILES);

    $this->repository = new \Ardyn\Zipcode\Repositories\Eloquent\ZipCodeRepository(
      new \Ardyn\Zipcode\Models\Eloquent\ZipCode, // We need to touch the database.
      $mockedConfig
    );

  } /* function setUp */



 /**
  * Test findByZipCode
  *
  * @test
  */
  public function testFindByZipCode() {

    $result = $this->repository->findByZipCode('00501');

    $this->assertInstanceOf('Ardyn\Zipcode\Models\Eloquent\ZipCode', $result);

  } /* function testFindByZipCode */



 /**
  * Test findByZipCode exception thrown
  *
  * @test
  * @expectedException Ardyn\Zipcode\Exceptions\ZipCodeNotFoundException
  */
  public function testFindByZipCodeFails() {

    $result = $this->repository->findByZipCode('invalid-zip-code');

  } /* function testFindByZipCodeFails */



 /**
  * Test distanceBetween exception thrown
  *
  * @test
  * @expectedException Ardyn\Zipcode\Exceptions\ZipCodeNotFoundException
  */
  public function testDistanceBetweenFailsOnZipCode() {

    $result = $this->repository->distanceBetween('00501', 'invalid-zip-code', Zip::MILES);

  } /* function testDistanceBetweenFailsOnZipCode */



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
