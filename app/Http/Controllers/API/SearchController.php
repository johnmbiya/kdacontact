<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;

use App\Models\Contact;

class SearchController extends Controller
{
    public static function search(Request $request) {
        $searchResults = (new Search())
            ->registerModel(Contact::class, 'nom', 'postnom', 'prenom', 'email', 'societe')
            ->search($request['q']);

        $contacts = [];

        foreach ($searchResults as $value) {
            $item = [
                'nom' => $value->searchable->nom,
                'postnom' => $value->searchable->postnom,
                'prenom' => $value->searchable->prenom,
                'email' => $value->searchable->email,
                'telephone' => $value->searchable->telephone,
                'societe' => $value->searchable->societe,
                'birth' => $value->searchable->birth,
            ];
            array_push($contacts, $item);
        }
        return $contacts;
    }
}
