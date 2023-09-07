<?php

namespace App\Helpers;

use Exception;
use App\Models\shared\ApiModel;
use App\Exceptions\ZohoApiException;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class ZohoHelper
{
    private array $params = ['page', 'sort_by', 'sort_order', 'per_page'];

    private array $operators = ['equals', 'starts_with'];

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

    // total access scope: ZohoCRM.settings.ALL, ZohoCRM.modules.ALL,
    public function generatePersistentToken(string $code): array
    {
        $response = Http::asForm()->post(config('zoho')['url_token'], [
            'client_id' => config('zoho.client_id'),
            'client_secret' => config('zoho.client_secret'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => config('zoho.redirect_uri'),
            'code' => $code,
        ])->json();

        if (isset($response['error'])) {
            throw new ZohoApiException($response);
        }

        return $response;
    }

    public function getRecords(string $moduleAPIName, array $params = []): LengthAwarePaginator
    {
        $criteria = '';

        foreach ($params as $key => $value) {
            if (!in_array($key, $this->params) and !empty($value)) {
                $filter = "($key:equals:$value)";

                $criteria = $criteria ? "($criteria and $filter)" : $filter;
            }
        }

        $filters = array_merge(compact('criteria'), [
            'per_page' => $params['per_page'] ?? 10,
            'page' => $params['page'] ?? 1,
            'sort_by' => $params['sort_by'] ?? null,
            'sort_order' => $params['sort_order'] ?? null
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->generateToken()['access_token']
        ])
            ->get(config('zoho.url') . $moduleAPIName .  !empty($criteria) ? '/search' : '', $filters)
            ->json();

        if (empty($response)) {
            throw new Exception(__('The Zoho API return a unknown error.'));
        }

        if (empty($response['data'])) {
            throw new ZohoApiException($response);
        }

        $collection = collect($response['data'])->mapInto(ApiModel::class);

        return new LengthAwarePaginator($collection, $response['info']['count'], $response['info']['per_page'], $response['info']['page'], [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => [
                'sort_by' => $response['info']['sort_by'],
                'sort_order' => $response['info']['sort_order'],
            ],
        ]);
    }

    public function getRecord(string $moduleAPIName, string $id): ApiModel
    {
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->generateToken()['access_token']
        ])
            ->get(config('zoho.url') . $moduleAPIName . "/$id")
            ->json();

        if (empty($response['data'])) {
            throw new ZohoApiException($response);
        }

        return new ApiModel($response['data'][0]);
    }

    public function create(string $moduleAPIName, array $attr): ApiModel
    {
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->generateToken()['access_token']
        ])
            ->post(config('zoho.url') . $moduleAPIName, [
                'data' => [$attr],
                'trigger' => ['approval', 'workflow', 'blueprint'],
            ])
            ->json();

        if (empty($response['data'])) {
            throw new ZohoApiException($response);
        }

        return $this->getRecord($moduleAPIName, $response['data'][0]['details']['id']);
    }
}
