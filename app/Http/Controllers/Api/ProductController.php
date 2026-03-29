<?php

namespace App\Http\Controllers\Api;

use App\Enums\ProductCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Get paginated list of active products with filtering.
     *
     * @param ProductIndexRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     *
     * @queryParam category string Filter by category (outdoor, indoor, transparent, posters)
     * @queryParam pixel_pitch_min numeric Minimum pixel pitch
     * @queryParam pixel_pitch_max numeric Maximum pixel pitch
     * @queryParam brightness_min integer Minimum brightness in nits
     * @queryParam search string Search in name, series, description
     * @queryParam per_page integer Items per page (default: 12, max: 50)
     */
    public function index(ProductIndexRequest $request)
    {
        $validated = $request->validated();

        $category = $validated['category'] ?? null;
        $pixelPitchMin = $validated['pixel_pitch_min'] ?? null;
        $pixelPitchMax = $validated['pixel_pitch_max'] ?? null;
        $brightnessMin = $validated['brightness_min'] ?? null;
        $search = $validated['search'] ?? null;
        $perPage = $validated['per_page'] ?? 12;
        $page = $validated['page'] ?? 1;

        $cacheKey = "products.list.{$category}.{$pixelPitchMin}.{$pixelPitchMax}.{$brightnessMin}.{$search}.{$perPage}.page.{$page}";

        try {
            $products = Cache::remember($cacheKey, 3600, function () use ($category, $pixelPitchMin, $pixelPitchMax, $brightnessMin, $search, $perPage) {
                $query = Product::active()
                    ->with(['features:id,product_id,icon,title,description', 'applications:id,product_id,name,order'])
                    ->select([
                    'id', 'name', 'series', 'category', 'slug', 'image',
                    'environment', 'pixel_pitch', 'brightness_min', 'brightness_max',
                    'cabinet_size', 'price', 'is_active',
                ]);

                if ($category) {
                    $query->byCategory($category);
                }

                if ($pixelPitchMin !== null && $pixelPitchMax !== null) {
                    $query->whereBetween('pixel_pitch', [$pixelPitchMin, $pixelPitchMax]);
                }
                elseif ($pixelPitchMin !== null) {
                    $query->where('pixel_pitch', '>=', $pixelPitchMin);
                }
                elseif ($pixelPitchMax !== null) {
                    $query->where('pixel_pitch', '<=', $pixelPitchMax);
                }

                if ($brightnessMin !== null) {
                    $query->where('brightness_max', '>=', $brightnessMin);
                }

                if ($search) {
                    $query->where(function ($q) use ($search) {
                                $q->where('name', 'LIKE', "%{$search}%")
                                    ->orWhere('series', 'LIKE', "%{$search}%")
                                    ->orWhere('description', 'LIKE', "%{$search}%");
                            }
                            );
                        }

                        return $query->orderBy('category')->orderBy('pixel_pitch')->paginate($perPage);
                    });

            return ProductResource::collection($products);
        }
        catch (\Exception $e) {
            Log::error('Failed to fetch products', [
                'error' => $e->getMessage(),
                'filters' => $validated,
            ]);

            return response()->json([
                'message' => 'Failed to fetch products.',
            ], 500);
        }
    }

    /**
     * Get a single product by slug with full details.
     *
     * @param string $slug
     * @return ProductDetailResource|JsonResponse
     *
     * @urlParam slug string required The product slug. Example: ptf-p3
     */
    public function show(string $slug)
    {
        try {
            $product = Cache::remember("product.{$slug}", 7200, function () use ($slug) {
                return Product::active()
                ->with(['features', 'applications', 'specifications'])
                ->where('slug', $slug)
                ->first();
            });

            if (!$product) {
                return response()->json([
                    'message' => 'Product not found.',
                ], 404);
            }

            // Track product view
            try {
                $product->incrementViewCount();
                Cache::forget("product.{$slug}");
            }
            catch (\Exception $e) {
                Log::warning('Failed to track product view', [
                    'slug' => $slug,
                    'error' => $e->getMessage(),
                ]);
            }

            return new ProductDetailResource($product);
        }
        catch (\Exception $e) {
            Log::error('Failed to fetch product detail', [
                'slug' => $slug,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to fetch product details.',
            ], 500);
        }
    }

    /**
     * Get all product categories with product counts.
     *
     * @return JsonResponse
     *
     * Returns array of category objects with id, label, and active product count.
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = Cache::remember('products.categories', 1800, function () {
                return collect(ProductCategory::cases())->map(function (ProductCategory $category) {
                        return [
                        'id' => $category->value,
                        'label' => $category->getLabel(),
                        'count' => Product::active()->byCategory($category->value)->count(),
                        ];
                    }
                    )->toArray();
                });

            return response()->json(['data' => $categories]);
        }
        catch (\Exception $e) {
            Log::error('Failed to fetch product categories', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to fetch categories.',
            ], 500);
        }
    }
}
