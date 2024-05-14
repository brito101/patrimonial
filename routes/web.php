<?php

use App\Http\Controllers\Admin\{
    AdminController,
    UserController,
    ACL\PermissionController,
    ACL\RoleController,
    ChangelogController,
    DepartmentController,
    GroupController,
    MaterialController,
};

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::get('admin', [AdminController::class, 'index'])->name('admin.home');
    Route::prefix('admin')->name('admin.')->group(function () {
        /** Chart home */
        Route::get('/chart', [AdminController::class, 'chart'])->name('home.chart');

        /** Users */
        Route::post('users-import', [UserController::class, 'fileImport'])->name('users.import');
        Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::resource('users', UserController::class)->except('show');

        /** Departments */
        Route::post('departments-import', [DepartmentController::class, 'fileImport'])->name('departments.import');
        Route::resource('departments', DepartmentController::class)->except('show');

        /** Groups */
        Route::post('groups-import', [GroupController::class, 'fileImport'])->name('groups.import');
        Route::resource('groups', GroupController::class)->except('show');

        /** Materials */
        Route::post('materials/batch/write-off', [MaterialController::class, 'batchWriteOff'])->name('materials.batch.write-off');
        Route::post('materials/batch/active', [MaterialController::class, 'batchActive'])->name('materials.batch.active');
        Route::post('materials/batch/delete', [MaterialController::class, 'batchDelete'])->name('materials.batch.delete');

        Route::get('materials/active', [MaterialController::class, 'active'])->name('materials.active');
        Route::get('materials/write-off', [MaterialController::class, 'writeOff'])->name('materials.writeOff');
        Route::resource('materials', MaterialController::class)->except('show');

        /**
         * ACL
         * */
        /** Permissions */
        Route::resource('permission', PermissionController::class);

        /** Roles */
        Route::get('role/{role}/permission', [RoleController::class, 'permissions'])->name('role.permissions');
        Route::put('role/{role}/permission/sync', [RoleController::class, 'permissionsSync'])->name('role.permissionsSync');
        Route::resource('role', RoleController::class);

        /** Changelog */
        Route::get('/changelog', [ChangelogController::class, 'index'])->name('changelog');
    });
});

/** Web */
Route::get('/', function () {
    return redirect('admin');
});

Auth::routes([
    'register' => false,
]);
