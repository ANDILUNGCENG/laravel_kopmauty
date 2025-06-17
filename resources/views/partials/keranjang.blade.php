@if (isset($keranjangs) && $keranjangs->count() > 0)
    @foreach ($keranjangs as $item)
        <div class="cart-item">
            <div class="item-name" data-bs-toggle="modal" data-bs-target="#itemModal_{{ $item->id }}"
                style="cursor: pointer;">
                {{ $item->produk->nama }}
            </div>
            <div class="item-details">
                <div class="item-quantity">
                    <div class="input-group input-group-md" style="width: 75px;">
                        <button class="btn btn-outline-secondary btn-minus btn-sm" type="button"
                            style="padding: 2px 5px;">-</button>
                        <input type="number" class="form-control qty-input" value="{{ $item->jumlah }}" min="1"
                            max="{{ $item->produk->stok }}" style="width: 30px; text-align: center; padding: 2px;"
                            data-id="{{ $item->id }}" data-jumlah="{{ $item->jumlah }}" data-price="{{ $item->produk->harga_jual }}"
                            data-stock="{{ $item->produk->stok }}">
                        <button class="btn btn-outline-secondary btn-plus btn-sm" type="button"
                            style="padding: 2px 5px;">+</button>
                    </div>
                </div>

                <div class="item-subtotal">
                    <div class="subtotal-container">
                        Rp <span class="subtotal-amount">{{ number_format($item->total, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="item-remove">
                    <button class="btn btn-sm btn-danger remove-item" data-id="{{ $item->id }}"
                        onclick="removeFromCart('{{ $item->id }}')">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        </div>

    @endforeach
@else
    <div class="alert alert-primary">
        <p><i class='bx bx-info-circle me-2'></i> <span class="text-primary">Petunjuk</span></p>
        <ul class="mb-0" style="list-style: none; padding-left: 0;">
            <li style="display: flex; align-items: start; gap: 8px; margin-bottom: 8px;">
                <i class='bx bx-barcode-reader'></i>
                <span class="text-primary">Tambahkan produk ke keranjang melalui fitur pencarian .</span>
            </li>
            <li style="display: flex; align-items: start; gap: 8px; margin-bottom: 8px;">
                <i class='bx bx-user'></i>
                <span class="text-primary">Klik nama untuk mengganti nama
                    pelanggan.</span>
            </li>
            <li style="display: flex; align-items: start; gap: 8px;">
                <i class='bx bx-trash'></i>
                <span class="text-primary">Klik untuk menghapus semua barang yang
                    dipilih.</span>
            </li>
        </ul>
    </div>
@endif
