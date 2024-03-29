@extends('layouts.app')

@section('title')
    Store Cart Page
@endsection

@section('content')
    <!-- Page Content -->
    <div class="page-content page-cart">
      <section
        class="store-breadcrumbs"
        data-aos="fade-down"
        data-aos-delay="100"
      >
        <div class="container">
          <div class="row">
            <div class="col-12">
              <nav>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                    <a href="{{route('home')}}">Home</a>
                  </li>
                  <li class="breadcrumb-item active">
                    Cart
                  </li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </section>

      <section class="store-cart">
        <div class="container">
          <div class="row" data-aos="fade-up" data-aos-delay="100">
            <div class="col-12 table-responsive">
              <table class="table table-borderless table-cart">
                <thead>
                  <tr>
                    <td>Image</td>
                    <td>Name &amp; Seller</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Menu</td>
                  </tr>
                </thead>
                <tbody>
                  @php $totalPrice = 0 @endphp
                  @foreach ($carts as $cart)
                    <tr>
                      <td style="width: 20%;">
                        @if($cart->product->galleries->count() > 0)
                          <img
                            src="{{ Storage::url($cart->product->galleries->first()->photos) }}"
                            alt=""
                            class="cart-image"
                          />
                        @endif
                      </td>
                      <td style="width: 25%;">
                        <div class="product-title">{{ $cart->product->name }}</div>
                        <div class="product-subtitle">by Irvianda Bakery</div>
                      </td>
                      <td style="width: 25%;">
                        <div class="product-title">Rp{{ number_format($cart->product->price) }}</div>
                        <div class="product-subtitle">Rupiah</div>
                      </td>
                      <td style="width: 15%;">
                        <div class="product-title">{{ $cart->quantity }}</div>
                        <div class="product-subtitle">Quantity</div>
                      </td>
                      <td style="width: 20%;">
                        <form action="{{ route('cart-delete',$cart->id) }}" method="POST">
                          @method('DELETE')
                          @csrf
                          <button class="btn btn-remove-cart" type="submit">
                            Hapus
                          </button>
                        </form>
                      </td>
                    </tr>
                    @php $totalPrice += $cart->product->price * $cart->quantity @endphp
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="row" data-aos="fade-up" data-aos-delay="150">
            <div class="col-12">
              <hr />
            </div>
            <div class="col-12 mb-4">
              <h2 class="">Detail Pengiriman</h2>
              <span >*Note : untuk sementara  hanya melayani daerah provinsi  jambi dan sumatra selatan saja</span>

            </div>
          </div>
          <form action="{{route('checkout')}}" enctype="multipart/form-data" method="POST" id="locations">
          @csrf            
            <input type="hidden"  id="price" value="{{ $totalPrice }}">
            <input type="hidden" name="total_price" id="total_price"  value="{{ $totalPrice }}">
            <input type="hidden" name="ongkir"  v-model="ongkir">
            <input type="hidden" name="ongkir_in"  v-model="ongkir_in">
            <div class="row mb-2" data-aos="fade-up" data-aos-delay="200" >
              <div class="col-md-6">
                <div class="form-group">
                  <label for="address">Alamat</label>
                  <input
                    type="text"
                    class="form-control"
                    id="address"
                    name="address"
                    value=""
                    required
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="detail_address">Detail alamat (Kelurahan & Kecamatan)</label>
                  <input
                    type="text"
                    class="form-control"
                    id="detail_address"
                    name="detail_address"
                    value=""
                    required
                  />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="provinces_id">Provinsi</label>
                  <select name="provinces_id" id="provinces_id" class="form-control" v-model="provinces_id" v-if="provinces">
                    <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                  </select>
                  <select v-else class="form-control"></select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="regencies_id">Kota</label>
                  <select name="regencies_id" id="regencies_id" required @change="CekOngkir()" class="form-control" v-model="regencies_id" v-if="regencies">
                    <option v-for="regency in regencies" :value="regency.city_id">@{{regency.name }}</option>
                  </select>
                  <select v-else class="form-control"></select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="zip_code">Kode Pos</label>
                  <input
                    type="text"
                    class="form-control"
                    id="zip_code"
                    name="zip_code"
                    value=""
                    required
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="country">Negara</label>
                  <input
                    type="text"
                    class="form-control"
                    id="country"
                    name="country"
                    value="Indonesia"
                    readonly
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="phone_number">Nomor Hp</label>
                  <input
                    type="text"
                    class="form-control"
                    id="phone_number"
                    name="phone_number"
                    value=""
                    required
                  />
                </div>
              </div>

            </div>
            <div class="row" data-aos="fade-up" data-aos-delay="150">
              <div class="col-12">
                <hr />
              </div>
              <div class="col-12">
                <h2 class="mb-1">Informasi Pembayaran</h2>
              </div>
            </div>

            <br>

            
              <div class="col-md-6" >
                <div class="form-group">
                  <label v-if="cost_in">Pilih jarak dan biaya pengiriman</label>
                    <select   class="form-control" v-model="penerima" required @change="GetOngkir()" v-if="cost_in">
                    <option value="1">Dalam Kelurahan Teratai - Gratis</option>
                    <option value="2">Dalam Wilayah Kecamatan Muara Bulian - Rp 10.000</option>
                    <option value="3">Diluar Kecamatan Bulian dan dalam Kabupaten Batanghari - Rp 12.500</option>
                    </select>

                    <label v-if="cost_out"> biaya pengiriman</label>
                    <select   class="form-control" v-model="ongkir" required @change="GetOngkir()" v-if="cost_out">
                      <option value="" v-if="costs.length == 0">Tidak tesedia pengiriman</option>
                      <option :value="value.cost[0].value" v-for="value in costs" v-else >@{{value.cost[0].value + ' | ' + value.service + ' (JNE)'}}</option>
                      
                      </select>
                      <span v-if="cost_out">*Note : Kalo pake kurir Expedisi biasanya jadi berantakan kuenya </span>
                      <br>

                    <label for="" v-if="cost_in">@{{ ongkir_in == 0 ? "Free Ongkir" : ongkir_in}}</label>
                </div>
              </div>
              {{-- <template v-if="penerima == 4 ">
                <div class="col-md-6 mb-3">
                  <div class="product-title">Masukan Ongkos Kirim</div>
                  <input type="text" class="form-control" v-on:keyup="InputOngkir" min="0" name="ongkir" v-model="ongkir">
                </div>
               
              </template> --}}
              <div class="col-md-12">
                <div class="form-group">
                  <label>Tambahkan catatan pembelian</label>
                  <textarea name="comment" placeholder="Masukan catatan atau variasi rasa roti jika ada" cols="20" rows="5" class="form-control"></textarea>
                </div>
                <p>Perhatian : Jika Roti kamu termasuk kategori Pre-Order, maka akan kami kirimkan hari besoknya, Terima Kasih !</p> 
              </div>

              <div class="col-4 col-md-4">
                <div class="product-title text-success" id="totalPembayaran">Rp{{ number_format($totalPrice ?? 0) }} </div>
                <div class="product-subtitle">Total Harga</div>
              </div>

              

              <div class="col-4 col-md-4">
                <button
                  type="submit"
                  class="btn btn-success mt-4 px-4 btn-block"
                >
                  Bayar Sekarang 
                </button>
              </div>
            </div>
          </form>
          
        </div>
      </section>
    </div>
@endsection

@push('addon-script')
    
<script src="/vendor/vue/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
      var locations = new Vue({
        el: "#locations",
        mounted() {
          AOS.init();
          this.getProvincesData();

        },
      
       
        data(){
          return{
          cost_in:false, //didalam masih statis
          cost_out:false, // diluar menggunakan raja ongkir
          costs:[], //menampung data ongkir
          penerima: null,
          ongkir_in: null,
          ongkir: null,
          provinces: null,
          regencies: null,
          input:null,
          provinces_id: null,
          regencies_id: null,
          }
        },
        methods: {
          GetOngkir(){
            
            
            var self = this;
            // Jika kota Batang Hari
             if(self.regencies_id == 50){
                if(this.penerima == 1){
                  this.ongkir_in = 0;
                }else if(this.penerima == 2){
                  this.ongkir_in = 10000;
                }else if(this.penerima == 3){
                  this.ongkir_in = 12500;
                }
                const price = document.getElementById('price').value ;
                const total = parseInt(price) + parseInt(this.ongkir_in)
                document.getElementById('totalPembayaran').innerHTML = `Rp ${total}`;
                document.getElementById('total_price').value = total;

             }else{
                const price = document.getElementById('price').value ;
                const total = parseInt(price) + parseInt(this.ongkir)
                document.getElementById('totalPembayaran').innerHTML = `Rp ${total}`;
                document.getElementById('total_price').value = total;
             }
            
            // console.log(self.city_id)
          },
          CekOngkir(){
            var self = this;
            
            // Jika kota Batang Hari
            if(self.regencies_id == 50){
              self.cost_in = true;
              self.cost_out = false;
              // Jika kota lain
            }else{
              // self.cost_out = true;
              self.cost_in = false;

              // Hit cek ongkir
             axios.post("{{ route('api-checkOngkir') }}", {
              regencies_id: self.regencies_id, // <-- ID kota tujuan
              })
                .then((response) => {
                
                  // set state cost menjadi true, untuk menampilkan pilihan cost pengiriman
                  self.cost_out = true;
                  //assign state costs dengan hasil response
                  self.costs = response.data.data[0].costs;
                })
                .catch((error) => {
                  console.log(error);
                });

            }
            
            // console.log(self.city_id)
          },
          // InputOngkir(){
            
          //   const price = document.getElementById('price').value ;

          //   if(this.ongkir == ""){
          //     document.getElementById('totalPembayaran').innerHTML = `Rp ${parseInt(price)}`;
          //   }else{
          //     const total = parseInt(price) + parseInt(this.ongkir)

          //   document.getElementById('totalPembayaran').innerHTML = `Rp ${parseInt(price) + parseInt(this.ongkir)}`;
          //   document.getElementById('total_price').value = total;

          //   }
          //   // console.log(self.city_id)
          // },
          getProvincesData() {
            var self = this;
            axios.get('{{ route('api-provinces') }}')
              .then(function (response) {
                console.log(response.data)
                  self.provinces = response.data;
              })
          },
          getRegenciesData() {
            var self = this;
            axios.get('{{ url('api/regencies') }}/' + self.provinces_id)
              .then(function (response) {
                  self.regencies = response.data;
              })
          },
        },
        watch: {
          provinces_id: function (val, oldVal) {
            this.regencies_id = null;
            this.getRegenciesData();
          },
        }
      });
    </script>
@endpush

