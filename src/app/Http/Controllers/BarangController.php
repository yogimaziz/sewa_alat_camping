<?php

namespace App\Http\Controllers;

use App\Models\barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{

	public function store(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $input = $request->all();
            $validationRules = [
                'nama_barang'   => 'required|min:5|unique:barang',
                'stock'         => 'required|in:ada,tidak_ada',
                'harga_sewa'    => 'required|numeric',
                'image'         => 'required',
            ];
            $validator = \Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $barang = new Barang;
            $barang->nama_barang    = $request->input('nama_barang');
            $barang->stock   = $request->input('stock');
            $barang->harga_sewa  = $request->input('harga_sewa');
            
            if ($request->hasFile('image')) {
                $imageName = str_replace(" ","_", $request->input('nama_barang'));
                $request->file('image')->move(storage_path('uploads/images'), $imageName);
            }

            $barang->image           = $imageName;
            $barang->save();
            return response()->json($barang, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $barang = Barang::OrderBy("id", "DESC")->paginate(5)->toArray();
            $response = [
                "total_count" => $barang["total"], 
                "limit"       => $barang["per_page"],
                "pagination"  => [
                    "next_page"    => $barang["next_page_url"], 
                    "current_page" => $barang["current_page"],
                ],
                "data" => $barang["data"],
            ];
            return response()->json($response, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    
    public function show(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $barang = Barang::find($id);
            if (!$barang) {
                abort(404);
            }
            return response()->json($barang, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function update(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
           
                $input = $request->all();
                $barang = Barang::find($id);
                if (!$barang) {
                    abort(404);
                }
                $barang->fill($input);
                $barang->save();
                return response()->json($barang, 200);
            
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $barang = Barang::find($id);
            if (!$barang) {
                abort(404);
            }
            $barang->delete();
            $message = ['message' => 'Deleted successfully', 'barang_id' => $id];
            return response()->json($message, 200);
        }else{
            return response('Not acceptable', 406);
        }
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
