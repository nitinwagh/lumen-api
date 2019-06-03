<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

class PostsController extends Controller {
    
    /**
     * Get all comments
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        try {
            
            $user = $request->user();
            $posts = $user->posts()->where('status', 1)->get();
            return response()->json($posts, 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ], 400);
        }
    }

    /**
     * Add new post
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function store(Request $request){
        try {
            $this->validate($request, [
                'title' => 'required|min:3',
                'description' => 'required|min:8'
            ]);
            $data = $request->all();
            $data['status'] = 1;
            $user = $request->user();
            $post = $user->posts()->create($data);
            return response()->json($post, 201);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Get single post
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function show(Request $request, $id) {
        try {
            $user = $request->user();
            $post = $user->posts()->find($id);
            if($post){
                return response()->json($post, 200);
            }
            return response()->json(['error' => 'Something went wrong!'], 400);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Update post
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function update(Request $request, $id) {
        try {
            $this->validate($request, [
                'title' => 'required|min:3',
                'description' => 'required|min:8'
            ]);
            $data = $request->all();
            $user = $request->user();
            $post = $user->posts()->find($id);
            if($post){
                $post->fill($data);
                $post->save();
                return response()->json($post, 200);
            }
            return response()->json([
                'error' => 'Not Found'
            ], 200);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Delete post
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function destoy(Request $request, $id) {
        try {
            $user = $request->user();
            $post = $user->posts()->find($id);
            if($post){
                $post->delete();
                return response()->json(['message' => 'Post deleted successfully.'], 200);
            }
            return response()->json(['error' => 'Something went wrong!'], 400);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
