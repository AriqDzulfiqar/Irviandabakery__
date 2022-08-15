
@extends('layouts.dashboard')


@section('title')
    Store Dashboard Transaction Detail
@endsection


@section('content')
  <!-- Section Content-->
          <div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">{{$transaction->code}}</h2>
                <p class="dashboard-subtitle">Transaksi Details</p>
              </div>
              <div class="dashboard-content" id="transactionDetails">
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-12 col-md-4">
                            <img
                              src="{{ Storage::url($transaction->product->galleries->first()->photos ?? '') }}"
                              class="w-100 mb-3"
                              alt=""
                            />
                          </div>
                          <div class="col-12 col-md-8">
                            <div class="row">
                              <div class="col-12 col-md-6">
                                <div class="product-title">Nama Customer</div>
                                <div class="product-subtitle">{{ $transaction->transaction->user->name ?? null }}</div>
                              </div>
                              <div class="col-12 col-md-6">
                                <div class="product-title">Nama Produk</div>
                                <div class="product-subtitle">{{ $transaction->product->name ?? null }}</div>
                              </div>
                              <div class="col-12 col-md-6">
                                <div class="product-title">Tanggal Transaksi</div>
                                <div class="product-subtitle">{{ $transaction->created_at }}</div>
                              </div>
                              <div class="col-12 col-md-6">
                                <div class="product-title">Status Pembayaran</div>
                                <div class="product-subtitle text-danger">{{ $transaction->transaction->transaction_status ?? null }}</div>
                              </div>
                              <div class="col-12 col-md-6">
                                  <div class="product-title">Total Harga</div>
                                  <div class="product-subtitle">Rp {{number_format($transaction->transaction->total_price)}}</div>

                                  <br>
                                  <div class="product-subtitle">
                                    Notes <br>
                                    {{ $transaction->transaction->notes ?? "-" }}
                                  </div>
                              </div>
                              <div class="col-12 col-md-6">
                                  <div class="product-title">Nomor Hp</div>
                                  <div class="product-subtitle">{{ $transaction->transaction->user->phone_number ?? null }}</div>
                              </div>
                              </div>
                            </div>
                          </div>
                          <form action="{{ route('dashboard-transaction-update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                          @csrf
                            <div class="row">
                            <div class="col-12 mt-4">
                              <h5>Informasi Pengiriman</h5>
                            </div>
                            <div class="col-12">
                              <div class="row">
                                <div class="col-12 col-md-6">
                                  <div class="product-title">Alamat</div>
                                  <div class="product-subtitle">{{$transaction->transaction->user->address ?? null}}</div>
                                </div>
                                <div class="col-12 col-md-6">
                                  <div class="product-title">Detil Alamat</div>
                                  <div class="product-subtitle">{{$transaction->transaction->user->detail_address ?? null}}</div>
                                </div>
                                <div class="col-12 col-md-6">
                                  <div class="product-title">Provinsi</div>
                                  <div class="product-subtitle">{{App\Province::find($transaction->transaction->user->provinces_id)->name ?? null}}</div>
                                </div> 
                                <div class="col-12 col-md-6">
                                  <div class="product-title">Kota</div>
                                  <div class="product-subtitle">{{App\City::find($transaction->transaction->user->regencies_id)->name ?? null}}</div>
                                </div>
                                <div class="col-12 col-md-6">
                                  <div class="product-title">Kode Pos</div>
                                  <div class="product-subtitle">{{$transaction->transaction->user->zip_code ?? null}}</div>
                                </div>
                                <div class="col-12 col-md-6">
                                  <div class="product-title">Negara</div>
                                  <div class="product-subtitle">{{$transaction->transaction->user->country ?? null}}</div>
                                </div>
                                <div class="col-12 col-md-3">
                                  <div class="product-title">Status Pengiriman</div>
                                  <select name="shipping_status" id="status" class="form-control" v-model="status">
                                    @if ($transaction->shipping_status == 'PENDING')
                                      <option value="PENDING" selected>Menunggu Pembayaran</option>
                                    @elseif ($transaction->shipping_status == 'SHIPPING')
                                      <option value="SHIPPING" selected>Pengiriman</option>
                                    @elseif ($transaction->shipping_status == 'SUCCESS')
                                    <option value="SUCCESS" selected>Success</option>
                                      @else
                                      <option value="">{{ $transaction->shipping_status }}</option>
                                    @endif
                                  </select>
                                </div>
                              
                              </div>
                            </div>
                          </div>
                          
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection


@push('addon-script')
  <script src="/vendor/vue/vue.js"></script>
    <script>
      var transactionDetails = new Vue({
        el: "#transactionDetails",
        data: {
          status: "{{ $transaction->shipping_status }}",
          resi: "{{ $transaction->resi }}",
        },
      });
    </script>
@endpush