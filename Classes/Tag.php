<?php
class Tag {
    private $tag_id;
    private $name;
    private $db;

    // Constructor
    public function __construct($tag_id, $name, $db) {
        $this->tag_id = $tag_id;
        $this->name = $name;
        $this->db = $db;
    }

    // Getters
    public function getTagId() {
        return $this->tag_id;
    }

    public function getName() {
        return $this->name;
    }

    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    // Save the tag to the database
    public function save() {
        if ($this->tag_id) {
            // Update existing tag
            $stmt = $this->db->prepare("UPDATE tags SET name = :name WHERE tag_id = :tag_id");
            $stmt->execute([
                'name' => $this->name,
                'tag_id' => $this->tag_id
            ]);
        } else {
            // Insert new tag
            $stmt = $this->db->prepare("INSERT INTO tags (name) VALUES (:name)");
            $stmt->execute(['name' => $this->name]);
            $this->tag_id = $this->db->lastInsertId();
        }
    }

    // Delete the tag from the database
    public function delete() {
        $stmt = $this->db->prepare("DELETE FROM tags WHERE tag_id = :tag_id");
        $stmt->execute(['tag_id' => $this->tag_id]);
    }

    // Static method to get a tag by ID
    public static function getTagById($tag_id, $db) {
        $stmt = $db->prepare("SELECT * FROM tags WHERE tag_id = :tag_id");
        $stmt->execute(['tag_id' => $tag_id]);
        $tag = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($tag) {
            return new Tag($tag['tag_id'], $tag['name'], $db);
        }
        return null;
    }

    // Static method to get all tags
    public static function getAllTags($db) {
        $stmt = $db->prepare("SELECT * FROM tags");
        $stmt->execute();
        $tags = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tags[] = new Tag($row['tag_id'], $row['name'], $db);
        }
        return $tags;
    }
}
?>