<?php

use Ardan\Zipcode\Exceptions\ZipCodeNotFoundException;
use Ardan\Zipcode\ZipCodeEngine;
use Mockery as m;

class EngineTest extends TestCase {

 /**
  * Setup our class
  *
  * @access public
  * @param void
  * @return void
  */
  public function setUp() {

    $this->validUnits = m::anyOf('miles', 'feet', 'kilometers', 'km', 'meters', 'm', 'radians', 'rad', 'degrees', 'deg');

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

    $mock = m::mock('\Ardan\Zipcode\Repositories\ZipCodeInterface');
    $mock->shouldReceive('findByZipCode')
         ->with('90210')
         ->once()
         ->andReturn(new \Ardan\Zipcode\Models\Eloquent\ZipCode);

    $engine = new ZipCodeEngine($mock);
    $result = $engine->find('90210');

    $this->assertInternalType('array', $result);

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

    $mock = m::mock('\Ardan\Zipcode\Repositories\ZipCodeInterface');
    $mock->shouldReceive('distanceBetween')
         ->with('90210', '12345', $this->validUnits)
         ->once()
         ->andReturn('1.2');

    $engine = new ZipCodeEngine($mock);
    $result = $engine->distance('90210', '12345', 'miles');

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

    $mock = m::mock('\Ardan\Zipcode\Repositories\ZipCodeInterface');
    $mock->shouldReceive('radiusSearch')
         ->with('90210', 0, 10, $this->validUnits)
         ->once()
         ->andReturn(new \Illuminate\Database\Eloquent\Collection);

    $engine = new ZipCodeEngine($mock);
    $result = $engine->radiusSearch('90210', 0, 10, 'miles');

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
    $mockedZipCode = m::mock('\Ardan\Zipcode\Repositories\ZipCodeInterface');
    $mockedEngine = m::mock('\Ardan\Zipcode\Engine', [$mockedZipCode]);
    $mockedEngine->shouldReceive('attribute')
                 ->once()
                 ->andReturn('value');

    $result = $mockedEngine->attribute();

    $this->assertEquals('value', $result);

  } /* function testMagicCall */

} /* class EngineTest */

/* EOF */
