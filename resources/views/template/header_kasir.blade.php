<nav class="navbar navbar-expand-lg navbar-light bg-white navbar-bottom" style="flex-shrink: 0;">
    <div class="container-fluid">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu"
                    aria-controls="offcanvasMenu">
                    <i class="bx bx-menu"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                  this.closest('form').submit();">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>

        <ul class="navbar-nav flex-row ms-auto mb-2 mb-lg-0">
            {{-- <li class="nav-item ms-2">
                <a class="btn btn-outline-primary btn-icon btn-md" href="#" id="calculatorLink"
                    data-bs-toggle="modal" data-bs-target="#calculatorModal" title="Kalkulator">
                    <i class="bx bx-calculator"></i>
                </a>
            </li> --}}
            {{-- <li class="nav-item ms-2">
                <a class="btn btn-outline-success btn-icon btn-md" href="#" data-bs-toggle="tooltip"
                    title="Riwayat">
                    <i class="bx bx-history"></i>
                </a>
            </li> --}}
            {{-- <li class="nav-item ms-2">
                <a class="btn btn-outline-info btn-icon btn-md" href="#" id="shortcutInfoLink"
                    data-bs-toggle="modal" data-bs-target="#shortcutInfoModal" title="Shortcut Info">
                    <i class="bx bx-info-circle"></i>
                </a>
            </li> --}}
        </ul>
    </div>
</nav>

<div class="modal fade" id="shortcutInfoModal" aria-labelledby="shortcutInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shortcutInfoModalLabel">Shortcut Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Daftar Shortcut:</h6>
                <ul>
                    <li><strong>Alt + F</strong>: Tombol Search</li>
                    <li><strong>Alt + S</strong>: Pilih Toko</li>
                    <li><strong>Alt + C</strong>: Pilih Pelanggan</li>
                    <li><strong>Alt + B</strong>: Tombol Bayar</li>
                    <li><strong>Alt + D</strong>: Menghapus semua keranjang</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="calculatorModal" tabindex="-1" aria-labelledby="calculatorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calculatorModalLabel">Kalkulator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="calculator">
                    <div class="calculator-display mb-3">
                        <input type="text" id="calculatorScreen" class="form-control" readonly>
                    </div>
                    <div class="calculator">
                        <div class="row g-2 mb-2">
                            <div class="col-3"><button class="btn btn-danger w-100" onclick="allClear()">AC</button>
                            </div>
                            <div class="col-3"><button class="btn btn-warning w-100" onclick="clearEntry()">CE</button>
                            </div>
                            <div class="col-3"><button class="btn btn-success w-100"
                                    onclick="appendValue('%')">%</button></div>
                            <div class="col-3"><button class="btn btn-success w-100"
                                    onclick="appendValue('/')">÷</button></div>

                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('7')">7</button></div>
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('8')">8</button></div>
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('9')">9</button></div>
                            <div class="col-3"><button class="btn btn-success w-100"
                                    onclick="appendValue('*')">x</button></div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('4')">4</button></div>
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('5')">5</button></div>
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('6')">6</button></div>
                            <div class="col-3"><button class="btn btn-success w-100"
                                    onclick="appendValue('-')">-</button></div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('1')">1</button></div>
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('2')">2</button></div>
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('3')">3</button></div>
                            <div class="col-3"><button class="btn btn-success w-100"
                                    onclick="appendValue('+')">+</button></div>

                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('0')">0</button></div>
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('000')">000</button></div>
                            <div class="col-3"><button class="btn btn-secondary w-100"
                                    onclick="appendValue('.')">.</button></div>
                            <div class="col-3"><button class="btn btn-primary w-100"
                                    onclick="calculate()">=</button></div>
                        </div>
                    </div>


                    <div class="calculator-history mt-3">
                        <h6>Histori Perhitungan</h6>
                        <ul id="calculatorHistory" class="list-group"></ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navbar Mobile -->
<nav class="navbar-mobile">
    <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu"
        aria-controls="offcanvasMenu">
        <i class="bx bx-menu"></i>
    </button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#calculatorModal"
        title="Kalkulator">
        <i class="bx bx-calculator"></i>
    </button>
    {{-- <button type="button" class="btn btn-success" data-bs-toggle="tooltip" title="Riwayat">
        <i class="bx bx-history"></i>
    </button> --}}
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#shortcutInfoModal"
        title="Shortcut Info">
        <i class="bx bx-info-circle"></i>
    </button>
</nav>

<!-- Offcanvas Menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasMenuLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <!-- Garis Pembatas -->
    <hr class="m-0">
    <div class="offcanvas-body">
        <ul class="list-group">
            <li class="list-group-item">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                    <i class="bx bx-home me-2"></i> Dashboard
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('transaksi.penjualan') }}" class="d-flex align-items-center">
                    <i class="bx bx-money me-2"></i> Penjualan
                </a>
            </li>
            <li class="list-group-item">
                <a class="d-flex align-items-center text-danger" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bx bx-log-out me-2"></i> Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>

</div>



<script>
    let calculatorScreen = document.getElementById('calculatorScreen');
    let historyList = document.getElementById('calculatorHistory');
    let history = JSON.parse(localStorage.getItem('calculatorHistory')) || []; // Muat histori dari Local Storage

    // Muat histori saat halaman dimuat
    updateHistoryDisplay();

    // Fungsi menambah nilai ke layar kalkulator
    function appendValue(value) {
        calculatorScreen.value += value;
    }

    // Fungsi AC (All Clear): Menghapus semua input dan histori
    function allClear() {
        calculatorScreen.value = ''; // Kosongkan layar
        history = []; // Kosongkan histori
        localStorage.removeItem('calculatorHistory'); // Hapus histori dari Local Storage
        updateHistoryDisplay(); // Perbarui tampilan histori
    }

    // Fungsi CE (Clear Entry): Menghapus input terakhir (mirip dengan DEL)
    function clearEntry() {
        calculatorScreen.value = calculatorScreen.value.slice(0, -1); // Hapus karakter terakhir
    }

    // Fungsi menghitung hasil dan menambahkannya ke histori
    function calculate() {
        try {
            let expression = calculatorScreen.value;

            // Gantikan semua persen dengan perhitungan persentase
            expression = expression.replace(/(\d+(?:\.\d+)?)%/g, (match, p1) => {
                // Hitung persentase dari nilai total
                let total = eval(expression.split(/[-+/*]/)[0]); // Ambil nilai total sebelum operator
                let percentValue = Math.round(total * (p1 / 100)); // Hitung nilai persentase dan bulatkan
                return percentValue; // Ganti yang ada di dalam ekspresi
            });

            // Hitung menggunakan eval
            let result = eval(expression);

            // Buat entri untuk histori dengan mengganti % dengan nilai yang dihitung
            let historyEntry = expression.replace(/(\d+(?:\.\d+)?)%/g, (match, p1) => {
                let total = eval(expression.split(/[-+/*]/)[0]); // Ambil nilai total sebelum operator
                return Math.round(total * (p1 / 100)); // Ganti dengan nilai persentase yang dibulatkan
            });

            if (!isNaN(result)) {
                // Tambahkan ke histori dalam format yang benar
                addToHistory(historyEntry + ' = ' + result);
                calculatorScreen.value = result; // Tampilkan hasil
            }
        } catch (error) {
            calculatorScreen.value = 'Error'; // Tampilkan 'Error' jika terjadi kesalahan
        }
    }

    // Fungsi menambahkan perhitungan ke histori
    function addToHistory(entry) {
        history.push(entry);
        localStorage.setItem('calculatorHistory', JSON.stringify(history)); // Simpan histori ke Local Storage
        updateHistoryDisplay();
    }

    // Fungsi memperbarui tampilan histori
    function updateHistoryDisplay() {
        historyList.innerHTML = '';
        history.forEach((item) => {
            let listItem = document.createElement('li');
            listItem.className = 'list-group-item';
            listItem.textContent = item;
            historyList.appendChild(listItem);
        });
    }
</script>
