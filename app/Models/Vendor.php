<?php

namespace App\Models;

use App\Models\shared\ZohoModel;

class Vendor extends ZohoModel
{
    protected string $module = 'Vendors';

    protected array $fillable = [
        "id", "Vendor_Name", "Tipo", 'Phone', 'Exchange_Rate', "Website",
        "Owner", "Country", "Currency", "Modified_By", "Record_Image",
        'Tag', 'State', "Description", "Zip_Code", "GL_Account", "Created_By", 'Email',
        "City", 'Category', "Street",
    ];
}
