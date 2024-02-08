Установка
------------

Установить проект

~~~
composer install
~~~

Настроить подключение к бд `config/db.php`:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

Запустить миграции

~~~
php yii migrate
~~~

---

Проект развернут тут: http://cd16721.tw1.ru/

Логин и пароль в админку: admin admin