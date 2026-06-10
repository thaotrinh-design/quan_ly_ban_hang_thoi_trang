<div class="filter-panel">
    <h6 class="filter-title"><i class="fa-solid fa-sliders me-2"></i>Bộ lọc</h6>
    <form method="GET" action="{{ $filterAction ?? route('shop.index') }}">
        <div class="mb-3">
            <label class="form-label">Từ khóa</label>
            <input type="text" name="keyword" class="form-control form-control-sm" value="{{ request('keyword') }}" placeholder="Tìm sản phẩm...">
        </div>
        <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" class="form-select form-select-sm">
                <option value="">Tất cả</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Mức giá</label>
            <select name="price_range" class="form-select form-select-sm">
                @foreach($priceRanges as $value => $label)
                <option value="{{ $value }}" {{ request('price_range') == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Size</label>
            <select name="size" class="form-select form-select-sm">
                <option value="">Tất cả</option>
                @foreach($sizes as $size)
                <option value="{{ $size }}" {{ request('size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Màu</label>
            <select name="color" class="form-select form-select-sm">
                <option value="">Tất cả</option>
                @foreach($colors as $color)
                <option value="{{ $color }}" {{ request('color') == $color ? 'selected' : '' }}>{{ $color }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Sắp xếp</label>
            <select name="sort" class="form-select form-select-sm">
                <option value="">Mặc định</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
            </select>
        </div>
        <button class="btn btn-fashion w-100 btn-sm">Áp dụng</button>
    </form>
</div>
