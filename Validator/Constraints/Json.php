<?php

namespace MarkPavlitski\ConstraintExtraBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Json extends Constraint
{
    public $message = 'The string "%string%" is not a valid JSON object: %error%';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}
