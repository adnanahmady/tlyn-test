<?php

namespace Tests\Feature\V1\Auth;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\Feature\FeatureTestCase;

class RegisterTest extends FeatureTestCase
{
    #[TestWith(['en', 'User registered successfully.'])]
    #[TestWith(['fa', 'کاربر با موفقیت ثبت نام شد.'])]
    public function testGivenUserWhenRegisteredThenShouldReturnExpectedResponse(
        string $locale,
        string $message,
    ): void {
        trans()->setLocale($locale);
        $this->login();
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson(route('v1.auth.register'), $data);

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('data.id')
                ->whereType('data.id', 'integer')
                ->where('data.name', $data['name'])
                ->where('data.email', $data['email'])
                ->where('meta.message', $message),
        );
    }

    public static function dataProviderForDataValidationTest(): array
    {
        return [
            'given name when is less than 3 characters then should throw validation error' => [
                [
                    'name' => 'Jo',
                    'email' => 'john@example.com',
                    'password' => 'password',
                ],
                'name',
            ],

            'given name when is not passed then should throw validation error' => [
                [
                    'email' => 'john@example.com',
                    'password' => 'password',
                ],
                'name',
            ],

            'given name when is not string then should throw validation error' => [
                [
                    'name' => 123,
                    'email' => 'john@example.com',
                    'password' => 'password',
                ],
                'name',
            ],

            'given name when is more than 255 characters then should throw validation error' => [
                [
                    'name' => str_repeat('a', 256),
                    'email' => 'john@example.com',
                    'password' => 'password',
                ],
                'name',
            ],

            'given email when is not passed then should throw validation error' => [
                [
                    'name' => 'John Doe',
                    'password' => 'password',
                ],
                'email',
            ],

            'given email when is not valid then should throw validation error' => [
                [
                    'name' => 'John Doe',
                    'email' => 'invalid-email',
                    'password' => 'password',
                ],
                'email',
            ],

            'given email when is more than 255 characters then should throw validation error' => [
                [
                    'name' => 'John Doe',
                    'email' => str_repeat('a', 247) . '@test.com',
                    'password' => 'password',
                ],
                'email',
            ],

            'given email when is not unique then should throw validation error' => [
                fn() => [
                    'name' => 'John Doe',
                    'email' => User::factory()->create()->email,
                    'password' => 'password',
                ],
                'email',
            ],

            'given password when is not passed then should throw validation error' => [
                [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                ],
                'password',
            ],

            'given password when is more than 200 characters then should throw validation error' => [
                [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'password' => str_repeat('a', 201),
                ],
                'password',
            ],

            'given password when is less than 8 characters then should throw validation error' => [
                [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'password' => '1234567',
                ],
                'password',
            ],

            'given password when is not string then should throw validation error' => [
                [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'password' => 1234567,
                ],
                'password',
            ],
        ];
    }

    #[DataProvider('dataProviderForDataValidationTest')]
    public function testDataValidation(array|callable $data, string $error): void
    {
        $this->login();
        $data = is_callable($data) ? $data() : $data;

        $response = $this->postJson(route('v1.auth.register'), $data);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors($error);
        $response->assertJsonCount(1, 'errors');
    }

    public function testGivenDataWhenCalledThenShouldReturnCreatedStatus(): void
    {
        $this->login();
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson(route('v1.auth.register'), $data);

        $response->assertCreated();
    }
}
