<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FleetManager;

class FleetManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type_fleet = FleetManager::TYPE_FLEET_MANAGER;
        $type_mvi = FleetManager::TYPE_MVI;
        $status = true;
        $fleetManagers = [
            'Mr. Noman Farooq',
            "Ms. Aisha Nazir ",
            "Mr. Umar Javaid ",
            'Ms. Nimra Zareen',
            'Mr. Nisar Ahmad',
            'Mr. Khubaib Khan ',
            'Mr. Umer Khan',
            'Mr. Umer Faiz',
            'Hassan Sohail',
            "Mujahid",
            'Yasir Ayoob',
            'Jaam Abbas',
            'Maryam Saleems',
            'Suleman',
            'Hira Khan',
            'Khalid Yousaf',
            "Abbas",
            'Rizwan Saeed',
            'Pool Incharge',
        ];


        $mvis = [
            'Malik Naseer',
            'Imran Butt',
            'Javeed Abdul Shakur',
            'Shahbaz Butt',
            'Mirza Ghaffar',
            'Farooq Butt',
            'Liaqat Sidhu',
            'Luqman Bhatti',
            'Zahid Sarwar',
            'Ahsan Shah',
            'Ahmad Butt',
            'Imran Jutt',
            'Asif Mughal',
            'Ayub Butt',
            'Malik Sadique',
            'Malik Nadeem',
            'Safdar Abbas',
            'Javeed Iqbal',
            'Hafiz Amir',
            'M. Akram',
            'Rana Hamayun',
            'Javaid',
            'Shehzad Noor',
            'Yousaf Bashir',
            'Ahsan Bashir',
            'Qamar Zaman',
            'Ramzan',
            'Mohsin Saddique',
            'Hafiz Muhammad Asghar',
            'Ramzan Pathan',
            'Shahzad Ahmad',
            'Malik Jamil',
            'Tanvir Shah',
            'Yaseen',
            'Zahid',
            'Rana Ali',
            'Shoaib Abid',
            'Malik Ijaz',
            'Jamil',
            'Malik khalid',
            'Ahmad Butt',
            'Ahsan Shah',
            'M Ashraf',
            'Naveed Bhatti',
            'Haji Yousaf',
            'Yasir Ayoob',
            'Haseeb',
            'Yousaf Butt',
            'Mukhtam Raza',
            'Imran Saleem',
            'Pool Incharge',
        ];

        foreach ($fleetManagers as $manager) {
            FleetManager::Create(
                ['name' => $manager, 'type' => $type_fleet, 'is_active' => $status]
            );
        }

        foreach ($mvis as $mvi) {
            FleetManager::Create(
                ['name' => $mvi, 'type' => $type_mvi, 'is_active' => $status]
            );
        }
    }
}
