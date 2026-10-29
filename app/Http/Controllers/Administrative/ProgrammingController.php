<?php

namespace App\Http\Controllers\Administrative;

use App\Models\Programming;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProgrammingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('programming.index');
    }
    
    public function automation()
    {
        $tree = base64_encode(
                json_encode([
                    //id sede (bodega nacional)
                    1=>[
                        "id" => 1,
                        "parent" => 0,
                        "name" => "Bodega Nacional",
                        "allow_sub" => true,
                        "category" => "",
                        //subnivel sedes
                        "categories" => [
                            "id" => 2,
                            "parent" => 1,
                            "name" => "Bucaramanga",
                            "allow_sub" => true,
                            "category" => "",
                            "type" => "office",
                            //subnivel bodega
                            "categories" => [
                                "id" => 1,
                                "parent" => 2,
                                "name" => "Bodega Frugal",
                                "allow_sub" => true,
                                "category" => "",
                                "type" => "warehouse",
                                //subnivel téncicos
                                "categories" => [
                                    "id" => 1,
                                    "parent" => 1,
                                    "name" => "Alma Marcela Rita De Mosion",
                                    "allow_sub" => false,
                                    "category" => "",
                                    "type" => "technical"
                                ]

                            ]
                        ]

                    ]        
        ]));    
                
        return view('programming.automation', compact('tree'));
    }
    
    public function save(){
        sleep(5);
        return [];
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Programming  $programming
     * @return \Illuminate\Http\Response
     */
    public function show(Programming $programming)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Programming  $programming
     * @return \Illuminate\Http\Response
     */
    public function edit(Programming $programming)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Programming  $programming
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Programming $programming)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Programming  $programming
     * @return \Illuminate\Http\Response
     */
    public function destroy(Programming $programming)
    {
        //
    }
}
