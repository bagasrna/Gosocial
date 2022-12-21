<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    public function optimize(){
        Artisan::call('optimize');
        return response()
                ->json('Success Call Artisan Optimize');
    }

    public function fresh(){
        Artisan::call('migrate:fresh');
        return response()
                ->json('Success Call Artisan Migrate Fresh');
    }
}
