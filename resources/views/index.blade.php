<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>Matrix API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="card m-5">
        <div class="card-body">
            <div class="text-center">
                <h3>Data Matrix</h3>
            </div>
            <a href="add" class="btn btn-sm btn-primary my-2" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop"><i class="fa-solid fa-plus"></i> <b>Data Matrix</b></a>
            <div class="card mt-2">
                <div class="card-body">
                    <table class="table table-hover" id="matrixTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Panjang</th>
                                <th scope="col">Tinggi</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                    <div class="input-group mb-3 w-25">
                        <span class="input-group-text">Skip</span>
                        <input type="number" id="skipInput" class="form-control" value="0" min="0">
                        <span class="input-group-text">Take</span>
                        <input type="number" id="takeInput" class="form-control" value="30" min="1">
                        <button id="loadDataBtn" class="btn btn-primary">Load Data</button>
                    </div>
                </div>
                                
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Matrix</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addMatrixForm">
                    <div class="modal-body">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="panjang"
                                        placeholder="Panjang Matrix" name="panjang">
                                    <label for="panjang">Panjang Matrix</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="tinggi"
                                        placeholder="Tinggi Matrix" name="tinggi">
                                    <label for="tinggi">Tinggi Matrix</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah Matrix</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="idMatrix">
                        <div class="row g-2">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="panjangMatrix"
                                        placeholder="Panjang Matrix" name="panjang">
                                    <label for="floatingInputGrid">Panjang Matrix</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="tinggiMatrix"
                                        placeholder="Tinggi Matrix" name="tinggi">
                                    <label for="floatingInputGrid">Tinggi Matrix</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop2" tabindex="-1" aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <table class="table table-bordered mt-3" id="modalMatrixTable">
                   
                    <tbody>
                        <!-- Data akan dimuat di sini -->
                    </tbody>
                </table>

            </div>
        </div>
    </div>


    <!-- Menambahkan script jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Menambahkan script eksternal (Jika Anda memisahkannya ke dalam file) -->
    <script src="{{ url('js/app.js') }}"></script>
    <!-- Menambahkan Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>

</html>
