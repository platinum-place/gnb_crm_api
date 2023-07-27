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
                echo $response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n";

                return;
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
                    echo 'Status: ' . $exception->getStatus()->getValue() . "\n";
                    echo 'Code: ' . $exception->getCode()->getValue() . "\n";
                    echo 'Details: ';
                    foreach ($exception->getDetails() as $key => $value) {
                        echo $key . ' : ' . $value . "\n";
                    }
                    echo 'Message: ' . $exception->getMessage()->getValue() . "\n";
                }
            } else {
                print_r($response);
            }
        }
    }

    public function getRecord(string $moduleAPIName, string $recordId, string $destinationFolder = null)
    {
        $recordOperations = new RecordOperations();
        $response = $recordOperations->getRecord($recordId, $moduleAPIName);
        if ($response != null) {
            if (in_array($response->getStatusCode(), [204, 304])) {
                echo $response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n";

                return;
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
                    echo 'Status: ' . $exception->getStatus()->getValue() . "\n";
                    echo 'Code: ' . $exception->getCode()->getValue() . "\n";
                    echo 'Details: ';
                    foreach ($exception->getDetails() as $key => $value) {
                        echo $key . ' : ' . $value . "\n";
                    }
                    echo 'Message: ' . $exception->getMessage()->getValue() . "\n";
                }
            } else {
                print_r($response);
            }
        }
    }

    public function createRecords(string $moduleAPIName, array $fields)
    {
        $recordOperations = new RecordOperations();
        $bodyWrapper = new BodyWrapper();
        $records = [];
        $recordClass = 'com\zoho\crm\api\record\Record';
        $record1 = new $recordClass();

        foreach ($fields as $key => $value) {
            $record1->addKeyValue($key, $value);
        }

        array_push($records, $record1);
        $bodyWrapper->setData($records);
        $bodyWrapper->setTrigger(['approval', 'workflow', 'blueprint']);

        $response = $recordOperations->createRecords($moduleAPIName, $bodyWrapper);
        if ($response != null) {
            if ($response->isExpected()) {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper) {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getData();
                    foreach ($actionResponses as $actionResponse) {
                        if ($actionResponse instanceof SuccessResponse) {
                            $successResponse = $actionResponse;
                            echo 'Status: ' . $successResponse->getStatus()->getValue() . "\n";
                            echo 'Code: ' . $successResponse->getCode()->getValue() . "\n";
                            echo 'Details: ';
                            foreach ($successResponse->getDetails() as $key => $value) {
                                echo $key . ' : ';
                                print_r($value);
                                echo "\n";
                            }
                            echo 'Message: ' . $successResponse->getMessage()->getValue() . "\n";
                        } elseif ($actionResponse instanceof APIException) {
                            $exception = $actionResponse;
                            echo 'Status: ' . $exception->getStatus()->getValue() . "\n";
                            echo 'Code: ' . $exception->getCode()->getValue() . "\n";
                            echo 'Details: ';
                            foreach ($exception->getDetails() as $key => $value) {
                                echo $key . ' : ' . $value . "\n";
                            }
                            echo 'Message: ' . $exception->getMessage()->getValue() . "\n";
                        }
                    }
                } elseif ($actionHandler instanceof APIException) {
                    $exception = $actionHandler;
                    echo 'Status: ' . $exception->getStatus()->getValue() . "\n";
                    echo 'Code: ' . $exception->getCode()->getValue() . "\n";
                    echo 'Details: ';
                    foreach ($exception->getDetails() as $key => $value) {
                        echo $key . ' : ' . $value . "\n";
                    }
                    echo 'Message: ' . $exception->getMessage()->getValue() . "\n";
                }
            } else {
                print_r($response);
            }
        }
    }
}
