<?php
namespace App\Services;

use App\Models\Ekstrakulikuler;

class HomepageService{

    public function getEkstra(){
        return Ekstrakulikuler::with([
            'pembina',
            'schedules.roomEkstra',
            
        ])->get();
    }
}