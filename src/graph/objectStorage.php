<?php
class ObjectStorage extends SplObjectStorage {
    public function getHash($object) {
        return $object->hashCode();
    }
}
?>