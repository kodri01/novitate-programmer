//Get Matrix Data
$(document).ready(function() {
    // Fungsi untuk mengambil data dari API menggunakan AJAX
    function loadMatrixData(skip = 0, take = 30) {
        $.ajax({
            url: '/api/matrix/' + skip + '/' + take, 
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Cek apakah data ada di response
                if(response.data) {
                    let matrixDataa = response.data;
                    let tableBodi = $('#matrixTable tbody');
                    tableBodi.empty(); 

                    // Iterasi dan tambahkan baris ke dalam tabel
                    matrixDataa.forEach(function(matrix, index) {
                        let row = '<tr>';
                        row += '<th scope="row">' + (index + 1) + '</th>';
                        row += '<td>' + matrix.panjang + '</td>';
                        row += '<td>' + matrix.tinggi + '</td>';
                        row += '<td>';
                        row += '<a href="#" id="loadMatrixBtn" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop2" data-id="' + matrix.id + '"><i class="fa-solid fa-eye"></i></a>';
                        row += '<button onclick="openEditModal(' + matrix.id + ', ' + matrix.panjang + ', ' + matrix.tinggi + ')" class="btn btn-warning btn-sm mx-2"><i class="fa-solid fa-pen"></i></button>';
                        row += '<form id="deleteForm' + matrix.id + '" style="display: none;">';
                        row += '<input type="hidden" name="_method" value="DELETE">';
                        row += '</form>';
                        row += '<button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(' + matrix.id + ')"><i class="fas fa-trash"></i></button>';
                        row += '</td>';
                        row += '</tr>';
                        tableBodi.append(row);
                    });
                    
                } else {
                    alert('Data tidak ditemukan.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Data tidak ditemukan',
                });
            }
        });
    }

    loadMatrixData(0, 30); 

    // Event listener untuk tombol "Load Data"
    $('#loadDataBtn').click(function() {
        var skip = $('#skipInput').val(); 
        var take = $('#takeInput').val(); 
        loadMatrixData(skip, take); 
    });
});


//Post Matrix Data  
document.getElementById('addMatrixForm').addEventListener('submit', function (event) {
    event.preventDefault();

    // Ambil nilai input dari form
    const panjang = document.getElementById('panjang').value;
    const tinggi = document.getElementById('tinggi').value;

    // Data yang akan dikirim ke API
    const data = {
        panjang: panjang,
        tinggi: tinggi
    };

    // Mengirim request POST ke API
    fetch('/api/matrix', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + 'TOKEN_ANDA'  // Jika API membutuhkan autentikasi
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Menampilkan SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Data Berhasil Disimpan',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Halaman akan di-reload setelah "OK" ditekan
                    location.reload(true);
                }
            });
            // Tutup modal setelah berhasil
            $('#staticBackdrop').modal('hide');
        } else {
            // Menampilkan SweetAlert untuk error
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: data.message,
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Menampilkan SweetAlert untuk kesalahan umum
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Gagal menyimpan data.',
        });
    });
});         

//update Matrix data
function openEditModal(id, panjang, tinggi) {
    $('#idMatrix').val(id);
    $('#panjangMatrix').val(panjang);
    $('#tinggiMatrix').val(tinggi);
    $('#staticBackdrop1').modal('show');
}

$('#editForm').on('submit', function(e) {
    e.preventDefault(); // Mencegah form submit biasa

    var id = $('#idMatrix').val();
    var panjang = $('#panjangMatrix').val();
    var tinggi = $('#tinggiMatrix').val();

    // Mengirimkan data melalui AJAX
    $.ajax({
        url: '/api/matrix/' + id,  // URL yang sesuai dengan route Anda
        type: 'PUT',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),  // CSRF token
            panjang: panjang,
            tinggi: tinggi
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Data Berhasil Disimpan',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Halaman akan di-reload setelah "OK" ditekan
                    location.reload(true);
                }
            });
        
            // Tutup modal setelah berhasil
            $('#staticBackdrop1').modal('hide');
        },
        
        error: function(xhr, status, error) {
            console.error('Error:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Gagal menyimpan data.',
            });
        }
    });
    
});


//Read-by Id
$(document).on('click', '#loadMatrixBtn', function() {
    const matrixId = $(this).data('id'); 
    const apiUrl = '/api/matrix/' + matrixId;

    $.get(apiUrl, function(response) {
        const matrixData = response.data.randomized_matrix;
        const panjang = response.data.panjang;
        const tinggi = response.data.tinggi;

        // Kosongkan tabel dalam modal
        $('#modalMatrixTable tbody').empty(); // Gunakan tabel khusus dalam modal (ID berbeda)

        // Menampilkan data dalam bentuk tabel di modal
        for (let y = 1; y <= tinggi; y++) {
            const row = $('<tr></tr>'); // Buat baris baru

            // Loop untuk kolom X dan menambahkan nilai ke dalam kolom
            for (let x = 1; x <= panjang; x++) {
                // Menemukan nilai dari matriks yang sesuai dengan X dan Y
                const cell = matrixData.find(item => item.x === x && item.y === y);

                const value = cell ? cell.value : '';
                row.append('<td class="text-center">' + value + '</td>'); 
            }
            // Menambahkan baris ke tabel di modal
            $('#modalMatrixTable tbody').append(row);
        }
    });
});


//Delete Matrix Data
const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success mx-2',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
});

function confirmDelete(id) {
    swalWithBootstrapButtons.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data ini akan dihapus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: 'Hapus',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/api/matrix/' + id,  
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')  
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',    
                        title: 'Data Berhasil Dihapus',
                    }).then(() => {
                        location.reload();  
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Data gagal dihapus',
                    });
                }
            });
        }
    });
}

