<?php

namespace App\Http\Controllers;

use App\Models\Sewa;
use Illuminate\Http\Request;

class SewaController extends Controller
{
    public function store(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $input = $request->all();
            $validationRules = [
                'user_id'       => 'required|exists:users,id',
                'penyewa_id'     => 'required|exists:penyewa,id',
                'barang_id'     => 'required|exists:barang,id',
                'tanggal_sewa'       => 'required|min:10',
                'tanggal_pengembalian'     => 'required|min:10',
            ];
            $validator = \Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $sewa = Sewa::create($input);
            return response()->json($sewa, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $sewa = Sewa::select('id','user_id','barang_id','penyewa_id','tanggal_sewa','tanggal_pengembalian')
            ->with(['user' => function ($query){
                $query->select('id', 'name');
            }])
            ->with(['penyewa' => function ($query){
                $query->select('id','nama_penyewa', 'alamat_penyewa', 'no_telp');
            }])
            ->with(['barang' => function ($query){
                $query->select('id','nama_barang', 'harga_sewa', 'stock');
            }])
            ->with(['pembayaran' => function ($query){
                $query->select('id','sewa_id', 'total', 'bayar','status');
            }])
            ->OrderBy("id", "DESC")->paginate(5)->toArray();
            $response = [
                "total_count" => $sewa["total"], 
                "limit"       => $sewa["per_page"],
                "pagination"  => [
                    "next_page"    => $sewa["next_page_url"], 
                    "current_page" => $sewa["current_page"],
                ],
                "data" => $sewa["data"],
            ];
            return response()->json($response, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function show(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json') {
            $sewa = Sewa::find($id);
            if (!$sewa) {
                abort(404);
            }
            return response()->json($sewa, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function update(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
                $input = $request->all();
                $sewa = Sewa::find($id);
                if (!$sewa) {
                    abort(404);
                }
                $sewa->fill($input);
                $sewa->save();
                return response()->json($sewa, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $sewa = Sewa::find($id);
            if (!$sewa) {
                abort(404);
            }
            $sewa->delete();
            $message = ['message' => 'Deleted successfully', 'sewa_id' => $id];
            return response()->json($message, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

}
