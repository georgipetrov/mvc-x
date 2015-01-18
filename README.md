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
<pre>
array(
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
);</pre>

 *   url - here you should add your site public url without protocol and subdomain, e.g. mysite.com 
 *   dir - the name of the app directory on your server e.g. mysite
 *   db - the database configuration of the app
 *   debug_mode - If you enable debug mode, you will see debug information at the bottom of your page. To put this app in debug mode you need to set it to 1, otherwise leave it 0.


#### III. Retrieving your data
* * *
The following ways of retrieving data are available,

##### Auto persistence
When enabled, this passes your datatabase table data automagically to the view. You enable it in your controller action the following way. 

<pre>$this->autoPersist = true;</pre>

Supported views are index, view, edit, add. When used in the latter two views, this will also automatically store the data if you perform a post request.

##### Fluent query

Using MVC-X fluent queries you can retrieve your specific desired data. It follows the model of $this->modelName->getColumnsByCriteria(). Example usage:

<pre>$this->blog->getAll();</pre> 
Returns all the blog posts i.e. all entries from the 'blog' table

<pre>$this->blog->getAllById(5);</pre> 
Returns the blog post with id equal to 5

<pre>$this->blog->getAllByContent('%hurricane%');</pre> 
Returns a list of blog posts that have the word 'hurricane' in its content

<pre>$this->blog->getAllByContentAndCreated('%hurricane%','> 2014-01-01');</pre> 
Returns a list of blog posts that have the word 'hurricane' in its content, created after 2014-01-01

<pre>$this->blog->getTitleByUser(12);</pre>
Returns a list of blog titles published by user with id = 12

<pre>$this->blog->getTitleAndContentByUserAndStatus(12, true);</pre>
Returns a list of blog titles and content, from blog posts with active status, published by user with id = 12

##### Database query

A normal query can be accomplished using your DB engine syntax. In this example, we are selecing all blog posts using MySQL. The returned result will be an array of posts.

<pre>$posts = $this->blog->query('SELECT * FROM blog');</pre>

#### IV. Saving your data
* * *
The following ways of saving data are available,

<pre>$this->modelName->save($data)</pre>

This will save an array of data having its keys matching the table columns. It can be single entry data or array of entries. If id is present, it will update the data, if not, it will insert it. 

<pre>$this->modelName->saveEntry($data)</pre>

This saves an array of data, exactly the same way as save(), with the only difference it can be used for single entries only.

<pre>$this->modelName->lastId()</pre>

Returns the id of the last inserted database table row.

#### V. Database table structure
* * *
In order to auto-bind database table to model, you need to have your table under the same name as your model, e.g. blog. While the only required column is `id`, you can make a good use of a few natively populated columns if you have them added. Please find the names of the columns which if you have added you will get them autofilled by MVC-X on each save operation,

* `id` - this is the autoincrement primary key of the table
* `ua` - the useragent of the user performing the save/update request with its browser information
* `ip` - the ip of the user performign the save/update request, catches IPs behind proxy too
* `created` - date of entry created
* `modified` - date of entry modified

#### VI. Debugging
* * *
The following techniques are available for debugging.

<pre>pr(mixed $var)</pre>

This will output a variable, array or object of your choice in well-formatted manner.

<pre>debug_mode=>true</pre>

This is a setting in the config.php of your app, which when enabled will produce useful debug information at the bottom of every view.
