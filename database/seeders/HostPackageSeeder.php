<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HostPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::create([
            'package_name'        => 'Basic',
            'price'               => "1500 1 + GST",
            'guest_limit'         => 200,
            'validity'            => "1 Year",
            'invitaion'           => 'Invitation',
            'rsvp'                => 'Save the Date RSVP',
            'ceramonies'          => 'Add own Ceremonies',
            'reports'             => 'Guest Reports',
            'gallery'             => 'Gallery - Snaps & Videos',
            'package_description' => 'SMS, eMail & Whatsapp message Services - Upto 300 Free - For additional: 1 Rs./Per Msg.',
            'wishboard'           => 'wishboard',
            'dcgqrcode'           => 'dcg',
            'vaf'                 => "*Any add-on SMS, Whatsapp\n** Can be Upgraded between\n***Use your Loyalty Points\n****Earn Referral Rewards",
            'invite_limit'        => 300,
            'storage_limit_mb'    => 512, // 0.5 GB in MB
        ]);
    }
}
