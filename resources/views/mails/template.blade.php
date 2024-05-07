<a href="https://osnovanie.info"><img src="{{asset('assets/images/logo-main.png')}}"
                                      width="260" alt="Основание"></a>
<h2>Здравствуйте, {{ $data['firstname'] }} {{ $data['middlename'] }}!</h2>
<h3>Ваша заявка {{ $data['id'] }} на обучение принята.</h3>
<p>Наш специалист свяжется с Вами в ближайшее время для подробной консультации и поможет подать заявление на обучение.</p>

@if($data['link'] != '')
    <p>Также Вы можете самостоятельно зарегистрироваться на курс по ссылке на портале Работа России: <a href="{{$data['link']}}">ЗАПИСАТЬСЯ</a></p>
    <p>Необходимо нажать на кнопку «Подать заявление», далее авторизоваться через свой аккаунт на госуслугах и заполнить предложенную анкету.</p>
    <p>В течение 3-х дней после подачи заявки на портале “Работа в России” на указанный Вами адрес электронной
        почты придет письмо, содержащее ссылку на личный кабинет гражданина.</p>
    <p>Обязательно проверяйте папку СПАМ!!!
        Если Вы так и не получили письмо со ссылкой, воспользуйтесь универсальной ссылкой lk.tgu-dpo.ru</p>
@endif
<p>Появились вопросы? <br>
    Наш номер телефона +7 499 677 54 55</p>
<p>Специально для вашего удобства мы создали <a href="https://t.me/osnovanie_study">телеграм-канал</a>, в котором будет вся необходимая и полная информация о программах обучения. А также много полезных материалов для Вас.</p>
<p>Будем рады видеть Вас в числе наших слушателей!</p>
<p>С уважением, команда Центра повышения квалификации и профессиональной подготовки «Основание».</p>



