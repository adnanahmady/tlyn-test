<?php

namespace Tests\Feature\V1\Transactions;

use App\Models\Position;
use App\Models\Transaction;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\FeatureTestCase;

class TransactionsListTest extends FeatureTestCase
{
    #[Test]
    public function givenDataWhenItsCorrectThenShouldReturnCreatedPosition(): void
    {
        $this->withoutExceptionHandling();
        $user = $this->login();
        $buyer = Position::factory()->user($user)->create();
        $seller = Position::factory()->user($user)->create();
        Transaction::factory()->price(100_000_000)->buyer($buyer)->create();
        $transaction = Transaction::factory()->price(100_000_000)->seller($seller)->create();

        $response = $this->getJson($this->route());

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->whereType('data.0.id', 'integer')
                ->where('data.0.amount', $transaction->amount)
                ->where('data.0.fee', (int) round($transaction->fee * 0.1))
                ->where('data.0.total_payment', (int) round($transaction->total_payment * 0.1))
                ->where(
                    'data.0.price_per_gram',
                    10_000_000,
                )
                ->where(
                    'data.0.buyer_position.id',
                    $transaction->buyerPosition->getKey(),
                )
                ->where(
                    'data.0.buyer_position.type',
                    $transaction->buyerPosition->type->name,
                )
                ->where(
                    'data.0.buyer_position.status',
                    $transaction->buyerPosition->status->name,
                )
                ->where(
                    'data.0.seller_position.id',
                    $transaction->sellerPosition->getKey(),
                )
                ->where(
                    'data.0.seller_position.type',
                    $transaction->sellerPosition->type->name,
                )
                ->where(
                    'data.0.seller_position.status',
                    $transaction->sellerPosition->status->name,
                )
                ->whereType('data.0.buyer_position.user.id', 'integer')
                ->whereType('data.0.buyer_position.user.name', 'string')
                ->whereType('data.0.buyer_position.user.email', 'string')
                ->where('data.0.seller_position.user.id', $user->getKey())
                ->where('data.0.seller_position.user.name', $user->name)
                ->where('data.0.seller_position.user.email', $user->email)
                ->has('meta.links')
                ->etc(),
        );
    }

    #[Test]
    public function givenRouteWhenUserIsNotAuthenticatedThenShouldReturnUnauthorized(): void
    {
        $data = [];

        $response = $this->getJson($this->route(), $data);

        $response->assertUnauthorized();
    }

    #[Test]
    public function givenRouteWhenCalledThenShouldBeOk(): void
    {
        $this->login();

        $response = $this->getJson($this->route());

        $response->assertOk();
    }

    public function route(): string
    {
        return route('v1.own.transactions.index');
    }
}
