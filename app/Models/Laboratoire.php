<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratoire extends Model
{
    protected $table = 'Laboratoire';
    protected $primaryKey = 'idLabo';
    public $timestamps = false;
    protected $fillable = ['idLabo', 'nomlabo', 'adresseRue', 'adresseCP', 'adresseVille'];
}
