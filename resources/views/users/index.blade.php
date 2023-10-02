@extends('layout.layout')
@section('menunav')
    <a class="sidebar-toggle js-sidebar-toggle">
        <a href="#" class="btn btn-outline-primary" id="addRoleBtn">Thêm</a>
    </a>
@endsection
@section('main')
    <!-- Modal -->
    <div class="modal fade" id="UserRoleModal" tabindex="-1" aria-labelledby="UserRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="UserRoleModalLabel">Loại tài khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" placeholder="Loại tài khoản" id="userrole" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitRoleBtn">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1700,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            // Toast.fire({
            //     icon: 'success',
            //     title: 'Signed in successfully'
            // })
            addUserRole();
        });

        function addUserRole() {
            $('#addRoleBtn').click(function(e) {
                e.preventDefault();
                $("#UserRoleModal").modal('show');
                $("#submitRoleBtn").click(function(e) {
                    e.preventDefault();
                    var rolename = $("#userrole").val().trim();
                    if (rolename == '') {
                        Toast.fire({
                            icon: 'error',
                            title: 'Thiếu loại tài khoản'
                        })
                    }else{
                        $.ajax({
                            type: "post",
                            url: "/",
                            data: "data",
                            dataType: "dataType",
                            success: function (response) {
                                
                            }
                        });
                    }
                });
            });
        }
    </script>
@endsection
