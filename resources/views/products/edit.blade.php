@extends('layouts.admin')

@section('title', 'Sửa sản phẩm')

@section('content')
<h2>Cập nhật sản phẩm</h2>
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="mt-3" style="max-width:700px">
    @csrf @method('PUT')
    <div class="mb-2"><label>Tên</label><input type="text" name="name" class="form-control" value="{{ $product->name }}" required></div>
    <div class="mb-2">
        <label>Danh mục</label>
        <select name="category_id" class="form-select" required>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $product->category_id==$category->id?'selected':'' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2"><label>Giá</label><input type="number" name="price" class="form-control" value="{{ $product->price }}" required></div>
    <div class="mb-2" id="stock-simple-wrapper" style="{{ count($product->getSizesList()) > 0 && count($product->getColorsList()) > 0 ? 'display:none' : '' }}"><label>Tồn kho</label><input type="number" name="stock_simple" id="stock-simple" class="form-control" min="0" value="{{ $product->stock }}"></div>
    <div class="mb-2" id="stock-variant-wrapper" style="{{ count($product->getSizesList()) > 0 && count($product->getColorsList()) > 0 ? '' : 'display:none' }}"><label>Tồn kho tổng (tự tính từ variants)</label><input type="number" name="stock" id="stock-variant" class="form-control" value="{{ $product->stock }}" readonly></div>
    <div class="mb-2"><label>Size (cách nhau bởi dấu phẩy)</label><input type="text" name="available_sizes" id="sizes-input" class="form-control" value="{{ implode(', ', $product->getSizesList()) }}"></div>
    <div class="mb-2"><label>Màu (cách nhau bởi dấu phẩy)</label><input type="text" name="available_colors" id="colors-input" class="form-control" value="{{ implode(', ', $product->getColorsList()) }}"></div>

    <div class="card p-3 mb-3">
        <h6><i class="fa-solid fa-boxes-stacked me-1"></i> Tồn kho theo Size/Màu</h6>
        <small class="text-muted mb-2 d-block">Nhập số lượng tồn kho cho từng combination size + màu.</small>
        <div id="variant-table"></div>
    </div>

    <div class="mb-2"><label>Mô tả</label><textarea name="description" class="form-control">{{ $product->description }}</textarea></div>
    <div class="mb-2"><img src="{{ $product->image_url }}" width="100" class="mb-2"><input type="file" name="image" class="form-control"></div>
    <div class="form-check mb-3"><input type="checkbox" name="status" value="1" class="form-check-input" {{ $product->status?'checked':'' }}><label class="form-check-label">Hiển thị</label></div>
    <button class="btn btn-success">Cập nhật</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
</form>
@endsection

@push('scripts')
<script>
const existingVariants = @json(
    collect($product->variants)->mapWithKeys(fn($v) => [
        $v->size . '_' . $v->color => ['size' => $v->size, 'color' => $v->color, 'stock' => $v->stock]
    ])
);

function parseList(str) {
    return str.split(',').map(s => s.trim()).filter(s => s.length > 0);
}

function buildVariantTable() {
    const sizes = parseList(document.getElementById('sizes-input').value);
    const colors = parseList(document.getElementById('colors-input').value);
    const container = document.getElementById('variant-table');
    const simpleWrapper = document.getElementById('stock-simple-wrapper');
    const variantWrapper = document.getElementById('stock-variant-wrapper');

    if (sizes.length === 0 || colors.length === 0) {
        container.innerHTML = '<p class="text-muted small">Nhập size và màu để hiển thị bảng tồn kho. Nếu bỏ trống, sản phẩm sẽ không có biến thể.</p>';
        simpleWrapper.style.display = '';
        variantWrapper.style.display = 'none';
        return;
    }

    simpleWrapper.style.display = 'none';
    variantWrapper.style.display = '';

    let html = '<table class="table table-sm table-bordered mb-0">';
    html += '<thead><tr><th>Size</th><th>Màu</th><th>Tồn kho</th></tr></thead><tbody>';

    for (const size of sizes) {
        for (const color of colors) {
            const key = size + '_' + color;
            const existingStock = existingVariants[key]?.stock || 0;
            html += `<tr>
                <td>${size}</td>
                <td>${color}</td>
                <td><input type="number" name="variant_stock[${key}]" class="form-control form-control-sm variant-stock" min="0" value="${existingStock}" data-key="${key}"></td>
            </tr>`;
        }
    }

    html += '</tbody></table>';
    container.innerHTML = html;

    document.querySelectorAll('.variant-stock').forEach(input => {
        input.addEventListener('input', updateTotalStock);
    });
}

function updateTotalStock() {
    let total = 0;
    document.querySelectorAll('.variant-stock').forEach(input => {
        total += parseInt(input.value) || 0;
    });
    document.getElementById('stock-variant').value = total;
}

document.getElementById('sizes-input').addEventListener('input', buildVariantTable);
document.getElementById('colors-input').addEventListener('input', buildVariantTable);

buildVariantTable();
</script>
@endpush
