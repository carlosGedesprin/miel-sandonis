<?php
namespace src\util;

class debug_utils
{

    public function __construct()
    {
    }

    /**
     * Prints the call stack to a file
     *
     *
     */
    function log_call_stack( $method )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/debug_utils_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
        $trace = debug_backtrace();
        $output = 'Call stack: '.$method.PHP_EOL;

        foreach ( $trace as $index => $frame )
        {
            $file = $frame['file'] ?? '[Unknown file]';
            $line = $frame['line'] ?? '[Unknows line]';
            $function = $frame['function'] ?? '[Anonymous function]';
            $class = $frame['class'] ?? '';
            $type = $frame['type'] ?? '';

            $args = [];
            if (isset($frame['args'])) {
                foreach ($frame['args'] as $arg) {
                    if (is_scalar($arg)) {
                        $args[] = var_export($arg, true);
                    } elseif (is_array($arg)) {
                        $args[] = 'array(' . count($arg) . ')';
                    } elseif (is_object($arg)) {
                        $args[] = 'object(' . get_class($arg) . ')';
                    } elseif (is_null($arg)) {
                        $args[] = 'null';
                    } else {
                        $args[] = gettype($arg);
                    }
                }
            }

            $arg_str = implode(', ', $args);

            $output .= sprintf(
                "%d %s(%s): %s%s%s(%s)\n",
                $index,
                $file,
                $line,
                $class,
                $type,
                $function,
                $arg_str
            );
        }
//$txt = 'Output '.$output.PHP_EOL; fwrite($myfile, $txt);
//fclose($myfile);
        return $output;
    }
}
