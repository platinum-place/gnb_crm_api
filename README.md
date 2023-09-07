# GNB CRM API

Welcome to the repository of the **Laravel REST API Project for Zoho CRM Extension**! This project aims to provide an efficient solution for connecting the Zoho CRM system to business processes, enabling a seamless extension of the CRM to clients. Through this repository, we offer a REST API developed using the popular Laravel framework, serving as an intermediary between Zoho CRM and client applications.

## Key Features:

- **Zoho CRM Integration**: We effectively connect to the Zoho CRM API, granting access to crucial data, business processes, and CRM functionalities.
- **Laravel REST API**: We harness the power of the robust and scalable Laravel framework to develop a resilient REST API that caters to evolving business and client needs.
- **Extensibility**: Our solution is designed for companies to expand and customize Zoho CRM capabilities according to their unique requirements and offer these enhanced features to their clients.
- **Security**: Security is paramount. We implement robust security measures to ensure data confidentiality and integrity while communicating between Zoho CRM, our API, and client applications.
- **Comprehensive Documentation**: We provide thorough documentation guiding developers through the setup, usage, and customization of the Zoho CRM Extension API.

## Installation

Installing the navixy API into your Laravel project is simple and efficient thanks to Laravel Sail, a pre-configured local development environment. With these steps, you are ready to integrate navixy's tracking and monitoring functionality into your project.

## Prerequisites:

- Have Docker installed on your system.
- Laravel 10 or higher.
- PHP 8 or higher.

## Steps

1. Clone your Laravel repository or create a new one if you don't have it yet:

   ```bash
   git clone https://github.com/platinum-place/gnb_crm_api.git

2. Navigate to your project folder:

   ```bash
    cd gnb_crm_api

3. Install Composer packages:

   ```bash
    composer install

4. Start the local development environment using Sail:

    ```bash
    ./vendor/bin/sail up

### Config

- Configure your environment variables in the .env file for connecting to the zohocrm API. Add the necessary API keys provided by zohocrm:

```bash
    ZOHO_USER=""
    ZOHO_URL_TOKEN="https://accounts.zoho.com/oauth/v2/token"
    ZOHO_URL_API="https://www.zohoapis.com/crm/v2/"
    ZOHO_REDIRECT_URI=""
    ZOHO_CLIENT_ID=""
    ZOHO_CLIENT_SECRET=""
    ZOHO_REFRESH_TOKEN=""
```

- Configure your environment variables in the .env file for connecting to the navixy API. Add the necessary API keys provided by navixy:

```bash
    NAVIXY_URL="https://api.us.navixy.com/v2/tracker/"
    NAVIXY_HASH=""
```

- Configure your environment variables in the .env file for connecting to the Systrack API. Add the necessary API keys provided by Systrack:

```bash
    SYSTRACK_URL="http://xxx.xxx.xxx.xxx/comGpsGate/api/v.1/applications/xxx/xxx/"
    SYSTRACK_APP_ID=""
    SYSTRACK_USER=""
    SYSTRACK_PASS=""
    SYSTRACK_AUTHORIZATION=""
```
