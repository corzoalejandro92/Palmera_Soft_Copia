<?php

use Illuminate\Database\Seeder;
use App\QuoteStatus;
use App\CrmTrackingTypes;
use App\Periodicity;
use App\Block;

class QuotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuoteStatus::create([
            "name" => 'Pendiente'
        ]);
        QuoteStatus::create([
            "name" => 'Aprobada'
        ]);
        QuoteStatus::create([
            "name" => 'Rechazada'
        ]);
        QuoteStatus::create([
            "name" => 'Cronograma Generado'
        ]);
        QuoteStatus::create([
            "name" => 'Programación Generada'
        ]);
        QuoteStatus::create([
            "name" => 'Facturada'
        ]);

        Periodicity::create([
            "name" => "Única vez",
            "value" => 0,
            "unit" => "never",
            "weeks" => 1,
            "deleted_at" => \Carbon\Carbon::now()->parse('Y-m-d H:i:s'),
        ]);

        Periodicity::create([
            "name" => "Diario",
            "value" => 1,
            "unit" => "day",
            "weeks" => 1,
            "deleted_at" => \Carbon\Carbon::now()->parse('Y-m-d H:i:s'),
        ]);

        Periodicity::create([
            "name" => "Semanal",
            "value" => 1,
            "unit" => "month",
            "weeks" => 5
        ]);

        Periodicity::create([
            "name" => "Quincenal",
            "value" => 1,
            "unit" => "month",
            "weeks" => 2,
            "deleted_at" => \Carbon\Carbon::now()->parse('Y-m-d H:i:s'),
        ]);

        Periodicity::create([
            "name" => "Mensual",
            "value" => 1,
            "unit" => "month",
            "weeks" => 4
        ]);

        Periodicity::create([
            "name"  => "Bimestral",
            "value" => 1,
            "unit"  => "month",
            "weeks" => 8
        ]);
        Periodicity::create([
            "name" => "Trimestral",
            "value" => 1,
            "unit" => "month",
            "weeks" => 12
        ]);
        Periodicity::create([
            "name" => "Cuatrimestal",
            "value" => 1,
            "unit" => "month",
            "weeks" => 16
        ]);
        Periodicity::create([
            "name" => "Quintimestral",
            "value" => 1,
            "unit" => "month",
            "weeks" => 20
        ]);
        Periodicity::create([
            "name" => "Semestral",
            "value" => 1,
            "unit" => "month",
            "weeks" => 24
        ]);
        Periodicity::create([
            "name" => "Anual",
            "value" => 1,
            "unit" => "month",
            "weeks" => 52
        ]);
        Periodicity::create([
            "name" => "Bienal",
            "value" => 1,
            "unit" => "month",
            "weeks" => 104
        ]);
        Periodicity::create([
            "name" => "Sin especificar",
            "value" => 0,
            "unit" => "never",
            "weeks" => 1
        ]);





        $options = [
            "key-words" => ['crm-name','crm-office-address'],
            "html" => '<p><span style="font-family:Calibri"><strong><span style="font-size:11,0000pt"><span style="font-family:Arial"><strong>Se&ntilde;ores</strong></span></span></strong></span></p>

        <p><span style="font-family:Arial"><strong>{{crm-name}}</strong></span></p>
        
        <p><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>Att. xxxxxx.xxxxxxxx</strong></span></span></strong></span></p>
        
        <p><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>{{crm-office-address}}</strong></span></span></strong></span></p>
        '];
        Block::create([
            "name" => "INFORMACIÓN DEL CLIENTE",
            "slug" => "quote",
            "description" => "Muestra un saludo y una entrada con la información del cliente de la cotización.",
            "options" => json_encode($options)
        ]);  




        $options = [
            "key-words" => [],
            "html" => '<p>&nbsp;</p>

            <p>&nbsp;</p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>NUESTRA COMPA&Ntilde;IA:</strong></span></span></strong></span></p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="color:#00b050"><strong>PALMERA JUNIOR</strong></span></span></span></strong><strong>&nbsp;</strong><span style="font-size:12,0000pt"><span style="font-family:Arial">es una empresa de car&aacute;cter Nacional especializada en el Manejo Integrado de Plagas mediante la implementaci&oacute;n de m&eacute;todos revolucionarios de control, que van de la mano con la protecci&oacute;n del medio ambiente, la salud de las personas, la inocuidad de los alimentos y principalmente, la satisfacci&oacute;n de nuestros clientes; utilizando en el desarrollo de sus procesos t&eacute;cnicas de control limpias con tecnolog&iacute;a de punta. </span></span></span></p>
            
            <p style="text-align:justify">&nbsp;</p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>COBERTURA NACIONAL</strong></span></span></strong></span></p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Nuestra compa&ntilde;&iacute;a tiene la capacidad t&eacute;cnica y log&iacute;stica para llegar a donde Nuestros clientes lo requieran, sin importar la ubicaci&oacute;n geogr&aacute;fica, el momento del a&ntilde;o o la hora del d&iacute;a, contando siempre con sedes propias sin la necesidad de acudir a intermediarios o subcontratistas.</span></span></span></p>
            
            <p style="text-align:justify">&nbsp;</p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>RESPALDO</strong></span></span></strong></span></p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Contamos con una trayectoria de m&aacute;s de 23 a&ntilde;os de experiencia, lo cual nos permite tener en la actualidad un </span></span><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>pool </em></span></span></em><span style="font-size:12,0000pt"><span style="font-family:Arial">de clientes de las empresas m&aacute;s reconocidas a nivel nacional y con amplia participaci&oacute;n en el mercado internacional, sumando m&aacute;s de 1000 empresas en Colombia solo en Empresas de alimentos, demostrando Nuestra experiencia en BPM, HACCP y AIB. Adicionalmente, tenemos implementado en nuestros procesos los sistemas de gesti&oacute;n bajo las normas internacionales ISO 9001:2015, ISO 14001:2015, e ISO 45001:2018 lo cual nos permite asegurar la calidad de nuestros servicios siendo amigables con el medio ambiente y al mismo tiempo seguros con todo el personal.</span></span></span></p>'
        ];
        Block::create([
            "name" => "NUESTRA COMPAÑÍA",
            "slug" => "quote",
            "description" => "Muestra un resumen breve de la operación de palmera junior con o sin información de cobertura nacional y respaldo.",
            "options" =>json_encode($options)
        ]);


        $options = [
            "key-words" => [],
            "html" => '
            <p style="text-align:justify">&nbsp;</p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>PORTAFOLIO DE SERVICIOS</strong></span></span></strong></span></p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Nuestra capacidad t&eacute;cnica &ndash; cient&iacute;fica nos permite ofrecer los siguientes servicios:</span></span></span></p>
            
            <ul>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Control general de plagas utilizando sistemas de control SIN TOXICOS</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Desinsectaci&oacute;n general (control de insectos utilizando productos cuya toxicidad oscila de baja a nula)</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Desratizaci&oacute;n mediante el uso de mecanismos de control NO TOXICOS</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Control de plagas en granos almacenados</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Desinfecci&oacute;n de ambientes</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Tratamiento contra insectos xil&oacute;fagos (plagas de la madera)</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Control de ofidios y quir&oacute;pteros (serpientes y murci&eacute;lagos)</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Alistamiento de granjas av&iacute;colas y porcicolas. </span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Control de aves mediante sistemas de exclusi&oacute;n</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Control de panales de avispas y abejas</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Recarga y mantenimiento de extintores</span></span></span></li>
            </ul>
            <p>&nbsp;</p>
            '
        ];

        Block::create([
            "name" => "PORTAFOLIO DE SERVICIOS",
            "slug" => "quote",
            "description" => "Muestra un contenido de los servicios disponibles dentro del sistema.",
            "options" =>json_encode($options)
        ]);  




        $options = [
            "key-words" => [],
            "html" => '
            <p>&nbsp;</p>

            <p style="text-align:justify">&nbsp;</p>

            <p><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>SOPORTE DOCUMENTAL</strong></span></span></strong></span></p>

            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">La documentaci&oacute;n que respalda la implementaci&oacute;n del Programa de Manejo Integrado de Plagas se soporta y fundamenta en el Decreto 3075 de 1997, Decreto 60 de 2002 y las Normas Internacionales AIB, BRC e IFS.</span></span></span></p>

            <p>&nbsp;</p>

            <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">De esta manera al implementar el programa con nuestra compa&ntilde;&iacute;a les ofrecemos: </span></span></span></p>

            <ul>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Diagn&oacute;stico general para determinar debilidades y amenazas bajo el concepto ICE</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Cronograma de actividades con rotaci&oacute;n de sustancias y dosificaciones. </span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Hojas de seguridad y fichas t&eacute;cnicas de todos los productos.</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Protocolos y/o procedimientos de operaci&oacute;n.</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Plano de ubicaci&oacute;n de mecanismos.</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Certificado de la Secretaria de Salud local.</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Certificado de control de plagas</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Capacitaci&oacute;n a todo el personal de la Planta </span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Asesor&iacute;a permanente en lo referente al program</span></span><span style="font-size:12,0000pt"><span style="font-family:Arial">a</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Respuesta oportuna a sus inquietudes y necesidades</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Reporte por cada visita.</span></span></span></li>
            </ul><p>&nbsp;</p>'
        ];
        Block::create([
            "name" => "SOPORTE DOCUMENTAL",
            "slug" => "quote",
            "description" => "Muestra la documentación que respalda la implementación del MIP.",
            "options" => json_encode($options)
        ]); 







        $options = [
            "key-words" => [],
            "html" => '<p>&nbsp;</p>

            <p><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>CUMPLIMIENTO LEGAL</strong></span></span></strong></span></p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">En</span></span><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="color:#00b050"><strong>&nbsp;PALMERA JUNIOR</strong></span></span></span></strong><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;damos cumplimiento a la legislaci&oacute;n que regula al sector de control de plagas, como el Decreto 1843 de 1991, lo cual nos permite contar con el Concepto T&eacute;cnico Sanitario de las Secretarias de Salud en todas nuestras sedes.</span></span></span></p>
            '
        ];
        Block::create([
            "name" => "CUMPLIMIENTO LEGAL",
            "slug" => "quote",
            "description" => "Muestra una breve descripción acerca del cumplimiento legal.",
            "options" => json_encode($options),
        ]); 






        $options = [
            "key-words" => ['quote-service-individual','quote-service-name','quote-service-description','quote-service-mechanisms','quote-service-mechanism-picture','quote-service-mechanism-name','quote-service-mechanism-description'],
            "html" => '<p>&nbsp;</p>

            
            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Descripci&oacute;n del servicio:<br />
            <br />
            <span style="font-family:Courier New,Courier,monospace"><strong><span style="color:#e74c3c">{{start-quote-service-individual}}</span></strong></span></span></span></span></p>
            
            <ul>
                <li style="text-align:justify"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>{{quote-service-name}}:</strong></span></span></strong><span style="font-size:12,0000pt"><span style="font-family:Arial"> {{quote-service-description}}</span></span></span><br />
                <br />
                <span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Algunos mecanismos son:</span></span></span><br />
                <span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="font-family:Courier New,Courier,monospace"><strong><span style="color:#e74c3c">{{start-quote-service-mechanisms}}</span></strong></span></span></span></span><br />
                &nbsp;
                <ul>
                    <li><span style="font-family:Calibri"><strong><span style="font-family:Arial"><strong>{{quote-service-mechanism-picture}} {{quote-service-mechanism-name}}:</strong></span></strong></span><br />
                    {{quote-service-mechanism-description}}</li>
                </ul>
                </li>
            </ul>
            
            <p style="margin-left:40px"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="font-family:Courier New,Courier,monospace"><strong><span style="color:#e74c3c">{{end-quote-service-mechanisms}}</span></strong></span></span></span></span></p>
            
            <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="font-family:Courier New,Courier,monospace"><strong><span style="color:#e74c3c">{{end-quote-service-individual}}</span></strong></span></span></span></span></p>
            
            <p>&nbsp;</p>'
        ];
        Block::create([
            "name" => "SERVICIO INDIVIDUAL",
            "slug" => "quote",
            "description" => "Muestra un titulo independiente y una descrición completa del servicio, con ajustes opcionales de anexos técnicos.",
            "options" =>json_encode($options)
        ]);



        $options = [
            "key-words" => ['quote-service-table'],
            "html" => '<p>&nbsp;</p>

            <p><span style="font-family:Arial">Tabla de servicios:</span></p>
            
            <p style="text-align:justify"><span style="font-family:Arial">{{quote-service-table}}</span></p>
            
            <p>&nbsp;</p>'
        ];
        Block::create([
            "name" => "SERVICIOS EN TABLAS",
            "slug" => "quote",
            "description" => "Muestra todos los servicios de la cotización en una tabla, sin descripción ni anexos técnicos.",
            "options" =>json_encode($options)
        ]); 




        $options = [
            "key-words" => ['quote-services-proposal','total-quote-service-price-value','total-quote-service-price-text','quote-service-controls'],
            "html" => '<p>&nbsp;</p>

            <p style="text-align:justify"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>PROPUESTA ECONOMICA </strong></span></span></strong></span></p>
            
            <p style="text-align:justify"><span style="font-family:Courier New,Courier,monospace"><span style="color:#e74c3c"><strong><strong>{{start-quote-services-proposal}}</strong></strong></span></span></p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">El servicio tiene</span></span></span><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">&nbsp;un valor de {{total-quote-service-price-text}}</span></span></span><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">&nbsp;</span></span></span><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong><span style="background-color:null">(${{total-quote-service-price-value}}) MAS IVA.</span></strong></span></span></strong></span></p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">El valor anterior incluye:</span></span></span></span></p>
            
            <p style="margin-left:40px; text-align:justify"><span style="font-family:Courier New,Courier,monospace"><span style="color:#e74c3c"><strong>{{start-quote-service-controls}}</strong></span></span></p>
            
            <p style="margin-left:40px; text-align:justify">Un control de {{control-name}}: {{control-description}}</p>
            
            <p style="margin-left:40px; text-align:justify"><span style="font-family:Courier New,Courier,monospace"><span style="color:#e74c3c"><strong>{{end-quote-service-controls}}</strong></span></span></p>
            
            <p style="text-align:justify"><span style="font-family:Courier New,Courier,monospace"><span style="color:#e74c3c"><strong><strong>{{start-quote-services-proposal}}</strong></strong></span></span></p>
            
            <p>&nbsp;</p>'
        ];
        Block::create([
            "name" => "PROPUESTA ECONÓMICA",
            "slug" => "quote",
            "description" => "Muestra los servicios de la cotización anteponiendo el total de estos y luego se detallan los controles.",
            "options" =>json_encode($options)
        ]); 




        $options = [
            "key-words" => ['quote-service-simple', 'service-name','control-name','service-area','total-quote-services-value'],
            "html" => '<p>&nbsp;</p>

            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">Nos permitimos cotizar:</span></span></span></span></p>
            
            <p style="text-align:justify"><span style="color:#e74c3c"><strong><span style="font-family:Calibri"><span style="font-family:Arial">{{start-quote-service-simple}}</span></span></strong></span></p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">{{service-name}} para el control de {{control-name}} con un alcance {{service-area}}</span></span></span></span></p>
            
            <p style="text-align:justify"><span style="color:#e74c3c"><strong><span style="font-family:Calibri"><span style="font-family:Arial">{{end-quote-service-simple}}</span></span></strong></span></p>
            
            <p style="text-align:justify">&nbsp;</p>
            
            <p><span style="font-family:Calibri"><strong><u><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong><u>COSTO</u></strong></span></span></u><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>: $ {{total-quote-services-value}}&nbsp;+ IVA</span></strong></span></span></strong></span></p>
            '
        ];
        Block::create([
            "name" => "SERVICIO SIMPLE",
            "slug" => "quote",
            "description" => "Muestra la información mas esencial de cada servicio con detalle de areas de coberturas y luego un total general.",
            "options" =>json_encode($options)
        ]);




        $options = [
            "key-words" => ['quote-service-simple', 'service-name','control-name','service-area','total-quote-services-value'],
            "html" => '<p style="text-align:justify">&nbsp;</p>

            <p style="text-align:justify"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="color:#000000"><strong>VALOR AGREGADO:</strong></span></span></span></strong></span></p>
            
            <p style="text-align:justify">&nbsp;</p>
            
            <ul>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Capacitaciones al personal, cuando se requieran, no solamente en el programa MIP, sino tambi&eacute;n en temas complementarios como BPM, HACCP y AIB, generando los registros de asistencia y evaluaci&oacute;n correspondientes, y exigidos en el programa de Capacitaciones.</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Asignaci&oacute;n de t&eacute;cnicos exclusivos, es decir que ser&iacute;an las &uacute;nicas personas que realizar&iacute;an los procesos del Manejo Integrado de Plagas programados. Este hecho contribuye a un mejor seguimiento, control y conocimiento del programa que se desarrollar&iacute;a.</span></span></span></li>
                <li style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Personal especializado y capacitado para trabajo en alturas.</span></span></span></li>
            </ul>
            '
        ];
        Block::create([
            "name" => "VALOR AGREGADO",
            "slug" => "quote",
            "description" => "Muestran información de valor agregado en la cotización.",
            "options" =>json_encode($options)
        ]); 




        $options = [
            "key-words" => [],
            "html" => '<p>&nbsp;</p>

            <p style="text-align:justify"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong><span style="background-color:null">SERVICIOS CON ADICIONALES</span></strong></span></span></strong></span></p>

            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Los servicios adicionales se prestan en caso de ser requeridos por </span></span><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong><span style="background-color:null">XXXXXX</span></strong></span></span></strong><strong>&nbsp;</strong><span style="font-size:12,0000pt"><span style="font-family:Arial">y tienen los siguientes costos:</span></span></span></p>
            
            <table cellspacing="0" class="Table" style="border-collapse:collapse; border:1,0000pt solid #000000; font-family:&quot;Times New Roman&quot;; width:441,4000pt">
                <tbody>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>SERVICIO</strong></span></span></strong></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>COSTO $</strong></span></span></strong></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>FRECUENCIA</strong></span></span></strong></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Retiro de panales</span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">$20.400</span></span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="margin-right:-26,6500pt"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Cada vez que se presente&nbsp;</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Retiro de gatos</span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">$56.700</span></span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="margin-right:-26,6500pt"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Cada vez que se presente&nbsp;</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Retiro de faras</span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">$56.700</span></span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="margin-right:-26,6500pt"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Cada vez que se presente&nbsp;</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Alquiler de jaula para gatos y faras</span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">$56.700</span></span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Mensual</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Control de furgones peque&ntilde;os y medianos&nbsp;</span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">$18.000 c/u</span></span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Semestral</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Control de furgones grandes</span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">$30.000 c/u</span></span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Semestral</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Venta de l&aacute;minas para l&aacute;mpara 925</span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">$249.500 caja * 25</span></span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="margin-right:-26,6500pt"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Cada vez que se presente&nbsp;</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Venta de tubo de repuesto 925</span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">$41.210</span></span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="margin-right:-26,6500pt"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Cada vez que se presente&nbsp;</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Venta de tubo de repuesto electrocutora</span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">$17.000</span></span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="margin-right:-26,6500pt"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Cada vez que se presente&nbsp;</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Murci&eacute;lagos</span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">&nbsp;Depende de cada caso&nbsp;</span></span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="margin-right:-26,6500pt"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Cada vez que se presente&nbsp;</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:222,2000pt">
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Palomas</span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:85,3000pt">
                        <p style="text-align:center"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="background-color:null">&nbsp;Depende de cada caso&nbsp;</span></span></span></span></p>
                        </td>
                        <td style="vertical-align:top; width:133,9000pt">
                        <p style="margin-right:-26,6500pt"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;Cada vez que se presente&nbsp;</span></span></span></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Todos los valores son m&aacute;s IVA con excepci&oacute;n de las l&aacute;minas para l&aacute;mparas las cuales son exentas de IVA.</span></span></span></p>'
        ];
        Block::create([
            "name" => "SERVICIOS ADICIONALES",
            "slug" => "quote",
            "description" => "Muestran una tabla definida de servicios adicionales en caso de ser requeridos",
            "options" => json_encode($options)
        ]); 

        $options = [
            "key-words" => [],
            "html" => '<p>&nbsp;</p>



            <p style="text-align:justify"><span style="font-family:Calibri"><strong><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong><em>FORMA Y TIEMPO DE PAGO</em></strong></span></span></em></strong><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>: </em></span></span></em></span></p>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Se realizar&aacute; en efectivo o traslado de cuenta a cuenta o con cheque cruzado, y el tiempo de pago ser&aacute; 30 d&iacute;as despu&eacute;s de la fecha de radicaci&oacute;n de la factura.</span></span></span></p>
            
            <p>&nbsp;</p>'
        ];
        Block::create([
            "name" => "FORMA Y TIEMPO DE PAGO",
            "slug" => "quote",
            "description" => "Texto predefinido que muestra información de forma de pago y tiempo de pago",
            "options" => json_encode($options)
        ]); 




        $options = [
            "key-words" => [],
            "html" => '<p>&nbsp;</p>

            <p><strong>INFORMACI&Oacute;N DE VIGENCIA</strong></p>
            
            <p><span style="font-family:Calibri"><strong><span style="font-size:11,0000pt"><span style="font-family:Arial"><strong>Validez de la oferta:</strong></span></span></strong><span style="font-size:11,0000pt"><span style="font-family:Arial">&nbsp;{{quote-expired-difference}}. </span></span></span><br />
            <span style="font-family:Calibri"><strong><span style="font-size:11,0000pt"><span style="font-family:Arial"><strong>Disponibilidad y Tiempo de programaci&oacute;n:</strong></span></span></strong><span style="font-size:11,0000pt"><span style="font-family:Arial">&nbsp;3 a 5 d&iacute;as despu&eacute;s de aprobada.</span></span></span></p>
            '
        ];
        Block::create([
            "name" => "INFORMACIÓN DE VIGENCIA",
            "slug" => "quote",
            "description" => "Muestra la vigencia establecida en la cotización.",
            "options" => json_encode($options)
        ]); 




        $options = [
            "key-words" => [],
            "html" => '<p style="text-align:justify">&nbsp;</p>

            <p style="text-align:justify"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>20 RAZONES PARA ESCOGER </strong></span></span></strong><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="color:#00b050"><strong>PALMERA JUNIOR</strong></span></span></span></strong><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>&nbsp;CONTROL DE PLAGAS</strong></span></span></strong></span></p>
            
            <table border="1" cellpadding="1" cellspacing="1" style="width:100%">
                <tbody>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="color:#00b050"><strong>PALMERA JUNIOR CONTROL DE PLAGAS</strong></span></span></span></strong></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>OTRAS EMPRESAS</strong></span></span></strong></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:262,2500pt">
                        <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Manejo de control de plagas, con implementaci&oacute;n de mecanismos </span></span><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="color:#00b050"><strong>SIN TOXICOS</strong></span></span></span></strong><span style="font-size:12,0000pt"><span style="font-family:Arial">, para contribuir en el cuidado del medio ambiente</span></span></span></p>
                        </td>
                        <td>
                        <p style="text-align:justify"><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Fumigan con productos qu&iacute;micos</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Contamos con m&aacute;s de 80 mecanismos no t&oacute;xicos de &uacute;ltima tecnolog&iacute;a</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Sus sistemas son solamente pegamentos, cajas porta cebo y qu&iacute;micos.</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">CERTIFICACION DE CALIDAD Y AMBIENTAL BAJO LA NORMA ISO 9001:2015; 14001:2004 y 45001:2018 &nbsp;por parte del ICONTEC.</span></span></span></p>
                        </td>
                        <td>
                        <p style="text-align:justify"><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Certificados con ISO 9001 algunas empresas.</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:47,8500pt; vertical-align:top; width:262,2500pt">
                        <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">No cobramos mecanismo de control instalado, si se debe invertir en La empresa en instalar m&aacute;s de 4 o 5 millones de pesos en mecanismo de control no t&oacute;xicos (Se &nbsp;hacen seg&uacute;n el &aacute;rea).</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Instalan cajas porta cebos y &nbsp;l&aacute;mparas, estos mecanismo los cobran &nbsp;y el pago debe ser de contado (Seg&uacute;n el &aacute;rea de la instalaci&oacute;n colocan un numero de cajas portacebos).</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Personal calificado como Ingenieros de alimentos, ambientales. industriales, microbi&oacute;logos, &nbsp;todos al servicio de ustedes.</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Algunos son agr&oacute;nomos o emp&iacute;ricos.</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Utilizamos los productos que ofrecemos, los presentamos sellados y nuevos, garantizando que s&iacute;, son adquiridos por nuestra empresa.</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Reenvasan los productos en recipientes identificados con el nombre de productos que nunca utilizan.</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Enfatizamos que se debe llevar a cabo controles preventivos y cambiando la cultura de utilizar productos qu&iacute;micos a no t&oacute;xicos.</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Utilizan solo productos qu&iacute;micos y algunas veces pegamentos en trozos de cart&oacute;n, sin ficha t&eacute;cnica.</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Brindamos capacitaci&oacute;n a los empleados de la empresa para darles a conocer el programa MIP Manejo Integral de Plagas por medio de nuestro personal profesional calificado, estas capacitaciones no se cobran d&aacute;ndole un valor agregado al programa MIP.</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Capacitaciones emp&iacute;ricas.</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Sedes propias a nivel nacional sin la necesidad de acudir a intermediarios o subcontratistas.</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Locales y subcontratan, no mantienen lo estandarizado.</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Damos garant&iacute;a de nuestros servicios</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Solo garantizan siempre y cuando la empresa lleve a cabo las adecuaciones de las instalaciones.</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Utilizamos productos naturales</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Productos qu&iacute;micos contaminantes con la excusa que son de baja toxicidad.</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Protegemos nuestros mecanismos de control instalados bajo la norma BPM y desarrollamos &nbsp;controles etol&oacute;gicos que se basa en conocer la plaga que se va a controlar. </span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Descuidos de los sistemas de control por falta de visitas, las plagas aprenden a identificar este peligro y evitan caer en ellas. Y como las cajas cebaderas son del cliente debe comprar nuevas</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; width:262,2500pt">
                        <p style="text-align:justify"><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Los mecanismos de control son cambiados dado por el mismo uso o &nbsp;por el roedor, sin ning&uacute;n costo.</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Si se da&ntilde;an por el mismo uso o &nbsp;por el roedor el cliente asume el costo.</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Las capturas son retiradas por los mismo T&eacute;cnicos de nuestra empresa y se env&iacute;a a incineraci&oacute;n. </span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Son arrojados a la basura contribuyendo a la contaminaci&oacute;n del medio ambiente.</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Nuestra bodega cuenta con filtros de vertimiento de aguas en el lavado de equipos.</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>Lavan los equipos vertiendo los residuos a las aguas residuales sin tener un control y conciencia en contribuir al cuidado del medio ambiente.</em></span></span></em><strong><em>&nbsp;</em></strong></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">El precio es acorde a la calidad</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><em><span style="font-size:12,0000pt"><span style="font-family:Arial"><em>El precio es alto, con respecto al servicio</em></span></span></em></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Los servicios que ofrecemos los mantenemos, garantizando que el programa de visitas se lleven a cabo.</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Incumplen y no dan un programa de visitas, los refuerzos de fumigaci&oacute;n o para roedores los cobran.</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Se entrega un informe basado en un diagn&oacute;stico realizado a las instalaciones con equipos de &uacute;ltima tecnolog&iacute;a.</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Informaci&oacute;n basada en una visita ocular de las instalaciones.</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Somos especialistas en empresas de alimentos.</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Hacen lo mismo en todo tipo de Industrias no son especializados</span></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Tenemos el concepto sanitario &nbsp;expedido por la </span></span><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>SECRETARIA DE SALUD</strong></span></span></strong><span style="font-size:12,0000pt"><span style="font-family:Arial">&nbsp;local de cada sede.</span></span></span></p>
                        </td>
                        <td>
                        <p><span style="font-family:Calibri"><span style="font-size:12,0000pt"><span style="font-family:Arial">Solo maneja el concepto sanitario local y en otras regiones del pa&iacute;s no lo tienen.</span></span></span></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <p style="text-align:justify"><span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><strong>Lo invitamos a que conozca nuestra p&aacute;gina web</strong></span></span></strong></span><br />
            <span style="font-family:Calibri"><strong><span style="font-size:12,0000pt"><span style="font-family:Arial"><span style="color:#00b050"><strong>www.palmerajunior.com</strong></span></span></span></strong></span></p>
            '
        ];
        Block::create([
            "name" => "20 RAZONES PARA ELEGIRNOS",
            "slug" => "quote",
            "description" => "Muestra una tabla comparativa de los servicios de Palmera Junior y otras empresas.",
            "options" => json_encode($options)
        ]); 




        $options = [
            "key-words" => ['user-firm','user-name','headquarter-name'],
            "html" => '<p>&nbsp;</p>

            <p><span style="font-family:Calibri"><strong><span style="font-size:11,0000pt"><span style="font-family:Arial"><strong>Lo invitamos a que conozca nuestra p&aacute;gina web </strong></span></span></strong><strong><span style="font-size:11,0000pt"><span style="font-family:Arial"><span style="color:#00b050"><strong>www.palmerajunior.com</strong></span></span></span></strong></span></p>
            
            <p>&nbsp;</p>
            
            <p><span style="font-family:Calibri"><span style="font-size:11,0000pt"><span style="font-family:Arial">A espera de una </span></span><span style="font-size:11,0000pt"><span style="font-family:Arial">pronta y positiva respuesta.</span></span></span></p>
            
            <p><span style="font-family:Calibri"><span style="font-size:11,0000pt"><span style="font-family:Arial">Cordialmente,</span></span></span></p>
            
            <p><br />
            <span style="font-family:Calibri"><strong><span style="font-size:11,0000pt"><span style="font-family:Arial"><strong>{{user-firm}}<br />
            {{user-name}}</strong></span></span></strong></span><br />
            <span style="font-family:Calibri"><strong><em><span style="font-size:11,0000pt"><span style="font-family:Arial"><strong><em>Gerente comercial {{hadquarer-name}}</em></strong></span></span></em></strong></span><br />
            <span style="font-family:Calibri"><strong><em><span style="font-size:11,0000pt"><span style="font-family:Arial"><strong><em>3XXXXXXX</em></strong></span></span></em></strong></span></p>
            <span style="font-family:Calibri"><strong><span style="font-size:11,0000pt"><span style="font-family:Arial"><strong>Disponibilidad y Tiempo de programaci&oacute;n:</strong></span></span></strong><span style="font-size:11,0000pt"><span style="font-family:Arial">&nbsp;3 a 5 d&iacute;as despu&eacute;s de aprobada.</span></span></span></p>
            '
        ];
        Block::create([
            "name" => "FIRMA",
            "slug" => "quote",
            "description" => "Muestra una firma digital, (Con o sin información de los servicios a realizar).",
            "options" => json_encode($options)
        ]); 

        $options = [
            "key-words" => ['date','customer','user_headquarter_city','customer_address'],
            "html" => '<p>Bucaramanga,&nbsp;{{date}}&nbsp;</p>

            <p>Se&ntilde;ores&nbsp;<br />
            <strong>{{customer}}</strong><br />
            {{user_headquarter_city}}</p>
            
            <p>&nbsp;</p>
            
            <p style="text-align:center"><strong>DIAGN&Oacute;STICO CONTROL DE PLAGAS PARA LAS INSTALACIONES DE&nbsp;{{customer}}&nbsp;</strong></p>
            
            <p>REVISI&Oacute;N E INVESTIGACI&Oacute;N GENERAL DE LAS INSTALACIONES LOCATIVAS UBICADAS&nbsp;EN&nbsp;{{customer_address}}&nbsp;QUE NOS PERMITA DETERMINAR QU&Eacute; PASOS DEBEMOS SEGUIR PARA OBTENER LOS RESULTADOS ESPERADOS (IMPEDIR&nbsp;LA CONTAMINACI&Oacute;N DEL&nbsp;PRODUCTO POR&nbsp;LA PRESENCIA DE&nbsp;PLAGAS).&nbsp;</p>
            
            <p>LA IMPORTANCIA DE&nbsp;DIFERENCIAR LAS PLAGAS CONSISTE EN ESTABLECER DIVERSOS MECANISMOS DE PREVENCI&Oacute;N Y CONTROL, PORQUE CADA UNA DE ELLAS TIENE DIFERENTES NIVELES DE COMPORTAMIENTO SOCIAL Y BIOL&Oacute;GICO.&nbsp;</p>
            
            <p>NOTA:&nbsp;Este diagn&oacute;stico es&nbsp;exclusivo&nbsp;de&nbsp;{{customer}},&nbsp;especial para ser presentado ante las entidades sanitarias que lo requieran, est&aacute;&nbsp;prohibida&nbsp;la copia para otras empresas de&nbsp;Control de Plagas y/o&nbsp;Fumigaci&oacute;n.&nbsp;</p>'
        ];
        Block::create([
            "name" => "CABECERA DIAGNÓSTICO",
            "slug" => "diagnostic-header",
            "description" => "Muestra una cabecera estándar para definir ",
            "options" => json_encode($options)
        ]); 

        

        CrmTrackingTypes::create([
            "name" => "Registro en el sistema",
            "slug" => "init",
            "enabled" => 0
        ]);

        CrmTrackingTypes::create([
            "name" => "Llamada",
            "slug" => "call"
        ]);

        CrmTrackingTypes::create([
            "name" => "Correo Electrónico",
            "slug" => "email"
        ]);

        CrmTrackingTypes::create([
            "name" => "Visita",
            "slug" => "visit"
        ]);

        CrmTrackingTypes::create([
            "name" => "Cronograma de servicio",
            "slug" => "schedule"
        ]);

        CrmTrackingTypes::create([
            "name" => "Agenda/Programación",
            "slug" => "agenda"
        ]);


        CrmTrackingTypes::create([
            "name" => "Mantenimiento",
            "slug" => "support"
        ]);

        CrmTrackingTypes::create([
            "name" => "Cotización",
            "slug" => "quote"
        ]);

        CrmTrackingTypes::create([
            "name" => "Diagnóstico",
            "slug" => "diagnostic"
        ]);

        CrmTrackingTypes::create([
            "name" => "Contrato",
            "slug" => "contract"
        ]);

        CrmTrackingTypes::create([
            "name" => "Facturación",
            "slug" => "bill"
        ]);

        CrmTrackingTypes::create([
            "name" => "Ejecución de servicio",
            "slug" => "service"
        ]);

        CrmTrackingTypes::create([
            "name" => "Incidente",
            "slug" => "incident"
        ]);
        CrmTrackingTypes::create([
            "name" => "Otro",
            "slug" => "other"
        ]);


    }
}
