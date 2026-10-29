<?php

namespace Database\Seeders;

use App\BusinessDay;
use Illuminate\Database\Seeder;

class AvailabilitiesTableProductionSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('business_days')->delete();

        BusinessDay::create([
            "name" => "Lunes"
        ]);
        BusinessDay::create([
            "name" => "Martes"
        ]);
        BusinessDay::create([
            "name" => "Miércoles"
        ]);
        BusinessDay::create([
            "name" => "Jueves"
        ]);
        BusinessDay::create([
            "name" => "Viernes"
        ]);
        BusinessDay::create([
            "name" => "Sábado"
        ]);
        BusinessDay::create([
            "name" => "Domingo"
        ]);

        \DB::table('availabilities')->delete();
        
        \DB::table('availabilities')->insert(array (
            0 => 
            array (
                'id' => 10,
                'name' => 'DISPONIBILIDAD JORNADA NORMAL',
                'holidays' => 1,
                'headquarter_id' => 6,
                'enabled' => 1,
                'created_at' => '2021-01-26 11:59:24',
                'updated_at' => '2021-01-30 10:38:25',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 11,
                'name' => 'Bogotá Diurno 1',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 12:08:46',
                'updated_at' => '2021-01-26 12:08:46',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 12,
                'name' => 'Tecnicos',
                'holidays' => 1,
                'headquarter_id' => 8,
                'enabled' => 1,
                'created_at' => '2021-01-26 12:34:37',
                'updated_at' => '2021-01-27 11:05:25',
                'deleted_at' => '2021-01-27 11:05:25',
            ),
            3 => 
            array (
                'id' => 13,
                'name' => 'Tecnico 1',
                'holidays' => 1,
                'headquarter_id' => 8,
                'enabled' => 1,
                'created_at' => '2021-01-26 12:43:27',
                'updated_at' => '2021-01-27 11:05:18',
                'deleted_at' => '2021-01-27 11:05:18',
            ),
            4 => 
            array (
                'id' => 14,
                'name' => 'Bogotá Diurno 2',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:30:16',
                'updated_at' => '2021-01-26 18:30:16',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 15,
                'name' => 'Bogotá Diurno 3',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:30:53',
                'updated_at' => '2021-01-26 18:30:53',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 16,
                'name' => 'Bogotá Diurno 4',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:31:48',
                'updated_at' => '2021-01-26 18:31:48',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 17,
                'name' => 'Bogotá Diurno 5',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:32:32',
                'updated_at' => '2021-01-26 18:32:32',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 18,
                'name' => 'Bogotá Diurno 6',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:34:16',
                'updated_at' => '2021-01-26 18:34:16',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 19,
                'name' => 'Bogotá Diurno 7',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:34:50',
                'updated_at' => '2021-01-26 18:34:50',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 20,
                'name' => 'Bogotá Tarde noche 1',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:36:10',
                'updated_at' => '2021-01-26 18:36:10',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 21,
                'name' => 'Bogotá Tarde noche 2',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:37:35',
                'updated_at' => '2021-01-26 18:37:35',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 22,
                'name' => 'Bogotá Tarde noche 3',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:38:19',
                'updated_at' => '2021-01-26 18:38:19',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 23,
                'name' => 'Bogotá Tarde noche 4',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:39:22',
                'updated_at' => '2021-01-26 18:39:22',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 24,
                'name' => 'Bogotá Tarde noche 5',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:40:07',
                'updated_at' => '2021-01-26 18:40:07',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 25,
                'name' => 'Bogotá Sábado Diurno 1',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:41:15',
                'updated_at' => '2021-01-26 18:41:15',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 26,
                'name' => 'Bogotá Sábado Diurno 2',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:44:34',
                'updated_at' => '2021-01-26 18:44:34',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 27,
                'name' => 'Bogotá Sábado Diurno 3',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:45:01',
                'updated_at' => '2021-01-26 18:45:01',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 28,
                'name' => 'Bogotá Sábado Diurno 4',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:45:30',
                'updated_at' => '2021-01-26 18:45:30',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 29,
                'name' => 'Bogotá Sábado Diurno 5',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:46:01',
                'updated_at' => '2021-01-26 18:46:01',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 30,
                'name' => 'Bogotá Sábado Diurno 6',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:46:32',
                'updated_at' => '2021-01-26 18:46:32',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 31,
                'name' => 'Bogotá Sábado Tarde Noche 1',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:47:01',
                'updated_at' => '2021-01-26 18:47:01',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 32,
                'name' => 'Bogotá Sábado Tarde Noche 2',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:48:07',
                'updated_at' => '2021-01-26 18:48:07',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 33,
                'name' => 'Bogotá Sábado Tarde Noche 3',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:48:38',
                'updated_at' => '2021-01-26 18:48:38',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 34,
                'name' => 'Bogotá Domingos y Festivos 1',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:49:08',
                'updated_at' => '2021-01-26 18:49:08',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 35,
                'name' => 'Bogotá Domingos y Festivos 2',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:49:33',
                'updated_at' => '2021-01-26 18:49:33',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 36,
                'name' => 'Bogotá Domingos y Festivos 3',
                'holidays' => 1,
                'headquarter_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-01-26 18:49:57',
                'updated_at' => '2021-01-26 18:49:57',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 37,
                'name' => 'Turno 1',
                'holidays' => 1,
                'headquarter_id' => 8,
                'enabled' => 1,
                'created_at' => '2021-01-27 11:14:56',
                'updated_at' => '2021-01-27 11:25:21',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 38,
                'name' => 'Turno 2',
                'holidays' => 1,
                'headquarter_id' => 8,
                'enabled' => 1,
                'created_at' => '2021-01-27 11:22:19',
                'updated_at' => '2021-01-27 11:25:08',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 39,
                'name' => 'Turno 3',
                'holidays' => 1,
                'headquarter_id' => 8,
                'enabled' => 1,
                'created_at' => '2021-01-27 11:24:40',
                'updated_at' => '2021-01-27 11:24:56',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 40,
                'name' => 'Turno 4',
                'holidays' => 1,
                'headquarter_id' => 8,
                'enabled' => 1,
                'created_at' => '2021-01-27 11:27:20',
                'updated_at' => '2021-01-27 11:28:23',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 41,
                'name' => 'Turno 5',
                'holidays' => 1,
                'headquarter_id' => 8,
                'enabled' => 1,
                'created_at' => '2021-01-27 11:31:32',
                'updated_at' => '2021-01-27 11:31:32',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 42,
                'name' => 'Turno 1',
                'holidays' => 1,
                'headquarter_id' => 1,
                'enabled' => 1,
                'created_at' => '2021-01-27 14:31:58',
                'updated_at' => '2021-01-27 14:31:58',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 43,
                'name' => 'Turno 2',
                'holidays' => 1,
                'headquarter_id' => 1,
                'enabled' => 1,
                'created_at' => '2021-01-27 14:32:30',
                'updated_at' => '2021-01-27 14:32:30',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 44,
                'name' => 'Turno 3',
                'holidays' => 1,
                'headquarter_id' => 1,
                'enabled' => 1,
                'created_at' => '2021-01-27 14:36:37',
                'updated_at' => '2021-01-27 14:36:37',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 45,
                'name' => 'Turno 4',
                'holidays' => 1,
                'headquarter_id' => 1,
                'enabled' => 1,
                'created_at' => '2021-01-27 14:37:04',
                'updated_at' => '2021-01-27 14:50:23',
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 46,
                'name' => 'Turno 5',
                'holidays' => 1,
                'headquarter_id' => 1,
                'enabled' => 1,
                'created_at' => '2021-01-27 14:44:07',
                'updated_at' => '2021-01-27 14:50:30',
                'deleted_at' => NULL,
            ),
            37 => 
            array (
                'id' => 47,
                'name' => 'Turno 6',
                'holidays' => 1,
                'headquarter_id' => 1,
                'enabled' => 1,
                'created_at' => '2021-01-27 14:44:41',
                'updated_at' => '2021-01-27 14:50:37',
                'deleted_at' => NULL,
            ),
            38 => 
            array (
                'id' => 48,
                'name' => 'Turno 7',
                'holidays' => 1,
                'headquarter_id' => 1,
                'enabled' => 1,
                'created_at' => '2021-01-27 14:49:32',
                'updated_at' => '2021-01-27 14:49:32',
                'deleted_at' => NULL,
            ),
            39 => 
            array (
                'id' => 49,
                'name' => 'Turno 8',
                'holidays' => 1,
                'headquarter_id' => 1,
                'enabled' => 1,
                'created_at' => '2021-01-27 14:50:17',
                'updated_at' => '2021-01-27 14:51:25',
                'deleted_at' => NULL,
            ),
            40 => 
            array (
                'id' => 50,
                'name' => 'Turno 9',
                'holidays' => 1,
                'headquarter_id' => 1,
                'enabled' => 1,
                'created_at' => '2021-01-27 14:51:56',
                'updated_at' => '2021-01-27 14:51:56',
                'deleted_at' => NULL,
            ),
            41 => 
            array (
                'id' => 51,
                'name' => 'Turno 10',
                'holidays' => 1,
                'headquarter_id' => 1,
                'enabled' => 1,
                'created_at' => '2021-01-27 14:52:32',
                'updated_at' => '2021-01-27 14:52:32',
                'deleted_at' => NULL,
            ),
            42 => 
            array (
                'id' => 52,
                'name' => 'JORNADA CONTINUA MAÑANA TURNO 1',
                'holidays' => 1,
                'headquarter_id' => 3,
                'enabled' => 1,
                'created_at' => '2021-01-27 15:31:31',
                'updated_at' => '2021-01-27 15:51:04',
                'deleted_at' => NULL,
            ),
            43 => 
            array (
                'id' => 54,
                'name' => 'JORNADA CONTINUA MAÑANA TURNO 2',
                'holidays' => 1,
                'headquarter_id' => 3,
                'enabled' => 1,
                'created_at' => '2021-01-27 15:59:53',
                'updated_at' => '2021-01-27 15:59:53',
                'deleted_at' => NULL,
            ),
            44 => 
            array (
                'id' => 55,
                'name' => 'JORNADA CONTINUA TARDE',
                'holidays' => 1,
                'headquarter_id' => 3,
                'enabled' => 1,
                'created_at' => '2021-01-27 16:01:28',
                'updated_at' => '2021-01-27 16:01:28',
                'deleted_at' => NULL,
            ),
            45 => 
            array (
                'id' => 56,
                'name' => 'JORNADA CONTINUA TARNE TURNO 2',
                'holidays' => 1,
                'headquarter_id' => 3,
                'enabled' => 1,
                'created_at' => '2021-01-27 16:08:39',
                'updated_at' => '2021-01-27 16:08:39',
                'deleted_at' => NULL,
            ),
            46 => 
            array (
                'id' => 57,
                'name' => 'JORNADA NOCTURNA',
                'holidays' => 1,
                'headquarter_id' => 3,
                'enabled' => 1,
                'created_at' => '2021-01-27 16:27:33',
                'updated_at' => '2021-01-27 16:27:33',
                'deleted_at' => NULL,
            ),
            47 => 
            array (
                'id' => 58,
                'name' => 'JORNADA IDEAL',
                'holidays' => 1,
                'headquarter_id' => 3,
                'enabled' => 1,
                'created_at' => '2021-01-27 16:33:11',
                'updated_at' => '2021-01-27 16:33:11',
                'deleted_at' => NULL,
            ),
            48 => 
            array (
                'id' => 59,
                'name' => 'JORNADA CONTINUA',
                'holidays' => 1,
                'headquarter_id' => 3,
                'enabled' => 1,
                'created_at' => '2021-01-27 16:36:40',
                'updated_at' => '2021-01-27 16:36:40',
                'deleted_at' => NULL,
            ),
            49 => 
            array (
                'id' => 60,
                'name' => 'JORNADA FRACCIONADA',
                'holidays' => 1,
                'headquarter_id' => 3,
                'enabled' => 1,
                'created_at' => '2021-01-27 16:39:53',
                'updated_at' => '2021-01-27 16:39:53',
                'deleted_at' => NULL,
            ),
            50 => 
            array (
                'id' => 61,
                'name' => 'jornada Diurna',
                'holidays' => 1,
                'headquarter_id' => 5,
                'enabled' => 1,
                'created_at' => '2021-01-28 11:37:45',
                'updated_at' => '2021-01-29 08:56:45',
                'deleted_at' => NULL,
            ),
            51 => 
            array (
                'id' => 62,
                'name' => 'jornadas Nocturnas',
                'holidays' => 1,
                'headquarter_id' => 5,
                'enabled' => 1,
                'created_at' => '2021-01-28 11:39:26',
                'updated_at' => '2021-01-28 11:39:26',
                'deleted_at' => NULL,
            ),
            52 => 
            array (
                'id' => 63,
                'name' => 'Domingo Diurna',
                'holidays' => 1,
                'headquarter_id' => 5,
                'enabled' => 1,
                'created_at' => '2021-01-28 11:42:09',
                'updated_at' => '2021-01-28 11:42:09',
                'deleted_at' => NULL,
            ),
            53 => 
            array (
                'id' => 64,
                'name' => 'Domingo Nocturna',
                'holidays' => 1,
                'headquarter_id' => 5,
                'enabled' => 1,
                'created_at' => '2021-01-28 11:53:21',
                'updated_at' => '2021-01-28 14:20:57',
                'deleted_at' => NULL,
            ),
            54 => 
            array (
                'id' => 65,
                'name' => 'Domingo diurno 2',
                'holidays' => 1,
                'headquarter_id' => 5,
                'enabled' => 1,
                'created_at' => '2021-01-29 08:51:33',
                'updated_at' => '2021-01-29 08:51:33',
                'deleted_at' => NULL,
            ),
            55 => 
            array (
                'id' => 66,
                'name' => 'Jornada Diurna 2',
                'holidays' => 1,
                'headquarter_id' => 5,
                'enabled' => 1,
                'created_at' => '2021-01-29 08:53:35',
                'updated_at' => '2021-01-29 08:53:35',
                'deleted_at' => NULL,
            ),
            56 => 
            array (
                'id' => 67,
                'name' => 'Jornada Mañana',
                'holidays' => 1,
                'headquarter_id' => 5,
                'enabled' => 1,
                'created_at' => '2021-01-29 11:55:11',
                'updated_at' => '2021-01-29 11:55:11',
                'deleted_at' => NULL,
            ),
            57 => 
            array (
                'id' => 68,
                'name' => 'Jornada Tarde',
                'holidays' => 1,
                'headquarter_id' => 5,
                'enabled' => 1,
                'created_at' => '2021-01-29 11:55:46',
                'updated_at' => '2021-01-29 11:56:05',
                'deleted_at' => NULL,
            ),
            58 => 
            array (
                'id' => 69,
                'name' => 'DISPONIBILIDAD JORNADA CLINICA',
                'holidays' => 1,
                'headquarter_id' => 6,
                'enabled' => 1,
                'created_at' => '2021-01-30 10:41:51',
                'updated_at' => '2021-01-30 10:41:51',
                'deleted_at' => NULL,
            ),
            59 => 
            array (
                'id' => 70,
                'name' => 'IMPALA',
                'holidays' => 1,
                'headquarter_id' => 6,
                'enabled' => 1,
                'created_at' => '2021-01-30 11:39:37',
                'updated_at' => '2021-01-30 11:39:37',
                'deleted_at' => NULL,
            ),
            60 => 
            array (
                'id' => 71,
                'name' => 'Disponibilidad Celsia',
                'holidays' => 1,
                'headquarter_id' => 6,
                'enabled' => 1,
                'created_at' => '2021-01-30 11:53:58',
                'updated_at' => '2021-01-30 11:53:58',
                'deleted_at' => NULL,
            ),
            61 => 
            array (
                'id' => 72,
                'name' => 'Impala',
                'holidays' => 1,
                'headquarter_id' => 6,
                'enabled' => 1,
                'created_at' => '2021-01-30 11:55:16',
                'updated_at' => '2021-01-30 11:55:16',
                'deleted_at' => NULL,
            ),
            62 => 
            array (
                'id' => 73,
                'name' => 'DISPONIBILDAD',
                'holidays' => 1,
                'headquarter_id' => 6,
                'enabled' => 1,
                'created_at' => '2021-01-30 12:02:12',
                'updated_at' => '2021-01-30 12:02:12',
                'deleted_at' => NULL,
            ),
            63 => 
            array (
                'id' => 75,
                'name' => 'COMPASS REFINERIA',
                'holidays' => 1,
                'headquarter_id' => 6,
                'enabled' => 1,
                'created_at' => '2021-01-30 12:08:34',
                'updated_at' => '2021-01-30 12:08:34',
                'deleted_at' => NULL,
            ),
            64 => 
            array (
                'id' => 77,
                'name' => 'diurno 1',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 12:00:56',
                'updated_at' => '2021-02-01 12:00:56',
                'deleted_at' => NULL,
            ),
            65 => 
            array (
                'id' => 78,
                'name' => 'diurno 2',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 12:05:32',
                'updated_at' => '2021-02-01 12:05:32',
                'deleted_at' => NULL,
            ),
            66 => 
            array (
                'id' => 79,
                'name' => 'diurno 3',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 12:06:58',
                'updated_at' => '2021-02-01 12:06:58',
                'deleted_at' => NULL,
            ),
            67 => 
            array (
                'id' => 80,
                'name' => 'diurno 4',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 12:07:53',
                'updated_at' => '2021-02-01 12:07:53',
                'deleted_at' => NULL,
            ),
            68 => 
            array (
                'id' => 81,
                'name' => 'Diurno I',
                'holidays' => 1,
                'headquarter_id' => 4,
                'enabled' => 1,
                'created_at' => '2021-02-01 14:30:20',
                'updated_at' => '2021-02-01 14:30:31',
                'deleted_at' => NULL,
            ),
            69 => 
            array (
                'id' => 82,
                'name' => 'Diurno II',
                'holidays' => 1,
                'headquarter_id' => 4,
                'enabled' => 1,
                'created_at' => '2021-02-01 14:31:37',
                'updated_at' => '2021-02-01 14:31:37',
                'deleted_at' => NULL,
            ),
            70 => 
            array (
                'id' => 83,
                'name' => 'Diurno III',
                'holidays' => 1,
                'headquarter_id' => 4,
                'enabled' => 1,
                'created_at' => '2021-02-01 14:32:37',
                'updated_at' => '2021-02-01 14:32:37',
                'deleted_at' => NULL,
            ),
            71 => 
            array (
                'id' => 84,
                'name' => 'Diurno IIII',
                'holidays' => 1,
                'headquarter_id' => 4,
                'enabled' => 1,
                'created_at' => '2021-02-01 14:33:49',
                'updated_at' => '2021-02-01 14:33:49',
                'deleted_at' => NULL,
            ),
            72 => 
            array (
                'id' => 86,
                'name' => 'Turno I',
                'holidays' => 1,
                'headquarter_id' => 4,
                'enabled' => 1,
                'created_at' => '2021-02-01 14:35:29',
                'updated_at' => '2021-02-01 14:35:29',
                'deleted_at' => NULL,
            ),
            73 => 
            array (
                'id' => 87,
                'name' => 'Turno II',
                'holidays' => 1,
                'headquarter_id' => 4,
                'enabled' => 1,
                'created_at' => '2021-02-01 14:36:27',
                'updated_at' => '2021-02-01 14:36:27',
                'deleted_at' => NULL,
            ),
            74 => 
            array (
                'id' => 88,
                'name' => 'Turno III',
                'holidays' => 1,
                'headquarter_id' => 4,
                'enabled' => 1,
                'created_at' => '2021-02-01 14:37:15',
                'updated_at' => '2021-02-01 14:37:15',
                'deleted_at' => NULL,
            ),
            75 => 
            array (
                'id' => 89,
                'name' => 'Madrugada I',
                'holidays' => 1,
                'headquarter_id' => 4,
                'enabled' => 1,
                'created_at' => '2021-02-01 14:38:20',
                'updated_at' => '2021-02-01 14:38:20',
                'deleted_at' => NULL,
            ),
            76 => 
            array (
                'id' => 90,
                'name' => 'Turno 1',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 15:02:41',
                'updated_at' => '2021-02-01 15:02:41',
                'deleted_at' => NULL,
            ),
            77 => 
            array (
                'id' => 91,
                'name' => 'Turno 2',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 15:04:07',
                'updated_at' => '2021-02-01 15:04:07',
                'deleted_at' => NULL,
            ),
            78 => 
            array (
                'id' => 92,
                'name' => 'Turno 3',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 15:06:54',
                'updated_at' => '2021-02-01 15:06:54',
                'deleted_at' => NULL,
            ),
            79 => 
            array (
                'id' => 93,
                'name' => 'Turno 4',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 15:10:58',
                'updated_at' => '2021-02-01 15:10:58',
                'deleted_at' => NULL,
            ),
            80 => 
            array (
                'id' => 94,
                'name' => 'Nocturno 1',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 15:14:01',
                'updated_at' => '2021-02-01 15:14:01',
                'deleted_at' => NULL,
            ),
            81 => 
            array (
                'id' => 95,
                'name' => 'Nocturno 2',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 15:15:03',
                'updated_at' => '2021-02-01 15:15:03',
                'deleted_at' => NULL,
            ),
            82 => 
            array (
                'id' => 96,
                'name' => 'Madrugada 1',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 15:18:40',
                'updated_at' => '2021-02-01 15:18:40',
                'deleted_at' => NULL,
            ),
            83 => 
            array (
                'id' => 97,
                'name' => 'madrugada 2',
                'holidays' => 1,
                'headquarter_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-02-01 15:20:29',
                'updated_at' => '2021-02-01 15:20:29',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}