<?php

use Illuminate\Foundation\Testing\TestCase as TestBase;

class TestCase extends TestBase {

 /**
  * Creates the application.
  *
  * @access public
  * @param void
  * @return \Symfony\Component\HttpKernel\HttpKernelInterface
  */
  public function createApplication() {

    $unitTesting = true;
    $testEnvironment = 'testing';

    return require __DIR__.'/../../../../bootstrap/start.php';

  } /* function createApplication */

} /* class TestCase */

/* EOF */
