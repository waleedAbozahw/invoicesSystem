<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoices extends Model
{
    use SoftDeletes;

   protected $guarded = [];

   protected $dates = ['deleted_at'];

   public function section(){
     return $this->belongsTo(Sections::class);
   }
   
}
