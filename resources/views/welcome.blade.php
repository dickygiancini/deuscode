<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
        <style>
            #map { height: 540px; width: 540px; }
            #map_edit { height: 360px; width: 360px; }
        </style>
    </head>
    <body>
    <div class="container">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
            <strong>{{ $message }}</strong>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Data Entry</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.data') }}" method="post"  autocomplete="off">
                            <div class="row">
                                @method('POST')
                                @csrf
                                <div class="col-md-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="jenis" class="form-label">Jenis</label>
                                    <select required id="jenis" name="jenis" class="form-select">
                                        <option value="" disabled selected>Select Customer</option>
                                        @foreach ($customertypes as $items)
                                            <option value="{{ $items->id }}">{{ $items->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control" name="tanggal_lahir"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-12 align-items-center">
                                    <label for="map" class="form-label">Map</label>
                                    <div id="map"></div>

                                    <input type="hidden" name="latitude" id="latitude" class="latitude">
                                    <input type="hidden" name="longitude" id="longitude" class="longitude">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <div class="card">
                <div class="card-header">
                    <h5 class="card-title">List Customer</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="table datatable">
                            <table id="data-table" class="table mb-0 dataTable no-footer" role="grid">
                                <thead>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Type</th>
                                    <th>Alamat</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Status</th>
                                    <th style="width: 140px;">Action</th>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Type</th>
                                        <th>Alamat</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Status</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('store.update') }}" method="post"  autocomplete="off">
                        <div class="row">
                            @csrf
                            <input type="hidden" name="id" id="id_edit">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama" required>
                            </div>
                            <div class="col-md-6">
                                <label for="jenis" class="form-label">Jenis</label>
                                <select required id="jenis_edit" name="jenis" class="form-select">
                                    <option value="" disabled selected>Select Customer</option>
                                    @foreach ($customertypes as $items)
                                        <option value="{{ $items->id }}">{{ $items->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat_edit" name="alamat" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" id="tanggal_lahir_edit" name="tanggal_lahir"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="map" class="form-label">Map</label>
                                <div id="map_edit"></div>

                                <input type="hidden" name="latitude" id="latitude_edit" class="latitude">
                                <input type="hidden" name="longitude" id="longitude_edit" class="longitude">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.6/dist/sweetalert2.all.min.js" integrity="sha256-jI91e7SOUXp23P/7pDnwTwij8eGScCTVERrN5qY+wgw=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.3.0-beta.1/pdfmake.min.js" integrity="sha512-G332POpNexhCYGoyPfct/0/K1BZc4vHO5XSzRENRML0evYCaRpAUNxFinoIJCZFJlGGnOWJbtMLgEGRtiCJ0Yw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.3.0-beta.1/vfs_fonts.min.js" integrity="sha512-6RDwGHTexMgLUqN/M2wHQ5KIR9T3WVbXd7hg0bnT+vs5ssavSnCica4Uw0EJnrErHzQa6LRfItjECPqRt4iZmA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>

    <script>

        $(document).ready(function() {
            // Ambil posisi langsung Deus Code sekarang
            $('#latitude').val(-7.2574719)
            $('#longitude').val(112.7520883)

            // Setup ajax untuk ajax request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });

        // Inisialisasi Data table
        var tables = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'pdf', 'print', 'colvis'
            ],
            ajax: '{!! route('datatables') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'customername', name: 'customer.nama' },
                { data: 'nama', name: 'customer_types.nama' },
                { data: 'alamat', name: 'customer.alamat' },
                { data: 'latitude', name: 'customer.latitude' },
                { data: 'longitude', name: 'customer.longitude' },
                { data: 'status', name: 'status'},
                { data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;

                    var input = document.createElement("input");
                    input.style.width = '100%'

                    $(input).appendTo($(column.footer()).empty())
                    .on('change', function () {
                        column.search($(this).val()).draw();
                    });
                });
            }
        });

        $( function() {
            $(".input-group.date").datepicker({
                format: 'yyyy-mm-dd'
            });
        }); // Date Picker


        var map = L.map('map').setView([-7.2574719, 112.7520883], 13); // Inisialisasi Map
        var marker = L.marker([-7.2574719, 112.7520883]).addTo(map); // Pendanda map
        var popup = L.popup(); // Popo Up Message

        // Tile Map
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoiZGlja3lnaWFuY2luaSIsImEiOiJja3l0ajIyajYwMTl6MnZveGl2cGVsZWN3In0.NCjUL0vMYv4sC4gADzC01Q'
        }).addTo(map);

        // Event click map
        function onMapClick(e) {
            popup.setLatLng(e.latlng).setContent("Lokasi anda sekarang: " + e.latlng.toString()).openOn(map);
            marker.setLatLng(e.latlng)
            $('#latitude').val(e.latlng.lat)
            $('#longitude').val(e.latlng.lng)
        }

        map.on('click', onMapClick);

        var map_edit = L.map('map_edit').setView([-7.2574719, 112.7520883], 13); // Inisialisasi Map
        var marker_edit = L.marker([-7.2574719, 112.7520883]).addTo(map_edit); // Marker
        var popup_edit = L.popup() // Popo Up Message

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoiZGlja3lnaWFuY2luaSIsImEiOiJja3l0ajIyajYwMTl6MnZveGl2cGVsZWN3In0.NCjUL0vMYv4sC4gADzC01Q'
        }).addTo(map_edit);

        // Event click map
        function onMapClickEdit(e) {
            popup_edit.setLatLng(e.latlng).setContent("Lokasi anda sekarang: " + e.latlng.toString()).openOn(map_edit);
            marker_edit.setLatLng(e.latlng)
            $('#latitude_edit').val(e.latlng.lat)
            $('#longitude_edit').val(e.latlng.lng)
        }

        map_edit.on('click', onMapClickEdit);

        // Ubah status
        function setStatus(id, status)
        {
            // semula dari active jadi inaktif
            var changestatus = status == 0 ? 'inactive' : 'active'
            Swal.fire({
                title: 'Yakin untuk mengubah status?',
                text: 'Anda akan mengubah status menjadi '+changestatus+'',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Ubah!'
                }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: "POST",
                        url: "{{ route('store.setstatus') }}",
                        data: {
                            id: id
                        },
                        success: (data) => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Berhasil mengubah data',
                            })
                            tables.draw(false)
                        },
                        error: (data) => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Gagal mengubah data',
                            })
                        }
                    })
                }
            })
        }

        function editModal(id)
        {
            $.ajax({
                type: 'GET',
                url: "{{ route('store.edit') }}",
                data: {
                    id: id
                },
                success: (data) => {

                    $('#id_edit').val(data.id)
                    $('#nama_edit').val(data.nama)
                    $('#jenis_edit').val(data.customer_types_id).change()
                    $('#tanggal_lahir_edit').val(data.tanggal_lahir)
                    $('#alamat_edit').val(data.alamat)
                    $('#latitude_edit').val(data.latitude)
                    $('#longitude_edit').val(data.longitude)
                    $(".input-group.date").datepicker({
                        format: 'yyyy-mm-dd'
                    }).datepicker("update", new Date(data.tanggal_lahir));

                    map_edit.setView([data.latitude, data.longitude]);
                    marker_edit.setLatLng([data.latitude, data.longitude]);
                },
                error: (data) => {
                    Swal.fire({
                        icon: 'error',
                        title: data.responseJSON.error,
                        text: 'Gagal mengubah data',
                    })
                }
            })
        }

    </script>
    </body>
</html>
