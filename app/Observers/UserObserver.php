<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $variation = [
            'is_default' => true,
            'is_active' => true,
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

        $user->variations()->create($variation);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
