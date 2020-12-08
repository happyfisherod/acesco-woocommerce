<?php

namespace WordFrame;

use Throwable;

class WordFrameException extends \Exception {
	public function __construct( $message = "", $code = 0, Throwable $previous = null ) {
		parent::__construct( $message, $code, $previous );
	}
}