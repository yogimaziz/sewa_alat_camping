<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{

    public function store(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            if (Gate::denies('only-admin')) {
                return response()->json([
                    'success' => false,
                    'status'  => 403,
                    'message' => 'You are unauthorized',
                ], 403);
            }
            $input = $request->all();
            $validationRules = [
                'name'      => 'required|min:5',
                'email'     => 'required|email|unique:users',
                'password'  => 'required|min:5|confirmed',
                'role'      => 'required|in:admin,petugas'
            ];
            $validator = \Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $user = new User;
            $user->name  = $request->input('name');
            $user->email = $request->input('email');
            $planPassword    = $request->input('password');
            $user->password = app('hash')->make($planPassword);
            $user->save();
            return response()->json($user, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }
 
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            if (Gate::denies('only-admin')) {
                return response()->json([
                    'success' => false,
                    'status'  => 403,
                    'message' => 'You are unauthorized',
                ], 403);
            }
            $user = User::OrderBy("id", "DESC")->paginate(5)->toArray();
            $response = [
                "total_count" => $user["total"], 
                "limit"       => $user["per_page"],
                "pagination"  => [
                    "next_page"    => $user["next_page_url"], 
                    "current_page" => $user["current_page"],
                ],
                "data" => $user["data"],
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
            if (Gate::denies('only-admin')) {
                return response()->json([
                    'success' => false,
                    'status'  => 403,
                    'message' => 'You are unauthorized',
                ], 403);
            }
            $user = User::find($id);
            if (!$user) {
                abort(404);
            }
            return response()->json($user, 200);
        }else{
            return response('Not acceptable', 406);   
        }
    }   

    public function update(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            if (Gate::denies('only-admin')) {
                return response()->json([
                    'success' => false,
                    'status'  => 403,
                    'message' => 'You are unauthorized',
                ], 403);
            }
            $input = $request->all();       
            $user = User::find($id);
            if (!$user) {
                abort(404);
            }
            $user->fill($input);
            $user->save();
            return response()->json($user, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            if (Gate::denies('only-admin')) {
                return response()->json([
                    'success' => false,
                    'status'  => 403,
                    'message' => 'You are unauthorized',
                ], 403);
            }
            $user = User::find($id);
            if (!$user) {
                abort(404);
            }
            $user->delete();
            $message = ['message' => 'Deleted successfully', 'user_id' => $id];
            return response()->json($message, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

}

