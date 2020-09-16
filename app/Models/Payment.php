<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPayment
 */
class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    const NEW = 'new';
    const PAID = 'paid';
    const CANCELLED = 'cancelled';

    public function streamer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
