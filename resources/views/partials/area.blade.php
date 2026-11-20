{{-- Área de texto reutilizable: nombre, etiqueta, valor, requerido --}}
<div class="campo ancho">
    <label for="{{ $nombre }}">{{ $etiqueta }}{{ ($requerido ?? false) ? ' *' : '' }}</label>
    <textarea id="{{ $nombre }}" name="{{ $nombre }}" rows="3" {{ ($requerido ?? false) ? 'required' : '' }}>{{ old($nombre, $valor ?? '') }}</textarea>
    @error($nombre)
        <span class="error">{{ $message }}</span>
    @enderror
</div>
