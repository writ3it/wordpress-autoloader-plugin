# Wordpress autoloader plugin
PSR-4 autoloader for wordpress developers
## Getting Started
To use a plugin, copy wp-content to your wordpress directory. That's all. Now you can use autoloader. See the example below:
```php
// f.e. hello-dolly.php - main plugin file
<?php
___Autoloader::namespaces([
            'PluginNS\\'=>__DIR__.'/src'
        ]);
// ... some useful code of your plugin

use PluginNS/SomeClass;

$instance = new SomeClass();
```

## Testing

```bash
$ composer install
$ composer test
```

## FAQ: A few words of explanation

1. *Why?* 
Ofcourse, We could use composer autoloader for each plugin but this practise isn't DRY.

2. *Why mu-plugins?*
Mu-plugins are loaded earlier than standard plugins.

3. *Why the name contains the exclamation mark!?*
Because exclamation mark occurs earlier than letters at ASCII table. Wordpress use sort function to determine order of plugin loading and autoloader should be first.

4. *Isn't the exclamation mark a problem?*
In fact is it, but I think that only for a windows based servers.



## Contributing


If you have discovered a bug or have a feature suggestion, feel free to create an issue on GitHub. Remember to write very detailed describtion with instructions of bug reproduction.

If you'd like to make some changes yourself, see the following:

1. Fork this repository to your own GitHub account and then clone it to your local device.
2. Write some tests of your improvements. Test should goes fail. (See [TDD](https://en.wikipedia.org/wiki/Test-driven_development).) 
3. Write changes that pass the tests.
4. If you contributed something new, add your profile to Contributors section.
5. Finally, **submit a pull request** with your changes!

## Contributors
Thanks goes out to all people:

- ***Your name*** - some description of changes - [github username](https://github.com/writ3it)

## License
This software is licensed under the [MIT](https://github.com/writ3it/wordpress-autoloader-plugin/blob/master/LICENSE).
