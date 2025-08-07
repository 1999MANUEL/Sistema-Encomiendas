
{{-- resources/views/forms/components/parcel-repeater.blade.php --}}

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        {{-- Aquí va el HTML de tu tabla de paquetes --}}
        <div class="fi-fo-repeater-grid gap-y-8">
            <div class="fi-fo-repeater-item-wrapper border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="text-xl font-bold mb-4">Detalle de Paquetes</div>

                <table class="w-full text-left table-auto border-collapse" id="parcel-items">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b-2 border-gray-300 dark:border-gray-600">Peso</th>
                            <th class="px-4 py-2 border-b-2 border-gray-300 dark:border-gray-600">Altura</th>
                            <th class="px-4 py-2 border-b-2 border-gray-300 dark:border-gray-600">Largo</th>
                            <th class="px-4 py-2 border-b-2 border-gray-300 dark:border-gray-600">Ancho</th>
                            <th class="px-4 py-2 border-b-2 border-gray-300 dark:border-gray-600">Precio</th>
                            <th class="px-4 py-2 border-b-2 border-gray-300 dark:border-gray-600"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($field->getState() && is_array($field->getState()))
                            {{-- Si el ViewField tiene un estado (ej. al editar) --}}
                            @foreach($field->getState() as $index => $item)
                                <tr data-id="{{ $index }}">
                                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                        <input type="number" step="0.01" name="weight[]" class="w-full p-2 border rounded-md number" value="{{ $item['weight'] ?? '' }}" placeholder="kg">
                                    </td>
                                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                        <input type="number" step="0.01" name="height[]" class="w-full p-2 border rounded-md number" value="{{ $item['height'] ?? '' }}" placeholder="cm">
                                    </td>
                                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                        <input type="number" step="0.01" name="length[]" class="w-full p-2 border rounded-md number" value="{{ $item['length'] ?? '' }}" placeholder="cm">
                                    </td>
                                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                        <input type="number" step="0.01" name="width[]" class="w-full p-2 border rounded-md number" value="{{ $item['width'] ?? '' }}" placeholder="cm">
                                    </td>
                                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                        <input type="text" name="price[]" class="w-full p-2 border rounded-md number price-input" value="{{ number_format($item['price'] ?? 0, 2, ',', '.') }}" placeholder="Bs.">
                                    </td>
                                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">
                                        <button type="button" class="text-danger-600 hover:text-danger-700 delete-row">
                                            <x-heroicon-o-x-circle class="w-6 h-6" />
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr data-id="0">
                                <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <input type="number" step="0.01" name="weight[]" class="w-full p-2 border rounded-md number" placeholder="kg">
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <input type="number" step="0.01" name="height[]" class="w-full p-2 border rounded-md number" placeholder="cm">
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <input type="number" step="0.01" name="length[]" class="w-full p-2 border rounded-md number" placeholder="cm">
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <input type="number" step="0.01" name="width[]" class="w-full p-2 border rounded-md number" placeholder="cm">
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <input type="text" name="price[]" class="w-full p-2 border rounded-md number price-input" placeholder="Bs.">
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">
                                    <button type="button" class="text-danger-600 hover:text-danger-700 delete-row">
                                        <x-heroicon-o-x-circle class="w-6 h-6" />
                                    </button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right px-4 py-2 font-bold">Total:</td>
                            <td class="px-4 py-2 font-bold" id="tAmount">Bs. 0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <button type="button" id="new_parcel" class="fi-btn fi-btn-size-md fi-btn-variant-outlined fi-btn-color-gray w-auto mt-4">
                    <span class="fi-btn-label">Añadir Paquete</span>
                </button>
            </div>
        </div>

        <table class="hidden" id="ptr_clone">
            <tbody>
                <tr data-id="">
                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <input type="number" step="0.01" name="weight[]" class="w-full p-2 border rounded-md number" placeholder="kg">
                    </td>
                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <input type="number" step="0.01" name="height[]" class="w-full p-2 border rounded-md number" placeholder="cm">
                    </td>
                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <input type="number" step="0.01" name="length[]" class="w-full p-2 border rounded-md number" placeholder="cm">
                    </td>
                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <input type="number" step="0.01" name="width[]" class="w-full p-2 border rounded-md number" placeholder="cm">
                    </td>
                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <input type="text" name="price[]" class="w-full p-2 border rounded-md number price-input" placeholder="Bs.">
                    </td>
                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">
                        <button type="button" class="text-danger-600 hover:text-danger-700 delete-row">
                            <x-heroicon-o-x-circle class="w-6 h-6" />
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Campos ocultos para enviar los totales calculados por JS a Filament --}}
        <input type="hidden" name="total_precio_js" id="total_precio_js" value="0">
        <input type="hidden" name="total_peso_js" id="total_peso_js" value="0">
        <input type="hidden" name="total_altura_js" id="total_altura_js" value="0">
        <input type="hidden" name="total_ancho_js" id="total_ancho_js" value="0">
        <input type="hidden" name="total_largo_js" id="total_largo_js" value="0">
        <input type="hidden" name="cantidad_paquetes_js" id="cantidad_paquetes_js" value="1">


        {{-- Script JavaScript --}}
        <script>
            // Asegúrate de que jQuery esté disponible en el entorno de Filament.
            // Filament usa Alpine.js y Livewire. Si jQuery no está ya cargado globalmente,
            // podría ser necesario incluirlo o reescribir esto en Alpine.js puro.
            // Para probar rápido, asumamos que jQuery está disponible.

            function updateHiddenFields() {
                var totalPrecio = 0;
                var totalPeso = 0;
                var maxAltura = 0;
                var maxAncho = 0;
                var maxLargo = 0;
                var cantidadPaquetes = 0;

                $('#parcel-items tbody tr').each(function() {
                    cantidadPaquetes++;
                    var row = $(this);
                    var price = parseFloat(row.find('input[name="price[]"]').val().replace(/[^0-9.]/g, '')) || 0;
                    var weight = parseFloat(row.find('input[name="weight[]"]').val()) || 0;
                    var height = parseFloat(row.find('input[name="height[]"]').val()) || 0;
                    var length = parseFloat(row.find('input[name="length[]"]').val()) || 0;
                    var width = parseFloat(row.find('input[name="width[]"]').val()) || 0;

                    totalPrecio += price;
                    totalPeso += weight;
                    maxAltura = Math.max(maxAltura, height);
                    maxAncho = Math.max(maxAncho, width);
                    maxLargo = Math.max(maxLargo, length);
                });

                $('#total_precio_js').val(totalPrecio.toFixed(2));
                $('#total_peso_js').val(totalPeso.toFixed(2));
                $('#total_altura_js').val(maxAltura.toFixed(2));
                $('#total_ancho_js').val(maxAncho.toFixed(2));
                $('#total_largo_js').val(maxLargo.toFixed(2));
                $('#cantidad_paquetes_js').val(cantidadPaquetes);

                // También actualiza el display visible de Filament
                // Necesitamos acceder al state de Filament. Es más complejo si el input visible es de Filament.
                // Si el input visible es solo tu #tAmount, entonces no hay problema.
                // Para actualizar un campo de Filament, necesitarías emitir un evento Livewire.
                // Por ahora, el `total_precio_display` de Filament solo se cargará al inicio y al guardar.
                // La reactividad visible en la UI la hará tu `calc()` en `#tAmount`.
                // Si quieres que el `total_precio_display` de Filament se actualice también,
                // necesitarías Livewire.emit('updateFormField', 'total_precio_display', totalPrecio);
                // Pero eso empieza a mezclar lógicas. Lo más simple es que #tAmount sea el único display dinámico.
            }

            function calc() {
                updateHiddenFields(); // Primero actualiza los campos ocultos
                var total = parseFloat($('#total_precio_js').val()) || 0; // Lee del campo oculto
                $('#tAmount').text('Bs. ' + total.toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            }

            // Función para formatear números mientras se escribe
            function formatNumberInput(input) {
                var val = input.val();
                val = val.replace(/[^0-9.,]/g, ''); // Permitir solo números, puntos y comas
                // Normalizar a punto decimal para cálculos JS
                val = val.replace(/\./g, ''); // Eliminar puntos de miles
                val = val.replace(/,/g, '.'); // Reemplazar coma decimal por punto
                
                var parts = val.split('.');
                if (parts.length > 2) { // Evitar múltiples puntos decimales
                    val = parts[0] + '.' + parts.slice(1).join('');
                }

                // Para mostrar, volver a formato local (ej. 1.234,56)
                if (val.length > 0) {
                     var num = parseFloat(val);
                     if (!isNaN(num)) {
                         // Formatear para visualización
                         input.val(num.toLocaleString('es-BO', { minimumFractionDigits: 0, maximumFractionDigits: 2 }));
                     } else {
                         input.val(''); // Limpiar si no es un número válido
                     }
                 } else {
                    input.val('');
                 }
                calc(); // Recalcular al formatear
            }


            $(document).ready(function() {
                // Inicializar cálculo al cargar la página
                calc();

                // Manejar la adición de nuevas filas
                $(document).on('click', '#new_parcel', function() {
                    var tr = $('#ptr_clone tbody tr').clone();
                    tr.find('input').val(''); // Limpiar valores clonados
                    var newId = $('#parcel-items tbody tr').length;
                    tr.attr('data-id', newId);
                    $('#parcel-items tbody').append(tr);
                    
                    // Re-adjuntar eventos a los nuevos inputs
                    tr.find('.price-input').on('keyup input', function() {
                        formatNumberInput($(this));
                    });
                    tr.find('.number').on('keyup input', function() { // Apply to all number inputs
                        formatNumberInput($(this));
                    });
                    calc(); // Recalcular después de añadir una fila
                });

                // Manejar la eliminación de filas
                $(document).on('click', '.delete-row', function() {
                    $(this).closest('tr').remove();
                    calc(); // Recalcular después de eliminar una fila
                });

                // Manejar la entrada de precios y otros números
                $(document).on('keyup input', '.number', function() {
                    formatNumberInput($(this));
                });
            });
        </script>
    </div>
</x-dynamic-component>