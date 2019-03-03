<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS","ANNOTATION"})
 */
class UniqueUser extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Already taken: {{value}}';

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}
