<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('store.name'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream: #faf7f4;
            --blush: #e8c4c4;
            --rose: #c9a9a6;
            --rose-deep: #b8847e;
            --sage: #b8c5b0;
            --charcoal: #3d3d3d;
            --soft-border: #efe8e3;
        }
        html, body { height: 100%; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--charcoal);
            display: flex; flex-direction: column; min-height: 100vh;
        }
        h1, h2, h3, .navbar-brand, .hero-title, .shop-hero-title { font-family: 'Cormorant Garamond', serif; }
        main.page-content { flex: 1 0 auto; }
        .footer-contact { flex-shrink: 0; margin-top: auto !important; background: #2c2a28; color: #e8e4e0; }
        .footer-contact h5 { color: #faf7f4; }

        /* Search Suggestions */
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e8e4e0;
            border-radius: 0 0 8px 8px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .suggestion-item {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            text-decoration: none;
            color: #3d3d3d;
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.15s;
        }
        .suggestion-item:hover {
            background: #faf7f4;
        }
        .suggestion-item:last-child {
            border-bottom: none;
        }
        .suggestion-category {
            color: var(--rose-deep);
            font-weight: 500;
        }
        .suggestion-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 10px;
        }
        .suggestion-info {
            flex: 1;
        }
        .suggestion-name {
            font-size: 0.9rem;
            font-weight: 500;
        }
        .suggestion-price {
            font-size: 0.8rem;
            color: var(--rose-deep);
            font-weight: 600;
        }
        .footer-contact a { color: #c9c4be; text-decoration: none; }
        .footer-contact a:hover { color: var(--blush); }
        .social-link { display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 50%; background: #3d3a37; color: #fff; margin-right: 8px; transition: .2s; }
        .social-link:hover { background: var(--rose-deep); color: #fff; }
        .map-container { border-radius: 12px; overflow: hidden; }
        .map-container iframe { width: 100%; height: 200px; border: 0; }

        .navbar-fashion { background: rgba(250,247,244,.95)!important; backdrop-filter: blur(10px); border-bottom: 1px solid var(--soft-border); }
        .navbar-fashion .navbar-brand { color: var(--charcoal)!important; font-size: 1.5rem; letter-spacing: 2px; }
        .navbar-fashion .nav-link { color: var(--charcoal)!important; font-weight: 500; font-size: .92rem; }
        .navbar-fashion .nav-link:hover { color: var(--rose-deep)!important; }

        .btn-fashion { background: var(--rose-deep); color: #fff; border: none; border-radius: 8px; font-weight: 500; }
        .btn-fashion:hover { background: #a6736d; color: #fff; }
        .btn-outline-fashion { border: 1.5px solid var(--rose); color: var(--rose-deep); background: transparent; border-radius: 8px; }
        .btn-outline-fashion:hover { background: var(--blush); color: var(--charcoal); border-color: var(--blush); }
        .text-fashion { color: var(--rose-deep); }
        .bg-fashion-soft { background: var(--blush)!important; color: var(--charcoal)!important; }

        .home-hero {
            background:
                linear-gradient(135deg, rgba(250,247,244,.86) 0%, rgba(243,235,230,.84) 50%, rgba(232,223,216,.86) 100%),
                url('https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1600&h=900&fit=crop&q=85') center/cover no-repeat;
            padding: 5rem 0 4rem;
            position: relative; overflow: hidden;
        }
        .home-hero::before {
            content: ''; position: absolute; right: -10%; top: -20%;
            width: 50%; height: 140%;
            background: radial-gradient(ellipse, rgba(201,169,166,.25) 0%, transparent 70%);
        }
        .home-hero::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(250,247,244,.08), rgba(250,247,244,.32));
            pointer-events: none;
        }
        .home-hero .container { position: relative; z-index: 1; }
        .hero-tag { text-transform: uppercase; letter-spacing: 3px; font-size: .75rem; color: var(--rose-deep); margin-bottom: .5rem; }
        .hero-title { font-size: clamp(2.5rem, 5vw, 4rem); line-height: 1.1; color: var(--charcoal); }
        .hero-title em { font-style: italic; color: var(--rose-deep); }
        .hero-desc { max-width: 520px; margin: 1rem auto 2rem; color: #6b6560; }

        .shop-hero {
            background:
                linear-gradient(135deg, rgba(250,247,244,.88) 0%, rgba(243,235,230,.88) 55%, rgba(232,223,216,.92) 100%),
                url('https://images.unsplash.com/photo-1445205170230-053b83016050?w=1600&h=700&fit=crop&q=85') center/cover no-repeat;
            padding: 2.5rem 0;
            border-bottom: 1px solid var(--soft-border);
            position: relative;
            overflow: hidden;
        }
        .shop-hero::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(250,247,244,.10), rgba(250,247,244,.34));
            pointer-events: none;
        }
        .shop-hero .container { position: relative; z-index: 1; }
        .shop-hero--sale {
            background:
                linear-gradient(135deg, rgba(250,247,244,.84) 0%, rgba(243,235,230,.82) 48%, rgba(232,223,216,.88) 100%),
                url('https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1600&h=700&fit=crop&q=85') center/cover no-repeat;
        }
        .shop-hero--collection {
            background:
                linear-gradient(135deg, rgba(250,247,244,.84) 0%, rgba(243,235,230,.82) 48%, rgba(232,223,216,.88) 100%),
                url('https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=1600&h=700&fit=crop&q=85') center/cover no-repeat;
        }
        .shop-hero--shirts {
            background:
                linear-gradient(135deg, rgba(250,247,244,.84) 0%, rgba(243,235,230,.82) 48%, rgba(232,223,216,.88) 100%),
                url('https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=1600&h=700&fit=crop&q=85') center/cover no-repeat;
        }
        .shop-hero--pants {
            background:
                linear-gradient(135deg, rgba(250,247,244,.84) 0%, rgba(243,235,230,.82) 48%, rgba(232,223,216,.88) 100%),
                url('https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=1600&h=700&fit=crop&q=85') center/cover no-repeat;
        }
        .shop-hero--accessories {
            background:
                linear-gradient(135deg, rgba(250,247,244,.84) 0%, rgba(243,235,230,.82) 48%, rgba(232,223,216,.88) 100%),
                url('https://images.unsplash.com/photo-1521369909029-2afed882baee?w=1600&h=700&fit=crop&q=85') center/cover no-repeat;
        }
        .shop-hero-title { font-size: 2.2rem; margin-bottom: .25rem; }
        .shop-hero-sub { color: #7a746f; margin-bottom: 1rem; }
        .shop-tabs { display: flex; gap: .5rem; flex-wrap: wrap; }
        .shop-tab { padding: .45rem 1.1rem; border-radius: 20px; text-decoration: none; color: var(--charcoal); background: #fff; border: 1px solid var(--soft-border); font-size: .9rem; transition: .2s; }
        .shop-tab.active, .shop-tab:hover { background: var(--rose-deep); color: #fff; border-color: var(--rose-deep); }

        .filter-panel { background: #fff; border-radius: 14px; padding: 1.25rem; border: 1px solid var(--soft-border); }
        .filter-title { font-weight: 600; margin-bottom: 1rem; color: var(--charcoal); }

        .product-card { background: #fff; border-radius: 14px; border: 1px solid var(--soft-border); overflow: hidden; display: flex; flex-direction: column; transition: .3s; }
        .product-card:hover { box-shadow: 0 12px 32px rgba(61,61,61,.08); transform: translateY(-4px); }
        .product-card-img-wrap { position: relative; overflow: hidden; background: #f5f0ec; }
        .product-card-img { width: 100%; height: 300px; object-fit: cover; transition: transform .4s; }
        .product-card:hover .product-card-img { transform: scale(1.03); }
        .product-card-body { padding: 1rem 1.1rem 1.1rem; display: flex; flex-direction: column; flex: 1; }
        .product-cat { font-size: .72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--rose-deep); }
        .product-name { font-size: .95rem; font-weight: 600; margin: .35rem 0; line-height: 1.35; }
        .product-actions { display: flex; gap: .4rem; flex-wrap: wrap; }
        .discount-badge {
            position: absolute; top: 12px; right: 12px; z-index: 2;
            background: var(--rose-deep); color: #fff;
            font-size: .78rem; font-weight: 700;
            padding: .35rem .55rem; border-radius: 6px;
            box-shadow: 0 2px 8px rgba(184,132,126,.4);
        }
        .price-original { display: block; font-size: .82rem; color: #9a9490; text-decoration: line-through; }
        .price-sale { font-size: 1.05rem; font-weight: 700; color: var(--rose-deep); }

        .category-pill {
            display: block; padding: 1.1rem; text-align: center;
            background: #fff; border: 1px solid var(--soft-border); border-radius: 12px;
            color: var(--charcoal); font-weight: 500; transition: .2s;
        }
        .category-pill:hover { background: var(--blush); border-color: var(--blush); color: var(--charcoal); }

        .section-head h2, .section-title { font-family: 'Cormorant Garamond', serif; font-weight: 600; }
        .product-detail-img-wrap { position: relative; border-radius: 16px; overflow: hidden; background: #f5f0ec; }
        .product-detail-img { width: 100%; border-radius: 16px; }
        .review-form, .review-item { background: #fff; border: 1px solid var(--soft-border); border-radius: 12px; padding: 1rem; }
        .review-item { margin-bottom: .75rem; }
        .empty-state { text-align: center; padding: 3rem; color: #9a9490; }

        .compact-pagination .page-link { padding: .2rem .55rem; font-size: .8rem; border-color: var(--soft-border); color: var(--charcoal); }
        .compact-pagination .page-item.active .page-link { background: var(--rose-deep); border-color: var(--rose-deep); }

        #chat-widget { position: fixed; bottom: 24px; right: 24px; z-index: 1050; }
        .chat-toggle-btn { width: 56px; height: 56px; border-radius: 50%; border: none; background: linear-gradient(135deg, var(--rose-deep), var(--blush)); color: #fff; font-size: 1.4rem; box-shadow: 0 4px 20px rgba(184,132,126,.45); cursor: pointer; position: relative; }
        .chat-badge { position: absolute; top: -4px; right: -4px; background: #fff; color: var(--rose-deep); font-size: .6rem; font-weight: bold; padding: 2px 5px; border-radius: 8px; }
        .chat-box { position: absolute; bottom: 70px; right: 0; width: 340px; max-height: 460px; background: #fff; border-radius: 16px; box-shadow: 0 8px 30px rgba(0,0,0,.12); display: flex; flex-direction: column; overflow: hidden; }
        .chat-header { background: linear-gradient(135deg, var(--rose-deep), #c9a9a6); color: #fff; padding: 12px 14px; display: flex; justify-content: space-between; align-items: flex-start; }
        .chat-messages { flex: 1; overflow-y: auto; padding: 12px; max-height: 320px; background: var(--cream); }
        .chat-msg { margin-bottom: 10px; display: flex; }
        .chat-msg.user { justify-content: flex-end; }
        .chat-msg .bubble { max-width: 85%; padding: 8px 12px; border-radius: 12px; font-size: .85rem; line-height: 1.4; white-space: pre-wrap; }
        .chat-msg.bot .bubble { background: #fff; border: 1px solid var(--soft-border); }
        .chat-msg.user .bubble { background: var(--rose-deep); color: #fff; }
        .chat-input-area { display: flex; gap: 6px; padding: 10px; border-top: 1px solid var(--soft-border); }
        @media (max-width: 400px) { .chat-box { width: calc(100vw - 48px); right: 0; } }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-fashion sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">{{ config('store.name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <form class="d-flex me-lg-3 my-2 my-lg-0 ms-lg-auto" action="{{ route('shop.index') }}" method="GET">
                <input class="form-control form-control-sm me-2" type="search" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm sản phẩm">
                <button class="btn btn-outline-fashion btn-sm" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            <ul class="navbar-nav me-lg-3">
                <li class="nav-item"><a class="nav-link" href="{{ route('shop.sale') }}">Sale</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop.collection') }}">Bộ sưu tập</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop.shirts') }}">Áo</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop.pants') }}">Quần</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop.accessories') }}">Phụ kiện</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#contact">Liên hệ</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ auth()->check() ? route('cart.index') : route('login') }}">Giỏ hàng</a></li>
            </ul>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Thông tin cá nhân</a></li>
                            <li><a class="dropdown-item" href="{{ route('addresses.index') }}">Địa chỉ giao hàng</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}">Lịch sử mua hàng</a></li>
                            @if(auth()->user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Quản trị</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Đăng ký</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="page-content">
@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success">{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger">{{ session('error') }}</div>
    </div>
@endif

@yield('content')
</main>

<footer id="contact" class="footer-contact pt-5 pb-3">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5><i class="fa-solid fa-store me-2"></i>Liên hệ chúng tôi</h5>
                <p class="mb-2"><i class="fa-solid fa-location-dot me-2 text-danger"></i>{{ config('store.address') }}</p>
                <p class="mb-2"><i class="fa-solid fa-phone me-2 text-success"></i><a href="tel:{{ str_replace(' ', '', config('store.phone')) }}">{{ config('store.phone') }}</a></p>
                <p class="mb-2"><i class="fa-solid fa-envelope me-2 text-info"></i><a href="mailto:{{ config('store.email') }}">{{ config('store.email') }}</a></p>
                <p class="mb-0"><i class="fa-solid fa-clock me-2"></i>{{ config('store.hours') }}</p>
            </div>
            <div class="col-md-4">
                <h5><i class="fa-solid fa-share-nodes me-2"></i>Kết nối với chúng tôi</h5>
                <div class="mb-3">
                    <a href="{{ config('store.facebook') }}" target="_blank" class="social-link" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="{{ config('store.tiktok') }}" target="_blank" class="social-link" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="{{ config('store.instagram') }}" target="_blank" class="social-link" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="{{ config('store.youtube') }}" target="_blank" class="social-link" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
                <p class="small text-muted">Theo dõi {{ config('store.name') }} để cập nhật xu hướng thời trang mới nhất, ưu đãi và livestream bán hàng.</p>
            </div>
            <div class="col-md-4">
                <h5><i class="fa-solid fa-map-location-dot me-2"></i>Vị trí cửa hàng</h5>
                <div class="map-container mb-2">
                    <iframe src="{{ config('store.map_embed') }}" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <a href="https://www.google.com/maps/search/?api=1&query={{ config('store.map_lat') }},{{ config('store.map_lng') }}" target="_blank" class="btn btn-sm btn-outline-light">
                    <i class="fa-solid fa-diamond-turn-right me-1"></i> Chỉ đường trên Google Maps
                </a>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <p class="text-center text-muted small mb-0">&copy; 2026 {{ config('store.name') }} - Hệ thống quản lý bán hàng thời trang</p>
    </div>
</footer>

@include('partials.chat-widget')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('chat-toggle');
    const box = document.getElementById('chat-box');
    const close = document.getElementById('chat-close');
    const input = document.getElementById('chat-input');
    const send = document.getElementById('chat-send');
    const messages = document.getElementById('chat-messages');
    const csrf = '{{ csrf_token() }}';

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function addMsg(text, sender) {
        const div = document.createElement('div');
        div.className = 'chat-msg ' + sender;
        div.innerHTML = '<div class="bubble">' + escapeHtml(text).replace(/\n/g, '<br>') + '</div>';
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    toggle?.addEventListener('click', () => box.classList.toggle('d-none'));
    close?.addEventListener('click', () => box.classList.add('d-none'));

    async function sendMessage() {
        const text = input.value.trim();
        if (!text) return;
        addMsg(text, 'user');
        input.value = '';
        send.disabled = true;
        try {
            const res = await fetch('{{ route("chat.send") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ message: text })
            });
            const data = await res.json();
            addMsg(data.reply, 'bot');
        } catch (e) {
            addMsg('Xin lỗi, có lỗi kết nối. Vui lòng gọi ' + '{{ config("store.phone") }}', 'bot');
        }
        send.disabled = false;
        input.focus();
    }

    send?.addEventListener('click', sendMessage);
    input?.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
});
</script>
@stack('scripts')
</body>
</html>
