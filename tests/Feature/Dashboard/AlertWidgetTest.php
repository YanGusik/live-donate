<?php


namespace Tests\Feature\Dashboard;

use App\Models\User;
use App\Models\Group;
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

    /** @test */
    public function another_can_not_see_a_alert_page()
    {
        $this->get("/dashboard/alert-widget")
            ->assertStatus(403);
    }

    /** @test */
    public function streamer_can_see_a_alert_page()
    {
        $this->actingAs($this->streamer)
            ->get("/dashboard/alert-widget")
            ->assertStatus(200)
            ->assertInertia('Alert');
    }

    public function another_can_not_create_a_alert_variations()
    {
        $response = $this->json('post', "/alert-variations/save", [
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
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_create_a_alert_variations()
    {
        $response = $this->actingAs($this->streamer)->json('post', "/alert-variations/save", [
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
        ]);
        $response->assertStatus(200);
        $this->assertEquals(2, Variation::count());
    }

    /** @test */
    public function user_can_change_default_a_alert_variations()
    {
        $response = $this->actingAs($this->streamer)->json('post', "/alert-variations/save", [
            'entity_id' => $this->streamer->getGroup(1)->getDefault->id,
            'is_default' => 1,
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
        ]);
        $response->assertStatus(200);
        $this->assertEquals(1, Variation::count());
    }

    /** @test */
    public function user_can_not_change_another_default_a_alert_variations()
    {
        $streemer_two = User::factory()->create([
            'name' => 'Another User'
        ]);

        $response = $this->actingAs($this->streamer)->json('post', "/alert-variations/save", [
            'entity_id' => $streemer_two->getGroup(1)->getDefault->id,
            'is_default' => 1,
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
        ]);
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => null,
            'status' => 'error',
            'text' => 'Нельзя изменять вариацию у других пользователей'
//            'text' => 'You cannot change the variation for other users'
        ]);
    }


}
