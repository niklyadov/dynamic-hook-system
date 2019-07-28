<?php
/*

@   Dynamic hook system, (с) 2019
@   Author: Anchovy
@   GitHub: //github.com/Anchovys/dynamic-hook-system
@   Source in: //github.com/Anchovys/cubsystem

*/

class Hooks {

    public $hooks = array();

    /**
     * Register function on your hook.
     * @param $hook - name of hook;
     * @param $func - name function (or anonymous function);
     * @param $class - class object, which to find.
     * @param $priory - run priority.
     * @return Not
    */
    public function register($hook = '', $func = '', $class = '', $priority = 10)
    {
        //check for data
        if (!$hook || !$func)
        {
            return false;
        }
        //to int
        $priory = intval($str);

        //minimum value = zero
        if($str < 0) {
            $str = 0;
        }

        if(is_string($func))
        {

            //try find methods

            if($class)
            {
                if( !method_exists($class, $func) )
                {
                    return false;
                }
            }
            else
            {
                if ( !function_exists($func) ) 
                {
                    return false;
                }
            }

        }

        $data = '';

        //if it`s anonymous function
        if(is_callable($func)) 
        {
            //get functuion object from data
            $data = $func;

            //set static name
            $func = 'anonymous_';

            //not need
            $priority = $class; //use class as priority
            $class = null;
        }

        //add to hooks array && sort
        $this->hooks[$hook][$func] = array (
            $priority,  //priority of this function (need for sort)
            $class,     //class
            $data       //addition data
        );
        arsort($this->hooks[$hook]);
        
    }

    /**
     * Execute all functions on this hook name
     * @param $hook - name of hook
     * @param $args - args for runnaible function
     * Return false on fail
     * @return Boolean
    */
    public function here($hook = '', $args = array())
    {
        if ($hook) 
        {
            $arr = array_keys($this->hooks);
            
            if ( in_array($hook, $arr) )
            {
                //all hooks which name
                foreach ( $this->hooks[$hook] as $func => $val)
                {
                    //getting class
                    $class = $val[1];

                    //additional param
                    $data = '';

                    //if we have a param - setting up
                    // '3' - min array length ( [0] - priority function; [1] - class; [2] - data )
                    if(count($val) == 3)
                    {
                        $data = $val[2];
                    }

                    //check for anonymous functuion
                    if($data && $func == 'anonymous_' &&  is_callable($data)) 
                    {
                        //try run
                        return call_user_func($data);
                    }
                    else if(is_string($func)) //default (non anonymous)
                    {
                        if($class) //if class setting
                        {
                            if( method_exists($class, $func) ) 
                            {
                                //run inside the class
                                return $class->$func($args);
                            }
                        }
                        else //class is not set
                        {
                            if ( function_exists($func) ) 
                            {   
                                //run outside the class
                                return $func($args);
                            }
                        }
                    } else //something else
                    {
                        return false; 
                    }   
                }
            }
        }

        return false;
        
    }


    /**
     * Check hook in the array of hooks
     * @param $hook - name of hook
     * @return Boolean
    */
    public function exists($hook = '')
    {
        if ($hook) 
        {
            $arr = array_keys($this->hooks);

            if ( in_array($hook, $arr) ) 
            {
                return true;
            }
        }

        return false;
    }

    /**
    * Remove function from hook
    * If some function not setup, remove all function from hook
    * @param $hook - name of hook
    * @param $func - name of function
    * @return Bool
    */
    public function remove($hook = '', $func = '')
    {
        if ($hook) 
        {
            $arr = array_keys($this->hooks);

            //checkup hook
            if ( in_array($hook, $arr) )
            {
                if ($func)
                {
                    // remove one function
                    unset($this->hooks[$hook][$func]);
                }
                else
                {
                    // remove all functuions
                    unset($this->hooks[$hook]);
                }

                return true;
            }
        }
        return false;
    }
}
