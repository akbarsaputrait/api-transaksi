<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Barang;


    class BarangController extends Controller
    {
        public function __construct(Request $request)
        {
            //
        }

        public function index()
        {
            return response()->json([
                'data' => Barang::orderBy('id_barang', 'DESC')->get(),
                'status' => 'success'
            ], 200);
        }

        public function store(Request $request)
        {
            $this->validate($request, [
                'nama_barang' => 'required|unique:barang,nama_barang',
                'harga' => 'required',
                'stok' => 'required'
            ], [
                'nama_barang.required' => 'Nama barang harus diisi',
                'harga.required' => 'Harga harus diisi',
                'stok.required' => 'Stok harus diisi',
                'nama_barang.unique' => 'Nama barang sudah tersedia'
            ]);

            $barang = new Barang;
            $barang->nama_barang = $request->nama_barang;
            $barang->harga = $request->harga;
            $barang->stok = $request->stok;
            $barang->save();

            return response()->json([
                'data' => $barang,
                'status' => 'success',
                'message' => 'Barang berhasil ditambahkan'
            ], 200);
        }

        public function show($id)
        {
            if (!Barang::where('id_barang', '=', $id)->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Barang tidak ditemukan'
                ], 404);
            } else {
                return response()->json([
                    'data' => Barang::find($id),
                    'status' => 'success',
                ], 200);
            }
        }

        public function update(Request $request, $id)
        {
            $this->validate($request, [
                'nama_barang' => 'required|unique:barang,nama_barang,' . $id . ',id_barang',
                'harga' => 'required',
                'stok' => 'required'
            ], [
                'nama_barang.required' => 'Nama barang harus diisi',
                'harga.required' => 'Harga harus diisi',
                'stok.required' => 'Stok harus diisi',
                'nama_barang.unique' => 'Nama barang sudah tersedia'
            ]);

            if (!Barang::where('id_barang', '=', $id)->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Barang tidak ditemukan'
                ], 404);
            } else {
                $barang = Barang::find($id);
                $barang->nama_barang = $request->nama_barang;
                $barang->harga = $request->harga;
                $barang->stok = $request->stok;
                $barang->save();

                return response()->json([
                    'data' => $barang,
                    'status' => 'success',
                    'message' => 'Barang berhasil diperbarui'
                ], 200);
            }
        }

        public function destroy($id)
        {
            if (!Barang::where('id_barang', '=', $id)->exists()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Barang tidak ditemukan'
                ], 404);
            } else {
                Barang::destroy($id);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Barang berhasil dihapus'
                ], 200);
            }
        }
    }
