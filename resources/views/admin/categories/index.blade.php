@extends('layouts.admin')

@section('title', 'Kategori')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Kategori Produk</h4>
            <p class="text-muted mb-0">Kelola kategori untuk produk/menu Anda</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Kategori
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari kategori..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    @if(request()->hasAny(['search']))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Jumlah Produk</th>
                            <th>Status</th>
                            <th style="width: 150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $category->name }}</span>
                                    @if($category->description)
                                        <br><small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                    @endif
                                </td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>
                                    <span class="badge bg-primary">{{ $category->products_count }} produk</span>
                                </td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aktif</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-times me-1"></i>Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Hapus"
                                            onclick="if(confirm('Yakin ingin menghapus kategori ini?')) document.getElementById('delete-{{ $category->id }}').submit()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-{{ $category->id }}"
                                        action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada kategori</p>
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm mt-3">
                                        <i class="fas fa-plus me-1"></i>Tambah Kategori Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($categories->hasPages())
            <div class="card-footer">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection