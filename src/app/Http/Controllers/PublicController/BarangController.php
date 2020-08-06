<?php

namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class barangController extends Controller
{

    public function index()
    {
        $barang = Barang::OrderBy("id", "DESC")->paginate(10)->toArray();

        $response = [
			"total_count" => $barang["total"],
			"limit" => $barang["per_page"],
			"pagination" => [
				"next_page" => $barang["next_page_url"],
				"current_page" => $barang["current_page"]
			],
			"data" => $barang["data"],
		];

        return response()->json($response, 200);
    }

    public function show($id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
			abort(404);
		}
        return response()->json($barang, 200);
    }
    public function image($imageName)
    {
        $imagePath = storage_path('uploads/images/'.$imageName);
        if (file_exists($imagePath)) {
            $file = file_get_contents($imagePath);
            return response($file, 200)->header('Content-Type', 'image/jpeg');
        }
        return response()->json([
            "message" => "Image not found"
        ],401);
    }

}
