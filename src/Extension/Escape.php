<?php
declare(strict_types = 1);

namespace AllenJB\Plates\Extension;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class Escape implements ExtensionInterface
{

    protected $engine;

    public function register(Engine $engine) : void
    {
        $this->engine = $engine;
        $engine->registerFunction('jsString', [$this, 'jsString']);
        $engine->registerFunction('js', [$this, 'js']);
    }


    public function jsString($value, $htmlEscape = true) : string
    {
        if (is_object($value) || is_array($value)) {
            trigger_error("Parameter 0 must be a string(-like) value)", E_USER_ERROR);
            return "";
        }

        $value = $this->jsonEncode("" . $value);
        // Remove the double-quotes
        $value = substr($value, 1, -1);
        if ($htmlEscape) {
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
        return $value;
    }


    public function js($value) : string
    {
        return $this->jsonEncode($value);
    }


    public function html($value) : string
    {
        $flags = ENT_QUOTES | (defined('ENT_SUBSTITUTE') ? ENT_SUBSTITUTE : 0);
        return htmlspecialchars($value, $flags, 'UTF-8');
    }


    protected function jsonEncode($value, $options = JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS) : string
    {
        $retval = json_encode($value, $options);

        if ($retval === false) {
            $msg = "Failed to encode as JSON";
            if (function_exists('json_last_error_msg')) {
                $msg .= ': ' . json_last_error_msg();
            }

            throw new \InvalidArgumentException($msg, json_last_error());
        }

        return $retval;
    }

}
