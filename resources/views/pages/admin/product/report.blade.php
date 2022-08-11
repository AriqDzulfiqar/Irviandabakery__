
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
                        <div class="dashboard-card-subtitle">{{ $days }}</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div class="dashboard-card-title"> Bulan {{ date('F') }}</div>
                        <div class="dashboard-card-subtitle"> {{ $month }}</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div class="dashboard-card-title">Tahun {{ date('Y') }}</div>
                        <div class="dashboard-card-subtitle">{{ $years }}</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div class="dashboard-card-title">FAILED</div>
                        <div class="dashboard-card-subtitle">{{ $failed }}</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-12 mt-2">
                    <form action="{{ route('report-product.index') }}" method="GET">
                      @csrf
                      <div class="row">
                        <div class="col-md-6">
                          <label for="">Pilih Select</label>
                          <select name="pilih" id="pilih" class="form-control mb-3">
                            <option value="tanggal" {{ request()->pilih == "tanggal" ? "selected" : ""}}>Tanggal</option>
                            <option value="bulan"  {{ request()->pilih == "bulan" ? "selected" : ""}}>Bulan</option>
                            <option value="tahun"  {{ request()->pilih == "tahun" ? "selected" : ""}}>Tahun</option>
                          </select>
                        </div>
                      </div>
                      <div class="row mb-3" >
                        <div class="col-3 bulan" style="display: none">
                          <label for="">Pilih Bulan</label>
                          <select name="bulan" id="bulan" class="form-control bulan">
                            <option value="">Pilih Bulan</option>
                            <option value="01" {{ request()->bulan == "01" ? "selected" : "" }}>Januari</option>
                            <option value="02"  {{ request()->bulan == "02" ? "selected" : "" }}>Februari</option>
                            <option value="03"  {{ request()->bulan == "03" ? "selected" : "" }}>Maret</option>
                            <option value="04"  {{ request()->bulan == "04" ? "selected" : "" }}>April</option>
                            <option value="05"  {{ request()->bulan == "05" ? "selected" : "" }}>Mei</option>
                            <option value="06"  {{ request()->bulan == "06" ? "selected" : "" }}>Juni</option>
                            <option value="07"  {{ request()->bulan == "07" ? "selected" : "" }}>Juli</option>
                            <option value="08"  {{ request()->bulan == "08" ? "selected" : "" }}>Agustus</option>
                            <option value="09"  {{ request()->bulan == "09" ? "selected" : "" }}>September</option>
                            <option value="10"  {{ request()->bulan == "10" ? "selected" : "" }}>Oktober</option>
                            <option value="11"  {{ request()->bulan == "11" ? "selected" : "" }}>November</option>
                            <option value="12"  {{ request()->bulan == "12" ? "selected" : "" }}>Desember</option>
                          </select>
                        </div>
                        <div class="col-3 tahun" style="display: none">
                          <label for="">Pilih Tahun</label>
                          <select name="tahun" id="tahun" class="form-control tahun">
                            <option value="">Pilih Tahun</option>
                            @for ($i = 2019; $i <= date('Y'); $i++)
                              <option value="{{ $i }}" {{ request()->tahun == $i ? "selected" : "" }}>{{ $i }}</option>
                            @endfor
                          </select>
                        </div>
                        <div class="col-3 tanggal" >
                          <label for="">Tanggal Start</label>
                          <input type="date" name="start"  value="{{ request()->start }}" class="form-control start">
                        </div>
                        <div class="col-3 tanggal">
                          <label for="">Tanggal End</label>
                          <input type="date" name="end"    value="{{ request()->end }}" class="form-control end">
                        </div>
                        <div class="col-3">
                          <br>
                          <button type="submit"  class="btn btn-primary mt-2"> Cari</button>
  
                        </div>
                      </div>
                    </form>
                    <h5 class="mb-3">Daftar Produk Best Sellert</h5>
                    @php
                         $globalTransaction = 0;
                    @endphp
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-1">
                          </div>
                          <div class="col-md-3">
                            photo
                          </div>
                          <div class="col-md-2">Price</div>
                          <div class="col-md-3">Quantity</div>
                          <div class="col-md-3">Product</div>
                         
                        </div>
                      </div>
                    @forelse ($transaction_data as $transaction)
                        <a
                          href="javascript:void(0)"
                          class="card card-list d-block"
                        >
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-1">{{  $loop->iteration  }}</div>

                              <div class="col-md-3">
                                <img src="{{ Storage::url($transaction->product->galleries->first()->photos ?? '') }}" class="w-75">
                                
                              </div>
                              <div class="col-md-2">{{  $transaction->product->price ?? ''  }}</div>
                              <div class="col-md-3">{{  $transaction->sum ?? ''}}</div>
                              <div class="col-md-3">{{  $transaction->product->name ?? ''}}</div>
                              
                            </div>
                          </div>
                        </a>
                        @php
                       
                          $globalTransaction += $transaction->sum;
                        @endphp
                    @empty
                    <div class="card-body">
                      <div class="row justify-content-center">
                       <div class="col-md-4">
                        <h4 class="text-center">Tidak Ada Produk</h4>
                       </div>
                      </div>
                    </div>
                    @endforelse  
                    @if ($transaction_count > 0)
                    @if (request()->start && request()->end || request()->bulan || request()->tahun)
                        
                    <h4 class="ml-3">Total :  {{ $globalTransaction }}</h4>
                    @endif
                        
                    @endif

                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection

@push('addon-script')
    <script>
        $(document).ready(function(){
         const pilih = $("#pilih").val();
          if(pilih == "bulan"){
            $(".bulan").show();
            $(".tahun").hide();
            $(".tanggal").hide();
          }else if(pilih == "tahun"){
            $(".bulan").hide();
            $(".tahun").show();
            $(".tanggal").hide();
          }else if(pilih == "tanggal"){
            $(".bulan").hide();
            $(".tahun").hide();
            $(".tanggal").show();
          }

            $('#pilih').change(function(){
                if($(this).val() == 'tanggal'){
                    $('.bulan').hide();
                    $('.tahun').hide();
                    $('.bulan').val("");
                    $('.tahun').val("");
                    $('.tanggal').show();
                }else if($(this).val() == 'bulan'){
                    $('.bulan').show();
                    $('.tahun').hide();
                    $('.tanggal').hide();
                    $('.start').val("");
                    $('.end').val("");
                    $('.tahun').val("");

                }else if($(this).val() == 'tahun'){
                    $('.bulan').hide();
                    $('.tahun').show();
                    $('.tanggal').hide();
                    $('.start').val("");
                    $('.end').val("");
                    $('.bulan').val("");

                }
            });
        });
    </script>
@endpush