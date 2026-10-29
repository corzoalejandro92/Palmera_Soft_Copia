<?php
use Illuminate\Support\Facades\Route;
/******************************************************************************/
/**Eliminar*/

Route::get('crm/delete-form', 'Administrative\CrmController@deleteForm')
->name('crms.deleteform')
->middleware('crmDelete');

Route::get('crm/deleteContact-form', 'Administrative\CrmController@deleteFormContact')
->name('crms.deleteformContact')
->middleware('can:crms.destroy');

Route::get('crm/deleteOffice-form', 'Administrative\CrmController@deleteFormOffice')
->name('crms.deleteformOffice')
->middleware('can:crms.destroy');

Route::delete('crm/delete', 'Administrative\CrmController@softDelete')
->name('crms.destroy')
->middleware('crmDelete');

Route::get('crm/deleteTechnical-form', 'Administrative\CrmController@deleteFormTechnical')
->name('crms.deleteformTechnical')
->middleware('can:crms.destroy');

Route::delete('crm/form-deleteTechnical/{crm_id}', 'Administrative\CrmController@softDeleteTechnical')
->name('crms.destroytechnical')
->middleware('can:crms.destroy');

Route::delete('crm/form-deleteContact', 'Administrative\CrmController@softDeleteContact')
->name('crms.destroyContact')
->middleware('can:crms.destroy');

Route::delete('crm/form-deleteOffice', 'Administrative\CrmController@softDeleteOffice')
->name('crms.destroyOffice')
->middleware('can:crms.destroy');

Route::get('crm/deleteFile-form', 'Administrative\CrmController@deleteFormFile')
->name('crms.deleteformFile');

Route::delete('crm/form-deleteFile', 'Administrative\CrmController@softDeleteFile')
->name('crms.destroyFile');

/******************************************************************************/

/**Vista listar*/
/* Route::get('crm/form-list', 'Administrative\CrmController@list')
->name('crms.index')
->middleware('can:crms.index'); */

Route::get('crm', 'Administrative\CrmController@list')
->name('crms.index')
->middleware('can:crms.index');

Route::get('crm/validate-nit', 'Administrative\CrmController@validateNit')
    ->name('crms.validate-nit');

Route::get('crm/reesamble', 'Administrative\CrmController@reesamble')
    ->name('crms.reesamble');

    
/******************************************************************************/

/**Vista crear*/
Route::get('crm/form-crm', 'Administrative\CrmController@formCrm')
->name('crms.create')
->middleware('can:crms.create');

/**Vista crear*/
Route::get('crm/form-crm/simple', 'Administrative\CrmController@formSimpleCrm')
->name('crms.create')
->middleware('can:crms.create');
/**Vista crear*/
Route::post('crm/save/simple', 'Administrative\CrmController@saveSimple')
->name('crms.save')
->middleware('can:crms.create');


/******************************************************************************/
        /**CREAR CLIENTE*/
     /**SECCION DE INFORMACION DEL CLIENTE*/
Route::get('crm/info-crm', 'Administrative\CrmController@infoForm')
->name('crms.info')
->middleware('can:crms.create');

Route::get('crm/type-crm', 'Administrative\CrmController@typeCrm')
->name('crms.typeCrm')
->middleware('can:crms.create');

Route::get('crm/mean-contact', 'Administrative\CrmController@meanContact')
->name('crms.meanContact')
->middleware('can:crms.create');

Route::get('crm/type-file', 'Administrative\CrmController@typeFileCrm')
->name('crms.typeFile')
->middleware('can:crms.create');

Route::get('crm/type-contact', 'Administrative\CrmController@typeContact')
->name('crms.typeContact');

Route::get('crm/offices', 'Administrative\CrmController@offices')
->name('crms.typeSeat');

Route::get('crm/map-frame', 'Administrative\CrmController@mapFrame');

Route::get('crm/cities', 'Administrative\CrmController@cities');

Route::get('crm/headquarters', 'Administrative\CrmController@headquarters');

Route::get('crm/offices-customer-crm', 'Administrative\CrmController@officeCustomer')
->name('crms.offices-customer')
->middleware('can:crms.show');
/******************************************************************************/
      /**CREAR CLIENTE*/
     /**SECCION DE CONTACTO DEL CLIENTE*/
Route::get('crm/contact-form', 'Administrative\CrmController@contactForm')
->name('crms.contact')
->middleware('can:crms.create');

Route::post('crm/{crm}/set-avatar', 'Administrative\CrmController@setAvatar')
->name('crms.setAvatar');

/******************************************************************************/
/**SECCION DE USUARIOS DEL CLIENTE*/
Route::get('crm/users', 'Administrative\CrmController@users')
    ->middleware('can:crms.show');

Route::get('crm/commercials/all', 'Administrative\CrmController@usersCommercial')
    ->middleware('can:crms.show');

Route::get('crm/technicals', 'Administrative\CrmController@technicals')
    ->middleware('can:crms.show');

Route::get('crm/commercials', 'Administrative\CrmController@commercials')
    ->middleware('can:crms.show');

Route::get('crm/find-a-route', 'Administrative\CrmController@findARoute')
    ->middleware('can:crms.show');
    

Route::get('crm/schedule-statuses', 'Administrative\CrmController@scheduleStatuses')
    ->middleware('can:crms.show');

Route::get('crm/user-customer-form', 'Administrative\CrmController@formCustomerUser')
    ->name('crms.userform')
    ->middleware('can:crms.show');

/**Vista show crm lista de usuarios*/
Route::get('crm/{crm}/user-list', 'Administrative\CrmController@listUserShow')
    ->name('crms.showUser')
    ->middleware('can:crms.show');


/**Guardar los datos del cliente en el metodo editar del crm*/
Route::post('crm/save-user', 'Administrative\CrmController@storeUser')
    ->name('crms.storeUser')
    ->middleware('can:crms.show');

Route::get('crm/deleteUser-form', 'Administrative\CrmController@deleteFormUser')
    ->name('crms.deleteformUser')
    ->middleware('can:crms.destroy');

    Route::get('crm/fileUser-form', 'Administrative\CrmController@fileFormUser');


Route::delete('crm/form-deleteUser', 'Administrative\CrmController@softDeleteUser')
    ->name('crms.destroyUser')
    ->middleware('can:crms.show');

/********************************************************************************/
/**SESSION DE CONTRATOS*/

Route::get('crm/{crm}/contract-list', 'Administrative\CrmController@listContractShow')
    ->name('crms.showContract')
    ->middleware('can:crms.show');


/******************************************************************************/

     /**SECCION DE SUCURSALES DEL CLIENTE*/
Route::get('crm/office-form', 'Administrative\CrmController@formOffice')
->name('crms.officeform')
->middleware('can:crms.create');

Route::get('crm/country-office', 'Administrative\CrmController@countryOffice')
->name('crms.countryOffice')
->middleware('can:crms.create');

Route::get('crm/states-office/{country}', 'Administrative\CrmController@statesOffice')
->name('crms.statesOffice')
->middleware('can:crms.create');

Route::get('crm/city-office/{city}', 'Administrative\CrmController@cityOffice')
->name('crms.cityOffice')
->middleware('can:crms.create');

Route::get('crm/headquarters', 'Administrative\CrmController@headquarters')
->name('crms.headquarter')
->middleware('can:crms.create');

Route::get('crm/zona-office/{headquarter}', 'Administrative\CrmController@zonaOffice')
->name('crms.zonaOffice')
->middleware('can:crms.create');

/******************************************************************************/
     /**CREAR CLIENTE*/
     /**SECCION DE DOCUMENTOS DEL CLIENTE*/

Route::get('crm/file-form', 'Administrative\CrmController@fileForm')
->name('crms.fileform')
->middleware('can:crms.create');

Route::get('crm/add-document-row', 'Administrative\CrmController@addDocumentRow')
->name('crms.documentrow')
->middleware('can:crms.create');

/******************************************************************************/
     /**CREAR CLIENTE*/
     /**ASIGNACIÓN DE TÉCNICOS A CLIENTE*/

Route::get('crm/technical-form', 'Administrative\CrmController@technicalForm')
->name('crms.technicalform')
->middleware('can:crms.create');
     
Route::get('crm/add-technical-row', 'Administrative\CrmController@addTechnicalRow')
->name('crms.technicalrow')
->middleware('can:crms.create');

Route::get('crm/{crm}/schedule/add-observation-row', 'Administrative\CrmController@addObservationRow');

Route::post('crm/{crm}/schedule/save/observations', 'Administrative\CrmController@storeObservation');
/******************************************************************************/

/**Guardar formulario de cliente*/
Route::post('crm/save', 'Administrative\CrmController@store')
->name('crms.store')
->middleware('can:crms.create');

/******************************************************************************/

/**Actualizar formulario de cliente*/
Route::put('crm/update', 'Administrative\CrmController@update')
->name('crms.update')
->middleware('can:crms.edit');

/******************************************************************************/

/**formulario de editar cliente*/
Route::get('crm/form-customer', 'Administrative\CrmController@formCustomer')
->name('crms.create')
->middleware('can:crms.show');

/**Guardar los datos del cliente en el metodo editar del crm*/
Route::put('crm/save-customer', 'Administrative\CrmController@updateCustomerShow')
->name('crms.updateCrm')
->middleware('can:crms.show');

/******************************************************************************/

/**Vista crear contacto*/
Route::get('crm/form-contact', 'Administrative\CrmController@formContact')
->name('crms.createContact')
->middleware('can:crms.show');

/**Vista show crm lista de contactos*/
Route::get('crm/{crm}/contact-list', 'Administrative\CrmController@listContactShow')
->name('crms.showContact')
->middleware('can:crms.show');

/**Guardar los datos del cliente en el metodo editar del crm*/
Route::post('crm/save-contact', 'Administrative\CrmController@storeContact')
->name('crms.storeContact')
->middleware('can:crms.show');

/**Vista show crm actualizar los datos del registro*/
Route::put('crm/save-contact', 'Administrative\CrmController@updateContactShow')
->name('crms.updateCrm')
->middleware('can:crms.show');

/**Vista show crm actualizar los datos del registro*/
Route::get('crm/{crm}/contact/give-access', 'Administrative\CrmController@giveAccessContact')
->name('crms.giveAccessContact')
->middleware('can:crms.show');

Route::get('crm/{crm}/contact/revoke-access', 'Administrative\CrmController@revokeAccessContact')
->name('crms.revokeAccessContact')
->middleware('can:crms.show');

/******************************************************************************/
/**Vista crear office en la vista show*/
Route::get('crm/form-oficce', 'Administrative\CrmController@formOffice')
->name('crms.createOffice')
->middleware('can:crms.show');

/**Vista show crm lista de sedes*/
Route::get('crm/{crm}/office-list', 'Administrative\CrmController@listOfficeShow')
->name('crms.showOffice')
->middleware('can:crms.show');


/**Guardar los datos del cliente en el metodo editar del crm*/
Route::post('crm/save-office', 'Administrative\CrmController@storeOffice')
->name('crms.storeOffice')
->middleware('can:crms.show');

/**Vista show crm actualizar los datos del registro*/
Route::put('crm/save-office', 'Administrative\CrmController@updateOfficeShow')
->name('crms.updateOffice')
->middleware('can:crms.show');

/******************************************************************************/


Route::get('crm/{crm}/File-list', 'Administrative\CrmController@listFileShow')
->name('crms.showFiles')
->middleware('can:crms.show');

/**Vista show crm lista de archivos*/
Route::get('crms/showfile', 'FileController@show')
->name('crms.showfile')
->middleware('can:crms.show');

/**Vista crear archivo en la vista show*/
Route::get('crm/form-document', 'Administrative\CrmController@formDocumento')
->name('crms.createfile')
->middleware('can:crms.show');

/**Guardar los archivos en el metodo editar del crm*/
Route::post('crm/save-document', 'Administrative\CrmController@storeDocumento')
->name('crms.storeFile')
->middleware('can:crms.show');

/**Guardar los archivos en el metodo editar del crm*/
Route::post('crm/update-document', 'Administrative\CrmController@updateDocumentShow')
->name('crms.updateFile')
->middleware('can:crms.show');

/******************************************************************************/


Route::get('crm/{crm}/technical-list', 'Administrative\CrmController@listTechnicalShow')
->name('crms.showTechincals')
->middleware('can:crms.show');

/**Vista crear servicio en la vista show*/
Route::get('crm/form-technical', 'Administrative\CrmController@formTechnical')
->name('crms.createservice')
->middleware('can:crms.show');

/**Guardar los servicios en el metodo editar del crm*/
Route::post('crm/save-technical', 'Administrative\CrmController@storeTechnical')
->name('crms.storeTechnical')
->middleware('can:crms.show');

/**Guardar los servicios en el metodo editar del crm*/
Route::post('crm/update-technical', 'Administrative\CrmController@updateTechnical')
->name('crms.updateTechnical')
->middleware('can:crms.show');

/******************************************************************************/

/**Vista show crm lista de cotizaciones*/
Route::get('crm/{crm}/quote-list', 'Administrative\CrmController@listQuoteShow')
->name('crms.showQuote')
->middleware('can:crms.show');

/**Vista show crm editar la cotizaciones*/
Route::get('crm/quote/{quote}', 'Administrative\CrmController@editQuote')
    ->name('crm.editQuote')
    ->middleware('can:crms.edit');


/******************************************************************************/
/**Vista show crm lista de planos*/
Route::get('crm/{crm}/plan-list', 'Administrative\CrmController@listPlanShow')
->name('crms.showPlan')
->middleware('can:crms.show');

Route::get('crm/{crm}/mechanisms', 'Administrative\CrmController@listMechanismShow')
->name('crms.showPlan')
->middleware('can:crms.show');

/**Vista show crm lista de cronograma de actividades*/
Route::post('crm/{crm}/schedule-list', 'Administrative\CrmController@listScheduleShow')
->name('crms.showSchedule');

Route::post('crm/{crm}/schedule-calendar', 'Administrative\CrmController@calendarScheduleShow')
->name('crms.showSchedule');

Route::post('crm/{crm}/schedule-send', 'Administrative\CrmController@ScheduleSend')
->name('crms.sendScheduleCrm')
->middleware('can:crms.schedule-send');

/**PDF de cronograma de actividades*/
Route::get('crm/{crm}/schedule-pdf', 'Administrative\CrmController@schedulePdf')
->name('crm.schedulePdf');

Route::get('crm/{crm}/schedule/send-form', 'Administrative\CrmController@scheduleSendForm')
->name('crm.scheduleSendForm')
->middleware(('can:crms.schedule-send'));


Route::get('crm/{crm}/schedule-form', 'Administrative\CrmController@scheduleForm')
->name('crm.scheduleForm')
->middleware(('can:crms.schedule-send'));


Route::get('crm/{crm}/schedule/service-save', 'Administrative\CrmController@tempServiceSave')
->name('crm.scheduleServiceSave')
->middleware(('can:crms.schedule-create'));

Route::get('crm/{crm}/schedule/service-form', 'Administrative\CrmController@serviceForm')
->name('crm.scheduleServiceForm')
->middleware(('can:crms.schedule-create'));

Route::get('crm/{crm}/schedule/service-remove', 'Administrative\CrmController@tempServiceRemove')
->name('crm.scheduleSendRemove')
->middleware(('can:crms.schedule-create'));

Route::get('crm/{crm}/schedule/session/service-remove', 'Administrative\CrmController@tempServiceRemove')
->name('crm.scheduleSessionSendRemove')
->middleware(('can:crms.schedule-create'));

Route::get('crm/{crm}/schedule/add-control-frame', 'Administrative\CrmController@addControlFrame')
->name('crm.scheduleAddControlFrame')
->middleware('can:crms.schedule-create');

Route::get('crm/{crm}/schedule/add-week-frame', 'Administrative\CrmController@addWeekFrame')
->name('crm.scheduleAddControlWeek');

Route::get('crm/{crm}/schedule/getWeeks', 'Administrative\CrmController@getWeeksOfPeriodicity')
->name('crm.scheduleGetWeek');

Route::get('crm/{crm}/schedule/doses', 'Administrative\CrmController@Doses')
->name('crm.scheduleDoses');

Route::get('crm/{crm}/schedule/applications', 'Administrative\CrmController@applications')
    ->name('crm.applications');

Route::get('crm/{crm}/schedule/areas', 'Administrative\CrmController@scheduleAreas')
->name('crm.scheduleAddControlFrame')
->middleware('can:crms.schedule-create');

Route::get('crm/{crm}/schedule/add-supply-row', 'Administrative\CrmController@addSupplyRow')
->name('crm.scheduleAddControlFrame')
->middleware('can:crms.schedule-create');

Route::post('crm/{crm}/schedule/save', 'Administrative\CrmController@scheduleSave')
->name('crm.scheduleAddControlFrame')
->middleware('can:crms.schedule-create');

Route::get('crm/schedule/delete/{control}', 'Administrative\CrmController@scheduleDelete')
->middleware('can:crms.schedule-destroy');

Route::get('crm/schedule/full-delete/{workschedule}', 'Administrative\CrmController@scheduleFullDelete')
->middleware('can:crms.schedule-destroy');

Route::get('schedule/crm/consolidated/report', 'Administrative\CrmController@consolidated')
->name('crmschedule.report.consolidated');


Route::get('crm/{crm}/schedules/edit/{control}', 'Administrative\CrmController@scheduleEdit')
->middleware('can:crms.schedule-create');

Route::get('crm/{crm}/schedules/new', 'Administrative\CrmController@scheduleNew')
->middleware('can:crms.schedule-create');


Route::get('crm/{crm}/external-note/delete/{id}', 'Administrative\CrmController@deleteExternalNote')
->middleware('can:crms.schedule-create');

Route::get('crm/{crm}/external-note/form', 'Administrative\CrmController@externalNoteForm')
->middleware('can:crms.schedule-create');

Route::post('crm/{crm}/external-note/save', 'Administrative\CrmController@storeExternalNote')
->middleware('can:crms.schedule-create');

Route::put('crm/{crm}/external-note/save', 'Administrative\CrmController@updateExternalNote')
->middleware('can:crms.schedule-create');

Route::post('crm/{crm}/external-notes', 'Administrative\CrmController@ExternalNotes')
->middleware('can:crms.schedule-create');

Route::get('crm/{crm}/{office}/general-observation', 'Administrative\CrmController@getGeneralObservation')
->middleware('can:crms.schedule-create');

Route::get('crm/{crm}/{office}/general-observation/save', 'Administrative\CrmController@saveGeneralObservation')
->middleware('can:crms.schedule-create');


Route::get('crm/{crm}/office/{office}/workschedules', 'Administrative\CrmController@workschedules')
->middleware('can:crms.schedule-create');

Route::post('crm/{crm}/office/{office}/workschedules/save', 'Administrative\CrmController@saveNewSchedule')
->middleware('can:crms.schedule-create');

Route::get('crm/workschedules/service/{crmSchedule}', 'Administrative\CrmController@getFrameFromService')
->middleware('can:crms.schedule-create');

Route::get('crm/{crm}/schedule/add-supply-row-form-control', 'Administrative\CrmController@addSupplyRowFormControl')
->middleware('can:crms.schedule-create');

Route::post('crm/{crm}/schedule/control/save', 'Administrative\CrmController@scheduleControlSave')
->middleware('can:crms.schedule-create');


Route::get('crm/scheduling/update-date', 'Administrative\CrmController@updateDate')
->middleware('can:crms.schedule-create');

Route::get('crm/note/update-date', 'Administrative\CrmController@updateNote')
->middleware('can:crms.schedule-create');


/**Vista show crm editar la cotizaciones*/
Route::get('crm/quote/{quote}', 'Administrative\CrmController@editQuote')
    ->name('crm.editQuote')
    ->middleware('can:crms.edit');
/******************************************************************************/

/**Guardar el nombre del area en el formulario de carga de planos*/
Route::get('crm/save-area-customer', 'Administrative\CrmController@storeAreaOffice')
    ->name('crm.saveAreaOffice')
    ->middleware('can:crms.plans');

Route::get('crm/update-area-customer', 'Administrative\CrmController@updateAreaOffice')
->name('crm.saveAreaOffice')
->middleware('can:crms.plans');


/** Dibujar planos desde cero */
Route::get('crm/drawPlan','Administrative\CrmController@drawPlan')
->name('crm.drawPlan')
->middleware('can:crms.plans');

/** Dibujar planos desde cero */
Route::get('crm/editPlan','Administrative\CrmController@editPlan')
->name('crm.editPlan')
->middleware('can:crms.plans');

/**formularios para eliminar las areas */

Route::get('crm/delete-form-area', 'Administrative\CrmController@deleteFormAreas')
->name('crm.deleteFormArea')
->middleware('can:crms.plans');

/**Confirmacion para eliminar el area **/
Route::delete('crm/area/delete', 'Administrative\CrmController@softDeleteArea')
->name('crm.destroyArea')
->middleware('can:crms.plans');

Route::get('crm/plan/types','Administrative\CrmController@planTypes')
->name('crm.planTypes')
->middleware('can:crms.plans');

Route::post('crm/plan/save','Administrative\CrmController@planSave')
->name('crm.planSave')
->middleware('can:crms.plans');

Route::put('crm/plan/save','Administrative\CrmController@planUpdate')
->name('crm.planSave')
->middleware('can:crms.plans');

Route::get('crm/showfile', 'FileController@show')
->name('crm.showfile');

Route::get('crm/showfileRoute', 'Administrative\CrmController@previewPlanRoute')
->name('crm.showPlanRoute');

Route::get('crm/previewfile', 'Administrative\CrmController@previewPlan')
->name('crm.previewfile');

Route::get('crm/delete-plan-form', 'Administrative\CrmController@deletePlanForm')
->name('crm.deletePlanForm')
->middleware('can:crms.plans');

Route::delete('crm/plan/delete', 'Administrative\CrmController@softDeletePlan')
->name('crm.destroyPlans')
->middleware('can:crms.plans');

Route::get('crm/drawMec','Administrative\CrmController@drawMechanisms')
->name('crm.drawMec')
->middleware('can:crms.plans');

Route::get('crm/listMec','Administrative\CrmController@listMechanisms')
->name('crm.listMec')
->middleware('can:crms.plans');

Route::get('crm/mechanism/printQrs','Administrative\CrmController@printQrs');

Route::get('crm/mechanism/save/{convection}','Administrative\CrmController@saveMechanism')
->name('crm.drawSaveMechanism')
->middleware('can:crms.plans');

Route::get('crm/mechanism/updatePosition/{fileExtend}','Administrative\CrmController@updatePositionMechanism')
->name('crm.updatePositionMechanism')
->middleware('can:crms.plans');

Route::put('crm/renumber/updateReNumber/{fileExtend}','Administrative\CrmController@updateReNumber')
->name('crm.updateReNumber')
->middleware('can:crms.plans');

Route::get('crm/record/{fileExtend}','Administrative\CrmController@record')
->name('crm.recordFileExtend')
->middleware('can:crms.plans');

Route::get('crm/consolidated','Administrative\CrmController@consolidated')
->name('crm.consolidated')
->middleware('can:crms.plans');

Route::get('crm/selectQr','Administrative\CrmController@selectQr')
->name('crm.recordFileExtend')
->middleware('can:crms.plans');

Route::get('crm/associateQr/{fileExtend}','Administrative\CrmController@associateQr')
->name('crm.associateQrFileExtend')
->middleware('can:crms.plans');

Route::get('crm/replaceMechanism/{fileExtend}','Administrative\CrmController@replaceMechanism')
->name('crm.replaceMechanism')
->middleware('can:crms.plans');

Route::get('convection/selectMechanims', 'Administrative\CrmController@selectMechanims')
        ->name('convection.selectMechanims')
        ->middleware('can:products.create');
        
Route::put('crm/replaceMechanism/replace/{oldFile}/{newFile}','Administrative\CrmController@replaceMechanismSave')
->name('crm.replaceMechanismSave')
->middleware('can:crms.plans');

Route::put('crm/associateQrUpdate/update','Administrative\CrmController@associateQrUpdate')
->name('crm.associateQrUpdateFileExtend')
->middleware('can:crms.plans');

Route::get('crm/renumber/{fileExtend}','Administrative\CrmController@reNumber')
->name('crm.reNumberFileExtend')
->middleware('can:crms.plans');

Route::get('crm/renumberAutomatic/{fileExtend}','Administrative\CrmController@reCountAutomatic')
->name('crm.reCountAutomaticFileExtend')
->middleware('can:crms.plans');

Route::get('crm/mechanism/update/{extend}','Administrative\CrmController@updateMechanism')
->name('crm.drawSaveMechanism')
->middleware('can:crms.plans');

Route::get('crm/risk/save/{infestation}','Administrative\CrmController@saveRisk')
->name('crm.drawSaveRisk')
->middleware('can:crms.plans');

Route::get('crm/drawRisk','Administrative\CrmController@drawRisks')
->name('crm.drawRisk')
->middleware('can:crms.plans');


Route::get('crm/element/{id}/delete','Administrative\CrmController@deleteElement')
->name('crm.deleteElement')
->middleware('can:crms.plans');



/**Vista show crm lista de la gestion del crm*/
Route::get('crm/{crm}/process-list', 'Administrative\CrmController@listProcessShow')
->name('crms.showProcess')
->middleware('can:crms.show');


/**Vista crear evento en la vista show*/
Route::get('crm/form-process', 'Administrative\CrmController@formProcess')
->name('crms.createEvento')
->middleware('can:crms.show');

/**Ruote del select para consultar os tipos de eventos en el crm vista show*/
Route::get('crm/crm-tracking-type', 'Administrative\CrmController@crmTrackingType')
->name('crms.crmTrackingType')
->middleware('can:crms.create');

/**Guardar los datos del cliente en el metodo editar del crm*/
Route::post('crm/save-event', 'Administrative\CrmController@storeEvent')
->name('crms.storeEvent')
->middleware('can:crms.show');

/**Vista show crm actualizar los datos del registro*/
Route::put('crm/save-event', 'Administrative\CrmController@updateEventShow')
->name('crms.updateEvent')
->middleware('can:crms.show');


/******************************************************************************/

Route::get('crm/save-customer/{crm}', 'Administrative\CrmController@storeUserCustomer')
->name('crms.saveUserCustomer')
->middleware('can:crms.create');

Route::get('crm/save-avatar/{crm}', 'Administrative\CrmController@storeAvatarCustomer')
->name('crms.saveUserCustomer')
->middleware('can:crms.create');

/******************************************************************************/

/**formulario de editar configuracion de facturacion*/
Route::get('crm/form-config-billing', 'Administrative\CrmController@formConfigBilling')
->name('crms.showFormConfigBilling')
->middleware('can:crms.show');

/**Vista show crm lista de configuracion de facturacion*/
Route::get('crm/{crm}/config-billing-list', 'Administrative\CrmController@listConfigBillingShow')
->name('crms.showConfigBilling')
->middleware('can:crms.show');

/**Guardar los datos del cliente en el metodo editar del crm*/
Route::put('crm/save-config-billing', 'Administrative\CrmController@updateConfigBilling')
->name('crms.updateCrm')
->middleware('can:crms.show');



/******************************************************************************/

/**rutas de los select de los filtros*/

Route::get('crms/filters', 'Administrative\CrmController@filters')
->name('crms.filters')
->middleware('can:crms.show');

//rutas del filtro tipos de eventos
Route::get('crms/types-event', 'Administrative\CrmController@crmTrackingType')
->name('crms.TypesEvent')
->middleware('can:crms.show');

//rutas del filtro fecha del evento
Route::get('crms/date-event', 'Administrative\CrmController@crmTrackingDate')
->name('crms.DateEvent')
->middleware('can:crms.show');

//rutas del filtro fecha del evento
Route::get('crms/user-event', 'Administrative\CrmController@crmTrackingUser')
->name('crms.UserEvent')
->middleware('can:crms.show');

Route::post('crms/event', 'Administrative\CrmController@resultFilter')
->name('crms.resultFilter')
->middleware('can:crms.index');



/******************************************************************************/

/**Vista detalles*/
Route::get('crm/{crm}/add-schedule-quote/office/{office}/quote/{quote}', 'Administrative\CrmController@addScheduleFromQuote')
->name('crm.add-schedule-from-quote')
->middleware('can:crms.schedule-create');

Route::get('crm/{crm}/add-schedule-office/{prototypeOffice}/keep-office/{office}', 'Administrative\CrmController@addScheduleFromOffice')
->name('crm.add-schedule-from-office');

/** editar/ver el origen del  cronograma de actividades */
Route::get('crm/{crm}/office/{office}/origin-schedule-edit/workschedule/{workschedule}', 'Administrative\CrmController@editWorkSchedule')
->name('crm.edit-workschedule')
->middleware('can:crms.schedule-create');

Route::get('crm/{crm}/office/{office}/origin-schedule-create', 'Administrative\CrmController@createWorkSchedule')
->name('crm.create-workschedule')
->middleware('can:crms.schedule-create');

/** ir al cronograma de una sucursal */
Route::get('crm/{crm}/office/{office}', 'Administrative\CrmController@gotoSchedule')
->name('crm.gotoSchedule');

/**Vista show crm lista de clientes*/
Route::get('crm/show-list', 'Administrative\CrmController@listCustomerShow')
->name('crms.showCustomer')
->middleware('can:crms.show');


Route::get('crm/{crm}', 'Administrative\CrmController@show')
->name('crms.show')
->middleware('can:crms.show');





/******************************************************************************/

/**Rutas para configuración de areas de clientes*/
Route::get('crms/areas-index', 'Administrative\CrmController@areas')
->name('crms.areas-index')
->middleware('can:crms.areas-index');

Route::get('crms/area-lists', 'Administrative\CrmController@areaLists')
->name('crms.areas-list')
->middleware('can:crms.areas-index');

Route::get('crms/area-form', 'Administrative\CrmController@areaForm')
->name('crms.areas-form')
->middleware(['can:crms.areas-create' || 'can:crms.areas-edit']);

Route::put('crms/area-save', 'Administrative\CrmController@areaUpdate')
->name('crms.area-put')
->middleware(['can:crms.areas-edit']);

Route::post('crms/area-save', 'Administrative\CrmController@areaStore')
->name('crms.area-save')
->middleware(['can:crms.areas-create']);

Route::get('crms/area-delete-form', 'Administrative\CrmController@areaDeleteForm')
->name('crms.area-delete-form')
->middleware(['can:crms.areas-destroy']);

Route::delete('crms/area-delete', 'Administrative\CrmController@areaSoftDelete')
->middleware('can:crms.areas-destroy');

Route::get('crm/{crm}/office/{office}/schedule/quotes', 'Administrative\CrmController@quotes');

Route::get('crm/{crm}/office/{office}/schedule/other-offices', 'Administrative\CrmController@otherOffices');


Route::get('crm/{crm}/scheduling/observations-form', 'Administrative\CrmController@observationForm');


Route::get('crm/{crm}/schedule/observation/{id}/delete', 'Administrative\CrmController@deleteObservation');

Route::get('crms/canvas', 'Administrative\CrmController@canvas');

Route::get('crm/{crm}/reforce/date/{date}/office/{office}/service/{service}', 'Administrative\CrmController@newReforce');

Route::get('crm/expired-notify/{notification}', 'Administrative\CrmController@expiredNotify')->name('crm.expiredNotify');