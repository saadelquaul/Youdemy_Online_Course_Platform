<?php
class Category {
    private $category_id;
    private $name;
    private $db;

    // Constructor
    public function __construct($category_id, $name, $db) {
        $this->category_id = $category_id;
        $this->name = $name;
        $this->db = $db;
    }

    // Getters
    public function getCategoryId() {
        return $this->category_id;
    }

    public function getName() {
        return $this->name;
    }

    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    // Save the category to the database
    public function save() {
        if ($this->category_id) {
            // Update existing category
            $stmt = $this->db->prepare("UPDATE categories SET name = :name WHERE category_id = :category_id");
            $stmt->execute([
                'name' => $this->name,
                'category_id' => $this->category_id
            ]);
        } else {
            // Insert new category
            $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (:name)");
            $stmt->execute(['name' => $this->name]);
            $this->category_id = $this->db->lastInsertId();
        }
    }

    // Delete the category from the database
    public function delete() {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $this->category_id]);
    }

    // Static method to get a category by ID
    public static function getCategoryById($category_id, $db) {
        $stmt = $db->prepare("SELECT * FROM categories WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $category_id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($category) {
            return new Category($category['category_id'], $category['name'], $db);
        }
        return null;
    }

    // Static method to get all categories
    public static function getAllCategories($db) {
        $stmt = $db->prepare("SELECT * FROM categories");
        $stmt->execute();
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category($row['category_id'], $row['name'], $db);
        }
        return $categories;
    }
}
?>