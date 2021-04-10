<?php

namespace Address\Book\Traits;

/**
 * Error handler trait
 */
trait Error {

    /**
     * Hold the errors
     * 
     * @var array
     */
    public $errors = [];

    /**
     * Check field has error
     *
     * @param string $key
     *
     * @return bool
     */
    public function has_error( $key ) {
        return isset( $this->errors[ $key ] ) ? true : false;
    }

    /**
     * Get the error by key
     *
     * @param string $key
     *
     * @return string : false
     */
    public function get_error( $key ) {
        if ( isset( $this->errors[ $key ] ) ) {
            return $this->errors[ $key ];
        }

        return false;
    }
}