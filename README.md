# Startup Framework
The starters framework for your MVC application.

#### Requirements:
- PHP >= 8.0.3
- BCMath PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Composer >= 2.0.11

## Getting started

### Configure the router

The web router can be found in `routes/web.php`. You can currently only define a `get` and `post` route. 
The router accepts two paramaters, which will be the path and callback.

#### Load a view named about.php:
```php
$app->getRouter()->get('/about', 'about');
```

#### You can use a closure:
```php
$app->getRouter()->get('/home', function () {
    return 'This is the home route.';
});
```

#### Or attach a route to a controller and method:
```php
$app->getRouter()->get('/example', [\App\Controllers\ExampleController::class, 'index']);
```
This can also be done for `post`.
```php
$app->getRouter()->post('/example', [\App\Controllers\ExampleController::class, 'store']);
```

#### Each router can have their own name:
```php
$app->getRouter()->get('/example', [\App\Controllers\ExampleController::class, 'index'], 'example.index');
$app->getRouter()->post('/example', [\App\Controllers\ExampleController::class, 'store'], 'example.store');
```

We can now easily generate the URI by passing a route name.
```php
use App\Helpers\Route;

$getExampleURI = Route::name('example.index');
// GET http://example.com/example
$postExampleURI = Route::name('example.store');
// POST http://example.com/example 
```

#### The router can also be used for redirection:
```php
$app->getRouter()->redirect('/foo', '/bar'); 
```

### Create a controller
The controllers can be found in directory `app/Controllers`. The authentication controllers are included as default 
and can be adjusted anytime. The controllers should always extend class `app/General/Controller.php`.
If you want to return a view from a method, you can use the method `view()` from the object that accepts a path and parameters 
which is by default an empty array.

#### Load view `home.php`:
```php
public function index(): array|string
{
    return $this->view('home');
}
```
#### If the view is located in a directory, just use a forward slash:
```php
return $this->view('directory/home');
```
#### Passing parameters into the view:
```php
$params = [
    'foo' => 'bar',
    'hello_world'
];

return $this->view('home', $params);
```
These parameters can be accessed in the view.
#### By an associative array we can access the value of key by a variable:
```php
<?php
echo $foo . ' ' . $params[1];
?>

// Outputs: bar hello_world
```

### Create a view
The views can be found in directory `views`. This directory has by default a `layouts/main.php` layout.
You can create a new default layout or rename the current one. The default layout holds the `{{ content }}` placeholder. 
This placeholder will automatically be replaced with the given view in the controller.

If you're using a form, don't forget to include the csrf token.

#### For example, if we have a register view:
```html
<form method="post" action="">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
</form> 
```

### Validation
The request also provides a validation process. The validate method accepts an array of attributes and rules. 

I currently support a number of validation rules, which will be expanded in the future. 
```
'required': Checks whether the attribute is not null, empty as string or array, 
'string': Checks whether the attribute is from type string,
'integer': Checks whether the attribute is from type integer,
'email': Checks whether the attribute is a valid email, 
'min:{amount}': Checks whether the attribute have a minimum value. Strings, numerics and arrays are being evaluated,
'max:{amount}': Checks whether the attribute have a maximum value. Strings, numerics and arrays are being evaluated, 
'confirmed': Checks whether attribute "password_confirmation" matches "password", this rule will be soon replaced with "match"
```

Feel free to add more in file `app/Traits/Validations.php`.

#### In our `RegisterController` we want to validate if the username has more than 3 characters and whether the password confirmation are matching:
```php
public function store(Request $request): array|string
{
    $validated = $request->validate([
        'username' => ['required', 'string', 'min:3'],
        'password' => ['required', 'string', 'min:6', 'confirmed']
    ]);

    return $this->view('auth/register');
}
```
The method `validate()` will return true when there are no errors.

#### When there are errors, we can get them with method `getErrors()`:
```php
$validated = $request->validate([
    'username' => ['required', 'string', 'min:3'],
    'password' => ['required', 'string', 'min:6', 'confirmed']
]);

if ($validated === false) {
    var_dump($request->getErrors());
}
```

### Migrations
If you want to create tables into your database, you can use the database migration.
These migrations can be found in `database/migrations`.
The file name must start with the letter m, then you describe the range and action that this migration will perform.
So your migrations should look like this `m0001_create_first_table` -> `m0002_create_second_table` etc.

#### Create a migration for table users:
```php
class m0001_create_users_table
{

    public function up()
    {
        \App\General\Application::$app->getDatabase()->getConnection()->exec("
            CREATE TABLE users (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(30) NOT NULL,
                password VARCHAR(255) NOT NULL,
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
    }

    public function down() {}
}
```

#### Start migrating with the PHP command:
```bash
$ php migration.php

[08-03-2021 21:13:57] - Applying migration m0001_create_users_table.php
[08-03-2021 21:13:57] - Applied migration m0001_create_users_table.php
```

And there we go, all migrations should be applied. If there are any errors, check your database credentials in the
`.env` file.

### Models
As soon as we want to create users in the database, we need to create a User model that can do this for us. 

#### Let's create a User model in `app/Models`:
A created model must extend class Model. Each model should have three methods by default. 
These are tableName, primaryKey and fillable. The method `fillable()` returns an array of columns which can be filled with data.
The primary key is by default `id`. Feel free to override it in the model.
````php
class User extends Model
{
    public function tableName(): string
    {
        return 'users';
    }
    
    public function primaryKey(): string
    {
        return "id";
    }

    public function fillable(): array
    {
        return [
            'username', 'password'
        ];
    }
}
````
#### Now we can use the model for writing data to the table:
In the controller we first want to create an instance of the User model. 
This instance will represent a user that we want to write to the database.

#### We can do this by using method `registerColumn()`:
```php 
$user = new User();
$user->registerColumn('username', $request->input('username'));
$user->registerColumn('username', $request->input('password'));
$user->save()  
```

#### Or by using the method `registerColumns()`:
```php 
$user = new User();
$user->registerColumns([
    'username' => $request->input('username'),
    'password' => $request->input('password')
]);
$user->save()
```

To make your life as a developer easier, I have a number of methods that can help you to retrieve desired models.

```php
// Get all users:
$users = User::all();

foreach ($users as $user) {
    var_dump($user);
}
```
```php
// Get all users that are female by gender:
$users = User::findAllWhere([
    'gender' => 'female'
]);

foreach ($users as $user) {
    var_dump($user);
}
```
```php
// Get user that has the username Yassine:
$users = User::findOneWhere([
    'username' => 'Yassine'
]);

foreach ($users as $user) {
    var_dump($user);
}
```

#### The helper method `make()` from class `Helpers/Hash` allows us to hash passwords:
```php
'password' => Hash::make($request->input('password'));
```

### Authentication
With the static method `findOneWhere()` we can search for a user with the given username.
The static method will return null when there are no results.
```php
$user = User::findOneWhere([
    'username' => $request->input('username')
]);

// Be aware, it is not wise to tell a visitor that the given username has not been registered.
// This is duo privacy reasons. 
if ($user === null) {
    $this->flashMessage->setFlashMessage('danger', 'This username does not exist!');

    return $this->view('auth/login');
} 
```
Then we can retrieve the password with method `getAttributeValue($attr)` and verify the given password 
with the static helper method `verify($givenPassword, $hashedPassword)`.
```php
if (Hash::verify($request->input('password'), $user->getAttributeValue('password')) === false) {
    $this->flashMessage->setFlashMessage('danger', 'The user credentials were incorrect!');

    return $this->view('auth/login');
} 
```
With the method `authenticateUser()` we can tell the application to authenticate the given user.
```php
$this->app->authenticateUser($user); 
```

### Middlewares
With middlewares you can implement restrictions on methods. For example, if a user is not authenticated
then this user should get a 403 response back.

#### First, let's create a middleware `AuthMiddleware` class in `app/Middlewares`
Be aware, the `execute()` method is abstract and should always be there in your middleware.
```php 
class AuthMiddleware extends Middleware
{
    public function execute(): mixed {}
}
```
In the execute method we need to validate whether the user is authenticated.
We return an exception when the user is not authenticated.
```php
public function execute(): mixed
{
    if (Application::isAuthenticated() === false) {
        return throw new ForbiddenException();
    }
    
    // Dive into the void
}
```
Now we want to use the `AuthMiddleware` in our `HomeController` since its restricted for only authenticated users.
#### We can do this by registering the middleware in the constructor:
```php
public function __construct()
{
    parent::__construct();

    $this->registerMiddleware(new AuthMiddleware());
} 
```

#### We can also restrict specific methods by passing an array:
```php
public function __construct()
{
    parent::__construct();

    // Use AuthMiddleware only for method index
    $this->registerMiddleware(new AuthMiddleware([
        'index'    
    ]));
} 
```

## Security

If you discover any security related issues, please create an issue.
## Credits

- [Yassine el Fahmi](https://github.com/yassinefahmi)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
