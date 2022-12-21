<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::select('id', 'name', 'email', 'phone', 'created_at')->with(['jobs'])->get();
        
        if($users){
            return response()->json([
                'success' => true,
                'message' => "Data user berhasil diterima!",
                'users'    => $users,  
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data user tidak ditemukan!'
        ], 409);
    }

    public function show($id){
        $user = User::select('id', 'name', 'email', 'phone', 'created_at')->with(['jobs'])->where('id', $id)->first();

        if($user){
            return response()->json([
                'success' => true,
                'message' => "Data user berhasil diterima!",
                'user'    => $user,  
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data user tidak ditemukan!'
        ], 409);
    }

    public function save(Request $request){
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone' => 'required|numeric'
        ];

        if($request->id){
            $rules['email'] = 'required|email';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            if (!$request->id) {
                $user = new User;
            } else {
                $user = User::find($request->id);
                if (!$user)
                    return response()->json([
                        'success' => false,
                        'message' => "ID User tidak ditemukan!",
                    ], 409);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->phone = $request->phone;
            $user->save();

            if (!$request->id){
                return response()->json([
                    'success' => true,
                    'message' => "User berhasil ditambahkan!",
                    'user'    => $user,
                ], 201);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => "User berhasil diupdate!",
                    'user'    => $user,  
                ], 201);
            }
            
        } catch (\Exception $e) {
            if (!$request->id){
                return response()->json([
                    'success' => false,
                    'message' => "User gagal ditambahkan!",
                    'error' => $e->getMessage()
                ], 409);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "User gagal diupdate!",
                    'error' => $e->getMessage()
                ], 409);
            }
        }
    }

    public function delete(Request $request)
    {
        try {
            $user = User::find($request->id);
            if (!$user)
                return response()->json([
                    'success' => false,
                    'message' => "ID User tidak ditemukan!",
                ], 409);

            $user->delete();
            return response()->json([
                'success' => true,
                'message' => "User berhasil dihapus!",
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "User gagal dihapus!",
                'error' => $e->getMessage()
            ], 409);
        }
    }
}
