@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<!-- Bagian Top Widget -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalEvent }}</h3>
                <p>Total Event</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalItem }}</h3>
                <p>Total Souvenir</p>
            </div>
            <div class="icon">
                <i class="fas fa-gift"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalParticipant }}</h3>
                <p>Total Peserta</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalDistributed }}</h3>
                <p>Souvenir Keluar</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Progress Distribusi -->
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-line mr-2"></i>
            Progress Distribusi Souvenir
        </h3>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
            <strong>
                {{ $totalDistributed }} / {{ $totalParticipant }} Peserta
            </strong>
            <strong>
                {{ $distributionPercentage }}%
            </strong>
        </div>
        <div class="progress progress-lg">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $distributionPercentage }}%;">
                {{ $distributionPercentage }}%
            </div>
        </div>
    </div>
</div>

<!-- Grid Utama: Grafik & Ringkasan -->
<!-- Menambahkan mb-4 di row ini agar ada jarak dengan tabel di bawahnya (tidak menempel) -->
<div class="row mb-4">

    <!-- Kolom Kiri: Bar Chart & Ringkasan Distribusi -->
    <div class="col-md-8 d-flex flex-column">

        <!-- Bar Chart -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Distribusi Souvenir per Event
                </h3>
            </div>
            <div class="card-body">
                <canvas id="eventChart" height="120"></canvas>
            </div>
        </div>

        <!-- Ringkasan Distribusi -->
        <!-- Menghapus mb-4 agar sejajar bagian bawahnya, flex-grow-1 agar tingginya maksimal -->
        <div class="card shadow-sm mb-0 flex-grow-1">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line text-primary mr-2"></i>
                    Ringkasan Distribusi
                </h3>
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="d-flex justify-content-between">
                            <strong><i class="fas fa-gift text-primary mr-2"></i> Total Dibagikan</strong>
                            <strong>{{ $totalSouvenirDistributed }}</strong>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-primary" style="width:{{ $distributionPercentage }}%"></div>
                        </div>
                        <small class="text-muted">{{ $distributionPercentage }}%</small>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="d-flex justify-content-between">
                            <strong><i class="fas fa-user-clock text-warning mr-2"></i> Belum Ambil</strong>
                            <strong>{{ $remainingParticipant }}</strong>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-warning" style="width:{{ $remainingPercentage }}%"></div>
                        </div>
                        <small class="text-muted">{{ $remainingPercentage }}%</small>
                    </div>

                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="d-flex justify-content-between">
                            <strong><i class="fas fa-trophy text-success mr-2"></i> Terfavorit</strong>
                            <strong>{{ optional($mostPopularSouvenir)->name }}</strong>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-success" style="width:{{ $favoritePercentage }}%"></div>
                        </div>
                        <small class="text-muted">{{ optional($mostPopularSouvenir)->total }} Kali Dibagikan</small>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <strong><i class="fas fa-chart-bar text-danger mr-2"></i> Rata-rata / Event</strong>
                            <strong>{{ $averagePerEvent }}</strong>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-danger" style="width:{{ $averagePercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Akhir Kolom Kiri -->

    <!-- Kolom Kanan: Pie Chart -->
    <div class="col-md-4">
        <!-- class h-100 membuat card ini setinggi kolom kiri. Hapus mb-4 agar bawahnya pas sejajar -->
        <div class="card mb-0 h-100">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-2"></i>
                    Jenis Souvenir
                </h3>
            </div>
            <!-- align-items-center agar chart presisi di tengah vertikal/horizontal -->
            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 250px;">
                <canvas id="itemChart"></canvas>
            </div>
        </div>
    </div> <!-- Akhir Kolom Kanan -->
</div>

<!-- Tabel Top 5 & Aktivitas -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-trophy mr-2"></i>
                    Top 5 Souvenir
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Souvenir</th>
                        <th width="120">Jumlah</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($topSouvenirs as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>
                                <span class="badge badge-success">
                                    {{ $item->total }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">Belum ada data.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2"></i>
                    Aktivitas Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Souvenir</th>
                        <th>Tanggal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentActivities as $activity)
                        <tr>
                            <td>{{ optional($activity->participant)->name }}</td>
                            <td>{{ $activity->receiptItems->pluck('item.name')->implode(', ') }}</td>
                            <td>{{ \Carbon\Carbon::parse($activity->received_at)->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada aktivitas.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.progress {
    height: 8px;
    border-radius: 20px;
}
.progress-bar {
    border-radius: 20px;
}
.card {
    border-radius: 12px;
}
.mb-md-0 {
    margin-bottom: 0 !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const eventCtx = document.getElementById('eventChart');
    if (eventCtx) {
        new Chart(eventCtx, {
            type: 'bar',
            data: {
                labels: @json($eventChartLabels),
                datasets: [{
                    label: 'Jumlah Distribusi',
                    data: @json($eventChartData),
                    borderWidth: 1,
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    const itemCtx = document.getElementById('itemChart');
    if (itemCtx) {
        new Chart(itemCtx, {
            type: 'pie',
            data: {
                labels: @json($itemChartLabels),
                datasets: [{
                    data: @json($itemChartData),
                    backgroundColor: [
                        '#007bff', '#28a745', '#ffc107', '#dc3545',
                        '#17a2b8', '#6f42c1', '#fd7e14', '#20c997',
                        '#6610f2', '#343a40'
                    ]
                }]
            },
            options: {
                responsive: true,
                // maintainAspectRatio dirubah jadi false agar tidak meregang kebesaran di dalam card h-100
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
@endpush
