<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Permission;
use Caffeinated\Shinobi\Models\PermissCielion;
use Caffeinated\Shinobi\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /*
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        /* //permiso para cambiar de sede
        $permission = new Permission();
        $permission->name        = 'Navegar entre sedes';
        $permission->slug        = 'headquarters.switch';
        $permission->description = 'Ver/gestionar información de otras sedes';
        $permission->save();
        $permission->roles()->sync([1]);
        
        //Permisos de usuario
        $permission = new Permission();
        $permission->name        = 'Listar Usuarios';
        $permission->slug        = 'users.index';
        $permission->description = 'Listar los registros de usuario';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,12]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles de usuario';
        $permission->slug        = 'users.show';
        $permission->description = 'Ver todos los detalles de registros de usuario';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,12]);
        
        $permission = new Permission();
        $permission->name        = 'Crear usuarios';
        $permission->slug        = 'users.create';
        $permission->description = 'Crear registros de usuario';
        $permission->save();
        $permission->roles()->sync([1,2]);
        
        $permission = new Permission();
        $permission->name        = 'Editar usuarios';
        $permission->slug        = 'users.edit';
        $permission->description = 'Editar registros de usuario';
        $permission->save();
        $permission->roles()->sync([1,2]);
        
        $permission = new Permission();
        $permission->name        = 'Eliminar usuarios';
        $permission->slug        = 'users.destroy';
        $permission->description = 'Eliminar usuarios del sistema';
        $permission->save();
        $permission->roles()->sync([1,2]);

        $permission = new Permission();
        $permission->name        = 'Editar Perfil de usuario';
        $permission->slug        = 'users.profile';
        $permission->description = 'Editar Perfil de usuario';
        $permission->save();
        $permission->roles()->sync([1,2,3,4,5,6,7,8,9,10,11,12]);
               
        //Permisos de disponibilidad técnica
        $permission = new Permission();
        $permission->name        = 'Ver las disponibilidades';
        $permission->slug        = 'availability.index';
        $permission->description = 'Ver la configuración de la disponibilidad de los técnicos';
        $permission->save();
        $permission->roles()->sync([1,2,8,10]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles de disponibilidad';
        $permission->slug        = 'availability.show';
        $permission->description = 'Ver todos los detalles de la disponibilidad técnica';
        $permission->save();
        $permission->roles()->sync([1,2,8,10]);
        
        $permission = new Permission();
        $permission->name        = 'Crear nueva disponibilidad';
        $permission->slug        = 'availability.create';
        $permission->description = 'Establecer configuración de la disponibilidad de los técnicos';
        $permission->save();
        $permission->roles()->sync([1,2,8,10]);
        
        $permission = new Permission();
        $permission->name        = 'Editar disponibilidades';
        $permission->slug        = 'availability.edit';
        $permission->description = 'Editar la disponibilidad de los técnicos';
        $permission->save();
        $permission->roles()->sync([1,2,8,10]);
        
        $permission = new Permission();
        $permission->name        = 'Eliminar disponibilidades';
        $permission->slug        = 'availability.destroy';
        $permission->description = 'Eliminar configuraciones de disponibilidades técnicas del sistema';
        $permission->save();
        $permission->roles()->sync([1,2,8,10]);
      
        
        //Permisos de sedes
        $permission = new Permission();
        $permission->name        = 'Listar Sedes';
        $permission->slug        = 'headquarters.index';
        $permission->description = 'Listar los registros de sede';
        $permission->save();
        $permission->roles()->sync([1]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles de sede';
        $permission->slug        = 'headquarters.show';
        $permission->description = 'Ver todos los detalles de registros de sede';
        $permission->save();
        $permission->roles()->sync([1,2,5,6,7,8,9,10,12]);
        
        $permission = new Permission();
        $permission->name        = 'Crear sedes';
        $permission->slug        = 'headquarters.create';
        $permission->description = 'Crear registros de sede';
        $permission->save();
        $permission->roles()->sync([1]);
        
        $permission = new Permission();
        $permission->name        = 'Editar sedes';
        $permission->slug        = 'headquarters.edit';
        $permission->description = 'Editar registros de sede';
        $permission->save();
        $permission->roles()->sync([1]);
        
        $permission = new Permission();
        $permission->name        = 'Eliminar sedes';
        $permission->slug        = 'headquarters.destroy';
        $permission->description = 'Eliminar sedes del sistema';
        $permission->save();
        $permission->roles()->sync([1]);


        $permission = new Permission();
        $permission->name        = 'Editar información de la propia sede';
        $permission->slug        = 'headquarters.self-edit';
        $permission->description = 'Permite modificar la información de la sede del usuario';
        $permission->save();
        $permission->roles()->sync([2]);

        //permisos de servicios
        $permission = new Permission();
        $permission->name        = 'Listar servicios';
        $permission->slug        = 'services.index';
        $permission->description = 'Listar los registros de servicio';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,12]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles de servicio';
        $permission->slug        = 'services.show';
        $permission->description = 'Ver todos los detalles de registros de servicio';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,12]);
        
        $permission = new Permission();
        $permission->name        = 'Crear servicios';
        $permission->slug        = 'services.create';
        $permission->description = 'Crear registros de servicio';
        $permission->save();
        $permission->roles()->sync([1]);
        
        $permission = new Permission();
        $permission->name        = 'Editar servicios';
        $permission->slug        = 'services.edit';
        $permission->description = 'Editar registros de servicio';
        $permission->save();
        $permission->roles()->sync([1]);

        $permission = new Permission();
        $permission->name        = 'Eliminar servicios';
        $permission->slug        = 'services.destroy';
        $permission->description = 'Eliminar servicios del sistema';
        $permission->save();
        $permission->roles()->sync([1]);

        //permisos de áreas de clientes
        
        $permission = new Permission();
        $permission->name        = 'Listar áreas de clientes';
        $permission->slug        = 'crms.areas-index';
        $permission->description = 'Listar las areas de clientes';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,11,12]);
        
        $permission = new Permission();
        $permission->name        = 'Crear un área de cliente';
        $permission->slug        = 'crms.areas-create';
        $permission->description = 'Crear un área de cliente';
        $permission->save();
        $permission->roles()->sync([1,2,4,6,9,12]);
        
        $permission = new Permission();
        $permission->name        = 'Editar area de cliente';
        $permission->slug        = 'crms.areas-edit';
        $permission->description = 'Editar area de cliente';
        $permission->save();
        $permission->roles()->sync([1,2,4,6,9,12]);

        $permission = new Permission();
        $permission->name        = 'Eliminar area de cliente';
        $permission->slug        = 'crms.areas-destroy';
        $permission->description = 'Eliminar area de cliente';
        $permission->save();
        $permission->roles()->sync([1,2,4,6,9,12]);


        //permisos de producto
        $permission = new Permission();
        $permission->name        = 'Listar productos';
        $permission->slug        = 'products.index';
        $permission->description = 'Listar los registros de producto';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,11,12]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles del producto';
        $permission->slug        = 'products.show';
        $permission->description = 'Ver todos los detalles del registros de producto';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,11,12]);
        
        $permission = new Permission();
        $permission->name        = 'Crear productos';
        $permission->slug        = 'products.create';
        $permission->description = 'Crear registros del productos';
        $permission->save();
        $permission->roles()->sync([1,2,5,6,7,12]);
   
        $permission = new Permission();
        $permission->name        = 'Editar productos';
        $permission->slug        = 'products.edit';
        $permission->description = 'Editar registros del producto';
        $permission->save();
        $permission->roles()->sync([1,2,5,6,7,12]);
        
        $permission = new Permission();
        $permission->name        = 'Eliminar productos';
        $permission->slug        = 'products.destroy';
        $permission->description = 'Eliminar productos del sistema';
        $permission->save();
        $permission->roles()->sync([1,2,5,6,7,12]);

        //permisos de vehiculo
        $permission = new Permission();
        $permission->name        = 'Listar vehiculos';
        $permission->slug        = 'vehicles.index';
        $permission->description = 'Listar los registros de vehículos';
        $permission->save();
        $permission->roles()->sync([1,2,8,10,11]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles del vehiculos';
        $permission->slug        = 'vehicles.show';
        $permission->description = 'Ver todos los detalles del registros de vehiculo';
        $permission->save();
        $permission->roles()->sync([1,2,8,10,11]);
        
        $permission = new Permission();
        $permission->name        = 'Crear vehiculos';
        $permission->slug        = 'vehicles.create';
        $permission->description = 'Crear registros del vehiculos';
        $permission->save();
        $permission->roles()->sync([1,2,8,10]);
        
        $permission = new Permission();
        $permission->name        = 'Editar vehiculos';
        $permission->slug        = 'vehicles.edit';
        $permission->description = 'Editar registros del vehiculos';
        $permission->save();
        $permission->roles()->sync([1,2,8,10]);
        
        $permission = new Permission();
        $permission->name        = 'Eliminar vehiculos';
        $permission->slug        = 'vehicles.destroy';
        $permission->description = 'Eliminar los vehiculos del sistema';
        $permission->save();
        $permission->roles()->sync([1,2,8,10]);

        //permisos de CRM
        $permission = new Permission();
        $permission->name        = 'Listar CRM';
        $permission->slug        = 'crms.index';
        $permission->description = 'Listar los registros del CRM';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,12]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles del CRM';
        $permission->slug        = 'crms.show';
        $permission->description = 'Ver todos los detalles del registros del CRM';
        $permission->save();
        $permission->roles()->sync([1,2,3,4,5,6,7,8,9,10,12]);

        $permission = new Permission();
        $permission->name        = 'Eliminar Detalles de CRM';
        $permission->slug        = 'crms.info.destroy';
        $permission->description = 'Habilita el boton eliminar para todas las secciones de CRM';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,12]);

        $permission = new Permission();
        $permission->name        = 'Permisos Administrativos Detalles de CRM';
        $permission->slug        = 'crms.info.admin';
        $permission->description = 'Habilita opciones administrativas dentro de las secciones de CRM';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,12]);

        $permission = new Permission();
        $permission->name        = 'Modificar Info CRM';
        $permission->slug        = 'crms.info.edit';
        $permission->description = 'Ver todos los detalles del registros del CRM';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,12]);

        $permission = new Permission();
        $permission->name        = 'Crear CRM';
        $permission->slug        = 'crms.create';
        $permission->description = 'Crear registros del CRM';
        $permission->save();
        $permission->roles()->sync([1,2,4,6,8,9,10,12]);
         
        $permission = new Permission();
        $permission->name        = 'Editar CRM';
        $permission->slug        = 'crms.edit';
        $permission->description = 'Editar registros del CRM';
        $permission->save();
        $permission->roles()->sync([1,2,4,6,8,9,10,12]);
        
        $permission = new Permission();
        $permission->name        = 'Eliminar CRM';
        $permission->slug        = 'crms.destroy';
        $permission->description = 'Eliminar los registros  del CRM';
        $permission->save();
        $permission->roles()->sync([1,2,6,8,9,10]);
           
        //Permisos de inventario
        $permission = new Permission();
        $permission->name        = 'Listar inventario';
        $permission->slug        = 'inventory.index';
        $permission->description = 'Listar los registros de inventario';
        $permission->save();
        $permission->roles()->sync([1,2,5,7,8,10]);

        $permission = new Permission();
        $permission->name        = 'ajustes inventario';
        $permission->slug        = 'inventory.settings.quantity';
        $permission->description = 'Ajustar las cantidades del inventario por producto';
        $permission->save();
        $permission->roles()->sync([1,2]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles de inventario';
        $permission->slug        = 'inventory.show';
        $permission->description = 'Ver todos los detalles de registros de inventario';
        $permission->save();
        $permission->roles()->sync([1,2,5,6,7,8,10,12]);
        
        $permission = new Permission();
        $permission->name        = 'Crear inventario';
        $permission->slug        = 'inventory.create';
        $permission->description = 'Crear registros de inventario';
        $permission->save();
        $permission->roles()->sync([1,2,7]);
        
        $permission = new Permission();
        $permission->name        = 'Editar inventario';
        $permission->slug        = 'inventory.edit';
        $permission->description = 'Editar registros de inventario';
        $permission->save();
        $permission->roles()->sync([1,2,7]);

        $permission = new Permission();
        $permission->name        = 'Transferir inventario';
        $permission->slug        = 'inventory.transfer';
        $permission->description = 'Tranferir inventario de una bodega a otra';
        $permission->save();
        $permission->roles()->sync([1,2,5,7]);

        $permission = new Permission();
        $permission->name        = 'Revertir transferencia';
        $permission->slug        = 'inventory.transfer-destroy';
        $permission->description = 'Revertir transferencia de productos';
        $permission->save();
        $permission->roles()->sync([1,2,5,7]);
        
        $permission = new Permission();
        $permission->name        = 'Eliminar inventario';
        $permission->slug        = 'inventory.destroy';
        $permission->description = 'Eliminar inventario del sistema';
        $permission->save();
        $permission->roles()->sync([1,2,7]);
        
        //permisos de Cotizaciones
        $permission = new Permission();
        $permission->name        = 'Listar Cotizacion';
        $permission->slug        = 'quotes.index';
        $permission->description = 'Listar los registros de la Cotizacion';
        $permission->save();
        $permission->roles()->sync([1,2,4,6,9,12]);
        
        $permission = new Permission();
        $permission->name        = 'Crear Cotizacion';
        $permission->slug        = 'quotes.create';
        $permission->description = 'Crear registros de la Cotizacion';
        $permission->save();
        $permission->roles()->sync([1,2,4,9,6,12]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles de la Cotizacion';
        $permission->slug        = 'quotes.show';
        $permission->description = 'Ver todos los detalles del registros de la Cotizacion';
        $permission->save();
        $permission->roles()->sync([1,2,4,6,9,12]);
        
        $permission = new Permission();
        $permission->name        = 'Editar Cotizacion';
        $permission->slug        = 'quotes.edit';
        $permission->description = 'Editar registros de la Cotizacion';
        $permission->save();
        $permission->roles()->sync([1,2,4,6,9,12]);

        $permission = new Permission();
        $permission->name        = 'Enviar Cotizacion';
        $permission->slug        = 'quotes.send';
        $permission->description = 'Enviar la cotación al cliente';
        $permission->save();
        $permission->roles()->sync([1,2,4,9,6,12]);
        
        $permission = new Permission();
        $permission->name        = 'Eliminar Cotizacion';
        $permission->slug        = 'quotes.destroy';
        $permission->description = 'Eliminar los registros  de la Cotizacion';
        $permission->save();
        $permission->roles()->sync([1,2,4,9,6,12]);

        $permission = new Permission();
        $permission->name        = 'Configurar formatos de cotiaciones';
        $permission->slug        = 'quotes.config';
        $permission->description = 'Permite generar diferentes formatos de cotizaciones con espeficaciones técnicas';
        $permission->save();
        $permission->roles()->sync([1,2,4,6,12]);

        $permission = new Permission();
        $permission->name        = 'Generar cronograma de actividades desde cotización por sede';
        $permission->slug        = 'quotes.supplies';
        $permission->description = 'Permite registrar los insumos a utilizar y la periodicidad del servicio cotizado';
        $permission->save();
        $permission->roles()->sync([1,2,6,8,10,12]);

        $permission = new Permission();
        $permission->name        = 'Generar cronograma de actividades';
        $permission->slug        = 'crms.schedule-create';
        $permission->description = 'Permite registrar un servicio dentro del cronograma de actividades';
        $permission->save();
        $permission->roles()->sync([1,2,6,8,10,12]);

        $permission = new Permission();
        $permission->name        = 'Ver el cronograma de cliente';
        $permission->slug        = 'crms.schedule-show';
        $permission->description = 'Permite ver detalles y generar el cronograma de actividades del cliente en PDF';
        $permission->save();
        $permission->roles()->sync([1,2,3,6,8,10,12]);

        $permission = new Permission();
        $permission->name        = 'Enviar cronograma';
        $permission->slug        = 'crms.schedule-send';
        $permission->description = 'Enviar al cliente PDF con cronograma de servicio';
        $permission->save();
        $permission->roles()->sync([1,2,6,8,10,12]);

        $permission = new Permission();
        $permission->name        = 'Eliminar programación del cronograma';
        $permission->slug        = 'crms.schedule-destroy';
        $permission->description = 'Eliminar programación del cronogram';
        $permission->save();
        $permission->roles()->sync([1,2,6,8,10,12]);

        $permission = new Permission();
        $permission->name        = 'Generar planos';
        $permission->slug        = 'crms.plans';
        $permission->description = 'Permite registrar los planos requeridos para el cliente';
        $permission->save();
        $permission->roles()->sync([1,2,6,12]);

        $permission = new Permission();
        $permission->name        = 'Adjuntar Diagnósitco';
        $permission->slug        = 'quotes.diagnosis';
        $permission->description = 'Permite adjuntar el archivo diagnóstico de una cotización';
        $permission->save();
        $permission->roles()->sync([1,2,6,12]);

        $permission = new Permission();
        $permission->name        = 'Crear contrato';
        $permission->slug        = 'quotes.contract';
        $permission->description = 'Permite adjuntar el archivo diagnóstico de una cotización';
        $permission->save();
        $permission->roles()->sync([1,2,6,8,10,12]);
        
        //permisos de programación
        $permission = new Permission();
        $permission->name        = 'Ver Agenda';
        $permission->slug        = 'scheduling.index';
        $permission->description = 'Ver la agenda de programación';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,11,12]);
        
        $permission = new Permission();
        $permission->name        = 'Crear Eventos de programación';
        $permission->slug        = 'scheduling.create';
        $permission->description = 'Crear eventos a la programación';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,11,12]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles de eventos';
        $permission->slug        = 'scheduling.show';
        $permission->description = 'Ver todos los detalles de los eventos de programación';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,11,12]);
        
        $permission = new Permission();
        $permission->name        = 'Editar eventos';
        $permission->slug        = 'scheduling.edit';
        $permission->description = 'Editar los eventos de la programación';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,11,12]);
        
        $permission = new Permission();
        $permission->name        = 'Eliminar eventos';
        $permission->slug        = 'scheduling.destroy';
        $permission->description = 'Eliminar los eventos de la programación';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,11,12]);

        $permission = new Permission();
        $permission->name        = 'Automatización de programación de rutas';
        $permission->slug        = 'scheduling.automation';
        $permission->description = 'Configuración de la automatización para la programación de rutas';
        $permission->save();
        $permission->roles()->sync([1,2,8,10]);


        //permisos de rutas
        $permission = new Permission();
        $permission->name        = 'Ver las rutas generadas';
        $permission->slug        = 'routes.index';
        $permission->description = 'Listar todas las rutas generadas manualmente y desde la automatización';
        $permission->save();
        $permission->roles()->sync([1,2,8,10]);

        //permisos de rutas
        $permission = new Permission();
        $permission->name        = 'Eliminar rutas generadas';
        $permission->slug        = 'routes.destroy';
        $permission->description = 'Permite eliminar una ruta generada mientras esta no esté siendo gestionada';
        $permission->save();
        $permission->roles()->sync([1,2,8,10]);
        
        
        //permisos de consecutivos
        $permission = new Permission();
        $permission->name        = 'Listar consecutivos';
        $permission->slug        = 'settings.index';
        $permission->description = 'Listar los consecutivos';
        $permission->save();
        $permission->roles()->sync([1,2,5,7,8,10]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles de los consecutivos';
        $permission->slug        = 'settings.show';
        $permission->description = 'Ver todos los detalles del registros';
        $permission->save();
        $permission->roles()->sync([1,2,5,7,8,10]);
        
        $permission = new Permission();
        $permission->name        = 'Crear consecutivos';
        $permission->slug        = 'settings.create';
        $permission->description = 'Crear registros del consecutivo';
        $permission->save();
        $permission->roles()->sync([1,2,5,7,8,10]);

        $permission = new Permission();
        $permission->name        = 'Editar consecutivos';
        $permission->slug        = 'settings.edit';
        $permission->description = 'Editar registros de los consecutivos';
        $permission->save();
        $permission->roles()->sync([1,2,5,7,8,10]);
     
        $permission = new Permission();
        $permission->name        = 'Eliminar consecutivos';
        $permission->slug        = 'settings.destroy';
        $permission->description = 'Eliminar los registros  de los consecutivos';
        $permission->save();
        $permission->roles()->sync([1,2,5,7,8,10]);

      //permisos de plagas
        $permission = new Permission();
        $permission->name        = 'Listar Controles';
        $permission->slug        = 'infestations.index';
        $permission->description = 'Listar los registros de los controles';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,12]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles de los controles';
        $permission->slug        = 'infestations.show';
        $permission->description = 'Ver todos los detalles de los controles';
        $permission->save();
        $permission->roles()->sync([1,2,4,5,6,7,8,9,10,12]);
        
        $permission = new Permission();
        $permission->name        = 'Crear controles';
        $permission->slug        = 'infestations.create';
        $permission->description = 'Crear registros de los controles';
        $permission->save();
        $permission->roles()->sync([1]);
        
        $permission = new Permission();
        $permission->name        = 'Editar Controles';
        $permission->slug        = 'infestations.edit';
        $permission->description = 'Editar registros de los controles';
        $permission->save();
        $permission->roles()->sync([1]);
        
        $permission = new Permission();
        $permission->name        = 'Eliminar Controles';
        $permission->slug        = 'infestations.destroy';
        $permission->description = 'Eliminar los registros de los controles';
        $permission->save();
        $permission->roles()->sync([1]);
        
         //permisos de facturacion
         $permission = new Permission();
         $permission->name        = 'Listar de proforma';
         $permission->slug        = 'billings.index';
         $permission->description = 'Listar los  registros de las proformas';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,9,10]);
         
         $permission = new Permission();
         $permission->name        = 'Detalles de las proformas';
         $permission->slug        = 'billings.show';
         $permission->description = 'Ver todos los detalles de las proformas';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,9,10]);
         
         $permission = new Permission();
         $permission->name        = 'Crear proforma';
         $permission->slug        = 'billings.create';
         $permission->description = 'Crear registros de la proforma';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,9,10]);
         
         $permission = new Permission();
         $permission->name        = 'Editar proforma';
         $permission->slug        = 'billings.edit';
         $permission->description = 'Editar registros de la proforma';
         $permission->save();
         $permission->roles()->sync([1,2]);
         
         $permission = new Permission();
         $permission->name        = 'Eliminar proforma';
         $permission->slug        = 'billings.destroy';
         $permission->description = 'Eliminar los registros de la proforma';
         $permission->save();
         $permission->roles()->sync([1,2]);

         //permisos de orden de compras
         $permission = new Permission();
         $permission->name        = 'Listar ordenes de compra';
         $permission->slug        = 'orderPurchases.index';
         $permission->description = 'Listar los  registros de las ordenes de compra';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,9]);

         $permission = new Permission();
         $permission->name        = 'Unificar ordenes de compra';
         $permission->slug        = 'orderPurchases.general';
         $permission->description = 'Unificar las ordenes de compras de todas las sedes';
         $permission->save();
         $permission->roles()->sync([1,2,8]);
         
         $permission = new Permission();
         $permission->name        = 'Detalles de las ordenes de compra';
         $permission->slug        = 'orderPurchases.show';
         $permission->description = 'Ver todos los detalles de las ordenes de compra';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,9]);
         
         $permission = new Permission();
         $permission->name        = 'Crear orden de compra';
         $permission->slug        = 'orderPurchases.create';
         $permission->description = 'Crear registros de la orden de compra';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,9]);
         
         $permission = new Permission();
         $permission->name        = 'Editar orden de compra';
         $permission->slug        = 'orderPurchases.edit';
         $permission->description = 'Editar registros de la orden de compra';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,9]);
         
         $permission = new Permission();
         $permission->name        = 'Eliminar orden de compra';
         $permission->slug        = 'orderPurchases.destroy';
         $permission->description = 'Eliminar los registros de la orden de compra';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,9]);


         //permisos de confirmacion de transferencia
         $permission = new Permission();
         $permission->name        = 'Listar de confirmacion de transferencia';
         $permission->slug        = 'confirmationTransfer.index';
         $permission->description = 'Listar los  registros de la confirmacion de transferencia';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,11]);
         
         $permission = new Permission();
         $permission->name        = 'Detalles de la confirmacion de transferencia';
         $permission->slug        = 'confirmationTransfer.show';
         $permission->description = 'Ver todos los detalles de la confirmacion de transferencia';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,11]);
         
         $permission = new Permission();
         $permission->name        = 'Crear confirmacion de transferencia';
         $permission->slug        = 'confirmationTransfer.create';
         $permission->description = 'Crear registros de la confirmacion de transferencia';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,11]);
         
         $permission = new Permission();
         $permission->name        = 'Eliminar confirmacion de transferencia';
         $permission->slug        = 'confirmationTransfer.destroy';
         $permission->description = 'Eliminar los registros de la confirmacion de transferencia';
         $permission->save();
         $permission->roles()->sync([1,2,5,7,11]);

         //permisos de vista del cliente
         $permission = new Permission();
         $permission->name        = 'Lista del cliente';
         $permission->slug        = 'customers.index';
         $permission->description = 'Listar los  registros del cliente';
         $permission->save();
         $permission->roles()->sync([1,3]);
         
         $permission = new Permission();
         $permission->name        = 'Detalles del cliente';
         $permission->slug        = 'customers.show';
         $permission->description = 'Ver todos los detalles del cliente';
         $permission->save();
         $permission->roles()->sync([1,3]);
         
         $permission = new Permission();
         $permission->name        = 'Crear cliente';
         $permission->slug        = 'customers.create';
         $permission->description = 'Crear registros del cliente';
         $permission->save();
         $permission->roles()->sync([1,3]);
         
         $permission = new Permission();
         $permission->name        = 'Editar cliente';
         $permission->slug        = 'customers.edit';
         $permission->description = 'Editar registros del cliente';
         $permission->save();
         $permission->roles()->sync([1,3]);
         
         $permission = new Permission();
         $permission->name        = 'Eliminar cliente';
         $permission->slug        = 'customers.destroy';
         $permission->description = 'Eliminar los registros del cliente';
         $permission->save();
         $permission->roles()->sync([1,3]);

         //permisos de vista del dashboard
         $permission = new Permission();
         $permission->name        = 'inicio de dashboard';
         $permission->slug        = 'dashboards.index';
         $permission->description = 'mostrar los repostes en el dashboard';
         $permission->save();
         $permission->roles()->sync([1,2]);
         
         $permission = new Permission();
         $permission->name        = 'Detalles de las cotizaciones';
         $permission->slug        = 'dashboards.commercial';
         $permission->description = 'Ver todos los detalles de las cotizaciones por comercial';
         $permission->save();
         $permission->roles()->sync([1,2]);
         
         $permission = new Permission();
         $permission->name        = 'Detalles de la programacion';
         $permission->slug        = 'dashboards.programming';
         $permission->description = 'Ver todos los detalles de la programacion';
         $permission->save();
         $permission->roles()->sync([1,2]);
         

         //permisos QRS
         $permission = new Permission();
         $permission->name        = 'Listar QRs generados';
         $permission->slug        = 'qrs.index';
         $permission->description = 'Listar los QRs generados desde el sistema';
         $permission->save();
         $permission->roles()->sync([1,2,6,8,10,12]);

         $permission = new Permission();
         $permission->name        = 'Generar nuevos Qrs';
         $permission->slug        = 'qrs.create';
         $permission->description = 'Permite generar un nuevo lote de QRs';
         $permission->save();
         $permission->roles()->sync([1,2,6,8,10,12]);

         $permission = new Permission();
         $permission->name        = 'Ver/Imprimir lote de Qrs';
         $permission->slug        = 'qrs.show';
         $permission->description = 'Permite visualizar en formato PDF el lote de Qrs seleccionado';
         $permission->save();
         $permission->roles()->sync([1,2,6,8,10,12]);

         $permission = new Permission();
         $permission->name        = 'Eliminar Qrs';
         $permission->slug        = 'qrs.destroy';
         $permission->description = 'Permite eliminar el lote de Qrs seleccionado';
         $permission->save();
         $permission->roles()->sync([1,2,6,8,10,12]);

         //permisos de vista encuestas 
         $permission = new Permission();
         $permission->name        = 'Lista del encuesta';
         $permission->slug        = 'polls.index';
         $permission->description = 'Listar los  registros de las encuestas';
         $permission->save();
         $permission->roles()->sync([1,2]);
         
         $permission = new Permission();
         $permission->name        = 'Detalles de las encuesta';
         $permission->slug        = 'polls.show';
         $permission->description = 'Ver todos los detalles de las encuestas';
         $permission->save();
         $permission->roles()->sync([1,2]);
         
         $permission = new Permission();
         $permission->name        = 'Crear encuesta';
         $permission->slug        = 'polls.create';
         $permission->description = 'Crear registros  de las encuestas';
         $permission->save();
         $permission->roles()->sync([1,2]);
         
         $permission = new Permission();
         $permission->name        = 'Editar encuesta';
         $permission->slug        = 'polls.edit';
         $permission->description = 'Editar registros de las encuestas';
         $permission->save();
         $permission->roles()->sync([1,2]);
         
         $permission = new Permission();
         $permission->name        = 'Eliminar encuesta';
         $permission->slug        = 'polls.destroy';
         $permission->description = 'Eliminar los registros de las encuestas';
         $permission->save();
         $permission->roles()->sync([1,2]);

         //Permisos para tipos de documentos
         $permission = new Permission();
         $permission->name        = 'Listar Tipos de Documentos';
         $permission->slug        = 'documentTypes.index';
         $permission->description = 'Listar los registros de documentos';
         $permission->save();
         $permission->roles()->sync([1,2]);
         
         $permission = new Permission();
         $permission->name        = 'Detalles documentos';
         $permission->slug        = 'documentTypes.show';
         $permission->description = 'Ver todos los detalles y registros de documentos';
         $permission->save();
         $permission->roles()->sync([1,2]);
         
         $permission = new Permission();
         $permission->name        = 'Crear documentos';
         $permission->slug        = 'documentTypes.create';
         $permission->description = 'Crear registros de documentos';
         $permission->save();
         $permission->roles()->sync([1,2]);
         
         $permission = new Permission();
         $permission->name        = 'Editar documentos';
         $permission->slug        = 'documentTypes.edit';
         $permission->description = 'Editar registros de documentos';
         $permission->save();
         $permission->roles()->sync([1,2]);
         
         $permission = new Permission();
         $permission->name        = 'Eliminar documentos';
         $permission->slug        = 'documentTypes.destroy';
         $permission->description = 'Eliminar los documentos del sistema';
         $permission->save();
         $permission->roles()->sync([1,2]);

         //permisos para aplicaciones
        
        $permission = new Permission();
        $permission->name        = 'Listar aplicaciones';
        $permission->slug        = 'applications.index';
        $permission->description = 'Listar aplicaciones';
        $permission->save();
        $permission->roles()->sync([1,2]);
        
        $permission = new Permission();
        $permission->name        = 'Crear aplicación';
        $permission->slug        = 'applications.create';
        $permission->description = 'Crear aplicación';
        $permission->save();
        $permission->roles()->sync([1,2]);
        
        $permission = new Permission();
        $permission->name        = 'Editar aplicación';
        $permission->slug        = 'applications.edit';
        $permission->description = 'Editar aplicación';
        $permission->save();
        $permission->roles()->sync([1,2]);

        $permission = new Permission();
        $permission->name        = 'Eliminar aplicación';
        $permission->slug        = 'applications.destroy';
        $permission->description = 'Eliminar aplicación';
        $permission->save();
        $permission->roles()->sync([1,2]);

        //permisos para dosis
        
        $permission = new Permission();
        $permission->name        = 'Listar dosis';
        $permission->slug        = 'doses.index';
        $permission->description = 'Listar dosis';
        $permission->save();
        $permission->roles()->sync([1,2]);
        
        $permission = new Permission();
        $permission->name        = 'Crear dosis';
        $permission->slug        = 'doses.create';
        $permission->description = 'Crear dosis';
        $permission->save();
        $permission->roles()->sync([1,2]);
        
        $permission = new Permission();
        $permission->name        = 'Editar dosis';
        $permission->slug        = 'doses.edit';
        $permission->description = 'Editar dosis';
        $permission->save();
        $permission->roles()->sync([1,2]);

        $permission = new Permission();
        $permission->name        = 'Eliminar dosis';
        $permission->slug        = 'doses.destroy';
        $permission->description = 'Eliminar dosis';
        $permission->save();
        $permission->roles()->sync([1,2]);

        //permisos para dosis
        
        $permission = new Permission();
        $permission->name        = 'Listar unidades';
        $permission->slug        = 'units.index';
        $permission->description = 'Listar unidades';
        $permission->save();
        $permission->roles()->sync([1,2]);
        
        $permission = new Permission();
        $permission->name        = 'Crear unidades';
        $permission->slug        = 'units.create';
        $permission->description = 'Crear unidades';
        $permission->save();
        $permission->roles()->sync([1,2]);
        
        $permission = new Permission();
        $permission->name        = 'Editar unidades';
        $permission->slug        = 'units.edit';
        $permission->description = 'Editar unidades';
        $permission->save();
        $permission->roles()->sync([1,2]);

        $permission = new Permission();
        $permission->name        = 'Eliminar unidades';
        $permission->slug        = 'units.destroy';
        $permission->description = 'Eliminar unidades';
        $permission->save();
        $permission->roles()->sync([1,2]); 

        //permisos para auditorias
        
        $permission = new Permission();
        $permission->name        = 'Listar Auditorias';
        $permission->slug        = 'audit.index';
        $permission->description = 'Listar Auditorias';
        $permission->save();
        $permission->roles()->sync([1,2]);
        
        $permission = new Permission();
        $permission->name        = 'Ver Auditorias';
        $permission->slug        = 'audit.view';
        $permission->description = 'Ver Auditorias';
        $permission->save();
        $permission->roles()->sync([1,2]);
        
        $permission = new Permission();
        $permission->name        = 'Restaurar Auditorias';
        $permission->slug        = 'audit.restore';
        $permission->description = 'Restaurar Auditorias';
        $permission->save();
        $permission->roles()->sync([1,2]);

        //permisos para Metas
        
        $permission = new Permission();
        $permission->name        = 'Listar metas';
        $permission->slug        = 'objetive.index';
        $permission->description = 'Listar metas';
        $permission->save();
        $permission->roles()->sync([1]);
        
        $permission = new Permission();
        $permission->name        = 'Crear metas';
        $permission->slug        = 'objetive.create';
        $permission->description = 'Crear metas';
        $permission->save();
        $permission->roles()->sync([1]);
        
        $permission = new Permission();
        $permission->name        = 'Editar metas';
        $permission->slug        = 'objetive.edit';
        $permission->description = 'Editar metas';
        $permission->save();
        $permission->roles()->sync([1]);

        $permission = new Permission();
        $permission->name        = 'Eliminar metas';
        $permission->slug        = 'objetive.destroy';
        $permission->description = 'Eliminar metas';
        $permission->save();
        $permission->roles()->sync([1]); 

      //permisos para informe indicadores
        
      $permission = new Permission();
      $permission->name        = 'Listar informe indicadores mensual';
      $permission->slug        = 'indicator.report.month.index';
      $permission->description = 'Listar informe indicadores mensual';
      $permission->save();
      $permission->roles()->sync([1]);
      
      $permission = new Permission();
      $permission->name        = 'Listar informe indicadores anual';
      $permission->slug        = 'indicator.report.year.index';
      $permission->description = 'Listar informe indicadores anual';
      $permission->save();

      $role         = new Role();
      $role->name   = "indicadores comerciales";
      $role->slug   = "indicators_commercial";
      $role->save();


      $role         = new Role();
      $role->name   = "indicadores comerciales gráficos";
      $role->slug   = "indicators_commercial_graphics";
      $role->save();

      $permission->roles()->sync([1]);
*/
      $permission = new Permission();
      $permission->name        = 'Listar ticket';
      $permission->slug        = 'ticket.index';
      $permission->save();
      $permission->roles()->sync([1,8,10,4,12,6]);

      $permission = new Permission();
      $permission->name        = 'Crear ticket';
      $permission->slug        = 'ticket.create';
      $permission->save();
      $permission->roles()->sync([1,3]);

      $permission = new Permission();
      $permission->name        = 'Ver ticket';
      $permission->slug        = 'ticket.view';
      $permission->save();
      $permission->roles()->sync([1,8,10,4,12,6]);

      $permission = new Permission();
      $permission->name        = 'Responder ticket';
      $permission->slug        = 'ticket.answer';
      $permission->save();
      $permission->roles()->sync([1,3,8,10,4,12,6]);

      $permission = new Permission();
      $permission->name        = 'Resolver ticket';
      $permission->slug        = 'ticket.solved';
      $permission->save();
      $permission->roles()->sync([1,3,8,10,4,12,6]);
      
      $permission = new Permission();
      $permission->name        = 'Cambiar prioridad';
      $permission->slug        = 'ticket.changed.priority';
      $permission->save();
      $permission->roles()->sync([1,8,10,4,12,6]);
      
      // permisos desde el modulo de crm

      $permission = new Permission();
      $permission->name        = 'Listar ticket cliente';
      $permission->slug        = 'ticket.index.customer';
      $permission->save();
      $permission->roles()->sync([1,3]);

      $permission = new Permission();
      $permission->name        = 'Ver ticket cliente';
      $permission->slug        = 'ticket.view.customer';
      $permission->save();
      $permission->roles()->sync([1,3]);

      

    }
    
}