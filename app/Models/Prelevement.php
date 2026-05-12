<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prelevement extends Model
{
    protected $fillable = ['id', 'date', 'laboratoire_id', 'triathlon_id', 'triathlete_id', 'etat'];
    
    // On désactive l'auto-incrément si l'ID est un string (ex: P23456 comme sur la maquette)
    public $incrementing = false;
    protected $keyType = 'string';

    public function laboratoire() {
        return $this->belongsTo(Laboratoire::class);
    }

    public function produits() {
        // Relation avec la table Taux (pivot)
        return $this->belongsToMany(ProduitDopant::class, 'taux')
                    ->withPivot('mesure');
    }
}
