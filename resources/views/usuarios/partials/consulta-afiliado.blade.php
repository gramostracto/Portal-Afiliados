@php
    $comparisonRows = [
        ['label' => 'Cédula', 'icon' => 'fa-id-card', 'portal' => $arrayResultLocal['number_id'], 'otm' => $arrayResultOtm['locationXid'], 'erp' => $arrayResultErp['TaxpayerId']],
        ['label' => 'Nombre', 'icon' => 'fa-building', 'portal' => $arrayResultLocal['name'], 'otm' => $arrayResultOtm['fullName'], 'erp' => $arrayResultErp['fullName']],
        ['label' => 'Email', 'icon' => 'fa-envelope', 'portal' => $arrayResultLocal['email'], 'otm' => $arrayResultOtm['emailAddress'], 'erp' => $arrayResultErp['emailAddress'], 'isEmail' => true],
        ['label' => 'Teléfono', 'icon' => 'fa-phone', 'portal' => $arrayResultLocal['phone'], 'otm' => $arrayResultOtm['phone'], 'erp' => $arrayResultErp['phone']],
    ];

    $normalizeValue = function ($value) {
        $value = trim(mb_strtoupper((string) $value));
        $value = preg_replace('/\s+/', ' ', $value);

        return $value;
    };

    $normalizeNameTokens = function ($value) use ($normalizeValue) {
        $value = $normalizeValue($value);
        $value = preg_replace('/[^\pL\pN\s]/u', ' ', $value);
        $value = preg_replace('/\s+/', ' ', trim($value));

        return collect(explode(' ', $value))->filter()->unique()->values();
    };

    $nameMatches = function ($values) use ($normalizeNameTokens) {
        $tokenGroups = $values->map($normalizeNameTokens)->filter(function ($tokens) {
            return $tokens->count() > 0;
        })->values();

        if ($tokenGroups->count() <= 1) {
            return false;
        }

        $smallestGroup = $tokenGroups->sortBy(function ($tokens) {
            return $tokens->count();
        })->first();

        return $tokenGroups->every(function ($tokens) use ($smallestGroup) {
            return $smallestGroup->diff($tokens)->isEmpty();
        });
    };

    $localStatusActive = $arrayResultLocal['estado'] == 'CONFIRMADO';
    $otmStatusActive = $arrayResultOtm['isActive'] == 1;
    $erpStatusActive = $arrayResultErp['isActive'] == 'ACTIVE';
@endphp

<div class="affiliate-validation">
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="validation-source">
                <span class="validation-source-label">Portal</span>
                <span class="badge rounded-pill bg-{{ $localStatusActive ? 'success' : 'danger' }}">
                    {{ $localStatusActive ? 'Activo' : 'Desactivado' }}
                </span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="validation-source">
                <span class="validation-source-label">OTM</span>
                <span class="badge rounded-pill bg-{{ $otmStatusActive ? 'success' : 'danger' }}">
                    {{ $otmStatusActive ? 'Activo' : 'Desactivado' }}
                </span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="validation-source">
                <span class="validation-source-label">ERP</span>
                <span class="badge rounded-pill bg-{{ $erpStatusActive ? 'success' : 'danger' }}">
                    {{ $erpStatusActive ? 'Activo' : 'Desactivado' }}
                </span>
            </div>
        </div>
    </div>

    <div class="validation-grid">
        @foreach ($comparisonRows as $row)
            @php
                $values = collect([$row['portal'], $row['otm'], $row['erp']])->filter(function ($value) {
                    return $value !== null && $value !== '';
                });
                $matches = $row['label'] === 'Nombre'
                    ? $nameMatches($values)
                    : $values->count() > 1 && $values->map($normalizeValue)->unique()->count() === 1;
            @endphp
            <div class="validation-row">
                <div class="validation-field">
                    <span class="validation-icon"><i class="fa {{ $row['icon'] }}"></i></span>
                    <div>
                        <span class="validation-field-label">{{ $row['label'] }}</span>
                        <span class="badge bg-{{ $matches ? 'success' : 'warning' }}-transparent text-{{ $matches ? 'success' : 'warning' }}">
                            {{ $matches ? 'Coincide' : 'Revisar' }}
                        </span>
                    </div>
                </div>
                @foreach (['portal' => 'Portal', 'otm' => 'OTM', 'erp' => 'ERP'] as $sourceKey => $sourceLabel)
                    <div class="validation-value">
                        <span class="validation-value-source">{{ $sourceLabel }}</span>
                        @if (!empty($row['isEmail']) && !empty($row[$sourceKey]))
                            <a href="mailto:{{ $row[$sourceKey] }}">{{ $row[$sourceKey] }}</a>
                        @else
                            <span>{{ $row[$sourceKey] ?: 'Sin dato' }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
