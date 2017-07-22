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

class FormHelper implements FormHelperInterface
{
    public static function formatData($data)
    {
        $result = [];
        $element = [];
        foreach ($data as $item) {
            switch ($item['type']) {
                case 'text':
                    $element = [$item['type'], [$item['name'], $item['value']]];
                    break;
                case 'select':
                    $options = [];
                    // value=showtext, twovalue=twotext
                    $addition = explode(',', trim($item['addition'], ','));
                    foreach ($addition as $optionItem) {
                        list($optionValue, $optionText) = explode('=', $optionItem);
                        $optionValue = trim($optionValue);
                        $optionText = trim($optionText);
                        $selected = $item['value'] == $optionValue ? ' selected="selected"' : '';
                        $options[] = [$selected, $optionValue, $optionText];
                    }
                    $element = [
                        'select',
                        [
                            [$item['name']],
                            $options,
                            []
                        ]
                    ];
                    break;
                case 'radio':
                    break;
                default:
                    $element = [$item['type'], [$item['name'], $item['value']]];
                    break;
            }

            $result[] = $element;
        }
        return $result;
    }


   /**
     * make option html
     *
     * @param $data e.g. ['k1' => $v1, 'k2' => 'v2']
     * @param $selected e.g. k1
     * @return string e.g. <option value="k1" selected="selected">v1</option><option value="k2">v2</option>
     */
    public static function makeSimpleOptionHtml($data, $selected)
    {
        $html = '';
        $template = '<option value="%s"%s>%s</option>';
        foreach ($data as $k => $v) {
            if($k == $selected) {
                $item = [$k, 'selected="selected"', $v];
            } else {
                $item = [$k, '', $v];
            }
            $html .= vsprintf($template, $item) . "\n";
        }

        return $html;
    }
}