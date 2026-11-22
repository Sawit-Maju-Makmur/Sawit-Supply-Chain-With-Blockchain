<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandCertificate extends Model
{
      protected $fillable = ['farmer_id', 'document_name', 'file_path', 'certification_type'];
}
