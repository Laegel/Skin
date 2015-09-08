# Skin
Skin is a PHP template engine with (X)HTML rendering. It manages loops, conditions, variables and uses a really simple syntax.

Skin templates have _.skln_ as file extension.
##Manage output
You're not obliged to set output as HTML5 ; it can be XHTML, or any HTML version that Skin can handle.

Of course, you don't have to modify a single line of your skeleton template to make a change in output type.

##Caching
Skin uses cache to improve performances by reducing execution time and avoid parsing uselessly an already parsed template.

If you modify a template, just remember to force cache expiration.

##HTML validation
Skin will always output W3C valid HTML. If you write something that doesn't exist, such as a tag or an attribute, it will
be adapted into a valid result.

##Parsing exception handler
Skin includes an exception handler that will throw errors and which line the problem happens.

##Inheritance & inclusion

###Extends
With Skin, you can extend your template with another main template.
For example, if you write `@extends main` in your current template, it will extend the "main.skln" template.

You can use slashes or backslashes if you need to access a template from a directory : `@extends main/main` will look for the template main.skln in your views directory, then in "main" directory.

You then need to write `@content` where you want to display your extended template in the parent.

###Include
You can also include partial views that would be recurrent (for example : a navigation view).

To do so, write `@partial nav` in your current template where you want to include the partial view "nav.skln". 

Just as extending, there's a possibility to fetch partial content from another directory by using slashes or backslashes.

##Tags & indent
Instead of writing (X)HTML with tags in your template, you only have to type HTML elements names. 
Just note that you must indent and add new lines to create relationship between elements.

For example :

```
div id(myDiv)
  h1 class(myHeaders)
    Hello, I'm a header.
    span
      Hi, I'm a span in the header.
```
will render
```
<div id="myDiv">
  <h1 class="myHeaders">
    Hello, I'm a header.
    <span>
      Hi, I'm a span in the header.
    </span>
  </h1>
</div>
```

###Element syntax
An element in Skin template is composed by a tag name and potentially attributes.

Attributes are composed by a name, opening parenthesis, value then closing parenthesis.
Here is a complete element syntax :

`div id(myId) class(classOne; classTwo) contenteditable(true)`

/!\ _HTML boolean attributes such as "checked", "readonly", etc ... require a value in Skin templates._
/!\ _If you need to write text and wanna be sure it won't be parsed, just write "plaintext" as a tag and put your text under it._

###Shortcuts for special attributes
Skin, id and class attributes have shortcut attributes that you can use in your skeleton to write your code faster.

<ul>
<li><b>#</b> : id</li>
<li><b>.</b> : class</li>
<li><b>%</b> : skloop</li>
<li><b>?</b> : skif</li>
<li><b>:</b> : skelse</li>
<li><b>$</b> : sksample</li>
</ul>

While using the shortcuts, you're not obliged to type the full attributes syntax ; you can avoid using the parenthesis like this :
`div #myId .classOne;classTwo`

/!\ _Just remember not to use blank spaces if you have multiple values in your attribute or parsing will fail._

##Variables
Need to display a variable content ? Like many other template engines, variables are displayed by using braces :

`{{ variable }}`

You can use treatment functions to modify these variables like following :

`{{ variable | function1, function2 }}`

You can also display a property if the data is an object or an associative array :

`{{ object.property }}`

/!\ _Variables are automatically escaped. If you want to display some code or HTML tags, just type "unescape" in functions slot._

##Translations
To display a translation string, it uses braces and equal signs :

`{= INDEX =}`

It will look for "INDEX" index in translation's current language array.

If you need to display data in that string, just do :

`{= INDEX | variable, object.property =}`


##Loops

###Set a loop

Loops are used for collections or numerical arrays. You can access each item property by using the alias concatenated with the property name.

To set a loop, you need to use `skloop` attribute in an element like this :

```
ul skloop(myItems=>item)
  li
    {{ item.property }}
```
where "myItems" is the data index and "item" is its alias, just as in foreach loops.


###Loops' special properties

Loops have special properties, such as length or current item numeric index.

You can display them like this :

`{{ item.$ }}` for the array length

and

`{{ item.# }}` for the current item index.

/!\ _Don't forget that you cannot access an item index out of a loop context._

##Conditions

Conditions are created with "skif" and "skelse" attributes.

For example, let's say that we have a data index called "test" and the following code sample :

```
section skif(test)
  If test is "true", display me !
  
section skelse(test)
  Else, display me !
```

