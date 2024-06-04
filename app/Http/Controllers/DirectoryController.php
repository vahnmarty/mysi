<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamilyDirectory;

class DirectoryController extends Controller
{
    public function index()
    {
        $directory = FamilyDirectory::get();
        
        return view('pages.directory.index', compact('directory'));
    }
}
