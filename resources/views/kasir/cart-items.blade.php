<div id="keranjang-container">
    @include('partials.keranjang')
</div>
<script>
    function removeFromCart(id) {
        fetch(`{{ url('/hapus-produk-keranjang') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    document.dispatchEvent(new Event('productAdded'));
                } else {
                    showToast('Gagal menghapus produk dari keranjang', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menghapus produk dari keranjang', 'danger');
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const cartItems = document.getElementById('keranjang-container');

        cartItems.addEventListener('click', function(event) {
            const target = event.target;
            if (target.classList.contains('btn-plus') || target.classList.contains('btn-minus')) {
                const input = target.closest('.input-group').querySelector('.qty-input');
                let newQty = parseInt(input.value);
                const id = input.dataset.id;
                const price = parseFloat(input.dataset.price);
                const stock = parseInt(input.dataset.stock) + newQty;

                if (target.classList.contains('btn-plus')) {
                    let tempQty = Math.min(newQty + 1, stock);
                    if (tempQty === newQty) {
                        showToast('Jumlah melebihi stok yang tersedia!', 'danger');
                    }
                    newQty = tempQty;
                } else if (target.classList.contains('btn-minus')) {
                    let tempQty = Math.max(newQty - 1, 1);
                    if (tempQty === newQty) {
                        showToast('Jumlah minimal adalah 1', 'danger');
                    }
                    newQty = tempQty;
                }

                input.value = newQty;

                const subtotal = price * newQty;
                const subtotalElement = input.closest('.item-details').querySelector('.item-subtotal');
                subtotalElement.innerHTML = `Rp ${subtotal.toLocaleString('id-ID')}`;
                updateCart(id, newQty);
            }
        });

        cartItems.addEventListener('change', function(event) {
            if (event.target.classList.contains('qty-input')) {
                const input = event.target;
                const id = input.dataset.id;
                let newQty = parseInt(input.value);
                let oldQty = parseInt(input.dataset.jumlah);
                const price = parseFloat(input.dataset.price);
                const stock = parseInt(input.dataset.stock) + oldQty;
                // console.log(stock);

                if (newQty > stock) {
                    newQty = stock;
                    input.value = stock;
                    const subtotal = price * newQty;

                    const subtotalElement = input.closest('.item-details').querySelector(
                        '.item-subtotal');
                    subtotalElement.textContent = subtotal.toLocaleString('id-ID');

                    updateCart(id, newQty);

                    showToast('Gagal menambahkan barang, jumlah melebihi stok tersedia', 'danger');

                }

                const subtotal = price * newQty;

                const subtotalElement = input.closest('.item-details').querySelector('.item-subtotal');
                subtotalElement.textContent = subtotal.toLocaleString('id-ID');

                updateCart(id, newQty);
            }
        });
    });

    function updateCart(id, qty) {
        fetch(`/update-produk-keranjang/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    qty: qty
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.dispatchEvent(new Event('productAdded'));
                } else {

                    showToast(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memperbarui keranjang', 'danger');
            });
    }
</script>
