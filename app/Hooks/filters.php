<?php
function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    global $hh_filter;
    if (!isset($hh_filter[$tag])) {
        $hh_filter[$tag] = new Hook();
    }
    $hh_filter[$tag]->add_filter($tag, $function_to_add, $priority, $accepted_args);
    return true;
}

/**
 * Check if any filter has been registered for a hook.
 *
 * @since 2.5.0
 *
 * @global array $wp_filter Stores all of the filters.
 *
 * @param string $tag The name of the filter hook.
 * @param callable|bool $function_to_check Optional. The callback to check for. Default false.
 * @return false|int If $function_to_check is omitted, returns boolean for whether the hook has
 *                   anything registered. When checking a specific function, the priority of that
 *                   hook is returned, or false if the function is not attached. When using the
 *                   $function_to_check argument, this function may return a non-boolean value
 *                   that evaluates to false (e.g.) 0, so use the === operator for testing the
 *                   return value.
 */
function has_filter($tag, $function_to_check = false)
{
    global $hh_filter;

    if (!isset($hh_filter[$tag])) {
        return false;
    }

    return $hh_filter[$tag]->has_filter($tag, $function_to_check);
}

/**
 * Call the functions added to a filter hook.
 *
 * The callback functions attached to filter hook $tag are invoked by calling
 * this function. This function can be used to create a new filter hook by
 * simply calling this function with the name of the new hook specified using
 * the $tag parameter.
 *
 * The function allows for additional arguments to be added and passed to hooks.
 *
 *     // Our filter callback function
 *     function example_callback( $string, $arg1, $arg2 ) {
 *         // (maybe) modify $string
 *         return $string;
 *     }
 *     add_filter( 'example_filter', 'example_callback', 10, 3 );
 *
 *     /*
 *      * Apply the filters by calling the 'example_callback' function we
 *      * "hooked" to 'example_filter' using the add_filter() function above.
 *      * - 'example_filter' is the filter hook $tag
 *      * - 'filter me' is the value being filtered
 *      * - $arg1 and $arg2 are the additional arguments passed to the callback.
 *     $value = apply_filters( 'example_filter', 'filter me', $arg1, $arg2 );
 *
 * @since 0.71
 *
 * @global array $wp_filter Stores all of the filters.
 * @global array $wp_current_filter Stores the list of current filters with the current one last.
 *
 * @param string $tag The name of the filter hook.
 * @param mixed $value The value on which the filters hooked to `$tag` are applied on.
 * @param mixed $var,... Additional variables passed to the functions hooked to `$tag`.
 * @return mixed The filtered value after all hooked functions are applied to it.
 */
function apply_filters($tag, $value)
{
    global $hh_filter, $hh_current_filter;

    $args = array();

    // Do 'all' actions first.
    if (isset($hh_filter['all'])) {
        $hh_current_filter[] = $tag;
        $args = func_get_args();
        _wp_call_all_hook($args);
    }

    if (!isset($hh_filter[$tag])) {
        if (isset($hh_filter['all'])) {
            array_pop($hh_current_filter);
        }
        return $value;
    }

    if (!isset($hh_filter['all'])) {
        $hh_current_filter[] = $tag;
    }

    if (empty($args)) {
        $args = func_get_args();
    }

    // don't pass the tag name to WP_Hook
    array_shift($args);

    $filtered = $hh_filter[$tag]->apply_filters($value, $args);

    array_pop($hh_current_filter);

    return $filtered;
}

/**
 * Execute functions hooked on a specific filter hook, specifying arguments in an array.
 *
 * @since 3.0.0
 *
 * @see apply_filters() This function is identical, but the arguments passed to the
 * functions hooked to `$tag` are supplied using an array.
 *
 * @global array $wp_filter Stores all of the filters
 * @global array $wp_current_filter Stores the list of current filters with the current one last
 *
 * @param string $tag The name of the filter hook.
 * @param array $args The arguments supplied to the functions hooked to $tag.
 * @return mixed The filtered value after all hooked functions are applied to it.
 */
function apply_filters_ref_array($tag, $args)
{
    global $hh_filter, $hh_current_filter;

    // Do 'all' actions first
    if (isset($hh_filter['all'])) {
        $hh_current_filter[] = $tag;
        $all_args = func_get_args();
        _wp_call_all_hook($all_args);
    }

    if (!isset($hh_filter[$tag])) {
        if (isset($hh_filter['all'])) {
            array_pop($hh_current_filter);
        }
        return $args[0];
    }

    if (!isset($hh_filter['all'])) {
        $hh_current_filter[] = $tag;
    }

    $filtered = $hh_filter[$tag]->apply_filters($args[0], $args);

    array_pop($hh_current_filter);

    return $filtered;
}

/**
 * Removes a function from a specified filter hook.
 *
 * This function removes a function attached to a specified filter hook. This
 * method can be used to remove default functions attached to a specific filter
 * hook and possibly replace them with a substitute.
 *
 * To remove a hook, the $function_to_remove and $priority arguments must match
 * when the hook was added. This goes for both filters and actions. No warning
 * will be given on removal failure.
 *
 * @since 1.2.0
 *
 * @global array $wp_filter Stores all of the filters
 *
 * @param string $tag The filter hook to which the function to be removed is hooked.
 * @param callable $function_to_remove The name of the function which should be removed.
 * @param int $priority Optional. The priority of the function. Default 10.
 * @return bool    Whether the function existed before it was removed.
 */
function remove_filter($tag, $function_to_remove, $priority = 10)
{
    global $hh_filter;

    $r = false;
    if (isset($hh_filter[$tag])) {
        $r = $hh_filter[$tag]->remove_filter($tag, $function_to_remove, $priority);
        if (!$hh_filter[$tag]->callbacks) {
            unset($hh_filter[$tag]);
        }
    }

    return $r;
}

/**
 * Remove all of the hooks from a filter.
 *
 * @since 2.7.0
 *
 * @global array $wp_filter Stores all of the filters
 *
 * @param string $tag The filter to remove hooks from.
 * @param int|bool $priority Optional. The priority number to remove. Default false.
 * @return true True when finished.
 */
function remove_all_filters($tag, $priority = false)
{
    global $hh_filter;

    if (isset($hh_filter[$tag])) {
        $hh_filter[$tag]->remove_all_filters($priority);
        if (!$hh_filter[$tag]->has_filters()) {
            unset($hh_filter[$tag]);
        }
    }

    return true;
}

/**
 * Retrieve the name of the current filter or action.
 *
 * @since 2.5.0
 *
 * @global array $wp_current_filter Stores the list of current filters with the current one last
 *
 * @return string Hook name of the current filter or action.
 */
function current_filter()
{
    global $hh_current_filter;
    return end($hh_current_filter);
}

/**
 * Retrieve the name of the current action.
 *
 * @since 3.9.0
 *
 * @return string Hook name of the current action.
 */
function current_action()
{
    return current_filter();
}

/**
 * Retrieve the name of a filter currently being processed.
 *
 * The function current_filter() only returns the most recent filter or action
 * being executed. did_action() returns true once the action is initially
 * processed.
 *
 * This function allows detection for any filter currently being
 * executed (despite not being the most recent filter to fire, in the case of
 * hooks called from hook callbacks) to be verified.
 *
 * @since 3.9.0
 *
 * @see current_filter()
 * @see did_action()
 * @global array $wp_current_filter Current filter.
 *
 * @param null|string $filter Optional. Filter to check. Defaults to null, which
 *                            checks if any filter is currently being run.
 * @return bool Whether the filter is currently in the stack.
 */
function doing_filter($filter = null)
{
    global $hh_current_filter;

    if (null === $filter) {
        return !empty($hh_current_filter);
    }

    return in_array($filter, $hh_current_filter);
}

/**
 * Retrieve the name of an action currently being processed.
 *
 * @since 3.9.0
 *
 * @param string|null $action Optional. Action to check. Defaults to null, which checks
 *                            if any action is currently being run.
 * @return bool Whether the action is currently in the stack.
 */
function doing_action($action = null)
{
    return doing_filter($action);
}

/**
 * Hooks a function on to a specific action.
 *
 * Actions are the hooks that the WordPress core launches at specific points
 * during execution, or when specific events occur. Plugins can specify that
 * one or more of its PHP functions are executed at these points, using the
 * Action API.
 *
 * @since 1.2.0
 *
 * @param string $tag The name of the action to which the $function_to_add is hooked.
 * @param callable $function_to_add The name of the function you wish to be called.
 * @param int $priority Optional. Used to specify the order in which the functions
 *                                  associated with a particular action are executed. Default 10.
 *                                  Lower numbers correspond with earlier execution,
 *                                  and functions with the same priority are executed
 *                                  in the order in which they were added to the action.
 * @param int $accepted_args Optional. The number of arguments the function accepts. Default 1.
 * @return true Will always return true.
 */
function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    return add_filter($tag, $function_to_add, $priority, $accepted_args);
}

/**
 * Execute functions hooked on a specific action hook.
 *
 * This function invokes all functions attached to action hook `$tag`. It is
 * possible to create new action hooks by simply calling this function,
 * specifying the name of the new hook using the `$tag` parameter.
 *
 * You can pass extra arguments to the hooks, much like you can with apply_filters().
 *
 * @since 1.2.0
 *
 * @global array $wp_filter Stores all of the filters
 * @global array $wp_actions Increments the amount of times action was triggered.
 * @global array $wp_current_filter Stores the list of current filters with the current one last
 *
 * @param string $tag The name of the action to be executed.
 * @param mixed $arg,... Optional. Additional arguments which are passed on to the
 *                        functions hooked to the action. Default empty.
 */
function do_action($tag, $arg = '')
{
    global $hh_filter, $hh_actions, $hh_current_filter;

    if (!isset($hh_actions[$tag])) {
        $hh_actions[$tag] = 1;
    } else {
        ++$hh_actions[$tag];
    }

    // Do 'all' actions first
    if (isset($hh_filter['all'])) {
        $hh_current_filter[] = $tag;
        $all_args = func_get_args();
        _wp_call_all_hook($all_args);
    }

    if (!isset($hh_filter[$tag])) {
        if (isset($hh_filter['all'])) {
            array_pop($hh_current_filter);
        }
        return;
    }

    if (!isset($hh_filter['all'])) {
        $hh_current_filter[] = $tag;
    }

    $args = array();
    if (is_array($arg) && 1 == count($arg) && isset($arg[0]) && is_object($arg[0])) { // array(&$this)
        $args[] =& $arg[0];
    } else {
        $args[] = $arg;
    }
    for ($a = 2, $num = func_num_args(); $a < $num; $a++) {
        $args[] = func_get_arg($a);
    }

    $hh_filter[$tag]->do_action($args);

    array_pop($hh_current_filter);
}

/**
 * Retrieve the number of times an action is fired.
 *
 * @since 2.1.0
 *
 * @global array $wp_actions Increments the amount of times action was triggered.
 *
 * @param string $tag The name of the action hook.
 * @return int The number of times action hook $tag is fired.
 */
function did_action($tag)
{
    global $hh_actions;

    if (!isset($hh_actions[$tag])) {
        return 0;
    }

    return $hh_actions[$tag];
}

/**
 * Execute functions hooked on a specific action hook, specifying arguments in an array.
 *
 * @since 2.1.0
 *
 * @see do_action() This function is identical, but the arguments passed to the
 *                  functions hooked to $tag< are supplied using an array.
 * @global array $wp_filter Stores all of the filters
 * @global array $wp_actions Increments the amount of times action was triggered.
 * @global array $wp_current_filter Stores the list of current filters with the current one last
 *
 * @param string $tag The name of the action to be executed.
 * @param array $args The arguments supplied to the functions hooked to `$tag`.
 */
function do_action_ref_array($tag, $args)
{
    global $hh_filter, $hh_actions, $hh_current_filter;

    if (!isset($hh_actions[$tag])) {
        $hh_actions[$tag] = 1;
    } else {
        ++$hh_actions[$tag];
    }

    // Do 'all' actions first
    if (isset($hh_filter['all'])) {
        $hh_current_filter[] = $tag;
        $all_args = func_get_args();
        _wp_call_all_hook($all_args);
    }

    if (!isset($hh_filter[$tag])) {
        if (isset($hh_filter['all'])) {
            array_pop($hh_current_filter);
        }
        return;
    }

    if (!isset($hh_filter['all'])) {
        $hh_current_filter[] = $tag;
    }

    $hh_filter[$tag]->do_action($args);

    array_pop($hh_current_filter);
}

/**
 * Check if any action has been registered for a hook.
 *
 * @since 2.5.0
 *
 * @see has_filter() has_action() is an alias of has_filter().
 *
 * @param string $tag The name of the action hook.
 * @param callable|bool $function_to_check Optional. The callback to check for. Default false.
 * @return bool|int If $function_to_check is omitted, returns boolean for whether the hook has
 *                  anything registered. When checking a specific function, the priority of that
 *                  hook is returned, or false if the function is not attached. When using the
 *                  $function_to_check argument, this function may return a non-boolean value
 *                  that evaluates to false (e.g.) 0, so use the === operator for testing the
 *                  return value.
 */
function has_action($tag, $function_to_check = false)
{
    return has_filter($tag, $function_to_check);
}

/**
 * Removes a function from a specified action hook.
 *
 * This function removes a function attached to a specified action hook. This
 * method can be used to remove default functions attached to a specific filter
 * hook and possibly replace them with a substitute.
 *
 * @since 1.2.0
 *
 * @param string $tag The action hook to which the function to be removed is hooked.
 * @param callable $function_to_remove The name of the function which should be removed.
 * @param int $priority Optional. The priority of the function. Default 10.
 * @return bool Whether the function is removed.
 */
function remove_action($tag, $function_to_remove, $priority = 10)
{
    return remove_filter($tag, $function_to_remove, $priority);
}

/**
 * Remove all of the hooks from an action.
 *
 * @since 2.7.0
 *
 * @param string $tag The action to remove hooks from.
 * @param int|bool $priority The priority number to remove them from. Default false.
 * @return true True when finished.
 */
function remove_all_actions($tag, $priority = false)
{
    return remove_all_filters($tag, $priority);
}

/**
 * Fires functions attached to a deprecated filter hook.
 *
 * When a filter hook is deprecated, the apply_filters() call is replaced with
 * apply_filters_deprecated(), which triggers a deprecation notice and then fires
 * the original filter hook.
 *
 * Note: the value and extra arguments passed to the original apply_filters() call
 * must be passed here to `$args` as an array. For example:
 *
 *     // Old filter.
 *     return apply_filters( 'wpdocs_filter', $value, $extra_arg );
 *
 *     // Deprecated.
 *     return apply_filters_deprecated( 'wpdocs_filter', array( $value, $extra_arg ), '4.9', 'wpdocs_new_filter' );
 *
 * @since 4.6.0
 *
 * @see _deprecated_hook()
 *
 * @param string $tag The name of the filter hook.
 * @param array $args Array of additional function arguments to be passed to apply_filters().
 * @param string $version The version of WordPress that deprecated the hook.
 * @param string $replacement Optional. The hook that should have been used. Default false.
 * @param string $message Optional. A message regarding the change. Default null.
 */
function apply_filters_deprecated($tag, $args, $version, $replacement = false, $message = null)
{
    if (!has_filter($tag)) {
        return $args[0];
    }

    _deprecated_hook($tag, $version, $replacement, $message);

    return apply_filters_ref_array($tag, $args);
}

/**
 * Fires functions attached to a deprecated action hook.
 *
 * When an action hook is deprecated, the do_action() call is replaced with
 * do_action_deprecated(), which triggers a deprecation notice and then fires
 * the original hook.
 *
 * @since 4.6.0
 *
 * @see _deprecated_hook()
 *
 * @param string $tag The name of the action hook.
 * @param array $args Array of additional function arguments to be passed to do_action().
 * @param string $version The version of WordPress that deprecated the hook.
 * @param string $replacement Optional. The hook that should have been used.
 * @param string $message Optional. A message regarding the change.
 */
function do_action_deprecated($tag, $args, $version, $replacement = false, $message = null)
{
    if (!has_action($tag)) {
        return;
    }

    _deprecated_hook($tag, $version, $replacement, $message);

    do_action_ref_array($tag, $args);
}

function _deprecated_hook($hook, $version, $replacement = null, $message = null)
{
    /**
     * Fires when a deprecated hook is called.
     *
     * @since 4.6.0
     *
     * @param string $hook The hook that was called.
     * @param string $replacement The hook that should be used as a replacement.
     * @param string $version The version of WordPress that deprecated the argument used.
     * @param string $message A message regarding the change.
     */
    do_action('deprecated_hook_run', $hook, $replacement, $version, $message);

    /**
     * Filters whether to trigger deprecated hook errors.
     *
     * @since 4.6.0
     *
     * @param bool $trigger Whether to trigger deprecated hook errors. Requires
     *                      `WP_DEBUG` to be defined true.
     */
    if (apply_filters('deprecated_hook_trigger_error', true)) {
        $message = empty($message) ? '' : ' ' . $message;
        if (!is_null($replacement)) {
            /* translators: 1: WordPress hook name, 2: version number, 3: alternative hook name */
            trigger_error(sprintf(__('%1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.'), $hook, $version, $replacement) . $message);
        } else {
            /* translators: 1: WordPress hook name, 2: version number */
            trigger_error(sprintf(__('%1$s is <strong>deprecated</strong> since version %2$s with no alternative available.'), $hook, $version) . $message);
        }
    }
}

function _wp_call_all_hook($args)
{
    global $hh_filter;

    $hh_filter['all']->do_all_hook($args);
}

function _wp_filter_build_unique_id($tag, $function, $priority)
{
    global $hh_filter;
    static $filter_id_count = 0;

    if (is_string($function)) {
        return $function;
    }

    if (is_object($function)) {
        // Closures are currently implemented as objects
        $function = array($function, '');
    } else {
        $function = (array)$function;
    }

    if (is_object($function[0])) {
        // Object Class Calling
        if (function_exists('spl_object_hash')) {
            return spl_object_hash($function[0]) . $function[1];
        } else {
            $obj_idx = get_class($function[0]) . $function[1];
            if (!isset($function[0]->wp_filter_id)) {
                if (false === $priority) {
                    return false;
                }
                $obj_idx .= isset($hh_filter[$tag][$priority]) ? count((array)$hh_filter[$tag][$priority]) : $filter_id_count;
                $function[0]->wp_filter_id = $filter_id_count;
                ++$filter_id_count;
            } else {
                $obj_idx .= $function[0]->wp_filter_id;
            }

            return $obj_idx;
        }
    } elseif (is_string($function[0])) {
        // Static Calling
        return $function[0] . '::' . $function[1];
    }
}
