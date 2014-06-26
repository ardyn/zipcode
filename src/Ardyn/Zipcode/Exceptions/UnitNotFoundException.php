<?php

namespace Ardyn\Zipcode\Exceptions;

use Exception;

class UnitNotFoundException extends ZipCodeException {

 /**
  * Set our custom message
  *
  * @access public
  * @param string $message
  * @param int $code
  * @param \Exception $previous
  */
  public function __construct($message, $code=0, $previous=null) {

    $message = 'The unit of distance "'.$message.'" does not exists.';

    parent::__construct($message, $code, $previous);

  } /* function __construct */

} /* class UnitNotFoundException */

/* EOF */
