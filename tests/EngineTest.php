<?php

use Mockery as m;
use Ardyn\Zipcode\ZipCodeEngine;
use Ardyn\Zipcode\Exceptions\ZipCodeNotFoundException;
use Ardyn\Zipcode\Repositories\AbstractZipCodeRepository as Zip;

class EngineTest extends TestCase {

 /**
  * Setup our class
  *
  * @access public
  * @param void
  * @return void
  */
  public function setUp() {

    $this->validUnits = m::anyOf(Zip::MILES, Zip::FEET, Zip::KILOMETERS, Zip::METERS, Zip::RADIANS, Zip::DEGREES);

  } /* function setUp */

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



 /**
  * Test find method.
  *
  * @test
  * @access public
  * @param void
  * @return void
  */
  public function testFind() {

    $mock = m::mock('\Ardyn\Zipcode\Repositories\ZipCodeInterface');
    $mock->shouldReceive('findByZipCode')
         ->with('90210')
         ->once()
         ->andReturn(new \Ardyn\Zipcode\Models\Eloquent\ZipCode);

    $engine = new ZipCodeEngine($mock);
    $result = $engine->find('90210');

    $this->assertInternalType('object', $result);

  } /* function testFind */



 /**
  * Test distance method.
  *
  * @test
  * @access public
  * @param void
  * @return void
  */
  public function testDistance() {

    $mock = m::mock('\Ardyn\Zipcode\Repositories\ZipCodeInterface');
    $mock->shouldReceive('distanceBetween')
         ->with('90210', '12345', $this->validUnits)
         ->once()
         ->andReturn('1.2');

    $engine = new ZipCodeEngine($mock);
    $result = $engine->distance('90210', '12345', Zip::MILES);

    $this->assertEquals('1.2', $result);

  } /* function testDistance */



 /**
  * Test radiusSearch method.
  *
  * @test
  * @access public
  * @param void
  * @return void
  */
  public function testRadiusSearch() {

    $mock = m::mock('\Ardyn\Zipcode\Repositories\ZipCodeInterface');
    $mock->shouldReceive('radiusSearch')
         ->with('90210', 0, 10, $this->validUnits)
         ->once()
         ->andReturn(new \Illuminate\Database\Eloquent\Collection);

    $engine = new ZipCodeEngine($mock);
    $result = $engine->radiusSearch('90210', 0, 10, Zip::MILES);

    $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $result);

  } /* function testRadiusSearch */



 /**
  * Test __call method.
  *
  * @test
  * @access public
  * @param void
  * @return void
  */
  public function testMagicCall() {

    // This test doesn't do anything!
    // We need to mock Engine and set fields property to an array
    // Call the magic method and ensure the value of our array is returned.
    $mockedZipCode = m::mock('\Ardyn\Zipcode\Repositories\ZipCodeInterface');
    $mockedEngine = m::mock('\Ardyn\Zipcode\Engine', [$mockedZipCode]);
    $mockedEngine->shouldReceive('attribute')
                 ->once()
                 ->andReturn('value');

    $result = $mockedEngine->attribute();

    $this->assertEquals('value', $result);

  } /* function testMagicCall */

} /* class EngineTest */

/* EOF */
