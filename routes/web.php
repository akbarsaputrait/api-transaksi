<?php

    /*
    |--------------------------------------------------------------------------
    | Application Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register all of the routes for an application.
    | It is a breeze. Simply tell Lumen the URIs it should respond to
    | and give it the Closure to call when that URI is requested.
    |
    */

    $router->get('/', function () use ($router) {

    });

    $router->group(['prefix' => '/api'], function () use ($router) {
        // $router->group(['middleware' => 'auth'], function () use ($router) {
            // BARANG
            $router->get('/barang', [
                'uses' => 'BarangController@index',
                'as' => 'barang.index'
            ]);

            $router->post('/barang', [
                'uses' => 'BarangController@store',
                'as' => 'barang.store'
            ]);

            $router->get('/barang/{id}', [
                'uses' => 'BarangController@show',
                'as' => 'barang.show'
            ]);

            $router->put('/barang/{id}', [
                'uses' => 'BarangController@update',
                'as' => 'barang.update'
            ]);

            $router->delete('/barang/{id}', [
                'uses' => 'BarangController@destroy',
                'as' => 'barang.destroy'
            ]);

            // TRANSAKSI
            $router->get('/transaksi', [
                'uses' => 'TransaksiController@index',
                'as' => 'transaksi.index'
            ]);

            $router->get('/transaksi/{id}', [
                'uses' => 'TransaksiController@show',
                'as' => 'transaksi.show'
            ]);

            $router->delete('/transaksi/{id}', [
                'uses' => 'TransaksiController@destroy',
                'as' => 'transaksi.destroy'
            ]);

            $router->post('/transaksi', [
                'uses' => 'TransaksiController@store',
                'as' => 'transaksi.store'
            ]);

            $router->get('/logout', [
                'uses' => 'AuthController@logout',
                'as' => 'logout.store'
            ]);
        // });
        // AUTH
        $router->post('/login', [
            'uses' => 'AuthController@login',
            'as' => 'login.store'
        ]);

        $router->post('/register', [
            'uses' => 'AuthController@register',
            'as' => 'register.store'
        ]);

    });
