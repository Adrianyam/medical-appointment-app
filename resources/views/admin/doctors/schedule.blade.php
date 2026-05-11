<x-admin-layout tittle="Horario del Doctor" :breadcrumbs="[
    [ 'name' => 'Dashboard', 'href' => route('admin.dashboard') ],
    [ 'name' => 'Doctores', 'href' => route('admin.doctors.index') ],
    [ 'name' => 'Horario' ],
]">

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h2 class="text-xl font-semibold mb-2">Gestor de horarios - {{ $doctor->user->name }}</h2>
        <p class="text-sm text-gray-500 mb-6">Configura un horario por día usando formato de 12 horas con AM/PM y selección de minutos.</p>

        <form action="{{ route('admin.doctors.schedule.update', $doctor) }}" method="POST">
            @csrf
            @method('PUT')

            @php
                $days = [
                    'monday' => 'Lunes',
                    'tuesday' => 'Martes',
                    'wednesday' => 'Miércoles',
                    'thursday' => 'Jueves',
                    'friday' => 'Viernes',
                    'saturday' => 'Sábado',
                    'sunday' => 'Domingo',
                ];

                $hours = range(1, 12);
                $minutes = ['00', '15', '30', '45'];
                $periods = ['AM', 'PM'];

                $parseTime = function (?string $time): array {
                    if (!$time || !preg_match('/^(\d{2}):(\d{2})/', $time, $matches)) {
                        return ['hour' => '08', 'minute' => '00', 'period' => 'AM'];
                    }

                    $hour = (int) $matches[1];
                    $minute = $matches[2];
                    $period = $hour >= 12 ? 'PM' : 'AM';
                    $displayHour = $hour % 12;
                    $displayHour = $displayHour === 0 ? 12 : $displayHour;

                    return [
                        'hour' => str_pad((string) $displayHour, 2, '0', STR_PAD_LEFT),
                        'minute' => $minute,
                        'period' => $period,
                    ];
                };

                $selected = [];

                foreach ($days as $dayKey => $dayLabel) {
                    $daySchedule = $schedule[$dayKey] ?? [];
                    $active = false;
                    $start = ['hour' => '08', 'minute' => '00', 'period' => 'AM'];
                    $end = ['hour' => '05', 'minute' => '00', 'period' => 'PM'];

                    if (is_array($daySchedule)) {
                        if (isset($daySchedule['active'])) {
                            $active = (bool) $daySchedule['active'];
                            if (isset($daySchedule['start'])) {
                                $start = $parseTime($daySchedule['start']);
                            }
                            if (isset($daySchedule['end'])) {
                                $end = $parseTime($daySchedule['end']);
                            }
                        } elseif (!empty($daySchedule)) {
                            $active = true;
                            $slots = array_values(array_filter($daySchedule));
                            $firstSlot = $slots[0] ?? null;
                            $lastSlot = $slots ? end($slots) : null;

                            if (is_string($firstSlot) && str_contains($firstSlot, '-')) {
                                [$startTime] = explode('-', $firstSlot);
                                $start = $parseTime($startTime);
                            }

                            if (is_string($lastSlot) && str_contains($lastSlot, '-')) {
                                [, $endTime] = explode('-', $lastSlot);
                                $end = $parseTime($endTime);
                            }
                        }
                    }

                    $selected[$dayKey] = compact('active', 'start', 'end');
                }

                $timeFieldValue = function (string $hour, string $minute, string $period): string {
                    return $hour . ':' . $minute . ' ' . $period;
                };
            @endphp

            <div class="grid grid-cols-1 gap-4">
                @foreach($days as $dayKey => $dayLabel)
                    @php
                        $dayData = $selected[$dayKey];
                        $startTime = $timeFieldValue($dayData['start']['hour'], $dayData['start']['minute'], $dayData['start']['period']);
                        $endTime = $timeFieldValue($dayData['end']['hour'], $dayData['end']['minute'], $dayData['end']['period']);
                    @endphp

                    <div class="border border-gray-200 rounded-2xl p-4 bg-gray-50 schedule-day-card" data-day-card="{{ $dayKey }}">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $dayLabel }}</h3>
                                    <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                                        <input
                                            type="checkbox"
                                            name="schedule[{{ $dayKey }}][active]"
                                            value="1"
                                            class="day-toggle h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            {{ $dayData['active'] ? 'checked' : '' }}
                                        >
                                        Activo
                                    </label>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Selecciona una franja de atención en formato 12 horas.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full lg:w-auto">
                                <div class="rounded-xl bg-white border border-gray-200 p-4">
                                    <p class="text-sm font-semibold text-gray-700 mb-3">Hora de inicio</p>
                                    <div class="grid grid-cols-3 gap-2 time-group" data-day="{{ $dayKey }}">
                                        <select name="schedule[{{ $dayKey }}][start_hour]" class="time-input rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($hours as $hour)
                                                <option value="{{ sprintf('%02d', $hour) }}" @selected($dayData['start']['hour'] === sprintf('%02d', $hour))>{{ sprintf('%02d', $hour) }}</option>
                                            @endforeach
                                        </select>
                                        <select name="schedule[{{ $dayKey }}][start_minute]" class="time-input rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($minutes as $minute)
                                                <option value="{{ $minute }}" @selected($dayData['start']['minute'] === $minute)>{{ $minute }}</option>
                                            @endforeach
                                        </select>
                                        <select name="schedule[{{ $dayKey }}][start_period]" class="time-input rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($periods as $period)
                                                <option value="{{ $period }}" @selected($dayData['start']['period'] === $period)>{{ $period }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="rounded-xl bg-white border border-gray-200 p-4">
                                    <p class="text-sm font-semibold text-gray-700 mb-3">Hora de fin</p>
                                    <div class="grid grid-cols-3 gap-2 time-group" data-day="{{ $dayKey }}">
                                        <select name="schedule[{{ $dayKey }}][end_hour]" class="time-input rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($hours as $hour)
                                                <option value="{{ sprintf('%02d', $hour) }}" @selected($dayData['end']['hour'] === sprintf('%02d', $hour))>{{ sprintf('%02d', $hour) }}</option>
                                            @endforeach
                                        </select>
                                        <select name="schedule[{{ $dayKey }}][end_minute]" class="time-input rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($minutes as $minute)
                                                <option value="{{ $minute }}" @selected($dayData['end']['minute'] === $minute)>{{ $minute }}</option>
                                            @endforeach
                                        </select>
                                        <select name="schedule[{{ $dayKey }}][end_period]" class="time-input rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($periods as $period)
                                                <option value="{{ $period }}" @selected($dayData['end']['period'] === $period)>{{ $period }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Guardar horario</button>
                <a href="{{ route('admin.doctors.index') }}" class="ml-3 text-gray-600">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.day-toggle').forEach(function(el) {
                el.addEventListener('change', function(e) {
                    const checked = this.checked;
                    const card = this.closest('.schedule-day-card');

                    if (!card) {
                        return;
                    }

                    card.querySelectorAll('.time-input').forEach(function(input) {
                        input.disabled = !checked;
                    });

                    card.querySelectorAll('.time-input').forEach(function(input) {
                        input.classList.toggle('bg-gray-100', !checked);
                    });
                });

                el.dispatchEvent(new Event('change'));
            });
        });
    </script>

</x-admin-layout>
