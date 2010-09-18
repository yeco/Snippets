/**
* @Push and element onto the end of an array with associative key
*
* @param array $array
*
* @string $key
*
* @mixed $value
*
* @return array
*
*/

function array_push_assoc($array, $key, $value){
$array[$key] = $value;
return $array;
}