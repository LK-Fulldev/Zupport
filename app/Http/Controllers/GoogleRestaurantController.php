<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Services\CallRestGoogle;
use App\Models\GoogleRestaurants;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\GoogleRestaurantsListCollection;

class GoogleRestaurantController extends Controller
{
    public function list(Request $request)
    {
        // Cache Query
        $googleData = Cache::remember($request->search_word, 3360, function () use ($request) {
            $googleData = GoogleRestaurants::query();
            $googleData->where('key_search', 'like', "%{$request->search_word}%");
            $googleData = $googleData->get();
            return $googleData;
        });

        if (!count($googleData)) {
            // Call Rest Google API
            $restClient = new CallRestGoogle();
            $result = $restClient->callRestaurants($request->search_word);
            foreach ($result['data'] as $value) {
                try {
                    if (!empty($value['photos'])) {
                        $setAttributeImg = json_encode([
                            'height' => $value['photos'][0]['height'],
                            'width' =>  $value['photos'][0]['width'],
                            'html_attributions' => $value['photos'][0]['html_attributions'][0],
                            'photo_reference' => $value['photos'][0]['photo_reference']
                        ]);
                        $setPhotos = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=800&photoreference=' . $value['photos'][0]['photo_reference'] . '&key=' . config('services.googlerestaurant.key');
                    }
                    $googleData = GoogleRestaurants::create([
                        'key_search' => $request->search_word,
                        'business_status' => $value['business_status'] ?? NULL,
                        'formatted_address' => $value['formatted_address'] ?? NULL,
                        'geometry' => NULL,
                        'icon' => $value['icon'] ?? NULL,
                        'icon_background_color' => $value['icon_background_color'] ?? NULL,
                        'icon_mask_base_uri' => $value['icon_mask_base_uri'] ?? NULL,
                        'name' => $value['name'] ?? NULL,
                        'opening_hours' => NULL,
                        'photos' => $setPhotos ?? NULL,
                        'photos_original' => $setAttributeImg ?? NULL,
                        'place_id' => $value['place_id'] ?? NULL,
                        'plus_code' => NULL,
                        'rating' => $value['rating'] ?? NULL,
                        'reference' => $value['reference'] ?? NULL,
                        'types' => (!empty($value['types'])) ? json_encode($value['types']) : NULL,
                        'user_ratings_total' => $value['user_ratings_total'] ?? NULL,
                        'date_current' => Date('Y-m-d H:i:s')
                    ]);
                } catch (Exception $e) {
                    echo "\n[Error]" . $e->getMessage() . ' Line: ' . $e->getLine();
                }
            }
            $googleData->refresh();
            // Query New Data
            $googleData = GoogleRestaurants::query();
            $googleData->where('key_search', 'like', "%{$request->search_word}%");
            $googleData = $googleData->get();
        }
        // Set Data Paginate For Response To Ajax Dom to HTML
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = new GoogleRestaurantsListCollection($googleData);
        $perPage = 10;
        $currentPageProducts = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedProducts = new LengthAwarePaginator($currentPageProducts, count($collection), $perPage);
        $paginatedProducts->setPath(request()->url());

        return response()->json([
            'data' => $paginatedProducts,
            'links' => $paginatedProducts->links()->toHtml()
        ]);
    }
}
