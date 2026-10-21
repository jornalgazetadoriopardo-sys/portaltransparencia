@props(['nome', 'rotulo', 'opcoes', 'valor' => null, 'vazio' => 'Todos'])
<div>
    <label for="filtro-{{ $nome }}" class="rotulo">{{ $rotulo }}</label>
    <select id="filtro-{{ $nome }}" name="{{ $nome }}" class="campo">
        <option value="">{{ $vazio }}</option>
        @foreach ($opcoes as $chave => $texto)
            <option value="{{ $chave }}" @selected((string) $valor === (string) $chave)>{{ $texto }}</option>
        @endforeach
    </select>
</div>
