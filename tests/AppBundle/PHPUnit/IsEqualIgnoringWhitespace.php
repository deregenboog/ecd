<?php

namespace Tests\AppBundle\PHPUnit;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\ScalarComparator;

final class IsEqualIgnoringWhitespace extends Constraint
{
    /**
     * @var mixed
     */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Evaluates the constraint for parameter $other.
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @throws ExpectationFailedException
     */
    public function evaluate($other, string $description = '', bool $returnResult = false): ?bool
    {
        if ($this->stripWhitespace($this->value) == $this->stripWhitespace($other)) {
            return true;
        }

        try {
            (new ScalarComparator())->assertEquals(
                $this->stripWhitespace($this->value),
                $this->stripWhitespace($other),
            );
        } catch (ComparisonFailure $f) {
            if ($returnResult) {
                return false;
            }

            throw new ExpectationFailedException(
                trim($description . "\n" . $f->getMessage()),
                $f,
            );
        }

        return true;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function toString(): string
    {
        if (is_string($this->value)) {
            if (strpos($this->value, "\n") !== false) {
                return 'is equal to <text>';
            }

            return sprintf(
                "is equal to '%s'",
                $this->value,
            );
        }

        return sprintf(
            'is equal to %s',
            $this->exporter()->export($this->value),
        );
    }

    protected static function stripWhitespace(string $value): string
    {
        $value = preg_replace('/[\n\r\t\v\x00]/', '', $value);
        $value = preg_replace('/\s+/', ' ', $value);
        $value = str_replace(['( ', ' )'], ['(', ')'], $value);

        return $value;
    }
}