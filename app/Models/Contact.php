<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Contact extends Model implements Searchable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'postnom',
        'prenom',
        'email',
        'telephone',
        'societe',
        'birth'
    ];

    public function getSearchResult(): SearchResult
     {
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->nom );
     }
}
