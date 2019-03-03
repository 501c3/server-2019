<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/16/19
 * Time: 9:29 AM
 */


namespace App\Form\Model;
use Symfony\Component\Validator\Constraints as Assert;

class Address
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $street;

    /**
     * @var string
     */
    private $department;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $state;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $postal;


    public function __construct(?string $street=null, ?string $department=null,?string $country=null,
                                ?string $city=null, ?string $state=null,?string $postal=null)
    {
        $this->street = $street;
        $this->department = $department;
        $this->country = $country;
        $this->city = $city;
        $this->state = $state;
        $this->postal = $postal;
    }


    public function getStreet(): ?string
    {
        return $this->street;
    }


    /**
     * @return string
     */
    public function getDepartment(): ?string
    {
        return $this->department;
    }

    /**
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }



    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }


    /**
     * @return string
     */
    public function getState(): ?string
    {
        return $this->state;
    }


    /**
     * @return string
     */
    public function getPostal(): ?string
    {
        return $this->postal;
    }




    public function toArray()
    {
        return ['street' => $this->street,
                'department'=>$this->department,
                'country'=>$this->country,
                'city'=>$this->city,
                'state'=>$this->state,
                'postal'=>$this->postal];
    }

    public function isValid()
    {
        return $this->street &&
                $this->country &&
                $this->city &&
                $this->state &&
                $this->postal;
    }

    public function getErrors()
    {
        $all = $this->toArray();
        unset($all['department']);
        $errors = [];
        foreach($all as $key=>$field) {
            if(is_null($field)){
                $errors[$key]=ucfirst($key).' is missing.';
            }
        }
        return $errors;
    }

    /**
     * @param string $street
     * @return Address
     */
    public function setStreet(string $street): Address
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @param string $department
     * @return Address
     */
    public function setDepartment(string $department): Address
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @param string $country
     * @return Address
     */
    public function setCountry(string $country): Address
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string $city
     * @return Address
     */
    public function setCity(string $city): Address
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $state
     * @return Address
     */
    public function setState(string $state): Address
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param string $postal
     * @return Address
     */
    public function setPostal(string $postal): Address
    {
        $this->postal = $postal;
        return $this;
    }



}