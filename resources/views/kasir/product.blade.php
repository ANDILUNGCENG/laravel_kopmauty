<!-- <div class="card mx-auto h-100% overflow-hidden" style="height: calc(100vh - 130px);"> -->
<div class="card mx-auto overflow-hidden" id="cartContainer">
    <div class="card-body d-flex flex-column h-100">
        <!-- Header -->
        <div class="header mb-3">
            <div class="row align-items-center">
                <form class="d-flex w-100" id="searchForm">
                    @csrf
                    <input id="searchInput" class="form-control me-2" name="barcode" type="search"
                        placeholder="Masukan nama Produk /  Barcode" aria-label="Search" autofocus>
                    <input type="hidden" id="storeId" value="1">
                </form>
            </div>
        </div>
        <!-- Garis Pembatas -->
        <hr class="custom-divider">
        <!-- Products -->
        <div class="products flex-grow overflow-auto mb-3" id="productResults">
            <div class="products-grid produk-search d-none">
                @foreach ($produks_all as $produk)
                    <div class="product-card" data-product-barcode="{{ $produk->barcode }}">
                        <div class="card h-100 shadow-sm product-card position-relative"
                            data-product-id="{{ $produk->id }}" data-product-barcode="{{ $produk->barcode }}"
                            data-stock="{{ $produk->stok }}" style="cursor: pointer;">
                            <span
                                class="position-absolute top-0 end-0 badge m-2 
                              @if ($produk->stok > ($produk->stok_minim ?? 0)) bg-primary 
                              @elseif ($produk->stok == ($produk->stok_minim ?? 0)) 
                                  bg-warning 
                              @else 
                                  bg-danger @endif"
                                @if ($produk->stok <= ($produk->stok_minim ?? 0)) data-bs-toggle="tooltip" data-bs-placement="top" 
                                  title="Peringatan! Stok Minimum: {{ $produk->stok_minim }}" @endif>
                                {{ $produk->stok }}
                            </span>


                            @if ($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}" class="card-img-top"
                                    alt="Gambar Produk">
                            @else
                                <img src="https://placehold.co/400x300?text=No Image" class="card-img-top"
                                    alt="Placeholder">
                            @endif
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1 text-sm" style="font-size: 14px;">
                                    {{ \Illuminate\Support\Str::limit($produk->nama, 30) }}
                                </h6>
                                <p class="card-text mb-1 text-sm" style="font-size: 12px;">
                                    <strong>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="products-grid produk-pages">
                @foreach ($produks as $produk)
                    <div class="product-card" data-product-barcode="{{ $produk->barcode }}">
                        <div class="card h-100 shadow-sm product-card position-relative"
                            data-product-id="{{ $produk->id }}" data-product-barcode="{{ $produk->barcode }}"
                            data-stock="{{ $produk->stok }}" style="cursor: pointer;">
                            <span
                                class="position-absolute top-0 end-0 badge m-2 
                              @if ($produk->stok > ($produk->stok_minim ?? 0)) bg-primary 
                              @elseif ($produk->stok == ($produk->stok_minim ?? 0)) 
                                  bg-warning 
                              @else 
                                  bg-danger @endif"
                                @if ($produk->stok <= ($produk->stok_minim ?? 0)) data-bs-toggle="tooltip" data-bs-placement="top" 
                                  title="Peringatan! Stok Minimum: {{ $produk->stok_minim }}" @endif>
                                {{ $produk->stok }}
                            </span>


                            @if ($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}" class="card-img-top"
                                    alt="Gambar Produk">
                            @else
                                <img src="https://placehold.co/400x300?text=No Image" class="card-img-top"
                                    alt="Placeholder">
                            @endif
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1 text-sm" style="font-size: 14px;">
                                    {{ \Illuminate\Support\Str::limit($produk->nama, 30) }}
                                </h6>
                                <p class="card-text mb-1 text-sm" style="font-size: 12px;">
                                    <strong>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footer: Page navigation -->
        @if ($produks->hasPages())
            <div class="footer mt-2 produk-pages">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0">

                        {{-- Previous Page Link --}}
                        @if ($produks->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ request()->url() . '?' . http_build_query(array_merge(request()->except('page'), ['page' => $produks->currentPage() - 1])) }}"
                                    rel="prev">&lsaquo;</a>
                            </li>
                        @endif

                        {{-- Page Number Links --}}
                        @for ($page = 1; $page <= $produks->lastPage(); $page++)
                            @php
                                $query = array_merge(request()->except('page'), ['page' => $page]);
                                $url = request()->url() . '?' . http_build_query($query);
                            @endphp
                            <li class="page-item {{ $page == $produks->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endfor

                        {{-- Next Page Link --}}
                        @if ($produks->hasMorePages())
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ request()->url() . '?' . http_build_query(array_merge(request()->except('page'), ['page' => $produks->currentPage() + 1])) }}"
                                    rel="next">&rsaquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&rsaquo;</span>
                            </li>
                        @endif

                    </ul>
                </nav>
            </div>
        @endif

    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase().trim();

            const produkPages = document.querySelectorAll('.produk-pages');
            const produkSearch = document.querySelectorAll('.produk-search');

            if (keyword === '') {
                produkSearch.forEach(function(search) {
                    search.classList.add('d-none');
                });
                produkPages.forEach(function(page) {
                    page.classList.remove('d-none');
                });
            } else {
                produkPages.forEach(function(page) {
                    page.classList.add('d-none');
                });
                produkSearch.forEach(function(search) {
                    search.classList.remove('d-none');
                });

                produkSearch.forEach(function(search) {
                    search.querySelectorAll('.product-card').forEach(function(card) {
                        const titleEl = card.querySelector('.card-title');
                        const nama = titleEl ? titleEl.innerText.toLowerCase() : '';
                        const barcodeAttr = card.getAttribute('data-product-barcode');
                        const barcode = barcodeAttr ? barcodeAttr.toLowerCase() : '';

                        if (nama.includes(keyword) || barcode.includes(keyword)) {
                            card.classList.remove('d-none');
                        } else {
                            card.classList.add('d-none');
                        }
                    });
                });
            }
        });
    });
</script>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        const beepSound = document.getElementById('beepSound');
        let isAddingToCart = false;

        function addProductToCart(productId, stock) {
            if (isAddingToCart) return;
            isAddingToCart = true;

            fetch(`/tambah-kranjang/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // console.log(data);
                        document.dispatchEvent(new Event('productAdded'));
                        beepSound.play();

                        // searchInput.value = '';
                        isAddingToCart = false;
                    } else {
                        showToast('Gagal menambahkan produk ke keranjang');
                        isAddingToCart = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat menambahkan produk ke keranjang');
                    isAddingToCart = false;
                });

        }

        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const stock = parseInt(this.getAttribute('data-stock'));
                addProductToCart(productId, stock);
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pajakSelect = document.getElementById('pajak');

        if (!pajakSelect) return;

        // Tangani klik pada link pagination
        document.querySelectorAll('.pagination a.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault(); // hentikan aksi default

                const selectedPajak = pajakSelect.value;
                const url = new URL(this.href);

                if (selectedPajak && selectedPajak !== '0') {
                    url.searchParams.set('id_pajak', selectedPajak);
                }

                // Redirect manual dengan URL yang sudah ditambah pajak
                window.location.href = url.toString();
            });
        });
    });
</script>

<script>
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    let isAddingToCart = false;

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const barcode = searchInput.value.trim();
        const produkSearch = document.querySelectorAll('.produk-search');
        const produkPages = document.querySelectorAll('.produk-pages');
        produkSearch.forEach(function(search) {
            search.classList.add('d-none');
        });
        produkPages.forEach(function(page) {
            page.classList.remove('d-none');
        });
        if (!barcode || isAddingToCart) return;

        isAddingToCart = true;

        fetch(`{{ route('transaksi.barcode.store') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    barcode: barcode
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    console.log('✅ Produk ditambahkan:', data);
                    // Tambahkan efek atau perbarui tampilan keranjang
                    document.dispatchEvent(new Event('productAdded'));
                    searchInput.value = '';
                    beepSound.play(); // jika ada suara
                } else {
                    searchInput.value = '';
                    alert(data.message || 'Gagal menambahkan produk.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan saat menambahkan produk.');
            })
            .finally(() => {
                isAddingToCart = false;
            });
    });

    // Auto-submit saat barcode scanner tekan Enter
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchForm.dispatchEvent(new Event('submit'));
        }
    });
</script>
