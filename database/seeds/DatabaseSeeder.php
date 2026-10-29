<?php

use Database\Seeders\ApplicationsTableProductionSeeder;
use Database\Seeders\AvailabilitiesTableProductionSeeder;
use Database\Seeders\AvailabilityBusinessDayTableProductionSeeder;
use Database\Seeders\AvailabilityUserTableProductionSeeder;
use Database\Seeders\ControlInfestationsTableProductionSeeder;
use Database\Seeders\ConvectionProductTableProductionSeeder;
use Database\Seeders\ConvectionsTableProductionSeeder;
use Database\Seeders\DosesTableProductionSeeder;
use Database\Seeders\HeadquartersTableProductionSeeder;
use Database\Seeders\InfestationsTableProductionSeeder;
use Database\Seeders\ProductsTableProductionSeeder;
use Database\Seeders\RoleUserTableProductionSeeder;
use Database\Seeders\UnitDosesTableProductionSeeder;
use Database\Seeders\UsersTableProductionSeeder;
use Database\Seeders\WarehousesTableProductionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        //$this->call(WarehousesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        //$this->call(HeadquartersTableSeeder::class);
        $this->call(HeadquartersTableProductionSeeder::class);
        //$this->call(AvailabilitiesTableSeeder::class);

        $this->call(UsersTableProductionSeeder::class);
        $this->call(RoleUserTableProductionSeeder::class);
        $this->call(WarehousesTableProductionSeeder::class);
        
        $this->call(ServicesTableSeeder::class);
        $this->call(ContactTypeTableSeeder::class);
        $this->call(MeanContactTableSeeder::class);
        $this->call(CrmCategoryTableSeeder::class);
        //$this->call(UsersTableSeeder::class);
        


        //seeders de permisos
        $this->call(PermissionsTableSeeder::class);
        $this->call(UnitDosesPermissionsTableSeeder::class);  /**NUEVO EJ MANUAL*/

        //tipos de archivos
        $this->call(FilesTableSeeder::class);
        $this->call(TypeFileImportSeeder::class); /**NUEVO EJ MANUAL */

        $this->call(ProductsTableSeeder::class);
        //$this->call(ProductTableSeeder::class);        

        //$this->call(ApplicationsTableSeeder::class);
        
        $this->call(SettingTableSeeder::class);
        $this->call(TimeUnitsTableSeeder::class);
        $this->call(SchedulingsTableSeeder::class);
        $this->call(UserTrackingsTableSeeder::class);
        $this->call(UnitTableSeeder::class);
        $this->call(ContractsTableSeeder::class);
        $this->call(QuotesTableSeeder::class);
        $this->call(QrsTableSeeder::class);
        $this->call(BlocksTableSeeder::class);
        $this->call(DiagnosticStatusTableSeeder::class);
        


        $this->call(TechnicalReportTableSeeder::class);
        $this->call(BillingStateTableSeeder::class);

        $this->call(AddTypeAgendaCrmTracking::class); /**NUEVO EJ MANUAL */
        $this->call(UpdatePeriodicitiesSeeder::class); /**NUEVO EJ MANUAL */
        
        /** seeders de datos de producción  */
        $this->call(ApplicationsTableProductionSeeder::class);
        $this->call(ProductsTableProductionSeeder::class);
        $this->call(ConvectionsTableProductionSeeder::class);
        $this->call(ConvectionProductTableProductionSeeder::class);
        $this->call(InfestationsTableProductionSeeder::class);
        $this->call(ControlInfestationsTableProductionSeeder::class);
        
        
        $this->call(AvailabilitiesTableProductionSeeder::class);
        $this->call(AvailabilityBusinessDayTableProductionSeeder::class);
        $this->call(AvailabilityUserTableProductionSeeder::class);
        $this->call(UnitDosesTableProductionSeeder::class);
        $this->call(DosesTableProductionSeeder::class);

        $this->call(NewStatusCrmSchedule::class);
        $this->call(NewTechnicalReportTypeSedder::class);

        
        
    }
}
