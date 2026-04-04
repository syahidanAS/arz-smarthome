<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CctvController extends Controller
{
    private function getLatestVideo()
    {
        $base = config('cctv.storage_path');

        $folders = array_filter(glob($base . '/*'), 'is_dir');
        rsort($folders);

        $latestFolder = $folders[0] ?? null;

        if (!$latestFolder) return null;

        $files = glob($latestFolder . '/*.mp4');
        rsort($files);

        return $files[1] ?? null;
    }

    public function stream()
    {
        $file = $this->getLatestVideo();

        if (!$file) {
            abort(404, 'Video tidak ditemukan');
        }

        return response()->file($file);
    }

    public function list()
    {
       $base = config('cctv.storage_path');

        $folders = array_filter(glob($base . '/*'), 'is_dir');
        rsort($folders);

        $latestFolder = $folders[0] ?? null;

        if (!$latestFolder) return response()->json([]);

        $files = glob($latestFolder . '/*.mp4');
        sort($files);

        return response()->json(array_values($files));
    }

    public function index()
    {
        return view('cctv');
    }
}
