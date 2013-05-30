<?php

class Knowledgeroot_Util {
    public static function objectToArray( $object )
    {
	if( !is_object( $object ) && !is_array( $object ) ) {
	    return $object;
	}

	if( is_object( $object ) ) {
	    $object = get_object_vars( $object );
	}

	return array_map( 'Knowledgeroot_Util::objectToArray', $object );
    }
}
