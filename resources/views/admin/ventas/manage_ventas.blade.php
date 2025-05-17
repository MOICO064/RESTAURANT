    @extends('layouts.main')

    @section('content')
        <style>
            /* Alturas máximas con scroll */
            #panel-left .tab-content,
            #item-list {
                max-height: 50vh;
                overflow-y: auto;
            }

            @media (min-width: 768px) {

                #panel-left .tab-content,
                #item-list {
                    max-height: 60vh;
                }
            }

            /* Fondo semitransparente */
            #panel-left,
            #item-list {
                background: rgba(255, 255, 255, 0.17);
            }
        </style>

        <div class="content py-3">
            <div class="container-fluid">

                <div class="card shadow">
                    <div class="card-header bg-gradient-navy text-white">
                        <h5 class="mb-0">
                            {{ $sale ? "Actualizar Venta #{$sale->code}" : "Nueva Venta" }}
                        </h5>
                    </div>

                    <div class="card-body ">
                        <form id="sale-form" novalidate>
                            @csrf
                            <input type="hidden" name="id" value="{{ $sale->id ?? '' }}">
                            <input type="hidden" name="amount" value="{{ $sale->amount ?? '' }}">

                            <div class="form-group">
                                <label for="client_name">Nombre del Cliente</label>
                                <input type="text" name="client_name" id="client_name" class="form-control"
                                    placeholder="Cliente" value="{{ old('client_name', $sale->client_name ?? '') }}"
                                    required>
                            </div>

                            <div id="sales-panel" class="border rounded p-2 bg-gradient-navy">
                                <div class="row">

                                    {{-- PRODUCTOS --}}
                                    <div class="col-12 col-sm-6 col-md-7 mb-3 mb-md-0">
                                        <ul class="nav nav-tabs bg-gradient-navy-dark p-0 pt-1" role="tablist">
                                            @foreach($categories as $i => $cat)
                                                <li class="nav-item">
                                                    <a class="nav-link {{ $i === 0 ? 'active' : '' }}" data-toggle="pill"
                                                        href="#cat-{{ $cat->id }}">{{ $cat->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content border-top pt-3" id="panel-left">
                                            @foreach($categories as $i => $cat)
                                                <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}"
                                                    id="cat-{{ $cat->id }}">
                                                    <div class="row">
                                                        @foreach($products->get($cat->id, []) as $prod)
                                                            <div class="col-6 col-lg-4 mb-2">
                                                                <a href="javascript:void(0)"
                                                                    class="btn btn-light btn-block prod-item text-truncate"
                                                                    data-id="{{ $prod->id }}" data-price="{{ $prod->price }}">
                                                                    <div class="card-body text-center p-0 m-0">{{ $prod->name }}</div>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- CARRITO --}}
                                    <div class="col-12 col-sm-6 col-md-5">
                                        <div class="table-responsive" id="item-list">
                                            <table class="table table-sm table-bordered mb-2" id="product-list">
                                                <thead class="thead-dark bg-gradient-navy-dark text-center">
                                                    <tr>
                                                        <th style="width:15%">Cant.</th>
                                                        <th style="width:40%">Producto</th>
                                                        <th style="width:15%">Precio</th>
                                                        <th style="width:20%">SubTotal</th>
                                                        <th style="width:10%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($sale)
                                                        @foreach($sale->products as $item)
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="product_id[]"
                                                                        value="{{ $item->product_id }}">
                                                                    <input type="hidden" name="product_price[]"
                                                                        value="{{ $item->price }}">
                                                                    <input type="number" name="product_qty[]"
                                                                        class="form-control form-control-sm text-center"
                                                                        value="{{ $item->qty }}" min="1" required>
                                                                </td>
                                                                <td>
                                                                    <div>{{ $item->product->name }}</div>                                                                    
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ number_format($item->price, 2) }}
                                                                </td>
                                                                <td class="text-right product_total">
                                                                    {{ number_format($item->qty * $item->price, 2) }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm rem-product">&times;</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="mt-3 d-flex justify-content-between text-white">
                                            <h3><strong>Total:</strong></h3>
                                            <h3><span id="amount">{{ number_format($sale->amount ?? 0, 2) }}</span></h3>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-6">
                                                <label class="text-white">Pagado</label>
                                                <input type="text" id="tendered" name="tendered"
                                                    class="form-control form-control-sm text-right"
                                                    value="{{ old('tendered', $sale->tendered ?? 0) }}" required>
                                            </div>
                                            <div class="form-group col-6">
                                                <label class="text-white">Cambio</label>
                                                <input type="text" id="change" class="form-control form-control-sm text-right"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-6">
                                                <label class="text-white">Tipo Pago</label>
                                                <select id="payment_type" name="payment_type"
                                                    class="form-control form-control-sm" required>
                                                    <option value="1" {{ ($sale->payment_type ?? '') == 1 ? 'selected' : '' }}>
                                                        Cash
                                                    </option>
                                                    <option value="2" {{ ($sale->payment_type ?? '') == 2 ? 'selected' : '' }}>
                                                        Debit
                                                        Card</option>
                                                    <option value="3" {{ ($sale->payment_type ?? '') == 3 ? 'selected' : '' }}>
                                                        Credit
                                                        Card</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-6 d-none" id="code">
                                                <label class="text-white">Código</label>
                                                <input type="text" id="payment_code" name="payment_code"
                                                    class="form-control form-control-sm d-none" placeholder="Código"
                                                    value="{{ old('payment_code', $sale->payment_code ?? '') }}">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="mt-3 text-right">
                                <button class="btn btn-primary">Guardar Venta</button>
                                <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <noscript id="product-clone">
            <tr>
                <td>
                    <input type="hidden" name="product_id[]">
                    <input type="hidden" name="product_price[]">
                    <input type="number" name="product_qty[]" class="form-control form-control-sm text-center" value="1"
                        min="1">
                </td>
                <td><span class="product_name">Producto</span> </td>
                <td class="text-center product_price"> 0.00</td>
                <td class="text-right product_total">0.00</td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm rem-product">&times;</button></td>
            </tr>
        </noscript>
    @endsection



    @section('scripts')
        <script>
            function calc_change() {
                let amount = parseFloat($('[name="amount"]').val()) || 0;
                let tendered = parseFloat($('#tendered').val()) || 0;
                $('#change').val((tendered - amount).toLocaleString('en-US', { minimumFractionDigits: 2 }));
            }
            function calc_total_amount() {
                let total = 0;
                $('#product-list tbody tr').each(function () {
                    let price = parseFloat($(this).find('[name="product_price[]"]').val()) || 0;
                    let qty = parseFloat($(this).find('[name="product_qty[]"]').val()) || 0;
                    total += price * qty;
                });
                $('[name="amount"]').val(total);
                $('#amount').text(total.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                calc_change();
            }
            $(function () {
                $('#payment_type').change(function () {
                    if (this.value == 1) $('#payment_code').addClass('d-none').prop('required', false);
                    else $('#payment_code').removeClass('d-none').prop('required', true)
                    $('#code').removeClass('d-none');
                });
                $('body').on('click', '.rem-product', function () {
                    $(this).closest('tr').remove();
                    calc_total_amount();
                });
                $('body').on('change input', '[name="product_qty[]"]', function () {
                    let tr = $(this).closest('tr');
                    let price = parseFloat(tr.find('[name="product_price[]"]').val()) || 0;
                    let qty = parseFloat(this.value) || 0;
                    tr.find('.product_total').text((price * qty).toLocaleString('en-US', { minimumFractionDigits: 2 }));
                    calc_total_amount();
                });
                $('.prod-item').on('click', function () {
                    // Obtener ID y precio directamente de los atributos
                    const id = $(this).attr('data-id');
                    const price = parseFloat($(this).attr('data-price')) || 0;
                    // Tomar el nombre sólo del contenido del .card-body
                    const name = $(this).find('.card-body').text().trim();

                    // Si ya existe, avisar
                    if ($('#product-list [name="product_id[]"][value="' + id + '"]').length) {
                        return alert_toast( ' El producto ya se encuentra en la lista', 'error');
                    }

                    // Clonar la fila de plantilla
                    const tr = $($('#product-clone').html());

                    // Rellenar valores
                    tr.find('[name="product_id[]"]').val(id);
                    tr.find('[name="product_price[]"]').val(price.toFixed(2));
                    tr.find('.product_name').text(name);
                    tr.find('.product_price').text(price.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                    tr.find('.product_total').text(price.toLocaleString('en-US', { minimumFractionDigits: 2 }));

                    // Añadir al listado y recalcular
                    $('#product-list tbody').append(tr);
                    calc_total_amount();
                });


                $('#tendered').on('input', calc_change);

                $('#client_name').on('input', function () {
                    const val = $(this).val().trim();
                    const form = $('#sale-form');

                    // Elimina el mensaje solo si ya es válido
                    if (val !== '') {
                        $('.err-msg').each(function () {
                            if ($(this).text().includes('Debe ingresar el nombre del cliente')) {
                                $(this).remove();
                            }
                        });
                    } else {
                        // Si está vacío o con solo espacios, agrega el error si no existe ya
                        if ($('.err-msg').filter(function () {
                            return $(this).text().includes('Debe ingresar el nombre del cliente');
                        }).length === 0) {
                            const el = $('<div>').addClass('alert alert-danger err-msg').text('Debe ingresar el nombre del cliente');
                            form.prepend(el);
                        }
                    }
                });


                $(document).on('click', '.prod-item', function () {
                    // Si había una alerta de "Debe agregar al menos un producto", elimínala
                    $('.err-msg').each(function () {
                        if ($(this).text().includes('Debe agregar al menos un producto')) {
                            $(this).remove();
                        }
                    });
                });

                $('#tendered').on('input', function () {
                    const amount = parseFloat($('#amount').text().replace(/,/g, ''));
                    const tendered = parseFloat($(this).val());

                    const existingAlert = $('.err-msg').filter(function () {
                        return $(this).text().includes('El monto pagado no puede ser menor al total.');
                    });

                    if (!isNaN(tendered) && tendered >= amount) {
                        // Si el monto es suficiente, eliminar alerta si existe
                        existingAlert.remove();
                    } else {
                        // Si el monto es insuficiente o inválido y aún no hay alerta, mostrarla
                        if (existingAlert.length === 0) {
                            let el = $('<div>').addClass('alert alert-danger err-msg').text('El monto pagado no puede ser menor al total.');
                            $('#sale-form').prepend(el);
                        }
                    }
                });


                $('#sale-form').submit(function (e) {
                    e.preventDefault();
                    $('.err-msg').remove();
                    // Validación: nombre del cliente no puede estar vacío
                    const clientName = $('#client_name').val().trim();
                    if (clientName === '') {
                        let el = $('<div>').addClass('alert alert-danger err-msg').text('Debe ingresar el nombre del cliente.');
                        $('#sale-form').prepend(el);
                        $('html, body').scrollTop(0);
                        return;
                    }
                    // Validación: debe haber al menos un producto
                    if ($('#product-list tbody tr').length === 0) {
                        let el = $('<div>').addClass('alert alert-danger err-msg').text('Debe agregar al menos un producto.');
                        $('#sale-form').prepend(el);
                        $('html, body').scrollTop(0);
                        return;
                    }

                    // Validación: monto pagado debe ser suficiente
                    const amount = parseFloat($('#amount').text().replace(/,/g, ''));
                    const tendered = parseFloat($('#tendered').val());
                    if (isNaN(tendered) || tendered < amount) {
                        let el = $('<div>').addClass('alert alert-danger err-msg').text('El monto pagado no puede ser menor al total.');
                        $('#sale-form').prepend(el);
                        $('html, body').scrollTop(0);
                        return;
                    }

                    start_loader();

                    $.ajax({
                        url: '/admin/ventas', // <-- asegúrate de colocar la URL correcta aquí
                        method: 'POST',
                        data: new FormData(this),
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        error(err) {
                            console.log(err);
                            alert_toast('Ocurrió un error', 'error');
                            end_loader();
                        },
                        success(resp) {
                            if (resp.status === 'success') {
                                window.location = '/admin/ventas/' + resp.sid + '/show';
                            } else if (resp.status === 'failed' && resp.msg) {
                                let el = $('<div>').addClass('alert alert-danger err-msg').text(resp.msg);
                                $('#sale-form').prepend(el);
                                $('html,body').scrollTop(0);
                            } else {
                                alert_toast('Ocurrió un error', 'error');
                            }
                            end_loader();
                        }
                    });
                });

            });
        </script>
    @endsection