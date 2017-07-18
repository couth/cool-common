# cool-common
 Common Library for Cool projects.


 - about Form\FormHelper
 
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
 
 
 - about Form\FormCore
 
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

 - about Catagory\CatagoryHelper
 
```
//====================Test code ================================

header("content-type:text/html;charset=utf-8");
$categories = array(
    array('id'=>1,'name'=>'电脑','pid'=>0),
    array('id'=>2,'name'=>'手机','pid'=>0),
    array('id'=>3,'name'=>'笔记本','pid'=>1),
    array('id'=>4,'name'=>'台式机','pid'=>1),
    array('id'=>5,'name'=>'智能机','pid'=>2),
    array('id'=>6,'name'=>'功能机','pid'=>2),
    array('id'=>7,'name'=>'超级本','pid'=>3),
    array('id'=>8,'name'=>'游戏本','pid'=>3),
);

//$cate = new CateHelper();
$tree = CateHelper::planeToTree($categories);

print_r($tree);

$data = CateHelper::treeToPlane($tree);

print_r($data);

print_r(CateHelper::sort($data));

print_r(CateHelper::sort($data, 1));

```

 - about Http\CurlHelper

```

//============ Test =================
//$curl = new curlHelper();
//$url = 'http://a.com/dfdsf/?dxid=dsl';
//$curl->curlGet($url,['name'=>'carl']);

```