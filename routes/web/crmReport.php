<?php
use Illuminate\Support\Facades\Route;

Route::get('crmreport/office/{office}/areas', 'Administrative\CrmReportController@areas');
Route::get('crmreport/technical-type-reports', 'Administrative\CrmReportController@reportTypes');
Route::get('crmreport/controls', 'Administrative\CrmReportController@controls');
Route::get('crmreport/control/{control}/pests', 'Administrative\CrmReportController@pests');
Route::post('crmreport/get-data', 'Administrative\CrmReportController@getData');
Route::get('crmreport/report-services', 'Administrative\CrmReportController@reportServices');

