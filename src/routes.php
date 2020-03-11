<?php

//routes


Route::get('roles', function(){
	//echo 'Hello from the calculator package!2';

  return view('acl::index');
});
// Route::get('/roles/list-roles', 'MsCart\Acl\AclController@index')->name('admin.acl.list_roles');

Route::group(['prefix'  =>  config('app.admin_prefix')], function () {

  Route::group(['middleware' => ['web','auth:admin']], function () {

        Route::get('/acl/list-roles', 'MsCart\Acl\AclController@index')->name('acl.list_roles');
        Route::post('/acl/getRoles','MsCart\Acl\AclController@getRoles')->name('acl.getRoles');
        Route::post('/acl/checkRoleName','MsCart\Acl\AclController@checkRoleName')->name('acl.checkRoleName');
        Route::resource('/acl', 'MsCart\Acl\AclController');

  });

});
