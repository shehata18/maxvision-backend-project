<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    /**
     * Get company information for the About page.
     *
     * Returns milestones, team members, certifications, partners, and stats
     * from the CompanyInfo key-value store.
     *
     * @return JsonResponse
     */
    public function about(): JsonResponse
    {
        try {
            $data = Cache::remember('company.about', 3600, function () {
                return [
                    'milestones' => CompanyInfo::getMilestones(),
                    'team' => CompanyInfo::getTeam(),
                    'certifications' => CompanyInfo::getCertifications(),
                    'partners' => CompanyInfo::getPartners(),
                    'stats' => CompanyInfo::getStats(),
                ];
            });

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch company information', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to fetch company information.',
            ], 500);
        }
    }

    /**
     * Get site settings as a flat key-value object.
     *
     * Returns all settings from the Settings key-value store including
     * site name, contact details, social media URLs, hero content, and footer.
     *
     * @return JsonResponse
     */
    public function settings(): JsonResponse
    {
        try {
            $data = Cache::remember('company.settings', 3600, function () {
                return Setting::getAll();
            });

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch site settings', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to fetch settings.',
            ], 500);
        }
    }
}
