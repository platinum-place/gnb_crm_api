<?php

namespace App\Helpers;

use Exception;
use App\Models\shared\ApiModel;
use App\Exceptions\ZohoApiException;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class ZohoHelper
{
    public function generateToken(): array
    {
        $response = Http::asForm()->post(config('zoho.url_token'), [
            'client_id' => config('zoho.client_id'),
            'client_secret' => config('zoho.client_secret'),
            'grant_type' => 'refresh_token',
            'refresh_token' => config('zoho.refresh_token'),
        ])->json();

        if (isset($response['error'])) {
            throw new ZohoApiException($response);
        }

        return $response;
    }

    public function getRecords(string $moduleAPIName, array $params = [])
    {
        $criteria = null;

        foreach ($params as $key => $value) {
            if (!in_array($key, ['page', 'sort_by', 'sort_order', 'per_page']) and !empty($value)) {
                $filter = "($key:equals:$value)";

                $criteria = $criteria ? "($criteria and $filter)" : $filter;
            }
        }

        $filters = implode(',', [
            'per_page' => $params['per_page'] ?? 10,
            'page' => $params['page'] ?? 1,
            'sort_by' => $params['sort_by'] ?? 'id',
            'sort_order' => $params['sort_order'] ?? 'desc'
        ]);

        $response = Http::withHeaders(['Authorization' => 'Zoho-oauthtoken ' . $this->generateToken()['access_token']])
            ->get(config('zoho.url') . $moduleAPIName . '?fields=P_liza,sort_by=id&page=1&per_page=10&sort_order=desc&converted=both&include_child=false')
            ->json();

        if (empty($response)) {
            throw new Exception(__('The Zoho API return a unknown error.'));
        }

        if (empty($response['data'])) {
            throw new ZohoApiException($response);
        }

        dd($response);

        $collection = collect($response['data']);

        return new LengthAwarePaginator($collection, $response['info']['count'], $response['info']['per_page'], $response['info']['page'], [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => [
                'sort_by' => $response['info']['sort_by'],
                'sort_order' => $response['info']['sort_order'],
            ],
        ]);
    }
}
