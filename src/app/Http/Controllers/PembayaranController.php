<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{

    public function store(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $input = $request->all();
            $validationRules = [
                'sewa_id'      => 'required|exists:sewa,id',
                'total'  => 'required|numeric',
                'bayar' => 'required|numeric',
                'status'       => 'required|in:lunas,belum_lunas',
            ];
            $validator = \Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $pembayaran = Pembayaran::create($input);
            return response()->json($pembayaran, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $pembayaran = Pembayaran::OrderBy("id", "DESC")->paginate(5)->toArray();
            $response = [
                "total_count" => $pembayaran["total"], 
                "limit"       => $pembayaran["per_page"],
                "pagination"  => [
                    "next_page"    => $pembayaran["next_page_url"], 
                    "current_page" => $pembayaran["current_page"],
                ],
                "data" => $pembayaran["data"],
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
            $pembayaran = Pembayaran::find($id);
            if (!$pembayaran) {
                abort(404);
            }
            return response()->json($pembayaran, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function update(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
                $input = $request->all();
                $validationRules = [
                    'sewa_id'      => 'required|exists:sewa,id',
                    'total'  => 'required|numeric',
                    'bayar' => 'required|numeric',
                    'status'       => 'required|in:lunas,belum_lunas',
                ];
                $validator = \Validator::make($input, $validationRules);
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }
                $pembayaran = Pembayaran::find($id);
                if (!$pembayaran) {
                    abort(404);
                }
                $pembayaran->fill($input);
                $pembayaran->save();
                return response()->json($pembayaran, 200);
            
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $pembayaran = Pembayaran::find($id);
            if (!$pembayaran) {
                abort(404);
            }
            $pembayaran->delete();
            $message = ['message' => 'Deleted successfully', 'pembayaran_id' => $id];
            return response()->json($message, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

}
