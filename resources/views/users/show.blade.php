@extends('layouts.app')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('users.index') }}">User</a>
</li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi User</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            @if($user->role == 'admin')
                            <span class="badge badge-danger">Admin</span>
                            @elseif($user->role == 'manager')
                            <span class="badge badge-warning">Manager</span>
                            @else
                            <span class="badge badge-info">Karyawan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Diupdate</th>
                        <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
                
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline float-right">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection