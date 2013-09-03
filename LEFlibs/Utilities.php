<?php
    class Utilities
    {
        public static function normalizeArray($arraysource = array(), &$arraytarget = array(), $globalkey = false)
        {
            if($globalkey)
            {
                $globalkey = $globalkey.'.';
            }
            else
            {
                $globalkey = '';
            }
            
            foreach($arraysource as $key => $value)
            {
                if(is_array($value))
                {
                    self::normalizeArray($value, $arraytarget, $globalkey.$key);
                }
                else
                {
                    $arraytarget[$globalkey.$key] = $value;
                }
            }
        }
    }