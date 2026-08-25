<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OracleRestErp
{
    protected static function getDataAccess()
    {
        try {
            return [
                'server'       => CommonUtils::getSetting('oracle_erp_server'),
                'username'     => CommonUtils::getSetting('oracle_erp_user'),
                'password'     => CommonUtils::getSetting('oracle_erp_password')
            ];
        } catch (Exception $e) {
            Log::error(__METHOD__ . '. General error: ' . $e->getMessage());
            return ['message' => 'Check oracle.php file configuration'];
        }
    }

    public static function procurementGetSuppliers($params = null)
    {

        $path = '/fscmRestApi/resources/11.13.18.05/suppliers';
        $erp  = self::getDataAccess();

        $url  = $erp['server'] . $path;

        $response = Http::withBasicAuth($erp['username'], $erp['password'])
            ->withHeaders([
                'REST-Framework-Version' => '2'
            ])->get($url, $params);

        return $response;
    }

    public static function getPayablesPayments($params = null)
    {
        $path = '/fscmRestApi/resources/11.13.18.05/payablesPayments';
        $erp  = self::getDataAccess();
        $url  = $erp['server'] . $path;
        $response = Http::withBasicAuth($erp['username'], $erp['password'])->timeout(60)
            ->retry(3, 1000)->withHeaders([
                'REST-Framework-Version' => '2',
                'Accept-Language' => 'es'
            ])->get($url, $params);
        return $response;
    }

    public static function getinvoiceHolds($params = null)
    {
        $path = '/fscmRestApi/resources/11.13.18.05/invoiceHolds';
        $erp = self::getDataAccess();
        $url  = $erp['server'] . $path;
        $response = Http::withBasicAuth($erp['username'], $erp['password'])->timeout(60)
            ->retry(3, 1000)->withHeaders([
                'REST-Framework-Version' => '2',
                'Accept-Language' => 'es'
            ])->get($url, $params);
        return $response;
    }

    public static function getInvoiceSuppliers($params = null)
    {
        $path = '/fscmRestApi/resources/11.13.18.05/invoices';
        $erp  = self::getDataAccess();
        $url  = $erp['server'] . $path;

        $response = Http::withBasicAuth($erp['username'], $erp['password'])->timeout(60)
            ->retry(3, 1000)->withHeaders([
                'REST-Framework-Version' => '2',
                'Accept-Language' => 'es'
            ])->get($url, $params);

        return $response;
    }

    public static function getInvoicesLines($InvoiceId, $params)
    {
        $path = "/fscmRestApi/resources/11.13.18.05/invoices/$InvoiceId/child/invoiceLines";
        $erp  = self::getDataAccess();
        $url  = $erp['server'] . $path;

        $response = Http::withBasicAuth($erp['username'], $erp['password'])->timeout(60)
            ->retry(3, 1000)->withHeaders([
                'REST-Framework-Version' => '2',
                'Accept-Language' => 'es'
            ])->get($url, $params);

        return $response;
    }
}
