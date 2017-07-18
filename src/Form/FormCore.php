<?php

/**
 * Author: Carl Liu<coolboy@outlook.com>
 * Complete Date: 2017/07/18 19:58
 * Change record:
 *   Date:
 *   Version:
 *   Author:
 *   Content:
 */

namespace Cool\Common\Form;

class FormCore
{
    private $templates = [];
    private $data = [];
    private $html = [];

    public function __construct($config = [])
    {
        $this->setTemplates($config);
    }

    public function setTemplates($templates)
    {
        $this->templates = array_merge($this->templates, $templates);

        return $this;
    }

    public function addData($data)
    {
        $this->data[] = $data;

        return $this;
    }

    public function addMultiData($data)
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function render()
    {
        foreach ($this->data as $item) {
            $html = $this->renderElement($item);
            if($html) {
                $this->html[] = $html;
            }
        }

        return implode(PHP_EOL, $this->html);
    }

    public function renderElement($element)
    {
        $html = '';
        list ($type, $value) = $element;
        if ( ! empty($this->templates[$type])) {
            switch ($type) {
                case 'text':
                    $html = $this->renderCommon($this->templates[$type], $value);
                    break;
                case 'select':
                    $html = $this->renderSelect($this->templates[$type], $value);
                    break;
                case 'radio':
                    break;
                default:
                    $html = $this->renderCommon($this->templates[$type], $value);
                    break;
            }
        }

        return $html;
    }

    public function getTemplates()
    {
        return $this->templates;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function flushData()
    {
        $this->data = $this->html = [];
        return $this;
    }

    public function flushAll()
    {
        $this->templates = $this->data = $this->html = [];
        return $this;
    }

    public function renderCommon($template, $data)
    {
        return vsprintf($template, $data);
    }

//    public function formatSelectOptions($options, $selected = '')
//    {
//        $data = [];
//        foreach ($options as $value => $text) {
//            if ($value == $selected) {
//                $data[] = [' selected="selected"', $value, $text];
//            } else {
//                $data[] = ['', $value, $text];
//            }
//        }
//
//        return $data;
//    }

    public function renderSelect($template, $data)
    {
        list($selectOpenTpl, $optionTpl, $selectEndTpl) = $template;
        list($open, $options, $end) = $data;
        // format html
        $html[] = $this->renderCommon($selectOpenTpl, $open);
        foreach ($options as $item) {
            $html[] = $this->renderCommon($optionTpl, $item);
        }
        $html[] = $this->renderCommon($selectEndTpl, $end);

        return implode(PHP_EOL, $html);
    }
}