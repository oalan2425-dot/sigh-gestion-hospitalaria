{{-- Lista desplegable reutilizable: nombre, etiqueta, opciones [valor => texto], valor, requerido --}}
<div class="campo">
    <label for="{{ $nombre }}">{{ $etiqueta }}{{ ($requerido ?? false) ? ' *' : '' }}</label>
    <select id="{{ $nombre }}" name="{{ $nombre }}" {{ ($requerido ?? false) ? 'required' : '' }}>
        <option value="">-- Seleccione --</option>
        @foreach ($opciones as $clave => $texto)
            <option value="{{ $clave }}" @selected((string) old($nombre, $valor ?? '') === (string) $clave)>{{ $texto }}</option>
        @endforeach
    </select>
    @error($nombre)
        <span class="error">{{ $message }}</span>
    @enderror
</div>
