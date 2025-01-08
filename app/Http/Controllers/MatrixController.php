<?php

namespace App\Http\Controllers;

use App\Models\Matrix;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MatrixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($skip = 0, $take = 30)
    {
        try {

            if (!is_numeric($skip) || $skip < 0) {
                return response()->json([
                    'message' => 'Parameter skip harus berupa angka positif atau 0.',
                ], 400);
            }

            if (!is_numeric($take) || $take < 0) {
                return response()->json([
                    'message' => 'Parameter take harus berupa angka positif atau 0.',
                ], 400);
            }

            $matrixs = Matrix::skip($skip)->take($take)->get();

            if ($matrixs->isEmpty()) {
                return response()->json([
                    'message' => 'Data tidak ditemukan.',
                    'data' => [],
                    'count' => 0,
                    'skip' => $skip,
                    'take' => $take
                ], 404);
            }

            return response()->json([
                'message' => 'Data berhasil ditemukan.',
                'data' => $matrixs,
                'count' => $matrixs->count(),
                'skip' => $skip,
                'take' => $take
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan internal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'panjang' => 'required|integer|min:1',
                'tinggi' => 'required|integer|min:1',
            ]);

            $matrix = Matrix::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => $matrix
            ], Response::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => 'Database error',
                'details' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan internal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $matrix = Matrix::findOrFail($id);

            $randomizedMatrix = $this->generateRandomizedMatrix($matrix->panjang, $matrix->tinggi);

            return response()->json([
                'message' => 'Data berhasil ditemukan.',
                'data' => [
                    'id' => $matrix->id,
                    'panjang' => $matrix->panjang,
                    'tinggi' => $matrix->tinggi,
                    'randomized_matrix' => $randomizedMatrix
                ]
            ], Response::HTTP_OK);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data tidak ditemukan.',
                'error' => 'Data dengan ID ' . $id . ' tidak ditemukan.',
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {

            return response()->json([
                'message' => 'Terjadi kesalahan saat pencarian data.',
                'error' => 'Database error',
                'details' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Menangani kesalahan lain
            return response()->json([
                'message' => 'Terjadi kesalahan internal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateRandomizedMatrix($panjang, $tinggi)
    {
        $matrix = [];
        for ($y = 1; $y <= $tinggi; $y++) {
            for ($x = 1; $x <= $panjang; $x++) {
                $matrix[] = [
                    'x' => $x,
                    'y' => $y,
                    'value' => rand(1, 10), // Menghasilkan nilai acak
                ];
            }
        }
        return $matrix;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $matrix = Matrix::findOrFail($id);

            $request->validate([
                'panjang' => 'required|integer|min:1',
                'tinggi' => 'required|integer|min:1',
            ]);

            $matrix->panjang = $request->panjang;
            $matrix->tinggi = $request->tinggi;
            $matrix->save();

            return response()->json([
                'message' => 'Data berhasil diperbarui.',
                'data' => $matrix
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data tidak ditemukan.',
                'error' => 'Data dengan ID ' . $id . ' tidak ditemukan.',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => 'Database error',
                'details' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan internal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $matrix = Matrix::findOrFail($id);
            $matrix->delete();

            return response()->json([
                'message' => 'Data berhasil dihapus',
                'data' => null
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'message' => 'Data tidak ditemukan.',
                'error' => 'Data dengan ID ' . $id . ' tidak ditemukan.',
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => 'Database error',
                'details' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan internal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
