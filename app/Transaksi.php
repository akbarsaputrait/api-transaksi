<?php


    namespace App;
    use Illuminate\Database\Eloquent\Model;

    class Transaksi extends Model
    {
        protected $table = 'transaksi';

        public function detail() {
            return $this->hasOne('App\TransaksiDetail', 'id_transaksi', 'id_transaksi');
        }
    }
