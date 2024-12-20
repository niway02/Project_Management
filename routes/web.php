<?php

use App\Http\Controllers\Material2Controller;
use App\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TodosController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpdaterController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MeetingsController;
use App\Http\Controllers\PayslipsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ContractsController;
use App\Http\Controllers\InstallerController;
use App\Http\Middleware\CustomRoleMiddleware;
use App\Http\Controllers\AllowancesController;
use App\Http\Controllers\DeductionsController;
use App\Http\Controllers\WorkspacesController;
use App\Http\Controllers\TimeTrackerController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\TaxesController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\EstimatesInvoicesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\NotificationsController;
use Spatie\Permission\Middlewares\RoleMiddleware;
use App\Http\Controllers\PaymentMethodsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Models\ActivityLog;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\ProjectReportController;
use App\Http\Controllers\TaskReportController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LaborController;
use App\Http\Controllers\MaterialCostController;
use App\Http\Controllers\EquipmentCostController;
use App\Http\Controllers\LaborCostController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SubDepartmentController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\HrmDashboardController;
use App\Http\Controllers\WorkProgressController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\EquipmentInventoriesController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BudgetAllocationController;
use App\Http\Controllers\MasterScheduleController;
use App\Http\Controllers\GanttController;
use App\Http\Controllers\ResourceAllocationController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\DamageController; 
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EquipmentTypeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//---------------------------------------------------------------
/*
Route::get('/update-test', function () {
    $updatePath = Config::get('constants.UPDATE_PATH');
    $sub_directory = (file_exists($updatePath . "plugin/package.json")) ? "plugin/" : "";
    $package_data = file_get_contents($updatePath . $sub_directory . "package.json");
    $package_data = json_decode($package_data, true);
});
*/

Route::get('/clear-cache', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return redirect()->back()->with('message', 'Cache cleared successfully.');
});

Route::get('/create-symlink', function () {
    if (config('constants.ALLOW_MODIFICATION') === 1) {
        $storageLinkPath = public_path('storage');
        if (is_dir($storageLinkPath)) {
            File::deleteDirectory($storageLinkPath);
        }
        Artisan::call('storage:link');
        return redirect()->back()->with('message', 'Symbolik link created successfully.');
    } else {
        return redirect()->back()->with('error', 'This operation is not allowed in demo mode.');
    }
});

Route::get('/phpinfo', function () {
    phpinfo();
})->middleware('multiguard');

Route::get('/install', [InstallerController::class, 'index'])->middleware('guest');

Route::post('/installer/config-db', [InstallerController::class, 'config_db'])->middleware('guest');

Route::post('/installer/install', [InstallerController::class, 'install'])->middleware('guest');


Route::middleware(['CheckInstallation'])->group(function () {

    Route::get('/', [UserController::class, 'login'])->name('login')->middleware('guest');

    Route::post('/users/authenticate', [UserController::class, 'authenticate']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->middleware('guest');

    Route::post('/forgot-password-mail', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('guest');

    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->middleware('guest')->name('password.reset');

    Route::post('/reset-password', [ForgotPasswordController::class, 'ResetPassword'])->middleware('guest')->name('password.update');

    Route::get('/email/verify', [UserController::class, 'email_verification'])->name('verification.notice')->middleware(['auth:web,client']);

    Route::get('/email/verify/{id}/{hash}', [ClientController::class, 'verify_email'])->middleware(['auth:web,client', 'custom.signature'])->name('verification.verify');

    Route::get('/email/verification-notification', [UserController::class, 'resend_verification_link'])->middleware(['auth:web,client', 'throttle:6,1'])->name('verification.send');

    Route::post('/logout', [UserController::class, 'logout'])->middleware(['multiguard']);

    // ,'custom-verified'
    Route::middleware(['multiguard', 'custom-verified'])->group(function () {

        Route::get('/home', [HomeController::class, 'index']);

        Route::get('/home/upcoming-birthdays', [HomeController::class, 'upcoming_birthdays']);

        Route::get('/home/upcoming-work-anniversaries', [HomeController::class, 'upcoming_work_anniversaries']);

        Route::get('/home/members-on-leave', [HomeController::class, 'members_on_leave']);

        //Tasks-----------------------------------------------------------
        // Route to show performance dashboard
        Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');

         // Route to fetch performance data via AJAX
        Route::get('/performance/data', [PerformanceController::class, 'getData'])->name('performance.data');

        Route::middleware(['has_workspace', 'customcan:manage_projects'])->group(function () {


            Route::get('/projects/{type?}', [ProjectsController::class, 'index'])->where('type', 'favorite');  
       
            Route::get('/projects/completed', [ProjectsController::class, 'completed']);
            Route::get('/projects/notStarted', [ProjectsController::class, 'notStarted']);
            Route::get('/projects/inProgress', [ProjectsController::class, 'inProgress']);
            Route::get('/projects/cancelled', [ProjectsController::class, 'cancelled']);


            Route::get('/projects/list/{type?}', [ProjectsController::class, 'list_view'])->where('type', 'favorite');

            Route::get('/projects/completed/list/{type?}', [ProjectsController::class, 'list_view'])->where('type', 'favorite');

            Route::get('/projects/information/{id}', [ProjectsController::class, 'show'])->middleware(['checkAccess:App\Models\Project,projects,id,projects']);

            // Route::get('/projects/create', [ProjectsController::class, 'create'])->middleware(['customcan:create_projects']);

            Route::post('/projects/store', [ProjectsController::class, 'store'])->middleware(['customcan:create_projects', 'log.activity']);

            // Route::get('/projects/edit/{id}', [ProjectsController::class, 'edit'])
            //     ->middleware(['customcan:edit_projects', 'checkAccess:App\Models\Project,projects,id,projects']);

            Route::get('/projects/get/{id}', [ProjectsController::class, 'get'])->middleware(['checkAccess:App\Models\Project,projects,id,projects'])->name('project.get');

            Route::post('/projects/update', [ProjectsController::class, 'update'])->name('projects.update')
                ->middleware(['customcan:edit_projects', 'log.activity']);

            Route::post('/projects/upload-media', [ProjectsController::class, 'upload_media'])
                ->middleware(['log.activity']);

            Route::get('/projects/get-media/{id}', [ProjectsController::class, 'get_media']);

            Route::delete('/projects/delete-media/{id}', [ProjectsController::class, 'delete_media'])
                ->middleware(['customcan:delete_projects', 'log.activity']);


            Route::post('/projects/delete-multiple-media', [ProjectsController::class, 'delete_multiple_media'])
                ->middleware(['customcan:delete_projects', 'log.activity']);

            Route::delete('/projects/destroy/{id}', [ProjectsController::class, 'destroy'])
                ->middleware(['customcan:delete_projects', 'demo_restriction', 'checkAccess:App\Models\Project,projects,id,projects', 'log.activity']);

            Route::post('/projects/destroy_multiple', [ProjectsController::class, 'destroy_multiple'])
                ->middleware(['customcan:delete_projects', 'demo_restriction', 'log.activity']);

            Route::get('/projects/listing/{id?}', [ProjectsController::class, 'list']);

            Route::post('/projects/update-favorite/{id}', [ProjectsController::class, 'update_favorite']);

            Route::get('/projects/duplicate/{id}', [ProjectsController::class, 'duplicate'])
                ->middleware(['customcan:create_projects', 'checkAccess:App\Models\Project,projects,id,projects', 'log.activity']);

            Route::get('/projects/tasks/create/{id}', [TasksController::class, 'create'])
                ->middleware(['customcan:manage_tasks', 'customcan:create_tasks', 'checkAccess:App\Models\Project,projects,id,projects']);

            Route::get('/projects/tasks/edit/{id}', [TasksController::class, 'edit'])
                ->middleware(['customcan:manage_tasks', 'customcan:edit_tasks', 'checkAccess:App\Models\Task,tasks,id,tasks']);

            Route::get('/projects/tasks/list/{id}', [TasksController::class, 'index'])
                ->middleware(['customcan:manage_tasks', 'checkAccess:App\Models\Project,projects,id,projects']);

            Route::get('/projects/tasks/draggable/{id}', [TasksController::class, 'dragula'])
                ->middleware(['customcan:manage_tasks', 'checkAccess:App\Models\Project,projects,id,projects']);

            Route::post('update-project-status', [ProjectsController::class, 'update_status'])
                ->middleware(['customcan:edit_projects', 'log.activity']);

            Route::post('update-project-priority', [ProjectsController::class, 'update_priority'])
                ->middleware(['customcan:edit_projects', 'log.activity']);



            Route::get('/tags/manage', [TagsController::class, 'index']);
            Route::post('/tags/store', [TagsController::class, 'store'])->middleware('log.activity');
            Route::get('/tags/list', [TagsController::class, 'list']);
            Route::get('/tags/get/{id}', [TagsController::class, 'get']);
            Route::post('/tags/update', [TagsController::class, 'update'])->middleware('log.activity');
            Route::get('/tags/get-suggestion', [TagsController::class, 'get_suggestions']);
            Route::post('/tags/get-ids', [TagsController::class, 'get_ids']);
            Route::delete('/tags/destroy/{id}', [TagsController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);
            Route::post('/tags/destroy_multiple', [TagsController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'log.activity']);
        });
        //schedule
          Route::get('schedule', [ScheduleController::class, 'index']);
          Route::get('member/schedule', [ScheduleController::class, 'memberSchedule']);
          Route::get('/master-schedule', [MasterScheduleController::class, 'index'])->name('master-schedule.index');     
          
         // sub task  
          // Route::get('subtasks', [SubtaskController::class, 'index']);
           Route::get('/subtasks', [SubtaskController::class, 'index'])->name('subtasks.all');    
           Route::get('/subtasks/create', [SubtaskController::class, 'create'])->name('subtasks.create');
           Route::post('/subtasks/store', [SubtaskController::class, 'store'])->name('subtasks.store');
//Inventory 
           Route::get('/inventory/dashboard', [InventoryController::class, 'dashboard'])->name('inventory.dashboard');
          // Route::get('materials', [MaterialController::class, 'index']);
          Route::get('materials', [MaterialController::class, 'index'])->name('materials.index');
          Route::get('/materials/data', [MaterialController::class, 'data'])->name('materials.data');

           Route::get('/materials/create', [MaterialController::class, 'create'])->name('materials.create');
           Route::post('/materials/store', [MaterialController::class, 'store'])->name('materials.store');
           
           //Equipment
           Route::get('equipmentcost', [EquipmentCostController::class, 'index']);
           Route::post('/equipments', [EquipmentController::class, 'store'])->name('equipments.store');
           Route::get('/equipments/data', [EquipmentController::class, 'data'])->name('equipments.data');

           Route::get('/equipmentcosts/show/{id}', [EquipmentCostController::class, 'show'])->name('equipmentcosts.show');
           Route::get('/equipmentcosts/create', [EquipmentCostController::class, 'create'])->name('equipmentcosts.create');
           Route::post('/equipmentcosts/store', [EquipmentCostController::class, 'store'])->name('equipmentcosts.store');

           Route::get('/equipment-inventories', [EquipmentInventoriesController::class, 'index'])->name('equipment-inventories.index');
         //Route::get('/materialcosts/show/{id}', 'MaterialCostsController@show')->name('materialcosts.show');
           // equipments
           Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipments.index');

//hrm 
Route::get('/hrm', [HrmDashboardController::class, 'index'])->name('hrm.dashboard');
Route::get('/laborPossition/list', [HrmDashboardController::class, 'list']);

Route::get('/equipments/create', [EquipmentController::class, 'create'])->name('equipments.create');
Route::get('/materialcosts/show/{id}', [MaterialCostController::class, 'show'])->name('materialcosts.show');
Route::get('/materialcosts/create', [MaterialCostController::class, 'create'])->name('materialcosts.create');

Route::get('/materialcosts', [MaterialCostController::class, 'index']);

//site

Route::get('/sites', [SiteController::class, 'index'])->name('site.index');
Route::get('/site/list', [SiteController::class, 'list']);
//work progress


Route::get('/work-progress', [WorkProgressController::class, 'index'])->name('work-progress.index');
Route::get('/work-progress/{user}', [WorkProgressController::class, 'show'])->name('work-progress.show');

//Route::get('/work-progress/detail', [WorkProgressController::class, 'index'])->name('work-progress.inde');
Route::get('/work-progress-detail', [WorkProgressController::class, 'showWorkProgress'])->name('work-progress.showWorkProgress');


Route::get('/materialcosts/materials', [MaterialCostController::class, 'materialcostsSelect'])->name('materialcosts.materialcostsSelect');
//Route::get('/materialAllocation', [MaterialCostController::class, 'materialAllocation'])->name('materialcosts.materialAllocation');

Route::get('/materialcosts/materialAllocation', [MaterialCostController::class, 'materialAllocation'])->name('materialcosts.materialAllocation');
Route::post('/materialcosts/allocate', [MaterialCostController::class, 'storeMaterialAllocation'])->name('materialcosts.allocate');

Route::post('/labor/allocate', [ResourceAllocationController::class, 'storeLaborAllocation'])->name('laborcostsAllocate.store');
//Route::post('/labor-selection', [ResourceAllocationController::class, 'storeLaborAllocation'])->name('labor.selection');
//budget allocation
Route::get('/budget/allocate', [BudgetAllocationController::class, 'index'])->name('index');
Route::get('/projetBudgetData', [BudgetAllocationController::class, 'projetBudgetData'])->name('projetBudgetData');
Route::get('/budgets/overview', [BudgetAllocationController::class, 'budgetOverview'])->name('budgets.overview');
//Route::post('/budget-allocations', [BudgetAllocationController::class, 'storeBudget'])->name('budget.store');
Route::post('/budget/update', [BudgetAllocationController::class, 'updateActualCost'])->name('budget.update');
//Route::post('/budget-allocations/store', [BudgetAllocationController::class, 'store'])->name('budget-allocations.store');
//Route::post('/budget', [BudgetAllocationController::class, 'store'])->name('budget.store');
//Route::get('/budget/create', [BudgetAllocationController::class, 'store'])->name('budget.create');

Route::get('/budget/create', [BudgetAllocationController::class, 'create'])->name('budget.create');
//Route::post('/budget', [BudgetAllocationController::class, 'storeBudget'])->name('budget.store');

Route::get('/budget/{id}', [BudgetAllocationController::class, 'show'])->name('budget.show');
// routes/web.php
//Route::get('/another-page', [MaterialController::class, 'anotherPage'])->name('materialcosts.anotherPage');
//Route::post('/another-page', [MaterialController::class, 'anotherPage'])->name('materialcosts.anotherPage');
//check list
Route::post('/activity/selection', [ActivityController::class,'actvitySellection'])->name('activity.checklist');
//Route::get('/material-selection', [MaterialCostController::class,'materialSelection'])->name('material-selection');

Route::post('/another-page', [MaterialController::class, 'anotherPage'])->name('materialcosts.another-page');
//Route::post('/material-selection', 'YourController@materialSelection')->name('material-selection');
Route::post('/material-selection', [MaterialCostController::class,'materialSelection'])->name('materialcosts.materialSelection');
Route::get('/material-selection', [MaterialCostController::class,'materialSelection'])->name('material-selection');
//Route::post('materialcosts/materials', 'MaterialCostsController@materialcostsSelect')->name('materialcosts.materialcostsSelect');

Route::get('/equipmentAllocation', [EquipmentCostController::class, 'equipmentAllocation'])->name('equipmentcosts.equipmentAllocation');


// Route to create a new activity
Route::post('/tasks/{taskId}/activities', [ActivityController::class, 'store'])->name('tasks.activities.store');

// Route to edit an existing activity
Route::get('/activities/{activityId}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
Route::put('/activities/{activityId}', [ActivityController::class, 'update'])->name('activities.update');

// Route to delete an activity
Route::delete('/activities/{activityId}', [ActivityController::class, 'destroy'])->name('activities.destroy');

Route::get('/equipments/{id}', [EquipmentController::class, 'show'])->name('equipments.show');
Route::get('/equipments/{id}/edit', [EquipmentController::class, 'edit'])->name('equipments.edit');
Route::put('/equipments/{id}', [EquipmentController::class, 'update'])->name('equipments.update');
Route::delete('/equipments/{id}', [EquipmentController::class, 'destroy'])->name('equipments.destroy');

Route::get('/materialCostData', [BudgetAllocationController::class,'getMaterialCosts'])->name('material-costs.getMaterialCosts');


           Route::get('labors', [LaborController::class, 'index']);
           Route::get('employee_possition', [LaborController::class, 'laborPossition']);
           Route::get('/labor/list', [LaborController::class, 'laborList']);  
           Route::get('/labor-possition/list', [LaborController::class, 'laborPossitionList']);  

           
           Route::get('/laborcosts/create', [LaborCostController::class, 'create'])->name('laborcosts.create');
           Route::get('/laborcosts/show/{id}', [LaborCostController::class, 'show'])->name('laborcosts.show');
           Route::post('/laborcosts/store', [LaborCostController::class, 'store'])->name('laborcosts.store');

Route::get('/laborAllocation', [LaborCostController::class, 'laborAllocation'])->name('laborcosts.laborAllocation');
 Route::post('/labor-selection', [LaborCostController::class,'laborSelection'])->name('laborcosts.laborSelection');

  //material cost 
  Route::get('/materialcosts', [MaterialCostController::class, 'index'])->name('materialsost.all');
  Route::post('/materialcosts/store', [MaterialCostController::class, 'store'])->name('materialcosts.store');
  Route::get('stores', [StoreController::class, 'index']);

  
Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
Route::resource('/material2', Material2Controller::class);

  
Route::get('/transfer', [TransferController::class, 'index'])->name('transfer.index');
Route::get('/incoming/transfer', [TransferController::class, 'incomingTransfer'])->name('incoming.transfer');
Route::get('/transfer/list', [TransferController::class, 'transferList']);  
Route::get('/transfer/submit', [TransferController::class, 'submitTransfers']);  
Route::post('/transfer/submit', [TransferController::class, 'submitTransfers'])->name('transfer.submit');
Route::post('/transfer/store', [TransferController::class, 'transferStore'])->name('transfer.store');

Route::get('/purchase', [WarehouseController::class, 'index'])->name('warehouses.index');
Route::get('/inventory-history', [WarehouseController::class, 'index'])->name('warehouses.index');
//Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
Route::get('/warehouses/data', [WarehouseController::class, 'data'])->name('warehouses.data');
Route::get('/warehouseData', [WarehouseController::class, 'warehouseData'])->name('warehouses.warehouseData');
//Route::get('/warehouses/{warehouse}/show', [WarehouseController::class, 'show'])->name('warehouses.show');
//Route::get('/warehouses/{warehouse}',[WarehouseController::class, 'show'])->name('warehouses.show');
//Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
//Route::get('/warehouses/{id}',[WarehouseController::class, 'show'])->name('warehouses.show');

Route::get('/mywarehouse',[WarehouseController::class, 'myWarehouses'])->name('warehouse.my');
Route::get('/warehouse/{id}',[WarehouseController::class, 'show'])->name('warehouses.show');
Route::get('/warehousesEquipments',[WarehouseController::class, 'warehousesEquipments'])->name('warehouses.warehousesEquipments');
Route::get('/warehousesMaterials',[WarehouseController::class, 'warehousesMaterials'])->name('warehouses.warehousesMaterials');
Route::get('/warehousesLabors',[WarehouseController::class, 'warehousesLabors'])->name('warehouses.warehousesLabors');


Route::get('warehouses/export/pdf', 'WarehouseController@exportPdf')->name('warehouses.export.pdf');
Route::get('warehouses/export/xlsx', 'WarehouseController@exportXlsx')->name('warehouses.export.xlsx');
Route::get('warehouses/export/csv', 'WarehouseController@exportCsv')->name('warehouses.export.csv');
Route::get('warehouses/import', 'WarehouseController@showImportForm')->name('warehouses.import');
Route::post('warehouses/import', 'WarehouseController@importWarehouses')->name('warehouses.import.process');

Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('employees/data', [EmployeeController::class, 'data'])->name('employees.data');

            Route::middleware(['has_workspace', 'customcan:manage_tasks,manage_projects'])->group(function () {
            Route::get('/status/manage', [StatusController::class, 'index']);
            Route::post('/status/store', [StatusController::class, 'store'])->middleware(['demo_restriction', 'log.activity']);
            Route::get('/status/list', [StatusController::class, 'list']);
            Route::post('/status/update', [StatusController::class, 'update'])->middleware(['demo_restriction', 'log.activity']);
            Route::get('/status/get/{id}', [StatusController::class, 'get']);
            Route::delete('/status/destroy/{id}', [StatusController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);
            Route::post('/status/destroy_multiple', [StatusController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'log.activity']);

            Route::get('/priority/manage', [PriorityController::class, 'index']);
            Route::post('/priority/store', [PriorityController::class, 'store'])->middleware(['demo_restriction', 'log.activity']);
            Route::get('/priority/list', [PriorityController::class, 'list']);
            Route::post('/priority/update', [PriorityController::class, 'update'])->middleware(['demo_restriction', 'log.activity']);
            Route::get('/priority/get/{id}', [PriorityController::class, 'get']);
            Route::delete('/priority/destroy/{id}', [PriorityController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);
            Route::post('/priority/destroy_multiple', [PriorityController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'log.activity']);
        });

        Route::middleware(['has_workspace', 'customcan:manage_milestones'])->group(function () {
            Route::post('/projects/store-milestone', [ProjectsController::class, 'store_milestone'])->middleware('log.activity');
            Route::get('/projects/get-milestones/{id}', [ProjectsController::class, 'get_milestones']);
            Route::get('/projects/get-milestone/{id}', [ProjectsController::class, 'get_milestone']);
            Route::post('/projects/update-milestone', [ProjectsController::class, 'update_milestone'])->middleware('log.activity');
            Route::delete('/projects/delete-milestone/{id}', [ProjectsController::class, 'delete_milestone'])->middleware(['demo_restriction', 'log.activity']);
            Route::post('/projects/delete-multiple-milestone', [ProjectsController::class, 'delete_multiple_milestones'])->middleware(['demo_restriction', 'log.activity']);
        });

        //Transfer
     //   Route::get('transfer', [TransferController::class, 'index'])->name('index');
        //Departments
     //   Route::resource('subdepartments', 'SubDepartmentController');
        Route::get('/departments ', [DepartmentController::class, 'index']);
        Route::get('/subdepartments', [SubDepartmentController::class, 'index']);
        Route::post('/department/store', [DepartmentController::class, 'store'])->name('departments.store');

        //Tasks-------------------------------------------------------------
        Route::get('user/task', [TasksController::class, 'userTask']);
        Route::get('/user/activity', [TasksController::class, 'userActivity']);
        Route::get('/usertasks/list/{id?}', [TasksController::class, 'userTasklist']);
        Route::get('/userActivities/list/{id?}', [TasksController::class, 'userActivitylist']);
        Route::get('/masterScheduleController/data',[MasterScheduleController::class, 'data']);
        
        Route::middleware(['has_workspace', 'customcan:manage_tasks'])->group(function () {

            Route::get('/tasks', [TasksController::class, 'index']);
            Route::get('/tasks/completed', [TasksController::class, 'completed']);
            Route::get('/tasks/notStarted', [TasksController::class, 'notStarted']);
            Route::get('/tasks/inProgress', [TasksController::class, 'inProgress']);
            Route::get('/tasks/cancelled', [TasksController::class, 'cancelled']);


            Route::get('/tasks/information/{id}', [TasksController::class, 'show'])
                ->middleware(['checkAccess:App\Models\Task,tasks,id,tasks']);

            // Route::get('/tasks/create', [TasksController::class, 'create'])
            //     ->middleware(['customcan:create_tasks']);

            Route::post('/tasks/store', [TasksController::class, 'store'])
                ->middleware(['customcan:create_tasks', 'log.activity']);

            Route::get('/tasks/duplicate/{id}', [TasksController::class, 'duplicate'])
                ->middleware(['customcan:create_tasks', 'checkAccess:App\Models\Task,tasks,id,tasks', 'log.activity']);

            Route::get('/tasks/get/{id}', [TasksController::class, 'get'])->middleware(['checkAccess:App\Models\Task,tasks,id,tasks'])->name('task.get');

            // Route::get('/tasks/edit/{id}', [TasksController::class, 'edit'])
            //     ->middleware(['customcan:edit_tasks', 'checkAccess:App\Models\Task,tasks,id,tasks']);

            Route::post('/tasks/update', [TasksController::class, 'update'])->name('tasks.update')
                ->middleware(['customcan:edit_tasks', 'log.activity']);


            Route::post('/tasks/upload-media', [TasksController::class, 'upload_media'])
                ->middleware(['log.activity']);

            Route::get('/tasks/get-media/{id}', [TasksController::class, 'get_media']);

            Route::delete('/tasks/delete-media/{id}', [TasksController::class, 'delete_media'])
                ->middleware(['customcan:delete_tasks', 'log.activity']);


            Route::post('/tasks/delete-multiple-media', [TasksController::class, 'delete_multiple_media'])
                ->middleware(['customcan:delete_tasks', 'log.activity']);

            Route::delete('/tasks/destroy/{id}', [TasksController::class, 'destroy'])
                ->middleware(['customcan:delete_tasks', 'demo_restriction', 'checkAccess:App\Models\Task,tasks,id,tasks', 'log.activity']);


            Route::post('/tasks/destroy_multiple', [TasksController::class, 'destroy_multiple'])->middleware(['customcan:delete_tasks', 'demo_restriction', 'log.activity']);

            Route::get('/tasks/list/{id?}', [TasksController::class, 'list']);

            Route::get('/tasks/draggable', [TasksController::class, 'dragula']);

            Route::put('/tasks/{id}/update-status/{status}', [TasksController::class, 'updateStatus'])->middleware(['customcan:edit_tasks', 'log.activity']);

            Route::post('update-task-status', [TasksController::class, 'update_status'])
                ->middleware(['customcan:edit_tasks', 'log.activity']);

            Route::post('update-task-priority', [TasksController::class, 'update_priority'])
                ->middleware(['customcan:edit_tasks', 'log.activity']);
        });

        //Meetings-------------------------------------------------------------
        Route::middleware(['has_workspace', 'customcan:manage_meetings'])->group(function () {

            Route::get('/meetings', [MeetingsController::class, 'index']);

            Route::get('/meetings/create', [MeetingsController::class, 'create'])->middleware(['customcan:create_meetings']);

            Route::post('/meetings/store', [MeetingsController::class, 'store'])->middleware(['customcan:create_meetings', 'log.activity']);

            Route::get('/meetings/list', [MeetingsController::class, 'list']);

            Route::get('/meetings/edit/{id}', [MeetingsController::class, 'edit'])
                ->middleware(['customcan:edit_meetings', 'checkAccess:App\Models\Meeting,meetings,id,meetings']);

            Route::put('/meetings/update/{id}', [MeetingsController::class, 'update'])
                ->middleware(['customcan:edit_meetings', 'checkAccess:App\Models\Meeting,meetings,id,meetings', 'log.activity']);

            Route::delete('/meetings/destroy/{id}', [MeetingsController::class, 'destroy'])
                ->middleware(['customcan:delete_meetings', 'demo_restriction', 'checkAccess:App\Models\Meeting,meetings,id,meetings', 'log.activity']);

            Route::post('/meetings/destroy_multiple', [MeetingsController::class, 'destroy_multiple'])
                ->middleware(['customcan:delete_meetings', 'demo_restriction', 'log.activity']);

            Route::get('/meetings/join/{id}', [MeetingsController::class, 'join'])
                ->middleware(['checkAccess:App\Models\Meeting,meetings,id,meetings']);

            Route::get('/meetings/duplicate/{id}', [MeetingsController::class, 'duplicate'])
                ->middleware(['customcan:create_meetings', 'checkAccess:App\Models\Meeting,meetings,id,meetings', 'log.activity']);
        });

        //Workspaces-------------------------------------------------------------
        Route::middleware(['customcan:manage_workspaces'])->group(function () {

            Route::get('/workspaces', [WorkspacesController::class, 'index']);

            Route::get('/workspaces/create', [WorkspacesController::class, 'create'])->middleware(['customcan:create_workspaces']);

            Route::post('/workspaces/store', [WorkspacesController::class, 'store'])->middleware(['customcan:create_workspaces', 'log.activity']);

            Route::get('/workspaces/duplicate/{id}', [WorkspacesController::class, 'duplicate'])
                ->middleware(['customcan:create_workspaces', 'checkAccess:App\Models\Workspace,workspaces,id,workspaces', 'log.activity']);

            Route::get('/workspaces/list', [WorkspacesController::class, 'list']);

            Route::get('/workspaces/edit/{id}', [WorkspacesController::class, 'edit'])
                ->middleware(['customcan:edit_workspaces', 'checkAccess:App\Models\Workspace,workspaces,id,workspaces']);

            Route::put('/workspaces/update/{id}', [WorkspacesController::class, 'update'])
                ->middleware(['customcan:edit_workspaces', 'demo_restriction', 'checkAccess:App\Models\Workspace,workspaces,id,workspaces', 'log.activity']);

            Route::delete('/workspaces/destroy/{id}', [WorkspacesController::class, 'destroy'])
                ->middleware(['customcan:delete_workspaces', 'demo_restriction', 'checkAccess:App\Models\Workspace,workspaces,id,workspaces', 'log.activity']);

            Route::post('/workspaces/destroy_multiple', [WorkspacesController::class, 'destroy_multiple'])
                ->middleware(['customcan:delete_workspaces', 'demo_restriction', 'log.activity']);

            Route::get('/workspaces/switch/{id}', [WorkspacesController::class, 'switch'])
                ->middleware(['checkAccess:App\Models\Workspace,workspaces,id,workspaces']);
        });
        Route::get('/workspaces/remove_participant', [WorkspacesController::class, 'remove_participant'])->middleware(['demo_restriction']);

        //Todos-------------------------------------------------------------
        Route::middleware(['has_workspace'])->group(function () {

            Route::get('/todos', [TodosController::class, 'index']);

            Route::get('/todos/create', [TodosController::class, 'create']);

            Route::post('/todos/store', [TodosController::class, 'store'])->middleware(['log.activity']);

            Route::get('/todos/edit/{id}', [TodosController::class, 'edit']);

            Route::post('/todos/update', [TodosController::class, 'update'])->name('todos.update')->middleware(['log.activity']);

            Route::put('/todos/update_status', [TodosController::class, 'update_status'])->middleware(['log.activity']);

            Route::delete('/todos/destroy/{id}', [TodosController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);

            Route::get('/todos/get/{id}', [TodosController::class, 'get']);


            Route::get('/notes', [NotesController::class, 'index']);

            Route::post('/notes/store', [NotesController::class, 'store'])->middleware('log.activity');

            Route::post('/notes/update', [NotesController::class, 'update'])->middleware('log.activity');

            Route::get('/notes/get/{id}', [NotesController::class, 'get']);

            Route::delete('/notes/destroy/{id}', [NotesController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);
        });

        //Users-------------------------------------------------------------

        Route::get('account/{user}', [ProfileController::class, 'show'])->name('profile.show');

        Route::put('/profile/update_photo/{userOrClient}', [ProfileController::class, 'update_photo']);

        Route::put('profile/update/{userOrClient}', [ProfileController::class, 'update'])->name('profile.update')->middleware(['demo_restriction']);

        Route::delete('/account/destroy/{user}', [ProfileController::class, 'destroy'])->middleware(['demo_restriction']);

        Route::middleware(['has_workspace', 'customcan:manage_users'])->group(function () {

            Route::get('/users', [UserController::class, 'index']);

            Route::get('/users/create', [UserController::class, 'create'])->middleware(['customcan:create_users']);

            Route::post('/users/store', [UserController::class, 'store'])->middleware(['customcan:create_users', 'log.activity']);

            Route::get('/users/profile/{id}', [UserController::class, 'show']);

            Route::get('/users/edit/{id}', [UserController::class, 'edit_user'])->middleware(['customcan:edit_users']);

            Route::put('/users/update_user/{user}', [UserController::class, 'update_user'])->middleware(['customcan:edit_users', 'demo_restriction', 'log.activity']);

            Route::delete('/users/delete_user/{user}', [UserController::class, 'delete_user'])->middleware(['customcan:delete_users', 'demo_restriction', 'log.activity']);

            Route::post('/users/delete_multiple_user', [UserController::class, 'delete_multiple_user'])->middleware(['customcan:delete_users', 'demo_restriction', 'log.activity']);

            Route::get('/users/list', [UserController::class, 'list']);
        });

        //Clients-------------------------------------------------------------

        Route::middleware(['has_workspace', 'customcan:manage_clients'])->group(function () {

            Route::get('/clients', [ClientController::class, 'index']);

            Route::get('/clients/profile/{id}', [ClientController::class, 'show']);

            Route::get('/clients/create', [ClientController::class, 'create'])->middleware(['customcan:create_clients']);

            Route::post('/clients/store', [ClientController::class, 'store'])->middleware(['customcan:create_clients', 'log.activity']);

            Route::get('/clients/get/{id}', [ClientController::class, 'get']);

            Route::get('/clients/edit/{id}', [ClientController::class, 'edit'])->middleware(['customcan:edit_clients']);

            Route::put('/clients/update/{id}', [ClientController::class, 'update'])->middleware(['customcan:edit_clients', 'demo_restriction', 'log.activity']);

            Route::delete('/clients/destroy/{id}', [ClientController::class, 'destroy'])->middleware(['customcan:delete_clients', 'demo_restriction', 'log.activity']);

            Route::post('/clients/destroy_multiple', [ClientController::class, 'destroy_multiple'])->middleware(['customcan:delete_clients', 'demo_restriction', 'log.activity']);

            Route::get('/clients/list', [ClientController::class, 'list']);
        });

        //Settings-------------------------------------------------------------
        Route::get("settings/languages/switch/{code}", [LanguageController::class, 'switch']);

        Route::put("settings/languages/set-default", [LanguageController::class, 'set_default']);

        Route::middleware(['customRole:admin'])->group(function () {

            Route::get('/settings/permission/create', [RolesController::class, 'create_permission']);

            Route::get('/settings/permission', [RolesController::class, 'index']);

            Route::delete('/roles/destroy/{id}', [RolesController::class, 'destroy'])->middleware(['demo_restriction']);

            Route::get('/roles/create', [RolesController::class, 'create']);

            Route::post('/roles/store', [RolesController::class, 'store']);

            Route::get('/roles/edit/{id}', [RolesController::class, 'edit']);

            Route::put('/roles/update/{id}', [RolesController::class, 'update']);

            Route::get('/settings/general', [SettingsController::class, 'index']);

            Route::put('/settings/store_general', [SettingsController::class, 'store_general_settings'])->middleware(['demo_restriction']);

            Route::get('/settings/languages', [LanguageController::class, 'index']);

            Route::post('/settings/languages/store', [LanguageController::class, 'store']);

            Route::get("settings/languages/change/{code}", [LanguageController::class, 'change']);

            Route::put("/settings/languages/save_labels", [LanguageController::class, 'save_labels']);

            Route::get("/settings/languages/manage", [LanguageController::class, 'manage']);

            Route::get('/settings/languages/get/{id}', [LanguageController::class, 'get']);

            Route::post('/settings/languages/update', [LanguageController::class, 'update'])->middleware(['demo_restriction']);

            Route::get("/settings/languages/list", [LanguageController::class, 'list']);

            Route::delete("/settings/languages/destroy/{id}", [LanguageController::class, 'destroy'])->middleware(['demo_restriction']);

            Route::post("/settings/languages/destroy_multiple", [LanguageController::class, 'destroy_multiple'])->middleware(['demo_restriction']);

            Route::get('/settings/email', [SettingsController::class, 'email']);

            Route::put('/settings/store_email', [SettingsController::class, 'store_email_settings'])->middleware(['demo_restriction']);

            Route::get('/settings/sms-gateway', [SettingsController::class, 'sms_gateway']);

            Route::put('/settings/store_sms_gateway', [SettingsController::class, 'store_sms_gateway_settings'])->middleware(['demo_restriction']);

            Route::get('/settings/pusher', [SettingsController::class, 'pusher']);

            Route::put('/settings/store_pusher', [SettingsController::class, 'store_pusher_settings'])->middleware(['demo_restriction']);

            Route::get('/settings/media-storage', [SettingsController::class, 'media_storage']);

            Route::put('/settings/store_media_storage', [SettingsController::class, 'store_media_storage_settings'])->middleware(['demo_restriction']);

            Route::get('/settings/templates', [SettingsController::class, 'templates']);

            Route::put('/settings/store_template', [SettingsController::class, 'store_template'])->middleware(['demo_restriction']);

            Route::post('/settings/get-default-template', [SettingsController::class, 'get_default_template']);

            Route::get('/settings/system-updater', [UpdaterController::class, 'index']);

            Route::post('/settings/update-system', [UpdaterController::class, 'update'])->middleware(['demo_restriction']);
        });
        //Report  
        Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('/project/report', [ProjectReportController::class, 'generateReport']);
        Route::get('/task/report', [TaskReportController::class, 'generateReport']);
        Route::post('/tasks/create', [TaskController::class, 'store']);

        // Export Routes
Route::get('/activities/export', [ActivityController::class, 'export'])->name('activities.export');
Route::get('/activities/exportCsv', [ActivityController::class, 'exportCsv'])->name('activities.exportCsv');
         
        

 //Damage
 Route::get('/damages', [DamageController::class, 'index']);
 Route::get('/damages/list', [DamageController::class, 'list']);  
 //Route::post('/dameges/store', [DamageController::class, 'store'])->middleware('log.activity');
 Route::post('/damages/store', [DamageController::class, 'store'])->name('damages.store');
//working on request
// activty  

Route::get('/activity', [ActivityController::class, 'index']);
Route::get('/activity/list', [ActivityController::class, 'index']);
Route::get('/activity/listing/{id?}', [ActivityController::class, 'list']);
//Route::post('/activities/store', [ActiityController::class, 'store'])->name('create_activties');
Route::post('/activities/store', [ActivityController::class, 'store'])->name('activities.store');

//Route::post('/store-selected-materials', [ResourceAllocationController::class,'store_selected_materials'])->name('store_selected_materials');
//Route::post('/tasks/store', [TasksController::class, 'store'])->middleware(['customcan:create_tasks', 'log.activity']);
//Gannt
Route::get('/gantt', [GanttController::class, 'index'])->name('gantt.index');
Route::get('/gantt/data', [GanttController::class, 'data'])->name('gantt.data');
// Resource Allocation routes  
Route::get('/material-allocations/{activity}', [ResourceAllocationController::class,'allMaterialAlloation'])->name('allMaterialAlloation.activity');
Route::get('/equipment-allocations', [ResourceAllocationController::class,'allEquipmentAlloation'])->name('allEquipmentAlloation.activity');

Route::get('/resource-allocation', 'ResourceAllocationController@index')->name('resource-allocation.index');
Route::get('/projects/data', [ResourceAllocationController::class, 'getProjectsData'])->name('projects.data');
Route::get('/resource-task/{id}', [ResourceAllocationController::class, 'resourceTaskAllocation'])->name('resourceTask.show');



Route::get('/resource-allocation/{activityId}', [ResourceAllocationController::class,'equipmentAllocation'])->name('equipmentAlloation.activity');
Route::post('/equipment-selection', [ResourceAllocationController::class,'equipmentSelection'])->name('materialcosts.equipmentSelection');

Route::get('/resource-allocation', 'ResourceAllocationController@index')->name('resource-allocation.index');
Route::get('/material-allocation/{activityId}', [ResourceAllocationController::class,'materialAllocation'])->name('materialAlloation.activity');
//Route::post('/equipment-selection', [ResourceAllocationController::class,'equipmentSelection'])->name('materialcosts.equipmentSelection');

Route::get('/resource-allocation', 'ResourceAllocationController@index')->name('resource-allocation.index');
Route::get('/labor-allocation/{activityId}', [ResourceAllocationController::class,'laborAllocation'])->name('laborAlloation.activity');
Route::get('/equipment-selection', [ResourceAllocationController::class,'equipmentSelection'])->name('materialcosts.equipmentSelection');

Route::get('/resource-allocation/{activity}/edit', 'ActivityController@edit')->name('activity.edit');
Route::patch('/resource-allocation/{activity}', 'ActivityController@update')->name('activity.update');
// edit resource
Route::post('/materialAllocation', [ResourceAllocationController::class, 'updateMaterialAllocation'])->name('allocation.update-material');
// equipment allocation
Route::post('/store-selected-materials', [ResourceAllocationController::class,'store_selected_materials'])->name('store_selected_materials');
Route::get('/equipment/{equipment}',[ResourceAllocationController::class,'whereEquipment'])->name('equipment.where');
Route::get('/material/{material}',[ResourceAllocationController::class,'whereMaterial'])->name('material.where');
Route::get('/labor/{labor}',[ResourceAllocationController::class,'whereLabor'])->name('labor.where');
      //Request 
      Route::get('/requests', [RequestsController::class, 'index'])->name('requests.index');
      Route::get('/request-task/{id}', [RequestsController::class, 'requestResourceByProjectId'])->name('rquestTask.show');
      Route::get('/requests/{id}', [RequestsController::class, 'show'])->name('requests.show');
      Route::get('/requests/create', [RequestsController::class, 'create'])->name('requests.create');
      Route::post('/requests', [RequestsController::class, 'store'])->name('requests.store');
      Route::get('/resource-allocation', [ResourceAllocationController::class, 'index'])->name('master-schedule1.index');
      
Route::get('/material-request-response/{id}', [RequestsController::class, 'specificMaterialRequestResponse'])->name('material.request.response');

//Route::get('/requests-resource', [RequestsController::class, 'request1'])->name('request');
Route::get('/resource-requests', [RequestsController::class, 'resourceRequest'])->name('request');

Route::get('/requests-activity/{activityId}', [RequestsController::class,'requestResource'])->name('request.activity');
//Route::post('/selelct-material-request', [MaterialCostController::class,'materialRequestSelection'])->name('sellect-material.request');
Route::post('/labor-request-selection',[RequestsController::class,'laborRequestSelection'])->name('sellect-labor.request');
Route::get('/labor-request-selection',[RequestsController::class,'laborRequestSelection'])->name('sellect-labor.request');

Route::post('/material-request-selection',[RequestsController::class,'materialRequestSelection'])->name('sellect-material.request');
Route::get('/material-request-selection',[RequestsController::class,'materialRequestSelection'])->name('sellect-material.request');
//Route::post('/request/selected', [RequestsController::class, 'storeMaterialRequest'])->name('materialRequest.allocate');
Route::post('/material-request',  [RequestsController::class, 'storeMaterialRequest'])->name('material-request.store');
Route::post('/labor-request',  [RequestsController::class, 'storeLaborRequest'])->name('labor-request.store');


//  ResourceAllocationController
//Route::get('/request/equipment', [RequestsController::class, 'requestEquipment'])->name('requests.requestEquipment');
//Route::get('/request/material', [RequestsController::class, 'requestMaterial'])->name('requests.requestLabor');
//Route::get('/request/labor', [RequestsController::class, 'requestLabor'])->name('requests.requestLabor');


Route::get('/requests-material/{activityId}', [RequestsController::class,'materilRequestById'])->name('requests.material');
Route::get('/req-material-finance/{activityId}', [RequestsController::class,'materilRequestToFinance'])->name('request-material-to-finance');
//materilRequestToFinance
Route::get('/successrequest/{id}', [RequestsController::class,'showMaterialRequestById'])->name('requests.showMaterial');
Route::get('/requests-equipment/{activityId}', [RequestsController::class,'equipmentRequestById'])->name('requests.equipment');
Route::get('/requests-labors/{activityId}', [RequestsController::class,'laborRequestById'])->name('requests.labor');
//Route::get('/requests.equipment/{activityId}', [RequestsController::class,'materilRequestById'])->name('requests.material');
//Route::get('/requests.labor/{activityId}', [RequestsController::class,'materilRequestById'])->name('requests.material');

Route::get('/requests-edit/{activityId}', [RequestsController::class,'requestResource'])->name('requests.markAsRead');
Route::get('/requests.markAsRead/{activityId}', [RequestsController::class,'requestResource'])->name('request.activity');
//Route::get('/requests-activity/{activityId}', [RequestsController::class,'requestResource'])->name('request.activity');
//Route::get('/requests-activity/{activityId}', [RequestsController::class,'requestResource'])->name('request.activity');
//Route::get('/requests-activity/{activityId}', [RequestsController::class,'requestResource'])->name('request.activity');

Route::post('/material-request-response',  [RequestsController::class, 'storeMaterialRequestResponse'])->name('request.store-material-request-response');
Route::post('/finance-approve-request',  [RequestsController::class, 'storeFinanceApprove'])->name('request.store-finace-aprove');
Route::post('/labor-request-response',  [RequestsController::class, 'storeLaborRequestResponse'])->name('request.store-labor-request-response');

//equipment type 

// Index route to display all equipment types
Route::get('equipment_types', [EquipmentTypeController::class, 'index'])->name('equipment_types.index');
Route::get('/equipment_types/list', [EquipmentTypeController::class, 'list']);
Route::get('equipment_types/create', [EquipmentTypeController::class, 'create'])->name('equipment_types.create');
Route::post('equipment_types', [EquipmentTypeController::class, 'store'])->name('equipment_types.store');
Route::get('equipment_types/{equipment_type}', [EquipmentTypeController::class, 'show'])->name('equipment_types.show');
Route::get('equipment_types/{equipment_type}/edit', [EquipmentTypeController::class, 'edit'])->name('equipment_types.edit');
Route::put('equipment_types/{equipment_type}', [EquipmentTypeController::class, 'update'])->name('equipment_types.update');
Route::delete('equipment_types/{equipment_type}', [EquipmentTypeController::class, 'destroy'])->name('equipment_types.destroy');
//labor
Route::post('/labor-selection', [ResourceAllocationController::class,'laborSelection'])->name('labor.selection');

        Route::middleware(['has_workspace'])->group(function () {
            Route::get('/search', [SearchController::class, 'search']);

            Route::middleware(['admin_or_user'])->group(function () {
                Route::get('/leave-requests', [LeaveRequestController::class, 'index']);
                Route::post('/leave-requests/store', [LeaveRequestController::class, 'store'])->middleware('log.activity');
                Route::get('/leave-requests/list', [LeaveRequestController::class, 'list']);  
                Route::get('/leave-requests/get/{id}', [LeaveRequestController::class, 'get']);
                Route::post('/leave-requests/update', [LeaveRequestController::class, 'update'])->middleware(['admin_or_leave_editor', 'log.activity']);
                Route::post('/leave-requests/update-editors', [LeaveRequestController::class, 'update_editors'])->middleware(['customRole:admin']);
                Route::delete('/leave-requests/destroy/{id}', [LeaveRequestController::class, 'destroy'])->middleware(['admin_or_leave_editor', 'demo_restriction', 'log.activity']);
                Route::post('/leave-requests/destroy_multiple', [LeaveRequestController::class, 'destroy_multiple'])->middleware(['admin_or_leave_editor', 'demo_restriction', 'log.activity']);
            });
            Route::middleware(['customcan:manage_contracts'])->group(function () {
                Route::get('/contracts', [ContractsController::class, 'index']);
                Route::post('/contracts/store', [ContractsController::class, 'store'])->middleware(['customcan:create_contracts', 'log.activity']);
                Route::get('/contracts/list', [ContractsController::class, 'list']);
                Route::get('/contracts/get/{id}', [ContractsController::class, 'get'])->middleware(['checkAccess:App\Models\Contract,contracts,id']);
                Route::post('/contracts/update', [ContractsController::class, 'update'])->middleware(['customcan:edit_contracts', 'log.activity']);
                Route::get('/contracts/sign/{id}', [ContractsController::class, 'sign'])->middleware(['checkAccess:App\Models\Contract,contracts,id,contracts', 'log.activity']);
                Route::post('/contracts/create-sign', [ContractsController::class, 'create_sign'])->middleware('log.activity');
                Route::get('/contracts/duplicate/{id}', [ContractsController::class, 'duplicate'])->middleware(['customcan:create_contracts', 'checkAccess:App\Models\Contract,contracts,id,contracts', 'log.activity']);
                Route::delete('/contracts/destroy/{id}', [ContractsController::class, 'destroy'])->middleware(['customcan:delete_contracts', 'demo_restriction', 'checkAccess:App\Models\Contract,contracts,id,contracts', 'log.activity']);
                Route::post('/contracts/destroy_multiple', [ContractsController::class, 'destroy_multiple'])->middleware(['customcan:delete_contracts', 'demo_restriction', 'log.activity']);
                Route::delete('/contracts/delete-sign/{id}', [ContractsController::class, 'delete_sign'])->middleware('log.activity');


                Route::get('/contracts/contract-types', [ContractsController::class, 'contract_types']);
                Route::post('/contracts/store-contract-type', [ContractsController::class, 'store_contract_type'])->middleware('log.activity');
                Route::get('/contracts/contract-types-list', [ContractsController::class, 'contract_types_list']);
                Route::get('/contracts/get-contract-type/{id}', [ContractsController::class, 'get_contract_type']);
                Route::post('/contracts/update-contract-type', [ContractsController::class, 'update_contract_type'])->middleware('log.activity');
                Route::delete('/contracts/delete-contract-type/{id}', [ContractsController::class, 'delete_contract_type'])->middleware(['demo_restriction', 'log.activity']);
                Route::post('/contracts/delete-multiple-contract-type', [ContractsController::class, 'delete_multiple_contract_type'])->middleware(['demo_restriction', 'log.activity']);
            });


            Route::middleware(['customcan:manage_payslips'])->group(function () {
                Route::get('/payslips', [PayslipsController::class, 'index']);
                Route::get('/payslips/create', [PayslipsController::class, 'create'])->middleware(['customcan:create_payslips']);
                Route::post('/payslips/store', [PayslipsController::class, 'store'])->middleware(['customcan:create_payslips', 'log.activity']);
                Route::get('/payslips/list', [PayslipsController::class, 'list']);
                Route::delete('/payslips/destroy/{id}', [PayslipsController::class, 'destroy'])->middleware(['demo_restriction', 'customcan:delete_payslips', 'checkAccess:App\Models\Payslip,payslips,id,payslips', 'log.activity']);
                Route::post('/payslips/destroy_multiple', [PayslipsController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'customcan:delete_payslips', 'log.activity']);
                Route::get('/payslips/duplicate/{id}', [PayslipsController::class, 'duplicate'])->middleware(['customcan:create_payslips', 'checkAccess:App\Models\Payslip,payslips,id,payslips', 'log.activity']);
                Route::get('/payslips/edit/{id}', [PayslipsController::class, 'edit'])->middleware(['customcan:edit_payslips', 'checkAccess:App\Models\Payslip,payslips,id,payslips']);
                Route::post('/payslips/update', [PayslipsController::class, 'update'])->middleware(['customcan:edit_payslips', 'checkAccess:App\Models\Payslip,payslips,id,payslips', 'log.activity']);
                Route::get('/payslips/view/{id}', [PayslipsController::class, 'view'])->middleware(['checkAccess:App\Models\Payslip,payslips,id,payslips']);

                Route::get('/allowances', [AllowancesController::class, 'index']);
                Route::post('/allowances/store', [AllowancesController::class, 'store'])->middleware('log.activity');
                Route::get('/allowances/list', [AllowancesController::class, 'list']);
                Route::get('/allowances/get/{id}', [AllowancesController::class, 'get']);
                Route::post('/allowances/update', [AllowancesController::class, 'update'])->middleware('log.activity');
                Route::delete('/allowances/destroy/{id}', [AllowancesController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);
                Route::post('/allowances/destroy_multiple', [AllowancesController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'log.activity']);

                Route::get('/deductions', [DeductionsController::class, 'index']);
                Route::post('/deductions/store', [DeductionsController::class, 'store'])->middleware('log.activity');
                Route::get('/deductions/get/{id}', [DeductionsController::class, 'get']);
                Route::get('/deductions/list', [DeductionsController::class, 'list']);
                Route::post('/deductions/update', [DeductionsController::class, 'update'])->middleware('log.activity');
                Route::delete('/deductions/destroy/{id}', [DeductionsController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);
                Route::post('/deductions/destroy_multiple', [DeductionsController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'log.activity']);
            });
            Route::get('/time-tracker', [TimeTrackerController::class, 'index'])->middleware(['customcan:manage_timesheet']);
            Route::post('/time-tracker/store', [TimeTrackerController::class, 'store'])->middleware(['customcan:create_timesheet', 'log.activity']);
            Route::post('/time-tracker/update', [TimeTrackerController::class, 'update'])->middleware('log.activity');
            Route::get('/time-tracker/list', [TimeTrackerController::class, 'list'])->middleware(['customcan:manage_timesheet']);
            Route::delete('/time-tracker/destroy/{id}', [TimeTrackerController::class, 'destroy'])->middleware(['customcan:delete_timesheet', 'log.activity']);
            Route::post('/time-tracker/destroy_multiple', [TimeTrackerController::class, 'destroy_multiple'])->middleware(['customcan:delete_timesheet', 'log.activity']);

            Route::middleware(['customcan:manage_activity_log'])->group(function () {
                Route::get('/activity-log', [ActivityLogController::class, 'index']);
                Route::get('/activity-log/list', [ActivityLogController::class, 'list']);
                Route::delete('/activity-log/destroy/{id}', [ActivityLogController::class, 'destroy'])->middleware(['demo_restriction', 'customcan:delete_activity_log']);
                Route::post('/activity-log/destroy_multiple', [ActivityLogController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'customcan:delete_activity_log']);
            });

            Route::middleware(['customcan:manage_estimates_invoices'])->group(function () {

                Route::get('/estimates-invoices', [EstimatesInvoicesController::class, 'index']);
                Route::get('/estimates-invoices/create', [EstimatesInvoicesController::class, 'create'])->middleware(['customcan:create_estimates_invoices']);;
                Route::post('/estimates-invoices/store', [EstimatesInvoicesController::class, 'store'])->middleware(['customcan:create_estimates_invoices', 'log.activity']);
                Route::get('/estimates-invoices/list', [EstimatesInvoicesController::class, 'list']);
                Route::get('/estimates-invoices/edit/{id}', [EstimatesInvoicesController::class, 'edit'])->middleware(['customcan:edit_estimates_invoices', 'checkAccess:App\Models\EstimatesInvoice,estimates_invoices,id,estimates_invoices']);
                Route::get('/estimates-invoices/view/{id}', [EstimatesInvoicesController::class, 'view'])->middleware(['checkAccess:App\Models\EstimatesInvoice,estimates_invoices,id,estimates_invoices']);
                Route::get('/estimates-invoices/pdf/{id}', [EstimatesInvoicesController::class, 'pdf'])->middleware(['checkAccess:App\Models\EstimatesInvoice,estimates_invoices,id,estimates_invoices']);
                Route::post('/estimates-invoices/update', [EstimatesInvoicesController::class, 'update'])->middleware(['customcan:edit_estimates_invoices', 'checkAccess:App\Models\EstimatesInvoice,estimates_invoices,id,estimates_invoices', 'log.activity']);
                Route::get('/estimates-invoices/duplicate/{id}', [EstimatesInvoicesController::class, 'duplicate'])->middleware(['customcan:create_estimates_invoices', 'checkAccess:App\Models\EstimatesInvoice,EstimatesInvoice,id,estimates_invoices', 'log.activity']);
                Route::delete('/estimates-invoices/destroy/{id}', [EstimatesInvoicesController::class, 'destroy'])->middleware(['demo_restriction', 'customcan:delete_estimates_invoices', 'checkAccess:App\Models\EstimatesInvoice,estimates_invoices,id,estimates_invoices', 'log.activity']);
                Route::post('/estimates-invoices/destroy_multiple', [EstimatesInvoicesController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'customcan:delete_estimates_invoices', 'log.activity']);

               // Route::get('/projects/export/pdf', [ProjectsController::class, 'exportPDF'])->name('projects.export.pdf');
//Route::get('/projects/export/csv', [ProjectsController::class, 'exportCSV'])->name('projects.export.csv');
//Route::post('/reports/generate', [ProjectsController::class, 'generateReport'])->name('reports.generate');
Route::get('/projects/export/pdf', [ProjectsController::class, 'exportPdf'])->name('projects.export.pdf');
Route::get('/projects/export/csv', [ProjectsController::class, 'exportCsv'])->name('projects.export.csv');
Route::get('/projects/pdf', [ProjectsController::class, 'pdf'])->name('projects.pdf');
Route::get('report/projects',[MasterScheduleController::class,'projectReports'])->name('master-schedule.report');              
Route::get('/report/generate', [MasterScheduleController::class, 'generateReport'])->name('report.generate');

Route::get('/master-schedule/export', [ProjectsController::class, 'exportPdf'])->name('master-schedule.export');
Route::get('/master-schedule/export-csv', [ProjectsController::class, 'exportCsv'])->name('master-schedule.exportCsv');

                 Route::get('/payment-methods', [PaymentMethodsController::class, 'index']);
                Route::post('/payment-methods/store', [PaymentMethodsController::class, 'store'])->middleware('log.activity');
                Route::get('/payment-methods/list', [PaymentMethodsController::class, 'list']);
                Route::get('/payment-methods/get/{id}', [PaymentMethodsController::class, 'get']);
                Route::post('/payment-methods/update', [PaymentMethodsController::class, 'update'])->middleware('log.activity');
                Route::delete('/payment-methods/destroy/{id}', [PaymentMethodsController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);
                Route::post('/payment-methods/destroy_multiple', [PaymentMethodsController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'log.activity']);


                Route::get('/payments', [PaymentsController::class, 'index']);
                Route::post('/payments/store', [PaymentsController::class, 'store'])->middleware(['customcan:create_estimates_invoices', 'log.activity']);
                Route::get('/payments/list', [PaymentsController::class, 'list']);
                Route::get('/payments/get/{id}', [PaymentsController::class, 'get'])->middleware(['checkAccess:App\Models\Payment,payments,id']);
                Route::post('/payments/update', [PaymentsController::class, 'update'])->middleware(['customcan:edit_estimates_invoices', 'log.activity']);
                Route::get('/expenses/duplicate/{id}', [ExpensesController::class, 'duplicate'])->middleware(['customcan:create_expenses', 'checkAccess:App\Models\Expense,expenses,id,expenses', 'log.activity']);
                Route::delete('/payments/destroy/{id}', [PaymentsController::class, 'destroy'])->middleware(['customcan:delete_estimates_invoices', 'demo_restriction', 'checkAccess:App\Models\Payments,payments,id,payments', 'log.activity']);
                Route::post('/payments/destroy_multiple', [PaymentsController::class, 'destroy_multiple'])->middleware(['customcan:delete_estimates_invoices', 'demo_restriction', 'log.activity']);

                Route::get('/taxes', [TaxesController::class, 'index']);
                Route::post('/taxes/store', [TaxesController::class, 'store'])->middleware('log.activity');
                Route::get('/taxes/get/{id}', [TaxesController::class, 'get']);
                Route::get('/taxes/list', [TaxesController::class, 'list']);
                Route::post('/taxes/update', [TaxesController::class, 'update'])->middleware('log.activity');
                Route::delete('/taxes/destroy/{id}', [TaxesController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);
                Route::post('/taxes/destroy_multiple', [TaxesController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'log.activity']);

                Route::get('/units', [UnitsController::class, 'index']);
                Route::post('/units/store', [UnitsController::class, 'store'])->middleware('log.activity');
                Route::get('/units/get/{id}', [UnitsController::class, 'get']);
                Route::get('/units/list', [UnitsController::class, 'list']);
                Route::post('/units/update', [UnitsController::class, 'update'])->middleware('log.activity');
                Route::delete('/units/destroy/{id}', [UnitsController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);
                Route::post('/units/destroy_multiple', [UnitsController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'log.activity']);


                Route::get('/items', [ItemsController::class, 'index']);
                Route::post('/items/store', [ItemsController::class, 'store'])->middleware('log.activity');
                Route::get('/items/get/{id}', [ItemsController::class, 'get']);
                Route::get('/items/list', [ItemsController::class, 'list']);
                Route::post('/items/update', [ItemsController::class, 'update'])->middleware('log.activity');
                Route::delete('/items/destroy/{id}', [ItemsController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);
                Route::post('/items/destroy_multiple', [ItemsController::class, 'destroy_multiple'])->middleware(['demo_restriction', 'log.activity']);
            });

            Route::middleware(['customcan:manage_expenses'])->group(function () {
                Route::get('/expenses', [ExpensesController::class, 'index']);
                Route::post('/expenses/store', [ExpensesController::class, 'store'])->middleware(['customcan:create_expenses', 'log.activity']);
                Route::get('/expenses/list', [ExpensesController::class, 'list']);
                Route::get('/expenses/get/{id}', [ExpensesController::class, 'get'])->middleware(['checkAccess:App\Models\Expense,expenses,id']);
                Route::post('/expenses/update', [ExpensesController::class, 'update'])->middleware(['customcan:edit_expenses', 'log.activity']);
                Route::get('/expenses/duplicate/{id}', [ExpensesController::class, 'duplicate'])->middleware(['customcan:create_expenses', 'checkAccess:App\Models\Expense,expenses,id,expenses', 'log.activity']);
                Route::delete('/expenses/destroy/{id}', [ExpensesController::class, 'destroy'])->middleware(['customcan:delete_expenses', 'demo_restriction', 'checkAccess:App\Models\Expense,expenses,id,expenses', 'log.activity']);
                Route::post('/expenses/destroy_multiple', [ExpensesController::class, 'destroy_multiple'])->middleware(['customcan:delete_expenses', 'demo_restriction', 'log.activity']);


                Route::get('/expenses/expense-types', [ExpensesController::class, 'expense_types']);
                Route::post('/expenses/store-expense-type', [ExpensesController::class, 'store_expense_type'])->middleware('log.activity');
                Route::get('/expenses/expense-types-list', [ExpensesController::class, 'expense_types_list']);
                Route::get('/expenses/get-expense-type/{id}', [ExpensesController::class, 'get_expense_type']);
                Route::post('/expenses/update-expense-type', [ExpensesController::class, 'update_expense_type'])->middleware('log.activity');
                Route::delete('/expenses/delete-expense-type/{id}', [ExpensesController::class, 'delete_expense_type'])->middleware(['demo_restriction', 'log.activity']);
                Route::post('/expenses/delete-multiple-expense-type', [ExpensesController::class, 'delete_multiple_expense_type'])->middleware(['demo_restriction', 'log.activity']);
            });

            Route::middleware(['customcan:manage_system_notifications'])->group(function () {
                Route::put('/notifications/mark-all-as-read', [NotificationsController::class, 'mark_all_as_read']);
                Route::get('/notifications', [NotificationsController::class, 'index']);
                Route::get('/notifications/list', [NotificationsController::class, 'list']);
                Route::delete('/notifications/destroy/{id}', [NotificationsController::class, 'destroy'])->middleware(['customcan:delete_system_notifications', 'demo_restriction']);
                Route::post('/notifications/destroy_multiple', [NotificationsController::class, 'destroy_multiple'])->middleware(['customcan:delete_system_notifications', 'demo_restriction']);
                Route::put('/notifications/update-status', [NotificationsController::class, 'update_status']);
                Route::get('/notifications/get-unread-notifications', [NotificationsController::class, 'getUnreadNotifications'])->middleware(['customcan:manage_system_notifications']);
            });
        });
    });
});
