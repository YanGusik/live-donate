<?php

namespace Tests\Feature\Payments;

use App\Events\SendDonationNotification;
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

    /** @test */
    public function user_can_create_a_new_payment()
    {
        $this->withoutExceptionHandling();

        $response = $this->json('post', "/payments/pay", [
            'email' => 'test@yandex.ru',
            'amount' => 10,
            'currency' => 'rub',
            'type_payment' => 'paypal',
            'nickname' => 'Vlad',
            'user_id' => $this->streamer->id,
            'message' => 'I love you'
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, Payment::count());
        tap(Payment::first(), function ($payment) {
            $this->assertEquals('test@yandex.ru', $payment->email);
            $this->assertEquals(10, $payment->amount);
            $this->assertEquals('rub', $payment->currency);
            $this->assertEquals('Vlad', $payment->nickname);
            $this->assertEquals($this->streamer->id, $payment->user_id);
            $this->assertEquals('I love you', $payment->message);
        });
    }

    /** @test */
    public function user_can_create_a_new_payment_without_redirect()
    {
        $this->withoutExceptionHandling();

        $response = $this->json('post', "/payments/pay", [
            'email' => 'test@yandex.ru',
            'amount' => 10,
            'currency' => 'rub',
            'type_payment' => 'mts',
            'nickname' => 'Vlad',
            'user_id' => $this->streamer->id,
            'message' => 'I love you'
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, Payment::count());
        $response->assertJson([
            'status' => 'success'
        ]);
        $this->expectsEvents(SendDonationNotification::class);
    }

    /** @test */
    public function user_can_create_a_new_payment_with_redirect()
    {
        $this->withoutExceptionHandling();

        $response = $this->json('post', "/payments/pay", [
            'email' => 'test@yandex.ru',
            'amount' => 10,
            'currency' => 'rub',
            'type_payment' => 'paypal',
            'nickname' => 'Vlad',
            'user_id' => $this->streamer->id,
            'message' => 'I love you'
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, Payment::count());

        $response->assertJsonStructure([
            'status',
            'url'
        ]);
    }

    /** @test */
    public function email_field_should_be_a_valid_email_to_create_a_payment()
    {
        $response = $this->json('post', "/payments/pay", [
            'email' => 'not-valid-email',
            'amount' => 10,
            'currency' => 'rub',
            'payment_type' => 'paypal',
            'nickname' => 'Vlad',
            'user_id' => 2222,
            'message' => 'I love you'
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, Payment::count());
        $response->assertJsonValidationErrors('email');
    }

    /** @test */
    public function userid_field_should_be_exist_to_create_a_payment()
    {
        $response = $this->json('post', "/payments/pay", [
            'email' => 'test@yandex.ru',
            'amount' => 10,
            'currency' => 'rub',
            'payment_type' => 'mts',
            'nickname' => 'Vlad',
            'user_id' => 2222,
            'message' => 'I love you'
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, Payment::count());
    }

    // Тип платежа должен быть обслуживаемым в системе, мол нельзя использовать QIWI, если я обслуживаю только paypal

    /** @test */
    public function payment_type_field_should_be_serviceable_to_create_a_payment()
    {
        $response = $this->json('post', "/payments/pay", [
            'email' => 'test@yandex.ru',
            'amount' => 10,
            'currency' => 'rub',
            'payment_type' => 'qiwi',
            'nickname' => 'Vlad',
            'user_id' => 2222,
            'message' => 'I love you'
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, Payment::count());
    }
}
