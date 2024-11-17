![](sample.jpg)

## Описание

Демо простого интернет магазина продуктовой лавки.

Фронтенд:
* регистрация, авторизация, восстановление пароля
* личный кабинет
* корзина
* каталог товаров
* блог новостей

Административная часть:
* управление товарами и категориями
* управление заказами
* управление пользователями
* управление настройками сайта
* управление новостями

## Установка приложения

1. Переименовать: `.env-example` в `.env`
Обязательные параметры:
   * `ADMIN_EMAIL` / `MAILER_DSN` - необходимо для функционала регистрации и восстановления пароля.
   * `SITE_BASE_HOST` / `SITE_BASE_SCHEME` - необходимо для формирования корректных ссылок в почтовых шаблонах.

2. Установить приложение и загрузить демо-данные: `make init`

Приложение будет доступно по адресу: `http://localhost:4080`<br>
Панель администратора: `http://localhost:4080/admin/`<br>
Тестовый пользователь: `admin@example.com`, пароль: `test1test1`

## Настройка отправки почты

Отправка почты (восстановление пароля, подтверждение учетной записи) выполняется с помощью [Symfony Messenger](https://symfony.com/doc/current/components/messenger.html "Symfony Messenger"). Для корректной работы необходимо настроить:
* повесить команду `php bin/console messenger:consume async` на `cron` 
* настроить `supervisor`

Пример конфига, который необходимо разместить `/etc/supervisor/conf.d/messenger-worker.conf`:
```
;/etc/supervisor/conf.d/messenger-worker.conf
[program:messenger-consume]
command=php /path/to/your/app/bin/console messenger:consume async --time-limit=3600
user=ubuntu
numprocs=2
startsecs=0
autostart=true
autorestart=true
process_name=%(program_name)s_%(process_num)02d
```
* `command=` - после `php` указать путь до консоли и через пробел, команду, которую надо добавить
* `user=` - указать текущего пользователя
* `numprocs=` - количество процессов, которые будут созданы

Остальные опции можно оставить без изменений. [Пример конфига](https://symfony.com/doc/6.4/messenger.html#supervisor-configuration) с официального сайта.

На этапе тестирования команду `php bin/console messenger:consume async` можно запускать вручную.

### Дополнительный функционал

* Консольные команды:
    * `php bin/console app:add-user` - создание пользователя
    * `php bin/console app:update-slug-product` - обновление слага товара
