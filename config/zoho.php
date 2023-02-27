<?php

return [
    // config
    "url_token" => env("ZOHO_URL_TOKEN"),
    "url_api" => env("ZOHO_URL_API"),
    "redirect_uri" => env("ZOHO_REDIRECT_URI"),
    "client_id" => env("ZOHO_CLIENT_ID"),
    "client_secret" => env("ZOHO_CLIENT_SECRET"),
    "grant_type" => env("ZOHO_GRANT_TYPE"),
    "refresh_token" => env("ZOHO_REFRESH_TOKEN"),

    // special fields
    "case_subject" => env("ZOHO_CASE_SUBJECT"),
    "case_status" => env("ZOHO_START_CASE_STATUS"),
];
