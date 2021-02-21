<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Cache;

class RuntimeObjectCache
{
    /**
     * Holds the cached objects.
     *
     * @var array
     */
    private $cache = array();

    /**
     * The amount of times the cache data was already stored in the cache.
     *
     * @var int
     */
    public $cache_hits = 0;

    /**
     * Amount of times the cache did not have the request in cache.
     *
     * @var int
     */
    public $cache_misses = 0;

    /**
     * List of global cache groups.
     *
     * @var array
     */
    protected $global_groups = array();

    /**
     * The blog prefix to prepend to keys in non-global groups.
     *
     * @var string
     */
    private $blog_prefix;

    /**
     * Holds the value of is_multisite().
     *
     * @var bool
     */
    private $multisite;

    /**
     * Sets up object properties; PHP 5 style constructor.
     *
     */
    public function __construct()
    {
        $this->multisite = is_multisite();
        $this->blog_prefix = $this->multisite ? get_current_blog_id() . ':' : '';
    }

    /**
     * Adds data to the cache if it doesn't already exist.
     *
     * @param int|string $key What to call the contents in the cache.
     * @param mixed $data The contents to store in the cache.
     * @param string $group Optional. Where to group the cache contents. Default 'default'.
     * @param int $expire Optional. When to expire the cache contents. Default 0 (no expiration).
     * @return bool True on success, false if cache key and group already exist.
     * @uses WP_Object_Cache::set()     Sets the data after the checking the cache
     *                                  contents existence.
     *
     * @since 2.0.0
     *
     * @uses WP_Object_Cache::_exists() Checks to see if the cache already has data.
     */
    public function add($key, $data, $group = 'default', $expire = 0)
    {
        if (wp_suspend_cache_addition()) {
            return false;
        }

        if (empty($group)) {
            $group = 'default';
        }

        $id = $key;
        if ($this->multisite && !isset($this->global_groups[$group])) {
            $id = $this->blog_prefix . $key;
        }

        if ($this->_exists($id, $group)) {
            return false;
        }

        return $this->set($key, $data, $group, (int)$expire);
    }

    /**
     * Removes the contents of the cache key in the group.
     *
     * If the cache key does not exist in the group, then nothing will happen.
     *
     * @param int|string $key What the contents in the cache are called.
     * @param string $group Optional. Where the cache contents are grouped. Default 'default'.
     * @return bool False if the contents weren't deleted and true on success.
     */
    public function delete($key, $group = 'default')
    {
        if (empty($group)) {
            $group = 'default';
        }

        if ($this->multisite && !isset($this->global_groups[$group])) {
            $key = $this->blog_prefix . $key;
        }

        if (!$this->_exists($key, $group)) {
            return false;
        }

        unset($this->cache[$group][$key]);
        return true;
    }

    /**
     * Clears the object cache of all data.
     *
     * @return true Always returns true.
     */
    public function flush()
    {
        $this->cache = array();

        return true;
    }

    /**
     * Retrieves the cache contents, if it exists.
     *
     * The contents will be first attempted to be retrieved by searching by the
     * key in the cache group. If the cache is hit (success) then the contents
     * are returned.
     *
     * On failure, the number of cache misses will be incremented.
     *
     * @param int|string $key The key under which the cache contents are stored.
     * @param string $group Optional. Where the cache contents are grouped. Default 'default'.
     * @param bool $force Optional. Unused. Whether to force an update of the local cache
     *                          from the persistent cache. Default false.
     * @param bool $found Optional. Whether the key was found in the cache (passed by reference).
     *                          Disambiguates a return of false, a storable value. Default null.
     * @return mixed|false The cache contents on success, false on failure to retrieve contents.
     */
    public function get($key, $group = 'default', $force = false, &$found = null)
    {
        if (empty($group)) {
            $group = 'default';
        }

        if ($this->multisite && !isset($this->global_groups[$group])) {
            $key = $this->blog_prefix . $key;
        }

        if ($this->_exists($key, $group)) {
            $found = true;
            $this->cache_hits += 1;
            if (is_object($this->cache[$group][$key])) {
                return clone $this->cache[$group][$key];
            } else {
                return $this->cache[$group][$key];
            }
        }

        $found = false;
        $this->cache_misses += 1;

        return false;
    }

    /**
     * Retrieves multiple values from the cache in one call.
     *
     * @param array $keys Array of keys under which the cache contents are stored.
     * @param string $group Optional. Where the cache contents are grouped. Default 'default'.
     * @param bool $force Optional. Whether to force an update of the local cache
     *                      from the persistent cache. Default false.
     * @return array Array of values organized into groups.
     */
    public function get_multiple($keys, $group = 'default', $force = false)
    {
        $values = array();

        foreach ($keys as $key) {
            $values[$key] = $this->get($key, $group, $force);
        }

        return $values;
    }

    /**
     * Replaces the contents in the cache, if contents already exist.
     *
     * @param int|string $key What to call the contents in the cache.
     * @param mixed $data The contents to store in the cache.
     * @param string $group Optional. Where to group the cache contents. Default 'default'.
     * @param int $expire Optional. When to expire the cache contents. Default 0 (no expiration).
     * @return bool False if not exists, true if contents were replaced.
     * @see WP_Object_Cache::set()
     *
     */
    public function replace($key, $data, $group = 'default', $expire = 0)
    {
        if (empty($group)) {
            $group = 'default';
        }

        $id = $key;
        if ($this->multisite && !isset($this->global_groups[$group])) {
            $id = $this->blog_prefix . $key;
        }

        if (!$this->_exists($id, $group)) {
            return false;
        }

        return $this->set($key, $data, $group, (int)$expire);
    }

    /**
     * Sets the data contents into the cache.
     *
     * The cache contents are grouped by the $group parameter followed by the
     * $key. This allows for duplicate IDs in unique groups. Therefore, naming of
     * the group should be used with care and should follow normal function
     * naming guidelines outside of core WordPress usage.
     *
     * The $expire parameter is not used, because the cache will automatically
     * expire for each time a page is accessed and PHP finishes. The method is
     * more for cache plugins which use files.
     *
     * @param int|string $key What to call the contents in the cache.
     * @param mixed $data The contents to store in the cache.
     * @param string $group Optional. Where to group the cache contents. Default 'default'.
     * @return true Always returns true.
     */
    public function set($key, $data, $group = 'default')
    {
        if (empty($group)) {
            $group = 'default';
        }

        if ($this->multisite && !isset($this->global_groups[$group])) {
            $key = $this->blog_prefix . $key;
        }

        if (is_object($data)) {
            $data = clone $data;
        }

        $this->cache[$group][$key] = $data;
        return true;
    }

    /**
     * Serves as a utility function to determine whether a key exists in the cache.
     *
     * @param int|string $key Cache key to check for existence.
     * @param string $group Cache group for the key existence check.
     * @return bool Whether the key exists in the cache for the given group.
     *
     */
    protected function _exists($key, $group)
    {
        return isset($this->cache[$group]) && (isset($this->cache[$group][$key]) || array_key_exists($key, $this->cache[$group]));
    }
}