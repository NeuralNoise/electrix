<?php

/**
 *
 * @author Magesh Ravi
 * @version -----
 * @copyright Copyright (c) 2011 Magesh Ravi
 */
class InvalidFormSubmissionException extends Exception {
    
    public function __construct($message="Invalid Form submission!") {
        parent::__construct($message, 99);
    }
    
    public function __toString() {
        return __CLASS__." [{$this->code}]: {$this->message}";
    }
}

?>
