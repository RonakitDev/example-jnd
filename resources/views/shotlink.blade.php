@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Save Data </h2>
        <form id="saveForm" class="p-4 border rounded bg-light shadow-sm">
            <div class="form-group">
                <label for="inputData">Enter Data:</label>
                <input type="url" id="inputData" name="data" class="form-control" placeholder="Enter data to save"
                       required>
            </div>
            <button type="button" class="btn btn-primary w-100" id="saveButton">Save</button>
        </form>
        <p id="response" class="mt-3 text-center"></p>
    </div>

    <div class="container mt-5">
        <h3 class="text-center mb-4">Data</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>urlnew</th>
                <th>urlold</th>
                <th>action</th>
            </tr>
            </thead>
            <tbody id="dataTableBody">
            <!-- Data will be appended here -->
            </tbody>
        </table>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="form-group">
                            <label for="editInputData">Enter new data:</label>
                            <input type="text" id="editInputData" value="" class="form-control" required>
                        </div>
                        <input type="hidden" id="editId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChangesButton">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            fetchData();
            $('#saveButton').on('click', function () {
                const inputField = $('#inputData');
                if (!inputField[0].checkValidity()) {
                    inputField[0].reportValidity();
                    return;
                }
                const inputData = inputField.val();
                $.ajax({
                    url: "{{ route('data.save') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        linkold: inputData,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        Swal.fire({
                            title: "Good job!",
                            text: "create success!",
                            icon: "success"
                        });
                        $('#inputData').val('');
                        $('#dataTableBody').empty();
                        fetchData()
                    },
                    error: function (error) {
                        console.error('Error:', error);
                        $('#response').text('Failed to save data.').addClass('text-danger');
                    }
                });
            });


            function fetchData() {
                $.ajax({
                    url: "{{ route('data.get') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        data.forEach(function (item, index) {
                            const newRow = `<tr>
                        <td>${index + 1}</td>
                        <td><a href="jdntest/${item.urlnew}" target="_blank">{{ asset('/${item.urlnew}') }}</a></td>
                        <td>${item.urlold}</td>
                        <td>
                         <button type="button"  class="btn btn-warning" id="${item.id}">edit</button>
                         <button type="button" class="btn btn-danger " id="${item.id}">delete</button>
                        </td>
                        </tr>`;
                            $('#dataTableBody').append(newRow);
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            $('#dataTableBody').on('click', '.btn-warning', function (e) {
                const id = e.target.id;
                $('#editId').val(id);
                $('#editModal').modal('show');
            });
            $('#saveChangesButton').on('click', function () {
                const id = $('#editId').val();

                const inputmodal = $('#editInputData');
                if (!inputmodal[0].checkValidity()) {
                    inputmodal[0].reportValidity();
                    return;
                }
                const input = $('#editInputData').val();

                $.ajax({
                    url: "/shotlink/update/" + id,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        linkold: input,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {

                        $('#editModal').modal('hide');
                        Swal.fire({
                            title: "Good job!",
                            text: "Edit success!",
                            icon: "success"
                        });
                        $('#dataTableBody').empty();
                        fetchData()
                    },
                    error: function (error) {
                        console.error('Error:', error);
                        $('#response').text('Failed to save data.').addClass('text-danger');
                    }
                });
            });

            $('#dataTableBody').on('click', '.btn-danger', function (e) {
                const id = e.target.id;
                Swal.fire({
                    title: 'ลบข้อมูล ?',
                    text: "โปรดตรวจสอบไห้แน่ใจว่าจะลบข้อมูล !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ตกลง !',
                    cancelButtonText: 'ยกเลิก !'
                }).then((result) => {

                    if (result.isConfirmed) {
                        $.ajax(
                            {
                                url: "/shotlink/delete/" + id,
                                type: 'post',
                                data: {
                                    "id": id,
                                    "_token": "{{ csrf_token() }}"
                                },
                                success: function (response) {
                                    $('#dataTableBody').empty();
                                    fetchData()
                                }
                            });
                        Swal.fire(
                            'สำเร็จ!',
                            'ลบข้อมูลสำเร็จ',
                            'success'
                        )
                    }
                })
            });
        });

    </script>
@endsection
