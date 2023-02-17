<?php
// set up array of expected values and types
$expected = array('carModel'=>'string', 'year'=>'int', 'imageLocation'=>'filename');

// check each input value for type and length 
foreach ( $expected AS $key => $type ) { 

	if ( empty( $_GET[ $key ]) ) { 
		${$key} = NULL; 
		continue; 	
		
	}
			
	 


	switch ( $type ) {
	case 'string' : 
		if ( is_string( $_GET[ $key ] ) && strlen( $_GET[ $key ] ) < 256 ) {
			 ${$key} = $_GET[ $key ]; 
			 echo "valid car model : ". ${$key} ."<br />";
		} 
		break;

	case 'int' : 
	if ( is_numeric( $_GET[ $key ] )  && strlen( $_GET[ $key ] )==4) {
	//if (is_int($_GET[ $key ] )){	
			${$key} = $_GET[ $key ]; 
			echo "valid year : ". ${$key}."<br />";
		} 
		break; 

	case 'filename' : 
		// limit filenames to 64 characters 
		if ( is_string( $_GET[ $key ] ) && strlen( $_GET[ $key ] ) < 64 ) {

			// escape any non-ASCII 
			${$key} = str_replace( '%', '_', rawurlencode( $_GET[ $key ] ) ); 
			echo "File location : ". ${$key}."<br />";
			
			//$a = "bb";
			// disallow double dots 
			if ( strpos( ${$key}, '..' ) !== false) { 
				${$key} = NULL; 
				echo "you cannot use ..";

			} 
		} 
		break; 
	} 

	if ( !isset( ${$key} ) ) {
		 ${$key} = NULL; 
	} 

} 
?>
