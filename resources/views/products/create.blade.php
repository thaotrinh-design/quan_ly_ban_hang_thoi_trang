@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
<h2>Thêm sản phẩm</h2>
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="mt-3" style="max-width:700px">
    @csrf
    <div class="mb-2"><label>Tên</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-2">
        <label>Danh mục</label>
        <select name="category_id" class="form-select" required>
            @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2"><label>Giá</label><input type="number" name="price" class="form-control" required></div>
    <div class="mb-2"><label>Tồn kho tổng (tự tính từ variants)</label><input type="number" name="stock" class="form-control" value="0" readonly></div>
    <div class="mb-2"><label>Size (cách nhau bởi dấu phẩy)</label><input type="text" name="available_sizes" id="sizes-input" class="form-control" placeholder="S, M, L, XL" value="S, M, L, XL"></div>
    <div class="mb-2"><label>Màu (cách nhau bởi dấu phẩy)</label><input type="text" name="available_colors" id="colors-input" class="form-control" placeholder="Đen, Trắng, Xám" value="Đen, Trắng, Xám, Xanh navy"></div>

    <div class="card p-3 mb-3">
        <h6><i class="fa-solid fa-boxes-stacked me-1"></i> Tồn kho theo Size/Màu</h6>
        <small class="text-muted mb-2 d-block">Nhập số lượng tồn kho cho từng combination size + màu.</small>
        <div id="variant-table"></div>
    </div>

    <div class="mb-2"><label>Mô tả</label><textarea name="description" class="form-control"></textarea></div>
    <div class="mb-2"><label>Ảnh</label><input type="file" name="image" class="form-control"></div>
    <div class="form-check mb-3"><input type="checkbox" name="status" value="1" class="form-check-input" checked><label class="form-check-label">Hiển thị</label></div>
    <button class="btn btn-success">Lưu</button>
</form>
@endsection

@push('scripts')
<script>
function parseList(str) {
    return str.split(',').map(s => s.trim()).filter(s => s.length > 0);
}

function buildVariantTable() {
    const sizes = parseList(document.getElementById('sizes-input').value);
    const colors = parseList(document.getElementById('colors-input').value);
    const container = document.getElementById('variant-table');
    const stockInput = document.querySelector('[name="stock"]');

    if (sizes.length === 0 || colors.length === 0) {
        container.innerHTML = '<p class="text-muted small">Nhập size và màu để hiển thị bảng tồn kho.</p>';
        stockInput.value = 0;
        return;
    }

    let html = '<table class="table table-sm table-bordered mb-0">';
    html += '<thead><tr><th>Size</th><th>Màu</th><th>Tồn kho</th></tr></thead><tbody>';

    let totalStock = 0;

    for (const size of sizes) {
        for (const color of colors) {
            const key = size + '_' + color;
            html += `<tr>
                <td>${size}</td>
                <td>${color}</td>
                <td><input type="number" name="variant_stock[${key}]" class="form-control form-control-sm variant-stock" min="0" value="0" data-key="${key}"></td>
            </tr>`;
        }
    }

    html += '</tbody></table>';
    container.innerHTML = html;

    // Update total stock when any variant changes
    document.querySelectorAll('.variant-stock').forEach(input => {
        input.addEventListener('input', updateTotalStock);
    });
}

function updateTotalStock() {
    let total = 0;
    document.querySelectorAll('.variant-stock').forEach(input => {
        total += parseInt(input.value) || 0;
    });
    document.querySelector('[name="stock"]').value = total;
}

document.getElementById('sizes-input').addEventListener('input', buildVariantTable);
document.getElementById('colors-input').addEventListener('input', buildVariantTable);

// Initial build
buildVariantTable();
</script>
@endpush
