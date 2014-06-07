<?php

namespace Ardan\Zipcode\Exceptions;

use Exception;

class AttributeNotFoundException extends ZipCodeException {

 /**
  * Set our custom message
  *
  * @access public
  * @param string $message
  * @param int $code
  * @param \Exception $previous
  */
  public function __construct($message, $code=0, $previous=null) {

    $message = 'Attribute "'.$message.'" does not exist.';

    parent::__construct($message, $code, $previous);

  } /* function __construct */

 } /* AttributeNotFoundException */

/* EOF */
