# SimpleHashId
A simple class that produce hash with incrementable ids. All hash are 100 % unique.

How to use : include file, instanciate that class, provide a minimum hash length and a random salt.

```
require 'SimpleHashId.php';
$myhashid = new SimpleHashId(6, 'randomsalthere');

// Use encode function to encode id
$myhashid->encode(1); // generates 'nryozKt'
$myhashid->encode(2); // generates 'yrEOxRa'
$myhashid->encode(3); // generates 'nyrozKt'
$myhashid->encode(4); // generates 'ErMAGQT'

$myhashid->encode(1000); // generates 'oZHdO1'
$myhashid->encode(1001); // generates 'noZHdO1'
$myhashid->encode(1002); // generates 'yoZHdO1'


//Use decode function to decode the hash
$myhashid->decode('nryozKt')); // will return 1
$myhashid->decode('yrEOxRa')); // will return 2
$myhashid->decode('nyrozKt')); // will return 3
$myhashid>decode('ErMAGQT')); // will return 4
		
$myhashid->decode('oZHdO1')); // will return 1000
$myhashid->decode('noZHdO1')); // will return 1001
$myhashid->decode('yoZHdO1')); // will return 1002
```
You can use a different salt, for example :
```
$userishasher = new SimpleHashId(6, 'users');
$arcticlehasher = new SimpleHashId(6, 'articles');

// each of them will generate their own set of hash
$userishasher->encode(1234); // will generate 'd9ECVs'
$arcticlehasher->endode(1224); // will generate 'TL6yce'
```

By default, hasher uses alpha characters and numbers. You can set a custom character table. But lesser the character table's length is, less amount of id can be hashed. Use function stats() to know what is the maximum value that the class can hash.

```
$examplehasher = new SimpleHashId(6, 'randomsalthere', 'smalltable');
$examplehasher->encode(1); // will generate 'atlmmm'
$examplehasher->encode(2); // will generate 'ltssss'
$examplehasher->encode(3); // will generate 'altmmm'
$examplehasher->encode(4); // will generate 'stbaaa'

// this hash has less character used to hash ids, so it can encode only numbers from 0 to 64. This is what function stats will show
$examplehasher->stats();

// Will show
Array
(
    [character_table] => smaltbe
    [len] => 6
    [min] => 0
    [max] => 64
    [generated_hash] => alsmbe
    [break_character] => t
)
```

Enjoy,

Simonniz
