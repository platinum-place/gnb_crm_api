<?php

namespace App\Models;

use App\Models\shared\ZohoModel;

class ZohoCase extends ZohoModel
{
    public string $module = "Cases";

    protected array $fillable = [
        "Owner", "P_liza", '$field_states', "Hora_de_cierre", "Diferencia", "Solicitante_2",
        "Caso_administrado", '$state', '$process_flow', "Monto", "Currency", "id", "Tipo_veh_culos",
        "Modelo_ce", "Desde", "Tiempo_de_despacho", "Status", '$approval', "Created_Time",
        "Product_Name", "Nota", "Modelo", "Punto_A", "Punto_B", "Created_By", "Related_To",
        "Asegurado", "Ubicaci_n", "Estado_p_liza", "Suplidor", "Description", "Hasta",
        "Marca_ce", "Hora_de_contacto", "Case_Number", "Asistido_por", '$review_process',
        "Caso_especial", '$canvas_id', "Bien", "KM_loma", "Ultima_actualizaci_n", "Fecha",
        "Account_Name", "Extracci_n", "Despacho", "Minutos_estimados", "Hora_de_llamada",
        "Tiempo_de_espera", "KM", "Contacto", "Add_Comment", "Plan", "Cierre", '$orchestration',
        "Marca", "Placa", "Tag", "Llamada", '$pathfinder', '$currency_symbol', "Internal_Comments",
        "Aseguradora", "Hora_de_despacho", "Exchange_Rate", '$approved', "Tiempo_estimado",
        "Tel_fono_2", "Color", '$editable', "Operador", "Peaje", "Historial", "Costo",
        "No_of_comments", "Horas_estimadas", "Hora_del_d_a", "Modified_By", "Feriado_Nocturnidad",
        '$review', "Tipo_de_asistencia", "Agendado", "Phone", "Precio_sugerido", "Chasis",
        "TUA", "Zona", "Modified_Time", "Comments", "Sobrepeso", "Case_Origin", "Solicitante",
        "Subject", "Tiempo_de_cierre", "A_o", '$in_merge', "Total", "C_dula", '$approval_state'
    ];

    protected array $mutable = [
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
        "Tipo_de_asistencia" => "service",
        "Zona" => "zone",
        "Asegurado" => "client_name",
    ];

    public function provider()
    {
        if ($this->Product_Name)
            return [
                "provider" => $this->Product_Name["name"],
                "locations" => (new ZohoProduct)->find($this->Product_Name["id"])->location
            ];
    }

    public function isFinished()
    {
        if ($this->Status)
            return in_array(
                $this->Status,
                [
                    "Medio servicio",
                    "Cancelado",
                    "Contacto",
                    "Cerrado",
                ]
            ) ? "Finished" : "In progress";
    }

    public function create(array $data = [])
    {
        return parent::create(array_merge($data, [
            "Status" => "Ubicado",
            "Caso_especial" => true,
            "Aseguradora" => auth()->user()->account_name,
            "Related_To" => auth()->user()->contact_name_id,
            "Subject" => "Asistencia remota",
            "Case_Origin" => "API",
        ]));
    }
}
