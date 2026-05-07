<x-admin-layout tittle="Horario del Doctor" :breadcrumbs="[
    [ 'name' => 'Dashboard', 'href' => route('admin.dashboard') ],
    [ 'name' => 'Doctores', 'href' => route('admin.doctors.index') ],
    [ 'name' => 'Horario' ],
]">

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h2 class="text-xl font-semibold mb-4">Gestor de horarios - {{ $doctor->user->name }}</h2>

        <form action="{{ route('admin.doctors.schedule.update', $doctor) }}" method="POST">
            @csrf
            @method('PUT')

            @php
                $days = ['monday'=>'Lunes','tuesday'=>'Martes','wednesday'=>'Miércoles','thursday'=>'Jueves','friday'=>'Viernes','saturday'=>'Sábado','sunday'=>'Domingo'];

                // preparar selected slots si el schedule ya existe (array de strings por día)
                $selected = [];
                if (is_array($schedule)) {
                    foreach ($days as $k => $v) {
                        if (isset($schedule[$k]) && is_array($schedule[$k])) {
                            $selected[$k] = array_map('strval', $schedule[$k]);
                        } else {
                            $selected[$k] = [];
                        }
                    }
                } else {
                    foreach ($days as $k => $v) { $selected[$k] = []; }
                }

                // generar franjas horarias cada 15 minutos entre 08:00 y 17:00
                $startHour = 8;
                $endHour = 17;
                $slots = [];
                for ($h = $startHour; $h <= $endHour; $h++) {
                    for ($m = 0; $m < 60; $m += 15) {
                        $from = sprintf('%02d:%02d', $h, $m);
                        $toH = $h;
                        $toM = $m + 15;
                        if ($toM == 60) { $toH = $h + 1; $toM = 0; }
                        $to = sprintf('%02d:%02d', $toH, $toM);
                        $slots[] = [ 'hour' => sprintf('%02d:00', $h), 'from' => $from, 'to' => $to, 'label' => "$from - $to" ];
                    }
                }
            @endphp

            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="p-3">DÍA/HORA</th>
                            @foreach($days as $k => $label)
                                <th class="p-3">{{ $label }}<br><small class="text-xs">Todos</small></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // agrupar por hora base (08:00, 09:00, ...)
                            $grouped = [];
                            foreach ($slots as $s) {
                                $grouped[$s['hour']][] = $s;
                            }
                        @endphp

                        @foreach($grouped as $hour => $group)
                            <tr class="border-b">
                                <td class="p-4 align-top font-medium">{{ $hour }}</td>
                                @foreach($days as $dayKey => $dayLabel)
                                    <td class="p-4 align-top">
                                        <div class="mb-2">
                                            <input type="checkbox" class="select-all-hour" data-day="{{ $dayKey }}" data-hour="{{ $hour }}" id="all_{{ $dayKey }}_{{ $hour }}">
                                            <label for="all_{{ $dayKey }}_{{ $hour }}" class="text-sm ml-2">Todos</label>
                                        </div>
                                        <div class="space-y-1">
                                            @foreach($group as $slot)
                                                @php $value = $slot['from'] . '-' . $slot['to']; @endphp
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="schedule[{{ $dayKey }}][]" value="{{ $value }}" class="slot-checkbox" data-day="{{ $dayKey }}" data-hour="{{ $hour }}" id="slot_{{ $dayKey }}_{{ str_replace([':'," ",'-'],['_','_','_'],$value) }}" {{ in_array($value, $selected[$dayKey]) ? 'checked' : '' }}>
                                                    <label for="slot_{{ $dayKey }}_{{ str_replace([':'," ",'-'],['_','_','_'],$value) }}" class="ml-2 text-sm">{{ $slot['label'] }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Guardar horario</button>
                <a href="{{ route('admin.doctors.index') }}" class="ml-3 text-gray-600">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // cuando se clickea 'Todos' seleccionar/desseleccionar todas las franjas de esa hora y día
            document.querySelectorAll('.select-all-hour').forEach(function(el) {
                el.addEventListener('change', function(e) {
                    const day = this.dataset.day;
                    const hour = this.dataset.hour;
                    const checked = this.checked;
                    document.querySelectorAll('.slot-checkbox[data-day="' + day + '"][data-hour="' + hour + '"]').forEach(function(cb) {
                        cb.checked = checked;
                    });
                });
            });
        });
    </script>

</x-admin-layout>
