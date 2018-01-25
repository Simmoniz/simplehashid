# simplehashid
A simple class that produce hash with incrementable ids. All produced hash are unique

How to use
Include file, instanciate that class, provide the minimum length of your hashes and a random salt.

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


//Use decode to decode the hash
$myhashid->decode('nryozKt')); // will return 1
$myhashid->decode('yrEOxRa')); // will return 2
$myhashid->decode('nyrozKt')); // will return 3
$myhashid>decode('ErMAGQT')); // will return 4
		
$myhashid->decode('oZHdO1')); // will return 1000
$myhashid->decode('noZHdO1')); // will return 1001
$myhashid->decode('yoZHdO1')); // will return 1002

// A cool thing is to "interface" the ids you want to hash according to type of element the id is related to, for example :
$userishasher = new SimpleHashId(6, 'users');
$arcticlehasher = new SimpleHashId(6, 'articles');

// each of them will generate their own different "hash set"
$userishasher->encode(1234); // will generate 'd9ECVs'
$arcticlehasher->endode(1224); // will generate 'TL6yce'
```

Below 'd9ECVs' and 'TL6yce' are same values, but hashed differently. Useful to hash all database autoincrement using the table name as a salt. But when decoding, the type of hash must be known. Generally, URIs identifies a ressource type and its id, so a different hasher could be used to decode hash like this :

example.com/user/d9ECVs  => user # 1234, because this URL points to a user ressource, which need to decode the hash with salt 'users'
example.com/article/TL6yce => article # 1234, because this URL points to an article ressource, which need to decode the hash with salt 'articles'

By default, hasher uses alpha characters and numbers. You can set a custom character table. But lesser the character table's length is, less amount of id can be hashed. Use function stats() to know what is the maximum value that the class can hash.

```
$examplehasher = new SimpleHashId(6, 'randomsalthere', 'smalltable');
$examplehasher->encode(1); // will generate 'leabsbs'
$examplehasher->encode(2); // will generate 'aetllll'
$examplehasher->encode(3); // will generate 'laebsbs'
$examplehasher->encode(4); // will generate 'telsbsb'

// this hash has less character used to hash ids, so it can encode only numbers from 0 to 512. This is what function stats will show
$examplehasher->stats();

// Will show
Array
(
    [character_table] => smaltbe
    [len] => 9
    [min] => 0
    [max] => 512
    [generated_hash] => latblasml
    [break_character] => e
)
```

Enjoy and feel free to make version of your favorite programming language. All this is done with simple coding
