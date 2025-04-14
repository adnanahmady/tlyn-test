<?php

namespace Tests\Feature\V1\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\FeatureTestCase;

class LoginTest extends FeatureTestCase
{
    public static function dataProviderForDataValidationTest(): array
    {
        return [
            'given password when is wrong then should throw validation error' => [
                fn($user) => [
                    'email' => $user->email,
                    'password' => 'wrong-password',
                ],
                'email',
                'Email or password is incorrect.',
            ],

            'given email when is wrong then should throw validation error' => [
                [
                    'email' => 'dummy@email.com',
                    'password' => 'password',
                ],
                'email',
                'Email or password is incorrect.',
            ],

            'given email when is not valid email then should throw validation error' => [
                [
                    'email' => 'invalid-email',
                    'password' => 'password',
                ],
                'email',
                'The email field must be a valid email address.',
            ],

            'given email when is more than 255 characters then should throw validation error' => [
                [
                    'email' => str_repeat('a', 247) . '@test.com',
                    'password' => 'password',
                ],
                'email',
                'The email field must not be greater than 255 characters.',
            ],

            'given password when is more than 255 characters then should throw validation error' => [
                [
                    'email' => 'test@test.com',
                    'password' => str_repeat('a', 201),
                ],
                'password',
                'The password field must not be greater than 200 characters.',
                2,
            ],

            'given email when is not provided then should throw validation error' => [
                ['password' => 'password'],
                'email',
                'The email field is required.',
            ],

            'given password when is not provided then should throw validation error' => [
                ['email' => 'test@test.com'],
                'password',
                'The password field is required.',
            ],
        ];
    }

    #[Test]
    #[DataProvider('dataProviderForDataValidationTest')]
    public function dataValidation(
        array|callable $data,
        string $expectedField,
        string $expectedMessage,
        int $expectedCount = 1,
    ): void {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ]);
        $route = route('v1.auth.login');
        $data = is_callable($data) ? $data($user) : $data;

        $response = $this->postJson($route, $data);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors($expectedField);
        $response->assertJsonPath(
            'errors.' . $expectedField . '.0',
            $expectedMessage,
        );
        $response->assertJsonCount($expectedCount, 'errors');
    }

    #[Test]
    public function givenUserWhenLoggedInThenShouldReturnOk(): void
    {
        User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ]);
        $route = route('v1.auth.login');
        $data = [
            'email' => 'test@test.com',
            'password' => 'password',
        ];

        $response = $this->postJson($route, $data);

        $response->assertOk();
    }
}
