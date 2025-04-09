@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Users</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered" id="userTable">
        <thead>
        <tr>
            <th>User</th>
            <th>Name</th>
            <th>Email</th>
            <th>Number of Accounts</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->saving_accounts_count }}</td>
                <td>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal" data-user-id="{{ $user->id }}">
                       Add Account
                    </button>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No users found.</td></tr>
        @endforelse
        </tbody>
    </table>

</div>

<!-- Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Create Saving Accounts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="accountForm">
                    @csrf

                    <!-- First static form block (not removable) -->
                    <div class="card mb-3 p-3 border account-group" data-index="0">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="accounts[0][first_name]" placeholder="First Name">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="accounts[0][last_name]" placeholder="Last Name">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="accounts[0][dob]">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="accounts[0][address]" placeholder="Address">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-2">
                                <select name="accounts[0][currency]" class="form-select">
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="account-form-area"></div>
                    <div style="text-align: center;">
                    <button type="button" class="btn btn-primary mb-3" id="addFormBtn">Add Another</button>
                    <button type="submit" class="btn btn-success mb-3">Save Accounts</button>
                    <input type="hidden" name="user_id" id="modal_user_id">
                    </div>

                </form>

                <div id="form-success" class="alert alert-success d-none mt-2"></div>
                <div id="form-errors" class="alert alert-danger d-none mt-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- Template for dynamic blocks (with remove button) -->
<template id="account-form-template">
    <div class="card mb-3 p-3 border account-group" data-index="{index}">
        <div class="row g-2 align-items-center">
            <div class="col-md-2">
                <input type="text" class="form-control" name="accounts[{index}][first_name]" placeholder="First Name">
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="accounts[{index}][last_name]" placeholder="Last Name">
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="accounts[{index}][dob]">
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="accounts[{index}][address]" placeholder="Address">
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-2">
                <select name="accounts[{index}][currency]" class="form-select">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                </select>
            </div>
            <div style="width: 12.5%;">
                <button type="button" class="btn btn-danger w-100 h-100" onclick="removeForm(this)">Remove</button>
            </div>
        </div>
    </div>
</template>
@push('scripts')
<script>
    let formIndex = 1; // Start from 1 since 0 is fixed

    function removeForm(button) {
        $(button).closest('.account-group').remove();
    }

    function addForm() {
        let template = document.getElementById('account-form-template').innerHTML;
        let html = template.replace(/{index}/g, formIndex);
        $('#account-form-area').append(html);
        formIndex++;
    }

    $(document).ready(function () {

        $('#registerModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget); // Button that triggered the modal
            let userId = button.data('user-id'); // Extract user_id from data-user-id
            $('#modal_user_id').val(userId);     // Set hidden input
        });

        $('#addFormBtn').click(function () {
            addForm();
        });

        $('#accountForm').on('submit', function (e) {
            e.preventDefault();
            //let userId = $('#modal_user_id').val();
            // Clear old errors
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#form-errors').addClass('d-none').text('');
            $('#form-success').addClass('d-none').text('');

            $.ajax({
                url: "{{ route('accounts.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    $('#form-success').removeClass('d-none').text(response.message);
                    $('#accountForm')[0].reset();
                    $('#account-form-area').html('');
                    formIndex = 1;

                    setTimeout(function () {
                        window.location.href = "{{ route('admin.users') }}"; // Replace with your target route
                    }, 1000); // Redirect after 1 second
                },
                error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, messages) {
                        // Escape array-style names like accounts.0.first_name to match input[name="accounts[0][first_name]"]
                        let inputName = key.replace(/\.(\d+)\./g, '[$1][').replace(/\./g, '][') + ']';
                        let $input = $('[name="' + inputName + '"]');

                        $input.addClass('is-invalid');
                        $input.siblings('.invalid-feedback').text(messages[0]);
                    });
                } else {
                    $('#form-errors').removeClass('d-none').text('An unexpected error occurred.');
                }
            }
            });
        });
    });

    $(document).ready(function() {
        $('#userTable').DataTable();
    });
</script>
@endpush
@endsection