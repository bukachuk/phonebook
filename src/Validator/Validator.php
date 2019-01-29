<?php

namespace Project\Validator;

use Project\Entity\PhoneBook;
use Project\Service\Hostaway;

/**
 * Class Validator
 * Phonebook API Validator
 *
 * @package Project\Validator
 */
class Validator
{
    const MESSAGES = [
        'firstName' => 'firstName is required',
        'phone' => 'phone is required',
        'phoneFormat' => 'phone has a wrong format',
        'countryCodeFormat' => 'countryCode has a wrong format',
        'timeZoneFormat' => 'timeZone has a wrong format',
    ];

    /**
     * @param PhoneBook $phoneBook
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function validate(PhoneBook $phoneBook)
    {
        $messages = [];

        if(!$phoneBook->getFirstName()){
            $messages[] = self::MESSAGES['firstName'];
        }

        if(!$phoneBook->getPhone()){
            $messages[] = self::MESSAGES['phone'];
        }

        if(!preg_match("/^\+[\d]{4,12}$/", $phoneBook->getPhone())){
            $messages[] = self::MESSAGES['phoneFormat'];
        }

        if($messages){
            return $messages;
        }

        return $this->validateHostaway($phoneBook);
    }

    /**
     * Validate country and timezone by Hostaway API
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function validateHostaway(PhoneBook $phoneBook){
        try {
            $hostaway = new Hostaway();

            if($phoneBook->getCountryCode()){
                $result = $hostaway->getCountries();

                if(false === array_key_exists($phoneBook->getCountryCode(), $result['result'])){
                    $messages[] = self::MESSAGES['countryCodeFormat'];
                }
            }

            if($phoneBook->getTimeZone()){
                $result = $hostaway->getTimezones();

                if(false === array_key_exists($phoneBook->getTimeZone(), $result['result'])){
                    $messages[] = self::MESSAGES['timeZoneFormat'];
                }
            }
        } catch (\Exception $e){
            $messages[] = (DEBUG) ? $e->getMessage() : 'Hostaway API Error';
        }

        return $messages;
    }
}