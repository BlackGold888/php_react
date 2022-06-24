<?php

namespace App;

class Validator
{
    /**
     * @param $data
     * @param $rules
     * @return array
     */
    public static function validate($data, $rules): array
    {
        $errors = [];
        foreach ($rules as $key => $value) {
            if (isset($data[$key])) {
                if ($value == 'required' && empty($data[$key])) {
                    $errors[$key] = 'This field is required.';
                } elseif ($value == 'email' && !filter_var($data[$key], FILTER_VALIDATE_EMAIL)) {
                    $errors[$key] = 'This field must be a valid email address.';
                } elseif ($value == 'number' && !is_numeric($data[$key])) {
                    $errors[$key] = 'This field must be a number.';
                } elseif ($value == 'min' && strlen($data[$key]) < $value[1]) {
                    $errors[$key] = 'This field must be at least ' . $value[1] . ' characters long.';
                } elseif ($value == 'max' && strlen($data[$key]) > $value[1]) {
                    $errors[$key] = 'This field must be at most ' . $value[1] . ' characters long.';
                } elseif ($value == 'regex' && !preg_match($value[1], $data[$key])) {
                    $errors[$key] = 'This field must match the regex ' . $value[1] . '.';
                }
            }else{
                $errors[$key] = 'This field is required.';
            }
        }
        return $errors;
    }
    
    /**
     * @param $data
     * @return array
     */
    public static function validateLogin($data): array
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];
        
        $data = (array)$data;
        return self::validate($data, $rules);
    }
    
    /**
     * @param $data
     * @return array
     */
    public static function validateRegister($data): array
    {
        $rules = [
            'username' => 'required|min:3|max:20|regex:/^[a-zA-Z0-9]+$/',
            'password' => 'required|min:3|max:20|regex:/^[a-zA-Z0-9]+$/',
            'email' => 'required|email'
        ];
        $data = (array)$data;
        return self::validate($data, $rules);
    }
    
    /**
     * @param $data
     * @return array
     */
    public static function validateUpdate($data): array
    {
        $rules = [
            'username' => 'required|min:3|max:20|regex:/^[a-zA-Z0-9]+$/',
            'password' => 'required|min:3|max:20|regex:/^[a-zA-Z0-9]+$/',
            'email' => 'required|email'
        ];
        return self::validate($data, $rules);
    }
    
}