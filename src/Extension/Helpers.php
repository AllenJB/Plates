<?php
declare(strict_types = 1);

namespace AllenJB\Plates\Extension;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class Helpers implements ExtensionInterface
{

    protected $engine;


    public function register(Engine $engine) : void
    {
        $this->engine = $engine;
        $engine->registerFunction('isChecked', [$this, 'isChecked']);
        $engine->registerFunction('selectOptions', [$this, 'selectOptions']);
        $engine->registerFunction('mouseover', [$this, 'mouseover']);
        $engine->registerFunction('selectColumnTitle', [$this, 'mouseoverColumnTitle']);
    }


    public function isChecked(string $key, array $array, bool $default = false) : string
    {
        $value = ($array[$key] ?? $default);
        if (!$value) {
            return '';
        }

        return ($value ? ' checked="checked" ' : '');
    }


    public function selectOptions(array $options, $selectedItem = null) : string
    {
        $retVal = '';
        foreach ($options as $value => $label) {
            $selected = ($selectedItem == $value ? ' selected="selected" ' : '');

            $retVal .= "<option value=\"" . $this->escapeHtml($value) . "\" {$selected}>"
                . $this->escapeHtml($label) . "</option>";
        }
        return $retVal;
    }


    public function mouseover($text, $powertipClass = 'powertip-container', $style = 'display: inline;') : string
    {
        $text = $this->escapeHtml($text);
        $retVal = <<<EOF
        <span class="{$powertipClass}" style="{$style}">
            <a class="powertip lightbulb" href="javascript:void(0);" title="{$text}">
                <img src="/images/icon/tip.png" border="0" alt="?" style="width: 16px;" /></a>
            </a>
        </span>
EOF;

        return $retVal;
    }

    public function mouseover_column_title($title, $tooltipText) : string
    {
        $title = nl2br($this->escapeHtml($title));
        $tooltipText = $this->escapeHtml($tooltipText);
        $retVal = <<<EOF
        <span class="powertip column-title" title="{$tooltipText}">{$title}</span>
EOF;

        return $retVal;
    }

    protected function escapeHtml($value) : string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

}
