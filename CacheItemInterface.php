<?php

namespace Psr\Cache;

/**
 * CacheItemInterface defines an interface for interacting with objects inside a cache.
 *
 * CacheItemInterface defines an item inside a cache system.  Each Item object
 * MUST be associated with a specific key, which can be set according to the
 * implementing system and is typically passed by the Cache\PoolInterface object.
 *
 * The Cache\CacheItemInterface object encapsulates the storage and retrieval of
 * cache items. Each Cache\ItemInterface is generated by a Cache\PoolInterface
 * object, which is responsible for any required setup as well as associating
 * the object with a unique Key. Cache\ItemInterface objects MUST be able to
 * store and retrieve any type of PHP value defined in the Data section of this
 * document.
 *
 * Calling Libraries MUST NOT instantiate Item objects themselves. They may only
 * be requested from a Pool object via the getItem() method.  Calling Libraries
 * SHOULD NOT assume that an Item created by one Implementing Library is
 * compatible with a Pool from another Implementing Library.
 */
interface CacheItemInterface
{
    /**
     * Returns the key for the current cache item.
     *
     * The key is loaded by the Implementing Library, but should be available to
     * the higher level callers when needed.
     *
     * @return string
     *   The key string for this cache item.
     */
    public function getKey();

    /**
     * Retrieves the value of the item from the cache associated with this objects key.
     *
     * The value returned must be identical to the value original stored by set().
     *
     * if isHit() returns false, this method MUST return null. Note that null
     * is a legitimate cached value, so the isHit() method SHOULD be used to
     * differentiate between "null value was found" and "no value was found."
     *
     * @return mixed
     *   The value corresponding to this cache item's key, or null if not found.
     */
    public function get();

    /**
     * Sets the value represented by this cache item.
     *
     * The $value argument may be any item that can be serialized by PHP,
     * although the method of serialization is left up to the Implementing
     * Library.
     *
     * Implementing Libraries MAY provide a default TTL if one is not specified.
     * If no TTL is specified and no default TTL has been set, the TTL MUST
     * be set to the maximum possible duration of the underlying storage
     * mechanism, or permanent if possible.
     *
     * @param mixed $value
     *   The serializable value to be stored.
     * @param int|\DateTime $ttl
     *   - If an integer is passed, it is interpreted as the number of seconds
     *     after which the item MUST be considered expired.
     *   - If a DateTime object is passed, it is interpreted as the point in
     *     time after which the item MUST be considered expired.
     *   - If no value is passed, a default value MAY be used. If none is set,
     *     the value should be stored permanently or for as long as the
     *     implementation allows.
     * @return static
     *   The invoked object.
     */
    public function set($value, $ttl = null);

    /**
     * Confirms if the cache item lookup resulted in a cache hit.
     *
     * Note: This method MUST NOT have a race condition between calling isHit()
     * and calling get().
     *
     * @return boolean
     *   True if the request resulted in a cache hit.  False otherwise.
     */
    public function isHit();

    /**
     * Confirms if the cache item exists in the cache.
     *
     * Note: This method MAY avoid retrieving the cached value for performance
     * reasons, which could result in a race condition between exists() and get().
     * To avoid that potential race condition use isHit() instead.
     *
     * @return boolean
     *  True if item exists in the cache, false otherwise.
     */
    public function exists();

    /**
     * Sets the expiration time this cache item.
     *
     * @param \DateTime|\DateTimeImmutable $expiration
     *   The point in time after which the item MUST be considered expired.
     *   If null is passed explicitly, a default value MAY be used. If none is set,
     *   the value should be stored permanently or for as long as the
     *   implementation allows.
     *
     * @return static
     *   The called object.
     */
    public function expiresAt($expiration);

    /**
     * Sets the expiration time this cache item.
     *
     * @param int|\DateInterval $time
     *   The period of time from the present after which the item MUST be considered
     *   expired. An integer parameter is understood to be the time in seconds until
     *   expiration.
     *
     * @return static
     *   The called object.
     */
    public function expiresAfter($time);

}
