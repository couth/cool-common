# cool-common
 Common Library for Cool projects.


 - about FormHelper
 
 ```
// This class is compatible with these templates
//    'text' => '<input type="text" name="%s" value="%s" />',
//    'select' => ['<select name="%s">', '<option%s value="%s">%s</option>', '</select>'],

/*
 *
 *  return example
    Array
    (
        [0] => Array
            (
                [0] => text
                [1] => Array
                    (
                        [0] => sitename
                        [1] => hero
                    )
            )
        [1] => Array
            (
                [0] => select
                [1] => Array
                    (
                        [0] => Array
                            (
                                [0] => language
                            )
                        [1] => Array
                            (
                                [0] => Array
                                    (
                                        [0] =>  selected="selected"
                                        [1] => zh_CN
                                        [2] => 简体中文
                                    )
                                [1] => Array
                                    (
                                        [0] =>
                                        [1] => en_US
                                        [2] => 英语
                                    )
                            )
                        [2] => Array
                            (
                            )
                    )
            )
    )

*/

```
 
 
 - about FormCore
 
```
$cfg = [
    'formOpen' => '<form action="%s" method="%s">',
    'text' => '<input type="text" name="%s" value="%s" />',
    'select' => ['<select name="%s">', '<option%s value="%s">%s</option>', '</select>'],
    'formEnd' => '</form>',
];

$form = new FormCore($cfg);
$form->addMultiData(['formOpen', ['', 'post']]);
$form->addData(['text', ['fname', 'carl']]);
$form->addData(['formEnd', []]);
echo $form->render();

```
