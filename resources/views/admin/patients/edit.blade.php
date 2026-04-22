<x-admin-layout tittle="Pacientes" :breadcrumbs="[
    [
      'name' => 'Dashboard',
      'href' => route('admin.dashboard'),
    ],
    [
      'name' => 'Pacientes',
      'href' => route('admin.patients.index'),
    ],
    [
      'name' => 'Editar',
    ],

]">

  <form action="{{route('admin.patients.update', $patient)}}" method="POST">
    @csrf
    @method('PUT')

    {{--encabezado con foto y acciones--}}
    <x-wire-card class="mb-8">
      <div class="lg:flex lg:justify-between lg:items-center">
        <div class="flex items-center space-x-4">
          <img src="{{ $patient->user->profile_photo_url }}" alt="{{ $patient->user->name }}" class="w-20 h-20 rounded-full object-cover object-center">
          <div>
            <p class="text-2xl font-bold text-gray-900">{{ $patient->user->name }}</p>
          </div>
        </div>

        <div class="flex space-x-3 mt-6 lg:mt-0">

          <x-wire-button outline gray href="{{ route('admin.patients.index') }}">Volver</x-wire-button>
          <x-wire-button type="submit">
            <i class="fa-solid fa-check"></i>
            Guardar cambios
          </x-wire-button>

        </div>
      </div>
    </x-wire-card>

    {{--tabs de navegacion--}}
  <x-wire-card>
    <div x-data="{tab: 'datos personales'}">

      {{--menu de pestañas--}}
      <div class="border-b border-gray-200">
          <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">

            {{--datos personales Tab 1--}}
              <li class="me-2">
                  <a href="#" x-on:click="tab = 'datos-personales'"
                  :class="{
                    'text-blue-600 border-blue-600 active': tab === 'datos-personales',
                    'border-transparent hover:text-blue-600 hover:border-blue-600': tab !== 'datos personales'
                  }"
                    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg  group trnasition-colors duration-200"
                    :aria-current="tab === 'datos-personales' ? 'page' : undefined">
                      <i class="fa-solid fa-user me-2"></i>
                      Datos personales
                  </a>
              </li>

              {{--Antecedentes Tab 2--}}
              <li class="me-2">
                  <a href="#" x-on:click="tab = 'Antecedentes'"
                  :class="{
                    'text-blue-600 border-blue-600 active': tab === 'Antecedentes',
                    'border-transparent hover:text-blue-600 hover:border-blue-600': tab !== 'Antecedentes'
                  }"
                    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg  group trnasition-colors duration-200"
                    :aria-current="tab === 'datos-personales' ? 'page' : undefined">
                      <i class="fa-solid fa-file-lines me-2"></i>
                      Antecedentes
                  </a>
              </li>

              {{--Tab 3 Informacion general--}}
              <li class="me-2">
                  <a href="#" x-on:click="tab = 'Informacion general'"
                  :class="{
                    'text-blue-600 border-blue-600 active': tab === 'Informacion general',
                    'border-transparent hover:text-blue-600 hover:border-blue-600': tab !== 'Informacion general'
                  }"
                    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg  group trnasition-colors duration-200"
                    :aria-current="tab === 'Informacion general' ? 'page' : undefined">
                      <i class="fa-solid fa-info me-2"></i>
                      Informacion general
                  </a>
              </li>

              {{--Tab 4 Contacto-de-emergencia --}}
              <li class="me-2">
                  <a href="#" x-on:click.prevent="tab = 'contacto-de-emergencia'"
                  :class="{
                    'text-blue-600 border-blue-600 active': tab === 'contacto-de-emergencia',
                    'border-transparent hover:text-blue-600 hover:border-blue-600': tab !== 'contacto-de-emergencia'
                  }"
                    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200"
                    :aria-current="tab === 'contacto-de-emergencia' ? 'page' : undefined">
                      <i class="fa-solid fa-heart me-2"></i>
                      Contacto de emergencia
                  </a>
              </li>           
          </ul>
      </div>
      {{---contenido de los tabs--}}
      <div class="px-4 mt-4">

        {{--contenido del tab 1--}}
        <div x-show="tab === 'datos-personales'">
          <div class="bg-blue border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
            <div class=flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4>
              
              {{--lado izquierdo:informacion--}}
              <div class="flex items-start">
                <div class="flex-shrink-0">
                  <i class="fa-solid fa-user-gear text-blue-500 text-xl mt-1"></i>
                </div>

                  <div class="ml-3">
                     <h3 class="text-sm font-bold text-blue-800">Edicion de cuenta de usuario</h3>
                      <div class="mt-1 text-sm text-blue-600">
                  
                        <p>La <strong>informacion de acceso</strong> (Nombre, Email y Contraseña) debe de gestionarse desde la cuenta de usuario asociada.</p>
                      </div>
                  </div>
              </div>

              {{--lado derecho:accion--}}
              <div class="flex-shrink-0">
                <x-wire-button primary sm 
                  href="{{ route('admin.users.edit', $patient->user) }}" 
                  target="_blank">
                    <i class="fa-solid fa-user-gear me-2"></i>
                    Editar usuario
                    <i class="fa-solid fa-arrow-up-right-from-square ms-2"></i>
                </x-wire-button>
              </div>
          </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-4">
        <div>
          <span class="text-gray-500 font-semibold ">Telefono:</span>
          <span class="text-gray-900 text-sm ml-1">{{ $patient->user->number_phone }}</span>
          
        </div>

        <div>
          <span class="text-gray-500 font-semibold ">Email:</span>
          <span class="text-gray-900 text-sm ml-1">{{ $patient->user->email }}</span>
          
        </div>

        <div>
          <span class="text-gray-500 font-semibold ">Direccion</span>
          <span class="text-gray-900 text-sm ml-1">{{ $patient->user->address }}</span>
          
        </div>
      </div>
      </div> 
    </div>

    {{--contenido de tab2:antecedentes--}}
      <div x-show="tab === 'Antecedentes'" style="display: none;">
        <div class="grid lg:gris-cols-2 gap-4">
          <div>
            <x-wire-textarea label="Alergias conocidas" name="allergies">
              {{ old('allergies', $patient->allergies) }}
            </x-wire-textarea>
          </div>

          <div>
            <x-wire-textarea label="Enfermedades cronicas" name="chronic_conditions">
              {{ old('chronic_conditions', $patient->chronic_conditions) }}
            </x-wire-textarea>
          </div>

          <div>
            <x-wire-textarea label="Antecedentes quirurgicos" name="surgical_history">
              {{ old('surgical_history', $patient->surgical_history) }}
            </x-wire-textarea>
          </div>

          <div>
            <x-wire-textarea label="Antecedentes familiares" name="family_history">
              {{ old('family_history', $patient->family_history) }}
            </x-wire-textarea>
          </div>
        </div>
      </div>

        {{--contenido de tab3:informacion general--}}
        <div x-show="tab === 'Informacion general'" style="display: none;">
          <x-wire-native-select label="Tipo de Sangre" class="mb-4" name="blood_type_id">
            <option value="">Seleccione un tipo de sangre</option>
            @foreach ($bloodTypes as $bloodType)
              <option value="{{ $bloodType->id }}" @selected((int) old('blood_type_id', $patient->blood_type_id) === $bloodType->id)>
                {{ $bloodType->name }}
              </option>
            @endforeach
          </x-wire-native-select>

          <x-wire-textarea label="Observaciones" name="Observations">
            {{ old('Observations', $patient->Observations) }}
          </x-wire-textarea>
        </div>

        {{--contenido de tab4:contacto de emergencia--}}
        <div x-show="tab === 'contacto-de-emergencia'" style="display: none;">
          <div class="space-y-4">
            <x-wire-input label="Nombre de contacto " name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" />

            <x-wire-phone label="Telefono de contacto " name="emergency_contact_phone" mask="(###) ###-####" placeholder="(999) 999-9999" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" />

            <x-wire-input label="Relacion con el paciente" name="emergency_contact_relationship" placeholder="Familiar, amigo, etc." value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}" />
          </div>
        </div>




  </x-wire-card>


  </form>


</x-admin-layout>