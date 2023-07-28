<?php

namespace App\Helpers;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\record\Accounts;
use com\zoho\crm\api\record\ActionWrapper;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\ApplyFeatureExecution;
use com\zoho\crm\api\record\BodyWrapper;
use com\zoho\crm\api\record\Calls;
use com\zoho\crm\api\record\Campaigns;
use com\zoho\crm\api\record\Cases;
use com\zoho\crm\api\record\Contacts;
use com\zoho\crm\api\record\Deals;
use com\zoho\crm\api\record\Events;
use com\zoho\crm\api\record\FileBodyWrapper;
use com\zoho\crm\api\record\FileDetails;
use com\zoho\crm\api\record\ImageUpload;
use com\zoho\crm\api\record\Leads;
use com\zoho\crm\api\record\LineItemProduct;
use com\zoho\crm\api\record\LineTax;
use com\zoho\crm\api\record\Participants;
use com\zoho\crm\api\record\Price_Books;
use com\zoho\crm\api\record\PricingDetails;
use com\zoho\crm\api\record\Purchase_Orders;
use com\zoho\crm\api\record\Quotes;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\RecurringActivity;
use com\zoho\crm\api\record\RemindAt;
use com\zoho\crm\api\record\ResponseWrapper;
use com\zoho\crm\api\record\Sales_Orders;
use com\zoho\crm\api\record\SearchRecordsParam;
use com\zoho\crm\api\record\Solutions;
use com\zoho\crm\api\record\SuccessResponse;
use com\zoho\crm\api\record\Tasks;
use com\zoho\crm\api\record\Tax;
use com\zoho\crm\api\record\Vendors;
use com\zoho\crm\api\tags\Tag;
use com\zoho\crm\api\users\User;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\util\Choice;
use Illuminate\Pagination\LengthAwarePaginator;

class ZohoHelper
{
    public function __construct()
    {
        $user = new UserSignature(env('ZOHO_USER'));
        $environment = USDataCenter::PRODUCTION();
        /** @var \com\zoho\api\authenticator\Token */
        $token = (new OAuthBuilder())
            ->clientId(env('ZOHO_CLIENT_ID'))
            ->clientSecret(env('ZOHO_CLIENT_SECRET'))
            ->refreshToken(env('ZOHO_REFRESH_TOKEN'))
            ->build();
        (new InitializeBuilder())
            ->user($user)
            ->environment($environment)
            ->token($token)
            ->initialize();
    }

    public function searchRecords(string $moduleAPIName, array $filters = [])
    {
        $fields = array_diff_key($filters, array_flip(['order_by', 'sort_by', 'per_page', 'page']));

        /** @var object */
        $criteria = '';

        foreach ($fields as $key => $value) {
            if ($criteria != null) {
                $criteria .= ' and ';
            }

            if ($key == 'equals' or $key == 'starts_with') {
                if (!is_array($value)) {
                    throw new \Exception(__('It is not possible to buy more than 2 values'));
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

        $recordOperations = new RecordOperations();
        $paramInstance = new ParameterMap();
        $paramInstance->add(SearchRecordsParam::criteria(), $criteria);

        /** @var object */
        $page = isset($filters['page']) ? (int) $filters['page'] : 1;
        $paramInstance->add(SearchRecordsParam::page(), $page);

        /** @var object */
        $per_page = isset($filters['per_page']) ? (int) $filters['per_page'] : 10;
        $paramInstance->add(SearchRecordsParam::perPage(), $per_page);

        $response = $recordOperations->searchRecords($moduleAPIName, $paramInstance);

        if ($response != null) {
            if (in_array($response->getStatusCode(), [204, 304])) {
                return response()->json([
                    'Status' => $response->getStatusCode(),
                    'Message' => $response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n",
                ]);
            }
            if ($response->isExpected()) {
                $responseHandler = $response->getObject();
                if ($responseHandler instanceof ResponseWrapper) {
                    $responseWrapper = $responseHandler;
                    $records = $responseWrapper->getData();
                    if ($records != null) {
                        $info = $responseWrapper->getInfo();

                        return new LengthAwarePaginator(collect($records), $info->getCount(), $info->getPerPage(), $info->getPage(), [
                            'path' => LengthAwarePaginator::resolveCurrentPath(),
                        ]);
                    }
                } elseif ($responseHandler instanceof APIException) {
                    $exception = $responseHandler;
                    $details = array();
                    foreach ($exception->getDetails() as $key => $value) {
                        $details[$key] = $value;
                    }
                    return response()->json([
                        'Status' => $exception->getStatus()->getValue(),
                        'Code' => $exception->getCode()->getValue(),
                        'Details' => $details,
                        'Message' => $exception->getMessage()->getValue(),
                    ]);
                }
            } else {
                return response()->json($response);
            }
        }
    }

    public function getRecord(string $moduleAPIName, string $recordId, string $destinationFolder = null)
    {
        $recordOperations = new RecordOperations();
        $response = $recordOperations->getRecord($recordId, $moduleAPIName);
        if ($response != null) {
            if (in_array($response->getStatusCode(), [204, 304])) {
                return response()->json([
                    'Status' => $response->getStatusCode(),
                    'Message' => $response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n",
                ]);
            }
            if ($response->isExpected()) {
                $responseHandler = $response->getObject();
                if ($responseHandler instanceof ResponseWrapper) {
                    $responseWrapper = $responseHandler;
                    $records = $responseWrapper->getData();
                    if ($records != null) {
                        return $records[0];
                    }
                } elseif ($responseHandler instanceof FileBodyWrapper) {
                    $fileBodyWrapper = $responseHandler;
                    $streamWrapper = $fileBodyWrapper->getFile();
                    //Create a file instance with the absolute_file_path
                    $fp = fopen($destinationFolder . '/' . $streamWrapper->getName(), 'w');
                    /** @var string */
                    $stream = $streamWrapper->getStream();
                    fwrite($fp, $stream);
                    fclose($fp);
                } elseif ($responseHandler instanceof APIException) {
                    $exception = $responseHandler;
                    $details = array();
                    foreach ($exception->getDetails() as $key => $value) {
                        $details[$key] = $value;
                    }
                    return response()->json([
                        'Status' => $exception->getStatus()->getValue(),
                        'Code' => $exception->getCode()->getValue(),
                        'Details' => $details,
                        'Message' => $exception->getMessage()->getValue(),
                    ]);
                }
            } else {
                return response()->json($response);
            }
        }
    }

    public function createRecords(string $moduleAPIName, array $fields)
    {
        $recordOperations = new RecordOperations();
        $bodyWrapper = new BodyWrapper();
        $records = array();
        $recordClass = 'com\zoho\crm\api\record\Record';
        $record1 = new $recordClass();

        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                if ($recordClass == $value[1]) {
                    $record = new $recordClass();
                    $record->setId($value[0]);
                    $record1->addKeyValue($key, $record);
                } else {
                    $record1->addKeyValue($key, new $value[1]($value[0]));
                }
            } else {
                $record1->addKeyValue($key, $value);
            }
        }

        array_push($records, $record1);
        $bodyWrapper->setData($records);
        $trigger = array("approval", "workflow", "blueprint");
        $bodyWrapper->setTrigger($trigger);
        $headerInstance = new HeaderMap();
        $response = $recordOperations->createRecords($moduleAPIName, $bodyWrapper, $headerInstance);
        if ($response != null) {
            if ($response->isExpected()) {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper) {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getData();
                    foreach ($actionResponses as $actionResponse) {
                        if ($actionResponse instanceof SuccessResponse) {
                            $successResponse = $actionResponse;
                            $details = array();
                            foreach ($successResponse->getDetails() as $key => $value) {
                                $details[$key] = $value;
                            }
                            $values[] = [
                                'Status' => $successResponse->getStatus()->getValue(),
                                'Code' => $successResponse->getCode()->getValue(),
                                'Details' => $details,
                                'Message' => $successResponse->getMessage()->getValue(),
                            ];
                        } else if ($actionResponse instanceof APIException) {
                            $exception = $actionResponse;
                            $details = array();
                            foreach ($exception->getDetails() as $key => $value) {
                                $details[$key] = $value;
                            }
                            $values[] = [
                                'Status' => $exception->getStatus()->getValue(),
                                'Code' => $exception->getCode()->getValue(),
                                'Details' => $details,
                                'Message' => $exception->getMessage()->getValue(),
                            ];
                        }
                    }
                    return $values;
                } else if ($actionHandler instanceof APIException) {
                    $exception = $actionHandler;
                    $details = array();
                    foreach ($exception->getDetails() as $key => $value) {
                        $details[$key] = $value;
                    }
                    return response()->json([
                        'Status' => $exception->getStatus()->getValue(),
                        'Code' => $exception->getCode()->getValue(),
                        'Details' => $details,
                        'Message' => $exception->getMessage()->getValue(),
                    ]);
                }
            } else {
                return response()->json($response);
            }
        }
    }
}
