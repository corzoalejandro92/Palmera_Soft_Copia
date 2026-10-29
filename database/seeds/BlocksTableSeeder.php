<?php

use Illuminate\Database\Seeder;
use App\Block;

class BlocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = [
            "key-words" => ['user_headquarter_city','date','customer','customer_address'],
            "html" => '<p>{{user_headquarter_city}},&nbsp;{{date}}</p>

            <p>Se&ntilde;ores&nbsp;<br />
            {{ customer }}&nbsp;<br />
            Ciudad&nbsp;</p>
            
            <p>&nbsp;</p>
            
            <p style="text-align:center"><strong>DIAGN&Oacute;STICO CONTROL DE PLAGAS PARA LAS INSTALACIONES DE {{customer}}</strong>&nbsp;</p>
            
            <p>REVISI&Oacute;N E INVESTIGACI&Oacute;N GENERAL DE LAS INSTALACIONES LOCATIVAS UBICADAS&nbsp;EN {{customer_address}} QUE NOS PERMITA DETERMINAR QU&Eacute; PASOS DEBEMOS SEGUIR PARA OBTENER LOS RESULTADOS ESPERADOS (IMPEDIR&nbsp;LA CONTAMINACI&Oacute;N DEL&nbsp;PRODUCTO POR&nbsp;LA PRESENCIA DE&nbsp;PLAGAS).&nbsp;</p>
            
            <p>LA IMPORTANCIA DE&nbsp;DIFERENCIAR LAS PLAGAS CONSISTE EN ESTABLECER DIVERSOS MECANISMOS DE PREVENCI&Oacute;N Y CONTROL, PORQUE CADA UNA DE ELLAS TIENE DIFERENTES NIVELES DE COMPORTAMIENTO SOCIAL Y BIOL&Oacute;GICO.&nbsp;</p>
            
            <p><strong>NOTA:</strong>&nbsp;Este diagn&oacute;stico es&nbsp;exclusivo&nbsp;de&nbsp;{{customer}} especial para ser presentado ante las entidades sanitarias que lo requieran, est&aacute;&nbsp;prohibida&nbsp;la copia para otras empresas de&nbsp;Control de Plagas y/o&nbsp;Fumigaci&oacute;n.&nbsp;</p>'];
        Block::create([
            "name"          => "INTRODUCCIÓN ESTÁNDAR",
            "slug"          => "diagnostic",
            "description"   => "Muestra un saludo y una entrada con la información del cliente de la cotización.",
            "options"       => json_encode($options)
        ]); 
    }
}
