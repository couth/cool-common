<?php
namespace Cool\Common\Pagination;

/**
 * Author: Carl Liu<coolboy@outlook.com>
 * Complete Date: 2018/11/8 15:56
 * Change record:
 *   Date:
 *   Version:
 *   Author:
 *   Content:
 */
class Pagination
{
    private $page = 1;
    private $totalPage = 1;
    private $groupSize = 5;
    private $firstPage = 1;
    private $prePage = 1;
    private $nextPage = 1;
    private $lastPage = 1;
    private $preGroupPage = 1;
    private $currentGroup = 1;
    private $nextGroupPage = 1;
    private $totalGroup = 1;
    private $totalNum = 20;
    private $pageLink = '';
    private $templateFlag = [
        'showFirstLastPage' => true,
        'showPreNextPage' => true,
        'showGroup' => true,
        'showLoop' => true,
        'showTotalPage' => true,
        'showTotalNum' => true,
    ];
    private $template = [
        'pageWrapper' => '<ul class="pagination pagination-sm">%s</ul>',
        'firstPage' => [
            '<li class="page-item"><a class="page-link" href="%s">%s</a></li>',
            '<li class="page-item disabled"><a class="page-link" href="#">%s</a></li>',
        ],
        'preGroupPage' => [
            '<li class="page-item"><a class="page-link" href="%s">%s</a></li>',
            '<li class="page-item disabled"><a class="page-link" href="#">%s</a></li>',
        ],
        'prePage' => [
            '<li class="page-item"><a class="page-link" href="%s">%s</a></li>',
            '<li class="page-item disabled"><a class="page-link" href="#">%s</a></li>',
        ],
        'pageItem' => '<li class="page-item"><a class="page-link" href="%s">%s</a></li>',
        'currentPage' => '<li class="page-item disabled"><a class="page-link" href="#">%s</a></li>',
        'nextPage' => [
            '<li class="page-item"><a class="page-link" href="%s">%s</a></li>',
            '<li class="page-item disabled"><a class="page-link" href="#">%s</a></li>',
        ],
        'nextGroupPage' => [
            '<li class="page-item"><a class="page-link" href="%s">%s</a></li>',
            '<li class="page-item disabled"><a class="page-link" href="#">%s</a></li>',
        ],
        'lastPage' => [
            '<li class="page-item"><a class="page-link" href="%s">%s</a></li>',
            '<li class="page-item disabled"><a class="page-link" href="#">%s</a></li>',
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
        'totalPage' => '<li class="page-item disabled"><a class="page-link" href="#">%s</a></li>',
        'totalNum' => '<li class="page-item disabled"><a class="page-link" href="#">%s</a></li>',
    ];
    private $templateLang = [
        'firstPage' => '首页',
        'preGroupPage' => '前 %s 页',
        'prePage' => '上一页',
        'pageItem' => '%s',
        'currentPage' => '%s',
        'nextPage' => '下一页',
        'nextGroupPage' => '后 %s 页',
        'lastPage' => '尾页',
        'loopPage' => '跳转',
        'totalPage' => '共 %s 页',
        'totalNum' => '共 %s 条',
    ];


    /**
     * init page with params
     *
     * @param int $page
     * @param int $totalPage
     * @param string $pageLink
     * @param int $groupSize
     */
    public function init($page = 1, $totalPage = 1, $pageLink = '?page=%s', $groupSize = 0)
    {
        if ($groupSize > 0) {
            $this->groupSize = $groupSize;
        }

        if($page > $totalPage) {
            $page = $totalPage;
        }

        // current page
        $this->page = $page;
        $this->totalPage = $totalPage;
        $this->lastPage = $totalPage;

        // prePage
        if ($this->page > 1) {
            $this->prePage = $this->page - 1;
        } else {
            $this->prePage = 0;
        }

        // next page
        if ($this->page < $this->lastPage) {
            $this->nextPage = $this->page + 1;
        } else {
            $this->nextPage = 0;
        }

        // total page group
        $this->totalGroup = ceil($this->totalPage / $this->groupSize);
        $this->currentGroup = ceil($this->page / $this->groupSize);
        if ($this->currentGroup > 1) {
            $this->preGroupPage = $this->page - $this->groupSize;
        } else {
            $this->preGroupPage = 0;
        }

        if ($this->currentGroup < $this->totalGroup) {
            $this->nextGroupPage = $this->page + $this->groupSize;
        } else {
            $this->nextGroupPage = 0;
        }

        $this->pageLink = $pageLink;
    }


    /**
     * set template flag
     *
     * @param array $conf
     */
    public function setTemplateFlag($conf = [])
    {
        $this->templateFlag = $this->merge($this->templateFlag, $conf);

    }


    /**
     * set template language
     *
     * @param array $conf
     */
    public function setTemplateLang($conf = [])
    {
        $this->templateLang = $this->merge($this->templateLang, $conf);
    }


    /**
     * set html template
     *
     * @param array $conf
     */
    public function setTemplate($conf = [])
    {
        $this->template = $this->merge($this->template, $conf);

    }


    /**
     * set html template
     *
     * @param integer $num
     */
    public function setTotalNum($num = 20)
    {
        $this->totalNum = intval($num);

    }

    /**
     * set html template
     *
     * @param string $loopUrl
     */
    public function setLoopUrl($loopUrl = '')
    {
        $this->template['loopPage'] = str_replace(
            'action="%s"', 'action="' . $loopUrl . '"',
            $this->template['loopPage']
        );
    }


    /**
     * render html
     *
     * @return string
     */
    public function view()
    {
        if ($this->totalPage < 1) {
            return '';
        }
        $html = '';

        $firstPageHtml = '';
        $lastPageHtml = '';
        $prePageHtml = '';
        $nextPageHtml = '';
        $preGroupPageHtml = '';
        $nextGroupPageHtml = '';
        $loopPageHtml = '';
        $totalPageHtml = '';
        $totalNumHtml = '';

        if ($this->pageLink) {
            $this->addPageLink($this->pageLink);
        }

        $this->templateLang['currentPage'] = $this->vsprintf($this->templateLang['currentPage'], $this->page);

        if ($this->templateFlag['showFirstLastPage']) {
            $firstPageHtml = $this->statusHtml('firstPage', 'firstPage');
            $lastPageHtml = $this->statusHtml('lastPage', 'lastPage');
        }

        if ($this->templateFlag['showPreNextPage']) {

            $prePageHtml = $this->statusHtml('prePage', 'prePage');
            $nextPageHtml = $this->statusHtml('nextPage', 'nextPage');
        }

        if ($this->templateFlag['showGroup']) {
            $this->templateLang['preGroupPage'] = $this->vsprintf($this->templateLang['preGroupPage'], $this->groupSize);
            $this->templateLang['nextGroupPage'] = $this->vsprintf($this->templateLang['nextGroupPage'], $this->groupSize);
            $preGroupPageHtml = $this->statusHtml('preGroupPage', 'preGroupPage');
            $nextGroupPageHtml = $this->statusHtml('nextGroupPage', 'nextGroupPage');
        }

        if ($this->templateFlag['showLoop']) {
            $loopPageHtml = $this->statusHtml('loopPage', 'firstPage');
        }

        if ($this->templateFlag['showTotalPage']) {
            $this->templateLang['totalPage'] = $this->vsprintf($this->templateLang['totalPage'], $this->totalPage);
            $totalPageHtml = $this->statusHtml('totalPage', 'totalPage');
        }

        if ($this->templateFlag['showTotalNum']) {
            $this->templateLang['totalNum'] = $this->vsprintf($this->templateLang['totalNum'], $this->totalNum);
            $totalNumHtml = $this->statusHtml('totalNum', 'totalNum');
        }

        $html = implode(
            '',
            [
                $firstPageHtml,
                $preGroupPageHtml,
                $prePageHtml,
                $this->pageItemsHtml(),
                $nextPageHtml,
                $nextGroupPageHtml,
                $lastPageHtml,
                $loopPageHtml,
                $totalPageHtml,
                $totalNumHtml,
            ]
        );

        return vsprintf($this->template['pageWrapper'], $html);
    }


    /**
     * merge configure with default
     *
     * @param array $default default config
     * @param array $config
     * @return array
     */
    private function merge($default = [], $config = [])
    {
        foreach ($default as $k => $v) {
            if (isset($config[$k])) {
                $default[$k] = $config[$k];
            }
        }

        return $default;
    }


    /**
     * get page list items
     *
     * @return string
     */
    private function pageItemsHtml()
    {
        if ($this->totalPage < 1) {
            return '';
        }

        $startPage = ($this->currentGroup - 1) * $this->groupSize + 1;
        $endPage = $this->currentGroup * $this->groupSize;
        if ($endPage > $this->lastPage) {
            $endPage = $this->lastPage;
        }
        $html = '';
        for (; $startPage <= $endPage; $startPage++) {
            if ($startPage == $this->page) {
                $html .= $this->vsprintf($this->template['currentPage'], $this->templateLang['currentPage'], $startPage);
            } else {
                $page = $this->vsprintf($this->templateLang['pageItem'], $startPage);
                $html .= $this->vsprintf($this->template['pageItem'], $page, $startPage);
            }
        }

        return $html;
    }


    /**
     * get html in different status
     *
     * @param string $key
     * @param string $keyPage
     * @param string $comparePage
     * @return string
     */
    private function statusHtml($key = 'firstPage', $keyPage = 'firstPage', $comparePage = 'page')
    {
        if (is_array($this->template[$key])) {
            $htmlIndex = 0;
            if ($this->$keyPage == 0 || $this->$keyPage == $this->$comparePage) {
                $htmlIndex = 1;
            }

            return $this->vsprintf($this->template[$key][$htmlIndex], $this->templateLang[$key], $this->$keyPage);
        } else {
            return $this->vsprintf($this->template[$key], $this->templateLang[$key], $this->$keyPage);
        }
    }


    /**
     * customer vsprintf method
     *
     * @param $template
     * @param $lang
     * @param int $page
     * @return string
     */
    private function vsprintf($template, $lang, $page = 0)
    {
        if ($page == 0) {
            $page = $this->page;
        }

        $count = preg_match_all('/%s/', $template, $matches);
        if ($count < 1) {
            return $template;
        }

        $args = [$lang];
        if ($count > 1) {
            $args = array_pad($args, -$count, $page);
        }

        return vsprintf($template, $args);
    }


    /**
     * add page link to template
     *
     * @param string $pageLink
     */
    private function addPageLink($pageLink = '')
    {
        foreach ($this->template as $k => $v) {

            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    $this->template[$k][$k2] = str_replace('href="%s"', 'href="' . $pageLink . '"', $v2);
                }
            } else {
                $this->template[$k] = str_replace('href="%s"', 'href="' . $pageLink . '"', $v);
            }
        }

    }
}