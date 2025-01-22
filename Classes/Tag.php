<?php
// require_once '../includes/db.php';
// require 'includes/session.php';
class Tag {
    private $tag_id;
    private $name;
    private $db;

    public function __construct($tag_id, $name) {
        $this->tag_id = $tag_id;
        $this->name = $name;
        $this->db = Database::getInstance();
    }

    public function getTagId() {
        return $this->tag_id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public static function getTagsByCourseId($course_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT t.* FROM tags t
                              JOIN course_tags ct ON t.tag_id = ct.tag_id
                              WHERE ct.course_id = :course_id");
        $stmt->execute(['course_id' => $course_id]);
        $tagsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tags = [];
        foreach ($tagsData as $tagData) {
            $tag = new Tag($tagData['tag_id'],$tagData['name']);
            $tags[] = $tag;
        }

        return $tags;
    }
    
    public function save() {
        if ($this->tag_id) {
            $stmt = $this->db->prepare("UPDATE tags SET name = :name WHERE tag_id = :tag_id");
            $stmt->execute([
                'name' => $this->name,
                'tag_id' => $this->tag_id
            ]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO tags (name) VALUES (:name)");
            $stmt->execute(['name' => $this->name]);
            $this->tag_id = $this->db->lastInsertId();
        }
    }

    public static function delete($tag_id) {
        $stmt = Database::getInstance()->prepare("DELETE FROM tags WHERE tag_id = :tag_id");
        $stmt->execute(['tag_id' => $tag_id]);
    }

    public static function getTagById($tag_id) {
        $stmt = Database::getInstance()->prepare("SELECT * FROM tags WHERE tag_id = :tag_id");
        $stmt->execute(['tag_id' => $tag_id]);
        $tag = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($tag) {
            return new Tag($tag['tag_id'], $tag['name']);
        }
        return null;
    }

    public static function getAllTags() {
        $stmt = database::getInstance()->prepare("SELECT * FROM tags");
        $stmt->execute();
        $tags = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tags[] = new Tag($row['tag_id'], $row['name']);
        }
        return $tags;
    }
}
?>