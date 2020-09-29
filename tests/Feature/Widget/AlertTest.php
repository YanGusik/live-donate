<?php


namespace Tests\Feature\Widget;

use App\Events\SendDonationNotification;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertTest extends TestCase
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
    public function streamer_can_see_a_alert_page()
    {
        $this->get("/widget/alerts/{$this->streamer->alert_token}")
            ->assertStatus(200)
            ->assertInertia('Alert');
    }

    /** @test */
    public function token_should_be_a_valid()
    {
        $this->get("/widget/alerts/12345")
            ->assertStatus(404);
    }


}
