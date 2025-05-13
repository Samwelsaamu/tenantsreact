<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\API\AuthLoginController;
use App\Http\Controllers\mpesa\MPesaController;
use App\Http\Controllers\mpesa\MPesaResponseController;

use App\Http\Controllers\API\AdminController;

use App\Http\Controllers\API\MailController;
use App\Http\Controllers\MiscController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login',[AuthLoginController::class,'login']);
Route::post('account/verify',[AuthLoginController::class,'verifyAccountCode']);
Route::post('account/2fa',[AuthLoginController::class,'verify2FACode']);


Route::post('password/reset',[AuthLoginController::class,'forgotPassword']);
Route::post('password/verify',[AuthLoginController::class,'verifyResetPassword']);



// getDashStats  error.response is undefined

// ,'role:admin'

// Route::post('user/assign/role',[AuthLoginController::class,'assignUserToRole']);


Route::group(['middleware' => ['auth:sanctum','twofactorverified']], function (){
    Route::get('isAuthencticated',function (){
        return response()->json(['message'=>'Yes','status'=>200],200);
    });
    

    Route::get('update/waterbill/load',[AdminController::class,'setWaterbillPageInitial'])->middleware("verified");
    Route::get('update/waterbill/load/{id}/{month}',[AdminController::class,'setWaterbillPage'])->middleware("verified");
    Route::post('update/waterbill/preview',[AdminController::class,'previewWaterbill'])->middleware("verified");

    Route::get('update/rent/load/{id}/{month}',[AdminController::class,'setRentPage'])->middleware("verified");
    
    Route::get('update/monthlybills/load/{id}/{month}',[AdminController::class,'setMonthlyBillsPage'])->middleware("verified");
    Route::get('update/newtenant/monthlybills/load/{id}/{month}',[AdminController::class,'setNewTenantBillsPage'])->middleware("verified");
    Route::get('update/newtenant/monthlybills/load/{month}',[AdminController::class,'setNewTenantBillsPageInitial'])->middleware("verified");
    

    Route::get('update/refunds/load/{id}/{month}',[AdminController::class,'setRefundPage'])->middleware("verified");
    Route::get('update/refunds/load/{month}',[AdminController::class,'setRefundPageInitial'])->middleware("verified");

    
    Route::get('update/deposits/load/{id}/{month}',[AdminController::class,'setDepositPage'])->middleware("verified");
    Route::get('update/deposits/load/{month}',[AdminController::class,'setDepositPageInitial'])->middleware("verified");

    Route::get('update/leases/load/{id}/{month}',[AdminController::class,'setLeasePage'])->middleware("verified");
    Route::get('update/leases/load/{month}',[AdminController::class,'setLeasePageInitial'])->middleware("verified");
    
    
    Route::post('save/waterbill/save',[AdminController::class,'savewaterbillnew'])->middleware("verified");
    Route::post('save/waterbill/upload',[AdminController::class,'saveupdatewaterbillupload'])->middleware("verified");
    Route::post('update/waterbill/save',[AdminController::class,'updatewaterbill'])->middleware("verified");

    Route::post('save/rentgarbage/save',[AdminController::class,'saveRentGarbagenew'])->middleware("verified");
    Route::post('save/rentgarbage/upload',[AdminController::class,'saveupdateRentGarbageupload'])->middleware("verified");
    Route::post('update/rentgarbage/save',[AdminController::class,'updateRentGarbage'])->middleware("verified");
    Route::post('generate/rentgarbage/selected',[AdminController::class,'generateRentGarbageSelected'])->middleware("verified");
    Route::post('generate/rentgarbage/all',[AdminController::class,'generateRentGarbageAll'])->middleware("verified");


    
    Route::post('save/monthlybills/save',[AdminController::class,'saveMonthlyBillnew'])->middleware("verified");
    


    Route::get('/properties/manage/load',[AdminController::class,'setManageProperty'])->middleware("verified");
    Route::get('/properties/manage/load/{id}',[AdminController::class,'manageProperty'])->middleware("verified");

    Route::get('/properties/mgr/tenants/load',[AdminController::class,'setManageTenant'])->middleware("verified");
    Route::get('/properties/mgr/tenants/load/{id}',[AdminController::class,'manageTenantIN'])->middleware("verified");


    Route::get('/properties/propertyhousetype/load/{id}',[AdminController::class,'getPropertyHouseType'])->middleware("verified");

    Route::get('/properties/mgr/tenants/category/load',[AdminController::class,'setManageTenantCategory'])->middleware("verified");
    Route::get('/properties/mgr/tenants/category/load/{id}',[AdminController::class,'manageTenantINCategory'])->middleware("verified");

    Route::get('/properties/mgr/tenants/vacate/{hid}/{id}',[AdminController::class,'manageVacateTenant'])->middleware("verified");

    Route::get('/properties/mgr/tenants/assign/{hid}/{id}',[AdminController::class,'manageAssignTenant'])->middleware("verified");
    Route::get('/properties/mgr/tenants/addhouse/{hid}/{id}',[AdminController::class,'manageAddHouseTenant'])->middleware("verified");
    
    Route::get('/properties/mgr/tenants/reassign/{hid}/{id}/{shid}',[AdminController::class,'manageReassignTenant'])->middleware("verified");


    Route::get('/properties/house/{plot}/{id}',[AdminController::class,'manageHouseTenants'])->middleware("verified");

    Route::get('/load/users',[AdminController::class,'getUsers'])->middleware("verified");
    Route::post('save/user',[AuthLoginController::class,'saveUser'])->middleware("verified");
    Route::post('save/change/password',[AuthLoginController::class,'changePassword'])->middleware("verified");
    Route::get('/load/currentuser',[AdminController::class,'getCurrentUser'])->middleware("verified");
    
    Route::get('/load/user/{id}',[AdminController::class,'getSelectedUser'])->middleware("verified");
    Route::get('/load/user/logs/{id}/{limit}',[AdminController::class,'getSelectedUserLogs'])->middleware("verified");

    Route::post('delete/user',[AuthLoginController::class,'deleteUser'])->middleware("verified");
    
    Route::get('get/roles-permissions',[AuthLoginController::class,'getAllRolesPermissions'])->middleware("verified");
    Route::get('get/rolepermissions/{id}',[AuthLoginController::class,'getRolePermissions'])->middleware("verified");
    // Route::get('get/permissionroles/{id}',[AuthLoginController::class,'getPermissionRoles'])->middleware("verified");
    
    Route::get('get/roleusers/{id}',[AuthLoginController::class,'getRoleUsers'])->middleware("verified");
    
    

    Route::post('save/role',[AuthLoginController::class,'saveRole'])->middleware("verified");
    Route::post('save/permission',[AuthLoginController::class,'savePermission'])->middleware("verified");
    // Route::post('assign/permission',[AuthLoginController::class,'assignPermission'])->middleware("verified");

    Route::post('delete/role',[AuthLoginController::class,'deleteRole'])->middleware("verified");
    Route::post('delete/permission',[AuthLoginController::class,'deletePermission'])->middleware("verified");

    Route::post('permission/assign/role',[AuthLoginController::class,'assignPermissionToRole'])->middleware("verified");

    Route::post('user/assign/role',[AuthLoginController::class,'assignUserToRole'])->middleware("verified");

    Route::post('permission/remove/role',[AuthLoginController::class,'removePermissionFromRole'])->middleware("verified");
    
    Route::post('user/remove/role',[AuthLoginController::class,'removeUserFromRole'])->middleware("verified");
    
    
    
    

    

    
    Route::post('save/property/save',[AdminController::class,'saveProperty'])->middleware("verified");
    Route::post('update/property/save',[AdminController::class,'updateProperty'])->middleware("verified");
    Route::post('delete/property/save',[AdminController::class,'deleteProperty'])->middleware("verified");

    Route::post('save/house/save',[AdminController::class,'saveHouse'])->middleware("verified");
    Route::post('update/house/save',[AdminController::class,'updateHouse'])->middleware("verified");
    Route::post('delete/house/save',[AdminController::class,'deleteHouse'])->middleware("verified");

    
    Route::post('save/tenant/save',[AdminController::class,'saveTenant'])->middleware("verified");
    Route::post('update/tenant/save',[AdminController::class,'updateTenant'])->middleware("verified");
    Route::post('delete/tenant/save',[AdminController::class,'deleteTenant'])->middleware("verified");

    Route::post('vacate/house/save',[AdminController::class,'vacateHouse'])->middleware("verified");
    Route::post('assign/house/save',[AdminController::class,'assignHouse'])->middleware("verified");
    Route::post('addhouse/house/save',[AdminController::class,'addTenantHouse'])->middleware("verified");
    Route::post('reassign/house/save',[AdminController::class,'reassignHouse'])->middleware("verified");


    
    Route::post('save/updatehousedetail/save',[AdminController::class,'saveupdateHouseDetail'])->middleware("verified");


    Route::post('save/propertyhousetype/save',[AdminController::class,'savePropertyHouseType'])->middleware("verified");
    Route::post('update/propertyhousetype/save',[AdminController::class,'updatePropertyHouseType'])->middleware("verified");
    Route::post('delete/propertyhousetype/save',[AdminController::class,'deletePropertyHouseType'])->middleware("verified");
    

    Route::post('send/message/new',[AdminController::class,'sendNewMessage'])->middleware("verified");

    Route::post('send/message/single/water',[AdminController::class,'sendSingleWaterMessage'])->middleware("verified");
    Route::post('send/message/all/water',[AdminController::class,'sendAllWaterMessage'])->middleware("verified");

   
    Route::post('send/message/tenant',[AdminController::class,'sendTenantMessage'])->middleware("verified");
    Route::post('send/message/all/tenant',[AdminController::class,'sendAllTenantMessage'])->middleware("verified");

    Route::post('send/message/reminder',[AdminController::class,'sendReminderMessage'])->middleware("verified");
    Route::post('send/message/all/reminder',[AdminController::class,'sendAllReminderMessage'])->middleware("verified");
    
    
    Route::post('delete/message/new/save',[AdminController::class,'deleteNewMessage'])->middleware("verified");

    

    Route::get('dash/month/prev/{month}',[AdminController::class,'getPreviousMonths'])->middleware("verified");
    Route::get('dash/water/{id}',[AdminController::class,'getWaterbill'])->middleware("verified");
    Route::get('dash/payments/{id}',[AdminController::class,'getPayments'])->middleware("verified");
    Route::get('dash/{id}',[AdminController::class,'getDashStats'])->middleware("verified");
    Route::get('dash/stats/{type}/{id}',[AdminController::class,'getDashboardStats'])->middleware("verified");
    Route::get('dash/insights/{month}',[AdminController::class,'getDashboardInsights'])->middleware("verified");
    
    Route::get('dash/insights/rents/{month}',[AdminController::class,'getDashboardInsightsRents'])->middleware("verified");

    Route::get('dash/tests/{type}/{id}',[AdminController::class,'Testing']);
    Route::get('dash/testing/{type}/{text}',[AdminController::class,'TestingSingle']);
    

    Route::get('update/messages/new/load',[AdminController::class, 'getComposedMessages'])->middleware("verified");
    
    Route::get('update/messages/water/load/{id}/{month}',[AdminController::class, 'setWaterbillPageMessages'])->middleware("verified");

    Route::get('update/messages/tenant/load/{id}',[AdminController::class, 'setTenantPageMessages'])->middleware("verified");
    Route::get('update/messages/payment/load/{id}/{month}',[AdminController::class, 'setPaymentPageMessages'])->middleware("verified");
    Route::get('update/messages/reminder/load/{id}/{month}',[AdminController::class, 'setReminderPageMessages'])->middleware("verified");
    

    Route::get('/get/agency',[AdminController::class, 'getSiteData'])->middleware("verified");
    Route::get('/get/agency/msg',[AdminController::class, 'getSiteMsgData'])->middleware("verified");
    Route::get('/getappdata',[AdminController::class, 'getappdata'])->middleware("verified");
    Route::get('search/load/{search}',[AdminController::class,'getSearchResult'])->middleware("verified");

    Route::post('save/agency',[AdminController::class,'saveAgency'])->middleware("verified");
    
    Route::post('save/agencymsg',[AdminController::class,'saveAgencyMsg'])->middleware("verified");
    
    Route::get('downloads/Reports/Waterbill/{id}/{month}',[AdminController::class, 'downloadWaterbillExcel'])->middleware("verified");
    Route::get('downloads/Reports/Waterbill/{id}/{Year}/{month}',[AdminController::class, 'downloadWaterbillPerYearExcel'])->middleware("verified");

    Route::get('downloads/Reports/Payments/{id}/{month}',[AdminController::class, 'downloadPaymentsExcel'])->middleware("verified");
    Route::get('downloads/Reports/Payments/{id}/{Year}/{month}',[AdminController::class, 'downloadPaymentPerYearExcel'])->middleware("verified");


    
    Route::post('logout',[AuthLoginController::class,'logout']);
    
    Route::post('get/roles/permissions',[AuthLoginController::class,'getRolesPermissions'])->middleware("verified");

   
});


Route::get('/get/access/token',[MPesaController::class, 'getAccessToken']);
Route::post('/register-url',[MPesaController::class, 'registerURLs']); 


Route::post('/simulate/c2b',[MPesaController::class, 'simulateTransaction']); 




Route::post('/validation',[MPesaResponseController::class, 'validation']); 
Route::post('/confirmation',[MPesaResponseController::class, 'confirmation']);

Route::get('/site/data',[MiscController::class, 'getSiteData']);
Route::post('/mesages/smsdelivery',[MiscController::class, 'smsDeliveryReports']);

Route::get('/properties/download/Reports/Waterbill/{id}/{month}',[MailController::class, 'downloadwaterbillexcel']);
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



// sandbox api key
// atsk_e2ea0753a6d9a577aa8015915062205f3d9d32f2bbc07f3205dc8dae99195f8b82def8ba

// {"status":"success","data":{"SMSMessageData":{"Message":"Sent to 2\/2 Total Cost: KES 4.8000 Message parts: 3","Recipients":[{"cost":"KES 2.4000","messageId":"ATXid_a095fe36f792e03fa063fd6816f9064f","number":"+254102782731","status":"Success","statusCode":101},{"cost":"KES 2.4000","messageId":"ATXid_3abf8670300ada1cbe63435c0c5ab60d","number":"+254725025536","status":"Success","statusCode":101}]}}}