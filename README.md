# laravel-zoho-crm-api
## _Laravel package to handle zoho crm api_

Using Zoho CRM API, not the SDK. The idea consists of a facade that is in charge of encapsulating the most relevant methods of the api and returning them as json, just like the documentation, simplifying the way of using them.

## Enviroment

It is necessary to put the following lines in the .env file to load the credentials of the api, for more references to generate the credentials follow the official documentation of [Zoho CRM API](https://www.zoho.com/crm/developer/docs/api/v3/access-refresh.html)

```sh
ZOHO_URL_TOKEN="https://accounts.zoho.com/oauth/v2/token"
ZOHO_URL_API="https://www.zohoapis.com/crm/v2/"
ZOHO_REDIRECT_URI=""
ZOHO_CLIENT_ID=""
ZOHO_CLIENT_SECRET=""
ZOHO_REFRESH_TOKEN=""
```

## Service methods

### generateToken

Generate the access token. It is the token for which it must be placed in the header of any request made to the api. The service class automatically generates a token and adds it to each method.

Output:

```sh
{
    "access_token": "{access_token}",
    "api_domain": "https://www.zohoapis.com",
    "token_type": "Bearer",
    "expires_in": 3600
}
```

### generatePersistentToken

Generate the persistent token. Once generated, it must be placed in the .evn variable ZOHO_REFRESH_TOKEN to be used to automatically create temporary tokens.

| Input | Type | Description |
| ------ | ------ | ------ |
| $code | string | It is the temporary code that the zoho developer console generates when registering a valid scope, for example: ZohoCRM.settings.ALL, ZohoCRM.modules.ALL |

Output:

```sh
{
    "access_token": "{access_token}",
    "refresh_token": "{refresh_token}",
    "api_domain": "https://www.zohoapis.com",
    "token_type": "Bearer",
    "expires_in": 3600
}
```

### getRecords

Get all paginates records from one module.

| Input | Type | Description |
| ------ | ------ | ------ |
| $moduleName | string | Api module name in CRM |
| $body | array | Fields with api name and values to filter list. Example: sort_by = ["id", "Created_Time", "Modified_Time"], int $page = 1, int $per_page = 10, ... |

Output:

```sh
{
    "data": [],
    "info": {
        "per_page": 200,
        "count": 200,
        "page": 1,
        "sort_by": "id",
        "sort_order": "desc",
        "more_records": true
    }
}
```
