<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Validator\FormPasswordValidator;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @covers \App\Validator\FormPasswordValidator
 */
final class FormPasswordValidatorTest extends TestCase
{
    private FormPasswordValidator $formPasswordValidator;

    public function setUp(): void
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag
            ->method('has')
            ->with('app.form-password')
            ->willReturn(true)
        ;

        $parameterBag
            ->method('get')
            ->with('app.form-password')
            ->willReturn('123')
        ;

        $this->formPasswordValidator = new FormPasswordValidator($parameterBag);
    }

    /**
     * @covers \App\Validator\FormPasswordValidator::__construct
     */
    public function test_throw_exception_when_form_password_env_key_was_not_set(): void
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag
            ->method('has')
            ->with('app.form-password')
            ->willReturn(false)
        ;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Did you forget to set the 'FORM_PASSWORD' env variable in your .env.local file?");

        new FormPasswordValidator($parameterBag);
    }

    /**
     * @covers \App\Validator\FormPasswordValidator::__construct
     */
    public function test_throw_exception_when_form_password_env_key_has_placeholder_value(): void
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag
            ->method('has')
            ->with('app.form-password')
            ->willReturn(true)
        ;

        $parameterBag
            ->method('get')
            ->with('app.form-password')
            ->willReturn('PLACEHOLDER')
        ;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Did you forget to set the 'FORM_PASSWORD' env variable in your .env.local file?");

        new FormPasswordValidator($parameterBag);
    }
}
