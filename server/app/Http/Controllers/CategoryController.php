<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\Category\CategoryRepository;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    // 🔹 GET ALL CATEGORIES
    public function get()
    {
        try {
            return $this->categoryRepository->getCategories();
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
            ], 500);
        }
    }

    // 🔹 ADD CATEGORY
    public function add(Request $request)
    {
        try {
            return $this->categoryRepository->addCategory($request->all());
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['message' => __('error')], 500);
        }
    }

    // 🔹 UPDATE CATEGORY
    public function update(Request $request)
    {
        try {
            return $this->categoryRepository->updateCategory($request->all());
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['message' => __('error')], 500);
        }
    }

    // 🔹 DELETE CATEGORY (Soft delete)
    public function delete(Request $request)
    {
        try {
            return $this->categoryRepository->deleteCategory($request->all());
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['message' => __('error')], 500);
        }
    }
}