Object To String
================
Just like the php __toString() method, but with the power of the [symfony expression language](http://symfony.com/doc/current/components/expression_language.html).

Installation
------------

### Download the Library

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require sk/object-to-string "dev-master"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Usage
-----

Before you can use the power of this library you have to initialize it. Depending on your needs, activate the driver you want.
Here is a full example with all available driver:
```php
<?php

use SK\ObjectToString\Metadata\Driver\AnnotationDriver;
use SK\ObjectToString\Metadata\Driver\XmlDriver;
use SK\ObjectToString\Metadata\Driver\YamlDriver;
use SK\ObjectToString\Metadata\Driver\PhpDriver;
use SK\ObjectToString\ObjectToString;
use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\Driver\FileLocator;
use Metadata\Driver\DriverChain;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

$fileLocator = new FileLocator(array('' => realpath('/path/to/config')));
$driverChain = new DriverChain([
    new AnnotationDriver(new AnnotationReader()),
    new XmlDriver($fileLocator),
    new YamlDriver($fileLocator),
    new PhpDriver($fileLocator),
]);

$metadataFactory =new \Metadata\MetadataFactory($driverChain);

$expressionLanguage = new ExpressionLanguage();

$objectToString = new ObjectToString($metadataFactory, $expressionLanguage);
```

But also you can just activate one driver. Just the annotation driver for example:

```php
<?php

use SK\ObjectToString\Metadata\Driver\AnnotationDriver;
use SK\ObjectToString\ObjectToString;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

$metadataFactory =new \Metadata\MetadataFactory(new AnnotationDriver(new AnnotationReader()));

$expressionLanguage = new ExpressionLanguage();

$objectToString = new ObjectToString($metadataFactory, $expressionLanguage);

```

#### Expression Language

This library uses the [symfony/expression-language](http://symfony.com/doc/current/components/expression_language.html) as string generator.
Therefore you are able to use all the it's features like caching, extending ...

As a little extra goody, the object self is always available as '_this'.

So instead writing
```
/**
 * [...]
 * @ObjectToString(name="name", format="name", params={"name": "name"})
 *[...]
 */
```
you are always able to replace it with
```
/**
 * [...]
 * @ObjectToString(name="name", format="_this.getName()")
 * [...]
 */
```
### Configuration Formats

This library provides all commonly used configuration formats which are annotation, XML, Yaml and Php. It is up to you
which one you want to use. Just add the drive and start writing the configuration.

#### Annotation

```php
<?php

namespace Acme\Model;

use SK\ObjectToString\Annotation\ObjectToString;

/**
 * Class Email
 *
 * @ObjectToString(name="name", format="name", params={"name": "name"})
 * @ObjectToString(name="email", format="email", params={"email": "email"})
 * @ObjectToString(name="email_alternative", format="email", params={"email": "emailAlternative"})
 * @ObjectToString(name="full_email", format="name ~ ' <' ~ email ~ '>'", params={"email": "email", "name": "name"})
 * @ObjectToString(name="full_email_alternative", format="name ~ ' <' ~ email ~ '>'", params={"email": "emailAlternative", "name": "name"})
 * @ObjectToString(name="full_email_validated", format="_this.getName() ~ (_this.isValidated() ? ' (validated)') ~' <' ~ _this.getEmail() ~ '>'")
 */
class Email
{

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $emailAlternative;

    /**
     * @var string
     */
    private $name;
    
    /**
     * @var bool
     */
    private $validated = false;
    
    /**
     * Email constructor.
     *
     * @param string $email
     * @param string $emailAlternative
     * @param string $name
     */
    public function __construct($name = null, $email = null, $emailAlternative = null)
    {
        $this->email = $email;
        $this->emailAlternative = $emailAlternative;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailAlternative()
    {
        return $this->emailAlternative;
    }

    /**
     * @param string $emailAlternative
     *
     * @return Email
     */
    public function setEmailAlternative($emailAlternative)
    {
        $this->emailAlternative = $emailAlternative;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Email
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param bool $validated
     *
     * @return Email
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }
}
```

#### Php

```php
<?php
// file: /path/to/config/Acme.Model.Email.php

$metadata = new \SK\ObjectToString\Metadata\ClassMetadata(
    \Acme\Model\Email::class
);
$metadata->addToString('name', 'name', array('name' => 'name'));
$metadata->addToString('email', 'email', array('email' => 'email'));
$metadata->addToString('email_alternative', 'email', array('email' => 'emailAlternative'));
$metadata->addToString('full_email', 'name ~ \' <\' ~ email ~ \'>\'', array('email' => 'email', 'name' => 'name'));
$metadata->addToString(
    'full_email_alternative',
    'name ~ \' <\' ~ email ~ \'>\'',
    array('email' => 'emailAlternative', 'name' => 'name')
);

return $metadata;
```

#### YAML

```yaml
# file: /path/to/config/Acme.Model.Email.yml
\Acme\Model\Email:
    name:
        format: "name"
        params:
            name: "name"
    email:
        format: "email"
        params:
            email: "email"
    email_alternative:
        format: "email"
        params:
            email: "emailAlternative"
    full_email:
        format: "name ~ ' <' ~ email ~ '>'"
        params:
            name: "name"
            email: "email"
    full_email_alternative:
        format: "name ~ ' <' ~ email ~ '>'"
        params:
            name: "name"
            email: "emailAlternative"
```

#### XML

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<object-to-string>
    <class name="\Acme\Model\Email">
        <name name="name" format="name">
            <param name="name">name</param>
        </name>
        <name name="email" format="email">
            <param name="email">email</param>
        </name>
        <name name="email_alternative" format="email">
            <param name="email">emailAlternative</param>
        </name>
        <name name="full_email" format="name ~ ' &lt;' ~ email ~ '&gt;'">
            <param name="email">email</param>
            <param name="name">name</param>
        </name>
        <name name="full_email_alternative" format="name ~ ' &lt;' ~ email ~ '&gt;'">
            <param name="email">emailAlternative</param>
            <param name="name">name</param>
        </name>
    </class>
</object-to-string>
```
```
A note to xml:
As you can see in this example, you have to escape some characters. Here the characters '<' and '>' are replaced with '&lt;' and 'gt;'.
So if you get a xml error like 'Extra content at the end of the document', make sure your xml is valid.
For more information take a look at:
  - [XML escape characters - on stackoverflow.com](http://stackoverflow.com/a/1091953/3972213)
  - [Character Data and Markup - on www.w3.org](https://www.w3.org/TR/xml/#syntax)
```

### Example Usage

After initializing and defining the names you are now able to use it:

```php
<?php

$email = new \Acme\Model\Email('John Doe', 'john.doe@example.com', 'jd@example.com');

echo $objectToString->generate('name', $email) . "\n";
echo $objectToString->generate('email', $email) . "\n";
echo $objectToString->generate('email_alternative', $email) . "\n";
echo $objectToString->generate('full_email', $email) . "\n";
echo $objectToString->generate('full_email_alternative', $email) . "\n";
echo $objectToString->generate('full_email_validated', $email) . "\n";
$email->setValidated(true);
echo $objectToString->generate('full_email_validated', $email) . "\n";
        
```
#### Output

```
John Doe
john.doe@example.com
jd@example.com
John Doe <john.doe@example.com>
John Doe <jd@example.com>
John Doe <john.doe@example.com>
John Doe (validated) <john.doe@example.com>
```

Issues and feature requests
===========================

Issues and feature requests are handled on github. If you found a bug, you are always welcome to open an issue. And also feel
free to create a pull request with a fix. Same for feature requests.

License
=======

This library is under the MIT license. See the complete license in the library LICENSE file.
