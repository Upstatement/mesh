# Mesh
Bootstrap content into a WordPress site

### Add a Post
```php
/* functions.php */
$post = new Mesh\Post('Hello World', 'post');
// add content...
$post->set('post_content', 'This is your first WordPress post');
// add custom fields...
$post->set('my_foo', 'bar');
// "thumbnail" is a reserved key to add post thumbnails
$post->set('thumbnail', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d6/STS120LaunchHiRes-edit1.jpg/490px-STS120LaunchHiRes-edit1.jpg');
```

### Add a User
/* functions.php */
$user = new Mesh\User('Jared Novack', 'subscriber');
// add content...
$user->set('description', 'Jared is cool');
// add custom fields...
$user->set('my_foo', 'bar');
// add images
$user->set_image('headshot', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d6/STS120LaunchHiRes-edit1.jpg/490px-STS120LaunchHiRes-edit1.jpg');
```

### Import JSON
See the [sample data](https://github.com/jarednova/mesh/blob/master/sample-data.json) for an example of what this should look like.
```php
$loader = new Mesh\JSON_Loader(__DIR__.'/../static/data/mesh-data.json');
```
