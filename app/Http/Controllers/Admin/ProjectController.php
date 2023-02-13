<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Technology;
use App\Models\Type;
use Hamcrest\Description;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();

        $types = Type::all();

        $technologies = Technology::all();

        return view("admin.projects.index", compact( "types", "projects", "technologies"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $types = Type::all();

        $technologies = Technology::all();

        return view("admin.projects.create", compact("types", "technologies") );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([

            "name" => "required|min:3|max:20",
            "type_id"=>"nullable|string",
            "description" => "required|string",
            "cover_img" => "file",
            "github_link" => "string",


            "technologies" => "nullable|array|exists:technologies,id"
            
        ]);
        

        if(key_exists("cover_img", $data)) {
            //con storage stiamo dicendo salva con il metodo put dentro la cartella projets
            $path = Storage::put("projects", $data["cover_img"]);
        }

        $types = Type::all();
        
        $project = Project::create([
            ...$data,
        //a bd vado a salvare solamente il percorso 
            "cover_img" => $path ?? '',
        // recuperiamo l'id dagli user cioé user_id é uguale all'utente loggato
            "user_id" => Auth::id()
        ]);

        // Controlla che nei dati che il server sta ricevendo, ci sia un valore per la chiave "tags".
        if ($request->has("technologies")) {
            // if (key_exists("tags", $data)) {
            $project->technologies()->attach($data["technologies"]);
        }

        

        return redirect()->route("admin.projects.show",  compact("project", "types"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);





        return view("admin.projects.show", compact("project"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        


        $types = Type::all();

        $technologies = Technology::all();

        return view("admin.projects.edit", compact("project", "types", "technologies"));
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
        $project = Project::findOrFail($id);

        $data = $request->validate([

            "name" => "required|min:3|max:20",
            "type_id"=>"nullable|string",
            "description" => "required|string",
            "cover_img" => "file",
            "github_link" => "string",

            // exists controlla sulla tabella categories,
            // che nella colonna id ci sia qualcuno con l'id ricevuto
            //  tramite il valore di category_id
            // "category_id" => "nullable|exists:categories,id"
            // "category_id" => "nullable|exists:categories,id",
            "technologies" => "nullable|array|exists:technologies,id"
            
        ]);

        if(key_exists("cover_img", $data)) {
            //con storage stiamo dicendo salva con il metodo put dentro la cartella projets
            $path = Storage::put("projects", $data["cover_img"]);


            Storage::delete($project->cover_img);
        }

        $project->update([
            ...$data,
            // Se $path ha un valore, significa che abbiamo caricato un nuovo file.
            // Altrimenti, usiamo il percorso vecchio tramite $post->cover_img
            "cover_img" => $path ?? $project->cover_img
        ]);
        
        // la funzione sync si arrangia a capire quali sono i tag da aggiungere,
        // quali da togliere e quali da lasciare invariati
        $project->technologies()->sync($data["technologies"]);

        return redirect()->route("admin.projects.show", compact("id","project"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        if ($project->cover_img) {
            Storage::delete($project->cover_img);
        }

        // se il post ha qualche relazione con i tag, PRIMA devo annullare tutte le relazioni
        // e solo DOPO potrò cancellare il post.
        $project->technologies()->detach();
        
        $project->delete();

        return redirect()->route("admin.projects.index");
    }
}