<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('portal_settings')->truncate();


        $isValid = DB::table('portal_settings')->where('name', 'oracle_erp_user')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'       => 'oracle_erp_user',
                    'val'        => 'gramos@tractocar.com',
                    'isEncrypt'  => '0',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            ]);
        }

        $isValid = DB::table('portal_settings')->where('name', 'oracle_erp_password')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'       => 'oracle_erp_password',
                    'val'        => Crypt::encryptString('*fYPHYy9ZhiCHjrpTRWW'),
                    'isEncrypt'  => '1',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            ]);
        }

        $isValid = DB::table('portal_settings')->where('name', 'oracle_erp_server')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'       => 'oracle_erp_server',
                    'val'        => 'https://ekhk.fa.us2.oraclecloud.com',
                    'isEncrypt'  => '0',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            ]);
        }

        $isValid = DB::table('portal_settings')->where('name', 'oracle_otm_user')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'       => 'oracle_otm_user',
                    'val'        => 'TCL.OCTOPUS',
                    'isEncrypt'  => '0',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            ]);
        }

        $isValid = DB::table('portal_settings')->where('name', 'oracle_otm_password')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'       => 'oracle_otm_password',
                    'val'        => Crypt::encryptString('Tracto2021'),
                    'isEncrypt'  => '1',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            ]);
        }

        $isValid = DB::table('portal_settings')->where('name', 'oracle_otm_server')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'       => 'oracle_otm_server',
                    'val'        => 'https://otmgtm-ekhk.otmgtm.us-phoenix-1.ocs.oraclecloud.com',
                    'isEncrypt'  => '0',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            ]);
        }

        $isValid = DB::table('portal_settings')->where('name', 'oracle_otm_user_soap')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'       => 'oracle_otm_user_soap',
                    'val'        => 'TCL.USERSOAP',
                    'isEncrypt'  => '0',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            ]);
        }

        $isValid = DB::table('portal_settings')->where('name', 'oracle_otm_password_soap')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'       => 'oracle_otm_password_soap',
                    'val'        => Crypt::encryptString('TRACTO2020'),
                    'isEncrypt'  => '1',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            ]);
        }

        $isValid = DB::table('portal_settings')->where('name', 'oracle_erp_date_end_default')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'       => 'oracle_erp_date_end_default',
                    'val'        => '0000-00-00 00:00:00',
                    'isEncrypt'  => '0',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            ]);
        }

        $isValid = DB::table('portal_settings')->where('name', 'user_ws_test')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'          => 'user_ws_test',
                    'val'           => 'TCL.RPTMONITOR',
                    'isEncrypt'     => '0',
                    'created_at'    => \Carbon\Carbon::now(),
                    'updated_at'    => \Carbon\Carbon::now()
                ]
            ]);
        }

        $isValid = DB::table('portal_settings')->where('name', 'test_ws_password')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'      => 'test_ws_password',
                    'val'       => Crypt::encryptString('@FTQ-hJ9Kvz6'),
                    'isEncrypt' => '1',
                    'created_at'            => \Carbon\Carbon::now(),
                    'updated_at'            => \Carbon\Carbon::now()
                ]
            ]);
        }

        $isValid = DB::table('portal_settings')->where('name', 'oracle_otm_soat_report_server_test')->doesntExist();
        if ($isValid) {

            DB::table('portal_settings')->insert([
                [
                    'name'      => 'oracle_otm_soat_report_server_test',
                    'val'       => 'https://otmgtm-ekhk.otmgtm.us-phoenix-1.ocs.oraclecloud.com/xmlpserver/services/v2/ReportService?WSDL',
                    'isEncrypt' => '0',
                    'created_at'            => \Carbon\Carbon::now(),
                    'updated_at'            => \Carbon\Carbon::now()
                ]
            ]);
        }
    }
}
