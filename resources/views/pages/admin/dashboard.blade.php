
@extends('layouts.admin')


@section('title')
    Store Dashboard
@endsection


@section('content')
{{-- Section Content  --}}
  
          <div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Admin Dashboard</h2>
                <p class="dashboard-subtitle">
                  Admin Irvianda Bakery Panel
                </p>
              </div>
              <div class="dashboard-content">
                <div class="row">
                  <div class="col-md-3">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div class="dashboard-card-title">Hari ini </div>
                        <div class="dashboard-card-subtitle">Rp{{ $days }}</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div class="dashboard-card-title"> Bulan {{ date('F') }}</div>
                        <div class="dashboard-card-subtitle"> Rp{{ $month }}</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div class="dashboard-card-title">Tahun {{ date('Y') }}</div>
                        <div class="dashboard-card-subtitle">Rp{{ $years }}</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div class="dashboard-card-title">Pelanggan</div>
                        <div class="dashboard-card-subtitle">{{ $customer }}</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-12 mt-2">
                    <form action="{{ route('admin-dashboard') }}" method="GET">
                      @csrf
                      <div class="row mb-3">
                        <div class="col-3">
                          <label for="">Tanggal Star</label>
                          <input type="date" name="start" required value="{{ request()->start }}" class="form-control">
                        </div>
                        <div class="col-3">
                          <label for="">Tanggal End</label>
                          <input type="date" name="end" required   value="{{ request()->end }}" class="form-control">
                        </div>
                        <div class="col-3">
                          <br>
                          <button type="submit"  class="btn btn-primary mt-2"> Cari</button>
  
                        </div>
                      </div>
                    </form>
                    <h5 class="mb-3">Daftar Transaksi</h5>
                    @forelse ($transaction_data as $transaction)
                        <a
                          href="{{route('dashboard-transaction-details',$transaction->id)}}"
                          class="card card-list d-block"
                        >
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-1">
                                <img src="{{ Storage::url($transaction->product->galleries->first()->photos ?? '') }}" class="w-75">
                                
                              </div>
                              <div class="col-md-4">{{  $transaction->product->name ?? ''}}</div>
                              <div class="col-md-3">{{  $transaction->transaction->user->name ?? ''}}</div>
                              <div class="col-md-3">{{  $transaction->created_at->format('d F Y') ?? ''}}</div>
                              <div class="col-md-1 d-none d-md-block">
                                <img
                                  src="/images/dashboard-arrow-right.svg"
                                  alt=""
                                />
                              </div>
                            </div>
                          </div>
                        </a>
                    @empty
                    <div class="card-body">
                      <div class="row">
                        <h4 class="text-center">Tidak Ada Transaksi</h4>
                      </div>
                    </div>
                    @endforelse  
                    @if ($transaction_count > 0)
                    <h4 class="text-center"> Rp{{ $transaction_sum }}</h4>
                        
                    @endif

                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection