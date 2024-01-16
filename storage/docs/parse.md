to parse a file using the larapase package, first load it from the filesystem. 

```
use App\LaraParse\Parse;

$parsed = Parse::file('path/to/file');
```

This will return an array of each paragraph from the markdown file. you map loop over it. 

```
use App\LaraParse\Parse; 

$parsed = Parse::file('path/to/file.md');

$firstParagraph = $parsed[0]';
```


