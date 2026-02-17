<?php

namespace App\Console\Commands;

use App\Models\CaseStudy;
use App\Models\Product;
use App\Models\Solution;
use App\Services\ImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeExistingImages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'images:optimize';

    /**
     * The console command description.
     */
    protected $description = 'Optimize existing images and generate thumbnails for all products, solutions, and case studies';

    public function handle(): int
    {
        $service = app(ImageService::class);
        $sizes = config('images.thumbnail_sizes', [400, 800, 1200]);
        $quality = config('images.quality', 85);

        $this->info('Starting image optimization...');
        $this->newLine();

        $processed = 0;
        $skipped = 0;
        $errors = 0;

        // --- Products ---
        $products = Product::whereNotNull('image')->get();
        $this->info("Processing {$products->count()} product images...");
        $bar = $this->output->createProgressBar($products->count());

        foreach ($products as $product) {
            try {
                $this->processImage($product->image, 'products', $sizes, $quality, $processed, $skipped);
                $bar->advance();
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("  Failed: {$product->slug} — {$e->getMessage()}");
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        // --- Product gallery images ---
        $productsWithGallery = Product::whereNotNull('gallery')->get();
        $galleryCount = $productsWithGallery->sum(fn ($p) => count($p->gallery ?? []));
        $this->info("Processing {$galleryCount} product gallery images...");

        if ($galleryCount > 0) {
            $bar = $this->output->createProgressBar($galleryCount);
            foreach ($productsWithGallery as $product) {
                foreach ($product->gallery ?? [] as $path) {
                    try {
                        $this->processImage($path, 'products/gallery', $sizes, $quality, $processed, $skipped);
                        $bar->advance();
                    } catch (\Exception $e) {
                        $errors++;
                        $bar->advance();
                    }
                }
            }
            $bar->finish();
            $this->newLine(2);
        }

        // --- Solutions ---
        $solutions = Solution::whereNotNull('image')->get();
        $this->info("Processing {$solutions->count()} solution images...");
        $bar = $this->output->createProgressBar($solutions->count());

        foreach ($solutions as $solution) {
            try {
                $this->processImage($solution->image, 'solutions', $sizes, $quality, $processed, $skipped);
                $bar->advance();
            } catch (\Exception $e) {
                $errors++;
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        // --- Case Studies ---
        $caseStudies = CaseStudy::whereNotNull('image')->get();
        $this->info("Processing {$caseStudies->count()} case study images...");
        $bar = $this->output->createProgressBar($caseStudies->count());

        foreach ($caseStudies as $caseStudy) {
            try {
                $this->processImage($caseStudy->image, 'case-studies', $sizes, $quality, $processed, $skipped);
                $bar->advance();
            } catch (\Exception $e) {
                $errors++;
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('=== Optimization Summary ===');
        $this->line("  Processed:  {$processed}");
        $this->line("  Skipped:    {$skipped}");
        $this->line("  Errors:     {$errors}");
        $this->newLine();

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Process a single image: generate thumbnails if they don't already exist.
     */
    private function processImage(
        string $path,
        string $directory,
        array $sizes,
        int $quality,
        int &$processed,
        int &$skipped
    ): void {
        $disk = Storage::disk('public');

        if (!$disk->exists($path)) {
            $skipped++;
            return;
        }

        $filename = basename($path);

        // Check if thumbnails already exist for this image
        $firstThumbPath = "{$directory}/thumbnails/{$sizes[0]}/{$filename}";
        if ($disk->exists($firstThumbPath)) {
            $skipped++;
            return;
        }

        // Read original image and generate thumbnails
        $fullPath = $disk->path($path);
        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());

        foreach ($sizes as $size) {
            $thumb = $manager->read($fullPath);
            $thumb->cover($size, $size);

            $thumbPath = "{$directory}/thumbnails/{$size}/{$filename}";
            $encoded = $thumb->toWebp($quality);
            $disk->put($thumbPath, (string) $encoded);
        }

        $processed++;
    }
}
