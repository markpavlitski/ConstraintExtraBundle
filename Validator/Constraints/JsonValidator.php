<?php

namespace MarkPavlitski\ConstraintExtraBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class JsonValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($error = $this->getJsonDecodeError($value)) {
            if ($this->context instanceof ExecutionContextInterface) {
                // the 2.5 API
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', $value)
                    ->setParameter('%error%', $error)
                    ->addViolation();

            } else {
                // the 2.4 API
                $this->context->addViolation(
                    $constraint->message,
                    array('%string%' => $value, '%error%' => $error)
                );
            }
        }
    }

    /**
     *
     * @param string $value
     *   A string potentially containing a JSON object.
     *
     * @return string
     *   An error message on JSON decode failure or NULL on successful decoding.
     *
     */
    private function getJsonDecodeError($value) {
        json_decode($value);
        // Switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return;
            case JSON_ERROR_DEPTH:
                return 'The maximum stack depth has been exceeded.';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Invalid or malformed JSON.';
            case JSON_ERROR_CTRL_CHAR:
                return 'Control character error, possibly incorrectly encoded.';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON.';
                // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                return 'One or more recursive references in the value to be encoded.';
                // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                return 'One or more NAN or INF values in the value to be encoded.';
            case JSON_ERROR_UNSUPPORTED_TYPE:
                return 'A value of a type that cannot be encoded was given.';
            default:
                return 'Unknown JSON error occured.';
        }
    }
}
