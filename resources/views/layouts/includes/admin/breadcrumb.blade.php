{{--verificar si hay un elemento en el arreglo breadcrumbs--}}
@if (count($breadcrumbs))
  {{--- Display:block ---}}
  <nav class="mb-2 block">
    <ol class="flex flex-wrap text-slate-700 text-sm">
      @foreach ($breadcrumbs as $item)


      <li class="flex items-center">
        {{-- si No es el primer elemento, pinta el separador con un espacio--}}
            @unless ($loop->first)
              {{--- Separador con margen lateral---}}
              <span class="px-2 text-gray-400">
                 /
              </span>
            @endunless
            {{--- Revisa si existe una llave/propiedad llamada 'href' ---}}
         @if(isset($item['href']))
         {{--si existe, se muestra como un enlcae con opacidad reducida--}}
         <a href="{{ $item['href'] }}" class="opacity-60 hover:opacity-100 transition">
            {{ $item['name'] }}
         </a>
          @else
          {{--si no existe, se muestra como texto normal--}}
            {{ $item['name'] }}
          @endif
      </li>
      @endforeach
    </ol>    
    {{--- el ultimo elemento aparezca resaltado ---}}
    @if(count($breadcrumbs) > 1)
      <h6 class="font-bold mt-2">
        {{ end($breadcrumbs)['name'] }}
      </h6>
    @endif
  </nav>


@endif
