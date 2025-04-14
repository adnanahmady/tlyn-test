<?php

namespace Tests\Feature\V1\Positions;

use App\Models\Position;
use App\Types\Positions\PositionStatus;
use App\Types\Positions\PositionType;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\Feature\FeatureTestCase;

class CreatePositionTest extends FeatureTestCase
{
    #[Test]
    #[TestWith([
        'amount',
        ['price_per_gram' => 100_000_000, 'type' => PositionType::Buy->name],
        'The amount field is required.',
    ])]
    #[TestWith([
        'amount',
        ['amount' => 'string', 'price_per_gram' => 100_000_000, 'type' => PositionType::Buy->name],
        'The amount field must be a number.',
    ])]
    #[TestWith([
        'amount',
        ['amount' => 0, 'price_per_gram' => 100_000_000, 'type' => PositionType::Buy->name],
        'The amount field must be at least 1.',
    ])]
    #[TestWith([
        'price_per_gram',
        ['amount' => 13, 'type' => PositionType::Buy->name],
        'The price per gram field is required.',
    ])]
    #[TestWith([
        'price_per_gram',
        ['amount' => 13, 'price_per_gram' => 'string', 'type' => PositionType::Buy->name],
        'The price per gram field must be an integer.',
    ])]
    #[TestWith([
        'price_per_gram',
        ['amount' => 13, 'price_per_gram' => 0, 'type' => PositionType::Buy->name],
        'The price per gram field must be at least 1.',
    ])]
    #[TestWith([
        'type',
        ['amount' => 13, 'price_per_gram' => 100_000_000],
        'The type field is required.',
    ])]
    #[TestWith([
        'type',
        ['amount' => 13, 'price_per_gram' => 100_000_000, 'type' => 'invalid'],
        'The selected type is invalid.',
    ])]
    public function givenDataWhenItsInvalidThenShouldReturnValidationError(
        string $field,
        array $data,
        string $message,
    ): void {
        $this->login();

        $response = $this->postJson($this->route(), $data);

        $response->assertUnprocessable();
        $response->assertJsonPath('errors.' . $field, [$message]);
        $response->assertJsonCount(1, 'errors');
    }

    #[Test]
    #[TestWith([PositionType::Buy])]
    #[TestWith([PositionType::Sell])]
    public function givenDataWhenItsCorrectThenShouldStorePosition(PositionType $type): void
    {
        $this->withoutExceptionHandling();
        $user = $this->login();
        $data = [
            'amount' => 13,
            'price_per_gram' => 100_000_000,
            'type' => $type->name,
        ];

        $response = $this->postJson($this->route(), $data);

        $this->assertDatabaseHas(Position::class, [
            'id' => $response->json('data.id'),
            'base_amount' => $data['amount'],
            'amount' => $data['amount'],
            'price_per_gram' => $data['price_per_gram'],
            'type' => $type->value,
            'status' => PositionStatus::Open->value,
            'user_id' => $user->getKey(),
        ]);
    }

    #[Test]
    #[TestWith(['en', 'Position created successfully.'])]
    #[TestWith(['fa', 'موقعیت با موفقیت ایجاد شد.'])]
    public function givenDataWhenItsCorrectThenShouldReturnCreatedPosition(
        string $locale,
        string $message,
    ): void {
        $this->withoutExceptionHandling();
        trans()->setLocale($locale);
        $user = $this->login();
        $data = [
            'amount' => 13,
            'price_per_gram' => 100_000_000,
            'type' => PositionType::Buy->name,
        ];

        $response = $this->postJson($this->route(), $data);

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('data.id')
                ->whereType('data.id', 'integer')
                ->where('data.amount', $data['amount'])
                ->where('data.price_per_gram', $data['price_per_gram'])
                ->where('data.type', $data['type'])
                ->where('data.status', PositionStatus::Open->name)
                ->where('data.user.id', $user->getKey())
                ->where('data.user.name', $user->name)
                ->where('data.user.email', $user->email)
                ->where('meta.message', $message),
        );
    }

    #[Test]
    public function givenRouteWhenUserIsNotAuthenticatedThenShouldReturnUnauthorized(): void
    {
        $data = [];

        $response = $this->postJson($this->route(), $data);

        $response->assertUnauthorized();
    }

    #[Test]
    public function givenDataWhenCalledThenShouldBeCreated(): void
    {
        $this->login();
        $data = [
            'amount' => 13.301,
            'price_per_gram' => 100_000_000,
            'type' => PositionType::Buy->name,
        ];

        $response = $this->postJson($this->route(), $data);

        $response->assertCreated();
    }

    public function route(): string
    {
        return route('v1.positions.create');
    }
}
