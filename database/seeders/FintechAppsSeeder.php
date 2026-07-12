<?php

namespace Database\Seeders;

use App\Models\FintechApp;
use Illuminate\Database\Seeder;

class FintechAppsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apps = [
            [
                'name' => 'OPay',
                'package_name' => 'com.opay.pay',
                'playstore_id' => 'team.opay.pay', // It's actually team.opay.pay for OPay in Nigeria
                'appstore_id' => '1461623696',
                'platform' => 'both',
                'twitter_handle' => 'OPay_NG',
                'description' => 'OPay is a leading digital payment platform in Nigeria, providing mobile money services, transfers, and bill payments.',
                'logo_url' => 'https://play-lh.googleusercontent.com/4h_L7u8H7EHT1VnU-J27iYyV2tE25f_fGqT9Tq9Q_QyX_v3vW_T-P6Q96PqDq6R-D6M=w240-h480-rw',
                'is_active' => true,
            ],
            [
                'name' => 'PalmPay',
                'package_name' => 'com.palmpay.app',
                'playstore_id' => 'com.palmpay.app',
                'appstore_id' => '1475150937',
                'platform' => 'both',
                'twitter_handle' => 'palmpay_ng',
                'description' => 'PalmPay is a secure and rewarding digital wallet in Africa, offering financial services including transfers and bill payments.',
                'logo_url' => 'https://play-lh.googleusercontent.com/8Q_A9o7_0kP0Q_K_w0z0yH2pP0L_N1D-M6p_H_1u4-0-9A8X8H-R0i1E4I0B-M_3E-w=w240-h480-rw',
                'is_active' => true,
            ],
            [
                'name' => 'Kuda',
                'package_name' => 'com.kudabank.app',
                'playstore_id' => 'com.kudabank.app',
                'appstore_id' => '1483965935',
                'platform' => 'both',
                'twitter_handle' => 'kudabank',
                'description' => 'Kuda is the bank of the free. We are a microfinance bank in Nigeria offering zero fees on transfers.',
                'logo_url' => 'https://play-lh.googleusercontent.com/9K6P1a4V5s0V0Z7T-B9N9c8U3u7M2S9S4S9B5A8G4S0I3R0W8D4K7I9S3E0B3M0P6T8=w240-h480-rw',
                'is_active' => true,
            ],
        ];

        foreach ($apps as $appData) {
            FintechApp::updateOrCreate(
                ['name' => $appData['name']],
                $appData
            );
        }
    }
}
