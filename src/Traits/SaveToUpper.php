<?php


namespace Tsung\NovaUserManagement\Traits;


trait SaveToUpper
{
    protected $no_uppercase = [
        'password',
        'username',
        'email',
        'remember_token',
        'slug',
    ];

    public function setAttribute( $key, $value )
    {
        parent::setAttribute( $key, $value );

        if ( is_string( $value ) ) {

            if ( $this->no_upper !== null ) {

                if ( !in_array( $key, $this->no_uppercase ) ) {

                    if ( !in_array( $key, $this->no_upper ) ) {

                        $this->attributes[ $key ] = trim( strtoupper( $value ) );

                    }

                }

            } else {

                if ( !in_array( $key, $this->no_uppercase ) ) {

                    $this->attributes[ $key ] = trim( strtoupper( $value ) );
                }

            }

        }

    }
}
