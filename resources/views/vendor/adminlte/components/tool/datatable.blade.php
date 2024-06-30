{{-- Table --}}

<div class="table-responsive">

    <table id="{{ $id }}" style="width:100%" {{ $attributes->merge(['class' => $makeTableClass()]) }}>

        {{-- Table head --}}
        <thead @isset($headTheme) class="thead-{{ $headTheme }}" @endisset>
            <tr>
                @foreach ($heads as $th)
                    <th @isset($th['width']) style="width:{{ $th['width'] }}%" @endisset
                        @isset($th['no-export']) dt-no-export @endisset>
                        {{ is_array($th) ? $th['label'] ?? '' : $th }}
                    </th>
                @endforeach
            </tr>
        </thead>

        {{-- Table body --}}
        <tbody>{{ $slot }}</tbody>

        {{-- Table footer --}}
        @isset($withFooter)
            <tfoot @isset($footerTheme) class="thead-{{ $footerTheme }}" @endisset>
                <tr>
                    @foreach ($heads as $th)
                        <th>{{ is_array($th) ? $th['label'] ?? '' : $th }}</th>
                    @endforeach
                </tr>
            </tfoot>
        @endisset

    </table>

</div>

{{-- Add plugin initialization and configuration code --}}

@push('js')
    @if (isset($withFooter) && $withFooter == 'groups')
        <script>
            $(() => {
                let {{ $id }} = $('#{{ $id }}').DataTable(
                        {!! substr(json_encode($config), 0, -1) !!},
                        "footerCallback": function(tfoot, data, start, end, display) {
                            const api = this.api();
                            let value = 0;
                            let quantity = 0;
                            data.forEach(el => {
                                value += parseFloat((el['value']).replace(/^R\$|\s/g, '').replace(/\./g, '')
                                    .replace(',', '.'));
                                quantity += el['quantity'];
                            });

                            value = value.toLocaleString('pt-br', {
                                style: 'currency',
                                currency: 'BRL'
                            });

                            $(api.column(0).footer()).html('');
                            $(api.column(1).footer()).html('');
                            $(api.column(2).footer()).html('');
                            $(api.column(3).footer()).html(value);
                            $(api.column(4).footer()).html(quantity);

                            $(tfoot).html(
                                `
                                <th colspan="3" class="text-center"></th>
                                <th colspan="1" class="text-center">Total: ${value}</th>
                                <th colspan="1" class="text-center">Total: ${quantity}</th>`
                            );
                        }
                    },
            );
            })
        </script>
    @elseif (isset($withFooter) && $withFooter == 'materials')
        <script>
            $(() => {
                let {{ $id }} = $('#{{ $id }}').DataTable(
                        {!! substr(json_encode($config), 0, -1) !!},
                        "footerCallback": function(tfoot, data, start, end, display) {
                            const api = this.api();
                            let value = 0;;
                            data.forEach(el => {
                                value += parseFloat((el['value']).replace(/^R\$|\s/g, '').replace(/\./g, '')
                                    .replace(',', '.'));
                            });

                            value = value.toLocaleString('pt-br', {
                                style: 'currency',
                                currency: 'BRL'
                            });

                            $(api.column(0).footer()).html('');
                            $(api.column(1).footer()).html('');
                            $(api.column(2).footer()).html('');
                            $(api.column(3).footer()).html('');
                            $(api.column(4).footer()).html('');
                            $(api.column(5).footer()).html(value);

                            $(tfoot).html(
                                `
                             <th colspan="5" class="text-center"></th>
                             <th colspan="1" class="text-center">Total: ${value}</th>
                             <th colspan="1" class="text-center"></th>
                             <th colspan="1" class="text-center"></th>`
                            );
                        }
                    },
            );
            })
        </script>
    @elseif (isset($withFooter) && $withFooter == 'materials_user')
        <script>
            $(() => {
                let {{ $id }} = $('#{{ $id }}').DataTable(
                        {!! substr(json_encode($config), 0, -1) !!},
                        "footerCallback": function(tfoot, data, start, end, display) {
                            const api = this.api();
                            let value = 0;;
                            data.forEach(el => {
                                value += parseFloat((el['value']).replace(/^R\$|\s/g, '').replace(/\./g, '')
                                    .replace(',', '.'));
                            });

                            value = value.toLocaleString('pt-br', {
                                style: 'currency',
                                currency: 'BRL'
                            });

                            $(api.column(0).footer()).html('');
                            $(api.column(1).footer()).html('');
                            $(api.column(2).footer()).html('');
                            $(api.column(3).footer()).html(value);
                            $(tfoot).html(
                                `
                              <th colspan="3" class="text-center"></th>
                              <th colspan="1" class="text-center">Total: ${value}</th>`
                            );
                        }
                    },
            );
            })
        </script>
    @else
        <script>
            $(() => {
                $('#{{ $id }}').DataTable(@json($config));
            })
        </script>
    @endif
@endpush

{{-- Add CSS styling --}}

@isset($beautify)
    @push('css')
        <style type="text/css">
            #{{ $id }} tr td,
            #{{ $id }} tr th {
                vertical-align: middle;
                text-align: center;
            }
        </style>
    @endpush
@endisset
