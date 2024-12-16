<h4>Создан заказ на обучение №{{$data['order_number']}}</h4>
<p>Курс: {{ $data['course_title'] }}</p>
<p>Стоимость: {{ $data['price'] }}</p>
<p>Заказчик: {{ $data['lastname'] }} {{ $data['firstname'] }} {{ $data['middlename'] }}</p>
<p>Телефон: {{ $data['phone_prefix'] }} {{ $data['phone'] }} </p>
<p>Email: {{ $data['email'] }} </p>
<p>Регион: {{ $data['region'] }} </p>
