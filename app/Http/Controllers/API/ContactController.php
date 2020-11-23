<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Contact::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => "email|required",
            'telephone' => 'required|phone:CD,mobile',
            'societe' => 'required',
        ]);

        return Contact::create([
           'nom' => $request['nom'],
           'postnom' =>  $request['postnom'],
           'prenom' => $request['prenom'],
           'email' => $request['email'],
           'telephone' => $request['telephone'],
           'societe' => $request['societe'],
           'birth' => $request['birth']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'email|required',
            'telephone' => 'required|phone:CD,mobile',
            'societe' => 'required',
        ]);

        $contact = Contact::findOrFail($id);

        $contact->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json([
         'message' => 'Contact supprimer avec succès'
        ]);
    }
}
