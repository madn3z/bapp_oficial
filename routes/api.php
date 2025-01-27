<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

//Tigger Trolo
Route::prefix('v1')->group(function () {

    
    Route::prefix('auth')->group(function () {
        // Below mention routes are public, user can access those without any restriction.
        // Create New User
        Route::post('register', 'AuthController@register');
        // Login User
        Route::post('login', 'AuthController@login');
        
        // Refresh the JWT Token
        Route::get('refresh', 'AuthController@refresh');
        
        // Below mention routes are available only for the authenticated users.
        //Route::middleware('auth:api')->group(function () {
            // Get user info
            Route::get('user', 'AuthController@user');
             // Logout user from application
            Route::post('logout', 'AuthController@logout');

        //});

      
    });
    //USUARIOS
    Route::prefix('users')->group(function () {
       // Route::middleware('auth:api')->group(function () {
            // Usuarios
            Route::get('users', 'UserController@index');
            Route::get('users/create', 'UserController@create');
            Route::get('users/{id}', 'UserController@show');
            Route::delete('users/{id}', 'UserController@destroy');
            Route::post('users/borrar', 'UserController@borrar');
            Route::put('users/{id}', 'UserController@update');
            Route::post('users/store', 'UserController@store');
            Route::get('users/{id}/edit', 'UserController@edit');


     //   });
    });
    //ROLES
    Route::prefix('roles')->group(function () {
      //  Route::middleware('auth:api')->group(function () {
            // Roles
            Route::get('roles', 'RoleController@index');
            Route::get('roles/create', 'RoleController@create');
            Route::get('roles/{id}', 'RoleController@show');
            Route::delete('roles/{id}', 'RoleController@destroy');
            Route::post('roles/borrar', 'RoleController@borrar');
            Route::put('roles/{id}', 'RoleController@update');
            Route::post('roles/store', 'RoleController@store');
            Route::get('roles/{id}/edit', 'RoleController@edit');


      // });
    });
    //PERMISOS
    Route::prefix('permisos')->group(function () {
     //    Route::middleware('auth:api')->group(function () {
            // Permisos
            Route::get('permisos', 'PermisosController@index');
     //    });
    });


     //CICLO FACTURACION
     Route::prefix('ciclofacturacion')->group(function () {
      //   Route::middleware('auth:api')->group(function () {
            // Ciclo facturacion
            Route::get('ciclofacturacion', 'CiclofacturacionController@index');
            Route::get('ciclofacturacion/create', 'CiclofacturacionController@create');
            Route::get('ciclofacturacion/{id}', 'CiclofacturacionController@show');
            Route::delete('ciclofacturacion/{id}', 'CiclofacturacionController@destroy');
            Route::post('ciclofacturacion/borrar', 'CiclofacturacionController@borrar');
            Route::put('ciclofacturacion/{id}', 'CiclofacturacionController@update');
            Route::post('ciclofacturacion/store', 'CiclofacturacionController@store');
            Route::get('ciclofacturacion/{id}/edit', 'CiclofacturacionController@edit');


      //  });
    });

     //CICLO PRODUCCION
     Route::prefix('cicloproduccion')->group(function () {
      //   Route::middleware('auth:api')->group(function () {
            // Ciclo produccion
            Route::get('cicloproduccion', 'CicloproduccionController@index');
            Route::get('cicloproduccion/create', 'CicloproduccionController@create');
            Route::get('cicloproduccion/{id}', 'CicloproduccionController@show');
            Route::delete('cicloproduccion/{id}', 'CicloproduccionController@destroy');
            Route::post('cicloproduccion/borrar', 'CicloproduccionController@borrar');
            Route::put('cicloproduccion/{id}', 'CicloproduccionController@update');
            Route::post('cicloproduccion/store', 'CicloproduccionController@store');
            Route::get('cicloproduccion/{id}/edit', 'CicloproduccionController@edit');


     //   });
    });

    //EMPRESA
    Route::prefix('empresa')->group(function () {
     //    Route::middleware('auth:api')->group(function () {
            // Empresa
            Route::get('empresa', 'EmpresaController@index');
            Route::get('empresa/create', 'EmpresaController@create');
            Route::get('empresa/{id}', 'EmpresaController@show');
            Route::delete('empresa/{id}', 'EmpresaController@destroy');
            Route::post('empresa/borrar', 'EmpresaController@borrar');
            Route::put('empresa/{id}', 'EmpresaController@update');
            Route::post('empresa/store', 'EmpresaController@store');
            Route::get('empresa/{id}/edit', 'EmpresaController@edit');


     //   });
    });

    //EXCEPCIONES
    Route::prefix('excepciones')->group(function () {
     //    Route::middleware('auth:api')->group(function () {
            // Excepciones
            Route::get('excepciones', 'ExcepcionesController@index');
            Route::get('excepciones/create', 'ExcepcionesController@create');
            Route::get('excepciones/{id}', 'ExcepcionesController@show');
            Route::delete('excepciones/{id}', 'ExcepcionesController@destroy');
            Route::post('excepciones/borrar', 'ExcepcionesController@borrar');
            Route::put('excepciones/{id}', 'ExcepcionesController@update');
            Route::post('excepciones/store', 'ExcepcionesController@store');
            Route::get('excepciones/{id}/edit', 'ExcepcionesController@edit');


     //   });
    });

     //FUERA ZONA
     Route::prefix('fuerazonas')->group(function () {
      //   Route::middleware('auth:api')->group(function () {
            // Fuera Zona
            Route::get('fuerazonas', 'FuerazonaController@index');
            Route::get('fuerazonas/create', 'FuerazonaController@create');
            Route::get('fuerazonas/{id}', 'FuerazonaController@show');
            Route::delete('fuerazonas/{id}', 'FuerazonaController@destroy');
            Route::post('fuerazonas/borrar', 'FuerazonaController@borrar');
            Route::put('fuerazonas/{id}', 'FuerazonaController@update');
            Route::post('fuerazonas/store', 'FuerazonaController@store');
            Route::get('fuerazonas/{id}/edit', 'FuerazonaController@edit');


       // });
    });

     //OBSERVACIONES
     Route::prefix('observaciones')->group(function () {
      //   Route::middleware('auth:api')->group(function () {
            // Observaciones
            Route::get('observaciones', 'ObservacionesController@index');
            Route::get('observaciones/create', 'ObservacionesController@create');
            Route::get('observaciones/{id}', 'ObservacionesController@show');
            Route::delete('observaciones/{id}', 'ObservacionesController@destroy');
            Route::post('observaciones/borrar', 'ObservacionesController@borrar');
            Route::put('observaciones/{id}', 'ObservacionesController@update');
            Route::post('observaciones/store', 'ObservacionesController@store');
            Route::get('observaciones/{id}/edit', 'ObservacionesController@edit');


      //  });
    });


    //OBSERVACIONES INTERNAS
    Route::prefix('observacionesinternas')->group(function () {
     //    Route::middleware('auth:api')->group(function () {
            // Observaciones Internas
            Route::get('observacionesinternas', 'ObservacionesinternasController@index');
            Route::get('observacionesinternas/create', 'ObservacionesinternasController@create');
            Route::get('observacionesinternas/{id}', 'ObservacionesinternasController@show');
            Route::delete('observacionesinternas/{id}', 'ObservacionesinternasController@destroy');
            Route::post('observacionesinternas/borrar', 'ObservacionesinternasController@borrar');
            Route::put('observacionesinternas/{id}', 'ObservacionesinternasController@update');
            Route::post('observacionesinternas/store', 'ObservacionesinternasController@store');
            Route::get('observacionesinternas/{id}/edit', 'ObservacionesinternasController@edit');


     //   });
    });

    //RESPONSABLE
    Route::prefix('responsable')->group(function () {
     //    Route::middleware('auth:api')->group(function () {
            // Responsable
            Route::get('responsable', 'ResponsableController@index');
            Route::get('responsable/create', 'ResponsableController@create');
            Route::get('responsable/{id}', 'ResponsableController@show');
            Route::delete('responsable/{id}', 'ResponsableController@destroy');
            Route::post('responsable/borrar', 'ResponsableController@borrar');
            Route::put('responsable/{id}', 'ResponsableController@update');
            Route::post('responsable/store', 'ResponsableController@store');
            Route::get('responsable/{id}/edit', 'ResponsableController@edit');


     //   });
    });

    //SUCURSAL
    Route::prefix('sucursal')->group(function () {
      //   Route::middleware('auth:api')->group(function () {
            // Sucursal
            Route::get('sucursal', 'SucursalController@index');
            Route::get('sucursal/create', 'SucursalController@create');
            Route::get('sucursal/{id}', 'SucursalController@show');
            Route::delete('sucursal/{id}', 'SucursalController@destroy');
            Route::post('sucursal/borrar', 'SucursalController@borrar');
            Route::put('sucursal/{id}', 'SucursalController@update');
            Route::post('sucursal/store', 'SucursalController@store');
            Route::get('sucursal/{id}/edit', 'SucursalController@edit');


      //  });
    });

    //TIPO SERVICIO
    Route::prefix('tiposervicio')->group(function () {
     //    Route::middleware('auth:api')->group(function () {
            // Tipo Servicio
            Route::get('tiposervicio', 'TiposervicioController@index');
            Route::get('tiposervicio/create', 'TiposervicioController@create');
            Route::get('tiposervicio/{id}', 'TiposervicioController@show');
            Route::delete('tiposervicio/{id}', 'TiposervicioController@destroy');
            Route::post('tiposervicio/borrar', 'TiposervicioController@borrar');
            Route::put('tiposervicio/{id}', 'TiposervicioController@update');
            Route::post('tiposervicio/store', 'TiposervicioController@store');
            Route::get('tiposervicio/{id}/edit', 'TiposervicioController@edit');


      //  });
    });

    //MOVILES
    Route::prefix('driver')->group(function () {
       //    Route::middleware('auth:api')->group(function () {
              // Excepciones
              Route::get('driver', 'DriverController@index');
              Route::get('driver/create', 'DriverController@create');
              Route::get('driver/{id}', 'DriverController@show');
              Route::delete('driver/{id}', 'DriverController@destroy');
              Route::post('driver/borrar', 'DriverController@borrar');
              Route::put('driver/{id}', 'DriverController@update');
              Route::post('driver/store', 'DriverController@store');
              Route::get('driver/{id}/edit', 'DriverController@edit');
  
       //   });
      });    
});