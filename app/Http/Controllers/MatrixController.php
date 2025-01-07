<?php

namespace App\Http\Controllers;

use App\Models\Matrix;
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
        $matrixs = Matrix::skip($skip)->take($take)->get();

        return response()->json([
            'message' => 'Data berhasil ditemukan.',
            'data' => $matrixs,
            'count' => $matrixs->count(),
            'skip' => $skip,
            'take' => $take
        ]);
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
        $validated = $request->validate([
            'panjang' => 'required|integer',
            'tinggi' => 'required|integer',
        ]);

        $matrix = Matrix::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
            'data' => $matrix
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
            public function show($id)
            {
                $matrix = Matrix::findOrFail($id);

                // Membuat matriks acak
                $randomizedMatrix = $this->generateRandomizedMatrix($matrix->panjang, $matrix->tinggi);

                return response()->json([
                    'message' => 'Data berhasil ditemukan.',
                    'data' => [
                        'id' => $matrix->id,
                        'panjang' => $matrix->panjang,
                        'tinggi' => $matrix->tinggi,
                        'randomized_matrix' => $randomizedMatrix
                    ]
                ]);
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
        $matrix = Matrix::findOrFail($id);

        $matrix->panjang = $request->panjang;
        $matrix->tinggi = $request->tinggi;
        $matrix->save();

        return response()->json([
            'message' => 'Data berhasil disimpan.',
            'data' => $matrix
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $matrix = Matrix::findOrFail($id);
        $matrix->delete();

        if ($matrix) {
            $matrix->delete();
            return response()->json([
                'message' => 'Data berhasil dihapus',
                'data' => null
            ], 200);
        }
    
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }
}
