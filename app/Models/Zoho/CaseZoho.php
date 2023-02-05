<?php

namespace App\Models\Zoho;

use App\Models\shared\ZohoModel;

class CaseZoho extends ZohoModel
{
    protected string $moduleName = 'Cases';

    protected array $fillable = [
        "Owner",
        "P_liza",
        '$field_states',
        "Hora_de_cierre",
        "Diferencia",
        "Solicitante_2",
        "Caso_administrado",
        '$state',
        '$process_flow',
        "Monto",
        "Currency",
        "id",
        "Tipo_veh_culos",
        "Modelo_ce",
        "Desde",
        "Tiempo_de_despacho",
        "Status",
        '$approval',
        "Created_Time",
        "Product_Name",
        "Nota",
        "Modelo",
        "Punto_A",
        "Punto_B",
        "Created_By",
        "Related_To",
        "Asegurado",
        "Ubicaci_n",
        "Estado_p_liza",
        "Suplidor",
        "Description",
        "Hasta",
        "Marca_ce",
        "Hora_de_contacto",
        "Case_Number",
        "Asistido_por",
        '$review_process',
        "Caso_especial",
        '$canvas_id',
        "Bien",
        "KM_loma",
        "Ultima_actualizaci_n",
        "Fecha",
        "Account_Name",
        "Extracci_n",
        "Despacho",
        "Minutos_estimados",
        "Hora_de_llamada",
        "Tiempo_de_espera",
        "KM",
        "Contacto",
        "Add_Comment",
        "Plan",
        "Cierre",
        '$orchestration',
        "Marca",
        "Placa",
        "Tag",
        "Llamada",
        '$pathfinder',
        '$currency_symbol',
        "Internal_Comments",
        "Aseguradora",
        "Hora_de_despacho",
        "Exchange_Rate",
        '$approved',
        "Tiempo_estimado",
        "Tel_fono_2",
        "Color",
        '$editable',
        "Operador",
        "Peaje",
        "Historial",
        "Costo",
        "No_of_comments",
        "Horas_estimadas",
        "Hora_del_d_a",
        "Modified_By",
        "Feriado_Nocturnidad",
        '$review',
        "Tipo_de_asistencia",
        "Agendado",
        "Phone",
        "Precio_sugerido",
        "Chasis",
        "TUA",
        "Zona",
        "Modified_Time",
        "Comments",
        "Sobrepeso",
        "Case_Origin",
        "Solicitante",
        "Subject",
        "Tiempo_de_cierre",
        "A_o",
        '$in_merge',
        "Total",
        "C_dula",
        '$approval_state',
    ];

    protected array $mutateable = [
        "P_liza" => "policy_number",
        "Chasis" => "chassis",
        "A_o" => "vehicle_year",
        "Color" => "vehicle_color",
        "Marca" => "vehicle_make",
        "Modelo" => "vehicle_model",
        "Placa" => "vehicle_plate",
        "Punto_A" => "site_a",
        "Punto_B" => "site_b",
        "Solicitante" => "client_name",
        "Phone" => "phone",
        "Plan" => "policy_plan",
        "Description" => "description",
        "Ubicaci_n" => "location_url",
        "Tipo_de_asistencia" => "service"
    ];

    public function isfinished(): bool
    {
        return in_array(
            $this->Status,
            [
                "Medio servicio",
                "Cancelado",
                "Contacto",
                "Cerrado",
            ]
        );
    }

    public function create(array $attributes = []): self|null
    {
        return parent::create(array_merge(
            $attributes,
            [
                "Status" => env("ZOHO_START_CASE_STATUS"),
                "Caso_especial" => true,
                "Account_Name" => "3222373000013452591", //from logged user
                "Aseguradora" => "LA COLONIAL, S.A", //from logged user
                "Subject" => env("ZOHO_CASE_SUBJECT"),
                "Related_To" => env("ZOHO_CASE_USER_ID"),
                "Case_Origin" => "API",
            ]
        ));
    }

    public function providerLocation()
    {
        $service = (new ProductZoho())->find($this->Product_Name["id"]);
        return $service->location();
    }
}
