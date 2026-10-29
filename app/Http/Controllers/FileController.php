<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
  public function show(Request $request){
    $id = $request->input('id');
    $file = File::find($id);
    return $this->OpenFile($file);
  }
}