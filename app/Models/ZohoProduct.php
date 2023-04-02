<?php

namespace App\Models;

use App\Facades\Navixy;
use App\Facades\Systrack;
use App\Models\shared\ZohoModel;

class ZohoProduct extends ZohoModel
{
    public string $module = "Products";

    protected array $fillable = [
        "Owner", "P_liza", "Riesgos_comprensivos", '$currency_symbol', '$field_states', "Restringir_veh_culos_de_uso",
        "Requisitos_codeudor", "Product_Active", "Lesiones_muerte_1_pers", "Suma_asegurada_min", "Tel_asistencia_vial",
        '$state', '$process_flow', "Requisitos_deudor", "Salvamento", "id", "Asistencia_vial", '$approved',
        "Valor_asistencia_vial", '$approval', "Riesgos_comprensivos_deducible", "Created_Time", "Product_Name", '$taxable',
        '$editable', "Lesiones_muerte_m_s_1_pers", "Riesgos_conductor", "Created_By", "Taxable", "Product_Category",
        "Lesiones_muerte_1_pas", "Tel_santo_domingo", "Description", "Vendor_Name", '$review_process', "Plazo_max",
        "Max_antig_edad", "Tel_santiago", "Prima_m_nima", "Colisi_n_y_vuelco", '$canvas_id', "Corredor", "Correo",
        "Fianza_judicial", "Modified_By", "KM_loma", '$review', "Product_Code", "Clave_API", "Modified_Time", "KM",
        "Pr_stamo_max", "Feriado_Nocturno", "Radio", "Sobrepeso", "Plataforma_API", "Da_os_propiedad_ajena",
        "Lesiones_muerte_m_s_1_pas", '$orchestration', "Suma_asegurada_max", "Incendio_y_robo", "Layout", "Qty_Ordered",
        '$in_merge', "En_caso_de_accidente", "Qty_in_Stock", "Tag", "Rotura_de_cristales_deducible", '$approval_state',
        '$pathfinder', "Tel_aseguradora", "Renta_veh_culo", "Unit_Price"
    ];

    public function location()
    {
        if ($this->Plataforma_API)
            return match ($this->Plataforma_API) {
                'Systrack' => Systrack::getLocation($this->Clave_API),
                'Navixy' => Navixy::getLocation($this->Clave_API),
            };
    }
}
