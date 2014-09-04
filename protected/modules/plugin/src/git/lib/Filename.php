<?php 
/**
* This is a validate for filename
*/
class Filename extends CValidator
{

    private $allowableCharacters = array();

    /**
     * @var array $notAllowableCharacters default use ext3 filesystem limit
     * @link http://en.wikipedia.org/wiki/Comparison_of_file_systems#Limits
     * @example $notAllowableCharacters = array('/');
     */
    public $notAllowableCharacters = array('/');

    protected function validateAttribute($object, $attribute)
    {
            if(is_array($this->notAllowableCharacters)) {
                foreach($this->notAllowableCharacters as $characters) {
                    if (strpos($object->$attribute, $characters)) {
                        $this->addError($object, $attribute, 'characters limit');
                    }
                }
            } else {
                throw new Exception('Unacceptable type, notAllowableCharacters is not an array', 1);
            }


    }
}