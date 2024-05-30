<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiDirectoryView extends Model
{
    use HasFactory;

    protected $guarded =[];

    protected $table = 'si_directory';

    protected $primaryKey = 'account_id';
}
