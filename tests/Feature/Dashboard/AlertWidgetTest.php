<?php


namespace Tests\Feature\Dashboard;

use App\Models\User;
use App\Models\Variation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertWidgetTest extends TestCase
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

    public function getVariationData($default = false): array
    {
        return [
            'name' => 'Test1',
            'group_id' => 1,
            'is_active' => 1,
            'variation_conditions_mode' => 'amount_equal_to', // или amount_equal_or_greater_than или random
            'variation_conditions_value' => 3453453,
            'display_duration' => 9,
            'text_delay' => 0,
            'text_duration' => 8,
            'background_color' => '#00FF00',
            'display_pattern' => 'image_top__text_bottom',
            'image' => 'images/2/zombie.gif',
            'sound' => 'sounds/2/point.mp3',
            'volume' => 50,
            'header_template' => '{username} - {amount}!',
            'header_text' => '{"font_style":{"font-family":"\"Roboto Condensed\"","font-size":"60px","color":"#FB8C2B","font-weight":"bold","font-style":"normal","text-decoration":"none","text-transform":"none","text-shadow":"1px","text-shadow_color":"rgb(0, 0, 0)","letter-spacing":"0px","word-spacing":"0px","text-align":"center","vertical-align":"middle","background-color":"rgba(255, 255, 255, 0)","border-radius":"0px"},"font_animation":{"text-animation":"tada","text-animation-mode":"letters"}}',
            'message_text' => '{"font_style":{"font-family":"\"Roboto Condensed\"","font-size":"25px","color":"#FFFFFF","font-weight":"normal","font-style":"normal","text-decoration":"none","text-transform":"none","text-shadow":"1px","text-shadow_color":"rgb(0, 0, 0)","letter-spacing":"0px","word-spacing":"0px","text-align":"center","vertical-align":"middle","background-color":"rgba(0, 0, 0, 0)","border-radius":"0px"},"font_animation":{"text-animation":"none","text-animation-mode":"letters"}}',
            'message_background' => 'rgba(0, 0, 0, 0)'
        ];
    }

    /** @test */
    public function unauthorized_user_can_not_see_a_alert_page()
    {
        $this->get("/dashboard/alert-widget")->assertStatus(302);
    }

    /** @test */
    public function unauthorized_user_can_not_create_a_alert_variations()
    {
        $response = $this->json('post', "/dashboard/alert-variations/store", $this->getVariationData());
        $response->assertStatus(401);
    }

    /** @test */
    public function unauthorized_user_can_not_change_a_alert_variations()
    {
        $this->withoutExceptionHandling();

        $this
            ->actingAs($this->streamer)
            ->json('post', "/dashboard/alert-variations/store", $this->getVariationData());

        $response = $this->json('post', "/dashboard/alert-variations/edit/{$this->streamer->variations()->latest()->first()->id}", $this->getVariationData());
        $response->assertStatus(401);
    }

    /** @test */
    public function unauthorized_user_can_not_remove_a_alert_variations()
    {
        $response = $this->actingAs($this->streamer)->json('post', "/dashboard/alert-variations/remove/{$this->streamer->variations()->latest()->first()->id}");
        $response->assertStatus(401);
    }


    /** @test */
    public function user_can_see_a_alert_page()
    {
        $this->actingAs($this->streamer)
            ->get("/dashboard/alert-widget")
            ->assertStatus(200);
    }

    /** @test */
    public function user_can_create_a_alert_variations()
    {
        $response = $this
            ->actingAs($this->streamer)
            ->json('post', "/dashboard/alert-variations/store", $this->getVariationData());
        $response->assertStatus(200);
        $response->assertJson([
            'errors' => null,
            'status' => 'success',
            'text' => 'Изменения были успешно сохранены!'
        ]);
        $this->assertEquals(2, Variation::count());
    }

    /** @test */
    public function user_can_change_a_alert_variations()
    {
        // create and change variation
        $this
            ->actingAs($this->streamer)
            ->json( 'post', "/dashboard/alert-variations/store", $this->getVariationData() );
        $response = $this
            ->actingAs($this->streamer)
            ->json('post', "/dashboard/alert-variations/edit/{$this->streamer->variations()->latest()->first()->id}", $this->getVariationData());
        $response->assertStatus(200);
        $response->assertJson([
            'errors' => null,
            'status' => 'success',
            'text' => 'Изменения были успешно сохранены!'
        ]);
    }

    /** @test */
    public function user_can_change_default_a_alert_variations()
    {
        $response = $this
            ->actingAs($this->streamer)
            ->json('post', "/dashboard/alert-variations/edit/{$this->streamer->variations()->latest()->first()->id}", $this->getVariationData());
        $response->assertStatus(200);
        $response->assertJson([
            'errors' => null,
            'status' => 'success',
            'text' => 'Изменения были успешно сохранены!'
        ]);
    }

    /** @test */
    public function user_can_not_change_another_a_alert_variations()
    {
        // create user and variation
        $another = User::factory()->create([
            'name' => 'Another_User'
        ]);
        $this
            ->actingAs($another)
            ->json('post', "/dashboard/alert-variations/store", $this->getVariationData());

        $response = $this
            ->actingAs($this->streamer)
            ->json('post', "/dashboard/alert-variations/edit/{$another->variations()->latest()->first()->id}", $this->getVariationData());
        $response->assertStatus(403);
        $response->assertJson([
            'errors' => null,
            'status' => 'error',
            'text' => 'Недостаточно прав для изменения этой вариации!'
        ]);
    }

    /** @test */
    public function user_can_remove_a_alert_variations()
    {
        // create variation before remove this
        $this
            ->actingAs($this->streamer)
            ->json('post', "/dashboard/alert-variations/store", $this->getVariationData());

        $response = $this
            ->actingAs($this->streamer)
            ->json('post', "/dashboard/alert-variations/remove/{$this->streamer->variations()->latest()->first()->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'text' => 'Вариация была удалена!'
        ]);
    }

    /** @test */
    public function user_can_not_remove_default_a_alert_variations()
    {
        $response = $this
            ->actingAs($this->streamer)
            ->json('post', "/dashboard/alert-variations/remove/{$this->streamer->variations()->default()->id}");
        $response->assertStatus(403);
        $response->assertJson([
            'status' => 'error',
            'text' => 'Недостаточно прав для удаления этой вариации!'
        ]);
    }

    /** @test */
    public function user_can_not_remove_another_a_alert_variations()
    {
        $streamer_two = User::factory()->create([
            'name' => 'Another User'
        ]);
        $this
            ->actingAs($streamer_two)
            ->json('post', "/dashboard/alert-variations/store", $this->getVariationData());

        $response = $this
            ->actingAs($this->streamer)
            ->json('post', "/dashboard/alert-variations/remove/{$streamer_two->variations()->latest()->first()->id}");
        $response->assertStatus(403);
        $response->assertJson([
            'status' => 'error',
            'text' => 'Недостаточно прав для удаления этой вариации!'
        ]);
    }
}
