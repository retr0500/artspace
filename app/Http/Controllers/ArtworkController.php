<?php
   namespace App\Http\Controllers;

   use App\Models\Artwork;
   use Illuminate\Http\Request;

   class ArtworkController extends Controller
   {
       // Ver el listado de obras
       public function index() {
           $obras = Artwork::all();
           return view('obras.index', compact('obras'));
       }

       // Guardar una obra nueva
       public function store(Request $request) {
           $obra = new Artwork();
           $obra->titulo = $request->titulo;
           $obra->descripcion = $request->descripcion;
           $obra->precio = $request->precio;
           $obra->save();

           return redirect('/'); // Vuelve a la pagina principal
       }
   }