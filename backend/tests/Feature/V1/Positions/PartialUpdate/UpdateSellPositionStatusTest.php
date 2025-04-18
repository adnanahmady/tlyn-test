<?php

namespace Tests\Feature\V1\Positions\PartialUpdate;

use App\Exceptions\ForbiddenToUpdateBuyPositionException;
use App\Models\Position;
use App\Models\User;
use App\Types\Positions\PositionStatus;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\Feature\FeatureTestCase;

class UpdateSellPositionStatusTest extends FeatureTestCase
{
    #[Test]
    public function givenInvalidStatusWhenCalledThenShouldReturnValidationError(): void
    {
        $user = $this->login();
        $data = ['status' => 'invalid-status'];
        [$route] = $this->route($user);

        $response = $this->patchJson($route, $data);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'status' => ['The selected status is invalid.'],
        ]);
    }

    #[Test]
    public function givenAuthenticatedUserWhenItsNotPositionOwnerThenShouldBeForbidden(): void
    {
        $this->login();
        $data = ['status' => PositionStatus::Canceled->name];
        $position = Position::factory()->sell()->create();
        [$route] = $this->route(position: $position);

        $response = $this->patchJson($route, $data);

        $response->assertForbidden();
    }

    #[Test]
    #[TestWith(['en', 'Only sell positions are allowed to edit.'])]
    #[TestWith(['fa', 'فقط موقعیت های فروش مجاز به ویرایش هستند.'])]
    public function givenPositionWithBuyTypeWhenCalledThenShouldBeForbidden(
        string $locale,
        string $message,
    ): void {
        // Assert
        $this->withoutExceptionHandling();
        $this->expectException(ForbiddenToUpdateBuyPositionException::class);
        $this->expectExceptionMessage($message);

        // Arrange
        trans()->setLocale($locale);
        $user = $this->login();
        $data = ['status' => PositionStatus::Canceled->name];
        $position = Position::factory()->buy()->user($user)->create();
        [$route] = $this->route(position: $position);

        // Act
        $this->patchJson($route, $data);
    }

    #[Test]
    #[TestWith(['en', PositionStatus::Open, 'Position updated successfully.'])]
    #[TestWith(['fa', PositionStatus::Open, 'موقعیت با موفقیت بروزرسانی شد.'])]
    public function givenDataWhenItsCorrectThenShouldReturnUpdatedPosition(
        string $locale,
        PositionStatus $status,
        string $message,
    ): void {
        $this->withoutExceptionHandling();
        trans()->setLocale($locale);
        $user = $this->login();
        $data = ['status' => $status->name];
        [$route, $position] = $this->route($user);

        $response = $this->patchJson($route, $data);

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('data.id')
                ->whereType('data.id', 'integer')
                ->where('data.amount', (int) $position->amount)
                ->where('data.price_per_gram', (int) round($position->price_per_gram * 0.1))
                ->where('data.type', $position->type->name)
                ->where('data.status', $status->name)
                ->where('data.user.id', $user->getKey())
                ->where('data.user.name', $user->name)
                ->where('data.user.email', $user->email)
                ->where('meta.message', $message),
        );
    }

    #[Test]
    public function givenRouteWhenUserIsNotAuthenticatedThenShouldReturnOk(): void
    {
        $user = $this->login();
        [$route] = $this->route($user);
        $data = [];

        $response = $this->patchJson($route, $data);

        $response->assertOk();
    }

    #[Test]
    public function givenDataWhenCalledThenShouldBeOk(): void
    {
        $user = $this->login();
        [$route] = $this->route($user);
        $data = [
            'status' => PositionStatus::Canceled->name,
        ];

        $response = $this->patchJson($route, $data);

        $response->assertOk();
    }

    public function route(?User $user = null, ?Position $position = null): array
    {
        $position ??= Position::factory()
            ->amount(3)
            ->baseAmount(10)
            ->sell()
            ->user($user)
            ->create();

        return [route(
            'v1.positions.sells.update-status',
            ['position' => $position],
        ), $position];
    }
}
