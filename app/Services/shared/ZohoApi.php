<?php

namespace App\Services\shared;

use App\Exceptions\ZohoApiException;
use App\Models\shared\ApiModel;
use App\Repositories\shared\ZohoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Facades\Zoho;
use Exception;
use Illuminate\Http\JsonResponse;

trait ZohoApi
{
    private array $params = ['page', 'sort_by', 'sort_order', 'per_page'];

    private array $operators = ['equals', 'starts_with'];

    public function filter(array $params = []): LengthAwarePaginator
    {
        $criteria = '';

        foreach ($params as $key => $value) {
            if (!in_array($key, $this->params)) {
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

        $response = Zoho::getRecords(
            $this->module,
            !empty($filters['criteria']) ? 'search' : '',
            $filters
        );

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

    public function findById(string $id): ApiModel
    {
        $response = Zoho::getRecord($this->module, $id);

        return new ApiModel($response['data'][0]);
    }

    public function create(array $attr): ApiModel
    {
        $response = Zoho::create($this->module, $attr);

        return $this->findById($response['data'][0]['details']['id']);
    }
}
