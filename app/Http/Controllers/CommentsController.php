<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

class CommentsController extends Controller {
    
    /**
     * Get user comments
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        try {
            $user = $request->user();
            $comments = $user->comments;
            return response()->json($comments, 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ], 400);
        }
    }

    /**
     * Create new comment
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function store(Request $request) {
        try {
            $this->validate($request, [
                'comment' => 'required|min:8',
                'post_id' => 'required|numeric'
            ]);
            $data = $request->all();
            $data['status'] = 1;
            $user = $request->user();
            $post = $user->posts()->find($data['post_id']);
            if($post){
                $comment = $user->comments()->create($data);
                return response()->json($comment, 201);
            }
            return response()->json([
                'error' => 'Post not found!'
            ], 200);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Get single comment
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function show(Request $request, $id) {
        try {
            $user = $request->user();
            $comment = $user->comments()->find($id);
            if($comment){
                return response()->json($comment, 200);
            }
            return response()->json([
                'error' => 'Comment not found!'
            ], 200);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Update comment
     *
     * @param Request $request
     * @param int $post_id
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function update(Request $request, $post_id, $id) {
        try {
            $user = $request->user();
            $comment = $user->comments()->where('post_id', $post_id)->find($id);
            if($comment){
                $data = $request->all();
                $comment->fill($data);
                $comment->save();
                return response()->json($comment, 200);
            }
            return response()->json([
                'error' => 'Not Found'
            ], 200);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Delete comment
     *
     * @param Request $request
     * @param int $post_id
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function destoy(Request $request, $post_id, $id) {
        try {
            $user = $request->user();
            $comment = $user->comments()->where('post_id', $post_id)->find($id);
            if($comment){
                $comment->delete();
                return response()->json([
                    'message' => 'Comment deleted successfully.'
                ], 200);
            }
            return response()->json(['error' => 'Something went wrong!'], 400);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
