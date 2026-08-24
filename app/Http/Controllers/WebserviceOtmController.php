<?php

namespace App\Http\Controllers;

use App\Http\Helpers\HelperIntegration;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebserviceOtmController extends Controller
{
    //
    public function store(Request $request)
    {
        $shipmentXid = null;
        try {
            // Process convert XML
            // $xmlContent         = str_replace('otm:', '', $request->getContent());
            // $xmlToJson          = json_decode(json_encode((array)simplexml_load_string($xmlContent), true));
            // $transmissionHeader = $xmlToJson->TransmissionHeader;
            // $transmissionBody   = $xmlToJson->TransmissionBody;
            // $body               = $transmissionBody->GLogXMLElement;
            // $ReleaseGid = $body->Release->ReleaseGid->Gid->Xid;
            $xmlContent = str_replace('otm:', '', $request->getContent());

            // Evita XXE: bloquea la resolución de entidades externas antes de parsear XML no confiable.
            libxml_set_external_entity_loader(function () {
                return null;
            });

            $xmlToJson          = json_decode(json_encode((array)simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NONET), true));
            $transmissionHeader = $xmlToJson->TransmissionHeader;
            $transmissionBody   = $xmlToJson->TransmissionBody;
            $body               = $transmissionBody->GLogXMLElement;
            $shipmentXid        = $body->PlannedShipment->Shipment->ShipmentHeader->ShipmentGid->Gid->Xid;
            $shipmentGid        = 'TCL.' . $shipmentXid;


            // Store Database Logs
            $integrationResult = [
                'integration_name' => 'OTM Process OrderReleases',
                'integration_id'   => $shipmentXid,
                'activity_name'    => 'XML reception',
                'payload'          => print_r($request->getContent(), true),
                'status_code'      => 201,
                'result'           => null
            ];
            // HelperIntegration::storeIntegrationResult($integrationResult);
            Log::info("shipmentXid: '{$shipmentXid}'");

            return response()->json(
                [
                    'result' => $integrationResult,
                    'shipmentXid' => $shipmentXid
                ]
            );
        } catch (Exception $e) {
            $integrationResult = [
                'integration_name' => 'OTM Process OrderReleases',
                'integration_id'   => $shipmentXid,
                'activity_name'    => 'Pit Process Error',
                'payload'          => '',
                'status_code'      => 400,
                'result'           => 'Line:' . $e->getLine() . '. Reason:' . $e->getMessage()
            ];
            return response()->json(
                [
                    'result'     => $integrationResult,
                    'ReleaseGid' => $e->getMessage()
                ]
            );
            // HelperIntegration::storeIntegrationResult($integrationResult);
            Log::error($e->getMessage());
        }
        // $shipmentXid        = $body->PlannedShipment->Shipment->ShipmentHeader->ShipmentGid->Gid->Xid;
        // $shipmentGid        = 'TCL.' . $shipmentXid;
    }
}
