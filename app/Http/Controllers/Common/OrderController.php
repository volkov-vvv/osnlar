<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Concerns\ResolvesPanel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\Order\UpdateRequest;
use App\Models\Course;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class OrderController extends Controller
{
    use ResolvesPanel;

    public function index(): View
    {
        $orders = Order::all();
        $statuses = Status::where('type', 'order')->get();
        $courses = Course::all();

        return view($this->panelView('order.index'), compact('orders', 'statuses', 'courses'));
    }

    public function create(): void
    {
    }

    public function store(): void
    {
    }

    public function show(Order $order): View
    {
        $statuses = Status::all();
        $activites = $this->orderActivities($order, $statuses);

        return view($this->panelView('order.show'), compact('order', 'statuses', 'activites'));
    }

    public function edit(Order $order): View
    {
        $statuses = Status::where('type', 'order')->get();
        $users = User::where('role', 3)->get();

        return view($this->panelView('order.edit'), compact('order', 'statuses', 'users'));
    }

    public function update(UpdateRequest $request, Order $order): RedirectResponse
    {
        $data = $request->validated();
        $oldStatus = $order->status_id;
        $order->update($data);

        $actionDescription = $oldStatus != $data['status_id']
            ? 'Изменение статуса'
            : 'Изменение информации';

        activity('order')
            ->performedOn($order)
            ->withProperties([
                'status_id_old' => $oldStatus,
                'status_id' => $data['status_id'],
                'comment' => $request->comment,
            ])
            ->log($actionDescription);

        return $this->panelRedirect('order.edit', compact('order'));
    }

    public function destroy(int $id): void
    {
    }

    protected function orderActivities(Order $order, Collection $statuses): Collection
    {
        $activites = Activity::all()
            ->where('log_name', '=', 'order')
            ->where('subject_id', '=', $order['id']);

        $activites->each(function ($item) use ($statuses) {
            $properties = $item->properties;
            $item->status_old = $statuses->where('id', $properties['status_id_old'])->first()->title;
            $item->status_new = $statuses->where('id', $properties['status_id'])->first()->title;
            $item->comment = $properties['comment'] ?? '';
            $item->user = User::findOrFail($item->causer_id)->name;
        });

        $firstActivity = Activity::all()
            ->where('subject_id', '=', $order['id'])
            ->where('description', '=', 'Изменение статуса')
            ->first();

        $activites->interval = $firstActivity
            ? dateDiff($firstActivity->created_at, $order->created_at)
            : '---';

        return $activites;
    }
}
