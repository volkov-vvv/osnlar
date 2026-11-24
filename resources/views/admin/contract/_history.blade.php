@php
    /** @var \Illuminate\Support\Collection $activities */
    $activities = $activities ?? collect();
@endphp

<div class="card card-outline card-secondary mt-4 mb-3">
    <div class="card-header p-3">
        <h3 class="card-title mb-0">История изменений ({{ $activities->count() }})</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body table-responsive p-3">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 160px;">Дата</th>
                    <th style="width: 160px;">Пользователь</th>
                    <th style="width: 160px;">Объект</th>
                    <th style="width: 180px;">Действие</th>
                    <th>Детали</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activities as $activity)
                    <tr>
                        <td>{{ $activity->created_at }}</td>
                        <td>{{ $activity->user }}</td>
                        <td>{{ $activity->entity_label }}</td>
                        <td>{{ $activity->description }}</td>
                        <td class="text-wrap">{{ $activity->details }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted">Пока нет изменений</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
