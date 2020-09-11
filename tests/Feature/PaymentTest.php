<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    private $streamer;

    public function setUp(): void
    {
        parent::setUp();

        $this->streamer = User::factory()->create([
            'name' => 'YanGus'
        ]);
    }

    /** @test */
    public function user_can_see_a_form_for_creating_new_payment()
    {
        $this->withoutExceptionHandling();

        $this->get("/r/{$this->streamer->name}")
            ->assertStatus(200)
            ->assertInertia('Payment');
    }

    /** @test  */
    public function user_can_create_a_new_payment()
    {
        $this->withoutExceptionHandling();

        $response = $this->json('post', "/api/payments", [
            'email' => 'test@yandex.ru',
            'amount' => 10,
            'currency' => 'rub',
            'nickname' => 'Vlad',
            'user_id' => $this->streamer->id,
            'message' => 'I love you'
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1,  Payment::count());
        tap(Payment::first(), function ($payment) {
           $this->assertEquals('test@yandex.ru', $payment->email);
           $this->assertEquals(10, $payment->amount);
           $this->assertEquals('rub', $payment->currency);
           $this->assertEquals('Vlad', $payment->nickname);
           $this->assertEquals($this->streamer->id, $payment->user_id);
           $this->assertEquals('I love you', $payment->message);
        });
    }

    /** @test  */
    public function email_field_should_be_a_valid_email_to_create_a_payment()
    {
        $response = $this->json('post', "/api/payments", [
            'email' => 'not-valid-email',
            'amount' => 10,
            'currency' => 'rub',
            'nickname' => 'Vlad',
            'user_id' => 2222,
            'message' => 'I love you'
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0,  Payment::count());
        $response->assertJsonValidationErrors('email');
    }

    /** @test  */
    public function userid_field_should_be_exist_to_create_a_payment()
    {
        $response = $this->json('post', "/api/payments", [
            'email' => 'test@yandex.ru',
            'amount' => 10,
            'currency' => 'rub',
            'nickname' => 'Vlad',
            'user_id' => 2222,
            'message' => 'I love you'
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0,  Payment::count());
    }
}
