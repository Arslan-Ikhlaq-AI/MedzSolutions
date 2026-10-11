<?php

namespace App\Models;

use Database\Factories\AffiliateApplicationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'phone', 'partner_type', 'company', 'website', 'audience', 'message'])]
class AffiliateApplication extends Model
{
    /** @use HasFactory<AffiliateApplicationFactory> */
    use HasFactory;
}
