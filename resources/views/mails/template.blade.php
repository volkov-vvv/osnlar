<a href="https://osnovanie.info"><img src="{{asset('assets/images/logo-main.png')}}"
                                      width="260" alt="Основание"></a>
<h2>Здравствуйте, {{ $data['firstname'] }} {{ $data['middlename'] }}!</h2>
<h3>Ваша заявка {{ $data['id'] }} на обучение принята.</h3>
<p>Наш специалист свяжется с Вами в ближайшее время для подробной консультации и поможет подать заявление на обучение.</p>

@if($data['link'] != '')
    <p>Также Вы можете самостоятельно зарегистрироваться на курс, для этого необходимо:<p>
    <p>Шаг 1. Зарегистрироваться на портале <a href="https://trudvsem.ru">Работа России</a>.</p>
    <p>Шаг 2. Перейти по ссылке программы в регионе - <a href="{{$data['link']}}">Ваша программа</a>, и нажать на кнопку «Подать заявление».</p>
    <p>В течение дня после подачи заявки на портале “Работа в России” на указанный Вами адрес электронной
        почты придет письмо, содержащее ссылку на личный кабинет гражданина.</p>
    <p>Обязательно проверяйте папку СПАМ!!!
        Если Вы так и не получили письмо со ссылкой, воспользуйтесь универсальной ссылкой lk.tgu-dpo.ru</p>
@endif
<p>Появились вопросы? <br>
    Наш номер телефона +7 499 677 54 55</p>
<p>Специально для вашего удобства мы создали <a href="https://t.me/osnovanie_study">телеграм-канал</a>, в котором будет вся необходимая и полная информация о программах обучения. А также много полезных материалов для Вас.</p>
<p>Будем рады видеть Вас в числе наших слушателей!</p>
<p>С уважением, команда Центра повышения квалификации и профессиональной подготовки «Основание».</p>



