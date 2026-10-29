<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleUserTableProductionSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_user')->delete();
        
        \DB::table('role_user')->insert(array (
            0 => 
            array (
                'id' => 1,
                'role_id' => 1,
                'user_id' => 1,
                'created_at' => '2021-01-19 00:41:50',
                'updated_at' => '2021-01-19 00:41:50',
            ),
            1 => 
            array (
                'id' => 2,
                'role_id' => 1,
                'user_id' => 2,
                'created_at' => '2021-01-19 00:41:50',
                'updated_at' => '2021-01-19 00:41:50',
            ),
            2 => 
            array (
                'id' => 3,
                'role_id' => 2,
                'user_id' => 3,
                'created_at' => '2021-01-19 11:46:02',
                'updated_at' => '2021-01-19 11:46:02',
            ),
            3 => 
            array (
                'id' => 4,
                'role_id' => 6,
                'user_id' => 3,
                'created_at' => '2021-01-19 11:46:02',
                'updated_at' => '2021-01-19 11:46:02',
            ),
            4 => 
            array (
                'id' => 5,
                'role_id' => 4,
                'user_id' => 3,
                'created_at' => '2021-01-19 11:46:02',
                'updated_at' => '2021-01-19 11:46:02',
            ),
            5 => 
            array (
                'id' => 6,
                'role_id' => 11,
                'user_id' => 3,
                'created_at' => '2021-01-19 11:46:02',
                'updated_at' => '2021-01-19 11:46:02',
            ),
            6 => 
            array (
                'id' => 7,
                'role_id' => 12,
                'user_id' => 4,
                'created_at' => '2021-01-19 14:33:45',
                'updated_at' => '2021-01-19 14:33:45',
            ),
            7 => 
            array (
                'id' => 8,
                'role_id' => 2,
                'user_id' => 5,
                'created_at' => '2021-01-19 14:40:29',
                'updated_at' => '2021-01-19 14:40:29',
            ),
            8 => 
            array (
                'id' => 9,
                'role_id' => 6,
                'user_id' => 5,
                'created_at' => '2021-01-19 14:40:29',
                'updated_at' => '2021-01-19 14:40:29',
            ),
            9 => 
            array (
                'id' => 10,
                'role_id' => 4,
                'user_id' => 5,
                'created_at' => '2021-01-19 14:40:29',
                'updated_at' => '2021-01-19 14:40:29',
            ),
            10 => 
            array (
                'id' => 11,
                'role_id' => 11,
                'user_id' => 5,
                'created_at' => '2021-01-19 14:40:29',
                'updated_at' => '2021-01-19 14:40:29',
            ),
            11 => 
            array (
                'id' => 12,
                'role_id' => 6,
                'user_id' => 6,
                'created_at' => '2021-01-19 14:44:58',
                'updated_at' => '2021-01-19 14:44:58',
            ),
            12 => 
            array (
                'id' => 13,
                'role_id' => 4,
                'user_id' => 6,
                'created_at' => '2021-01-19 14:44:58',
                'updated_at' => '2021-01-19 14:44:58',
            ),
            13 => 
            array (
                'id' => 14,
                'role_id' => 11,
                'user_id' => 6,
                'created_at' => '2021-01-19 14:44:58',
                'updated_at' => '2021-01-19 14:44:58',
            ),
            14 => 
            array (
                'id' => 15,
                'role_id' => 12,
                'user_id' => 7,
                'created_at' => '2021-01-19 14:48:30',
                'updated_at' => '2021-01-19 14:48:30',
            ),
            15 => 
            array (
                'id' => 16,
                'role_id' => 2,
                'user_id' => 8,
                'created_at' => '2021-01-19 14:57:46',
                'updated_at' => '2021-01-19 14:57:46',
            ),
            16 => 
            array (
                'id' => 17,
                'role_id' => 6,
                'user_id' => 8,
                'created_at' => '2021-01-19 14:57:46',
                'updated_at' => '2021-01-19 14:57:46',
            ),
            17 => 
            array (
                'id' => 18,
                'role_id' => 4,
                'user_id' => 8,
                'created_at' => '2021-01-19 14:57:46',
                'updated_at' => '2021-01-19 14:57:46',
            ),
            18 => 
            array (
                'id' => 19,
                'role_id' => 11,
                'user_id' => 8,
                'created_at' => '2021-01-19 14:57:46',
                'updated_at' => '2021-01-19 14:57:46',
            ),
            19 => 
            array (
                'id' => 20,
                'role_id' => 6,
                'user_id' => 9,
                'created_at' => '2021-01-19 15:02:19',
                'updated_at' => '2021-01-19 15:02:19',
            ),
            20 => 
            array (
                'id' => 21,
                'role_id' => 4,
                'user_id' => 9,
                'created_at' => '2021-01-19 15:02:19',
                'updated_at' => '2021-01-19 15:02:19',
            ),
            21 => 
            array (
                'id' => 22,
                'role_id' => 11,
                'user_id' => 9,
                'created_at' => '2021-01-19 15:02:19',
                'updated_at' => '2021-01-19 15:02:19',
            ),
            22 => 
            array (
                'id' => 23,
                'role_id' => 12,
                'user_id' => 10,
                'created_at' => '2021-01-19 15:06:45',
                'updated_at' => '2021-01-19 15:06:45',
            ),
            23 => 
            array (
                'id' => 24,
                'role_id' => 2,
                'user_id' => 11,
                'created_at' => '2021-01-19 15:09:10',
                'updated_at' => '2021-01-19 15:09:10',
            ),
            24 => 
            array (
                'id' => 25,
                'role_id' => 6,
                'user_id' => 11,
                'created_at' => '2021-01-19 15:09:10',
                'updated_at' => '2021-01-19 15:09:10',
            ),
            25 => 
            array (
                'id' => 26,
                'role_id' => 4,
                'user_id' => 11,
                'created_at' => '2021-01-19 15:09:10',
                'updated_at' => '2021-01-19 15:09:10',
            ),
            26 => 
            array (
                'id' => 27,
                'role_id' => 11,
                'user_id' => 11,
                'created_at' => '2021-01-19 15:09:10',
                'updated_at' => '2021-01-19 15:09:10',
            ),
            27 => 
            array (
                'id' => 28,
                'role_id' => 6,
                'user_id' => 12,
                'created_at' => '2021-01-19 15:11:10',
                'updated_at' => '2021-01-19 15:11:10',
            ),
            28 => 
            array (
                'id' => 29,
                'role_id' => 11,
                'user_id' => 12,
                'created_at' => '2021-01-19 15:11:10',
                'updated_at' => '2021-01-19 15:11:10',
            ),
            29 => 
            array (
                'id' => 30,
                'role_id' => 4,
                'user_id' => 12,
                'created_at' => '2021-01-19 15:11:10',
                'updated_at' => '2021-01-19 15:11:10',
            ),
            30 => 
            array (
                'id' => 31,
                'role_id' => 12,
                'user_id' => 13,
                'created_at' => '2021-01-19 15:12:26',
                'updated_at' => '2021-01-19 15:12:26',
            ),
            31 => 
            array (
                'id' => 32,
                'role_id' => 2,
                'user_id' => 14,
                'created_at' => '2021-01-19 15:15:38',
                'updated_at' => '2021-01-19 15:15:38',
            ),
            32 => 
            array (
                'id' => 33,
                'role_id' => 6,
                'user_id' => 14,
                'created_at' => '2021-01-19 15:15:38',
                'updated_at' => '2021-01-19 15:15:38',
            ),
            33 => 
            array (
                'id' => 34,
                'role_id' => 4,
                'user_id' => 14,
                'created_at' => '2021-01-19 15:15:38',
                'updated_at' => '2021-01-19 15:15:38',
            ),
            34 => 
            array (
                'id' => 35,
                'role_id' => 11,
                'user_id' => 14,
                'created_at' => '2021-01-19 15:15:38',
                'updated_at' => '2021-01-19 15:15:38',
            ),
            35 => 
            array (
                'id' => 36,
                'role_id' => 2,
                'user_id' => 15,
                'created_at' => '2021-01-19 15:20:03',
                'updated_at' => '2021-01-19 15:20:03',
            ),
            36 => 
            array (
                'id' => 37,
                'role_id' => 9,
                'user_id' => 15,
                'created_at' => '2021-01-19 15:20:03',
                'updated_at' => '2021-01-19 15:20:03',
            ),
            37 => 
            array (
                'id' => 38,
                'role_id' => 2,
                'user_id' => 16,
                'created_at' => '2021-01-19 15:21:14',
                'updated_at' => '2021-01-19 15:21:14',
            ),
            38 => 
            array (
                'id' => 39,
                'role_id' => 9,
                'user_id' => 16,
                'created_at' => '2021-01-19 15:21:14',
                'updated_at' => '2021-01-19 15:21:14',
            ),
            39 => 
            array (
                'id' => 40,
                'role_id' => 2,
                'user_id' => 17,
                'created_at' => '2021-01-19 15:22:17',
                'updated_at' => '2021-01-19 15:22:17',
            ),
            40 => 
            array (
                'id' => 41,
                'role_id' => 6,
                'user_id' => 17,
                'created_at' => '2021-01-19 15:22:17',
                'updated_at' => '2021-01-19 15:22:17',
            ),
            41 => 
            array (
                'id' => 42,
                'role_id' => 4,
                'user_id' => 17,
                'created_at' => '2021-01-19 15:22:17',
                'updated_at' => '2021-01-19 15:22:17',
            ),
            42 => 
            array (
                'id' => 43,
                'role_id' => 11,
                'user_id' => 17,
                'created_at' => '2021-01-19 15:22:17',
                'updated_at' => '2021-01-19 15:22:17',
            ),
            43 => 
            array (
                'id' => 44,
                'role_id' => 2,
                'user_id' => 18,
                'created_at' => '2021-01-19 15:23:30',
                'updated_at' => '2021-01-19 15:23:30',
            ),
            44 => 
            array (
                'id' => 45,
                'role_id' => 8,
                'user_id' => 19,
                'created_at' => '2021-01-19 16:17:06',
                'updated_at' => '2021-01-19 16:17:06',
            ),
            45 => 
            array (
                'id' => 47,
                'role_id' => 8,
                'user_id' => 21,
                'created_at' => '2021-01-19 16:19:44',
                'updated_at' => '2021-01-19 16:19:44',
            ),
            46 => 
            array (
                'id' => 48,
                'role_id' => 7,
                'user_id' => 22,
                'created_at' => '2021-01-19 16:21:28',
                'updated_at' => '2021-01-19 16:21:28',
            ),
            47 => 
            array (
                'id' => 49,
                'role_id' => 7,
                'user_id' => 23,
                'created_at' => '2021-01-19 16:22:46',
                'updated_at' => '2021-01-19 16:22:46',
            ),
            48 => 
            array (
                'id' => 50,
                'role_id' => 4,
                'user_id' => 24,
                'created_at' => '2021-01-19 16:24:44',
                'updated_at' => '2021-01-19 16:24:44',
            ),
            49 => 
            array (
                'id' => 51,
                'role_id' => 2,
                'user_id' => 25,
                'created_at' => '2021-01-19 16:29:06',
                'updated_at' => '2021-01-19 16:29:06',
            ),
            50 => 
            array (
                'id' => 52,
                'role_id' => 8,
                'user_id' => 26,
                'created_at' => '2021-01-19 16:33:10',
                'updated_at' => '2021-01-19 16:33:10',
            ),
            51 => 
            array (
                'id' => 53,
                'role_id' => 5,
                'user_id' => 27,
                'created_at' => '2021-01-19 16:36:23',
                'updated_at' => '2021-01-19 16:36:23',
            ),
            52 => 
            array (
                'id' => 54,
                'role_id' => 8,
                'user_id' => 28,
                'created_at' => '2021-01-19 16:38:58',
                'updated_at' => '2021-01-19 16:38:58',
            ),
            53 => 
            array (
                'id' => 55,
                'role_id' => 9,
                'user_id' => 29,
                'created_at' => '2021-01-19 16:42:18',
                'updated_at' => '2021-01-19 16:42:18',
            ),
            54 => 
            array (
                'id' => 56,
                'role_id' => 11,
                'user_id' => 30,
                'created_at' => '2021-01-19 16:46:02',
                'updated_at' => '2021-01-19 16:46:02',
            ),
            55 => 
            array (
                'id' => 57,
                'role_id' => 11,
                'user_id' => 31,
                'created_at' => '2021-01-19 16:47:57',
                'updated_at' => '2021-01-19 16:47:57',
            ),
            56 => 
            array (
                'id' => 58,
                'role_id' => 11,
                'user_id' => 32,
                'created_at' => '2021-01-19 16:49:23',
                'updated_at' => '2021-01-19 16:49:23',
            ),
            57 => 
            array (
                'id' => 59,
                'role_id' => 11,
                'user_id' => 33,
                'created_at' => '2021-01-19 16:52:24',
                'updated_at' => '2021-01-19 16:52:24',
            ),
            58 => 
            array (
                'id' => 60,
                'role_id' => 11,
                'user_id' => 34,
                'created_at' => '2021-01-19 16:56:26',
                'updated_at' => '2021-01-19 16:56:26',
            ),
            59 => 
            array (
                'id' => 61,
                'role_id' => 11,
                'user_id' => 35,
                'created_at' => '2021-01-19 17:02:43',
                'updated_at' => '2021-01-19 17:02:43',
            ),
            60 => 
            array (
                'id' => 62,
                'role_id' => 11,
                'user_id' => 36,
                'created_at' => '2021-01-19 17:04:42',
                'updated_at' => '2021-01-19 17:04:42',
            ),
            61 => 
            array (
                'id' => 63,
                'role_id' => 11,
                'user_id' => 37,
                'created_at' => '2021-01-19 17:07:25',
                'updated_at' => '2021-01-19 17:07:25',
            ),
            62 => 
            array (
                'id' => 64,
                'role_id' => 11,
                'user_id' => 38,
                'created_at' => '2021-01-19 17:09:34',
                'updated_at' => '2021-01-19 17:09:34',
            ),
            63 => 
            array (
                'id' => 65,
                'role_id' => 11,
                'user_id' => 39,
                'created_at' => '2021-01-19 17:11:13',
                'updated_at' => '2021-01-19 17:11:13',
            ),
            64 => 
            array (
                'id' => 66,
                'role_id' => 11,
                'user_id' => 40,
                'created_at' => '2021-01-19 17:13:55',
                'updated_at' => '2021-01-19 17:13:55',
            ),
            65 => 
            array (
                'id' => 67,
                'role_id' => 11,
                'user_id' => 41,
                'created_at' => '2021-01-19 17:17:23',
                'updated_at' => '2021-01-19 17:17:23',
            ),
            66 => 
            array (
                'id' => 68,
                'role_id' => 11,
                'user_id' => 42,
                'created_at' => '2021-01-19 17:24:50',
                'updated_at' => '2021-01-19 17:24:50',
            ),
            67 => 
            array (
                'id' => 69,
                'role_id' => 11,
                'user_id' => 43,
                'created_at' => '2021-01-19 17:27:32',
                'updated_at' => '2021-01-19 17:27:32',
            ),
            68 => 
            array (
                'id' => 70,
                'role_id' => 11,
                'user_id' => 44,
                'created_at' => '2021-01-19 17:30:50',
                'updated_at' => '2021-01-19 17:30:50',
            ),
            69 => 
            array (
                'id' => 71,
                'role_id' => 9,
                'user_id' => 45,
                'created_at' => '2021-01-19 17:36:49',
                'updated_at' => '2021-01-19 17:36:49',
            ),
            70 => 
            array (
                'id' => 72,
                'role_id' => 4,
                'user_id' => 46,
                'created_at' => '2021-01-19 18:03:11',
                'updated_at' => '2021-01-19 18:03:11',
            ),
            71 => 
            array (
                'id' => 74,
                'role_id' => 6,
                'user_id' => 48,
                'created_at' => '2021-01-19 18:09:59',
                'updated_at' => '2021-01-19 18:09:59',
            ),
            72 => 
            array (
                'id' => 75,
                'role_id' => 11,
                'user_id' => 49,
                'created_at' => '2021-01-19 18:11:58',
                'updated_at' => '2021-01-19 18:11:58',
            ),
            73 => 
            array (
                'id' => 76,
                'role_id' => 11,
                'user_id' => 50,
                'created_at' => '2021-01-19 18:31:55',
                'updated_at' => '2021-01-19 18:31:55',
            ),
            74 => 
            array (
                'id' => 77,
                'role_id' => 11,
                'user_id' => 51,
                'created_at' => '2021-01-19 18:40:48',
                'updated_at' => '2021-01-19 18:40:48',
            ),
            75 => 
            array (
                'id' => 78,
                'role_id' => 11,
                'user_id' => 52,
                'created_at' => '2021-01-19 18:43:38',
                'updated_at' => '2021-01-19 18:43:38',
            ),
            76 => 
            array (
                'id' => 79,
                'role_id' => 11,
                'user_id' => 53,
                'created_at' => '2021-01-19 22:41:42',
                'updated_at' => '2021-01-19 22:41:42',
            ),
            77 => 
            array (
                'id' => 80,
                'role_id' => 11,
                'user_id' => 54,
                'created_at' => '2021-01-19 22:44:08',
                'updated_at' => '2021-01-19 22:44:08',
            ),
            78 => 
            array (
                'id' => 81,
                'role_id' => 6,
                'user_id' => 4,
                'created_at' => '2021-01-20 08:04:07',
                'updated_at' => '2021-01-20 08:04:07',
            ),
            79 => 
            array (
                'id' => 82,
                'role_id' => 11,
                'user_id' => 55,
                'created_at' => '2021-01-20 09:07:13',
                'updated_at' => '2021-01-20 09:07:13',
            ),
            80 => 
            array (
                'id' => 83,
                'role_id' => 11,
                'user_id' => 56,
                'created_at' => '2021-01-20 09:17:26',
                'updated_at' => '2021-01-20 09:17:26',
            ),
            81 => 
            array (
                'id' => 84,
                'role_id' => 11,
                'user_id' => 57,
                'created_at' => '2021-01-20 09:17:38',
                'updated_at' => '2021-01-20 09:17:38',
            ),
            82 => 
            array (
                'id' => 85,
                'role_id' => 11,
                'user_id' => 58,
                'created_at' => '2021-01-20 09:19:15',
                'updated_at' => '2021-01-20 09:19:15',
            ),
            83 => 
            array (
                'id' => 86,
                'role_id' => 11,
                'user_id' => 59,
                'created_at' => '2021-01-20 09:19:49',
                'updated_at' => '2021-01-20 09:19:49',
            ),
            84 => 
            array (
                'id' => 87,
                'role_id' => 11,
                'user_id' => 60,
                'created_at' => '2021-01-20 09:21:11',
                'updated_at' => '2021-01-20 09:21:11',
            ),
            85 => 
            array (
                'id' => 88,
                'role_id' => 11,
                'user_id' => 61,
                'created_at' => '2021-01-20 09:22:40',
                'updated_at' => '2021-01-20 09:22:40',
            ),
            86 => 
            array (
                'id' => 89,
                'role_id' => 11,
                'user_id' => 62,
                'created_at' => '2021-01-20 09:23:59',
                'updated_at' => '2021-01-20 09:23:59',
            ),
            87 => 
            array (
                'id' => 90,
                'role_id' => 11,
                'user_id' => 63,
                'created_at' => '2021-01-20 09:24:17',
                'updated_at' => '2021-01-20 09:24:17',
            ),
            88 => 
            array (
                'id' => 91,
                'role_id' => 11,
                'user_id' => 64,
                'created_at' => '2021-01-20 09:25:42',
                'updated_at' => '2021-01-20 09:25:42',
            ),
            89 => 
            array (
                'id' => 92,
                'role_id' => 11,
                'user_id' => 65,
                'created_at' => '2021-01-20 09:25:56',
                'updated_at' => '2021-01-20 09:25:56',
            ),
            90 => 
            array (
                'id' => 93,
                'role_id' => 11,
                'user_id' => 66,
                'created_at' => '2021-01-20 09:27:30',
                'updated_at' => '2021-01-20 09:27:30',
            ),
            91 => 
            array (
                'id' => 94,
                'role_id' => 11,
                'user_id' => 67,
                'created_at' => '2021-01-20 09:30:02',
                'updated_at' => '2021-01-20 09:30:02',
            ),
            92 => 
            array (
                'id' => 95,
                'role_id' => 11,
                'user_id' => 68,
                'created_at' => '2021-01-20 09:39:14',
                'updated_at' => '2021-01-20 09:39:14',
            ),
            93 => 
            array (
                'id' => 96,
                'role_id' => 11,
                'user_id' => 69,
                'created_at' => '2021-01-20 09:39:19',
                'updated_at' => '2021-01-20 09:39:19',
            ),
            94 => 
            array (
                'id' => 97,
                'role_id' => 11,
                'user_id' => 70,
                'created_at' => '2021-01-20 09:40:58',
                'updated_at' => '2021-01-20 09:40:58',
            ),
            95 => 
            array (
                'id' => 98,
                'role_id' => 11,
                'user_id' => 71,
                'created_at' => '2021-01-20 09:41:23',
                'updated_at' => '2021-01-20 09:41:23',
            ),
            96 => 
            array (
                'id' => 99,
                'role_id' => 11,
                'user_id' => 72,
                'created_at' => '2021-01-20 09:44:41',
                'updated_at' => '2021-01-20 09:44:41',
            ),
            97 => 
            array (
                'id' => 100,
                'role_id' => 11,
                'user_id' => 73,
                'created_at' => '2021-01-20 09:46:42',
                'updated_at' => '2021-01-20 09:46:42',
            ),
            98 => 
            array (
                'id' => 101,
                'role_id' => 11,
                'user_id' => 74,
                'created_at' => '2021-01-20 09:48:02',
                'updated_at' => '2021-01-20 09:48:02',
            ),
            99 => 
            array (
                'id' => 103,
                'role_id' => 11,
                'user_id' => 76,
                'created_at' => '2021-01-20 11:54:16',
                'updated_at' => '2021-01-20 11:54:16',
            ),
            100 => 
            array (
                'id' => 105,
                'role_id' => 2,
                'user_id' => 78,
                'created_at' => '2021-01-20 15:30:39',
                'updated_at' => '2021-01-20 15:30:39',
            ),
            101 => 
            array (
                'id' => 106,
                'role_id' => 4,
                'user_id' => 79,
                'created_at' => '2021-01-20 15:52:18',
                'updated_at' => '2021-01-20 15:52:18',
            ),
            102 => 
            array (
                'id' => 107,
                'role_id' => 4,
                'user_id' => 80,
                'created_at' => '2021-01-20 15:57:21',
                'updated_at' => '2021-01-20 15:57:21',
            ),
            103 => 
            array (
                'id' => 108,
                'role_id' => 5,
                'user_id' => 81,
                'created_at' => '2021-01-20 16:06:44',
                'updated_at' => '2021-01-20 16:06:44',
            ),
            104 => 
            array (
                'id' => 109,
                'role_id' => 8,
                'user_id' => 82,
                'created_at' => '2021-01-20 16:09:15',
                'updated_at' => '2021-01-20 16:09:15',
            ),
            105 => 
            array (
                'id' => 110,
                'role_id' => 11,
                'user_id' => 83,
                'created_at' => '2021-01-20 16:22:06',
                'updated_at' => '2021-01-20 16:22:06',
            ),
            106 => 
            array (
                'id' => 111,
                'role_id' => 11,
                'user_id' => 84,
                'created_at' => '2021-01-20 16:25:03',
                'updated_at' => '2021-01-20 16:25:03',
            ),
            107 => 
            array (
                'id' => 112,
                'role_id' => 11,
                'user_id' => 85,
                'created_at' => '2021-01-20 16:27:39',
                'updated_at' => '2021-01-20 16:27:39',
            ),
            108 => 
            array (
                'id' => 113,
                'role_id' => 11,
                'user_id' => 86,
                'created_at' => '2021-01-20 16:30:33',
                'updated_at' => '2021-01-20 16:30:33',
            ),
            109 => 
            array (
                'id' => 114,
                'role_id' => 11,
                'user_id' => 87,
                'created_at' => '2021-01-20 16:34:08',
                'updated_at' => '2021-01-20 16:34:08',
            ),
            110 => 
            array (
                'id' => 115,
                'role_id' => 9,
                'user_id' => 77,
                'created_at' => '2021-01-20 17:22:40',
                'updated_at' => '2021-01-20 17:22:40',
            ),
            111 => 
            array (
                'id' => 116,
                'role_id' => 8,
                'user_id' => 75,
                'created_at' => '2021-01-20 17:22:56',
                'updated_at' => '2021-01-20 17:22:56',
            ),
            112 => 
            array (
                'id' => 117,
                'role_id' => 8,
                'user_id' => 88,
                'created_at' => '2021-01-20 21:18:04',
                'updated_at' => '2021-01-20 21:18:04',
            ),
            113 => 
            array (
                'id' => 118,
                'role_id' => 10,
                'user_id' => 89,
                'created_at' => '2021-01-20 21:25:34',
                'updated_at' => '2021-01-20 21:25:34',
            ),
            114 => 
            array (
                'id' => 119,
                'role_id' => 11,
                'user_id' => 90,
                'created_at' => '2021-01-21 07:57:06',
                'updated_at' => '2021-01-21 07:57:06',
            ),
            115 => 
            array (
                'id' => 120,
                'role_id' => 11,
                'user_id' => 91,
                'created_at' => '2021-01-21 07:59:36',
                'updated_at' => '2021-01-21 07:59:36',
            ),
            116 => 
            array (
                'id' => 121,
                'role_id' => 11,
                'user_id' => 92,
                'created_at' => '2021-01-21 08:01:10',
                'updated_at' => '2021-01-21 08:01:10',
            ),
            117 => 
            array (
                'id' => 122,
                'role_id' => 11,
                'user_id' => 93,
                'created_at' => '2021-01-21 08:05:21',
                'updated_at' => '2021-01-21 08:05:21',
            ),
            118 => 
            array (
                'id' => 123,
                'role_id' => 11,
                'user_id' => 94,
                'created_at' => '2021-01-21 08:28:32',
                'updated_at' => '2021-01-21 08:28:32',
            ),
            119 => 
            array (
                'id' => 124,
                'role_id' => 11,
                'user_id' => 95,
                'created_at' => '2021-01-21 08:31:43',
                'updated_at' => '2021-01-21 08:31:43',
            ),
            120 => 
            array (
                'id' => 125,
                'role_id' => 11,
                'user_id' => 96,
                'created_at' => '2021-01-21 08:34:44',
                'updated_at' => '2021-01-21 08:34:44',
            ),
            121 => 
            array (
                'id' => 126,
                'role_id' => 11,
                'user_id' => 97,
                'created_at' => '2021-01-21 08:42:46',
                'updated_at' => '2021-01-21 08:42:46',
            ),
            122 => 
            array (
                'id' => 127,
                'role_id' => 11,
                'user_id' => 98,
                'created_at' => '2021-01-21 08:44:06',
                'updated_at' => '2021-01-21 08:44:06',
            ),
            123 => 
            array (
                'id' => 128,
                'role_id' => 10,
                'user_id' => 99,
                'created_at' => '2021-01-21 10:06:17',
                'updated_at' => '2021-01-21 10:06:17',
            ),
            124 => 
            array (
                'id' => 129,
                'role_id' => 8,
                'user_id' => 100,
                'created_at' => '2021-01-21 17:43:01',
                'updated_at' => '2021-01-21 17:43:01',
            ),
            125 => 
            array (
                'id' => 130,
                'role_id' => 5,
                'user_id' => 101,
                'created_at' => '2021-01-21 17:49:07',
                'updated_at' => '2021-01-21 17:49:07',
            ),
            126 => 
            array (
                'id' => 131,
                'role_id' => 12,
                'user_id' => 102,
                'created_at' => '2021-01-21 17:52:51',
                'updated_at' => '2021-01-21 17:52:51',
            ),
            127 => 
            array (
                'id' => 132,
                'role_id' => 5,
                'user_id' => 103,
                'created_at' => '2021-01-21 17:55:42',
                'updated_at' => '2021-01-21 17:55:42',
            ),
            128 => 
            array (
                'id' => 133,
                'role_id' => 11,
                'user_id' => 104,
                'created_at' => '2021-01-21 18:14:24',
                'updated_at' => '2021-01-21 18:14:24',
            ),
            129 => 
            array (
                'id' => 134,
                'role_id' => 11,
                'user_id' => 105,
                'created_at' => '2021-01-21 18:18:30',
                'updated_at' => '2021-01-21 18:18:30',
            ),
            130 => 
            array (
                'id' => 135,
                'role_id' => 11,
                'user_id' => 106,
                'created_at' => '2021-01-21 18:22:36',
                'updated_at' => '2021-01-21 18:22:36',
            ),
            131 => 
            array (
                'id' => 136,
                'role_id' => 11,
                'user_id' => 107,
                'created_at' => '2021-01-21 18:25:24',
                'updated_at' => '2021-01-21 18:25:24',
            ),
            132 => 
            array (
                'id' => 137,
                'role_id' => 11,
                'user_id' => 108,
                'created_at' => '2021-01-21 18:28:49',
                'updated_at' => '2021-01-21 18:28:49',
            ),
            133 => 
            array (
                'id' => 138,
                'role_id' => 11,
                'user_id' => 109,
                'created_at' => '2021-01-21 18:30:56',
                'updated_at' => '2021-01-21 18:30:56',
            ),
            134 => 
            array (
                'id' => 139,
                'role_id' => 11,
                'user_id' => 110,
                'created_at' => '2021-01-21 18:33:32',
                'updated_at' => '2021-01-21 18:33:32',
            ),
            135 => 
            array (
                'id' => 140,
                'role_id' => 11,
                'user_id' => 111,
                'created_at' => '2021-01-21 18:36:16',
                'updated_at' => '2021-01-21 18:36:16',
            ),
            136 => 
            array (
                'id' => 141,
                'role_id' => 11,
                'user_id' => 112,
                'created_at' => '2021-01-21 18:40:07',
                'updated_at' => '2021-01-21 18:40:07',
            ),
            137 => 
            array (
                'id' => 142,
                'role_id' => 11,
                'user_id' => 113,
                'created_at' => '2021-01-21 18:45:39',
                'updated_at' => '2021-01-21 18:45:39',
            ),
            138 => 
            array (
                'id' => 143,
                'role_id' => 11,
                'user_id' => 114,
                'created_at' => '2021-01-21 20:27:24',
                'updated_at' => '2021-01-21 20:27:24',
            ),
            139 => 
            array (
                'id' => 144,
                'role_id' => 11,
                'user_id' => 115,
                'created_at' => '2021-01-21 20:33:40',
                'updated_at' => '2021-01-21 20:33:40',
            ),
            140 => 
            array (
                'id' => 145,
                'role_id' => 11,
                'user_id' => 116,
                'created_at' => '2021-01-21 20:37:54',
                'updated_at' => '2021-01-21 20:37:54',
            ),
            141 => 
            array (
                'id' => 146,
                'role_id' => 11,
                'user_id' => 117,
                'created_at' => '2021-01-21 20:40:29',
                'updated_at' => '2021-01-21 20:40:29',
            ),
            142 => 
            array (
                'id' => 147,
                'role_id' => 11,
                'user_id' => 118,
                'created_at' => '2021-01-21 20:44:43',
                'updated_at' => '2021-01-21 20:44:43',
            ),
            143 => 
            array (
                'id' => 148,
                'role_id' => 2,
                'user_id' => 7,
                'created_at' => '2021-01-23 09:17:23',
                'updated_at' => '2021-01-23 09:17:23',
            ),
            144 => 
            array (
                'id' => 150,
                'role_id' => 8,
                'user_id' => 120,
                'created_at' => '2021-01-23 09:39:44',
                'updated_at' => '2021-01-23 09:39:44',
            ),
            145 => 
            array (
                'id' => 151,
                'role_id' => 5,
                'user_id' => 121,
                'created_at' => '2021-01-23 09:41:10',
                'updated_at' => '2021-01-23 09:41:10',
            ),
            146 => 
            array (
                'id' => 152,
                'role_id' => 4,
                'user_id' => 122,
                'created_at' => '2021-01-23 09:42:24',
                'updated_at' => '2021-01-23 09:42:24',
            ),
            147 => 
            array (
                'id' => 153,
                'role_id' => 4,
                'user_id' => 123,
                'created_at' => '2021-01-23 09:43:36',
                'updated_at' => '2021-01-23 09:43:36',
            ),
            148 => 
            array (
                'id' => 154,
                'role_id' => 2,
                'user_id' => 119,
                'created_at' => '2021-01-23 09:45:59',
                'updated_at' => '2021-01-23 09:45:59',
            ),
            149 => 
            array (
                'id' => 155,
                'role_id' => 11,
                'user_id' => 124,
                'created_at' => '2021-01-23 09:48:51',
                'updated_at' => '2021-01-23 09:48:51',
            ),
            150 => 
            array (
                'id' => 156,
                'role_id' => 11,
                'user_id' => 125,
                'created_at' => '2021-01-23 09:50:10',
                'updated_at' => '2021-01-23 09:50:10',
            ),
            151 => 
            array (
                'id' => 157,
                'role_id' => 11,
                'user_id' => 126,
                'created_at' => '2021-01-23 09:52:02',
                'updated_at' => '2021-01-23 09:52:02',
            ),
            152 => 
            array (
                'id' => 158,
                'role_id' => 11,
                'user_id' => 127,
                'created_at' => '2021-01-23 09:53:09',
                'updated_at' => '2021-01-23 09:53:09',
            ),
            153 => 
            array (
                'id' => 159,
                'role_id' => 11,
                'user_id' => 128,
                'created_at' => '2021-01-23 09:54:31',
                'updated_at' => '2021-01-23 09:54:31',
            ),
            154 => 
            array (
                'id' => 160,
                'role_id' => 11,
                'user_id' => 129,
                'created_at' => '2021-01-23 09:55:47',
                'updated_at' => '2021-01-23 09:55:47',
            ),
            155 => 
            array (
                'id' => 161,
                'role_id' => 11,
                'user_id' => 130,
                'created_at' => '2021-01-23 09:57:18',
                'updated_at' => '2021-01-23 09:57:18',
            ),
            156 => 
            array (
                'id' => 162,
                'role_id' => 11,
                'user_id' => 131,
                'created_at' => '2021-01-23 09:58:28',
                'updated_at' => '2021-01-23 09:58:28',
            ),
            157 => 
            array (
                'id' => 163,
                'role_id' => 11,
                'user_id' => 132,
                'created_at' => '2021-01-23 10:00:21',
                'updated_at' => '2021-01-23 10:00:21',
            ),
            158 => 
            array (
                'id' => 164,
                'role_id' => 11,
                'user_id' => 133,
                'created_at' => '2021-01-23 10:01:53',
                'updated_at' => '2021-01-23 10:01:53',
            ),
            159 => 
            array (
                'id' => 165,
                'role_id' => 11,
                'user_id' => 134,
                'created_at' => '2021-01-23 10:03:37',
                'updated_at' => '2021-01-23 10:03:37',
            ),
            160 => 
            array (
                'id' => 166,
                'role_id' => 1,
                'user_id' => 135,
                'created_at' => '2021-01-25 09:49:26',
                'updated_at' => '2021-01-25 09:49:26',
            ),
            161 => 
            array (
                'id' => 167,
                'role_id' => 12,
                'user_id' => 136,
                'created_at' => '2021-01-25 10:20:39',
                'updated_at' => '2021-01-25 10:20:39',
            ),
            162 => 
            array (
                'id' => 168,
                'role_id' => 11,
                'user_id' => 137,
                'created_at' => '2021-01-26 12:10:07',
                'updated_at' => '2021-01-26 12:10:07',
            ),
            163 => 
            array (
                'id' => 169,
                'role_id' => 8,
                'user_id' => 47,
                'created_at' => '2021-01-26 12:20:07',
                'updated_at' => '2021-01-26 12:20:07',
            ),
            164 => 
            array (
                'id' => 170,
                'role_id' => 2,
                'user_id' => 10,
                'created_at' => '2021-01-26 14:37:20',
                'updated_at' => '2021-01-26 14:37:20',
            ),
            165 => 
            array (
                'id' => 171,
                'role_id' => 10,
                'user_id' => 46,
                'created_at' => '2021-01-26 17:06:52',
                'updated_at' => '2021-01-26 17:06:52',
            ),
            166 => 
            array (
                'id' => 172,
                'role_id' => 11,
                'user_id' => 138,
                'created_at' => '2021-01-29 10:32:17',
                'updated_at' => '2021-01-29 10:32:17',
            ),
            167 => 
            array (
                'id' => 173,
                'role_id' => 11,
                'user_id' => 139,
                'created_at' => '2021-01-29 10:39:26',
                'updated_at' => '2021-01-29 10:39:26',
            ),
            168 => 
            array (
                'id' => 174,
                'role_id' => 11,
                'user_id' => 78,
                'created_at' => '2021-01-30 12:13:20',
                'updated_at' => '2021-01-30 12:13:20',
            ),
            169 => 
            array (
                'id' => 175,
                'role_id' => 3,
                'user_id' => 140,
                'created_at' => '2021-02-06 10:56:23',
                'updated_at' => '2021-02-06 10:56:23',
            ),
            170 => 
            array (
                'id' => 176,
                'role_id' => 3,
                'user_id' => 141,
                'created_at' => '2021-02-06 11:35:22',
                'updated_at' => '2021-02-06 11:35:22',
            ),
            171 => 
            array (
                'id' => 177,
                'role_id' => 3,
                'user_id' => 142,
                'created_at' => '2021-02-06 12:02:59',
                'updated_at' => '2021-02-06 12:02:59',
            ),
            172 => 
            array (
                'id' => 178,
                'role_id' => 3,
                'user_id' => 143,
                'created_at' => '2021-02-06 12:07:12',
                'updated_at' => '2021-02-06 12:07:12',
            ),
            173 => 
            array (
                'id' => 179,
                'role_id' => 2,
                'user_id' => 77,
                'created_at' => '2021-02-12 14:50:38',
                'updated_at' => '2021-02-12 14:50:38',
            ),
            174 => 
            array (
                'id' => 180,
                'role_id' => 11,
                'user_id' => 144,
                'created_at' => '2021-02-25 10:30:24',
                'updated_at' => '2021-02-25 10:30:24',
            ),
            175 => 
            array (
                'id' => 181,
                'role_id' => 11,
                'user_id' => 145,
                'created_at' => '2021-02-27 11:51:44',
                'updated_at' => '2021-02-27 11:51:44',
            ),
            176 => 
            array (
                'id' => 184,
                'role_id' => 3,
                'user_id' => 20,
                'created_at' => '2021-03-01 15:12:47',
                'updated_at' => '2021-03-01 15:12:47',
            ),
            177 => 
            array (
                'id' => 185,
                'role_id' => 3,
                'user_id' => 146,
                'created_at' => '2021-03-01 17:36:44',
                'updated_at' => '2021-03-01 17:36:44',
            ),
            178 => 
            array (
                'id' => 186,
                'role_id' => 3,
                'user_id' => 147,
                'created_at' => '2021-03-01 17:47:48',
                'updated_at' => '2021-03-01 17:47:48',
            ),
            179 => 
            array (
                'id' => 187,
                'role_id' => 3,
                'user_id' => 148,
                'created_at' => '2021-03-01 18:30:07',
                'updated_at' => '2021-03-01 18:30:07',
            ),
        ));
        
        
    }
}