<?php
// require_once ('../includes/db.php');
// require 'includes/session.php';

class Category {
    private $category_id;
    private $name;
    private $db ;

    public function __construct($category_id, $name) {
        $this->category_id = $category_id;
        $this->name = $name;
        $this->db = Database::getInstance();
    }

    public function getCategoryId() {
        return $this->category_id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function save() {
        if ($this->category_id) {
            $stmt = $this->db->prepare("UPDATE categories SET name = :name WHERE category_id = :category_id");
            $stmt->execute([
                'name' => $this->name,
                'category_id' => $this->category_id
            ]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (:name)");
            $stmt->execute(['name' => $this->name]);
            $this->category_id = $this->db->lastInsertId();
        }
    }

    public static function delete($category_id) {
        $stmt = Database::getInstance()->prepare("DELETE FROM categories WHERE category_id = :category_id");
        return $stmt->execute(['category_id' => $category_id]);
    }

    public static function getCategoryById($category_id) {
        $stmt = database::getInstance()->prepare("SELECT * FROM categories WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $category_id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($category) {
            return new Category($category['category_id'], $category['name']);
        }
        return null;
    }

    public static function getAllCategories() {
        $stmt = Database::getInstance()->prepare("SELECT * FROM categories");
        $stmt->execute();
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category($row['category_id'], $row['name']);
        }
        return $categories;
    }

    public function totalCourses() {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total_courses FROM courses WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $this->category_id]);
        return $stmt->fetchColumn();

}
}
?>