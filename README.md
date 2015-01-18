# mvc-x

MVC-X is a fast multi-app, multi-database MVC with low-coupled extension support and small footprint. It has also SEO-friendly URLs and easy data access.

#### I. Configuration

The configuration of each app is in the config.php file in the root of the app, and it looks like,
<pre>
`array(
'url'=>array('mysite.com'),
'dir'=>'mysiteapp',
'db' => array(
	'type'=>'mysql',
	'host'=>'localhost',
	'name'=>'',
	'username'=>'',
	'password'=>'',
	'table_prefix'=>''
	),
'debug_mode'=> 1
);`</pre>

 *   url - here you should add your site public url without protocol and subdomain, e.g. mysite.com 
 *   dir - the name of the app directory on your server e.g. mysite
 *   db - the database configuration of the app
 *   debug_mode - If you enable debug mode, you will see debug information at the bottom of your page. To put this app in debug mode you need to set it to 1, otherwise leave it 0.


#### II. Retrieving data
* * *
The following ways of retrieving data are available,

##### Auto persistence
When enabled, this passes your datatabase table data automagically to the view. You enable it in your controller action the following way. 

<pre>`$this->autoPersist = true;`</pre>

Supported views are index, view, edit, add. When used in the latter two views, this will also automatically store the data if you perform a post request.

##### Fluent query

Using MVC-X fluent queries you can retrieve your specific desired data. It follows the model of $this->modelName->getColumnsByCriteria(). Example usage:

##### `$this->blog->getAll();` 
Returns all the blog posts i.e. all entries from the 'blog' table

##### `$this->blog->getAllById(5);` 
Returns the blog post with id equal to 5

##### `$this->blog->getAllByContent('%hurricane%');` 
Returns a list of blog posts that have the word 'hurricane' in its content

##### `$this->blog->getAllByContentAndCreated('%hurricane%','> 2014-01-01');` 
Returns a list of blog posts that have the word 'hurricane' in its content, created after 2014-01-01

##### `$this->blog->getTitleByUser(12);`
Returns a list of blog titles published by user with id = 12

##### `$this->blog->getTitleAndContentByUserAndStatus(12, true);`
Returns a list of blog titles and content, from blog posts with active status, published by user with id = 12

##### Database query

A normal query can be accomplished using your DB engine syntax. In this example, we are selecing all blog posts using MySQL. The returned result will be an array of posts.

##### `$posts = $this->blog->query('SELECT * FROM blog');`

#### III. Saving data
* * *
The following ways of saving data are available,

##### `$this->modelName->save($data)`

This will save an array of data having its keys matching the table columns. It can be single entry data or array of entries. If id is present, it will update the data, if not, it will insert it. 

##### `$this->modelName->saveEntry($data)`

This saves an array of data, exactly the same way as save(), with the only difference it can be used for single entries only.

##### `$this->modelName->lastId()`

Returns the id of the last inserted database table row.


#### IV. Debugging
* * *
The following techniques are available for debugging.

##### `pr(mixed $var)`

This will output a variable, array or object of your choice in well-formatted manner.

##### `debug_mode=>true`

This is a setting in the config.php of your app, which when enabled will produce useful debug information at the bottom of every view.



