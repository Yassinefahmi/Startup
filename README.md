# Startup Framework
The starters framework for your MVC application.

### Configure the router

The router can be found under file `public/index.php`. No worries, the router will get its own file in the future.

You can currently only define a `get` and `post` route. The router accepts two paramaters, which will be the path and callback.
#### You can use a closure:
```php
$app->router->get('/home', function () {
    return 'This is the home route.';
});
```

#### Or attach a route to a controller and method.
```php
$app->router->get('/example', [\App\Controllers\ExampleController::class, 'index']);
```
This can also be done for `post`.
```php
$app->router->post('/example', [\App\Controllers\ExampleController::class, 'store']);
```

### Create a controller
The controllers can be found under directory `app/Controllers`. The authentication controllers are included as default 
and can be adjusted. The controllers are always an expanding of class `app/General/Controller.php`.
If you want to return a view from the method, you can use the function `view()` from the object that accepts a path and parameters 
which is by default an empty array.

#### Make method index return view `home.php`:
```php
public function index(): array|string
{
    return $this->view('home');
}
```
#### If the view is located in a directory inside `view`, just use a forward slash:
```php
return $this->view('directory/home');
```
#### Sending parameters into the view:
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
The views can be found under directory `views`. This directory has standard a `layouts/main.php` file that must be used as default layout for the application.
You can create a new default layout or rename the current one. The default layout holds the `{{ content }}` placeholder. 
This placeholder will automatically be replaced with the given view in the controller.

### Validation
The request also provides a validation process. The validate function accepts an array of attributes and rules. 

We currently support a number of validation rules, which will be expanded in the future. 

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

#### In our `RegisterController` we want to validate if the username has more than 3 characters and whether the password confirmation are matching.
Be aware! Values processed from a form are always a string.
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
The function `validate()` will return true when there are no errors.

#### When there are errors, we can get them with function `getErrors()`.
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
If you want to create tables into your database, you can use the migration functionality.
Only one migration needs to be created for a table, you can add this file in path `database/migrations`.
The file name must start with the letter m, then you describe the range and action that this migration will perform.
So your migrations should look like this `m0001_create_first_table` -> `m0002_create_second_table` etc.

#### Let's migrate the users table
```php
class m0001_create_users_table
{

    public function up()
    {
        \App\General\Application::$app->getDatabase()->getConnection()->exec("
            CREATE TABLE users (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                firstname VARCHAR(30) NOT NULL,
                lastname VARCHAR(30) NOT NULL,
                email VARCHAR(50),
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
    }

    public function down()
    {

    }
}
```

#### Let's migrate with the PHP command
```bash
$ php migration

[08-03-2021 21:13:57] - Applying migration m0001_create_users_table.php
[08-03-2021 21:13:57] - Applied migration m0001_create_users_table.php
```

And there we go, all migrations should be applied. If there are any errors, check your database credentials in the
`.env` file.

### Models
As soon as we want to create users in the database, we need to create a User model that can do this for us. 
A model represents a table in the database. It is able to save data safely.

#### Let's create a User model in directory `app/Models`
A created model must inherit class Model. Each model has two functions, tableName and attributes. 
In the function `tableName()` we return the table name. The function `attributes()` returns an array of columns which
needs to be filled with data.
````php
class User extends Model
{
    public function tableName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return [
            'username', 'password'
        ];
    }
}
````
#### Now we can use the model for writing data to the table.
In the controller we first want to create an instance to the User model. 
This instance represents a user that we want to write to the database.

We can do this by using function `registerColumn()`:
```php 
$user = new User();
$user->registerColumn('username', $request->input('username'));
$user->registerColumn('username', $request->input('password'));
$user->save()  
```

Or by using the function `registerColumns()`:
```php 
$user = new User();
$user->registerColumns([
    'username' => $request->input('username'),
    'password' => $request->input('password')
]);
$user->save()
```
#### The helper function `make()` from class `Helpers/Hash` allows us to encrypt passwords.
```php
'password' => Hash::make($request->input('password'));
```

## Security

If you discover any security related issues, please create an issue.
## Credits

- [Yassine el Fahmi](https://github.com/yassinefahmi)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
