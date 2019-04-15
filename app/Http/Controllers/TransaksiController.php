<?php


    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Transaksi;
    use App\Barang;
    use App\TransaksiDetail;
    use App\User;

    class TransaksiController extends Controller
    {
        public function __construct()
        {
            //
        }

        public function index()
        {
            return response()->json([
                'data' => TransaksiDetail::orderBy('id', 'DESC')->get(),
                'status' => 'success'
            ], 200);
        }

        public function show($id)
        {
            if (!Transaksi::where('id_transaksi', $id)->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Transaksi tidak ditemukan'
                ], 200);
            } else {
                return response()->json([
                    'data' => Transaksi::with(['detail'])->where('id_transaksi', $id)->first(),
                    'status' => 'success'
                ], 200);
            }
        }

        public function store(Request $request)
        {
            $this->validate($request, [
                'id_barang' => 'required',
                'quantity' => 'required'
            ], [
                'id_barang.required' => 'ID Barang harus diisi',
                'quantity.requird' => 'Jumlah barang harus diisi'
            ]);

            if (!Barang::where('id_barang', '=', $request->id_barang)->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Barang tidak ditemukan'
                ]);
            } else {
                $barang = Barang::find($request->id_barang);

                if ($barang->stok < $request->quantity) {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Barang yang disimpan melebihi stok yang ada'
                    ]);
                } else {
                    if ($barang->stok == 0) {
                        return response()->json([
                            'status' => 'fail',
                            'message' => 'Maaf, stok barang habis.'
                        ]);
                    } else {
                        $user = User::where('token', $request->header('token'))->first();
                        $tanggal_terbaru = Transaksi::whereDate('created_at', date('Y-m-d'))->get();
                        $id_baru = sprintf("%04s", count($tanggal_terbaru) + 1);
                        $id_transaksi = 'TR' . date('dmY') . $id_baru;

                        $transaksi = new Transaksi;
                        $transaksi->id_transaksi = $id_transaksi;
                        $transaksi->id_user = $user->id;
                        $transaksi->total = $barang->harga * $request->quantity;
                        $transaksi->save();

                        $detail = new TransaksiDetail;
                        $detail->id_transaksi = $id_transaksi;
                        $detail->id_barang = $barang->id_barang;
                        $detail->harga = $barang->harga;
                        $detail->qty = $request->quantity;
                        $detail->sub_total = $barang->harga * $request->quantity;
                        $detail->save();

                        $barang->stok = $barang->stok - $request->quantity;
                        $barang->save();

                        return response()->json([
                            'data' => [
                                'barang' => $barang,
                                'transaksi' => $detail
                            ],
                            'status' => 'success',
                            'message' => 'Transaksi berhasil'
                        ], 200);
                    }
                }
            }
        }

        public function destroy($id)
        {
            if (!Transaksi::where('id_transaksi', '=', $id)->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            } else {
                Transaksi::destroy($id);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil dihapus'
                ]);
            }
        }
    }
