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

 - about Category\CategoryHelper
 
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

//$cate = new CategoryHelper();
$cate = CategoryHelper::render($categories);
$cate = CategoryHelper::format($categories);

```

 - about Http\CurlHelper

```

//============ Test =================
//$curl = new curlHelper();
//$url = 'http://a.com/dfdsf/?dxid=dsl';
//$curl->curlGet($url,['name'=>'carl']);

```

 - about Pagination\Pagination
 
```

//============ Generate normal pagination =================
$p = new Pagination();
$page = isset($_GET['page']) ? intval($_GET['page']) : 8;
$page = $page > 0 ? $page : 8;
$p->init($page, 123);
$p->setTotalNum(1230);

// echo css
echo '<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.1.0/css/bootstrap.min.css">
      <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
      <script src="https://cdn.staticfile.org/popper.js/1.12.5/umd/popper.min.js"></script>
      <script src="https://cdn.staticfile.org/twitter-bootstrap/4.1.0/js/bootstrap.min.js"></script>
      <style type="text/css">
          .input-page {
              width: 3em;
          }
      </style>';
          
echo $p->view();

```

```
// Generate js pagination. (You can add js event to loop)

$p = new Pagination();
$page = isset($_GET['page']) ? intval($_GET['page']) : 7;
$page = $page > 0 ? $page : 7;
$p->init($page, 123);

$template = [
    'pageWrapper' => '<ul class="pagination pagination-sm">%s</ul>',
    'firstPage' => [
        '<li class="page-item"><span class="page-link page-item-click" data-page="%s">%s</span></li>',
        '<li class="page-item disabled"><span class="page-link">%s</span></li>',
    ],
    'preGroupPage' => [
        '<li class="page-item"><span class="page-link page-item-click" data-page="%s">%s</span></li>',
        '<li class="page-item disabled"><span class="page-link">%s</span></li>',
    ],
    'prePage' => [
        '<li class="page-item"><span class="page-link page-item-click" data-page="%s">%s</span></li>',
        '<li class="page-item disabled"><span class="page-link">%s</span></li>',
    ],
    'pageItem' => '<li class="page-item"><span class="page-link page-item-click" data-page="%s">%s</span></li>',
    'currentPage' => '<li class="page-item disabled"><span class="page-link">%s</span></li>',
    'nextPage' => [
        '<li class="page-item"><span class="page-link page-item-click" data-page="%s">%s</span></li>',
        '<li class="page-item disabled"><span class="page-link">%s</span></li>',
    ],
    'nextGroupPage' => [
        '<li class="page-item"><span class="page-link page-item-click" data-page="%s">%s</span></li>',
        '<li class="page-item disabled"><span class="page-link">%s</span></li>',
    ],
    'lastPage' => [
        '<li class="page-item"><span class="page-link page-item-click" data-page="%s">%s</span></li>',
        '<li class="page-item disabled"><span class="page-link">%s</span></li>',
    ],
    'loopPage' => '<li>
            <form action="%s" method="get">
                <div class="input-group input-group-sm">
                    <input class="input-page" name="page" type="text">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-info">%s</button>
                    </div>
                </div>
            </form>
        </li>',
    'totalPage' => '<li class="page-item disabled"><span class="page-link">%s</span></li>',
    'totalNum' => '<li class="page-item disabled"><span class="page-link">%s</span></li>',
];

$p->setTemplate($template);
$p->setTotalNum(12050);
echo $p->view();

```