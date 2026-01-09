<?php 

namespace App\Http\Controllers;

use App\Services\HomepageService;

class HomepageController extends Controller
{
    public function index(HomepageService $service)
    {
        return view('welcome', [
            'ekstras' => $service->getEkstra()
        ]);
    }
}
