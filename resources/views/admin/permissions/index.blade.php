@extends('layouts.admin')

@section('title', 'Phân quyền')

@section('content')
<h2>Phân quyền hệ thống</h2>
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card p-3">
            <h5>Tạo nhóm quyền (UC15.1)</h5>
            <form action="{{ route('admin.permissions.role.store') }}" method="POST">
                @csrf
                <div class="mb-2"><input type="text" name="name" class="form-control" placeholder="Tên nhóm" required></div>
                <div class="mb-2"><input type="text" name="description" class="form-control" placeholder="Mô tả"></div>
                <button class="btn btn-success btn-sm">Tạo</button>
            </form>
        </div>
        <div class="card p-3 mt-3">
            <h5>Danh sách nhóm quyền</h5>
            @foreach($roles as $role)
            <div class="border rounded p-2 mb-2">
                <strong>{{ $role->name }}</strong>
                <small class="text-muted d-block">{{ $role->description }}</small>
                <form action="{{ route('admin.permissions.role.assign', $role) }}" method="POST" class="mt-2">
                    @csrf
                    @foreach($permissions as $perm)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                            {{ $role->permissions->contains($perm->id) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $perm->name }}</label>
                    </div>
                    @endforeach
                    <button class="btn btn-sm btn-primary mt-1">Gán quyền (UC15.2)</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-8">
        <div class="card p-3">
            <h5>Gán quyền cho người dùng (UC15.2)</h5>
            <table class="table table-sm">
                <thead><tr><th>Tên</th><th>Email</th><th>Vai trò hệ thống</th><th>Nhóm quyền</th><th></th></tr></thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <form action="{{ route('admin.permissions.user.assign', $user) }}" method="POST" class="d-flex gap-1">
                                @csrf
                                <select name="role_id" class="form-select form-select-sm">
                                    <option value="">-- Chọn --</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id==$role->id?'selected':'' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-dark">Gán</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
