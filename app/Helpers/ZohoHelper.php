<?php

namespace App\Helpers;

use App\Exceptions\ZohoHttpException;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class ZohoHelper
{
    protected array
        $config = [];

    protected array
        $header = [];

    protected array
        $params = ['order_by', 'sort_by', 'per_page', 'page'];

    public function __construct()
    {
        $this->config = config('zoho');
        $this->header = ['Authorization' => 'Zoho-oauthtoken '.$this->generateToken()['access_token']];
    }

    public function generateToken(): array
    {
        $response = Http::asForm()->post($this->config['url_token'], [
            'client_id' => $this->config['client_id'],
            'client_secret' => $this->config['client_secret'],
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->config['refresh_token'],
        ]);

        return json_decode($response->body(), true);
    }

    // total access scope: ZohoCRM.settings.ALL, ZohoCRM.modules.ALL,
    public function generatePersistentToken(string $code): array
    {
        $response = Http::asForm()->post($this->config['url_token'], [
            'client_id' => $this->config['client_id'],
            'client_secret' => $this->config['client_secret'],
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->config['redirect_uri'],
            'code' => $code,
        ]);

        return json_decode($response->body(), true);
    }

    public function getRecords(string $moduleName, array $filters = [], bool $search = true): LengthAwarePaginator
    {
        $fields = array_diff_key($filters, array_flip($this->params));

        $criteria = '';

        foreach ($fields as $key => $value) {
            if ($criteria != null) {
                $criteria .= ' and ';
            }

            if ($key == 'equals' or $key == 'starts_with') {
                if (! is_array($value)) {
                    throw new Exception(__('It is not possible to buy more than 2 values'));
                }

                foreach ($value as $k => $v) {
                    if ($criteria != null) {
                        $criteria .= ' and ';
                    }

                    $criteria .= "(($k:$key:$v))";
                }
            } else {
                $criteria .= "(($key:equals:$value))";
            }
        }

        $response = Http::withHeaders($this->header)->get(
            $this->config['url_api'].$moduleName.($search ? '/search' : ''),
            array_merge([
                'order_by' => $filters['order_by'] ?? null,
                'sort_by' => $filters['sort_by'] ?? null,
                'per_page' => $filters['per_page'] ?? 10,
                'page' => $filters['page'] ?? null,
            ], compact('criteria'))
        );
        $responseData = json_decode($response->body(), true);

        if (! isset($responseData['data']) or (isset($responseData['status']) and $responseData['status'] == 'error')) {
            throw new ZohoHttpException($responseData, __('Records not found.'));
        }

        return new LengthAwarePaginator(collect($responseData['data']), $responseData['info']['count'], $responseData['info']['per_page'], $responseData['info']['page'], [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => [
                'sort_by' => $responseData['info']['sort_by'],
                'sort_order' => $responseData['info']['sort_order'],
            ],
        ]);
    }

    public function getRecord(string $moduleName, int|string $id): array
    {
        $response = Http::withHeaders($this->header)->get($this->config['url_api'].$moduleName."/$id");
        $responseData = json_decode($response->body(), true);

        if (! isset($responseData['data']) or (isset($responseData['status']) and $responseData['status'] == 'error')) {
            throw new ZohoHttpException($responseData, __('Record not found.'));
        }

        return $responseData['data'][0];
    }

    public function create(string $moduleName, array $body): int|string
    {
        $response = Http::withHeaders($this->header)->post($this->config['url_api'].$moduleName, [
            'data' => [$body],
            'trigger' => ['approval', 'workflow', 'blueprint'],
        ]);
        $responseData = json_decode($response->body(), true);

        if (! isset($responseData['data']) or (isset($responseData['status']) and $responseData['status'] == 'error')) {
            throw new ZohoHttpException($responseData, __('Server error.'));
        }

        return $responseData['data'][0]['details']['id'];
    }
}
