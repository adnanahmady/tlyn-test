<?php

namespace Tests\Feature\Jobs;

use App\Jobs\CreateTransactionsJob;
use App\Models\Position;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Positions\PositionRepositoryInterface;
use App\Services\V1\Transactions\CreateTransactionService;
use App\Types\Positions\PositionStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\Feature\FeatureTestCase;

class CreateTransactionsJobTest extends FeatureTestCase
{
    #[Test]
    public function givenBuyerAndSellerWhenAreBothForSameUserThenTransactionMustGetIgnored(): void
    {
        $price = 100_000_000;
        $user = User::factory()->create();
        $seller1 = Position::factory()->sell()->amount(6)->baseAmount(30)
            ->user($user)->pricePerGram($price)->create();
        $seller2 = Position::factory()->sell()->amount(7)->baseAmount(30)
            ->pricePerGram($price)->create();
        $buyer = Position::factory()->buy()->amount(6)->baseAmount(6)
            ->user($user)->pricePerGram($price)->create();
        $job = $this->job();

        $job->handle();

        $this->assertDatabaseHas(Position::class, [
            'id' => $buyer->getKey(),
            'amount' => 0,
        ]);
        $this->assertDatabaseHas(Position::class, [
            'id' => $seller1->getKey(),
            'amount' => 6,
        ]);
        $this->assertDatabaseHas(Position::class, [
            'id' => $seller2->getKey(),
            'amount' => 1,
        ]);
        $this->assertDatabaseMissing(Transaction::class, [
            'buyer_position_id' => $buyer->getKey(),
            'seller_position_id' => $seller1->getKey(),
            'amount' => 6,
        ]);
        $this->assertDatabaseHas(Transaction::class, [
            'buyer_position_id' => $buyer->getKey(),
            'seller_position_id' => $seller2->getKey(),
            'amount' => 6,
        ]);
    }

    public static function dataProviderForFeeCalculationTest(): array
    {
        return [
            'given 15 grams when calculated fee is over 5 milion tomans then fee should not get over 5 milion toman' => [
                15,
                2_500_001_000,
                15 * 2_500_001_000 + 50_000_000,
                50_000_000,
            ],

            'given 10 grams when calculated fee is over 5 milion tomans then fee should not get over 5 milion toman' => [
                10,
                2_500_001_000,
                10 * 2_500_001_000 + 50_000_000,
                50_000_000,
            ],

            'given 2 grams when calculated fee is over 5 milion tomans then fee should not get over 5 milion toman' => [
                2,
                2_500_001_000,
                2 * 2_500_001_000 + 50_000_000,
                50_000_000,
            ],

            'given 1 gram when calculated fee is over 5 milion tomans then fee should not get over 5 milion toman' => [
                1,
                2_500_001_000,
                1 * 2_500_001_000 + 50_000_000,
                50_000_000,
            ],

            'given 13 grams when fee is in accepting range then calculated fee should accepted' => [
                13,
                100_000_000,
                13 * 100_000_000 + 13_000_000,
                13_000_000,
            ],

            'given 10 grams when fee is in accepting range then calculated fee should accepted' => [
                10,
                100_000_000,
                10 * 100_000_000 + 15_000_000,
                15_000_000,
            ],

            'given 3 grams when fee is in accepting range then calculated fee should accepted' => [
                3,
                100_000_000,
                3 * 100_000_000 + 4_500_000,
                4_500_000,
            ],

            'given 1 gram when fee is in accepting range then calculated fee should accepted' => [
                1,
                100_000_000,
                1 * 100_000_000 + 2_000_000,
                2_000_000,
            ],

            'given 13 grams when price is medium then should take 1% fee' => [
                13,
                10_000_000,
                13 * 10_000_000 + 1_300_000,
                1_300_000,
            ],

            'given 10 grams when price is medium then should take 1.5% fee' => [
                10,
                10_000_000,
                10 * 10_000_000 + 1_500_000,
                1_500_000,
            ],

            'given 3 grams when price is medium then should take minimum fee' => [
                3,
                10_000_000,
                3 * 10_000_000 + 500_000,
                500_000,
            ],

            'given 1 gram when price is medium then should take minimum fee' => [
                1,
                10_000_000,
                1 * 10_000_000 + 500_000,
                500_000,
            ],

            'given 13 grams when price is low then should take minimum fee' => [
                13,
                1_000_000,
                13 * 1_000_000 + 500_000,
                500_000,
            ],

            'given 10 grams when price is low then should take minimum fee' => [
                10,
                1_000_000,
                10 * 1_000_000 + 500_000,
                500_000,
            ],

            'given 3 grams when price is low then should take minimum fee' => [
                3,
                1_000_000,
                3 * 1_000_000 + 500_000,
                500_000,
            ],

            'given 1 gram when price is low then should take minimum fee' => [
                1,
                1_000_000,
                1 * 1_000_000 + 500_000,
                500_000,
            ],
        ];
    }

    /** @test */
    #[Test]
    #[DataProvider('dataProviderForFeeCalculationTest')]
    public function givenDifferentGramsOfGoldWhenSoldThenShouldTakeExpectedFee(
        int $amount,
        int $price,
        int $expectedPayment,
        int $expectedFee,
    ): void {
        $this->login();
        $buyer = Position::factory()->buy()->amount($amount)->baseAmount($amount)
            ->pricePerGram($price)->create();
        $seller = Position::factory()->sell()->amount($amount)->baseAmount($amount)
            ->pricePerGram($price)->create();
        $job = $this->job();

        $job->handle();

        $this->assertDatabaseHas(Transaction::class, [
            'buyer_position_id' => $buyer->getKey(),
            'seller_position_id' => $seller->getKey(),
            'amount' => $amount,
            'total_payment' => $expectedPayment,
            'fee' => $expectedFee,
        ]);
    }

    #[Test]
    public function givenBuyerGreaterThanSellerWhenAmountBoughtThenBuyerRemainingAmountShouldBeAsExpected(): void
    {
        $price = 100_000_000;
        $buyAmount = 9;
        $seller1 = Position::factory()->sell()->amount(6)->baseAmount(30)
            ->pricePerGram($price)->create();
        $seller2 = Position::factory()->sell()->amount(10)->baseAmount(50)
            ->pricePerGram($price + 10)->create();
        $buyer = Position::factory()->buy()->amount($buyAmount)->baseAmount($buyAmount)
            ->pricePerGram($price)->create();
        $job = $this->job();

        $job->handle();

        $this->assertDatabaseHas(Position::class, [
            'id' => $seller1->getKey(),
            'amount' => 0,
        ]);
        $this->assertDatabaseHas(Position::class, [
            'id' => $seller2->getKey(),
            'amount' => 10,
        ]);
        $this->assertDatabaseHas(Transaction::class, [
            'buyer_position_id' => $buyer->getKey(),
            'seller_position_id' => $seller1->getKey(),
            'amount' => 6,
        ]);
        $this->assertDatabaseMissing(Transaction::class, [
            'buyer_position_id' => $buyer->getKey(),
            'seller_position_id' => $seller2->getKey(),
            'amount' => 3,
        ]);
    }

    #[Test]
    #[TestDox(
        'given b as buyer and s as seller ' .
        'when b(9) < s(10) but b(9) > s1(6) and b(9) < s2(10) ' .
        'then b amount should be 0 and s1 amount should be 0 and s2 amount should be 7',
    )]
    public function givenBuyerLessThanSellerWhenAmountBoughtThenShouldCloseFirstSellerPosition(): void
    {
        $price = 100_000_000;
        $buyAmount = 9;
        $seller1 = Position::factory()->sell()->amount(6)->baseAmount(30)
            ->pricePerGram($price)->create();
        $seller2 = Position::factory()->sell()->amount(10)->baseAmount(50)
            ->pricePerGram($price)->create();
        $buyer = Position::factory()->buy()->amount($buyAmount)->baseAmount($buyAmount)
            ->pricePerGram($price)->create();
        $job = $this->job();

        $job->handle();

        $this->assertDatabaseHas(Position::class, [
            'id' => $seller1->getKey(),
            'amount' => 0,
        ]);
        $this->assertDatabaseHas(Position::class, [
            'id' => $seller2->getKey(),
            'amount' => 7,
        ]);
        $this->assertDatabaseHas(Transaction::class, [
            'buyer_position_id' => $buyer->getKey(),
            'seller_position_id' => $seller1->getKey(),
            'amount' => 6,
        ]);
        $this->assertDatabaseHas(Transaction::class, [
            'buyer_position_id' => $buyer->getKey(),
            'seller_position_id' => $seller2->getKey(),
            'amount' => 3,
        ]);
    }

    #[Test]
    public function givenTwoPositionsWhenAreMatchedThenShouldDeclineBuyerAmount(): void
    {
        $price = 100_000_000;
        $buyAmount = 9;
        Position::factory()->sell()->amount(100)->baseAmount(100)
            ->pricePerGram($price)->create();
        $buyer = Position::factory()->buy()->amount($buyAmount)->baseAmount($buyAmount)
            ->pricePerGram($price)->create();
        $job = $this->job();

        $job->handle();

        $this->assertDatabaseHas(Position::class, [
            'id' => $buyer->getKey(),
            'base_amount' => 9,
            'amount' => 0,
        ]);
    }

    #[Test]
    public function givenTwoPositionsWhenAreMatchedThenShouldDeclineSellerAmount(): void
    {
        $price = 100_000_000;
        $buyAmount = 9;
        $seller = Position::factory()->sell()->amount(100)->baseAmount(100)
            ->pricePerGram($price)->create();
        Position::factory()->buy()->amount($buyAmount)->baseAmount($buyAmount)
            ->pricePerGram($price)->create();
        $job = $this->job();

        $job->handle();

        $this->assertDatabaseHas(Position::class, [
            'id' => $seller->getKey(),
            'base_amount' => 100,
            'amount' => 100 - $buyAmount,
        ]);
    }

    #[Test]
    public function givenTwoPositionsWhenAreMatchedThenShouldCreateTransaction(): void
    {
        $price = 100_000_000;
        $buyAmount = 9;
        $seller = Position::factory()->sell()->amount(100)->baseAmount(100)
            ->pricePerGram($price)->create();
        $buyer = Position::factory()->buy()->amount($buyAmount)->baseAmount($buyAmount)
            ->pricePerGram($price)->create();
        $job = $this->job();

        $job->handle();

        $fee = $buyAmount * $price * 0.015;
        $this->assertDatabaseHas(Position::class, [
            'id' => $buyer->getKey(),
            'amount' => 0,
            'status' => PositionStatus::Closed->value,
        ]);
        $this->assertDatabaseHas(Transaction::class, [
            'buyer_position_id' => $buyer->getKey(),
            'seller_position_id' => $seller->getKey(),
            'amount' => $buyAmount,
            'price_per_gram' => $price,
            'fee' => $fee,
            'total_payment' => $buyAmount * $price + $fee,
        ]);
    }

    private function job(): CreateTransactionsJob
    {
        $service = app(CreateTransactionService::class);
        $pRepo = app(PositionRepositoryInterface::class);

        return new CreateTransactionsJob($service, $pRepo);
    }
}
