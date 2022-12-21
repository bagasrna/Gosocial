<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Job;
use App\Models\User;

class JobController extends Controller
{
    public function index(){
        $jobs = Job::select('id', 'user_id', 'name', 'description', 'start_date', 'created_at')->with(['user'])->get();
        
        if($jobs){
            return response()->json([
                'success' => true,
                'message' => "Data job berhasil diterima!",
                'jobs'    => $jobs,  
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data job tidak ditemukan!'
        ], 409);
    }

    public function show($id){
        $job = Job::select('id', 'user_id', 'name', 'description', 'start_date', 'created_at')->where('id', $id)->with(['user'])->first();

        if($job){
            return response()->json([
                'success' => true,
                'message' => "Data job berhasil diterima!",
                'job'    => $job,  
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data job tidak ditemukan!'
        ], 409);
    }

    public function save(Request $request){
        $rules = [
            'user_id' => 'required|numeric',
            'name' => 'required|max:255',
            'description' => 'required',
            'start_date' => 'required|date',
        ];

        $user = User::find($request->user_id);
        if(!$user)
            return response()->json([
                'success' => false,
                'message' => "ID user tidak ditemukan!",
            ], 409);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            if (!$request->id) {
                $job = new Job;
            } else {
                $job = job::find($request->id);
                if (!$job)
                    return response()->json([
                        'success' => false,
                        'message' => "ID job tidak ditemukan!",
                    ], 409);
            }

            $job->user_id = $request->user_id;
            $job->name = $request->name;
            $job->description = $request->description;
            $job->start_date = $request->start_date;
            $job->save();

            if (!$request->id){
                return response()->json([
                    'success' => true,
                    'message' => "Job berhasil ditambahkan!",
                    'job'    => $job,
                ], 201);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => "Job berhasil diupdate!",
                    'job'    => $job,  
                ], 201);
            }
            
        } catch (\Exception $e) {
            if (!$request->id){
                return response()->json([
                    'success' => false,
                    'message' => "Job gagal ditambahkan!",
                    'error' => $e->getMessage()
                ], 409);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Job gagal diupdate!",
                    'error' => $e->getMessage()
                ], 409);
            }
        }
    }

    public function delete(Request $request)
    {
        try {
            $job = job::find($request->id);
            if (!$job)
                return response()->json([
                    'success' => false,
                    'message' => "ID job tidak ditemukan!",
                ], 409);

            $job->delete();
            return response()->json([
                'success' => true,
                'message' => "Job berhasil dihapus!",
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Job gagal dihapus!",
                'error' => $e->getMessage()
            ], 409);
        }
    }
}
