<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitDopant extends Model
{
    protected $table = 'ProduitDopant';
    protected $primaryKey = 'codeProduit';
    public $timestamps = false;
    protected $fillable = ['codeProduit', 'libelleProduit', 'tauxMaxi'];
}
