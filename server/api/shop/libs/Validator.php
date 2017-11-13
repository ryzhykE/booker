<?php

class Validator
{
    protected $data;
    public $value;

    /**
     * login validator
     * @param string $data
     * @return bool
     */
    public function loginValid($data)
    {

        $pattern = '/^[a-zA-Z][a-zA-Z0-9-_\.]{3,10}$/';
        if(preg_match($pattern, $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * validator password
     * @param $data
     * @return bool
     */
    public function passwordValid($data)
    {
        $pattern = '/^([0-9a-z]{6,10})$/i';
        if(preg_match($pattern, $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * cler data
     * @param $data
     * @return string
     */
    public function clearData($data)
    {
        $this->value = trim($data);
        $this->value = stripslashes($data);
        $this->value = strip_tags($data);
        $this->value = htmlspecialchars($data);
        return $this->value;
    }

}


