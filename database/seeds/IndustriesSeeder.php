<?php

use Illuminate\Database\Seeder;
use App\Models\Industries;

class IndustriesSeeder extends Seeder
{
    const INDUSTRIES = [
        'Agribisnis',
        'Akuntan',
        'Alas Kaki',
        'Asuransi',
        'Bioteknologi / biologi',
        'Biro Perjalanan',
        'Kertas',
        'Desain Interior',
        'E-Commerce',
        'Ekspedisi / Agen cargo',
        'Elektronika',
        'Energi',
        'Farmasi',
        'Furnitur',
        'Garmen / Tekstil',
        'Hiburan',
        'Hotel',
        'Hukum',
        'Internet',
        'Jasa Pemindahan',
        'Jasa Pengamanan',
        'Kecantikan',
        'Kehutanan',
        'Kelautan',
        'Keramik',
        'Keuangan / Bank',
        'Kimia',
        'Komputer (IT Hardware)',
        'Komputer / TI',
        'Konstruksi',
        'Konsultan',
        'Kosmetik',
        'Kulit',
        'Kurir',
        'Logam',
        'Logistik',
        'Mainan',
        'Makanan Dan Minuman',
        'Manajemen Fasilitas',
        'Manufaktur',
        'Media',
        'Mekanik / Listrik',
        'Mesin / Peralatan',
        'Minyak dam Gas',
        'Otomotif',
        'Pemerintahan',
        'Pendidikan',
        'Penerbangan',
        'Perawatan Kesehatan',
        'Percetakan',
        'Perdagangan Komoditas',
        'Perdagangan Umum',
        'Pergudangan',
        'Perikanan',
        'Periklanan',
        'Permata dan Perhiasan',
        'Pertambangan dan Mineral',
        'Produk Konsumen',
        'Properti',
        'Pupuk Pestisida',
        'Ritel',
        'Servis',
        'Telekomunikasi',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (static::INDUSTRIES as $industry) {
            Industries::create([
                'name' => $industry
            ]);
        }
        Industries::where('id', 52)->update(['icon' => '1.svg']);
        Industries::where('id', 26)->update(['icon' => '2.svg']);
        Industries::where('id', 29)->update(['icon' => '3.svg']);
        Industries::where('id', 61)->update(['icon' => '4.svg']);
        Industries::where('id', 40)->update(['icon' => '5.svg']);
        Industries::where('id', 38)->update(['icon' => '6.svg']);
        Industries::where('id', 58)->update(['icon' => '7.svg']);
        Industries::where('id', 30)->update(['icon' => '8.svg']);
        Industries::where('id', 47)->update(['icon' => '9.svg']);
        Industries::where('id', 13)->update(['icon' => '10.svg']);
        Industries::where('id', 59)->update(['icon' => '11.svg']);
        Industries::where('id', 62)->update(['icon' => '12.svg']);
        Industries::where('id', 63)->update(['icon' => '13.svg']);
        Industries::where('id', 4)->update(['icon' => '14.svg']);
        Industries::where('id', 45)->update(['icon' => '15.svg']);
        Industries::where('id', 49)->update(['icon' => '16.svg']);
        Industries::where('id', 41)->update(['icon' => '17.svg']);
        Industries::where('id', 9)->update(['icon' => '18.svg']);
    }
}
