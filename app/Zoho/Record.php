<?php

namespace App\Zoho;

use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class Record extends Zoho
{
    public function getRecords(string $moduleAPIName, array $filters = []): LengthAwarePaginator
    {
        $fields = array_diff_key($filters, array_flip($this->params));

        $criteria = '';

        foreach ($fields as $key => $value) {
            if ($criteria != null) {
                $criteria .= ' and ';
            }

            if ($key == 'equals' or $key == 'starts_with') {
                if (!is_array($value)) {
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

        $body = array_merge([
            'order_by' => $filters['order_by'] ?? null,
            'sort_by' => $filters['sort_by'] ?? null,
            'per_page' => $filters['per_page'] ?? 10,
            'page' => $filters['page'] ?? null,
        ], compact('criteria'));

        $responseData = parent::getAll($moduleAPIName, '/search', $body);

        $items = collect($responseData['data']);
        $total = $responseData['info']['count'];
        $perPage = $responseData['info']['per_page'];
        $currentPage = $responseData['info']['page'];
        $options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => [
                'sort_by' => $responseData['info']['sort_by'],
                'sort_order' => $responseData['info']['sort_order'],
            ],
        ];

        return new LengthAwarePaginator($items, $total, $perPage, $currentPage, $options);
    }
}
