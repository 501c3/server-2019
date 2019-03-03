<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/16/19
 * Time: 9:28 AM
 */

namespace App\Form\Model;


use Symfony\Component\Validator\Constraints as Assert;

class Name
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $first;

    /** @var string */
    private $middle ;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $last;

    /** @var string */
    private $suffix;


    public function __construct(?string $title=null,
                                ?string $first=null,
                                ?string $middle=null,
                                ?string $last=null,
                                ?string $suffix=null)
    {
        $this->title=$title;
        $this->first=$first;
        $this->middle=$middle;
        $this->last=$last;
        $this->suffix=$suffix;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }


    /**
     * @return string|null
     */
    public function getFirst(): ?string
    {
        return $this->first;
    }

    /**
     * @return string|null
     */
    public function getMiddle(): ?string
    {
        return $this->middle;
    }


    /**
     * @return string|null
     */
    public function getLast(): ?string
    {
        return $this->last;
    }

    /**
     * @return string|null
     */
    public function getSuffix(): ?string
    {
        return $this->suffix;
    }


    public function toArray()
    {
        return ['title'=>$this->title,
                'first'=>$this->first,
                'middle'=>$this->middle,
                'last'=>$this->last,
                'suffix'=>$this->suffix];
    }

    public function isValid()
    {

        return $this->first && $this->last;
    }

    public function getErrors()
    {
        $errors=[];
        if(!$this->first) {
            $errors['first']='First name missing.';
        }
        if(!$this->last) {
            $last['last'][]='Last name missing.';
        }
        return $errors;
    }

    /**
     * @param string $title
     * @return Name
     */
    public function setTitle(string $title): Name
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $first
     * @return Name
     */
    public function setFirst(string $first): Name
    {
        $this->first = $first;
        return $this;
    }

    /**
     * @param string $middle
     * @return Name
     */
    public function setMiddle(string $middle): Name
    {
        $this->middle = $middle;
        return $this;
    }

    /**
     * @param string $last
     * @return Name
     */
    public function setLast(string $last): Name
    {
        $this->last = $last;
        return $this;
    }

    /**
     * @param string $suffix
     * @return Name
     */
    public function setSuffix(string $suffix): Name
    {
        $this->suffix = $suffix;
        return $this;
    }


}