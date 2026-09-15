<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryRepository
{
    // 🔹 GET ALL
    public function getCategories()
    {
        $user = Auth::user();

        $categories = Category::where('societe_id', $user->societe_id)->get();

        $result = $categories->map(function ($c) {
            return [
                'id' => encrypt($c->id),
                'name' => $c->name,
            ];
        });

        return response()->json([
            'categories' => $result
        ]);
    }

    // 🔹 ADD
    public function addCategory($data)
    {
        $validator = \Validator::make($data, [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        $category = new Category();
        $category->name = $data['name'];
        $category->societe_id = $user->societe_id;
        $category->save();

        return response()->json([
            'message' => __('added')
        ], 200);
    }

    // 🔹 DELETE
    public function deleteCategory($data)
    {
        if (!isset($data['id'])) {
            return response()->json([
                'message' => __("Category id is required")
            ], 400);
        }

        try {
            $categoryId = decrypt($data['id']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __("Invalid category id")
            ], 400);
        }

        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json([
                'message' => __("Category not found")
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message' => __('deleted')
        ]);
    }

    // 🔹 UPDATE
    public function updateCategory($data)
    {
        if (!isset($data['id'])) {
            return response()->json([
                'message' => __("Category id is required")
            ], 400);
        }

        try {
            $categoryId = decrypt($data['id']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __("Invalid category id")
            ], 400);
        }

        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json([
                'message' => __("Category not found")
            ], 404);
        }

        if (isset($data['name'])) {
            $category->name = $data['name'];
        }

        $category->save();

        return response()->json([
            'message' => __('updated')
        ]);
    }
}