<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use Illuminate\Http\Request;

class PenyewaController extends Controller
{
    public function store(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $input = $request->all();
            $validationRules = [
                'nama_penyewa'   => 'required|min:5',
                'alamat_penyewa'        => 'required|min:5',
                'no_telp'       => 'required|numeric',
            ];
            $validator = \Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $penyewa = Penyewa::create($input);
            return response()->json($penyewa, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $penyewa = Penyewa::OrderBy("id", "DESC")->paginate(5)->toArray();
            $response = [
                "total_count" => $penyewa["total"], 
                "limit"       => $penyewa["per_page"],
                "pagination"  => [
                    "next_page"    => $penyewa["next_page_url"], 
                    "current_page" => $penyewa["current_page"],
                ],
                "data" => $penyewa["data"],
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
            $penyewa = Penyewa::find($id);
            if (!$penyewa) {
                abort(404);
            }
            return response()->json($penyewa, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function update(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
                $input = $request->all();
                $penyewa = Penyewa::find($id);
                if (!$penyewa) {
                    abort(404);
                }
                $penyewa->fill($input);
                $penyewa->save();
                return response()->json($penyewa, 200);
            }
        else{
            return response('Not acceptable', 406);
        }
    }

    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $penyewa = Penyewa::find($id);
            if (!$penyewa) {
                abort(404);
            }
            $penyewa->delete();
            $message = ['message' => 'Deleted successfully', 'penyewa_id' => $id];
            return response()->json($message, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

}
