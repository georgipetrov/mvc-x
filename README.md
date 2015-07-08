# mvc-x

MVC-X is a fast multi-app, multi-database MVC with low-coupled extension support and small footprint. It has also SEO-friendly URLs and easy data access.

#### I. Concept

MVC-X provides a MVC (model-view-controller) architecture well-isolated from X (extensions) and core code (framework). This allows you to achieve powerful webapp scenarios, while keeping the app codebase clean. Two main directories come with the code,

* .mvcx/ - under-the-hood folder, no touch here
* apps/ - here you create and implement your applications

Each application has the following directory structure,

* /model/ - here you create and implement your models, i.e. blog.php
* /view/ - here you create and implement your views, i.e. blog/view.tpl, check the cool autoPersist feature below
* /controller/ - here you create and implement your controllers, i.e. blog.php
* /x/ - extensions, here you create and put all the extensions (themes, language, vendors, etc). The extensions can both autoload and load on demand. They can also be separate logic, overrides, pages, everything.

As you noticed, the naming convention is singular lowercased. The name blog in the points above is just for an example, you will notice it in other examples too.

#### II. Configuration

The configuration of each app is in the config.php file in the root of the app, and it looks like,
```
array(
	'url'=>array('mysite.com'),
	'dir'=>'mysiteapp',
	'db' => array(
		'default' => array(
			'type'=>'mysql',
			'host'=>'localhost',
			'name'=>'',
			'username'=>'',
			'password'=>'',
			'table_prefix'=>''
		)
	),
	'debug_mode'=> 1
);
```
 -   **url** - here you should add your site public url without protocol and subdomain, e.g. mysite.com 
 -   **dir** - the name of the app directory on your server e.g. mysite
 -   **db** - the database configuration of the app
 -   **debug_mode** - If you enable debug mode, you will see debug information at the bottom of your page. To put this app in debug mode you need to set it to 1, otherwise leave it 0.

As you can guess multiple db configurations are supported. When MVC-X boots it first looks for a configuration named **default**, if such is not found it will use the first one available. Later you can switch to a different configuration by simply calling `$this->app->setDb('{config_name}');`

#### III. Retrieving your data
* * *
The following ways of retrieving data are available,

##### Auto persistence
When enabled, this automatically retrieves your data and passes it to the view. You can enable it in your controller action in the following way. 

`$this->autoPersist = true;`

Supported views are index, view, edit, add. When used in the latter two views, this will also automatically store the data if you perform a post request.

##### Fluent query

Using MVC-X fluent queries you can retrieve your specific desired data. It follows the model of *$this->modelName->getColumnsByCriteria()*. Example usage:

`$this->blog->getAll();`
Returns all the blog posts i.e. all entries from the 'blog' table

`$this->blog->getAllById(5);`
Returns the blog post with id equal to 5

`$this->blog->getAllByContent('%hurricane%');`
Returns a list of blog posts that have the word 'hurricane' in its content

`$this->blog->getAllByContentAndCreated('%hurricane%','> 2014-01-01');`
Returns a list of blog posts that have the word 'hurricane' in its content, created after 2014-01-01

`$this->blog->getTitleByUser(12);`
Returns a list of blog titles published by user with id = 12

`$this->blog->getTitleAndContentByUserAndStatus(12, true);`
Returns a list of blog titles and content, from blog posts with active status, published by user with id = 12

##### Database query

A normal query can be accomplished using your DB engine syntax. In this example, we are selecing all blog posts using MySQL. The returned result will be an array of posts.

`$posts = $this->blog->query('SELECT * FROM blog');`

**_Note!_** The example above is valid if you are querying the database from the controller. I guess it is logical but, if you want to execute a query in the model you will do just `$posts = $this->query('SELECT * FROM blog');`

#### IV. Saving your data
* * *
The following ways of saving data are available,

`$this->modelName->save($data);`

This will save an array of data having its keys matching the table columns. It can be single entry data or array of entries. If id is present, it will update the data, if not, it will insert it. 

`$this->modelName->saveEntry($data);`

This saves an array of data, exactly the same way as save(), with the only difference it can be used for single entries only.

`$this->modelName->lastId();`

Returns the id of the last inserted database table row.

**_Note!_** By default models are connected to the controller's name. So if you are loading the **User** model while the current controller is **Blog**, the model will try to work on the **blog** database table instead of **user**. You can specify the model's table by adding a $table property to the class definition like this

```
class Blog extends Model {
	public $table = 'blog';
}
```
That way you will make sure the model is always working on the table you expect it to.

#### V. Database table structure
* * *
In order to auto-bind database table to model, you need to have your table under the same name as your controller, e.g. blog. While the only required column is `id`, you can make a good use of a few natively populated columns if you have them added. Please find the names of the columns which if you have added you will get them autofilled by MVC-X on each save operation via the `*Model::save()*` or `*Model::saveEntry()*` methods,

- `id` - this is the autoincrement primary key of the table
- `ua` - the useragent of the user performing the save/update request with its browser information
- `ip` - the ip of the user performign the save/update request, catches IPs behind proxy too
- `created` - date of entry created
- `modified` - date of entry modified

#### VI. Debugging
* * *
The following techniques are available for debugging.

`pr(mixed $var);`

This will output a variable, array or object of your choice in well-formatted manner.

`debug_mode=>true`

This is a setting in the config.php of your app, which when enabled will produce useful debug information at the bottom of every page.
