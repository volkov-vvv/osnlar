<h5>Текущий баланс</h5>

<div>
    @if(cache()->has('balance')) {{cache()->get('balance')}} @else 0 @endif
</div>
<br>
<h2>Пополнить баланс</h2>
<br>
<form method="post" action="{{route('payment.create')}}">
    @csrf
    <label for="description">Описание платежа</label>
    <textarea name="description" id="description" rows="3"></textarea>
    <br>
    <label for="amount">Сумма</label>
    <input type="number" name="amount" id="amount">
    <br>
    <button type="submit">
        Сохранить
    </button>
</form>
<br>

<h2>Список транзакций</h2>
<table>
    <thead>
    <tr>
        <th>#id</th>
        <th>Сумма</th>
        <th>Описание</th>
        <th>Статус</th>
        <th>Дата</th>
    </tr>
    </thead>
    <tbody>
    @forelse($transactions as $transaction)
        <tr>
            <td>{{$transaction->id}}</td>
            <td>{{$transaction->amount}}</td>
            <td>{{$transaction->description}}</td>
            <td>{{$transaction->status}}</td>
            <td>{{$transaction->updated_at->format('d-m-Y H:i')}}</td>
        </tr>
    @empty
        Транзакций нет
    @endforelse
    </tbody>
</table>
