<?php
/**
 * Created by PhpStorm.
 * User: Muhannad Shelleh <muhannad.shelleh@live.com>
 * Date: 6/24/17
 * Time: 2:58 PM
 */

namespace ItvisionSy\Tree;

class Tree implements \IteratorAggregate, \JsonSerializable, \ArrayAccess
{

    /** @var TreeNode[] */
    protected $roots = [];

    /** @var TreeNode[] */
    protected $nodes = [];

    public function insert(TreeNode $node, $parentId = null)
    {
        if (!$parentId) {
            $this->roots[] = $node;
        } elseif (!array_key_exists($parentId, $this->nodes)) {
            throw new \Exception("Parent node does not exist");
        } else {
            $this->nodes[$parentId]->addChild($node);
        }
        $this->nodes[$node->getId()] = $node;
        return $this;
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->roots);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function (TreeNode $node) {
            return $node->toArray();
        }, $this->roots);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->nodes);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->nodes[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @throws \Exception
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        throw new \Exception("Use the insert function");
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @throws \Exception
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        throw new \Exception("Not implemented");
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return json_encode($this->toArray());
    }
}