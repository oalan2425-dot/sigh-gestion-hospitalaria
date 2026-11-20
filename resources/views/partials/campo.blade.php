{{-- Campo de texto reutilizable: nombre, etiqueta, valor, tipo, requerido, ancho --}}
<div class="campo {{ ($ancho ?? false) ? 'ancho' : '' }}">
    <label for="{{ $nombre }}">{{ $etiqueta }}{{ ($requerido ?? false) ? ' *' : '' }}</label>
    <input type="{{ $tipo ?? 'text' }}" id="{{ $nombre }}" name="{{ $nombre }}"
           value="{{ old($nombre, $valor ?? '') }}" {{ ($requerido ?? false) ? 'required' : '' }}>
    @error($nombre)
        <span class="error">{{ $message }}</span>
    @enderror
</div>
