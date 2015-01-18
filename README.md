# mvc-x

MVC-X is a fast multi-app, multi-database MVC with low-coupled extension support and small footprint.

#### I. Retrieving data
* * *
The following ways of retrieving data are available,

##### Auto persistence
When enabled, this passes your datatabase table data automagically to the view. You enable it in your controller action the following way. 

`$this->autoPersist = true;`

Supported views are index, view, edit, add. When used in the latter two views, this will also automatically store the data if you perform a post request.

##### Fluent query

Using MVC-X fluent queries you can retrieve your specific desired data. It follows the model of $this->modelName->getColumnsByCriteria(). Example usage:

##### `$this->blog->getAll();` 
Returns all the blog posts i.e. all entries from the 'blog' table

##### `$this->blog->getAllById(5);` 
Returns the blog post with id equal to 5

##### `$this->blog->getAllByContent('%hurricane%');` 
Returns a list of blog posts who have the word 'hurricane' in its content

##### `$this->blog->getAllByContentAndCreated('%hurricane%','> 2014-01-01');` 
Returns a list of blog posts who have the word 'hurricane' in its content, that are created after 2014-01-01

##### `$this->blog->getTitleByUser(12);`
Returns a list of blog titles published by user with id = 12

##### `$this->blog->getTitleAndContentByUserAndStatus(12, true);`
Returns a list of blog titles and content, from blog posts with active status, published by user with id = 12

##### Database query

`$posts = $this->modelName->query('SELECT * FROM blog');`

#### II. Saving data
* * *
The following ways of saving data are available,

##### `$this->modelName->save($data)`

This will save an array of data having its keys matching the table columns. It can be single entry data or array of entries. If id is present, it will update the data, if not, it will insert it. 

##### `$this->modelName->saveEntry($data)`

This saves an array of data, exactly the same way as save(), with the only difference it can be used for single entries only.

##### `$this->modelName->lastId()`

Returns the id of the last inserted database table row.


#### III. Debugging
* * *
The following techniques are available for debugging.

##### `pr(mixed $var)`

This will output a variable, array or object of your choice in well-formatted manner.

##### `debug_mode=>true`

This is a setting in the config.php of your app, which when enabled will produce useful debug information at the bottom of every view.



